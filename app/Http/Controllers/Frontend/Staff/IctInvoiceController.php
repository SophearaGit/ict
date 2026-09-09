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
            // "payments.paidBy" is needed so the detail view can show who
            // recorded a cash payment (a staff member) vs. an ABA PayWay
            // payment (recorded automatically, paid_by is the student).
            // "staff" is the staff member who registered/created the
            // invoice (shown as "Registered by" on the detail view).
            // "items.course.schedule" is eager-loaded (rather than just
            // "items") since the course table renders each item's own
            // course + schedule.
            'invoice' => ICTInvoice::with(['student', 'staff', 'course.schedule', 'payments.paidBy', 'items.course.schedule'])->findOrFail($invoice_id),
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
    protected function recalculateInvoice(ICTInvoice $invoice): void
    {
        $totalPaid = (float) $invoice->payments()->sum('amount');
        $remaining = max(0, (float) $invoice->total_amount - $totalPaid);
        $invoice->update([
            'paid_amount' => $totalPaid,
            'remaining_amount' => $remaining,
            // The payment_status column only allows 'paid', 'half_paid',
            // 'unpaid', or 'free' (see the i_c_t_invoices migration) —
            // this used to write 'partial', an invalid value the blade
            // views never actually checked for, which meant a
            // partially-paid invoice's status badge silently fell back to
            // "Unpaid" here after any staff-recorded payment.
            'payment_status' => $remaining <= 0 ? 'paid' : ($totalPaid > 0 ? 'half_paid' : 'unpaid'),
            'paid_at' => $remaining <= 0 ? now() : $invoice->paid_at,
        ]);
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
            ->paginate(15);
        // Keep search term in pagination links
        $invoices->appends(['search' => $search]);
        return view('frontend.staff.pages.invoice', [
            'page_title' => 'ICT | STAFF | INVOICES',
            'invoices' => $invoices,
        ]);
    }
}
