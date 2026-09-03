<?php

namespace Database\Seeders;

use App\Models\ICTCourse;
use App\Models\TeacherAttendances;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TeacherAttendancesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Generates a session log per non-draft course, roughly spaced every
     * 2 days starting at the course's start_date, using its schedule's
     * start/end time as the per-session length.
     *
     * `actual_hours` is stored as a running cumulative total (not a
     * per-session delta) because ICTCourse::progress / revenue /
     * completedSessions all read it off the *latest* attendance row —
     * see ICTCourse::getProgressAttribute() etc.
     */
    public function run(): void
    {
        ICTCourse::where('status', '!=', 'draft')->with('schedule')->orderBy('id')->each(function (ICTCourse $course): void {
            $schedule = $course->schedule;
            if (! $schedule) {
                return;
            }

            $sessionHours = round(
                Carbon::parse($schedule->start_time)->diffInMinutes(Carbon::parse($schedule->end_time)) / 60,
                2
            );
            $sessionHours = $sessionHours > 0 ? $sessionHours : 2.0;

            $totalDuration = (float) ($course->duration ?: 24);
            $sessionsCount = (int) min(18, max(4, round($totalDuration / $sessionHours)));

            // Finished ("inactive") courses ran their full schedule; active
            // courses are only part-way through, capped at "today".
            $today = Carbon::today();
            $date = $course->start_date ? Carbon::parse($course->start_date) : $today->copy()->subMonths(2);

            $cumulativeActual = 0.0;

            for ($i = 0; $i < $sessionsCount; $i++) {
                if ($i > 0) {
                    $date = $date->copy()->addDays(2);
                }

                if ($course->status !== 'inactive' && $date->greaterThan($today)) {
                    break; // an in-progress course shouldn't have future attendance
                }

                $roll = $i % 10;
                $status = match (true) {
                    $roll === 9 => 'absent',
                    $roll === 5 => 'late',
                    default => 'present',
                };

                $lateMinutes = $status === 'late' ? rand(5, 20) : null;
                $sessionActual = $status === 'absent' ? 0.0 : round($sessionHours - ($lateMinutes ? $lateMinutes / 60 : 0), 2);
                $cumulativeActual = round($cumulativeActual + $sessionActual, 2);

                // 'remarks' and 'signature' aren't in TeacherAttendances::$fillable,
                // so a plain updateOrCreate() would silently drop them —
                // forceFill() so the seeded remark actually persists.
                $attendance = TeacherAttendances::where('course_id', $course->id)
                    ->where('date', $date->format('Y-m-d'))
                    ->first() ?? new TeacherAttendances();

                $attendance->forceFill([
                    'course_id' => $course->id,
                    'teacher_id' => $course->instructor_id,
                    'schedule_id' => $course->schedule_id,
                    'date' => $date->format('Y-m-d'),
                    'start_time' => $schedule->start_time,
                    'end_time' => $schedule->end_time,
                    'total_hours' => $sessionHours,
                    'actual_hours' => $cumulativeActual,
                    'room' => 'Room ' . chr(65 + ($course->id % 4)),
                    'late_minutes' => $lateMinutes,
                    'late_reason' => $status === 'late' ? 'Traffic delay' : null,
                    'status' => $status,
                    'remarks' => $status === 'absent' ? 'Instructor unavailable — makeup session scheduled.' : null,
                ])->save();
            }
        });
    }
}
