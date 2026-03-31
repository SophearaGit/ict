<?php

namespace App\Http\Controllers\Frontend\Staff;

use App\Http\Controllers\Controller;
use App\Models\TeacherAttendances;
use Illuminate\Http\Request;

class TecherAttendancesController extends Controller
{
    //  Route::post('/teacher-attendance/update', [TeacherAttendances::class, 'update'])
    public function update(Request $request)
    {
        $attendances = $request->attendances;
        $courseId = $request->course_id;
        $teacherId = $request->teacher_id;
        $scheduleId = $request->schedule_id;

        foreach ($request->attendances as $attendance) {

            if (
                empty($attendance['date']) &&
                empty($attendance['start_time']) &&
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
                        'status' => $attendance['status'],
                        'course_id' => $request->course_id,
                        'teacher_id' => $request->teacher_id,
                        'schedule_id' => $request->schedule_id,
                    ]);
            } else {
                TeacherAttendances::create([
                    'date' => $attendance['date'],
                    'start_time' => $attendance['start_time'],
                    'end_time' => $attendance['end_time'],
                    'total_hours' => $attendance['total_hours'],
                    'actual_hours' => $attendance['actual_hours'],
                    'room' => $attendance['room'],
                    'status' => $attendance['status'],
                    'course_id' => $request->course_id,
                    'teacher_id' => $request->teacher_id,
                    'schedule_id' => $request->schedule_id,
                ]);
            }
        }

        return back()->with('success', 'Attendance saved successfully!');
    }

}
