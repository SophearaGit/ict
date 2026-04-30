<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileUpdateRequest;
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
        $data = [
            'page_title' => 'ICT | Student Dashboard',
            'enrolled_courses' => auth()->user()->enrollments()->with('course')->get(),
        ];
        return view('frontend.student.index', $data);
    }

    public function becomeInstructor(Request $request)
    {
        if (auth()->guard()->user()->role === 'instructor')
            abort(403);
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
