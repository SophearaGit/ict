<?php

namespace Database\Seeders;

use App\Models\ICTInvoice;
use App\Models\ICTInvoiceItems;
use Illuminate\Database\Seeder;

class ICTInvoiceItemsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * One line item per seeded invoice, mirroring the invoice's own
     * price/discount/extra_charge/total — these are single-course
     * invoices, so item totals always match the invoice total.
     */
    public function run(): void
    {
        ICTInvoice::orderBy('id')->each(function (ICTInvoice $invoice): void {
            ICTInvoiceItems::updateOrCreate(
                ['invoice_id' => $invoice->id, 'course_id' => $invoice->course_id],
                [
                    'price' => $invoice->price,
                    'discount' => $invoice->discount,
                    'extra_charge' => $invoice->extra_charge,
                    'total' => $invoice->total_amount,
                ]
            );
        });
    }
}
