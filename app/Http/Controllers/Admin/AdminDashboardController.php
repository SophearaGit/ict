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
        // Month filter — defaults to current month
        if ($request->filled('month')) {
            $from = Carbon::parse($request->month)->startOfMonth();
            $to = Carbon::parse($request->month)->endOfMonth();
        } else {
            $from = Carbon::now()->startOfMonth();
            $to = Carbon::now()->endOfMonth();
        }
        $selectedMonth = $from->format('Y-m');
        // Courses active in this month
        $courseQuery = ICTCourse::where('start_date', '<=', $to)->where('end_date', '>=', $from);
        $totalCourses = $courseQuery->count();
        $pendingCourses = (clone $courseQuery)->where('status', 'inactive')->count();
        // Revenue — payments made during this month
        $totalRevenue = ICTPayments::whereBetween('created_at', [$from, $to])->sum('amount');
        // Compare vs previous month
        $prevFrom = $from->copy()->subMonth()->startOfMonth();
        $prevTo = $from->copy()->subMonth()->endOfMonth();
        $prevRevenue = ICTPayments::whereBetween('created_at', [$prevFrom, $prevTo])->sum('amount');
        $revenueChange = $totalRevenue - $prevRevenue;
        // Students registered this month
        $totalStudents = User::where('role', 'student')
            ->whereNull('document')
            ->whereBetween('created_at', [$from, $to])
            ->count();
        // Instructors registered this month
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
        // Students list (for quick-view modal)
        $newStudentsList = User::where('role', 'student')
            ->whereNull('document')
            ->whereBetween('created_at', [$from, $to])
            ->latest()
            ->get()
            ->map(
                fn($student) => [
                    'id' => $student->id,
                    'name' => $student->name,
                    'email' => $student->email,
                    'phone' => $student->phone ?? '-',
                    'image' => $student->image && $student->image !== 'no-img.jpg'
                        ? asset($student->image)
                        : asset('/default-images/user/both.jpg'),
                    'registered' => $student->created_at->format('M d, Y'),
                ],
            );
        // Recent courses active in this month
        $recentCourses = ICTCourse::with('instructor')->where('start_date', '<=', $to)->where('end_date', '>=', $from)->latest('start_date')->take(8)->get()->map(
            fn($course) => [
                'title' => $course->title,
                'thumbnail' => $course->thumbnail ? asset($course->thumbnail) : asset('default-images/staff/no-course-img.png'),
                'instructor_name' => $course->instructor?->name ?? 'N/A',
                'instructor_image' => $course->instructor?->image === 'no-img.jpg' ? asset('/default-images/user/both.jpg') : asset($course->instructor?->image ?? '/default-images/user/both.jpg'),
            ],
        );
        // Activity
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
        // Today's courses — active courses that span today
        $todaysCourses = ICTCourse::with(['instructor', 'schedule'])
            ->withCount('enrollments')
            ->whereDate('start_date', '<=', Carbon::today())
            ->whereDate('end_date', '>=', Carbon::today())
            ->where('status', 'active')
            ->latest('start_date')
            ->get()
            ->map(
                fn($course) => [
                    'id' => $course->id,
                    'title' => $course->title,
                    'khmer_title' => $course->khmer_title,
                    'thumbnail' => $course->thumbnail ? asset($course->thumbnail) : asset('default-images/staff/no-course-img.png'),
                    'instructor_name' => $course->instructor?->name ?? 'N/A',
                    'instructor_image' => $course->instructor?->image === 'no-img.jpg' ? asset('/default-images/user/both.jpg') : asset($course->instructor?->image ?? '/default-images/user/both.jpg'),
                    'start_date' => $course->start_date,
                    'end_date' => $course->end_date,
                    'duration' => $course->duration,
                    'status' => $course->status,
                    'enrollments_count' => $course->enrollments_count,
                    'price' => $course->price,
                    'total_revenue' => $course->payments()->sum('amount'),
                    'schedule_days' => $course->schedule?->short_days,
                    'schedule_shift' => $course->schedule?->shift_label,
                    'schedule_time' => $course->schedule?->formatted_time,
                ],
            );
        $data = [
            'page_title' => 'ICT | ADMIN | DASHBOARD',
            'selected_month' => $selectedMonth,
            'total_revenue' => number_format($totalRevenue, 2),
            'change' => number_format(abs($revenueChange), 2),
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
            'todays_courses' => $todaysCourses,
            'new_students_list' => $newStudentsList,
        ];
        return view('admin.pages.dashboard', $data);
    }
}
