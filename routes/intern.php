<?php
use App\Http\Controllers\Frontend\Intern\InternDashboardController;
use App\Http\Controllers\Frontend\Intern\InternReportController;
use App\Http\Controllers\Frontend\Intern\InternProfileController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:web', 'verified', 'check_role:intern'])
    ->prefix('intern')
    ->name('intern.')
    ->group(function () {

        Route::get('/dashboard', [InternDashboardController::class, 'index'])
            ->name('dashboard');

        Route::resource('report', InternReportController::class)
            ->except(['show', 'create', 'edit']);
        Route::patch('report/{report}/period', [InternReportController::class, 'updatePeriod'])->name('report.updatePeriod');

        // Profile settings
        Route::prefix('profile')
            ->name('profile.')
            ->controller(InternProfileController::class)
            ->group(function () {
            Route::get('/', 'edit')->name('edit');
            Route::put('/', 'update')->name('update');
            Route::put('/social', 'updateSocial')->name('social.update');
            Route::put('/password', 'updatePassword')->name('password.update');
        });
    });
