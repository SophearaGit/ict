<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use App\Models\ICTCourseCategory;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('frontend.*', function ($view) {
            $view->with(
                'categories_for_frontend',
                ICTCourseCategory::whereNull('parent_id')
                    ->with([
                        'courses' => function ($q) {
                            $q->where('status', 'active');
                        }
                    ])
                    ->where('is_active', 1)
                    ->orderBy('sort_order')
                    ->get()
            );
        });
    }
}
