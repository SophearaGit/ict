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
            ->keyBy('student_id'); // 🔥 important

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

            $present = $student->student_attendances->where('status', 'present')->count();
            $absent = $student->student_attendances->where('status', 'absent')->count();
            $permission = $student->student_attendances->where('status', 'late')->count();

            $totalClasses = $present + $absent + $permission;

            $attendanceScore = $totalClasses > 0
                ? ($present / $totalClasses) * 100
                : 0;

            $existing = StudentReports::where([
                'course_id' => $courseId,
                'student_id' => $student->id
            ])->first();

            $assignment = $existing->assignment_score ?? 0;
            $mini = $existing->mini_project_score ?? 0;
            $final = $existing->final_project_score ?? 0;

            $totalScore =
                ($attendanceScore * 0.10) +
                ($assignment * 0.20) +
                ($mini * 0.20) +
                ($final * 0.50);

            $report = StudentReports::updateOrCreate(
                [
                    'course_id' => $courseId,
                    'student_id' => $student->id
                ],
                [
                    'present' => $present,
                    'absent' => $absent,
                    'permission' => $permission,
                    'attendance_score' => round($attendanceScore, 2),
                    'total_score' => round($totalScore, 2),
                    'result' => $totalScore >= 50 ? 'pass' : 'fail'
                ]
            );

            $result[$student->id] = [
                'present' => $report->present,
                'absent' => $report->absent,
                'permission' => $report->permission,
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

            // 🔥 IMPORTANT: Recalculate reports AFTER saving
            $reports = $this->recalculateReports($request->course_id);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Attendance saved successfully!',
                'reports' => $reports // 👈 SEND BACK TO FRONTEND
            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }


    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'course_id' => 'required|exists:i_c_t_courses,id',
    //         'date' => 'required|date',
    //         'attendances' => 'required|array',
    //     ]);

    //     DB::beginTransaction();

    //     try {
    //         foreach ($request->attendances as $attendance) {

    //             StudentAttendances::updateOrCreate(
    //                 [
    //                     'course_id' => $request->course_id,
    //                     'student_id' => $attendance['student_id'],
    //                     'date' => $request->date,
    //                 ],
    //                 [
    //                     'status' => $attendance['status'],
    //                     'note' => $attendance['note'] ?? null,
    //                 ]
    //             );
    //         }

    //         DB::commit();

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Attendance saved successfully!'
    //         ]);

    //     } catch (\Exception $e) {
    //         DB::rollBack();

    //         return response()->json([
    //             'success' => false,
    //             'message' => $e->getMessage()
    //         ], 500);
    //     }
    // }
}
