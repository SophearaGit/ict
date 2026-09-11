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
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\CourseCategoryController;
use App\Http\Controllers\Admin\CourseLanguageController;
use App\Http\Controllers\Admin\CourseLevelController;
use App\Http\Controllers\Admin\InstructorRequestController;
use App\Http\Controllers\Admin\InstructorControlller;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\InternController;
use App\Http\Controllers\Admin\InternReportController;
use App\Http\Controllers\Admin\ProfileUpdateController;
use App\Http\Controllers\Admin\RealTimeCoursesController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\StaffReportController;
use App\Http\Controllers\Admin\StudentInvoicePaymentDetailController;
use App\Http\Controllers\Admin\StudentReportController;
use App\Http\Controllers\Frontend\Teacher\StudentAttendanceController;


use Illuminate\Support\Facades\Route;
Route::group(['middleware' => 'guest:admin', 'prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::get('x8472/academic-control-center/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store'])->name('login.store');
    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.store');
});
Route::group(['middleware' => 'auth:admin', 'prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::get('verify-email', EmailVerificationPromptController::class)->name('verification.notice');
    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');
    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');
    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])->name('password.confirm');
    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);
    Route::put('password', [PasswordController::class, 'update'])->name('password.update');
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('instructor-doc-download/{user}', [InstructorRequestController::class, 'download'])->name('instructor-doc-download');
    Route::resource('instructor-request', InstructorRequestController::class);
    /*******************************************************
     * INSTRUCTOR & STUDENT & STAFF & INTERN
     *******************************************************/
    // intern resource route
    Route::resource('intern', InternController::class);
    Route::patch('/intern/{user}/toggle', [InternController::class, 'toggle'])->name('intern.toggle');


    Route::get('/instructor', [InstructorControlller::class, 'index'])->name('instructor.index');
    Route::post('/instructor', [InstructorControlller::class, 'store'])->name('instructor.store');
    Route::get('/instructor/{id}', [InstructorControlller::class, 'instructorShowDetail'])->name('instructor.show.detail');
    Route::patch('/instructor/{instructor}/toggle', [InstructorControlller::class, 'toggle'])->name('instructor.toggle');
    Route::delete('/instructor/{instructor}', [InstructorControlller::class, 'destroy'])->name('instructor.destroy');

    Route::get('/student', [StudentController::class, 'index'])->name('student.index');
    Route::get('/student/{student}', [StudentController::class, 'show'])->name('student.show');

    Route::get('/staff', [StaffController::class, 'index'])->name('staff.index');
    Route::get('/staff/create', [StaffController::class, 'create'])->name('staff.create');
    Route::post('/staff', [StaffController::class, 'store'])->name('staff.store');
    Route::get('/staff/edit/{id}', [StaffController::class, 'edit'])->name('staff.edit');
    Route::put('/staff/{id}', [StaffController::class, 'update'])->name('staff.update');
    Route::delete('/staff/{id}', [StaffController::class, 'destroy'])->name('staff.destroy');
    Route::patch('/staff/{id}/toggle', [StaffController::class, 'toggle'])->name('staff.toggle');
    Route::patch('/staff/{id}/toggle-access', [StaffController::class, 'toggleAccess'])->name('staff.toggle-access');
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
    // Admin is view-only for existing courses — creating a new one is still
    // allowed above, but editing/deleting one is not. The edit/update/destroy
    // routes were removed (not just their buttons) so a direct URL/request
    // can't reach them either; RealTimeCoursesController::edit()/update()/
    // destroy() are left in place but are now unreachable dead code.
    /*******************************************************
     * STUDENT INVOICE DETAIL IN COURSE
     *******************************************************/
    Route::get('courses/{course}/students/{student}/invoice', [StudentInvoicePaymentDetailController::class, 'studentInvoice'])
        ->name('courses.student.invoice')
        ->scopeBindings();
    /*******************************************************
     * INVOICES (admin-wide list + quick-view modal)
     *******************************************************/
    Route::get('/invoices', [StudentInvoicePaymentDetailController::class, 'index'])->name('invoices.index');
    Route::get('/invoices/{invoice}/quick-view', [StudentInvoicePaymentDetailController::class, 'quickView'])->name('invoices.quick-view');
    /*******************************************************
     *  STUDENT REPORT
     *******************************************************/
    Route::get('/student-report', [StudentReportController::class, 'studentReport'])->name('student-report.index');
    Route::post('/student-report/approve/{course}', [StudentReportController::class, 'approve'])->name('student-report.approve');
    Route::patch('/student-report/reject/{course}', [StudentReportController::class, 'reject'])->name('student-report.reject');
    /*******************************************************
     *  STUDENT ATTENDANCE (read-only — admin has no use for the
     *  day-by-day "Student's Attendance" grid, so that tab and its
     *  `student-attendance.get` route were removed; the Session Log
     *  tab, which reuses the same read-only controller method, still
     *  gives admin the full per-session history)
     *******************************************************/
    Route::get('/student-attendance/session-log', [StudentAttendanceController::class, 'sessionLog'])
        ->name('student-attendance.session-log');
    /*******************************************************
     *  STAFF REPORT
     *******************************************************/
    Route::get('/staff-report', [StaffReportController::class, 'index'])->name('staff-report.index');
    Route::patch('/staff-report/{report}/review', [StaffReportController::class, 'review'])->name('staff-report.review');
    /*******************************************************
     *  ITERN REPORT
     *******************************************************/
    Route::get('/intern-report', [InternReportController::class, 'index'])->name('intern-report.index');
    Route::patch('/intern-report/{report}/review', [InternReportController::class, 'review'])->name('intern-report.review');
    /*******************************************************
     *  NOTIFICATION
     *******************************************************/
    Route::post('/notifications/read/{id}', [AdminDashboardController::class, 'readNotification'])->name('notifications.read');
    Route::post('/notifications/read-all', [AdminDashboardController::class, 'readAllNotifications'])->name('notifications.read-all');
    /*******************************************************
     *  PROFILE
     *******************************************************/
    Route::get('/profile', [ProfileUpdateController::class, 'profile'])->name('profile.index');
    Route::post('/profile', [ProfileUpdateController::class, 'update'])->name('profile.update');
    Route::post('/profile/password', [ProfileUpdateController::class, 'updatePassword'])->name('profile.password.update');
    Route::post('/profile/social', [ProfileUpdateController::class, 'updateSocial'])->name('profile.social.update');
    /*******************************************************
     *  Blog
     *******************************************************/
    Route::resource('blogs', BlogController::class);
    Route::post('blogs/fetch-thumbnail', [BlogController::class, 'fetchThumbnail'])->name('blogs.fetch-thumbnail');
});
