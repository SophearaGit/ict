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
    }
}
