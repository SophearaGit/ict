<?php
namespace App\Providers;
use App\Models\ICTCourse;
use App\Models\ICTCourseCategory;
use App\Models\TeacherAttendances;
use App\Observers\TeacherAttendancesObserver;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }
    public function boot(): void
    {
        TeacherAttendances::observe(TeacherAttendancesObserver::class);
        View::composer('frontend.*', function ($view) {
            $categories = ICTCourseCategory::with([
                'courses' => function ($q) {
                    $q->where('status', 'active')
                        ->orderBy('title');
                }
            ])
                ->where('is_active', 1)
                ->whereHas('courses', function ($q) {
                    $q->where('status', 'active');
                })
                ->orderBy('sort_order')
                ->get();
            $popularCourses = ICTCourse::withCount('students')
                ->where('status', 'active')
                ->get()
                ->groupBy('title')
                ->map(fn($group) => $group->first())
                ->sortByDesc('students_count')
                ->take(5);
            $view->with([
                'categories_for_frontend' => $categories,
                'popularCourses' => $popularCourses,
            ]);
        });
    }
}
