<?php

namespace Database\Seeders;

use App\Models\CourseChapter;
use App\Models\CourseChapterLesson;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CourseChapterLessonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * NOTE: CourseChapterLesson declares no $fillable/$guarded at all, so
     * it's fully mass-assignment-guarded by default — forceFill() is used
     * here for the same reason as CourseChapterSeeder.
     */
    public function run(): void
    {
        $lessonTitles = ['Overview', 'Deep Dive', 'Practice Exercise'];

        CourseChapter::orderBy('id')->each(function (CourseChapter $chapter) use ($lessonTitles): void {
            if (! $chapter->course_id) {
                return;
            }

            foreach ($lessonTitles as $index => $title) {
                $slug = Str::slug($chapter->title . '-' . $title);

                $lesson = CourseChapterLesson::where('chapter_id', $chapter->id)->where('slug', $slug)->first()
                    ?? new CourseChapterLesson();

                $lesson->forceFill([
                    'title' => $title,
                    'slug' => $slug,
                    'description' => null,
                    'instructor_id' => $chapter->instructor_id,
                    'course_id' => $chapter->course_id,
                    'chapter_id' => $chapter->id,
                    'file_path' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                    'storage' => 'youtube',
                    'volume' => null,
                    'duration' => '10:00',
                    'file_type' => 'video',
                    'downloadable' => false,
                    'order' => $index + 1,
                    'is_preview' => $index === 0,
                    'status' => true,
                    'lesson_type' => 'lesson',
                ])->save();
            }
        });
    }
}
