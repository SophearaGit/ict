<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ICTCourse;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use App\Models\ICTPayments;
use App\Models\User;
use Illuminate\Support\Carbon;

class AdminDashboardController extends Controller
{
    public function index(): View
    {
        // 1. Get total revenue (Global)
        $totalRevenue = ICTPayments::sum('amount');
        // 2. Get revenue for "Today"
        $todayRevenue = ICTPayments::whereDate('created_at', Carbon::today())->sum('amount');
        // 3. Get revenue for "Yesterday" (to calculate the difference/growth)
        $yesterdayRevenue = ICTPayments::whereDate('created_at', Carbon::yesterday())->sum('amount');
        // 4. Calculate the difference (the green text in your UI)
        $revenueChange = $todayRevenue - $yesterdayRevenue;

        // Fetch counts for the 3 main cards
        $totalCourses = ICTCourse::count();
        $pendingCourses = ICTCourse::where('status', 'inactive')->count();

        $totalStudents = User::where('role', 'student')->
            whereNull('document')
            ->count();
        $newStudentsToday = User::where('role', 'student')->whereNull('document')->whereDate('created_at', Carbon::today())->count();

        $totalInstructors = User::where('role', 'instructor')->where('document', '!=', '')->count();
        $newInstructorsToday = User::where('role', 'instructor')->where('document', '!=', '')->whereDate('created_at', Carbon::today())->count();

        $data = [
            'page_title' => 'ICT | ADMIN | DASHBOARD',
            'total_revenue' => number_format($totalRevenue, 2),
            'change' => $revenueChange,
            'is_up' => $revenueChange >= 0, // Boolean to toggle green/red UI
            'total_courses' => number_format($totalCourses),
            'pending_courses' => $pendingCourses,
            'total_students' => number_format($totalStudents),
            'new_students' => $newStudentsToday,
            'total_instructors' => number_format($totalInstructors),
            'new_instructors' => $newInstructorsToday,
        ];

        return view('admin.pages.dashboard', $data);
    }

}
