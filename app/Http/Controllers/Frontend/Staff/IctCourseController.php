<?php
namespace App\Http\Controllers\Frontend\Staff;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Models\ICTCourse;
use App\Models\ICTCourseCategory;
use App\Models\ICTCourseEnrollments;
use App\Models\ICTInvoiceItems;
use App\Models\ICTSchedule;
use App\Models\StudentAttendances;
use App\Models\User;
use App\Traites\FileUpload;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Illuminate\Support\Facades\DB;
class IctCourseController extends Controller
{
    use FileUpload;
    public function index(Request $request): View
    {
        $perPage = $request->input('per_page', 10);

        $sortField = $request->input('sort', 'title');
        $sortDirection = $request->input('direction', 'asc');

        $allowedSorts = ['title', 'price', 'start_date', 'duration', 'created_at'];
        if (!in_array($sortField, $allowedSorts)) {
            $sortField = 'title';
        }
        $sortDirection = $sortDirection === 'desc' ? 'desc' : 'asc';

        $query = ICTCourse::with(['instructor', 'schedule', 'category', 'students'])
            ->when($request->filled('search'), function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%');
            })
            ->when($request->filled('status') && in_array($request->status, ['active', 'inactive']), function ($q) use ($request) {
                $q->where('status', $request->status);
            })
            ->orderBy($sortField, $sortDirection);

        $showingAll = $perPage === 'all';

        $courses = $showingAll
            ? $query->get()
            : $query->paginate((int) $perPage)->withQueryString();

        return view('frontend.staff.pages.course-management.course', [
            'page_title' => 'ICT | STAFF | COURSES',
            'courses' => $courses,
            'showingAll' => $showingAll,
            'sortField' => $sortField,
            'sortDirection' => $sortDirection,
        ]);
    }
    public function show(Request $request, $id): View
    {
        $course = ICTCourse::with([
            'students.student_attendances' => fn($q) => $q->where('course_id', $id),
            'studentReports.student',
            'instructor',
            'schedule',
            'category',
        ])->findOrFail($id);
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
        | REPLACE RELATION WITH FILTERED DATA
        |--------------------------------------------------------------------------
        */
        $course->setRelation('teacherAttendances', $filteredAttendances);
        /*
        |--------------------------------------------------------------------------
        | STUDENT ATTENDANCE TABLE
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
        // after loading $course
        $enrollmentMap = ICTCourseEnrollments::where('status', 'active')
            ->whereIn('student_id', $course->students->pluck('id'))
            ->get()
            ->groupBy('student_id')
            ->map(fn($rows) => $rows->pluck('course_id')->toArray());
        return view('frontend.staff.pages.course-management.course-detail.course-detail', [
            'page_title' => 'ICT | STAFF | COURSE DETAIL',
            'course' => $course,
            'attendanceData' => $attendanceData,
            'enrollmentMap' => $enrollmentMap, // ← add this
        ]);
    }
    public function create(): View
    {
        return view('frontend.staff.pages.course-management.create', [
            'page_title' => 'ICT | STAFF | CREATE COURSE',
            'instructors' => User::where('role', 'instructor')->orderBy('name')->get(),
            'schedules' => ICTSchedule::latest()->get()->groupBy('study_day'),
            'categories' => ICTCourseCategory::where('is_active', true)->orderBy('sort_order')->orderBy('name')->get(),
        ]);
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
            'category_id' => 'nullable|exists:i_c_t_course_categories,id',
            'description' => 'nullable|string',
            'thumbnail' => 'nullable|image|max:3000',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'duration' => 'nullable|numeric|min:0',
            'capacity' => 'nullable|integer|min:1',
        ]);
        $course = new ICTCourse();
        $course->title = $request->title;
        $course->khmer_title = $request->khmer_name;
        $course->price = $request->price;
        $course->price_per_session = $request->price_per_session;
        $course->slug = Str::slug($request->title);
        $course->thumbnail = $request->hasFile('thumbnail') ? $this->uploadFile($request->file('thumbnail'), 'uploads/courses/thumbnails') : '';
        $course->status = $request->status;
        $course->instructor_id = $request->instructor_id;
        $course->schedule_id = $request->schedule_id;
        $course->category_id = $request->category_id;
        $course->description = $request->description;
        $course->start_date = $request->start_date;
        $course->end_date = $request->end_date;
        $course->duration = $request->duration;
        $course->capacity = $request->capacity;
        $course->save();
        return redirect()->route('staff.courses.index')->with('success', 'Course created successfully.');
    }
    public function edit($id): View
    {
        return view('frontend.staff.pages.course-management.edit', [
            'page_title' => 'ICT | STAFF | EDIT COURSE',
            'course' => ICTCourse::findOrFail($id),
            'instructors' => User::where('role', 'instructor')->orderBy('name')->get(),
            'schedules' => ICTSchedule::latest()->get()->groupBy('study_day'),
            'categories' => ICTCourseCategory::where('is_active', true)->orderBy('sort_order')->orderBy('name')->get(),
        ]);
    }
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
            'category_id' => 'nullable|exists:i_c_t_course_categories,id',
            'description' => 'nullable|string',
            'thumbnail' => 'nullable|image|max:3000',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'duration' => 'nullable|numeric|min:0',
            'capacity' => 'nullable|integer|min:1',
        ]);
        if ($request->hasFile('thumbnail')) {
            if ($course->thumbnail != '') {
                $this->deleteIfImageExist($course->thumbnail);
            }
            $course->thumbnail = $this->uploadFile($request->file('thumbnail'), 'uploads/courses/thumbnails');
        }
        $course->title = $request->title;
        $course->khmer_title = $request->khmer_title;
        $course->price = $request->price;
        $course->price_per_session = $request->price_per_session;
        $course->slug = Str::slug($request->title);
        $course->status = $request->status;
        $course->instructor_id = $request->instructor_id;
        $course->schedule_id = $request->schedule_id;
        $course->category_id = $request->category_id;
        $course->description = $request->description;
        $course->start_date = $request->start_date;
        $course->end_date = $request->end_date;
        $course->duration = $request->duration;
        $course->capacity = $request->capacity;
        $course->save();
        return redirect()->route('staff.courses.index')->with('success', 'Course updated successfully.');
    }
    public function destroy($id): RedirectResponse
    {
        $course = ICTCourse::findOrFail($id);
        if ($course->thumbnail != '') {
            $this->deleteIfImageExist($course->thumbnail);
        }
        $course->delete();
        return redirect()->route('staff.courses.index')->with('success', 'Course deleted successfully.');
    }
    public function moveStudent(Request $request, $course_id): RedirectResponse
    {
        $request->validate([
            'student_ids' => 'required|array|min:1',
            'student_ids.*' => 'exists:users,id',
            'target_course_id' => 'required|exists:i_c_t_courses,id',
            'charge_difference' => 'nullable|in:0,1',
        ]);
        if ($request->target_course_id == $course_id) {
            return redirect()
                ->back()
                ->withErrors(['target_course_id' => 'Destination course must be different from the current course.']);
        }
        $targetCourseId = (int) $request->target_course_id;
        $targetCourse = ICTCourse::findOrFail($targetCourseId);
        $chargeDifference = $request->input('charge_difference', '0') === '1';
        $studentCount = count($request->student_ids);
        DB::transaction(function () use ($request, $course_id, $targetCourseId, $targetCourse, $chargeDifference) {
            foreach ($request->student_ids as $studentId) {
                /*------------------------------------------------------------------
                 | 1. Find the invoice item for THIS course & student
                 |    (works for both single and multi-course invoices)
                 *------------------------------------------------------------------*/
                $invoiceItem = ICTInvoiceItems::whereHas('invoice', function ($q) use ($studentId) {
                    $q->where('student_id', $studentId);
                })
                    ->where('course_id', $course_id)
                    ->first();
                if ($invoiceItem) {
                    $invoice = $invoiceItem->invoice; // the parent invoice
                    $oldPrice = (float) $invoiceItem->price;
                    $newPrice = (float) $targetCourse->price;
                    /*--------------------------------------------------------------
                     | 2. Check if target course already has an item on this invoice
                     |    (student already enrolled in target — avoid duplicate item)
                     *--------------------------------------------------------------*/
                    $targetItemExists = ICTInvoiceItems::where('invoice_id', $invoice->id)
                        ->where('course_id', $targetCourseId)
                        ->exists();
                    if ($targetItemExists) {
                        // Just remove the old item — student is already in target course
                        $invoiceItem->delete();
                    } else {
                        /*----------------------------------------------------------
                         | 3. Update the invoice item to point to the new course
                         *----------------------------------------------------------*/
                        $itemDiscount = (float) $invoiceItem->discount;
                        $itemExtraCharge = (float) $invoiceItem->extra_charge;
                        // if ($chargeDifference && $newPrice > $oldPrice) {
                        //     $newItemTotal = $newPrice - $itemDiscount + $itemExtraCharge;
                        // } else {
                        //     // Keep proportional discount, just swap the price
                        //     $newItemTotal = $newPrice - $itemDiscount + $itemExtraCharge;
                        // }
                        $newItemTotal = $newPrice - $itemDiscount + $itemExtraCharge;
                        $invoiceItem->update([
                            'course_id' => $targetCourseId,
                            'price' => $newPrice,
                            'total' => round($newItemTotal, 2),
                        ]);
                    }
                    /*--------------------------------------------------------------
                     | 4. Recalculate invoice header totals from all remaining items
                     *--------------------------------------------------------------*/
                    $allItems = ICTInvoiceItems::where('invoice_id', $invoice->id)->get();
                    $newFullPrice = $allItems->sum('price');
                    $newTotalDiscount = $allItems->sum('discount');
                    $newTotalExtra = $allItems->sum('extra_charge');
                    $newTotal = $allItems->sum('total');
                    $newRemaining = round($newTotal - (float) $invoice->paid_amount, 2);
                    $newRemaining = max($newRemaining, 0);
                    $paymentStatus = $newRemaining <= 0 ? 'paid' : 'half_paid';
                    /*--------------------------------------------------------------
                     | 5. Update invoice header course_id only if it pointed to
                     |    the course being moved (primary course swap)
                     *--------------------------------------------------------------*/
                    $newPrimaryCourseId = (int) $invoice->course_id === (int) $course_id
                        ? $targetCourseId
                        : $invoice->course_id;
                    $invoice->update([
                        'course_id' => $newPrimaryCourseId,
                        'price' => round($newFullPrice, 2),
                        'discount' => round($newTotalDiscount, 2),
                        'extra_charge' => round($newTotalExtra, 2),
                        'total_amount' => round($newTotal, 2),
                        'remaining_amount' => $newRemaining,
                        'payment_status' => $paymentStatus,
                    ]);
                }
                /*------------------------------------------------------------------
                 | 6. Move the enrollment
                 *------------------------------------------------------------------*/
                ICTCourseEnrollments::where('course_id', $course_id)
                    ->where('student_id', $studentId)
                    ->delete();
                if (!ICTCourseEnrollments::where('course_id', $targetCourseId)->where('student_id', $studentId)->exists()) {
                    ICTCourseEnrollments::create([
                        'student_id' => $studentId,
                        'course_id' => $targetCourseId,
                        'enrolled_by' => auth()->id(),
                        'status' => 'active',
                        'enrolled_at' => now(),
                    ]);
                }
            }
        });
        return redirect()
            ->back()
            ->with('success', $studentCount . ' student(s) moved successfully.');
    }
    public function removeStudent(Request $request, $course_id): RedirectResponse
    {
        $request->validate([
            'student_ids' => 'required|array|min:1',
            'student_ids.*' => 'exists:users,id',
        ]);
        ICTCourseEnrollments::where('course_id', $course_id)
            ->whereIn('student_id', $request->student_ids)
            ->update(['status' => 'dropped']);
        return redirect()
            ->back()
            ->with('success', count($request->student_ids) . ' student(s) removed from course.');
    }
}
