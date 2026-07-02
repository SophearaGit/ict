<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\ICTCourse;
use Illuminate\Http\Request;
use App\Models\StudentReports;
use App\Notifications\Admin\StudentReportApprovedNotification;
use App\Notifications\Admin\StudentReportRejectedNotification;
use Illuminate\Support\Facades\Auth;
class StudentReportController extends Controller
{
    public function studentReport()
    {
        $pendingReports = StudentReports::with([
            'course.instructor',
            'student'
        ])
            ->where('approval_status', 'pending')
            ->get()
            ->groupBy('course_id');
        $data = [
            'page_title' => 'ICT | ADMIN | STUDENT REPORT',
            'pendingReports' => $pendingReports,
        ];
        return view('admin.pages.Report.Student.index', $data);
    }
    public function approve($courseId)
    {
        $course = ICTCourse::findOrFail($courseId);
        StudentReports::where('course_id', $course->id)
            ->update([
                'approval_status' => 'approved',
                'approved_by' => Auth::id(),
                'approved_at' => now(),
            ]);
        // notify teacher
        $teacher = $course->instructor;
        $teacher->notify(
            new StudentReportApprovedNotification($course)
        );
        return back()->with(
            'success',
            'Student reports approved.'
        );
    }
    public function reject($courseId)
    {
        $course = ICTCourse::findOrFail($courseId);
        StudentReports::where('course_id', $course->id)
            ->update([
                'approval_status' => 'draft',
                'approved_by' => null,
                'approved_at' => null,
            ]);
        // notify teacher
        $teacher = $course->instructor;
        $teacher->notify(
            new StudentReportRejectedNotification($course)
        );
        return back()->with(
            'success',
            'Student reports rejected successfully.'
        );
    }
}
