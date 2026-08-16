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
        $categoryId = request('category');
        $groupedCourses = ICTCourse::frontendVisible()
            ->with([
                'instructor',
                'schedule',
                'category'
            ])
            ->when($search, function ($query) use ($search) {
                $query->where('title', 'like', "%{$search}%");
            })
            ->when($categoryId, function ($query) use ($categoryId) {
                $query->where('category_id', $categoryId);
            })
            ->latest()
            ->get()
            ->groupBy('title');
        $perPage = 100;
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
        $course = ICTCourse::frontendVisible()
            ->with([
                'instructor',
                'schedule',
                'category',
                'chapters.lessons',
            ])
            ->where('slug', $slug)
            ->firstOrFail();
        $batches = ICTCourse::frontendVisible()
            ->with('schedule')
            ->where('title', $course->title)
            ->get();
        $moreCourses = ICTCourse::frontendVisible()
            ->with([
                'instructor',
                'schedule',
                'category'
            ])
            ->where('title', '!=', $course->title)
            ->when(
                $course->category_id,
                fn($q) => $q->where('category_id', $course->category_id)
            )
            ->latest()
            ->get()
            ->groupBy('title')
            ->take(4);
        // How many featured sections share this title (including this one)
        $siblingCount = ICTCourse::frontendVisible()
            ->where('title', $course->title)
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
