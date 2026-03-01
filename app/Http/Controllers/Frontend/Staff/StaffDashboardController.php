<?php

namespace App\Http\Controllers\Frontend\Staff;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StaffDashboardController extends Controller
{
    public function index(): View
    {
        $data = [
            'page_title' => 'ICT Center | Dashboard',
            'students_count' => Auth::user()->students()->count(),
            'students' => User::where('registered_by_staff_id', Auth::id())
                ->where('role', 'student')
                ->latest()->paginate(4),

        ];
        return view('frontend.staff.index', $data);
    }

}
