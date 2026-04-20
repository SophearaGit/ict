<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Admin\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Admin\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Admin\Auth\NewPasswordController;
use App\Http\Controllers\Admin\Auth\PasswordController;
use App\Http\Controllers\Admin\Auth\PasswordResetLinkController;
use App\Http\Controllers\Admin\Auth\VerifyEmailController;
use App\Http\Controllers\Admin\CourseCategoryController;
use App\Http\Controllers\Admin\CourseLanguageController;
use App\Http\Controllers\Admin\CourseLevelController;
use App\Http\Controllers\Admin\InstructorRequestController;
use App\Http\Controllers\Admin\InstructorControlller;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\ProfileUpdateController;
use App\Http\Controllers\Admin\RealTimeCoursesController;
use App\Http\Controllers\Admin\StaffController;
use Illuminate\Support\Facades\Route;

Route::group(["middleware" => "guest:admin", "prefix" => "admin", "as" => "admin."], function () {

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store'])->name('login.store');

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');
});

Route::group(["middleware" => "auth:admin", "prefix" => "admin", "as" => "admin."], function () {
    Route::get('verify-email', EmailVerificationPromptController::class)
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');

    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::get('instructor-doc-download/{user}', [InstructorRequestController::class, 'download'])->name('instructor-doc-download');
    Route::resource('instructor-request', InstructorRequestController::class);





    /*******************************************************
     * INSTRUCTOR & STUDENT & STAFF
     *******************************************************/
    Route::get('/instructor', [InstructorControlller::class, 'index'])->name('instructor.index');
    Route::get('/instructor/{id}', [InstructorControlller::class, 'instructorShowDetail'])->name('instructor.show.detail');

    Route::get('/student', [StudentController::class, 'index'])->name('student.index');


    Route::get('/staff', [StaffController::class, 'index'])->name('staff.index');
    Route::get('/staff/create', [StaffController::class, 'create'])->name('staff.create');
    Route::post('/staff', [StaffController::class, 'store'])->name('staff.store');
    Route::get('/staff/edit/{id}', [StaffController::class, 'edit'])->name('staff.edit');
    Route::put('/staff/{id}', [StaffController::class, 'update'])->name('staff.update');
    Route::delete('/staff/{id}', [StaffController::class, 'destroy'])->name('staff.destroy');
    Route::patch('/staff/{id}/toggle', [StaffController::class, 'toggle'])
        ->name('staff.toggle');







    /*******************************************************
     * LANGUAGE, LEVEL, CATEGORY
     *******************************************************/
    Route::resource('course-language', CourseLanguageController::class);
    Route::resource('course-level', CourseLevelController::class);
    Route::resource('course-category', CourseCategoryController::class);


    /*******************************************************
     *  COURSE ONLINE
     *******************************************************/
    Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');

    /*******************************************************
     *  COURSE REAL TIME
     *******************************************************/
    Route::get('/realtime-courses', [RealTimeCoursesController::class, 'realtimeIndex'])->name('courses.realtime.index');
    Route::get('/realtime-courses/detail/{id}', [RealTimeCoursesController::class, 'realtimeShow'])->name('courses.realtime.show');
    Route::get('/realtime-courses/create', [RealTimeCoursesController::class, 'create'])->name('courses.realtime.create');
    Route::post('/realtime-courses', [RealTimeCoursesController::class, 'store'])->name('courses.realtime.store');
    Route::get('/realtime-courses/{id}/edit', [RealTimeCoursesController::class, 'edit'])->name('courses.realtime.edit');
    Route::put('/realtime-courses/{id}', [RealTimeCoursesController::class, 'update'])->name('courses.realtime.update');
    Route::delete('/realtime-courses/{id}', [RealTimeCoursesController::class, 'destroy'])->name('courses.realtime.destroy');



    /*******************************************************
     *  PROFILE
     *******************************************************/
    Route::get('/profile', [ProfileUpdateController::class, 'profile'])->name('profile.index');
    Route::post('/profile', [ProfileUpdateController::class, 'update'])->name('profile.update');
    Route::post('/profile/password', [ProfileUpdateController::class, 'updatePassword'])->name('profile.password.update');

});

