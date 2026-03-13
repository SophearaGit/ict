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



        $data = [
            'page_title' => 'ICT | ADMIN | REAL TIME COURSES',
            'courses' => ICTCourse::when($request->filled('search'), function ($query) use ($request) {
                $query->where('title', 'like', '%' . $request->search . '%');
            })
                ->withCount('enrollments')
                ->orderBy('title', 'asc')
                ->orderBy('created_at', 'desc')
                ->paginate(6),
            'schedules' => ICTSchedule::all(),
        ];
        return view('admin.pages.real-time-courses.real-time-courses', $data);
    }
}
