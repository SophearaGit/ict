<?php

namespace App\Http\Controllers\Frontend\Staff;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\ICTCourse;
use App\Models\StudentReports;
use App\Models\User;
use App\Notifications\Teacher\StudentReportApprovalRequestNotification;
use App\Notifications\Teacher\StudentReportCancelApprovalNotification;
use Illuminate\Http\Request;

class StudentReportController extends Controller
{
    public function cancelApproval($courseId)
    {
        $course = ICTCourse::findOrFail($courseId);

        StudentReports::where('course_id', $course->id)->update(['approval_status' => 'draft']);

        $admins = Admin::get();
        foreach ($admins as $admin) {
            $admin->notify(new StudentReportCancelApprovalNotification($course));
        }

        return back()->with('success', 'Approval request cancelled.');
    }

    public function requestApproval($courseId)
    {
        $course = ICTCourse::findOrFail($courseId);

        StudentReports::where('course_id', $course->id)->update(['approval_status' => 'pending']);

        $notification = new StudentReportApprovalRequestNotification($course);

        $admins = Admin::get();
        foreach ($admins as $admin) {
            $admin->notify($notification);
        }

        $notification->sendTelegram(); // 👈 add this after the foreach
        // also change sendTelegram() to public

        return back()->with('success', 'Approval request sent successfully.');
    }

    public function update(Request $request, $id)
    {
        $report = StudentReports::findOrFail($id);

        // Scores are locked the same way attendance is: once the report is
        // pending approval or already approved, only an admin rejection
        // (back to 'draft') reopens editing. The UI already disables these
        // inputs; this is the server-side backstop.
        if (in_array($report->approval_status, ['pending', 'approved'])) {
            return response()->json([
                'message' => 'This report is locked while pending approval or already approved.',
            ], 403);
        }

        // update field
        $report->{$request->field} = $request->value;

        /*
        |--------------------------------------------------------------------------
        | Total Score
        |--------------------------------------------------------------------------
        |
        | attendance = 10
        | assignment = 30
        | mini = 20
        | final = 40
        |
        | TOTAL = 100
        |
        */

        $total = (float) $report->attendance_score + (float) $report->assignment_score + (float) $report->mini_project_score + (float) $report->final_project_score;

        $report->total_score = round($total, 2);

        $report->result = $total >= 50 ? 'pass' : 'fail';

        $report->save();

        return response()->json([
            'total_score' => $report->total_score,
            'result' => $report->result,
        ]);
    }
}
