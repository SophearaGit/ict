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
}
