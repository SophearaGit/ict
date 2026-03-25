<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ICTCourse;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

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

        return view('admin.pages.user.instructor-detail', [
            'page_title' => 'ICT | ADMIN | INSTRUCTOR DETAIL',
            'instructor' => $instructor,
        ]);
    }






}
