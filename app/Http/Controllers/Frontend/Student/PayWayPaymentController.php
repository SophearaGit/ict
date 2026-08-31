<?php

namespace App\Http\Controllers\Frontend\Student;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\ICTCourseEnrollments;
use App\Models\ICTInvoice;
use App\Models\ICTPayments;
use App\Notifications\Admin\PaymentVerifiedNotification;
use App\Services\PayWayService;
use App\Services\TelegramService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PayWayPaymentController extends Controller
{
    public function __construct(
        protected readonly PayWayService $payway,
        protected readonly TelegramService $telegram,
    ) {
    }

    /**
     * Frontend polls this after the checkout widget closes. We don't trust
     * the widget's own success callback for anything financial — this
     * endpoint is the only thing allowed to flip payment_status to 'paid',
     * and it does so by asking PayWay directly for the transaction status.
     *
     * Route: GET /student/payment/{invoice}/status  (auth:web, check_role:student)
     */
    public function status(ICTInvoice $invoice): JsonResponse
    {
        abort_unless($invoice->student_id === auth()->id(), 403);

        if ($invoice->payment_status === 'paid') {
            return response()->json(['status' => 'paid', 'invoice' => $this->invoiceSummary($invoice)]);
        }

        if (empty($invoice->payway_tran_id)) {
            return response()->json(['status' => 'unpaid']);
        }

        $result = $this->payway->getTransactionDetail($invoice->payway_tran_id);

        if (($result['status'] ?? null) === 'APPROVED') {
            $this->markInvoicePaid($invoice, $result['data'] ?? []);
            return response()->json(['status' => 'paid', 'invoice' => $this->invoiceSummary($invoice->fresh())]);
        }

        return response()->json([
            'status' => strtolower($result['status'] ?? 'unpaid'),
        ]);
    }

    /**
     * Everything the Step 4 "Success" panel needs to render a full receipt
     * without another round trip once payment is confirmed.
     */
    private function invoiceSummary(ICTInvoice $invoice): array
    {
        $invoice->loadMissing(['course.schedule', 'course.instructor', 'student', 'payments']);
        $payment = $invoice->payments->last();
        $schedule = $invoice->course?->schedule;

        return [
            'invoice_code' => $invoice->invoice_code,
            'course_title' => $invoice->course->title ?? '—',
            'schedule' => $schedule
                ? trim($schedule->short_days . ' ' . $schedule->formatted_time . ($schedule->shift_label ? " ({$schedule->shift_label})" : ''))
                : 'Not scheduled yet',
            'student_name' => $invoice->student->name ?? '—',
            'payment_option' => $invoice->payment_option,
            'amount_paid' => number_format((float) $invoice->paid_amount, 2),
            'paid_at' => optional($invoice->paid_at)->format('d M Y, h:i A'),
            'tran_id' => $invoice->payway_tran_id,
            'gateway_reference' => $payment->gateway_reference ?? null,
            'gateway_approval_code' => $payment->gateway_approval_code ?? null,
            'receipt_url' => route('student.payment.receipt', $invoice->id),
        ];
    }

    /**
     * PayWay pushes a notification here (return_url, base64-encoded when
     * sent in the purchase request) once a transaction completes.
     *
     * We treat this purely as a "go check now" trigger rather than trusting
     * its payload directly — the tran_id it reports is looked up locally,
     * then confirmed against PayWay's own transaction-detail API before we
     * mark anything paid. This route must NOT sit behind auth/CSRF since
     * PayWay's servers call it directly, not the browser.
     *
     * Route: POST /payment/payway/callback (no auth, CSRF-exempt)
     */
    public function callback(Request $request): JsonResponse
    {
        $tranId = $request->input('tran_id') ?? $request->input('apv') ?? null;

        Log::info('PayWay callback received', $request->all());

        if (!$tranId) {
            return response()->json(['status' => 'ignored'], 200);
        }

        $invoice = ICTInvoice::where('payway_tran_id', $tranId)->first();

        if (!$invoice) {
            Log::warning('PayWay callback: no invoice found for tran_id', ['tran_id' => $tranId]);
            return response()->json(['status' => 'not_found'], 200);
        }

        if ($invoice->payment_status === 'paid') {
            return response()->json(['status' => 'already_paid'], 200);
        }

        $result = $this->payway->getTransactionDetail($tranId);

        if (($result['status'] ?? null) === 'APPROVED') {
            $this->markInvoicePaid($invoice, $result['data'] ?? []);
        }

        // Always 200 — PayWay only cares that we received the push.
        return response()->json(['status' => 'ok'], 200);
    }

    /**
     * Downloadable PDF receipt for the ABA PayWay payment — built from the
     * data we captured off PayWay's transaction-detail response, not
     * anything PayWay itself hosts (they don't expose a receipt/invoice
     * download endpoint).
     *
     * Assumes barryvdh/laravel-dompdf, matching the existing
     * resources/views/pdf/certificate.blade.php convention already used
     * for certificates in this app — adjust the Pdf::loadView() call below
     * if this project uses a different PDF package.
     *
     * Route: GET /student/payment/{invoice}/receipt (auth:web, check_role:student)
     */
    public function downloadReceipt(ICTInvoice $invoice)
    {
        abort_unless($invoice->student_id === auth()->id(), 403);
        abort_unless($invoice->payment_status === 'paid', 404);

        $invoice->loadMissing(['course.schedule', 'student', 'payments']);
        $payment = $invoice->payments->last();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.aba-receipt', [
            'invoice' => $invoice,
            'payment' => $payment,
        ]);

        return $pdf->download('receipt-' . $invoice->invoice_code . '.pdf');
    }

    private function markInvoicePaid(ICTInvoice $invoice, array $transactionData): void
    {
        $paidAmount = (float) ($transactionData['payment_amount'] ?? $invoice->total_amount);

        DB::transaction(function () use ($invoice, $transactionData, $paidAmount) {
            ICTPayments::firstOrCreate(
                ['invoice_id' => $invoice->id],
                [
                    'amount' => $paidAmount,
                    'note' => 'ABA PayWay — ' . ($transactionData['payment_type'] ?? 'online payment')
                        . (isset($transactionData['bank_ref']) ? ' (ref: ' . $transactionData['bank_ref'] . ')' : ''),
                    'paid_by' => $invoice->student_id,
                    'payment_method' => 'payway',
                    'gateway_reference' => $transactionData['bank_ref'] ?? null,
                    'gateway_approval_code' => $transactionData['apv'] ?? null,
                    'gateway_response' => $transactionData,
                    'paid_at' => now(),
                ]
            );

            $invoice->update([
                'paid_amount' => $paidAmount,
                'remaining_amount' => max(0, $invoice->total_amount - $paidAmount),
                'payment_status' => $paidAmount >= $invoice->total_amount ? 'paid' : 'half_paid',
                'paid_at' => now(),
                'payment_gateway' => 'payway',
            ]);

            ICTCourseEnrollments::firstOrCreate(
                ['student_id' => $invoice->student_id, 'course_id' => $invoice->course_id],
                ['enrolled_by' => $invoice->student_id, 'status' => 'active', 'enrolled_at' => now()]
            );

            Admin::all()->each(
                fn($admin) => $admin->notify(new PaymentVerifiedNotification(
                    invoice: $invoice,
                    hash: $transactionData['bank_ref'] ?? $invoice->payway_tran_id,
                    paidAmount: $paidAmount,
                ))
            );
        });

        $invoice->refresh();
        $invoice->loadMissing('course.schedule');
        $student = $invoice->student ?? auth()->user();
        $course = $invoice->course;
        $schedule = $course?->schedule;

        $scheduleLine = 'Not scheduled yet';
        if ($schedule) {
            $days = $schedule->short_days ?: '—';
            $time = $schedule->formatted_time ?: '';
            $shift = $schedule->shift_label ? " ({$schedule->shift_label})" : '';
            $scheduleLine = trim("{$days} {$time}{$shift}");
        }

        $paymentOptionLabel = match ($invoice->payment_option) {
            'full' => 'Full payment',
            'half' => 'Half payment',
            'multi' => 'Installments',
            'normal' => 'Normal',
            'free' => 'Free',
            'other' => 'Other',
            default => ucfirst((string) $invoice->payment_option) ?: '—',
        };

        $rows = [
            'Student' => $student->name,
            'Course' => $course->title ?? '—',
            'Schedule' => $scheduleLine,
            'Invoice' => $invoice->invoice_code,
            'Option' => $paymentOptionLabel,
            'Amount' => '$' . number_format($paidAmount, 2),
            'Tran ID' => $invoice->payway_tran_id,
            'Paid At' => now()->format('d M Y, h:i A'),
        ];

        $labelWidth = max(array_map('strlen', array_keys($rows))) + 1;
        $receipt = '';
        foreach ($rows as $label => $value) {
            $receipt .= sprintf(
                "%-{$labelWidth}s %s\n",
                $label . ':',
                htmlspecialchars((string) $value, ENT_QUOTES)
            );
        }

        $this->telegram->send(
            message: "✅ <b>Payment Confirmed — ABA PayWay</b>\n\n" .
                "<pre>{$receipt}</pre>\n" .
                "📌 <i>Review in the admin dashboard</i>",
            chatId: config('services.telegram.payment_verify_chat_id'),
            botToken: config('services.telegram.bot_token_payment_verify'),
        );
    }
}
