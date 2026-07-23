<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
class StudentController extends Controller
{
    public function index(Request $request): View
    {
        $students = User::where('role', 'student')
            ->where('approval_status', 'approved')
            ->where(function ($q) {
                $q->whereNull('document')->orWhere('document', '');
            })
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->where('name', 'like', "%{$request->search}%")
                        ->orWhere('email', 'like', "%{$request->search}%");
                });
            })
            ->withCount('enrollments')
            ->withSum('payments', 'amount')
            ->latest()
            ->paginate(12)
            ->withQueryString();
        $data = [
            'page_title' => 'ICT | ADMIN | STUDENTS',
            'students' => $students,
            'total_students' => User::where('role', 'student')
                ->where('approval_status', 'approved')
                ->where(function ($q) {
                    $q->whereNull('document')->orWhere('document', '');
                })->count(),
        ];
        return view('admin.pages.user.student', $data);
    }
    public function show(User $student): View
    {
        $student->load([
            'enrollments.course.latestTeacherAttendance',
        ]);
        $sessionDuration = 1.5;
        foreach ($student->enrollments as $enrollment) {
            $course = $enrollment->course;
            if (!$course) {
                continue;
            }
            $totalATH = $course->latestTeacherAttendance->actual_hours ?? 0;
            $duration = $course->duration ?? 0;
            $progress = $duration > 0
                ? ($totalATH / $duration) * 100
                : 0;
            $course->progress = min(round($progress, 2), 100);
            $course->total_sessions = $duration > 0
                ? round($duration / $sessionDuration)
                : 0;
            $course->completed_sessions = $totalATH > 0
                ? floor($totalATH / $sessionDuration)
                : 0;
        }
        $totalCourses = $student->enrollments->count();
        // Based on actual progress %
        $completedCourses = $student->enrollments
            ->filter(fn($e) => ($e->course->progress ?? 0) >= 100)
            ->count();
        $inProgressCount = $student->enrollments
            ->filter(fn($e) => ($e->course->progress ?? 0) > 0
                && ($e->course->progress ?? 0) < 100)
            ->count();
        $notStartedCount = $student->enrollments
            ->filter(fn($e) => ($e->course->progress ?? 0) <= 0)
            ->count();
        $averageProgress = $totalCourses > 0
            ? round(
                $student->enrollments->avg(
                    fn($e) => $e->course->progress ?? 0
                ),
                1
            )
            : 0;
        return view('admin.pages.user.student-detail', [
            'page_title' => 'ICT | ADMIN | STUDENT — ' . strtoupper($student->name),
            'student' => $student,
            // Quick Stats
            'totalCourses' => $totalCourses,
            'completedCourses' => $completedCourses,
            'inProgressCount' => $inProgressCount,
            'notStartedCount' => $notStartedCount,
            'averageProgress' => $averageProgress,
        ]);
    }
}
