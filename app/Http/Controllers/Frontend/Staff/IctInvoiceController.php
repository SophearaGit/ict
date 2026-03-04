<?php

namespace App\Http\Controllers\Frontend\Staff;

use App\Http\Controllers\Controller;
use App\Models\ICTInvoice;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

class IctInvoiceController extends Controller
{

    public function getInvoiceDetail(string $invoice_id): string
    {
        $data = [
            'invoice' => ICTInvoice::with(['student', 'course.schedule'])->findOrFail($invoice_id),
        ];
        return view('frontend.staff.pages.partials.inv-body', $data)->render();
    }

    public function confirmPayment(string $invoice_id): string
    {
        $data = [
            'invoice' => ICTInvoice::with(['student', 'course.schedule'])->findOrFail($invoice_id),
        ];
        return view('frontend.staff.pages.partials.inv-confirm-payment', $data)->render();
    }

    public function updatePayment(Request $request, $id): RedirectResponse
    {
        $invoice = ICTInvoice::findOrFail($id);

        $request->validate([
            'additional_payment' => 'required|numeric|min:0'
        ]);

        $additional = $request->additional_payment;

        if ($additional > $invoice->remaining_amount) {
            return back()->with('error', 'Payment exceeds remaining balance.');
        }

        $invoice->paid_amount += $additional;
        $invoice->remaining_amount -= $additional;

        if ($invoice->remaining_amount == 0) {
            $invoice->payment_status = 'paid';
        } else {
            $invoice->payment_status = 'half_paid';
        }

        $invoice->save();

        return back()->with('success', 'Payment updated successfully.');
    }


    public function invoices(): View
    {
        $data = [
            'page_title' => 'ICT Center | Invoices',
            'invoices' => ICTInvoice::with(['student', 'course'])
                ->latest()
                ->get(),
        ];
        return view('frontend.staff.pages.invoice', $data);
    }

}
