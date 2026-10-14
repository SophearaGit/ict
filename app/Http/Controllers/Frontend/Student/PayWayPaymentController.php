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
     * endpoint is the only thing allowed to flip payment_status to 'paid'
     * (or 'half_paid' for the first Pay Half installment), and it does so
     * by asking PayWay directly for the transaction status.
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
            // A Pay Half invoice lands here right after its 1st installment
            // is confirmed — markInvoicePaid() clears payway_tran_id so the
            // next page load mints a fresh one for installment 2, but
            // nothing has re-generated that QR yet. Report the settled
            // half_paid state (with a receipt-ready summary) instead of
            // "unpaid", so the page can show "installment 1 of 2 paid"
            // rather than looking like no progress was made.
            return response()->json([
                'status' => $invoice->payment_status,
                'invoice' => $invoice->payment_status === 'half_paid' ? $this->invoiceSummary($invoice) : null,
            ]);
        }

        $result = $this->payway->getTransactionDetail($invoice->payway_tran_id);

        if (($result['status'] ?? null) === 'APPROVED') {
            $this->markInvoicePaid($invoice, $result['data'] ?? []);
            $fresh = $invoice->fresh();
            return response()->json(['status' => $fresh->payment_status, 'invoice' => $this->invoiceSummary($fresh)]);
        }

        return response()->json([
            'status' => strtolower($result['status'] ?? 'unpaid'),
        ]);
    }

    /**
     * Everything the Step 4 "Success" panel (or the Pay Half "installment
     * paid" prompt) needs to render without another round trip once a
     * payment is confirmed.
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
            // This receipt only ever renders invoices paid through the
            // checkout's hard-locked ABA KHQR channel (see
            // CourseEnrollmentController@startEnrollment's 'abapay_khqr'
            // payment_option request param), so we show the payment method
            // used rather than $invoice->payment_option — that column is an
            // unrelated discount/installment plan enum (full/half/multi/...)
            // and would confusingly show "full"/"half" here instead.
            'payment_option' => 'ABA KHQR',
            'amount_paid' => number_format((float) ($payment->amount ?? $invoice->paid_amount), 2),
            'paid_at' => optional($invoice->paid_at)->format('d M Y, h:i A'),
            'tran_id' => $invoice->payway_tran_id,
            'gateway_reference' => $payment->gateway_reference ?? null,
            'gateway_approval_code' => $payment->gateway_approval_code ?? null,
            'receipt_url' => route('student.payment.receipt', $invoice->id),
            // Pay Half-specific fields — unused by the fully-paid receipt,
            // but what the "installment 1 of 2 paid" prompt renders.
            'payment_status' => $invoice->payment_status,
            'plan' => $invoice->payment_option,
            'total_amount' => number_format((float) $invoice->total_amount, 2),
            'paid_amount_total' => number_format((float) $invoice->paid_amount, 2),
            'remaining_amount' => number_format((float) $invoice->remaining_amount, 2),
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

        // Looked up by the invoice's CURRENT payway_tran_id, which
        // markInvoicePaid() clears the moment an installment is confirmed —
        // so a late/duplicate callback for an already-settled tran_id
        // (installment 1's, say, once installment 2's tran_id has taken
        // its place) simply finds no matching invoice and is ignored,
        // rather than re-crediting a payment that already landed.
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
        abort_unless(in_array($invoice->payment_status, ['paid', 'half_paid'], true), 404);

        // Same relations the staff invoice partial loads (see
        // IctInvoicePaymentController/inv-body.blade.php) — the receipt is
        // meant to look just like it, down to the itemized course table and
        // full payment history, not just the single latest payment.
        $invoice->loadMissing(['course.schedule', 'student', 'items.course.schedule', 'payments.paidBy']);
        $payment = $invoice->payments->last();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.aba-receipt', [
            'invoice' => $invoice,
            'payment' => $payment,
        ]);

        return $pdf->download('receipt-' . $invoice->invoice_code . '.pdf');
    }

    /**
     * Marks one installment of an invoice as paid. For a Pay Full invoice
     * (or a Pay Half invoice's 2nd/final installment) this fully settles
     * it. For a Pay Half invoice's 1st installment, it only brings the
     * invoice to 'half_paid' — paid_amount accumulates across calls rather
     * than being overwritten, and a separate ICTPayments row is recorded
     * per installment (instead of reusing/skipping a single row) so both
     * payments show up on the receipt and admin side.
     */
    private function markInvoicePaid(ICTInvoice $invoice, array $transactionData): void
    {
        DB::transaction(function () use ($invoice, $transactionData) {
            // Re-fetch and lock the row inside the transaction rather than
            // trusting the (possibly stale, e.g. from a concurrent poll)
            // $invoice instance passed in — this is the source of truth for
            // how much is already paid before we add this installment to it.
            $locked = ICTInvoice::where('id', $invoice->id)->lockForUpdate()->first();

            if (!$locked || $locked->payment_status === 'paid') {
                // Already fully settled by a concurrent request — nothing
                // left to do, and crediting this installment again would
                // double-count it.
                return;
            }

            // Captured BEFORE the update() below, which nulls
            // payway_tran_id for a non-final installment (to force a fresh
            // one on the next page load) — this is the tran_id this
            // specific installment was actually paid against, and the only
            // fallback reference PaymentVerifiedNotification has when
            // PayWay's own response doesn't include a bank_ref. Reading it
            // from $locked AFTER update() would see the just-cleared null
            // instead (the bug that silently rolled back every half-plan
            // 1st-installment confirmation — see git history).
            $paidTranId = $locked->payway_tran_id;

            $installmentAmount = (float) ($transactionData['payment_amount'] ?? $locked->remaining_amount);
            $newPaidAmount = (float) $locked->paid_amount + $installmentAmount;
            $newRemaining = max(0, (float) $locked->total_amount - $newPaidAmount);
            $isFullySettled = $newRemaining <= 0.009; // guard against float dust, e.g. 59.999999999

            ICTPayments::create([
                'invoice_id' => $locked->id,
                'amount' => $installmentAmount,
                'note' => 'ABA PayWay — ' . ($transactionData['payment_type'] ?? 'online payment')
                    . (isset($transactionData['bank_ref']) ? ' (ref: ' . $transactionData['bank_ref'] . ')' : ''),
                'paid_by' => $locked->student_id,
                'payment_method' => 'payway',
                // PayWay's sandbox (and sometimes live) transaction-detail
                // response doesn't always include a bank_ref — falling back
                // to $paidTranId (the tran_id this installment was actually
                // paid against, captured above before it gets cleared)
                // means the receipt, admin notification, and Telegram
                // message always have SOMETHING traceable here instead of
                // a bare "—".
                'gateway_reference' => $transactionData['bank_ref'] ?? $paidTranId,
                'gateway_approval_code' => $transactionData['apv'] ?? null,
                'gateway_response' => $transactionData,
                'paid_at' => now(),
            ]);

            $locked->update([
                'paid_amount' => $newPaidAmount,
                'remaining_amount' => $newRemaining,
                'payment_status' => $isFullySettled ? 'paid' : 'half_paid',
                'paid_at' => now(),
                'payment_gateway' => 'payway',
                // Force a fresh, correctly-signed tran_id for the next
                // installment. Once fully settled this doesn't matter —
                // paymentPage() short-circuits on payment_status === 'paid'
                // before it would ever be read again.
                'payway_tran_id' => $isFullySettled ? $locked->payway_tran_id : null,
            ]);

            // Access is granted from the first payment that lands, even a
            // Pay Half deposit — matches this app's existing behavior for
            // staff-recorded partial payments (see IctInvoicePaymentController).
            ICTCourseEnrollments::firstOrCreate(
                ['student_id' => $locked->student_id, 'course_id' => $locked->course_id],
                ['enrolled_by' => $locked->student_id, 'status' => 'active', 'enrolled_at' => now()]
            );

            Admin::all()->each(
                fn($admin) => $admin->notify(new PaymentVerifiedNotification(
                    invoice: $locked,
                    hash: $transactionData['bank_ref'] ?? $paidTranId ?? 'N/A',
                    paidAmount: $installmentAmount,
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

        // Pay Half is the one plan where a single "payment confirmed" ping
        // isn't the whole story — an admin reading this in Telegram needs
        // to know at a glance whether the student just put down a deposit
        // (still owes the rest) or just cleared the final installment
        // (plan is now fully settled), not just "a payment happened".
        $isHalfPlan = $invoice->payment_option === 'half';
        $isFullySettled = $invoice->payment_status === 'paid';

        $paymentOptionLabel = match (true) {
            $isHalfPlan && $isFullySettled => 'Half payment — installment 2 of 2 (final)',
            $isHalfPlan && !$isFullySettled => 'Half payment — installment 1 of 2',
            $invoice->payment_option === 'full' => 'Full payment',
            $invoice->payment_option === 'multi' => 'Installments',
            $invoice->payment_option === 'normal' => 'Normal',
            $invoice->payment_option === 'free' => 'Free',
            $invoice->payment_option === 'other' => 'Other',
            default => ucfirst((string) $invoice->payment_option) ?: '—',
        };

        $latestPayment = $invoice->payments()->latest()->first();
        $justPaidAmount = (float) ($latestPayment->amount ?? $invoice->paid_amount);

        $rows = [
            'Student' => $student->name,
            'Course' => $course->title ?? '—',
            'Schedule' => $scheduleLine,
            'Invoice' => $invoice->invoice_code,
            'Plan' => $paymentOptionLabel,
            'Amount Paid Now' => '$' . number_format($justPaidAmount, 2),
            'Tran ID' => $latestPayment->gateway_reference ? $latestPayment->gateway_reference : '—',
            'Paid At' => now()->format('d M Y, h:i A'),
        ];

        // Full plan-cost context lives here too — not just whether this
        // one installment cleared, but where the whole invoice stands.
        if ($isHalfPlan) {
            $rows['Total Plan'] = '$' . number_format((float) $invoice->total_amount, 2);
            $rows['Remaining'] = $isFullySettled
                ? '$0.00 — fully settled'
                : '$' . number_format((float) $invoice->remaining_amount, 2);
        }

        // Pad the LABEL itself (not "label:") so the colons line up in one
        // column too, not just the values — e.g. "Amount Paid Now :" and
        // "Plan            :" sharing the same colon column reads far
        // cleaner in Telegram's monospace <pre> block than a ragged colon
        // right after each differently-sized label.
        $labelWidth = max(array_map('strlen', array_keys($rows)));
        $receipt = '';
        foreach ($rows as $label => $value) {
            $receipt .= sprintf(
                "%-{$labelWidth}s : %s\n",
                $label,
                htmlspecialchars((string) $value, ENT_QUOTES)
            );
        }

        // Three distinct states an admin can tell apart at a glance:
        // a one-shot full payment, a Pay Half deposit that still leaves a
        // balance due, and a Pay Half plan's final installment (which also
        // lands on payment_status 'paid', same as a full payment, but is
        // the end of a 2-payment plan rather than a single payment).
        $heading = match (true) {
            $isHalfPlan && !$isFullySettled => '🕒 <b>Installment 1 of 2 Received — ABA PayWay</b>',
            $isHalfPlan && $isFullySettled => '✅ <b>Final Installment Received — Plan Fully Paid — ABA PayWay</b>',
            default => '✅ <b>Payment Confirmed (Full) — ABA PayWay</b>',
        };

        $this->telegram->send(
            message: "{$heading}\n\n" .
                "<pre>{$receipt}</pre>\n" .
                "📌 <i>Review in the admin dashboard</i>",
            chatId: config('services.telegram.payment_verify_chat_id'),
            botToken: config('services.telegram.bot_token_payment_verify'),
        );
    }
}
