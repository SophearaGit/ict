<?php
namespace App\Http\Controllers\Frontend\Staff;
use App\Http\Controllers\Controller;
use App\Models\ICTCourse;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
class StaffDashboardController extends Controller
{
    public function index(): View
    {
        $data = [
            'page_title' => 'ICT | STAFF | DASHBOARD',
            // ── Stat counters ──────────────────────────────────────────────
            'students_count' => Auth::user()->students()->count(),
            'staffs_count' => User::where('role', 'staff')->count(),
            'reports_count' => Auth::user()->reports()->count(),
            'courses_count' => ICTCourse::count(),
            // ── Today's Courses ────────────────────────────────────────────
            'today_courses' => ICTCourse::with(['instructor', 'schedule'])
                ->whereHas('schedule', fn($q) => $q->whereDate('start_date', today()))
                ->latest()
                ->get(),
            // ── Recent Courses (last 6 added) ──────────────────────────────
            'recent_courses' => ICTCourse::with('instructor')->latest()->take(6)->get(),
            // ── Popular Teachers (top 6 by course count) ──────────────────
            'popular_teachers' => User::where('role', 'instructor')->withCount('courses')->having('courses_count', '>', 0)->orderByDesc('courses_count')->take(6)->get(),
            // ── Registered students (for other parts of the view) ──────────
            'students' => User::where('registered_by_staff_id', Auth::id())->where('role', 'student')->latest()->paginate(8),
        ];
        return view('frontend.staff.index', $data);
    }
}
