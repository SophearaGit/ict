<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index(Request $request): View
    {

        $data = [
            'page_title' => 'ICT Center | Courses',
            'courses' => Course::with('instructor')
                ->when($request->filled('status'), function ($query) use ($request) {
                    $query->where('is_approved', $request->status);
                })
                ->when($request->filled('search'), function ($query) use ($request) {
                    $query->where('title', 'like', '%' . $request->search . '%');
                })
                ->latest()
                ->paginate(10),
        ];
        return view('admin.pages.course.index', $data);
    }
}
