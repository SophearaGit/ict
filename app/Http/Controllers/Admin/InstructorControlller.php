<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ICTCourse;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InstructorControlller extends Controller
{
    public function index(Request $request): View
    {
        $instructors = User::with(['courses'])
            ->where('role', 'instructor')
            ->where('approval_status', 'approved')
            ->whereNotNull('document')

            // 🔍 Search
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where('name', 'like', "%{$request->search}%");
            })

            // 📘 Subject Filter
            ->when($request->filled('subject') && $request->subject !== 'All Subject', function ($query) use ($request) {
                $query->whereHas('courses', function ($q) use ($request) {
                    $q->where('title', $request->subject);
                });
            })

            // 🟢 Status Filter
            ->when($request->filled('status') && $request->status !== 'All Status', function ($query) use ($request) {
                $query->where('status', $request->status);
            })

            // 👤 Gender Filter
            ->when($request->filled('gender') && $request->gender !== 'All Gender', function ($query) use ($request) {
                $query->where('gender', $request->gender);
            })

            ->latest()
            ->paginate(8)
            ->withQueryString(); // 🔥 VERY IMPORTANT

        $subjects = ICTCourse::pluck('title')->unique();

        return view('admin.pages.user.instructor', [
            'page_title' => 'ICT | ADMIN | INSTRUCTORS',
            'instructors' => $instructors,
            'subjects' => $subjects,
        ]);
    }


    public function instructorShowDetail($id): View
    {
        $instructor = User::with('courses')->findOrFail($id);

        // ✅ Get latest ATH per course (NOT SUM)
        $athByCourse = DB::table('teacher_attendances as t1')
            ->select('t1.course_id', 't1.actual_hours as ath')
            ->where('t1.teacher_id', $id)
            ->where('t1.status', 'present')
            ->whereRaw('t1.id = (
            SELECT MAX(t2.id)
            FROM teacher_attendances t2
            WHERE t2.course_id = t1.course_id
            AND t2.teacher_id = t1.teacher_id
        )')
            ->get()
            ->keyBy('course_id');

        return view('admin.pages.user.instructor-detail', [
            'instructor' => $instructor,
            'athByCourse' => $athByCourse,
        ]);
    }






}
