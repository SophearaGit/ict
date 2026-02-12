<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class InstructorDashboardController extends Controller
{
    public function index(): View
    {
        $data = [
            'page_title' => 'ICT Center | Dashboard',
        ];
        return view('frontend.instructor.index', $data);
    }
}
