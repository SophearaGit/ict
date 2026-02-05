<?php

use App\Http\Controllers\Frontend\FrontendController;
use App\Http\Controllers\Frontend\InstructorDashboardController;
use App\Http\Controllers\Frontend\StudentDashboardController;
use Illuminate\Support\Facades\Route;

/**
 * GLOBAL FRONTEND ROUTES
 */
Route::get('/', [FrontendController::class, 'index'])->name('home');

/**
 * AUTHENTICATED STUDENT ROUTES
 */
Route::group(["middleware" => ['auth:web', 'verified', 'check_role:student'], 'prefix' => 'student', 'as' => 'student.'], function () {
    Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');
});

/**
 * AUTHENTICATED INSTRUCTOR ROUTES
 */
Route::group(["middleware" => ['auth:web', 'verified', 'check_role:instructor'], 'prefix' => 'instructor', 'as' => 'instructor.'], function () {
    Route::get('/dashboard', [InstructorDashboardController::class, 'index'])->name('dashboard');
});

require __DIR__ . '/admin.php';
require __DIR__ . '/auth.php';
