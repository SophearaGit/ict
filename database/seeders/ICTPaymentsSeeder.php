<?php

namespace Database\Seeders;

use App\Models\ICTInvoice;
use App\Models\ICTPayments;
use Illuminate\Database\Seeder;

class ICTPaymentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * One payment row per invoice that actually has money on it
     * (paid_amount > 0), matching the settlement method ICTInvoiceSeeder
     * already picked: cash collected by staff, or an online
     * Bakong/PayWay payment with gateway reference details attached.
     */
    public function run(): void
    {
        ICTInvoice::where('paid_amount', '>', 0)->orderBy('id')->each(function (ICTInvoice $invoice): void {
            $isPayway = ! empty($invoice->payway_tran_id);
            $isBakong = ! empty($invoice->bakong_txn_ref);

            $method = $isPayway ? 'payway' : ($isBakong ? 'online' : 'cash');

            ICTPayments::updateOrCreate(
                ['invoice_id' => $invoice->id],
                [
                    'amount' => $invoice->paid_amount,
                    'payment_method' => $method,
                    'note' => $method === 'cash' ? 'Paid in cash at the office.' : null,
                    'paid_by' => $method === 'cash' ? $invoice->staff_id : null,
                    'paid_at' => $invoice->paid_at ?? now(),
                    'gateway_reference' => $isPayway
                        ? '100FT' . str_pad((string) $invoice->id, 8, '0', STR_PAD_LEFT)
                        : ($isBakong ? $invoice->bakong_txn_ref : null),
                    'gateway_approval_code' => $isPayway ? (string) random_int(100000, 999999) : null,
                    'gateway_response' => $isPayway || $isBakong ? [
                        'status' => 'success',
                        'tran_id' => $invoice->payway_tran_id ?? $invoice->bakong_txn_ref,
                        'amount' => $invoice->paid_amount,
                        'currency' => 'USD',
                    ] : null,
                ]
            );
        });
    }
}
