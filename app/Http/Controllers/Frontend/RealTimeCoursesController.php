<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\ICTCourse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RealTimeCoursesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $courses = ICTCourse::with('teacherAttendances')
            ->withCount('enrollments')
            ->where('instructor_id', Auth::id())
            ->latest()
            ->get()
            ->map(function ($course) {
                // Get last ATH (total taught hours)
                $totalATH = $course->teacherAttendances->last()->actual_hours ?? 0;

                // Course duration (make sure this column exists)
                $duration = $course->duration ?? 0;

                // Calculate %
                $progress = $duration > 0 ? ($totalATH / $duration) * 100 : 0;

                // Limit max = 100
                $course->progress = min(round($progress, 2), 100);

                return $course;
            });

        return view('frontend.instructor.pages.course-real-time.course-real-time', [
            'page_title' => 'ICT | INSTRUCTOR | COURSES (REAL-TIME)',
            'ictcourses' => $courses,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $course = ICTCourse::with([
            'teacherAttendances',
            'teacherAttendances.schedule'
        ])
            ->where('id', $id)
            ->where('instructor_id', Auth::id())
            ->firstOrFail();

        // Paginate students (IMPORTANT)
        $students = $course->students()->paginate(5); // 5 per page

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

        $course->other_courses = ICTCourse::where('instructor_id', Auth::id())
            ->where('id', '!=', $course->id)
            ->latest()
            ->get();

        $attendances = $course->teacherAttendances;

        return view('frontend.instructor.pages.course-real-time.course-real-time-show', [
            'page_title' => 'ICT | INSTRUCTOR | COURSES (REAL-TIME) | SHOW',
            'course' => $course,
            'students' => $students,
            'other_courses' => $course->other_courses,
            'attendances' => $attendances, // ✅ ADD THIS
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
