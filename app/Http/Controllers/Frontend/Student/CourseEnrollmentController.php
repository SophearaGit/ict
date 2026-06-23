<?php
namespace App\Http\Controllers\Frontend\Student;
use App\Http\Controllers\Controller;
use App\Models\ICTCourse;
use App\Models\ICTInvoice;
use App\Models\ICTCourseEnrollments;
use App\Models\ICTInvoiceItems;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
class CourseEnrollmentController extends Controller
{
    public function startEnrollment(ICTCourse $course)
    {
        $student = auth()->user();
        $alreadyEnrolled = ICTCourseEnrollments::where([
            'student_id' => $student->id,
            'course_id' => $course->id,
        ])->exists();
        if ($alreadyEnrolled) {
            return back()->with('error', 'You are already enrolled.');
        }
        $invoice = ICTInvoice::create([
            'staff_id' => auth()->id(), // see note below
            'student_id' => auth()->id(),
            'course_id' => $course->id,
            'price' => $course->price,
            'discount' => 0,
            'extra_charge' => 0,
            'total_amount' => $course->price,
            'paid_amount' => 0,
            'remaining_amount' => $course->price,
            'payment_option' => 'full',
            'payment_status' => 'unpaid',
            'invoice_code' => 'INV-' . now()->format('YmdHis') . rand(100, 999),
        ]);
        ICTInvoiceItems::create([
            'invoice_id' => $invoice->id,
            'course_id' => $course->id,
            'price' => $course->price,
            'discount' => 0,
            'extra_charge' => 0,
            'total' => $course->price,
        ]);
        return redirect()->route(
            'student.payment.page',
            $invoice->id
        );
    }
    public function paymentPage(ICTInvoice $invoice): View
    {
        $invoice->load('course');
        return view(
            'frontend.student.pages.payment.index',
            compact('invoice')
        );
    }
}
