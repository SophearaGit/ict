<?php
namespace App\Http\Controllers\Frontend\Staff;
use App\Models\User;
use App\Models\ICTCourse;
use App\Models\ICTSchedule;
use App\Models\ICTInvoiceItems;
use App\Models\ICTCourseCategory;
use App\Models\ICTCourseEnrollments;
use App\Services\Course\CourseDetailService;
use App\Traites\FileUpload;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\RedirectResponse;
class IctCourseController extends Controller
{
    use FileUpload;
    public function index(Request $request): View
    {
        $perPage = $request->input('per_page', 9);
        $sortField = $request->input('sort', 'title');
        $sortDirection = $request->input('direction', 'asc');
        $allowedSorts = ['title', 'price', 'start_date', 'duration', 'created_at'];
        if (!in_array($sortField, $allowedSorts)) {
            $sortField = 'title';
        }
        $sortDirection = $sortDirection === 'desc' ? 'desc' : 'asc';
        $query = ICTCourse::with(['instructor', 'schedule', 'category'])
            ->withCount('students')
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
    public function show(
        Request $request,
        ICTCourse $course,
        CourseDetailService $service
    ): View {
        return view(
            'frontend.staff.pages.course-management.course-detail.course-detail',
            $service->getData($course, $request)
        );
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
            'telegram_group_link' => 'nullable|url',
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
        $course->telegram_group_link = $request->telegram_group_link;
        $course->save();
        return redirect()->route('staff.courses.index')->with('success', 'Course created successfully.');
    }
    public function edit(ICTCourse $course): View
    {
        return view('frontend.staff.pages.course-management.edit', [
            'page_title' => 'ICT | STAFF | EDIT COURSE',
            'course' => $course,
            'instructors' => User::where('role', 'instructor')->orderBy('name')->get(),
            'schedules' => ICTSchedule::latest()->get()->groupBy('study_day'),
            'categories' => ICTCourseCategory::where('is_active', true)
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get(),
        ]);
    }
    public function update(Request $request, ICTCourse $course): RedirectResponse
    {
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
            'telegram_group_link' => 'nullable|url',
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
        $course->telegram_group_link = $request->telegram_group_link;
        $course->save();
        return redirect($request->redirect ?? route('staff.courses.index'))
            ->with('success', 'Course updated successfully.');
    }
    public function destroy(ICTCourse $course): RedirectResponse
    {
        if ($course->thumbnail) {
            $this->deleteIfImageExist($course->thumbnail);
        }
        $course->delete();
        return redirect()->route('staff.courses.index')
            ->with('success', 'Course deleted successfully.');
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
