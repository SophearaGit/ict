<?php

namespace Database\Seeders;

use App\Models\ICTCourseEnrollments;
use App\Models\StudentAttendances;
use App\Models\StudentReports;
use App\Models\User;
use Illuminate\Database\Seeder;

class StudentReportsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * One report card per active/completed enrollment. The attendance
     * counts are derived from the StudentAttendances rows already
     * seeded for that student+course, so the numbers agree with each
     * other instead of being independently random.
     */
    public function run(): void
    {
        $staffId = User::where('role', 'staff')->value('id');

        ICTCourseEnrollments::whereIn('status', ['active', 'completed'])
            ->orderBy('id')
            ->each(function (ICTCourseEnrollments $enrollment) use ($staffId): void {
                $attendance = StudentAttendances::where('course_id', $enrollment->course_id)
                    ->where('student_id', $enrollment->student_id)
                    ->get();

                $present = $attendance->where('status', 'present')->count();
                $absent = $attendance->where('status', 'absent')->count();
                $permission = $attendance->where('status', 'permission')->count();

                // Mirrors StudentAttendanceController::recalculateReports() —
                // 'permission' (excused) absences don't cost points, only
                // unexcused ones do: -1 point per 4 absences.
                $attendanceScore = round(max(0, 10 - floor($absent / 4)), 2);
                $assignmentScore = rand(15, 30);
                $miniProjectScore = rand(10, 20);
                $finalProjectScore = $enrollment->status === 'completed' ? rand(18, 40) : rand(0, 30);
                $totalScore = round($attendanceScore + $assignmentScore + $miniProjectScore + $finalProjectScore, 2);
                $result = $totalScore >= 50 ? 'pass' : 'fail';

                // Finished courses have mostly-finalized report cards;
                // in-progress courses are still mostly draft/pending.
                $roll = $enrollment->id % 10;
                $approvalStatus = $enrollment->status === 'completed'
                    ? ($roll < 7 ? 'approved' : ($roll < 9 ? 'pending' : 'draft'))
                    : ($roll < 2 ? 'pending' : 'draft');

                // StudentReports::$fillable doesn't include 'approval_status',
                // 'approved_by', or 'approved_at' (added by a later migration
                // but never added to $fillable) — a plain updateOrCreate()
                // would silently drop all three and every report would sit
                // at the default 'draft' state. forceFill() sets them anyway.
                $report = StudentReports::where('course_id', $enrollment->course_id)
                    ->where('student_id', $enrollment->student_id)
                    ->first() ?? new StudentReports();

                $report->forceFill([
                    'course_id' => $enrollment->course_id,
                    'student_id' => $enrollment->student_id,
                    'present' => $present,
                    'absent' => $absent,
                    'permission' => $permission,
                    'attendance_score' => $attendanceScore,
                    'assignment_score' => $assignmentScore,
                    'mini_project_score' => $miniProjectScore,
                    'final_project_score' => $finalProjectScore,
                    'total_score' => $totalScore,
                    'result' => $enrollment->status === 'completed' ? $result : null,
                    'remark' => $result === 'pass' ? 'Good progress.' : null,
                    'approval_status' => $approvalStatus,
                    'approved_by' => $approvalStatus === 'approved' ? $staffId : null,
                    'approved_at' => $approvalStatus === 'approved' ? now()->subDays(rand(1, 10)) : null,
                ])->save();
            });
    }
}
