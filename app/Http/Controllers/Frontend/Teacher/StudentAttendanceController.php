<?php

namespace App\Http\Controllers\Frontend\Teacher;

use App\Http\Controllers\Controller;
use App\Models\StudentAttendances;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentAttendanceController extends Controller
{
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

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Attendance saved successfully!'
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
