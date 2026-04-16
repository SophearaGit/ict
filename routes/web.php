<?php

use App\Http\Controllers\Frontend\{
    CourseContentController,
    CourseController,
    FrontendController,
    InstructorDashboardController,
    ProfileController,
    RealTimeCoursesController,
    StudentDashboardController,
    TeacherCourseController
};

use App\Http\Controllers\Frontend\Staff\{
    IctCourseController,
    IctScheduleController,
    StaffDashboardController,
    IctInvoiceController,
    StudentRegisterationController,
    IctStaffReportController,
    TecherAttendancesController
};
use App\Http\Controllers\Frontend\Teacher\StudentAttendanceController;
use App\Models\TeacherAttendances;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

/*******************************************************
 * GLOBAL
 *******************************************************/
Route::get('/', [FrontendController::class, 'index'])->name('home');


/*******************************************************
 * STUDENT
 *******************************************************/
Route::middleware(['auth:web', 'verified', 'check_role:student'])
    ->prefix('student')
    ->name('student.')
    ->group(function (): void {


        /*******************************************************
         * DASHBOARD
         *******************************************************/
        Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');

        /*******************************************************
         * INSTRUCTOR REQUEST
         *******************************************************/
        Route::get('/become-instructor', [StudentDashboardController::class, 'becomeInstructor'])->name('become.instructor');
        Route::post('/become-instructor/{user}', [StudentDashboardController::class, 'becomeInstructorSubmit'])->name('become.instructor.submit');

        /*******************************************************
         * PROFILE
         *******************************************************/
        Route::get('/profile-edit', [ProfileController::class, 'getEditProfile'])->name('profile.edit');
        Route::post('/profile-edit-submit', [ProfileController::class, 'profileEditSubmit'])->name('profile.edit.submit');
        Route::get('/security', [ProfileController::class, 'security'])->name('security');
        Route::post('/security-update', [ProfileController::class, 'securityUpdate'])->name('security.update');
        Route::get('/social-profile', [ProfileController::class, 'socialProfile'])->name('social.profile');
        Route::post('/social-profile-update', [ProfileController::class, 'socialProfileUpdate'])->name('social.profile.update');


    });


/*******************************************************
 * INSTRUCTOR
 *******************************************************/
Route::middleware(['auth:web', 'verified', 'check_role:instructor'])
    ->prefix('instructor')
    ->name('instructor.')
    ->group(function () {


        /*******************************************************
         * DASHBOARD
         *******************************************************/
        Route::get('/dashboard', [InstructorDashboardController::class, 'index'])->name('dashboard');

        /*******************************************************
         * PROFILE
         *******************************************************/
        Route::get('/profile-edit', [ProfileController::class, 'teacherProfileEdit'])->name('profile.edit');
        Route::post('/profile-edit-update', [ProfileController::class, 'teacherProfileUpdate'])->name('profile.edit.update');
        Route::get('/security', [ProfileController::class, 'teacherSecurity'])->name('security');
        Route::post('/security-update', [ProfileController::class, 'teacherSecurityUpdate'])->name('security.update');
        Route::get('/social-profile', [ProfileController::class, 'teacherSocialProfile'])->name('social.profile');
        Route::post('/social-profile-update', [ProfileController::class, 'teacherSocialProfileUpdate'])->name('social.profile.update');

        /*******************************************************
         * COURSE
         *******************************************************/
        Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
        Route::get('/courses/create', [CourseController::class, 'create'])->name('courses.create');
        Route::post('/courses/create', action: [CourseController::class, 'storeBasicInfo'])->name('courses.store_basic_info');
        Route::get('/courses/{id}/edit', [CourseController::class, 'edit'])->name('courses.edit');
        Route::post('/courses/update', action: [CourseController::class, 'update'])->name('courses.update');

        /*******************************************************
         * COURSE REAL TIME
         *******************************************************/
        Route::get('/courses/real-time', [RealTimeCoursesController::class, 'index'])->name('courses.real_time');
        Route::get('/courses/real-time/{course_id}', [RealTimeCoursesController::class, 'show'])->name('courses.real_time.show');
        Route::post('/student-attendance/save', [StudentAttendanceController::class, 'store'])
            ->name('student-attendance.store');
        Route::get('/student-attendance', [StudentAttendanceController::class, 'getByDate'])
            ->name('student-attendance.get');

        /*******************************************************
         * CHAPTER
         *******************************************************/
        Route::get('/course-content/{course_id}/create-chapter', [CourseContentController::class, 'createChapterModal'])->name('course-content.create-chapter');
        Route::post('/course-content/{course_id}/create-chapter', [CourseContentController::class, 'storeChapterModal'])->name('course-content.store-chapter');
        Route::get('/course-content/{chapter_id}/edit-chapter', [CourseContentController::class, 'editChapterModal'])->name('course-content.edit-chapter');
        Route::post('/course-content/{chapter_id}/edit-chapter', [CourseContentController::class, 'updateChapterModal'])->name('course-content.update-chapter');
        Route::delete('/course-content/{chapter_id}/delete-chapter', [CourseContentController::class, 'deleteChapterModal'])->name('course-content.delete-chapter');

        /*******************************************************
         * LESSON
         *******************************************************/
        Route::get('/course-content/create-lesson', [CourseContentController::class, 'createLessonModal'])->name('course-content.create-lesson');
        Route::post('/course-content/create-lesson', [CourseContentController::class, 'storeLessonModal'])->name('course-content.store-lesson');
        Route::get('/course-content/edit-lesson', [CourseContentController::class, 'editLessonModal'])->name('course-content.edit-lesson');
        Route::post('/course-content/{id}/update-lesson', [CourseContentController::class, 'updateLessonModal'])->name('course-content.update-lesson');
        Route::delete('/course-content/{id}/delete-lesson', [CourseContentController::class, 'deleteLessonModal'])->name('course-content.delete-lesson');
        Route::post('/course-chapter/{chapter_id}/sort-lesson', [CourseContentController::class, 'sortLesson'])->name('course-chapter.sort-lesson');

        /*******************************************************
         * LARAVEL FILE MANAGER
         *******************************************************/
        Route::group(['prefix' => 'laravel-filemanager', 'middleware' => ['web', 'auth']], function () {
            \UniSharp\LaravelFilemanager\Lfm::routes();
        });


    });


/*******************************************************
 * STAFF
 *******************************************************/
Route::middleware(['auth:web', 'verified', 'check_role:staff'])
    ->prefix('staff')
    ->name('staff.')
    ->group(function (): void {
        Route::get('/dashboard', [StaffDashboardController::class, 'index'])->name('dashboard');

        /*******************************************************
         * PROFILE
         *******************************************************/
        Route::get('/profile-edit', [ProfileController::class, 'StaffProfileEdit'])->name('profile.edit');
        Route::post('/profile-edit-update', [ProfileController::class, 'StaffProfileUpdate'])->name('profile.edit.update');
        Route::post('/social-profile-update', [ProfileController::class, 'StaffSocialProfileUpdate'])->name('social.profile.update');

        /*******************************************************
         * INVOICES
         *******************************************************/
        Route::get('/invoices', [IctInvoiceController::class, 'invoices'])->name('invoices');
        Route::get('/invoice-detail/{invoice_id}', [IctInvoiceController::class, 'getInvoiceDetail'])->name('invoice.detail');
        // Route::get('/invoice-confirm-payment/{invoice_id}', [IctInvoiceController::class, 'confirmPayment'])->name('invoice.confirm.payment');
        Route::put('/invoice/{invoice_id}/confirm-payment', [IctInvoiceController::class, 'updatePayment'])->name('invoice.confirm-payment');

        /*******************************************************
         * STUDENT REGISTRATION
         *******************************************************/
        Route::get('/student-registration', [StudentRegisterationController::class, 'studentRegistration'])->name('student.registration');
        Route::post('/student-registration', [StudentRegisterationController::class, 'studentRegistrationSubmit'])->name('student.registration.submit');

        /*******************************************************
         * COURSE MANAGEMENT
         *******************************************************/
        Route::get('/courses', [IctCourseController::class, 'index'])->name('courses.index');
        Route::get('/courses/create', [IctCourseController::class, 'create'])->name('courses.create');
        Route::post('/courses/create', [IctCourseController::class, 'store'])->name('courses.store');
        Route::get('/courses/{id}', [IctCourseController::class, 'show'])->name('courses.show');
        Route::get('/courses/{id}/edit', [IctCourseController::class, 'edit'])
            ->name('courses.edit');
        Route::put('/courses/{id}', [IctCourseController::class, 'update'])
            ->name('courses.update');

        /*******************************************************
         * TEACHER ATTENDANCES
         *******************************************************/
        // update teacher attendance
        Route::post('/teacher-attendance/update', [TecherAttendancesController::class, 'update'])
            ->name('teacher.attendance.update');
        Route::resource('/schedules', IctScheduleController::class);

        /*******************************************************
         * REPORTS RESOURCE ROUTES
         *******************************************************/
        Route::resource('/reports', IctStaffReportController::class);


    });

/*******************************************************
 * 404 NOT FOUND
 *******************************************************/
Route::fallback(function (): Response {
    $data = [
        'page_title' => 'ICT | Page Not Found',
    ];
    return response()->view('errors.404', $data, Response::HTTP_NOT_FOUND);
})->name('404');




// Additional Routes
require __DIR__ . '/admin.php';
require __DIR__ . '/auth.php';
