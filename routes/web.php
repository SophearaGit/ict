<?php

use App\Http\Controllers\Frontend\{
    CourseContentController,
    CourseController,
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

        Route::get('/profile-edit', [ProfileController::class, 'teacherProfileEdit'])->name('profile.edit');
        Route::post('/profile-edit-update', [ProfileController::class, 'teacherProfileUpdate'])->name('profile.edit.update');
        Route::get('/security', [ProfileController::class, 'teacherSecurity'])->name('security');
        Route::post('/security-update', [ProfileController::class, 'teacherSecurityUpdate'])->name('security.update');
        Route::get('/social-profile', [ProfileController::class, 'teacherSocialProfile'])->name('social.profile');
        Route::post('/social-profile-update', [ProfileController::class, 'teacherSocialProfileUpdate'])->name('social.profile.update');

        Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
        Route::get('/courses/create', [CourseController::class, 'create'])->name('courses.create');
        Route::post('/courses/create', action: [CourseController::class, 'storeBasicInfo'])->name('courses.store_basic_info');
        Route::get('/courses/{id}/edit', [CourseController::class, 'edit'])->name('courses.edit');
        Route::post('/courses/update', action: [CourseController::class, 'update'])->name('courses.update');

        /**
         * ————————————————————————————————————————————————————————————————————————————————
         * COURSE CHAPTER ROUTE
         * ————————————————————————————————————————————————————————————————————————————————
         */
        Route::get('/course-content/{course_id}/create-chapter', [CourseContentController::class, 'createChapterModal'])->name('course-content.create-chapter');
        Route::post('/course-content/{course_id}/create-chapter', [CourseContentController::class, 'storeChapterModal'])->name('course-content.store-chapter');
        Route::get('/course-content/{chapter_id}/edit-chapter', [CourseContentController::class, 'editChapterModal'])->name('course-content.edit-chapter');
        Route::post('/course-content/{chapter_id}/edit-chapter', [CourseContentController::class, 'updateChapterModal'])->name('course-content.update-chapter');
        Route::delete('/course-content/{chapter_id}/delete-chapter', [CourseContentController::class, 'deleteChapterModal'])->name('course-content.delete-chapter');

        /**
         * ————————————————————————————————————————————————————————————————————————————————
         * COURSE LESSON ROUTE
         * ————————————————————————————————————————————————————————————————————————————————
         */
        Route::get('/course-content/create-lesson', [CourseContentController::class, 'createLessonModal'])->name('course-content.create-lesson');
        Route::post('/course-content/create-lesson', [CourseContentController::class, 'storeLessonModal'])->name('course-content.store-lesson');
        Route::get('/course-content/edit-lesson', [CourseContentController::class, 'editLessonModal'])->name('course-content.edit-lesson');
        Route::post('/course-content/{id}/update-lesson', [CourseContentController::class, 'updateLessonModal'])->name('course-content.update-lesson');
        Route::delete('/course-content/{id}/delete-lesson', [CourseContentController::class, 'deleteLessonModal'])->name('course-content.delete-lesson');

        // SORT LESSON ROUTE
        Route::post('/course-chapter/{chapter_id}/sort-lesson', [CourseContentController::class, 'sortLesson'])->name('course-chapter.sort-lesson');












        // LFM Routes
        Route::group(['prefix' => 'laravel-filemanager', 'middleware' => ['web', 'auth']], function () {
            \UniSharp\LaravelFilemanager\Lfm::routes();
        });
    });

// Additional Routes
require __DIR__ . '/admin.php';
require __DIR__ . '/auth.php';
