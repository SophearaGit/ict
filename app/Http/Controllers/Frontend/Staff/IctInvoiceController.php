<?php

namespace App\Http\Controllers\Frontend\Staff;

use App\Http\Controllers\Controller;
use App\Models\ICTInvoice;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
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

    public function updatePayment(Request $request, $id): JsonResponse
    {
        $invoice = ICTInvoice::findOrFail($id);

        $invoice->payment_status = 'paid';
        $invoice->paid_amount = $invoice->total_amount;
        $invoice->remaining_amount = 0;
        $invoice->paid_at = now();
        $invoice->save();

        return response()->json([
            'message' => 'Payment confirmed successfully',
        ]);
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
