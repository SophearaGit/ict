<?php

namespace App\Http\Controllers\Frontend\Staff;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Models\ICTCourse;
use App\Models\ICTSchedule;
use App\Models\StudentAttendances;
use App\Models\TeacherAttendances;
use App\Models\User;
use App\Traites\FileUpload;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\RedirectResponse;

class IctCourseController extends Controller
{

    use FileUpload;

    public function index(Request $request): View
    {
        $perPage = $request->input('per_page', 10);

        $courses = ICTCourse::when($request->filled('search'), function ($query) use ($request) {
            $query->where('title', 'like', '%' . $request->search . '%');
        })
            ->orderBy('title', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage)
            ->withQueryString();

        return view('frontend.staff.pages.course-management.course', [
            'page_title' => 'ICT | Staff | Courses',
            'courses' => $courses,
        ]);
    }



    public function show($id): View
    {
        $course = ICTCourse::with([
            'students.student_attendances' => fn($q) => $q->where('course_id', $id),
            'instructor',
            'schedule'
        ])->findOrFail($id);

        // Sessions logic
        $sessionDuration = 1.5;

        // Total taught hours
        $totalATH = $course->teacherAttendances()->latest()->first()->actual_hours ?? 0;

        // Course duration
        $duration = $course->duration ?? 0;

        // Progress %
        $progress = $duration > 0 ? ($totalATH / $duration) * 100 : 0;
        $course->progress = min(round($progress, 2), 100);

        // Sessions logic
        $sessionDuration = 1.5;

        $course->total_sessions = $duration > 0
            ? round($duration / $sessionDuration)
            : 0;

        $course->completed_sessions = $totalATH > 0
            ? floor($totalATH / $sessionDuration)
            : 0;

        // Earnings logic
        $course->earnings = round($course->completed_sessions * ($course->price_per_session ?? 0), 2);

        // ✅ Get all unique dates
        $dates = StudentAttendances::where('course_id', $id)
            ->select('date')
            ->distinct()
            ->orderBy('date')
            ->pluck('date');

        // Format dates (15/02/2026)
        $formattedDates = $dates->map(fn($d) => Carbon::parse($d)->format('d/m/Y'));

        // ✅ Build rows
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
                "shift" => optional($course->schedule)->start_time && optional($course->schedule)->end_time
                    ? Carbon::parse($course->schedule->start_time)->format('g:i') . '-' .
                    Carbon::parse($course->schedule->end_time)->format('g:i A')
                    : '-',
                "attendance" => $attendanceMap
            ];
        }

        // ✅ Final structured data
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
                    ["NO", "Student Name", "Sex", "Date", "Shift"],
                    $formattedDates->toArray()
                ),
                "data_rows" => $rows
            ],
            "visual_elements" => [
                "header_color" => "Yellow (#FFFF00)",
                "date_row_color" => "Light Pink (#F4CCCC)",
                "attendance_keys" => [
                    "A" => "Absent",
                    "P" => "Present",
                    "L" => "Late"
                ]
            ]
        ];

        return view('frontend.staff.pages.course-management.course-detail.course-detail', [
            'course' => $course,
            'attendanceData' => $attendanceData
        ]);
    }

    // public function show($id): View
    // {
    //     $course = ICTCourse::with([
    //         'students.student_attendances' => function ($q) use ($id) {
    //             $q->where('course_id', $id);
    //         },
    //         'instructor',
    //         'schedule',
    //         'teacherAttendances.teacher',
    //         'teacherAttendances.schedule'
    //     ])->findOrFail($id);

    //     // ✅ Get all unique attendance dates (for columns)
    //     $dates = StudentAttendances::where('course_id', $id)
    //         ->orderBy('date')
    //         ->pluck('date')
    //         ->unique()
    //         ->values();

    //     return view('frontend.staff.pages.course-management.course-detail.course-detail', [
    //         'page_title' => 'ICT | STAFF | COURSE DETAIL',
    //         'course' => $course,
    //         'students' => $course->students,
    //         'dates' => $dates
    //     ]);
    // }

    public function create(): View
    {
        $data = [
            'page_title' => 'ICT | Staff | Create Course',
            'instructors' => User::where('role', 'instructor')
                ->latest()->paginate(10),
            'schedules' => ICTSchedule::latest()->get()->groupBy('study_day'),
        ];
        return view('frontend.staff.pages.course-management.create', $data);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'price_per_session' => 'nullable|numeric|min:0',
            'status' => 'required|in:active,inactive',
            'instructor_id' => 'required|exists:users,id',
            'schedule_id' => 'required|exists:i_c_t_schedules,id',
            'description' => 'nullable|string',
            'thumbnail' => 'nullable|image|max:3000',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'duration' => 'nullable|numeric|min:0',
        ]);

        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $this->uploadFile($request->file('thumbnail'));
        } else {
            $thumbnailPath = ""; // or set a default thumbnail path if needed
        }

        $course = new ICTCourse();
        $course->title = $request->title;
        $course->price = $request->price;
        $course->price_per_session = $request->price_per_session;
        $course->slug = Str::slug($request->title);
        $course->thumbnail = $thumbnailPath;
        $course->status = $request->status;
        $course->instructor_id = $request->instructor_id;
        $course->schedule_id = $request->schedule_id;
        $course->description = $request->description;
        $course->start_date = $request->start_date;
        $course->end_date = $request->end_date;
        $course->duration = $request->duration;
        $course->save();

        return redirect()->route('staff.courses.index')
            ->with('success', 'Course created successfully. Please add lessons to the course.');
    }

    public function edit($id): View
    {
        $data = [
            'page_title' => 'ICT | STAFF | EDIT COURSE',
            'course' => ICTCourse::findOrFail($id),
            'instructors' => User::where('role', 'instructor')
                ->latest()->paginate(10),
            'schedules' => ICTSchedule::latest()->get()->groupBy('study_day'),
        ];
        return view('frontend.staff.pages.course-management.edit', $data);
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $course = ICTCourse::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'price_per_session' => 'nullable|numeric|min:0',
            'status' => 'required|in:active,inactive',
            'instructor_id' => 'required|exists:users,id',
            'schedule_id' => 'required|exists:i_c_t_schedules,id',
            'description' => 'nullable|string',
            'thumbnail' => 'nullable|image|max:3000',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'duration' => 'nullable|numeric|min:0',
        ]);

        if ($request->hasFile('thumbnail')) {
            if ($course->thumbnail != '') {
                $this->deleteIfImageExist($course->thumbnail);
            }
            $thumbnailPath = $this->uploadFile($request->file('thumbnail'));
        } else {
            $thumbnailPath = $course->thumbnail; // Keep existing thumbnail if no new file is uploaded
        }

        $course->title = $request->title;
        $course->price = $request->price;
        $course->price_per_session = $request->price_per_session;
        $course->slug = Str::slug($request->title);
        $course->thumbnail = $thumbnailPath;
        $course->status = $request->status;
        $course->instructor_id = $request->instructor_id;
        $course->schedule_id = $request->schedule_id;
        $course->description = $request->description;
        $course->start_date = $request->start_date;
        $course->end_date = $request->end_date;
        $course->duration = $request->duration;
        $course->save();

        return redirect()->route('staff.courses.index')
            ->with('success', 'Course updated successfully.');

    }



}
