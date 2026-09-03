<?php

namespace Database\Seeders;

use App\Models\ICTCourse;
use App\Models\ICTCourseEnrollments;
use App\Models\User;
use Illuminate\Database\Seeder;

class ICTCourseEnrollmentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Enrolls a random slice of the seeded students into every non-draft
     * course. Finished/"inactive" courses get mostly 'completed' students;
     * everything else is mostly 'active' with a couple of 'dropped' rows
     * so the enrollment-status filters have something to show.
     */
    public function run(): void
    {
        $students = User::where('role', 'student')->where('approval_status', 'approved')->pluck('id')->all();
        $staffIds = User::where('role', 'staff')->pluck('id')->all();

        if (empty($students)) {
            $this->command?->warn('ICTCourseEnrollmentsSeeder: no approved students found — run UserSeeder first.');
            return;
        }

        ICTCourse::where('status', '!=', 'draft')->orderBy('id')->each(function (ICTCourse $course) use ($students, $staffIds): void {
            $capacity = $course->capacity ?: 20;
            $enrollCount = min(count($students), max(4, (int) round($capacity * 0.6)));

            $shuffled = $students;
            shuffle($shuffled);
            $selected = array_slice($shuffled, 0, $enrollCount);

            foreach ($selected as $i => $studentId) {
                $status = match (true) {
                    $course->status === 'inactive' => $i % 6 === 0 ? 'dropped' : 'completed',
                    $i % 9 === 0 => 'dropped',
                    $i % 5 === 0 => 'completed',
                    default => 'active',
                };

                $enrolledAt = $course->start_date
                    ? \Illuminate\Support\Carbon::parse($course->start_date)->subDays(rand(1, 10))
                    : now()->subDays(rand(5, 60));

                ICTCourseEnrollments::updateOrCreate(
                    ['course_id' => $course->id, 'student_id' => $studentId],
                    [
                        'enrolled_by' => $staffIds[array_rand($staffIds)] ?? null,
                        'status' => $status,
                        'enrolled_at' => $enrolledAt,
                    ]
                );
            }
        });
    }
}
