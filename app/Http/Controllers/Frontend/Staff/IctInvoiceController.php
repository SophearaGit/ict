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
    public function updatePayment(Request $request, $id): JsonResponse
    {
        $invoice = ICTInvoice::findOrFail($id);
        $request->validate([
            'additional_payment' => 'required|numeric|min:0.01|max:' . $invoice->remaining_amount,
        ]);
        DB::beginTransaction();
        try {
            $payNow = (float) $request->additional_payment;
            ICTPayments::create([
                'invoice_id' => $invoice->id,
                'amount' => $payNow,
                'payment_method' => 'cash',
                'paid_by' => Auth::id(),
                'paid_at' => now(),
                'note' => $invoice->payments()->exists()
                    ? 'Additional payment'
                    : 'Initial payment during registration',
            ]);
            $this->recalculateInvoice($invoice);
            DB::commit();
            return response()->json(['message' => 'Payment confirmed successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Payment failed'], 500);
        }
    }
    public function invoices(Request $request): View
    {
        $search = $request->input('search');
        $invoices = ICTInvoice::with(['student', 'course'])
            ->when($search, function ($query, $search) {
                $query->where('invoice_code', 'like', "%{$search}%")->orWhereHas('student', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(5);
        // Keep search term in pagination links
        $invoices->appends(['search' => $search]);
        return view('frontend.staff.pages.invoice', [
            'page_title' => 'ICT | STAFF | INVOICES',
            'invoices' => $invoices,
        ]);
    }
    public function update(Request $request, string $invoice_id): JsonResponse
    {
        $invoice = ICTInvoice::with('items')->findOrFail($invoice_id);
        $request->validate([
            'price' => ['required', 'numeric', 'min:0'],
            'discount' => ['nullable', 'numeric', 'min:0'],
            'extra_charge' => ['nullable', 'numeric', 'min:0'],
        ]);
        $price = (float) $request->price;
        $discount = (float) ($request->discount ?? 0);
        $extraCharge = (float) ($request->extra_charge ?? 0);
        if ($discount > $price) {
            return response()->json([
                'message' => 'Discount cannot be greater than the price.',
            ], 422);
        }
        DB::beginTransaction();
        try {
            $total = ($price - $discount) + $extraCharge;
            foreach ($invoice->items as $item) {
                $item->update([
                    'price' => $price,
                    'discount' => $discount,
                    'extra_charge' => $extraCharge,
                    'total' => $total,
                ]);
            }
            $invoice->update([
                'price' => $price,
                'discount' => $discount,
                'extra_charge' => $extraCharge,
                'total_amount' => $total,
            ]);
            if ($invoice->payments()->count() === 1) {
                $payment = $invoice->payments()->first();
                $payment->update([
                    'amount' => $total,
                    'note' => 'Updated automatically after invoice correction',
                ]);
            }
            $this->recalculateInvoice($invoice);
            DB::commit();
            return response()->json([
                'message' => 'Invoice updated successfully.',
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            \Log::error($e);
            return response()->json([
                'message' => 'Failed to update invoice.',
            ], 500);
        }
    }
    public function edit(string $invoice_id): string
    {
        $invoice = ICTInvoice::with([
            'student',
            'course.schedule',
            'items.course.schedule',
            'payments',
        ])->findOrFail($invoice_id);
        return view(
            'frontend.staff.pages.partials.inv-edit',
            compact('invoice')
        )->render();
    }
    private function recalculateInvoice(ICTInvoice $invoice): void
    {
        $invoice->load('payments');
        $paidAmount = (float) $invoice->payments()->sum('amount');
        $total = (float) $invoice->total_amount;
        $remaining = max(0, $total - $paidAmount);
        if ($paidAmount <= 0) {
            $status = 'unpaid';
        } elseif ($paidAmount < $total) {
            $status = 'half_paid';
        } else {
            $status = 'paid';
        }
        $lastPayment = $invoice->payments()->latest('paid_at')->first();
        $invoice->update([
            'paid_amount' => $paidAmount,
            'remaining_amount' => $remaining,
            'payment_status' => $status,
            'paid_at' => $lastPayment?->paid_at,
        ]);
    }
}
