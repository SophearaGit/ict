<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index()
    {
        $data = [
            'page_title' => 'ICT Center | Instructors',
            'students' => User::where('role', 'student')
                ->where('approval_status', 'approved')
                ->whereNull('document')
                ->orWhere('document', '')
                ->latest()->get(),
        ];
        return view('admin.pages.student', $data);
    }
}
