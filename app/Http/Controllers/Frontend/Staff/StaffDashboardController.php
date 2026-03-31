<?php

namespace App\Http\Controllers\Frontend\Staff;

use App\Http\Controllers\Controller;
use App\Models\ICTCourse;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StaffDashboardController extends Controller
{
    public function index(): View
    {
        $data = [
            'page_title' => 'ICT | Staff | Dashboard',
            'students_count' => Auth::user()->students()->count(),
            'staffs_count' => User::where('role', 'staff')->count(),
            'reports_count' => Auth::user()->reports()->count(),
            'today_courses' => ICTCourse::whereHas('schedule', function ($query) {
                $query->whereDate('start_date', today()); // Compare the start_date with today's date
            })
                ->with(['schedule', 'instructor'])
                ->latest()
                ->get(),
            'students' => User::where('registered_by_staff_id', Auth::id())
                ->where('role', 'student')
                ->latest()->paginate(8),
        ];
        return view('frontend.staff.index', $data);
    }


}
