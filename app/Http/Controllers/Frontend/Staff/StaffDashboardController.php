<?php
namespace App\Http\Controllers\Frontend\Staff;
use App\Http\Controllers\Controller;
use App\Models\ICTCourse;
use App\Models\ICTStaffReport;
use App\Models\User;
use Illuminate\Contracts\View\View;
class StaffDashboardController extends Controller
{
    public function index(): View
    {
        $data = [
            'page_title' => 'ICT | STAFF | DASHBOARD',
            // ── Stat counters ──────────────────────────────────────────────
            // These cards are labeled plainly "Students" / "Reports" (not
            // "My Students" / "My Reports"), so they need to be whole-platform
            // totals — same as Courses/Staffs/Draft Courses below. They were
            // previously scoped to Auth::user() (students THIS staff member
            // personally registered, reports THIS staff member filed), which
            // is why "Students" showed 0 even though the LMS clearly has
            // students enrolled by other staff.
            'students_count' => User::where('role', 'student')->count(),
            'staffs_count' => User::where('role', 'staff')->count(),
            'reports_count' => ICTStaffReport::count(),
            'courses_count' => ICTCourse::count(),
            'draft_courses_count' => ICTCourse::where('status', 'draft')->count(),
            // ── Today's Courses ────────────────────────────────────────────
            'today_courses' => ICTCourse::with(['instructor', 'schedule'])
                ->whereHas('schedule', fn($q) => $q->whereDate('start_date', today()))
                ->latest()
                ->get(),
            // ── Recent Courses (last 6 added) ──────────────────────────────
            'recent_courses' => ICTCourse::with('instructor')->latest()->take(6)->get(),
            // ── Popular Teachers (top 6 by course count) ──────────────────
            'popular_teachers' => User::where('role', 'instructor')->withCount('courses')->having('courses_count', '>', 0)->orderByDesc('courses_count')->take(6)->get(),
        ];
        return view('frontend.staff.index', $data);
    }
}
