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
        // Scoped to the public-site layout only — this used to be bound to
        // 'frontend.*', which also matches every staff/student/instructor/
        // intern portal view (they all live under resources/views/frontend/),
        // so these two queries (a category+course join and a full course-table
        // scan for the "popular courses" widget) ran on EVERY staff page load
        // even though categories_for_frontend/popularCourses are only ever used
        // by the public header/footer/mobile-drawer partials, which the public
        // layout includes. Those partials inherit this layout's composed data
        // via Blade's @include, so scoping to just the layout view still reaches
        // them, while no longer firing for every other portal.
        View::composer('frontend.layouts.new.master', function ($view) {
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
