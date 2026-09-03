<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\CourseChapter;
use Illuminate\Database\Seeder;

class CourseChapterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * NOTE: CourseChapter::$fillable doesn't include 'order' (a required,
     * non-nullable *string* column on course_chapters — not the same
     * type as ICTCourseChapter's integer 'order'), so this uses
     * forceFill() rather than create()/updateOrCreate() to set it.
     */
    public function run(): void
    {
        $chapterTitles = ['Getting Started', 'Core Lessons', 'Final Project'];

        Course::orderBy('id')->each(function (Course $course) use ($chapterTitles): void {
            foreach ($chapterTitles as $index => $title) {
                $chapter = CourseChapter::where('course_id', $course->id)->where('title', $title)->first()
                    ?? new CourseChapter();

                $chapter->forceFill([
                    'title' => $title,
                    'instructor_id' => $course->instructor_id,
                    'course_id' => $course->id,
                    'order' => (string) ($index + 1),
                    'status' => true,
                ])->save();
            }
        });
    }
}
