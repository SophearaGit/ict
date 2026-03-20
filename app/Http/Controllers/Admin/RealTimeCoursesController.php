<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ICTCourse;
use App\Models\ICTSchedule;
use App\Models\User;
use App\Traites\FileUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RealTimeCoursesController extends Controller
{
    use FileUpload;

    public function realtimeIndex(Request $request)
    {
        $courses = ICTCourse::query();

        // Search
        if ($request->filled('search_query')) {
            $courses->where('title', 'like', '%' . $request->search_query . '%');
        }

        // Schedule filter
        if ($request->filled('schedule_ids')) {
            $courses->whereHas('schedule', function ($q) use ($request) {
                $q->whereIn('id', $request->schedule_ids);
            });
        }

        // ✅ Status filter
        if ($request->filled('status')) {
            $courses->where('status', $request->status);
        }

        $courses = $courses
            ->withCount('enrollments')
            ->withSum('invoiceItems as total_revenue', 'total') // if original price use price, if total use total
            ->orderBy('title', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate(6)
            ->withQueryString();

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
        $course->save();

        return redirect()->route('admin.courses.realtime.index')
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
