<?php

namespace App\Http\Controllers\Frontend\Staff;

use App\Http\Controllers\Controller;
use App\Models\ICTInvoice;
use App\Models\ICTPayments;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;

class IctInvoiceController extends Controller
{

    public function getInvoiceDetail(string $invoice_id): string
    {
        $data = [
            'invoice' => ICTInvoice::with(['student', 'course.schedule', 'payments', 'items'])->findOrFail($invoice_id),
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

    // public function updatePayment(Request $request, $id): JsonResponse
    // {
    //     dd($request->all(), $id);
    //     $invoice = ICTInvoice::findOrFail($id);

    //     $invoice->payment_status = 'paid';
    //     $invoice->paid_amount = $invoice->total_amount;
    //     $invoice->remaining_amount = 0;
    //     $invoice->paid_at = now();
    //     $invoice->save();

    //     return response()->json([
    //         'message' => 'Payment confirmed successfully',
    //     ]);
    // }

    public function updatePayment(Request $request, $id): JsonResponse
    {
        $invoice = ICTInvoice::findOrFail($id);

        DB::beginTransaction();

        try {

            $remaining = $invoice->remaining_amount;

            // Create payment record
            ICTPayments::create([
                'invoice_id' => $invoice->id,
                'amount' => $remaining,
                'payment_method' => 'cash',
                'paid_by' => Auth::id(),
                'paid_at' => now(),
                'note' => 'Final payment'
            ]);

            // Update invoice
            $invoice->paid_amount += $remaining;
            $invoice->remaining_amount = 0;
            $invoice->payment_status = 'paid';
            $invoice->paid_at = now();
            $invoice->save();

            DB::commit();

            return response()->json([
                'message' => 'Payment confirmed successfully'
            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'message' => 'Payment failed'
            ], 500);
        }
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
