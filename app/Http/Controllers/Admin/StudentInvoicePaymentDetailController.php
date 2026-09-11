<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ICTCourse;
use App\Models\ICTInvoice;
use App\Models\ICTInvoiceItems;
use App\Models\ICTPayments;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class StudentInvoicePaymentDetailController extends Controller
{
    public function studentInvoice(Request $request, ICTCourse $course, User $student): View
    {
        $course->loadMissing(['instructor', 'schedule']);
        $invoice = ICTInvoice::with(['staff', 'paidBy', 'items.course'])
            ->where('student_id', $student->id)
            ->where('course_id', $course->id)
            ->latest()
            ->first();
        abort_if(!$invoice, 404, 'Invoice not found for this student and course.');
        $invoiceItems = ICTInvoiceItems::with('course')->where('invoice_id', $invoice->id)->get();
        $payments = ICTPayments::with('paidBy')->where('invoice_id', $invoice->id)->orderBy('paid_at')->get();
        return view('admin.pages.invoice-payment-detail.student-invoice', [
            'page_title' => 'ICT | STAFF | STUDENT INVOICE',
            'course' => $course,
            'student' => $student,
            'invoice' => $invoice,
            'invoiceItems' => $invoiceItems,
            'payments' => $payments,
        ]);
    }

    /**
     * Admin-wide invoice list — previously the only way to see an
     * invoice at all was to already know which course + student to look
     * under (studentInvoice() above); this is the missing entry point.
     */
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $status = $request->input('status');

        $invoices = ICTInvoice::with(['student', 'course'])
            ->when($search, function ($query, $search) {
                // Grouped in a single where() so it ANDs correctly with
                // the status filter below instead of OR-ing against the
                // whole query.
                $query->where(function ($q) use ($search) {
                    $q->where('invoice_code', 'like', "%{$search}%")
                        ->orWhereHas('student', fn($sq) => $sq->where('name', 'like', "%{$search}%"))
                        ->orWhereHas('course', fn($sq) => $sq->where('title', 'like', "%{$search}%"));
                });
            })
            ->when($status, fn($query, $status) => $query->where('payment_status', $status))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.pages.invoices.index', [
            'page_title' => 'ICT | ADMIN | INVOICES',
            'invoices' => $invoices,
            'search' => $search,
            'status' => $status,
        ]);
    }

    /**
     * Renders just the invoice-card fragment (no layout) for the quick-view
     * modal — used both from the Invoices list above and from a course's
     * Students tab. Kept intentionally read-only, same as the full page.
     */
    public function quickView(ICTInvoice $invoice): View
    {
        $invoice->load(['student', 'staff', 'course.schedule', 'items.course', 'payments.paidBy']);

        return view('admin.pages.invoices.partials.quick-view', [
            'invoice' => $invoice,
        ]);
    }
}
