<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileUpdateRequest;
use App\Models\ICTCourseEnrollments;
use App\Models\ICTInvoice;
use App\Models\User;
use App\Traites\FileUpload;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\HttpFoundation\RedirectResponse;

class StudentDashboardController extends Controller
{
    use FileUpload;

    public function myCourses(): View
    {
        $data = [
            'page_title' => 'ICT | My Courses',
            'enrolled_courses' => auth()->user()->enrollments()->with('course')->get(),
        ];
        return view('frontend.student.pages.my-courses.my-courses', $data);
    }

    // myCourseDetail
    public function myCourseDetail($courseId): View
    {
        $data = [
            'page_title' => 'ICT | My Course Detail',
            'course' => auth()->user()->enrollments()->with('course')->where('course_id', $courseId)->firstOrFail()->course,
            'classmates' => auth()->user()->enrollments()->with('course')->where('course_id', $courseId)->firstOrFail()->course->enrollments()->with('student')->get(),
        ];
        return view('frontend.student.pages.my-courses.my-course-detail', $data);
    }

    public function index(): View
    {
        $enrolledCourses = auth()->user()->enrollments()->with('course')->get();

        // Enrollments and invoices aren't directly related in the schema
        // (the enrollment pivot has no invoice_id), so a Pay Half student
        // who still owes the 2nd installment is matched up here by
        // (student, course) instead — orderByDesc + unique('course_id')
        // keeps only the most recent invoice per course, in case a course
        // was ever invoiced more than once for the same student.
        $invoicesByCourse = ICTInvoice::where('student_id', auth()->id())
            ->whereIn('course_id', $enrolledCourses->pluck('course_id'))
            ->orderByDesc('id')
            ->get()
            ->unique('course_id')
            ->keyBy('course_id');

        $enrolledCourses->each(function (ICTCourseEnrollments $enrollment) use ($invoicesByCourse) {
            $invoice = $invoicesByCourse->get($enrollment->course_id);

            // Dynamic, non-persisted display fields — same pattern used
            // for the select-schedule cards (see selectSchedule() in
            // CourseEnrollmentController): computed per-request rather
            // than real columns, so the view can flag "still owes a
            // remaining balance" without a schema change.
            $enrollment->setAttribute('payment_status', $invoice?->payment_status);
            $enrollment->setAttribute('remaining_amount', $invoice?->remaining_amount);
            $enrollment->setAttribute('invoice_id', $invoice?->id);
        });

        $data = [
            'page_title' => 'ICT | Student Dashboard',
            'enrolled_courses' => $enrolledCourses,
        ];
        return view('frontend.student.index', $data);
    }

    public function becomeInstructor(Request $request)
    {
        if (auth()->guard()->user()->role === 'instructor') {
            abort(403);
        }
        $data = [
            'page_title' => 'ICT | Become an Instructor',
        ];
        return view('frontend.student.become-instructor', $data);
    }

    public function becomeInstructorSubmit(Request $request, User $user)
    {
        $request->validate([
            'document' => ['required', 'mimes:pdf,doc,docx,jpg,png', 'max:12000'],
        ]);

        $filePath = $this->uploadFile($request->file('document'));

        $user->update([
            'approval_status' => 'pending',
            'document' => $filePath,
        ]);

        return redirect()->route('student.dashboard');
    }
}
