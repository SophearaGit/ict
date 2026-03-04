<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class FrontendController extends Controller
{
    public function index(): View
    {
        $data = [
            'page_title' => 'ICT | Welcome',
        ];
        return view('frontend.pages.home.index', $data);
    }
}
