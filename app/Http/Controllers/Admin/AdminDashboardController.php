<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index(): View
    {
        $data = [
            'page_title' => 'ICT | ADMIN | DASHBOARD',
        ];
        return view('admin.pages.dashboard', $data);
    }

}
