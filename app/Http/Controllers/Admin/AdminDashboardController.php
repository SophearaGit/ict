<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\ICTCourse;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use App\Models\ICTPayments;
use App\Models\User;
use Illuminate\Support\Carbon;
class AdminDashboardController extends Controller
{
    public function readNotification($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        // security check
        if ($notification->notifiable_id !== auth()->id()) {
            abort(403);
        }
        $notification->markAsRead();
        return back();
    }
    public function readAllNotifications()
    {
        auth()->user()->unreadNotifications->markAsRead();
        return back();
    }
    public function index(Request $request): View
    {
        // Handle quick presets FIRST, before the custom range fallback
        switch ($request->preset) {
            case 'today':
                $from = Carbon::today()->startOfDay();
                $to = Carbon::today()->endOfDay();
                break;
            case 'week':
                $from = Carbon::now()->startOfWeek();
                $to = Carbon::now()->endOfWeek();
                break;
            case 'month':
                $from = Carbon::now()->startOfMonth();
                $to = Carbon::now()->endOfMonth();
                break;
            case 'year':
                $from = Carbon::now()->startOfYear();
                $to = Carbon::now()->endOfYear();
                break;
            case 'all':
            default:
                // <-- all is now the default
                $from = $request->filled('from') ? Carbon::parse($request->from)->startOfDay() : Carbon::createFromTimestamp(0);
                $to = $request->filled('to') ? Carbon::parse($request->to)->endOfDay() : Carbon::now()->endOfDay();
                break;
        }
        // Revenue
        $totalRevenue = ICTPayments::whereBetween('created_at', [$from, $to])->sum('amount');
        $diffInDays = $from->diffInDays($to) + 1;
        $prevFrom = $from->copy()->subDays($diffInDays);
        $prevTo = $from->copy()->subSecond();
        $prevRevenue = ICTPayments::whereBetween('created_at', [$prevFrom, $prevTo])->sum('amount');
        $revenueChange = $totalRevenue - $prevRevenue;
        // Courses
        $totalCourses = ICTCourse::whereBetween('created_at', [$from, $to])->count();
        $pendingCourses = ICTCourse::where('status', 'inactive')
            ->whereBetween('created_at', [$from, $to])
            ->count();
        // Students
        $totalStudents = User::where('role', 'student')
            ->whereNull('document')
            ->whereBetween('created_at', [$from, $to])
            ->count();
        // Instructors
        $totalInstructors = User::where('role', 'instructor')
            ->where('document', '!=', '')
            ->whereBetween('created_at', [$from, $to])
            ->count();
        // Instructors list
        $newInstructorsList = User::where('role', 'instructor')
            ->where('document', '!=', '')
            ->whereBetween('created_at', [$from, $to])
            ->with(['courses' => fn($q) => $q->withCount('enrollments')])
            ->take(8)
            ->get()
            ->map(
                fn($instructor) => [
                    'name' => $instructor->name,
                    'image' => $instructor->image === 'no-img.jpg' ? asset('/default-images/user/both.jpg') : asset($instructor->image),
                    'course_count' => $instructor->courses->count(),
                    'student_count' => $instructor->courses->sum('enrollments_count'),
                ],
            );
        // Recent Courses
        $recentCourses = ICTCourse::with('instructor')
            ->whereBetween('created_at', [$from, $to])
            ->latest()
            ->take(8)
            ->get()
            ->map(
                fn($course) => [
                    'title' => $course->title,
                    'thumbnail' => $course->thumbnail ? asset($course->thumbnail) : asset('default-images/staff/no-course-img.png'),
                    'instructor_name' => $course->instructor?->name ?? 'N/A',
                    'instructor_image' => $course->instructor?->image === 'no-img.jpg' ? asset('/default-images/user/both.jpg') : asset($course->instructor?->image ?? '/default-images/user/both.jpg'),
                ],
            );
        // Activity feed from notifications
        $activities = auth('admin')
            ->user()
            ->notifications()
            ->whereBetween('created_at', [$from, $to])
            ->latest()
            ->take(5)
            ->get()
            ->map(
                fn($n) => [
                    'title' => $n->data['title'],
                    'message' => $n->data['message'],
                    'image' => $n->data['teacher_image'] ?? ($n->data['admin_image'] ?? asset('/default-images/user/both.jpg')),
                    'time' => $n->created_at->diffForHumans(),
                    'is_read' => !is_null($n->read_at),
                ],
            );
        // For displaying back in the date inputs — presets show their resolved dates
        $data = [
            'page_title' => 'ICT | ADMIN | DASHBOARD',
            'total_revenue' => number_format($totalRevenue, 2),
            'change' => number_format($revenueChange, 2),
            'is_up' => $revenueChange >= 0,
            'total_courses' => number_format($totalCourses),
            'pending_courses' => $pendingCourses,
            'total_students' => number_format($totalStudents),
            'new_students' => $totalStudents,
            'total_instructors' => number_format($totalInstructors),
            'new_instructors' => $totalInstructors,
            'new_instructors_list' => $newInstructorsList,
            'recent_courses' => $recentCourses,
            'activities' => $activities,
            'from' => $from->toDateString(),
            'to' => $to->toDateString(),
        ];
        return view('admin.pages.dashboard', $data);
    }
}
