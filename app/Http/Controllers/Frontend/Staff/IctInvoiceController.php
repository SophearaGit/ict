<?php
namespace App\Http\Controllers\Frontend\Staff;
use App\Http\Controllers\Controller;
use App\Models\ICTCourseEnrollments;
use App\Models\ICTInvoice;
use App\Models\ICTPayments;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\JsonResponse;
class IctInvoiceController extends Controller
{
    public function destroy(string $invoice_id): JsonResponse
    {
        $invoice = ICTInvoice::findOrFail($invoice_id);

        DB::beginTransaction();
        try {
            $studentId = $invoice->student_id;
            $courseId = $invoice->course_id;

            // Deletes the invoice; items and payments cascade automatically
            // via FK cascadeOnDelete().
            $invoice->delete();

            // Remove the matching enrollment so the student can be
            // re-registered for this course cleanly.
            ICTCourseEnrollments::where('student_id', $studentId)
                ->where('course_id', $courseId)
                ->delete();

            DB::commit();

            return response()->json([
                'message' => 'Invoice deleted. Redirecting to registration to re-enter correct details.',
                'student_id' => $studentId,
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            \Log::error($e);
            return response()->json(['message' => 'Failed to delete invoice.'], 500);
        }
    }
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
}
