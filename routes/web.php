<?php
use App\Http\Controllers\Frontend\{CourseContentController, CourseController, CoursePageController, FrontendController, InstructorDashboardController, ProfileController, RealTimeCoursesController, StudentDashboardController};
use App\Http\Controllers\Frontend\Staff\{BakongPaymentController, IctInvoicePaymentController, CertificateController, IctCourseCategoryController, StudentReportController, IctCourseController, IctScheduleController, StaffDashboardController, IctInvoiceController, StudentRegisterationController, IctStaffReportController, InternController, StudentController, TeacherController, TecherAttendancesController};
use App\Http\Controllers\Frontend\Student\CourseEnrollmentController;
use App\Http\Controllers\Frontend\Teacher\StudentAttendanceController;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;
/*******************************************************
 * GLOBAL
 *******************************************************/
Route::get('/', [FrontendController::class, 'index'])->name('home');
Route::get('/contact', [FrontendController::class, 'contact'])->name('contact');
Route::get('/course', [CoursePageController::class, 'course'])->name('course');
Route::get('/course/{slug}', [CoursePageController::class, 'courseDetails'])->name('course.details');
Route::get('/about', [FrontendController::class, 'about'])->name('about');
Route::get('/blogs', [FrontendController::class, 'blog'])->name('blog');
Route::get('/blogs/{slug}', [FrontendController::class, 'blogDetails'])->name('blog.details');

/*******************************************************
 * STUDENT
 *******************************************************/
Route::middleware(['auth:web', 'verified', 'check_role:student'])
    ->prefix('student')
    ->name('student.')
    ->group(function (): void {
        /*
        |--------------------------------------------------------------------------
        | COURSE ENROLLMENT + PAYMENT
        |--------------------------------------------------------------------------
        */
        Route::post('/course/{course}/enroll', [CourseEnrollmentController::class, 'startEnrollment'])->name('course.enroll');
        Route::get('/payment/{invoice}', [CourseEnrollmentController::class, 'paymentPage'])->name('payment.page');
        /*******************************************************
         * DASHBOARD
         *******************************************************/
        Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');
        Route::get('/my-courses/{course_id}', [StudentDashboardController::class, 'myCourseDetail'])->name('my.course.detail');
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
        Route::get('/courses/{course}/schedules', [CourseController::class, 'schedules'])
            ->name('course.schedules');
    });
/*******************************************************
 * INSTRUCTOR
 *******************************************************/
Route::middleware(['auth:web', 'verified', 'check_role:instructor'])
    ->prefix('instructor')
    ->name('instructor.')
    ->group(function () {
        // NOTIFICATIONS
        Route::post('/notifications/read/{id}', [InstructorDashboardController::class, 'readNotification'])->name('notifications.read');
        Route::post('/notifications/read-all', [InstructorDashboardController::class, 'readAllNotifications'])->name('notifications.read-all');
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
        /*******************************************************
         * STUDENT REPORT
         *******************************************************/
        Route::post('/student-report/update/{id}', [StudentReportController::class, 'update']);
        Route::post('/student-report/request-approval/{course}', [StudentReportController::class, 'requestApproval'])->name('student-report.request-approval');
        Route::post('/student-report/cancel-approval/{course}', [StudentReportController::class, 'cancelApproval'])->name('student-report.cancel-approval');
        Route::post('/student-attendance/save', [StudentAttendanceController::class, 'store'])->name('student-attendance.store');
        Route::get('/student-attendance', [StudentAttendanceController::class, 'getByDate'])->name('student-attendance.get');
        Route::post('/student-attendance/reset', [StudentAttendanceController::class, 'reset'])->name('student-attendance.reset');
        Route::get('/student-attendance/session-log', [StudentAttendanceController::class, 'sessionLog'])
            ->name('student-attendance.session-log');
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
         * TEACHER & STUDENT RESOURCE ROUTES
         *******************************************************/
        Route::resource('/teacher', TeacherController::class);
        Route::resource('/student', StudentController::class);
        Route::resource('/intern', InternController::class);
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
        Route::put('/invoice/{invoice_id}/confirm-payment', [IctInvoiceController::class, 'updatePayment'])->name('invoice.confirm-payment');
        Route::delete('/invoice/{invoice_id}', [IctInvoiceController::class, 'destroy'])->name('invoice.destroy');
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
        Route::get('/courses/{course}/edit', [IctCourseController::class, 'edit'])->name('courses.edit');
        Route::put('/courses/{course}', [IctCourseController::class, 'update'])
            ->name('courses.update');
        Route::get('/courses/{course}', [IctCourseController::class, 'show'])->name('courses.show');
        Route::delete('courses/{course}', [IctCourseController::class, 'destroy'])
            ->name('courses.destroy');
        /*******************************************************
         * CATEGORY
         *******************************************************/
        Route::resource('/course-categories', IctCourseCategoryController::class);
        /*******************************************************
         * STUDENT INVOICE DETAIL IN COURSE
         *******************************************************/
        Route::get('courses/{course}/students/{student}/invoice', [IctInvoicePaymentController::class, 'studentInvoice'])
            ->name('courses.student.invoice')
            ->scopeBindings();
        /*******************************************************
         * MOVE STUDENT TO ANOTHER COURSE & REMOVE STUDENT FROM COURSE
         *******************************************************/
        Route::post('/courses/{course_id}/move-student', [IctCourseController::class, 'moveStudent'])->name('courses.move-student');
        Route::post('/courses/{course_id}/remove-student', [IctCourseController::class, 'removeStudent'])->name('courses.remove-student');
        /*******************************************************
         * PRINT CERTIFICATE
         *******************************************************/
        Route::post('/staff/certificates/print', [CertificateController::class, 'print'])->name('certificates.print');
        /*******************************************************
         * TEACHER ATTENDANCES
         *******************************************************/
        Route::post('/teacher-attendance/update', [TecherAttendancesController::class, 'update'])->name('teacher.attendance.update');
        Route::resource('/schedules', IctScheduleController::class);
        /*******************************************************
         * REPORTS RESOURCE ROUTES
         *******************************************************/
        Route::resource('/reports', IctStaffReportController::class);
        /*******************************************************
         * STUDENT REPORT
         *******************************************************/
        Route::post('/student-report/update/{id}', [StudentReportController::class, 'update']);
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
Route::middleware(['auth:web', 'verified'])
    ->prefix('bakong')
    ->name('bakong.')
    ->group(function () {
        Route::post('/generate-qr', [BakongPaymentController::class, 'generateQr'])->name('generate-qr');
        Route::post('/verify-hash', [BakongPaymentController::class, 'verifyByHash'])->name('verify-hash');
    });
// Additional Routes
require __DIR__ . '/admin.php';
require __DIR__ . '/auth.php';
require __DIR__ . '/intern.php';
