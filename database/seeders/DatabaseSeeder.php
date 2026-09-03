<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     *
     * Order matters — each seeder here only runs after the seeders whose
     * data it depends on (users/admins before courses, courses before
     * enrollments, enrollments before invoices/attendance, attendance
     * before the reports that summarize it, etc.). Previously only 9 of
     * the 27 seeders in database/seeders were actually wired up here;
     * the rest either didn't run at all or were empty stubs.
     */
    public function run(): void
    {
        $this->call([
            // People
            AdminSeeder::class,
            UserSeeder::class,

            // Legacy self-paced course taxonomy (course_languages/levels/categories)
            CourseLanguageSeeder::class,
            CourseLevelSeeder::class,
            CourseCategorySeeder::class,

            // Real-time / cohort ICT course system
            ICTScheduleSeeder::class,
            ICTCourseCategorySeeder::class,
            ICTCourseSeeder::class,
            ICTCourseChapterSeeder::class,
            ICTCourseChapterLessonSeeder::class,
            ICTCourseLearningPointSeeder::class,
            ICTCourseRequirementSeeder::class,

            // Enrollment -> billing chain
            ICTCourseEnrollmentsSeeder::class,
            ICTInvoiceSeeder::class,
            ICTInvoiceItemsSeeder::class,
            ICTPaymentsSeeder::class,

            // Attendance — teacher sessions only. Student attendance is
            // intentionally NOT seeded: it's marked live through the
            // instructor's Mark Attendance / Session Log UI, and fake
            // pre-filled rows there just get in the way of testing that.
            TeacherAttendancesSeeder::class,

            // Reports (present/absent/permission start at 0 with no
            // seeded attendance — they fill in as real attendance is marked)
            StudentReportsSeeder::class,
            ICTStaffReportSeeder::class,
            InternReportSeeder::class,

            // Content
            BlogSeeder::class,

            // Legacy self-paced "online course" builder
            CourseSeeder::class,
            CourseChapterSeeder::class,
            CourseChapterLessonSeeder::class,

            // Project showcase
            ProjectCategorySeeder::class,
            ProjectSeeder::class,
        ]);
    }
}
