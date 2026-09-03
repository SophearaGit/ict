<?php

namespace Database\Seeders;

use App\Models\ICTCourseEnrollments;
use App\Models\StudentAttendances;
use App\Models\TeacherAttendances;
use Illuminate\Database\Seeder;

class StudentAttendancesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * For every session TeacherAttendancesSeeder created, mark every
     * actively-enrolled (or completed) student present/permission/absent.
     * Each student gets their own "reliability" roll so attendance isn't
     * uniformly perfect across the class.
     */
    public function run(): void
    {
        $sessionDatesByCourse = TeacherAttendances::orderBy('date')
            ->get(['course_id', 'date'])
            ->groupBy('course_id')
            ->map(fn ($rows) => $rows->pluck('date'));

        ICTCourseEnrollments::whereIn('status', ['active', 'completed'])
            ->orderBy('course_id')
            ->get(['course_id', 'student_id'])
            ->groupBy('course_id')
            ->each(function ($enrollments, $courseId) use ($sessionDatesByCourse): void {
                $dates = $sessionDatesByCourse->get($courseId, collect());
                if ($dates->isEmpty()) {
                    return;
                }

                foreach ($enrollments as $enrollment) {
                    // Each student's own attendance rate for this course (70-98%).
                    $reliability = rand(70, 98) / 100;

                    foreach ($dates as $date) {
                        $roll = mt_rand(0, 1000) / 1000;
                        $status = match (true) {
                            $roll < $reliability => 'present',
                            $roll < $reliability + 0.07 => 'permission',
                            default => 'absent',
                        };

                        StudentAttendances::updateOrCreate(
                            [
                                'course_id' => $courseId,
                                'student_id' => $enrollment->student_id,
                                'date' => $date,
                            ],
                            [
                                'status' => $status,
                                'note' => null,
                            ]
                        );
                    }
                }
            });
    }
}
