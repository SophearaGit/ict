<?php
use App\Http\Controllers\Frontend\Intern\InternDashboardController;
use App\Http\Controllers\Frontend\Intern\InternReportController;
use App\Http\Controllers\Frontend\ProfileController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:web', 'verified', 'check_role:intern'])
    ->prefix('intern')
    ->name('intern.')
    ->group(function () {
        Route::get('/dashboard', [InternDashboardController::class, 'index'])
            ->name('dashboard');
        Route::get('/profile-edit', [ProfileController::class, 'StaffProfileEdit'])
            ->name('profile.edit');
        Route::post('/profile-edit-update', [ProfileController::class, 'StaffProfileUpdate'])
            ->name('profile.edit.update');
        Route::post('/social-profile-update', [ProfileController::class, 'StaffSocialProfileUpdate'])
            ->name('social.profile.update');


        Route::resource('report', InternReportController::class);




    });
