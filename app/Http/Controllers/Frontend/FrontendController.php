<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\ICTCourse;
use App\Models\User;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class FrontendController extends Controller
{
    public function index(): View
    {
        $data = [
            'page_title' => 'ICT | STUDENT | WELCOME 🙏',
            'courses' => ICTCourse::with(['instructor', 'schedule', 'category'])
                ->where('status', 'active')
                ->latest()
                ->take(12)
                ->get()
                ->groupBy('title'),

            'instructors' => User::where('role', 'instructor')
                ->withCount('courses')
                ->withCount('enrollments as students_count')
                ->has('courses')
                ->get(),
        ];
        return view('frontend.pages.home-new.index', $data);
    }
}
