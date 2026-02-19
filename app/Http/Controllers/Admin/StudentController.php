<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index(Request $request): View
    {
        $data = [
            'page_title' => 'ICT Center | Students',
            'students' => User::where('role', 'student')
                ->where('approval_status', 'approved')
                ->whereNull('document')
                ->orWhere('document', '')
                ->when($request->filled('search'), function ($query) use ($request) {
                    $query->where('name', 'like', "%{$request->search}%");
                })
                ->latest()->paginate(10),
        ];
        return view('admin.pages.user.student', $data);
    }
}
