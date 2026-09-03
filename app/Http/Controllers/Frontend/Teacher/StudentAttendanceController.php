<?php
namespace App\Http\Controllers\Frontend\Teacher;
use App\Http\Controllers\Controller;
use App\Models\StudentAttendances;
use App\Models\StudentReports;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
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
            'data' => $attendances,
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
            $permission = $student->student_attendances->where('status', 'permission')->count();
            /*
            |--------------------------------------------------------------------------
            | ATTENDANCE SCORE
            | Start with 10 points. Every 4 (unexcused) absences = -1 point.
            | 'permission' (excused) absences don't cost points.
            |--------------------------------------------------------------------------
            */
            $attendanceScore = max(0, 10 - floor($absent / 4));
            $existing = StudentReports::firstOrCreate(
                ['course_id' => $courseId, 'student_id' => $student->id],
                ['assignment_score' => 0, 'mini_project_score' => 0, 'final_project_score' => 0]
            );
            $assignment = min(30, max(0, (float) $existing->assignment_score));
            $mini = min(20, max(0, (float) $existing->mini_project_score));
            $final = min(40, max(0, (float) $existing->final_project_score));
            /*
            |--------------------------------------------------------------------------
            | Total Score: attendance(10) + assignment(30) + mini(20) + final(40) = 100
            |--------------------------------------------------------------------------
            */
            $totalScore = $attendanceScore + $assignment + $mini + $final;
            $report = StudentReports::updateOrCreate(
                ['course_id' => $courseId, 'student_id' => $student->id],
                [
                    'present' => $present,
                    'absent' => $absent,
                    'permission' => $permission,
                    'attendance_score' => round(min(10, $attendanceScore), 2),
                    'total_score' => round($totalScore, 2),
                    'result' => $totalScore >= 50 ? 'pass' : 'fail',
                ]
            );
            $result[$student->id] = [
                'present' => $report->present,
                'absent' => $report->absent,
                'permission' => $report->permission,
                'attendance_score' => $report->attendance_score,
                'total_score' => $report->total_score,
                'result' => $report->result,
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
            'attendances.*.status' => 'nullable|in:present,absent,permission,unmarked',
        ]);
        DB::beginTransaction();
        try {
            foreach ($request->attendances as $attendance) {
                // A student left unclicked sends status: '' — persist that
                // explicitly as 'unmarked' rather than skipping the row.
                // Otherwise a save where nobody was clicked yet (e.g. the
                // instructor just opened the date and hit save) writes
                // zero rows, and the Session Log — which only lists dates
                // that have at least one row — never shows that session
                // even though attendance was genuinely opened/saved for it.
                $status = $attendance['status'] ?: 'unmarked';
                StudentAttendances::updateOrCreate(
                    [
                        'course_id' => $request->course_id,
                        'student_id' => $attendance['student_id'],
                        'date' => $request->date,
                    ],
                    [
                        'status' => $status,
                        'note' => $attendance['note'] ?? null,
                    ]
                );
            }
            $reports = $this->recalculateReports($request->course_id);
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Attendance saved successfully!',
                'reports' => $reports,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    public function reset(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:i_c_t_courses,id',
            'date' => 'required|date',
        ]);
        DB::beginTransaction();
        try {
            StudentAttendances::where('course_id', $request->course_id)
                ->whereDate('date', $request->date)
                ->delete();
            $reports = $this->recalculateReports($request->course_id);
            DB::commit();
            return response()->json([
                'success' => true,
                'reports' => $reports,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    public function sessionLog(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:i_c_t_courses,id',
        ]);
        // All distinct dates that have attendance for this course
        $dates = StudentAttendances::where('course_id', $request->course_id)
            ->select('date')
            ->distinct()
            ->orderBy('date', 'desc')
            ->pluck('date');
        // All ACTIVELY enrolled students — same scope as the Mark
        // Attendance roster ($course->students(), which filters to
        // status = 'active'). Without this filter a dropped/completed
        // student who can no longer be marked would still inflate
        // total_students here, making every session look like it has
        // extra "unmarked" students who were never markable in the
        // first place.
        $students = User::whereHas('enrollments', fn($q) => $q->where('course_id', $request->course_id)
            ->where('status', 'active'))
            ->select('id', 'name')
            ->orderBy('name')
            ->get();
        $totalStudents = $students->count();
        $sessions = $dates->map(function ($date) use ($request, $totalStudents) {
            $records = StudentAttendances::where('course_id', $request->course_id)
                ->whereDate('date', $date)
                ->get()
                ->keyBy('student_id');
            $presentCount = $records->where('status', 'present')->count();
            $absentCount = $records->where('status', 'absent')->count();
            $permissionCount = $records->where('status', 'permission')->count();
            // Rows explicitly saved as 'unmarked', plus (for sessions saved
            // before that status existed, or students enrolled afterward)
            // any enrolled student with no row at all for this date.
            $unmarkedCount = $records->where('status', 'unmarked')->count()
                + max(0, $totalStudents - $records->count());
            $parsed = Carbon::parse($date);
            return [
                'date' => $date,
                'day' => $parsed->format('d'),
                'month' => $parsed->format('M'),
                'year' => $parsed->format('Y'),
                'present_count' => $presentCount,
                'absent_count' => $absentCount,
                'permission_count' => $permissionCount,
                'unmarked_count' => max(0, $unmarkedCount),
                'total_students' => $totalStudents,
                'records' => $records->map(fn($r) => [
                    'status' => $r->status,
                    'note' => $r->note,
                ]),
            ];
        })->values();
        return response()->json([
            'success' => true,
            'sessions' => $sessions,
            'students' => $students,
        ]);
    }
}
