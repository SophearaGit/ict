<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\ICTCourse;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class FrontendController extends Controller
{
    public function index(): View
    {
        $data = [
            'page_title' => 'Welcome to ICT Professional Training Center',
            'courses' => ICTCourse::with(['instructor', 'schedule', 'category'])
                ->where('status', 'active')
                ->latest()
                ->take(12)
                ->get()
                ->groupBy('title'),
        ];
        return view('frontend.pages.home-new.index', $data);
    }
}
