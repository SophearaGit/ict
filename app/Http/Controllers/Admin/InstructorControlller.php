<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class InstructorControlller extends Controller
{
    public function index()
    {
        $data = [
            'page_title' => 'ICT Center | Instructors',
            'instructors' => User::where('role', 'instructor')
                ->where('approval_status', 'approved')
                ->whereNotNull('document')
                ->latest()->get(),
        ];
        return view('admin.pages.user.instructor', $data);
    }
}
