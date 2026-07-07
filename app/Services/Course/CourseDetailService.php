<?php
namespace App\Services\Course;
use App\Models\ICTCourse;
use App\Models\ICTCourseEnrollments;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class CourseDetailService
{
    private function loadRelations(ICTCourse $course): void
    {
        $course->load([
            'students.student_attendances' => fn($q) => $q->where('course_id', $course->id),
            'studentReports.student',
            'instructor',
            'schedule',
            'category',
        ]);
    }
    private function prepareTeacherAttendance(ICTCourse $course, Request $request): void
    {
        /*
        |--------------------------------------------------------------------------
        | DATE FILTER (TEACHER ATTENDANCE)
        |--------------------------------------------------------------------------
        */
        $fromDate = $request->from_date;
        $toDate = $request->to_date;
        $attendanceQuery = $course->teacherAttendances();
        if ($fromDate && $toDate) {
            $attendanceQuery->whereBetween('date', [$fromDate, $toDate]);
        }
        $filteredAttendances = $attendanceQuery->orderBy('date')->get();
        /*
        |--------------------------------------------------------------------------
        | RECALCULATE ATH (CUMULATIVE)
        |--------------------------------------------------------------------------
        */
        $cumulativeATH = 0;
        $filteredAttendances = $filteredAttendances->map(function ($att) use (&$cumulativeATH) {
            $cumulativeATH += (float) $att->total_hours;
            $att->actual_hours = round($cumulativeATH, 2);
            return $att;
        });
        /*
        |--------------------------------------------------------------------------
        | FILTERED CALCULATIONS
        |--------------------------------------------------------------------------
        */
        $sessionDuration = 1.5;
        $totalHours = $filteredAttendances->sum('total_hours');
        $completedSessions = $totalHours > 0 ? floor($totalHours / $sessionDuration) : 0;
        $course->filtered_hours = round($totalHours, 2);
        $course->filtered_sessions = $completedSessions;
        $course->filtered_earnings = round($completedSessions * ($course->price_per_session ?? 0), 2);
        /*
        |--------------------------------------------------------------------------
        | ORIGINAL COURSE PROGRESS
        |--------------------------------------------------------------------------
        */
        $latestAttendance = $filteredAttendances->last();
        $totalATH = $latestAttendance->actual_hours ?? 0;
        $duration = $course->duration ?? 0;
        $progress = $duration > 0 ? ($totalATH / $duration) * 100 : 0;
        $course->progress = min(round($progress, 2), 100);
        $course->total_sessions = $duration > 0 ? round($duration / $sessionDuration) : 0;
        $course->completed_sessions = $totalATH > 0 ? floor($totalATH / $sessionDuration) : 0;
        $course->earnings = round($course->completed_sessions * ($course->price_per_session ?? 0), 2);
        /*
        |--------------------------------------------------------------------------
        | REPLACE RELATION WITH FILTERED DATA
        |--------------------------------------------------------------------------
        */
        $course->setRelation('teacherAttendances', $filteredAttendances);
    }
    private function buildAttendanceData(ICTCourse $course): array
    {
        $dates = $course->students
            ->flatMap(fn($student) => $student->student_attendances)
            ->pluck('date')
            ->unique()
            ->sort()
            ->values();
        $formattedDates = $dates->map(
            fn($date) => Carbon::parse($date)->format('d/m/Y')
        );
        $rows = [];
        $schedule = $course->schedule;
        foreach ($course->students as $index => $student) {
            $attendanceMap = [];
            foreach ($student->student_attendances as $attendance) {
                $formatted = Carbon::parse($attendance->date)
                    ->format('d/m/Y');
                $attendanceMap[$formatted] = match ($attendance->status) {
                    'present' => 'P',
                    'absent' => 'A',
                    'late' => 'L',
                    default => '-',
                };
            }
            $rows[] = [
                'no' => $index + 1,
                'student_name' => $student->name,
                'sex' => $student->gender ?? 'M',
                'day' => $schedule->study_day ?? 'Sunday',
                'shift' => $schedule && $schedule->start_time && $schedule->end_time
                    ? Carbon::parse($schedule->start_time)->format('g:i')
                    . ' - '
                    . Carbon::parse($schedule->end_time)->format('g:i A')
                    : '-',
                'attendance' => $attendanceMap,
            ];
        }
        return [
            'form_metadata' => [
                'software' => 'Google Sheets',
                'class_title' => $course->title,
                'class_start' => $course->created_at?->format('d-M-Y'),
                'room' => 'D',
                'lecturer_name' => $course->instructor->name ?? '',
                'lecturer_phone' => $course->instructor->phone ?? null,
            ],
            'table_structure' => [
                'columns' => array_merge(
                    ['NO', 'Student Name', 'Sex', 'Date', 'Shift'],
                    $formattedDates->toArray()
                ),
                'data_rows' => $rows,
            ],
        ];
    }
    private function buildEnrollmentMap(ICTCourse $course): Collection
    {
        return ICTCourseEnrollments::where('status', 'active')
            ->whereIn('student_id', $course->students->pluck('id'))
            ->get()
            ->groupBy('student_id')
            ->map(fn($rows) => $rows->pluck('course_id')->toArray());
    }
    public function getData(ICTCourse $course, Request $request): array
    {
        $this->loadRelations($course);
        $this->prepareTeacherAttendance($course, $request);
        $attendanceData = $this->buildAttendanceData($course);
        $enrollmentMap = $this->buildEnrollmentMap($course);
        return [
            'page_title' => 'ICT | STAFF | COURSE DETAIL',
            'course' => $course,
            'attendanceData' => $attendanceData,
            'enrollmentMap' => $enrollmentMap,
        ];
    }
}
