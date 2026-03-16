<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ICTCourse;
use App\Models\ICTSchedule;
use Illuminate\Http\Request;

class RealTimeCoursesController extends Controller
{
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
}
