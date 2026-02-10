<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\InstructorRequestApprovedMail;
use App\Mail\InstructorRequestRejectedMail;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class InstructorRequestController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            "page_title" => "ICT Center | Instructor Requests",
            "instructor_requests" => User::where('approval_status', 'pending')
                ->orWhere('approval_status', 'rejected')
                ->latest()->get(),
        ];
        return view("admin.pages.instructor-request", $data);
    }

    // instructor-doc-download
    public function download(User $user): BinaryFileResponse
    {
        return response()->download(public_path($user->document));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $instructor_request): RedirectResponse
    {
        $request->validate([
            "status" => "required|in:pending,approved,rejected",
        ]);

        $instructor_request->approval_status = $request->status;
        $request->status == "approved" ? $instructor_request->role = "instructor" : "";
        $instructor_request->save();

        self::sendNotification($instructor_request);

        return redirect()->back();
    }

    public static function sendNotification($instructor_request): void
    {
        switch ($instructor_request->approval_status) {
            case 'approved':
                if (config('mail_queue.is_queue')) {
                    Mail::to($instructor_request->email)->queue(new InstructorRequestApprovedMail($instructor_request));
                } else {
                    Mail::to($instructor_request->email)->send(new InstructorRequestApprovedMail($instructor_request));
                }
                break;
            case 'rejected':
                if (config('mail_queue.is_queue')) {
                    Mail::to($instructor_request->email)->queue(new InstructorRequestRejectedMail($instructor_request));
                } else {
                    Mail::to($instructor_request->email)->send(new InstructorRequestRejectedMail($instructor_request));
                }
                break;
            default:
                # code...
                break;
        }
    }

}
