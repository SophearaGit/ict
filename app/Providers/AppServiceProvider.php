<?php
namespace App\Providers;
use App\Models\ICTCourse;
use App\Models\ICTCourseCategory;
use App\Models\TeacherAttendances;
use App\Observers\TeacherAttendancesObserver;
use Illuminate\Support\Facades\View;
use Illuminate\Console\Scheduling\Schedule;
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
                    $q->frontendVisible()->orderBy('title');
                }
            ])
                ->where('is_active', 1)
                ->whereHas('courses', function ($q) {
                    $q->frontendVisible();
                })
                ->orderBy('sort_order')
                ->get();
            $popularCourses = ICTCourse::frontendVisible()
                ->withCount('students')
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
        $this->app->booted(function () {
            $schedule = $this->app->make(Schedule::class);

            $schedule->command('courses:update-expired-status')
                ->hourlyAt(5) // runs at :05 past every hour
                ->onOneServer()
                ->withoutOverlapping();
        });
    }
}
