<?php

use App\Http\Controllers\Frontend\{
    FrontendController,
    InstructorDashboardController,
    ProfileController,
    StudentDashboardController,
};
use Illuminate\Support\Facades\Route;

// Global Frontend Routes
Route::get('/', [FrontendController::class, 'index'])->name('home');

// Student Routes
Route::middleware(['auth:web', 'verified', 'check_role:student'])
    ->prefix('student')
    ->name('student.')
    ->group(function () {
        Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');
        Route::get('/become-instructor', [StudentDashboardController::class, 'becomeInstructor'])->name('become.instructor');
        Route::post('/become-instructor/{user}', [StudentDashboardController::class, 'becomeInstructorSubmit'])->name('become.instructor.submit');

        Route::get('/profile-edit', [ProfileController::class, 'getEditProfile'])->name('profile.edit');
        Route::post('/profile-edit-submit', [ProfileController::class, 'profileEditSubmit'])->name('profile.edit.submit');
        Route::get('/security', [ProfileController::class, 'security'])->name('security');
        Route::post('/security-update', [ProfileController::class, 'securityUpdate'])->name('security.update');
        Route::get('/social-profile', [ProfileController::class, 'socialProfile'])->name('social.profile');
        Route::post('/social-profile-update', [ProfileController::class, 'socialProfileUpdate'])->name('social.profile.update');



    });

// Instructor Routes
Route::middleware(['auth:web', 'verified', 'check_role:instructor'])
    ->prefix('instructor')
    ->name('instructor.')
    ->group(function () {
        Route::get('/dashboard', [InstructorDashboardController::class, 'index'])->name('dashboard');
    });

// Additional Routes
require __DIR__ . '/admin.php';
require __DIR__ . '/auth.php';
