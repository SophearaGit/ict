<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ICTCourse;
use App\Models\ICTSchedule;
use App\Models\StudentAttendances;
use App\Models\StudentReports;
use App\Models\User;
use App\Traites\FileUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;

class RealTimeCoursesController extends Controller
{
    use FileUpload;

    public function realtimeShow(Request $request, $id): View
    {
        $course = ICTCourse::with([
            'students.student_attendances' => fn($q) => $q->where('course_id', $id),
            'studentReports.student',
            'instructor',
            'schedule'
        ])->findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | 🔎 DATE FILTER (TEACHER ATTENDANCE)
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
        | 🔥 RECALCULATE ATH (CUMULATIVE)
        |--------------------------------------------------------------------------
        */
        $cumulativeATH = 0;

        $filteredAttendances = $filteredAttendances->map(function ($att) use (&$cumulativeATH) {

            $hours = (float) $att->total_hours;

            $cumulativeATH += $hours;

            $att->actual_hours = round($cumulativeATH, 2);

            return $att;
        });

        /*
        |--------------------------------------------------------------------------
        | 📊 FILTERED CALCULATIONS
        |--------------------------------------------------------------------------
        */
        $totalHours = $filteredAttendances->sum('total_hours');

        $sessionDuration = 1.5;

        $completedSessions = $totalHours > 0
            ? floor($totalHours / $sessionDuration)
            : 0;

        $earnings = $completedSessions * ($course->price_per_session ?? 0);

        $course->filtered_hours = round($totalHours, 2);
        $course->filtered_sessions = $completedSessions;
        $course->filtered_earnings = round($earnings, 2);

        /*
        |--------------------------------------------------------------------------
        | 📈 ORIGINAL COURSE PROGRESS
        |--------------------------------------------------------------------------
        */
        $latestAttendance = $course->teacherAttendances()->latest()->first();

        $totalATH = $latestAttendance->actual_hours ?? 0;
        $duration = $course->duration ?? 0;

        $progress = $duration > 0
            ? ($totalATH / $duration) * 100
            : 0;

        $course->progress = min(round($progress, 2), 100);

        $course->total_sessions = $duration > 0
            ? round($duration / $sessionDuration)
            : 0;

        $course->completed_sessions = $totalATH > 0
            ? floor($totalATH / $sessionDuration)
            : 0;

        $course->earnings = round(
            $course->completed_sessions * ($course->price_per_session ?? 0),
            2
        );

        /*
        |--------------------------------------------------------------------------
        | 🔁 REPLACE RELATION WITH FILTERED DATA
        |--------------------------------------------------------------------------
        */
        $course->setRelation('teacherAttendances', $filteredAttendances);

        /*
        |--------------------------------------------------------------------------
        | 👨‍🎓 STUDENT ATTENDANCE TABLE (UNCHANGED)
        |--------------------------------------------------------------------------
        */
        $dates = StudentAttendances::where('course_id', $id)
            ->select('date')
            ->distinct()
            ->orderBy('date')
            ->pluck('date');

        $formattedDates = $dates->map(
            fn($d) => Carbon::parse($d)->format('d/m/Y')
        );

        $rows = [];

        foreach ($course->students as $index => $student) {

            $attendanceMap = [];

            foreach ($student->student_attendances as $att) {
                $formatted = Carbon::parse($att->date)->format('d/m/Y');

                $attendanceMap[$formatted] = match ($att->status) {
                    'present' => 'P',
                    'absent' => 'A',
                    'late' => 'L',
                    default => '-'
                };
            }

            $rows[] = [
                "no" => $index + 1,
                "student_name" => $student->name,
                "sex" => $student->gender ?? 'M',
                "day" => $course->schedule->study_day ?? 'Sunday',
                "shift" => optional($course->schedule)->start_time &&
                    optional($course->schedule)->end_time
                    ? Carbon::parse($course->schedule->start_time)->format('g:i') . '-' .
                    Carbon::parse($course->schedule->end_time)->format('g:i A')
                    : '-',
                "attendance" => $attendanceMap
            ];
        }

        $attendanceData = [
            "form_metadata" => [
                "software" => "Google Sheets",
                "class_title" => $course->title,
                "class_start" => optional($course->created_at)->format('d-M-Y'),
                "room" => "D",
                "lecturer_name" => $course->instructor->name ?? '',
                "lecturer_phone" => $course->instructor->phone ?? null,
            ],
            "table_structure" => [
                "columns" => array_merge(
                    ["NO", "Name", "Sex", "Date", "Shift"],
                    $formattedDates->toArray()
                ),
                "data_rows" => $rows
            ]
        ];

        // othher courses by the same instructor
        $others_courses = ICTCourse::where('instructor_id', $course->instructor_id)
            ->where('id', '!=', $course->id)
            ->latest()
            ->get();

        // Paginate students (IMPORTANT)
        $students = $course->students()->paginate(5); // 5 per page

        /*
        |--------------------------------------------------------------------------
        | 🧮 STUDENT REPORT (AUTO CALCULATE)
        |--------------------------------------------------------------------------
        */
        foreach ($course->students as $student) {

            $attendances = $student->student_attendances;

            $present = $attendances->where('status', 'present')->count();
            $absent = $attendances->where('status', 'absent')->count();
            $permission = $attendances->where('status', 'late')->count();

            $totalClasses = $present + $absent + $permission;

            $attendanceScore = $totalClasses > 0
                ? ($present / $totalClasses) * 100
                : 0;

            // Keep existing scores if already entered
            $existing = StudentReports::where([
                'course_id' => $course->id,
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

            $result = $totalScore >= 50 ? 'pass' : 'fail';

            StudentReports::updateOrCreate(
                [
                    'course_id' => $course->id,
                    'student_id' => $student->id
                ],
                [
                    'present' => $present,
                    'absent' => $absent,
                    'permission' => $permission,
                    'attendance_score' => round($attendanceScore, 2),
                    'assignment_score' => $assignment,
                    'mini_project_score' => $mini,
                    'final_project_score' => $final,
                    'total_score' => round($totalScore, 2),
                    'result' => $result
                ]
            );
        }

        return view(
            'admin.pages.real-time-courses-detail.real-time-courses-detail',
            [
                'page_title' => 'ICT | Staff | Course Detail',
                'course' => $course,
                'attendanceData' => $attendanceData,
                'students' => $students,
                'other_courses' => $others_courses,
            ]
        );
    }

    // public function realtimeShow($id)
    // {
    //     $course = ICTCourse::with([
    //         'students.student_attendances' => fn($q) => $q->where('course_id', $id),
    //         'instructor',
    //         'schedule'
    //     ])->findOrFail($id);

    //     // Sessions logic
    //     $sessionDuration = 1.5;

    //     // Total taught hours
    //     $totalATH = $course->teacherAttendances()->latest()->first()->actual_hours ?? 0;

    //     // Course duration
    //     $duration = $course->duration ?? 0;

    //     // Progress %
    //     $progress = $duration > 0 ? ($totalATH / $duration) * 100 : 0;
    //     $course->progress = min(round($progress, 2), 100);

    //     // Sessions logic
    //     $sessionDuration = 1.5;

    //     $course->total_sessions = $duration > 0
    //         ? round($duration / $sessionDuration)
    //         : 0;

    //     $course->completed_sessions = $totalATH > 0
    //         ? floor($totalATH / $sessionDuration)
    //         : 0;

    //     // Earnings logic
    //     $course->earnings = round($course->completed_sessions * ($course->price_per_session ?? 0), 2);

    //     // ✅ Get all unique dates
    //     $dates = StudentAttendances::where('course_id', $id)
    //         ->select('date')
    //         ->distinct()
    //         ->orderBy('date')
    //         ->pluck('date');

    //     // Format dates (15/02/2026)
    //     $formattedDates = $dates->map(fn($d) => Carbon::parse($d)->format('d/m/Y'));

    //     // ✅ Build rows
    //     $rows = [];

    //     foreach ($course->students as $index => $student) {

    //         $attendanceMap = [];

    //         foreach ($student->student_attendances as $att) {
    //             $formatted = Carbon::parse($att->date)->format('d/m/Y');

    //             $attendanceMap[$formatted] = match ($att->status) {
    //                 'present' => 'P',
    //                 'absent' => 'A',
    //                 'late' => 'L',
    //                 default => '-'
    //             };
    //         }

    //         $rows[] = [
    //             "no" => $index + 1,
    //             "student_name" => $student->name,
    //             "sex" => $student->gender ?? 'M',
    //             "day" => $course->schedule->study_day ?? 'Sunday',
    //             "shift" => optional($course->schedule)->start_time && optional($course->schedule)->end_time
    //                 ? Carbon::parse($course->schedule->start_time)->format('g:i') . '-' .
    //                 Carbon::parse($course->schedule->end_time)->format('g:i A')
    //                 : '-',
    //             "attendance" => $attendanceMap
    //         ];
    //     }

    //     // ✅ Final structured data
    //     $attendanceData = [
    //         "form_metadata" => [
    //             "software" => "Google Sheets",
    //             "class_title" => $course->title,
    //             "class_start" => optional($course->created_at)->format('d-M-Y'),
    //             "room" => "D",
    //             "lecturer_name" => $course->instructor->name ?? '',
    //             "lecturer_phone" => $course->instructor->phone ?? null,
    //         ],
    //         "table_structure" => [
    //             "columns" => array_merge(
    //                 ["NO", "Student Name", "Sex", "Date", "Shift"],
    //                 $formattedDates->toArray()
    //             ),
    //             "data_rows" => $rows
    //         ],
    //         "visual_elements" => [
    //             "header_color" => "Yellow (#FFFF00)",
    //             "date_row_color" => "Light Pink (#F4CCCC)",
    //             "attendance_keys" => [
    //                 "A" => "Absent",
    //                 "P" => "Present",
    //                 "L" => "Late"
    //             ]
    //         ]
    //     ];

    //     // othher courses by the same instructor
    //     $others_courses = ICTCourse::where('instructor_id', $course->instructor_id)
    //         ->where('id', '!=', $course->id)
    //         ->latest()
    //         ->get();

    //     return view('admin.pages.real-time-courses-detail.real-time-courses-detail', [
    //         'course' => $course,
    //         'attendanceData' => $attendanceData,
    //         'other_courses' => $others_courses,
    //         'page_title' => 'ICT | ADMIN | REAL TIME COURSE DETAIL',
    //     ]);
    // }

    public function realtimeIndex(Request $request)
    {
        $courses = ICTCourse::query();

        // 🔍 Search
        if ($request->filled('search_query')) {
            $courses->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search_query . '%')
                    ->orWhere('description', 'like', '%' . $request->search_query . '%'); // optional
            });
        }

        // 📅 Schedule filter
        if ($request->filled('schedule_ids')) {
            $courses->whereHas('schedule', function ($q) use ($request) {
                $q->whereIn('id', $request->schedule_ids);
            });
        }

        // ✅ Status filter
        if ($request->filled('status')) {
            $courses->where('status', $request->status);
        }

        // 💰 Revenue + enrollments (DO NOT RESET QUERY)
        $courses->withCount('enrollments')
            ->addSelect([
                'total_revenue' => DB::table('i_c_t_invoice_items')
                    ->join('i_c_t_invoices', 'i_c_t_invoice_items.invoice_id', '=', 'i_c_t_invoices.id')
                    ->join('i_c_t_payments', 'i_c_t_invoices.id', '=', 'i_c_t_payments.invoice_id')
                    ->whereColumn('i_c_t_invoice_items.course_id', 'i_c_t_courses.id')
                    ->selectRaw("
                    COALESCE(SUM(
                        i_c_t_payments.amount *
                        (i_c_t_invoice_items.total / NULLIF(i_c_t_invoices.total_amount, 0))
                    ), 0)
                ")
            ]);

        // 📊 Sorting (optional improvement)
        if ($request->filled('sort_by')) {
            if ($request->sort_by === 'revenue') {
                $courses->orderByDesc('total_revenue');
            } elseif ($request->sort_by === 'students') {
                $courses->orderByDesc('enrollments_count');
            } else {
                $courses->orderBy('title', 'asc');
            }
        } else {
            $courses->orderBy('title', 'asc');
        }

        // 📄 Pagination
        $courses = $courses->paginate(6)->withQueryString();

        // 📅 Schedule grouping
        $groupedSchedules = ICTSchedule::all()->groupBy('study_day');

        return view('admin.pages.real-time-courses.real-time-courses', [
            'page_title' => 'ICT | ADMIN | REAL TIME COURSES',
            'courses' => $courses,
            'schedules' => ICTSchedule::all(),
            'selected_schedule_ids' => $request->schedule_ids ?? [],
            'groupedSchedules' => $groupedSchedules,
        ]);
    }

    // create
    public function create()
    {
        return view('admin.pages.real-time-courses.partials.create', [
            'page_title' => 'ICT | ADMIN | CREATE REAL TIME COURSE',
            'instructors' => User::where('role', 'instructor')->latest()->get(),
            'schedules_for_select' => ICTSchedule::latest()->get()->groupBy('study_day'),
        ]);
    }

    // store
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive',
            'instructor_id' => 'required|exists:users,id',
            'schedule_id' => 'required|exists:i_c_t_schedules,id',
            'description' => 'nullable|string',
            'thumbnail' => 'nullable|image|max:3000',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $this->uploadFile($request->file('thumbnail'));
        } else {
            $thumbnailPath = "";
        }

        $course = new ICTCourse();
        $course->title = $request->title;
        $course->price = $request->price;
        $course->slug = Str::slug($request->title);
        $course->thumbnail = $thumbnailPath;
        $course->status = $request->status;
        $course->instructor_id = $request->instructor_id;
        $course->schedule_id = $request->schedule_id;
        $course->description = $request->description;
        $course->start_date = $request->start_date;
        $course->end_date = $request->end_date;
        $course->save();

        return redirect()->route('admin.courses.realtime.index')
            ->with('success', 'Staff member added successfully.');
    }

    // edit
    public function edit($id)
    {
        $course = ICTCourse::findOrFail($id);
        return view('admin.pages.real-time-courses.partials.edit', [
            'page_title' => 'ICT | ADMIN | EDIT REAL TIME COURSE',
            'course' => $course,
            'instructors' => User::where('role', 'instructor')->latest()->get(),
            'schedules_for_select' => ICTSchedule::latest()->get()->groupBy('study_day'),
        ]);
    }

    // update
    public function update(Request $request, $id)
    {
        $course = ICTCourse::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive',
            'instructor_id' => 'required|exists:users,id',
            'schedule_id' => 'required|exists:i_c_t_schedules,id',
            'description' => 'nullable|string',
            'thumbnail' => 'nullable|image|max:3000',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        if ($request->hasFile('thumbnail')) {
            if ($course->thumbnail != '') {
                $this->deleteIfImageExist($course->thumbnail);
            }
            $thumbnailPath = $this->uploadFile($request->file('thumbnail'));
        } else {
            $thumbnailPath = $course->thumbnail;
        }

        $course->title = $request->title;
        $course->price = $request->price;
        $course->slug = Str::slug($request->title);
        $course->thumbnail = $thumbnailPath;
        $course->status = $request->status;
        $course->instructor_id = $request->instructor_id;
        $course->schedule_id = $request->schedule_id;
        $course->description = $request->description;
        $course->start_date = $request->start_date;
        $course->end_date = $request->end_date;
        $course->save();

        return redirect()->back()
            ->with('success', 'Course updated successfully.');
    }

    // destroy
    public function destroy($id)
    {
        $course = ICTCourse::findOrFail($id);
        if ($course->thumbnail != '') {
            $this->deleteIfImageExist($course->thumbnail);
        }
        $course->delete();
        return response()->json([
            'message' => 'Course deleted successfully.',
            'status' => 'success',
            'redirect_url' => route('admin.courses.realtime.index'),
        ]);
    }


}
