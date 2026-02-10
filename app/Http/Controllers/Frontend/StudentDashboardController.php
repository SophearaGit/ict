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

    public function index(): View
    {
        $data = [
            'page_title' => 'ICT Center | Student Dashboard',
        ];
        return view('frontend.student.index');
    }

    public function becomeInstructor(Request $request)
    {
        if (auth()->guard()->user()->role === 'instructor')
            abort(403);
        $data = [
            'page_title' => 'ICT Center | Become an Instructor',
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
