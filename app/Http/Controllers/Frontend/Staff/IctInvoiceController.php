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
    /**
     * Build the filtered invoice query shared by invoices() (paginated,
     * on-screen) and exportCsv() (unpaginated, full result set) so the
     * two can never silently drift out of sync on what counts as a
     * "match" for a given search/status/sort combination.
     */
    protected function filteredInvoicesQuery(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');
        $sort = $request->input('sort', 'newest');
        $query = ICTInvoice::with(['student', 'course'])
            ->when($search, function ($query, $search) {
                $query->where('invoice_code', 'like', "%{$search}%")->orWhereHas('student', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
            })
            ->when($status, function ($query, $status) {
                // "Awaiting" isn't a real payment_status value — it's the
                // stat-card/chip shorthand for "still needs follow-up",
                // i.e. unpaid OR half_paid combined.
                if ($status === 'awaiting') {
                    $query->whereIn('payment_status', ['unpaid', 'half_paid']);
                } else {
                    $query->where('payment_status', $status);
                }
            });
        match ($sort) {
            'oldest' => $query->oldest(),
            'amount_desc' => $query->orderByDesc('total_amount'),
            'amount_asc' => $query->orderBy('total_amount'),
            default => $query->latest(),
        };
        return $query;
    }
    public function invoices(Request $request): View
    {
        $search = $request->input('search');
        $status = $request->input('status');
        $sort = $request->input('sort', 'newest');
        $invoices = $this->filteredInvoicesQuery($request)->paginate(15);
        // Keep search/status/sort in pagination links
        $invoices->appends(['search' => $search, 'status' => $status, 'sort' => $sort]);
        // ── Summary stats (whole table, independent of the search/status
        //    filters above and of pagination) — gives staff an at-a-glance
        //    read of how many invoices still need payment follow-up,
        //    without having to page through the list to count them. ──
        $statusCounts = ICTInvoice::selectRaw('payment_status, COUNT(*) as c')
            ->groupBy('payment_status')
            ->pluck('c', 'payment_status');
        $paidCount = (int) ($statusCounts['paid'] ?? 0);
        $halfPaidCount = (int) ($statusCounts['half_paid'] ?? 0);
        $unpaidCount = (int) ($statusCounts['unpaid'] ?? 0);
        $freeCount = (int) ($statusCounts['free'] ?? 0);
        return view('frontend.staff.pages.invoice', [
            'page_title' => 'ICT | STAFF | INVOICES',
            'invoices' => $invoices,
            'current_status' => $status,
            'current_search' => $search,
            'current_sort' => $sort,
            'total_invoices_count' => $paidCount + $halfPaidCount + $unpaidCount + $freeCount,
            'paid_count' => $paidCount,
            'half_paid_count' => $halfPaidCount,
            'unpaid_count' => $unpaidCount,
            'free_count' => $freeCount,
            'awaiting_count' => $halfPaidCount + $unpaidCount,
            'outstanding_total' => (float) ICTInvoice::whereIn('payment_status', ['unpaid', 'half_paid'])->sum('remaining_amount'),
        ]);
    }
    /**
     * Download the currently filtered/sorted invoice list (NOT just the
     * current page — every matching row) as a CSV, so staff can take a
     * follow-up list into a spreadsheet or hand it to someone else
     * without screen-sharing this page.
     */
    public function exportCsv(Request $request)
    {
        // A plain in-memory string response (not response()->stream())
        // on purpose — this environment's PHP process terminates the
        // request/response cycle in a way that a StreamedResponse's
        // callback-based output doesn't survive cleanly here, while an
        // ordinary string response goes through the exact same pipeline
        // every other page on this site already renders through
        // reliably. A CSV of a staff's invoice list is at most a few
        // thousand rows, so building it fully in memory first costs
        // nothing noticeable.
        $invoices = $this->filteredInvoicesQuery($request)->get();
        $rows = [['Invoice Code', 'Student', 'Course', 'Status', 'Total', 'Paid', 'Remaining', 'Date']];
        foreach ($invoices as $invoice) {
            $rows[] = [
                $invoice->invoice_code,
                $invoice->student->name ?? '',
                $invoice->course->title ?? '',
                $invoice->payment_status,
                number_format($invoice->total_amount, 2),
                number_format($invoice->paid_amount, 2),
                number_format($invoice->remaining_amount, 2),
                $invoice->created_at->format('Y-m-d'),
            ];
        }
        $handle = fopen('php://temp', 'r+');
        foreach ($rows as $row) {
            fputcsv($handle, $row);
        }
        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);
        $filename = 'invoices-' . now()->format('Y-m-d-His') . '.csv';
        return response($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }
}
