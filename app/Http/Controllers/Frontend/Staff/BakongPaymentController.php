<?php
namespace App\Http\Controllers\Frontend\Staff;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\ICTCourseEnrollments;
use App\Models\ICTInvoice;
use App\Models\ICTPayments;
use App\Notifications\Admin\PaymentVerifiedNotification;
use App\Services\BakongService;
use App\Services\TelegramService; // ← add this import
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BakongPaymentController extends Controller
{
    public function __construct(
        protected readonly BakongService $bakong,
        protected readonly TelegramService $telegram, // ← add this property
    ) {

    }
    public function generateQr(Request $request): JsonResponse
    {
        $request->validate([
            'invoice_id' => 'required|integer|exists:i_c_t_invoices,id',
            'currency' => 'nullable|in:USD,KHR',
        ]);
        $invoice = ICTInvoice::findOrFail($request->invoice_id);
        if ($invoice->payment_status === 'paid') {
            return response()->json(['success' => false, 'message' => 'Invoice already paid.'], 422);
        }
        if (auth()->user()->role === 'student' && $invoice->student_id !== auth()->id()) {
            abort(403);
        }
        $amount = (float) $invoice->total_amount;
        $currency = $request->currency ?? 'USD';
        $transactionId = 'INV-' . $invoice->id . '-' . strtoupper(Str::random(6));
        $result = $this->bakong->generateKHQR(
            merchantId: config('services.bakong.merchant_id', 'nhim_nhanh@bkrt'),
            merchantName: config('services.bakong.merchant_name', 'ICT Center'),
            city: 'Phnom Penh',
            amount: $amount,
            currency: $currency,
            description: $invoice->invoice_code,
            transactionId: $transactionId,
        );
        if (!($result['success'] ?? false)) {
            return response()->json([
                'success' => false,
                'message' => $result['message'] ?? 'Unable to generate QR',
            ], 500);
        }
        $qrImageUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data='
            . urlencode($result['qr']);
        $invoice->update(['bakong_txn_ref' => $transactionId]);
        return response()->json([
            'success' => true,
            'qr_image' => $qrImageUrl,
            'amount' => $amount,
            'currency' => $currency,
        ]);
    }
    public function verifyByHash(Request $request): JsonResponse
    {
        $request->validate([
            'hash' => 'required|string|size:8',
            'invoice_id' => 'required|integer|exists:i_c_t_invoices,id',
        ]);

        $invoice = ICTInvoice::findOrFail($request->invoice_id);

        if ($invoice->payment_status === 'paid') {
            return response()->json(['status' => 'success']);
        }

        if (auth()->user()->role === 'student' && $invoice->student_id !== auth()->id()) {
            abort(403);
        }

        $amount = (float) $invoice->total_amount;
        $currency = 'USD';

        $response = $this->bakong->checkTransactionByShortHash(
            hash: $request->hash,
            amount: $amount,
            currency: $currency,
        );

        if (($response['responseCode'] ?? 1) !== 0 || empty($response['data'])) {
            return response()->json([
                'status' => 'failed',
                'message' => $response['responseMessage']
                    ?? 'Transaction not found. Please check your Bakong hash and try again.',
            ]);
        }

        $txnData = $response['data'];
        $expectedMerchant = config('services.bakong.merchant_id');

        if (($txnData['toAccountId'] ?? '') !== $expectedMerchant) {
            return response()->json([
                'status' => 'failed',
                'message' => 'This transaction was not paid to the correct account.',
            ]);
        }

        $paidAmount = (float) ($txnData['amount'] ?? 0);

        if ($paidAmount < $amount) {
            return response()->json([
                'status' => 'failed',
                'message' => "Amount mismatch. Expected \${$amount}, received \${$paidAmount}.",
            ]);
        }

        DB::transaction(function () use ($invoice, $txnData, $request, $paidAmount) {
            ICTPayments::firstOrCreate(
                ['invoice_id' => $invoice->id],
                [
                    'amount' => $txnData['amount'] ?? $invoice->total_amount,
                    'note' => 'Bakong KHQR - hash: ' . $request->hash,
                    'paid_by' => $invoice->student_id,
                    'payment_method' => 'bank_transfer',
                    'paid_at' => now(),
                ]
            );

            $invoice->update([
                'paid_amount' => $txnData['amount'] ?? $invoice->total_amount,
                'remaining_amount' => 0,
                'payment_status' => 'paid',
                'paid_at' => now(),
                'bakong_hash' => $txnData['hash'] ?? $request->hash,
            ]);

            ICTCourseEnrollments::firstOrCreate(
                ['student_id' => $invoice->student_id, 'course_id' => $invoice->course_id],
                ['enrolled_by' => $invoice->student_id, 'status' => 'active', 'enrolled_at' => now()]
            );

            // ── In-system notification to all admins ──────────────────────
            Admin::all()->each(
                fn($admin) => $admin->notify(new PaymentVerifiedNotification(
                    invoice: $invoice,
                    hash: $request->hash,
                    paidAmount: $paidAmount,
                ))
            );
        });

        // ── Telegram notification (outside transaction — non-critical) ────
        $student = $invoice->student ?? auth()->user();

        $this->telegram->send(                                          // ← called here
            message: "✅ <b>Payment Confirmed</b>\n\n" .
            "👤 Student: <b>{$student->name}</b>\n" .
            "📄 Invoice: <b>{$invoice->invoice_code}</b>\n" .
            "💵 Amount: <b>\${$paidAmount}</b>\n" .
            "🔑 Hash: <code>{$request->hash}</code>\n" .
            "🕐 Time: " . now()->format('d M Y, H:i') . "\n\n" .
            "📌 Please review in the admin dashboard.",
            chatId: config('services.telegram.payment_verify_chat_id'),   // TELEGRAM_ADMIN_CHAT_ID_VERIFY_PAYMENT
            botToken: config('services.telegram.bot_token_payment_verify'), // TELEGRAM_BOT_TOKEN_VERIFY_PAYMENT
        );

        return response()->json(['status' => 'success']);
    }

}
