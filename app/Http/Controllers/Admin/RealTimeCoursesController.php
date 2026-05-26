<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\ICTCourse;
use App\Models\ICTSchedule;
use App\Models\StudentAttendances;
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
            'schedule',
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
        $completedSessions = $totalHours > 0 ? floor($totalHours / $sessionDuration) : 0;
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
        $progress = $duration > 0 ? ($totalATH / $duration) * 100 : 0;
        $course->progress = min(round($progress, 2), 100);
        $course->total_sessions = $duration > 0 ? round($duration / $sessionDuration) : 0;
        $course->completed_sessions = $totalATH > 0 ? floor($totalATH / $sessionDuration) : 0;
        $course->earnings = round($course->completed_sessions * ($course->price_per_session ?? 0), 2);
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
        $dates = StudentAttendances::where('course_id', $id)->select('date')->distinct()->orderBy('date')->pluck('date');
        $formattedDates = $dates->map(fn($d) => Carbon::parse($d)->format('d/m/Y'));
        $rows = [];
        foreach ($course->students as $index => $student) {
            $attendanceMap = [];
            foreach ($student->student_attendances as $att) {
                $formatted = Carbon::parse($att->date)->format('d/m/Y');
                $attendanceMap[$formatted] = match ($att->status) {
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
                'day' => $course->schedule->study_day ?? 'Sunday',
                'shift' => optional($course->schedule)->start_time && optional($course->schedule)->end_time ? Carbon::parse($course->schedule->start_time)->format('g:i') . '-' . Carbon::parse($course->schedule->end_time)->format('g:i A') : '-',
                'attendance' => $attendanceMap,
            ];
        }
        $attendanceData = [
            'form_metadata' => [
                'software' => 'Google Sheets',
                'class_title' => $course->title,
                'class_start' => optional($course->created_at)->format('d-M-Y'),
                'room' => 'D',
                'lecturer_name' => $course->instructor->name ?? '',
                'lecturer_phone' => $course->instructor->phone ?? null,
            ],
            'table_structure' => [
                'columns' => array_merge(['NO', 'Name', 'Sex', 'Date', 'Shift'], $formattedDates->toArray()),
                'data_rows' => $rows,
            ],
        ];
        // othher courses by the same instructor
        $others_courses = ICTCourse::where('instructor_id', $course->instructor_id)->where('id', '!=', $course->id)->latest()->get();
        // Paginate students (IMPORTANT)
        $students = $course->students()->paginate(5); // 5 per page
        return view('admin.pages.real-time-courses-detail.real-time-courses-detail', [
            'page_title' => 'ICT | Staff | Course Detail',
            'course' => $course,
            'attendanceData' => $attendanceData,
            'students' => $students,
            'other_courses' => $others_courses,
        ]);
    }
    public function realtimeIndex(Request $request)
    {
        $courses = ICTCourse::query();

        // Search
        if ($request->filled('search_query')) {
            $courses->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search_query . '%')
                    ->orWhere('description', 'like', '%' . $request->search_query . '%');
            });
        }

        // Schedule filter
        if ($request->filled('schedule_ids')) {
            $courses->whereHas('schedule', function ($q) use ($request) {
                $q->whereIn('id', $request->schedule_ids);
            });
        }

        // Status filter (active / inactive / draft)
        if ($request->filled('status')) {
            $courses->where('status', $request->status);
        }

        // Month filter — filters by the month of start_date
        if ($request->filled('month')) {
            $courses->whereMonth('start_date', $request->month);
        }

        // Revenue + enrollment count
        $courses->withCount('enrollments')->addSelect([
            'total_revenue' => DB::table('i_c_t_invoice_items')
                ->join('i_c_t_invoices', 'i_c_t_invoice_items.invoice_id', '=', 'i_c_t_invoices.id')
                ->join('i_c_t_payments', 'i_c_t_invoices.id', '=', 'i_c_t_payments.invoice_id')
                ->whereColumn('i_c_t_invoice_items.course_id', 'i_c_t_courses.id')
                ->selectRaw("
                COALESCE(SUM(
                    i_c_t_payments.amount *
                    (i_c_t_invoice_items.total / NULLIF(i_c_t_invoices.total_amount, 0))
                ), 0)
            "),
        ]);

        // Sorting
        match ($request->sort_by) {
            'revenue' => $courses->orderByDesc('total_revenue'),
            'students' => $courses->orderByDesc('enrollments_count'),
            'start_asc' => $courses->orderBy('start_date', 'asc'),
            'start_desc' => $courses->orderByDesc('start_date'),
            'end_asc' => $courses->orderBy('end_date', 'asc'),
            'end_desc' => $courses->orderByDesc('end_date'),
            default => $courses->orderBy('start_date', 'asc'),
        };

        $courses = $courses->paginate(6)->withQueryString();

        $groupedSchedules = ICTSchedule::all()->groupBy('study_day');

        return view('admin.pages.real-time-courses.real-time-courses', [
            'page_title' => 'ICT | ADMIN | REAL TIME COURSES',
            'courses' => $courses,
            'schedules' => ICTSchedule::all(),
            'selected_schedule_ids' => $request->schedule_ids ?? [],
            'groupedSchedules' => $groupedSchedules,
        ]);
    }
    public function create()
    {
        return view('admin.pages.real-time-courses.partials.create', [
            'page_title' => 'ICT | ADMIN | CREATE REAL TIME COURSE',
            'instructors' => User::where('role', 'instructor')->latest()->get(),
            'schedules_for_select' => ICTSchedule::latest()->get()->groupBy('study_day'),
        ]);
    }
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
            $thumbnailPath = '';
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
        return redirect()->route('admin.courses.realtime.index')->with('success', 'Staff member added successfully.');
    }
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
        return redirect()->back()->with('success', 'Course updated successfully.');
    }
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
