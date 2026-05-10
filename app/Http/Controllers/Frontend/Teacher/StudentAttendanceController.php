<?php

namespace App\Http\Controllers\Frontend\Teacher;

use App\Http\Controllers\Controller;
use App\Models\StudentAttendances;
use App\Models\StudentReports;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentAttendanceController extends Controller
{

    public function getByDate(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:i_c_t_courses,id',
            'date' => 'required|date',
        ]);

        $attendances = StudentAttendances::where('course_id', $request->course_id)
            ->whereDate('date', $request->date)
            ->get()
            ->keyBy('student_id');

        return response()->json([
            'success' => true,
            'data' => $attendances
        ]);
    }

    private function recalculateReports($courseId)
    {
        $students = User::with([
            'student_attendances' => function ($q) use ($courseId) {
                $q->where('course_id', $courseId);
            }
        ])->whereHas('enrollments', fn($q) => $q->where('course_id', $courseId))->get();

        $result = [];

        foreach ($students as $student) {

            $present = $student->student_attendances
                ->where('status', 'present')
                ->count();

            $absent = $student->student_attendances
                ->where('status', 'absent')
                ->count();

            $totalAttendance = $present + $absent;

            /*
            |--------------------------------------------------------------------------
            | ATTENDANCE SCORE
            |--------------------------------------------------------------------------
            |
            | Start with 10 points
            | Every 4 absences = -1 point
            |
            */

            $attendanceScore = 10 - floor($absent / 4);

            // prevent negative
            $attendanceScore = max(0, $attendanceScore);

            // existing report
            $existing = StudentReports::firstOrCreate(
                [
                    'course_id' => $courseId,
                    'student_id' => $student->id
                ],
                [
                    'assignment_score' => 0,
                    'mini_project_score' => 0,
                    'final_project_score' => 0,
                ]
            );

            // clamp old corrupted data
            $existing->attendance_score = min(10, max(0, $attendanceScore));

            $assignment = min(30, max(0, (float) $existing->assignment_score));
            $mini = min(20, max(0, (float) $existing->mini_project_score));
            $final = min(40, max(0, (float) $existing->final_project_score));

            /*
            |--------------------------------------------------------------------------
            | Total Score
            |--------------------------------------------------------------------------
            |
            | attendance = 10
            | assignment = 30
            | mini = 20
            | final = 40
            |
            | TOTAL = 100
            |
            */

            $totalScore =
                $attendanceScore +
                $assignment +
                $mini +
                $final;

            $report = StudentReports::updateOrCreate(
                [
                    'course_id' => $courseId,
                    'student_id' => $student->id
                ],
                [
                    'present' => $present,
                    'absent' => $absent,
                    'attendance_score' => round(min(10, $attendanceScore), 2),
                    'total_score' => round($totalScore, 2),
                    'result' => $totalScore >= 50 ? 'pass' : 'fail'
                ]
            );

            $result[$student->id] = [
                'present' => $report->present,
                'absent' => $report->absent,
                'attendance_score' => $report->attendance_score,
                'total_score' => $report->total_score,
                'result' => $report->result
            ];
        }

        return $result;
    }

    public function store(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:i_c_t_courses,id',
            'date' => 'required|date',
            'attendances' => 'required|array',
        ]);

        DB::beginTransaction();

        try {

            foreach ($request->attendances as $attendance) {

                StudentAttendances::updateOrCreate(
                    [
                        'course_id' => $request->course_id,
                        'student_id' => $attendance['student_id'],
                        'date' => $request->date,
                    ],
                    [
                        'status' => $attendance['status'],
                        'note' => $attendance['note'] ?? null,
                    ]
                );
            }

            // 🔥 RECALCULATE REPORTS
            $reports = $this->recalculateReports($request->course_id);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Attendance saved successfully!',
                'reports' => $reports
            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

}
