<?php
namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;
use App\Models\ICTCourse;
use App\Models\ICTCourseCategory;
use App\Models\ICTCourseEnrollments;
use Illuminate\Contracts\View\View;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
class CoursePageController extends Controller
{
    public function course(): View
    {
        $search = request('search');
        $groupedCourses = ICTCourse::with([
            'instructor',
            'schedule',
            'category'
        ])
            ->where('status', 'active')
            ->when($search, function ($query) use ($search) {
                $query->where('title', 'like', "%{$search}%");
            })
            ->latest()
            ->get()
            ->groupBy('title');
        $perPage = 12;
        $currentPage = request()->get('page', 1);
        $pagedCourses = new LengthAwarePaginator(
            $groupedCourses->forPage($currentPage, $perPage),
            $groupedCourses->count(),
            $perPage,
            $currentPage,
            [
                'path' => request()->url(),
                'query' => request()->query(),
            ]
        );
        $data = [
            'page_title' => 'COURSE',
            'courses' => $pagedCourses,
            'categories' => ICTCourseCategory::where('is_active', 1)
                ->orderBy('sort_order')
                ->get(),
        ];
        return view('frontend.pages.home-new.course', $data);
    }
    // course details page
    public function courseDetails($slug): View
    {
        $course = ICTCourse::with([
            'instructor',
            'schedule',
            'category'
        ])
            ->where('slug', $slug)
            ->where('status', 'active')
            ->firstOrFail();
        $batches = ICTCourse::with('schedule')
            ->where('title', $course->title)
            ->where('status', 'active')
            ->get();
        $moreCourses = ICTCourse::with([
            'instructor',
            'schedule',
            'category'
        ])
            ->where('status', 'active')
            ->where('title', '!=', $course->title)
            ->when(
                $course->category_id,
                fn($q) => $q->where('category_id', $course->category_id)
            )
            ->latest()
            ->get()
            ->groupBy('title')
            ->take(4);
        // How many active sections share this title (including this one)
        $siblingCount = ICTCourse::where('title', $course->title)
            ->where('status', 'active')
            ->count();
        $alreadyEnrolled = false;
        if (Auth::check()) {
            $alreadyEnrolled = ICTCourseEnrollments::where([
                'student_id' => Auth::id(),
                'course_id' => $course->id,
            ])->exists();
        }
        return view('frontend.pages.home-new.course-details', [
            'page_title' => 'COURSE DETAILS',
            'course' => $course,
            'batches' => $batches,
            'moreCourses' => $moreCourses,
            'alreadyEnrolled' => $alreadyEnrolled,
            'siblingCount' => $siblingCount,
        ]);
    }
}
