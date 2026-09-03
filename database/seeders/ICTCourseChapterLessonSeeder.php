<?php

namespace Database\Seeders;

use App\Models\ICTCourseChapter;
use App\Models\ICTCourseChapterLesson;
use Illuminate\Database\Seeder;

class ICTCourseChapterLessonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Adds 3 generic lessons to every chapter created by
     * ICTCourseChapterSeeder, so course-content / curriculum views have
     * something to expand and reorder.
     */
    public function run(): void
    {
        $lessonTitlesByChapter = [
            'Introduction & Setup' => ['Welcome & Course Overview', 'Environment Setup', 'Your First Exercise'],
            'Core Concepts' => ['Key Concept Walkthrough', 'Common Patterns', 'Best Practices'],
            'Hands-on Practice' => ['Guided Exercise 1', 'Guided Exercise 2', 'Q&A / Troubleshooting'],
            'Final Project & Wrap-up' => ['Project Brief', 'Building the Project', 'Review & Next Steps'],
        ];

        ICTCourseChapter::orderBy('id')->each(function (ICTCourseChapter $chapter) use ($lessonTitlesByChapter): void {
            $lessons = $lessonTitlesByChapter[$chapter->title] ?? ['Lesson 1', 'Lesson 2', 'Lesson 3'];

            foreach ($lessons as $order => $title) {
                ICTCourseChapterLesson::updateOrCreate(
                    ['chapter_id' => $chapter->id, 'title' => $title],
                    [
                        'course_id' => $chapter->course_id,
                        'order' => $order + 1,
                        'status' => true,
                    ]
                );
            }
        });
    }
}
