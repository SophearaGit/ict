<?php

namespace App\Http\Controllers\Frontend\Staff\Concerns;

use App\Models\ICTCourse;

/**
 * Shared by IctCourseController (create/edit form) and
 * IctCourseOverviewController (dedicated overview page) — both submit
 * the same shape of data: a flat array of strings per bullet list.
 */
trait SyncsCourseBulletPoints
{
    /**
     * Replace a course's bullet-list child rows with the submitted list,
     * in submitted order. Blank rows (empty text left in the repeater)
     * are dropped silently.
     *
     * @param class-string<\Illuminate\Database\Eloquent\Model> $modelClass
     * @param array<int, string|null> $lines
     */
    private function syncCourseBulletPoints(ICTCourse $course, string $modelClass, array $lines): void
    {
        $modelClass::where('course_id', $course->id)->delete();

        $rows = collect($lines)
            ->map(fn ($line) => trim((string) $line))
            ->filter()
            ->values()
            ->map(fn ($content, $index) => [
                'course_id' => $course->id,
                'content' => $content,
                'order' => $index,
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

        if ($rows->isNotEmpty()) {
            $modelClass::insert($rows->all());
        }
    }
}
