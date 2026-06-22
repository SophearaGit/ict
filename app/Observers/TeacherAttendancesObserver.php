<?php
namespace App\Observers;
use App\Models\TeacherAttendances;
class TeacherAttendancesObserver
{
    public function saved(TeacherAttendances $attendance): void
    {
        $course = $attendance->course;
        if (!$course || $course->status !== 'active') {
            return;
        }
        $totalHours = TeacherAttendances::where('course_id', $course->id)
            ->sum('actual_hours');
        if ($totalHours >= $course->duration) {
            $course->update(['status' => 'inactive']);
        }
    }
}
