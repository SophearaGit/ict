<?php

namespace App\Http\Controllers\Frontend\Intern;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class InternDashboardController extends Controller
{
    public function index(): View
    {
        return view('frontend.Intern.layout.master');
    }
}
