<?php
namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;
use App\Models\ICTCourse;
use App\Models\User;
use Illuminate\Contracts\View\View;
class FrontendController extends Controller
{
    public function index(): View
    {
        $data = [
            'page_title' => 'WELCOME 😁🙏',
            'courses' => ICTCourse::with([
                'instructor',
                'schedule',
                'category'
            ])
                ->where('status', 'active')
                ->latest()
                ->get()
                ->groupBy('title'),
            'instructors' => User::where('role', 'instructor')
                ->withCount('courses')
                ->withCount('enrollments as students_count')
                ->orderByDesc('students_count')
                ->take(4)
                ->get(),
            'featured_instructors' => User::where('role', 'instructor')
                ->withCount('courses')
                ->withCount('enrollments as students_count')
                ->with([
                    'courses' => fn($q) => $q->latest()
                ])
                ->where('approval_status', 'approved')
                ->where('status', 'active')
                ->orderByDesc('students_count')
                ->take(8)
                ->get(),
            'total_students' => User::where('role', 'student')
                ->where('approval_status', 'approved')
                ->count(),
        ];
        return view('frontend.pages.home-new.index', $data);
    }

    public function contact(): View
    {
        $data = [
            'page_title' => 'CONTACT',
        ];
        return view('frontend.pages.home-new.contact', $data);
    }

}
