<?php
namespace App\Http\Controllers\Frontend\Staff;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Models\ICTCourse;
use App\Models\ICTSchedule;
use App\Models\StudentAttendances;
use App\Models\User;
use App\Traites\FileUpload;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\RedirectResponse;
class IctCourseController extends Controller
{
    use FileUpload;
    public function index(Request $request): View
    {
        $perPage = $request->input('per_page', 10);
        $courses = ICTCourse::with(['instructor', 'schedule'])
            ->when($request->filled('search'), function ($query) use ($request) {
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
    public function show(Request $request, $id): View
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
                'columns' => array_merge(['NO', 'Student Name', 'Sex', 'Date', 'Shift'], $formattedDates->toArray()),
                'data_rows' => $rows,
            ],
        ];
        return view('frontend.staff.pages.course-management.course-detail.course-detail', [
            'page_title' => 'ICT | Staff | Course Detail',
            'course' => $course,
            'attendanceData' => $attendanceData,
        ]);
    }
    public function create(): View
    {
        $data = [
            'page_title' => 'ICT | SRAFF | CREATE COURSE',
            'instructors' => User::where('role', 'instructor')
                ->orderBy('name') // changed from latest()
                ->get(),
            'schedules' => ICTSchedule::latest()->get()->groupBy('study_day'),
        ];
        return view('frontend.staff.pages.course-management.create', $data);
    }
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'khmer_name' => 'nullable|string|max:255',
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
            $thumbnailPath = ''; // or set a default thumbnail path if needed
        }
        $course = new ICTCourse();
        $course->title = $request->title;
        $course->khmer_title = $request->khmer_name;
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
        return redirect()->route('staff.courses.index')->with('success', 'Course created successfully. Please add lessons to the course.');
    }
    public function edit($id): View
    {
        $data = [
            'page_title' => 'ICT | STAFF | EDIT COURSE',
            'course' => ICTCourse::findOrFail($id),
            'instructors' => User::where('role', 'instructor')
                ->orderBy('name') // changed from latest()
                ->get(),
            'schedules' => ICTSchedule::latest()->get()->groupBy('study_day'),
        ];
        return view('frontend.staff.pages.course-management.edit', $data);
    }
    // public function updateDetail(Request $request, $id)
    // {
    //     $report = StudentReports::findOrFail($id);
    //     /*
    //     |--------------------------------------------------------------------------
    //     | VALIDATION
    //     |--------------------------------------------------------------------------
    //     */
    //     $rules = [
    //         'assignment_score' => 30,
    //         'mini_project_score' => 20,
    //         'final_project_score' => 40,
    //     ];
    //     $field = $request->field;
    //     $value = (float) $request->value;
    //     // invalid field
    //     if (!array_key_exists($field, $rules)) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Invalid field'
    //         ], 422);
    //     }
    //     // clamp value
    //     $value = max(0, min($value, $rules[$field]));
    //     /*
    //     |--------------------------------------------------------------------------
    //     | UPDATE FIELD
    //     |--------------------------------------------------------------------------
    //     */
    //     $report->$field = $value;
    //     /*
    //     |--------------------------------------------------------------------------
    //     | RECALCULATE TOTAL
    //     |--------------------------------------------------------------------------
    //     */
    //     $attendance = (float) $report->attendance_score;
    //     $assignment = (float) $report->assignment_score;
    //     $mini = (float) $report->mini_project_score;
    //     $final = (float) $report->final_project_score;
    //     $total =
    //         $attendance +
    //         $assignment +
    //         $mini +
    //         $final;
    //     $report->total_score = round($total, 2);
    //     $report->result = $total >= 50
    //         ? 'pass'
    //         : 'fail';
    //     $report->save();
    //     return response()->json([
    //         'success' => true,
    //         'attendance_score' => $report->attendance_score,
    //         'total_score' => $report->total_score,
    //         'result' => $report->result
    //     ]);
    // }
    public function update(Request $request, $id): RedirectResponse
    {
        $course = ICTCourse::findOrFail($id);
        $request->validate([
            'title' => 'required|string|max:255',
            'khmer_title' => 'nullable|string|max:255',
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
        $course->khmer_title = $request->khmer_name;
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
        return redirect()->route('staff.courses.index')->with('success', 'Course updated successfully.');
    }
    public function destroy($id)
    {
        $course = ICTCourse::findOrFail($id);
        if ($course->thumbnail != '') {
            $this->deleteIfImageExist($course->thumbnail);
        }
        $course->delete();
        return redirect()->route('staff.courses.index')->with('success', 'Course deleted successfully.');
    }
}
