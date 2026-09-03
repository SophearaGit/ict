<?php

namespace Database\Seeders;

use App\Models\ICTCourse;
use App\Models\ICTCourseChapter;
use Illuminate\Database\Seeder;

class ICTCourseChapterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Gives every seeded ICT course a generic 4-chapter curriculum shell
     * (Introduction -> Core Concepts -> Hands-on Practice -> Final Project)
     * so the curriculum builder and student course-content pages aren't
     * empty. ICTCourseChapterLessonSeeder fills each chapter with lessons.
     */
    public function run(): void
    {
        $chapterTitles = [
            'Introduction & Setup',
            'Core Concepts',
            'Hands-on Practice',
            'Final Project & Wrap-up',
        ];

        ICTCourse::orderBy('id')->each(function (ICTCourse $course) use ($chapterTitles): void {
            foreach ($chapterTitles as $order => $title) {
                ICTCourseChapter::updateOrCreate(
                    ['course_id' => $course->id, 'title' => $title],
                    ['order' => $order + 1, 'status' => true]
                );
            }
        });
    }
}
