<?php
namespace App\Http\Controllers\Frontend\Staff;
use App\Http\Controllers\Controller;
use App\Models\ICTCourse;
use App\Models\ICTCourseEnrollments;
use App\Models\ICTInvoice;
use App\Models\ICTStaffReport;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
class StaffDashboardController extends Controller
{
    public function index(): View
    {
        // ── Course status breakdown (for the donut chart) ──────────────────
        $active_courses_count = ICTCourse::where('status', 'active')->count();
        $draft_courses_count = ICTCourse::where('status', 'draft')->count();
        $inactive_courses_count = ICTCourse::where('status', 'inactive')->count();
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
            'draft_courses_count' => $draft_courses_count,
            'enrollments_count' => ICTCourseEnrollments::count(),
            // ── This-week deltas, shown as small "+N this week" trend chips
            //    on the stat cards so the numbers aren't just static totals ──
            'students_new_this_week' => User::where('role', 'student')->where('created_at', '>=', now()->subDays(7))->count(),
            'courses_new_this_week' => ICTCourse::where('created_at', '>=', now()->subDays(7))->count(),
            // ── Today's Courses ────────────────────────────────────────────
            'today_courses' => ICTCourse::with(['instructor', 'schedule'])
                ->whereHas('schedule', fn($q) => $q->whereDate('start_date', today()))
                ->latest()
                ->get(),
            // ── Recent Courses (last 6 added) ──────────────────────────────
            // Eager-loads 'schedule' too (not just 'instructor') now that
            // these render as full course-card tiles, same as Today's
            // Courses, which read $course->schedule — without it this would
            // silently N+1 query per card instead of erroring.
            'recent_courses' => ICTCourse::with(['instructor', 'schedule'])->latest()->take(6)->get(),
            // ── Popular Teachers (top 6 by course count) ──────────────────
            'popular_teachers' => User::where('role', 'instructor')->withCount('courses')->having('courses_count', '>', 0)->orderByDesc('courses_count')->take(6)->get(),
            // ── Course status donut ────────────────────────────────────────
            'active_courses_count' => $active_courses_count,
            'inactive_courses_count' => $inactive_courses_count,
            // ── Invoices awaiting payment follow-up (count only — staff
            //    shouldn't see platform revenue figures on this dashboard,
            //    but knowing how many invoices still need chasing is a
            //    genuinely actionable number, not a financial one) ──────────
            'outstanding_invoices_count' => ICTInvoice::whereIn('payment_status', ['unpaid', 'half_paid'])->count(),
            // ── This staff member's own recent reports ─────────────────────
            'my_recent_reports' => ICTStaffReport::where('reported_by', Auth::id())->latest()->take(4)->get(),
            'my_pending_reports_count' => ICTStaffReport::where('reported_by', Auth::id())->where('status', 'pending')->count(),
        ];
        return view('frontend.staff.index', $data);
    }
}
