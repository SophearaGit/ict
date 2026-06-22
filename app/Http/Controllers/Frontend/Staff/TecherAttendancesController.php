<?php
namespace App\Http\Controllers\Frontend\Staff;
use App\Http\Controllers\Controller;
use App\Models\ICTCourse;
use App\Models\TeacherAttendances;
use Illuminate\Http\Request;
class TecherAttendancesController extends Controller
{
    public function update(Request $request)
    {
        $courseId = $request->course_id;
        $teacherId = $request->teacher_id;
        $scheduleId = $request->schedule_id;
        // Guard — block submission if course is already completed
        $course = ICTCourse::find($courseId);
        if (!$course || $course->status !== 'active') {
            return back()->with('error', 'This course is already completed and no longer accepts attendance.');
        }
        foreach ($request->attendances as $attendance) {
            if (
                empty($attendance['start_time']) ||
                empty($attendance['end_time'])
            ) {
                continue;
            }
            if (!empty($attendance['id'])) {
                TeacherAttendances::where('id', $attendance['id'])
                    ->update([
                        'date' => $attendance['date'],
                        'start_time' => $attendance['start_time'],
                        'end_time' => $attendance['end_time'],
                        'total_hours' => $attendance['total_hours'],
                        'actual_hours' => $attendance['actual_hours'],
                        'room' => $attendance['room'],
                        'status' => 'present',
                        'late_minutes' => $attendance['late_minutes'] ?? 0,
                        'late_reason' => $attendance['late_reason'] ?? null,
                        'course_id' => $courseId,
                        'teacher_id' => $teacherId,
                        'schedule_id' => $scheduleId,
                    ]);
            } else {
                TeacherAttendances::create([
                    'date' => $attendance['date'],
                    'start_time' => $attendance['start_time'],
                    'end_time' => $attendance['end_time'],
                    'total_hours' => $attendance['total_hours'],
                    'actual_hours' => $attendance['actual_hours'],
                    'room' => $attendance['room'],
                    'status' => 'present',
                    'late_minutes' => $attendance['late_minutes'] ?? 0,
                    'late_reason' => $attendance['late_reason'] ?? null,
                    'course_id' => $courseId,
                    'teacher_id' => $teacherId,
                    'schedule_id' => $scheduleId,
                ]);
            }
        }
        return back()->with('success', 'Attendance saved successfully!');
    }
}
