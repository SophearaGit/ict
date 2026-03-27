<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ICTCourse;
use App\Models\ICTSchedule;
use App\Models\User;
use App\Traites\FileUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class RealTimeCoursesController extends Controller
{
    use FileUpload;

    // public function realtimeIndex(Request $request)
    // {
    //     $courses = ICTCourse::query();

    //     // Search
    //     if ($request->filled('search_query')) {
    //         $courses->where('title', 'like', '%' . $request->search_query . '%');
    //     }

    //     // Schedule filter
    //     if ($request->filled('schedule_ids')) {
    //         $courses->whereHas('schedule', function ($q) use ($request) {
    //             $q->whereIn('id', $request->schedule_ids);
    //         });
    //     }

    //     // ✅ Status filter
    //     if ($request->filled('status')) {
    //         $courses->where('status', $request->status);
    //     }

    //     $courses = ICTCourse::query()
    //         ->withCount('enrollments')
    //         ->addSelect([
    //             'total_revenue' => DB::table('i_c_t_invoice_items')
    //                 ->join('i_c_t_invoices', 'i_c_t_invoice_items.invoice_id', '=', 'i_c_t_invoices.id')
    //                 ->join('i_c_t_payments', 'i_c_t_invoices.id', '=', 'i_c_t_payments.invoice_id')
    //                 ->whereColumn('i_c_t_invoice_items.course_id', 'i_c_t_courses.id')
    //                 ->selectRaw("
    //             COALESCE(SUM(
    //                 i_c_t_payments.amount *
    //                 (i_c_t_invoice_items.total / i_c_t_invoices.total_amount)
    //             ), 0)
    //         ")
    //         ])
    //         ->orderBy('title', 'asc')
    //         ->paginate(6)
    //         ->withQueryString(); // ✅ KEEP THIS

    //     $groupedSchedules = ICTSchedule::all()->groupBy('study_day');

    //     return view('admin.pages.real-time-courses.real-time-courses', [
    //         'page_title' => 'ICT | ADMIN | REAL TIME COURSES',
    //         'courses' => $courses,
    //         'schedules' => ICTSchedule::all(),
    //         'selected_schedule_ids' => $request->schedule_ids ?? [],
    //         'groupedSchedules' => $groupedSchedules,
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
