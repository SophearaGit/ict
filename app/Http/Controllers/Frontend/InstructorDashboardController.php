<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;

class InstructorDashboardController extends Controller
{
    public function index(): View
    {
        $data = [
            'page_title' => 'ICT | INSTRUCTOR | DASHBOARD',
        ];
        return view('frontend.instructor.index', $data);
    }

    public function readNotification($id)
    {
        $notification = auth()
            ->user()
            ->notifications()
            ->findOrFail($id);

        // security check
        if ($notification->notifiable_id !== auth()->id()) {
            abort(403);
        }

        $notification->markAsRead();

        return back();
    }

    public function readAllNotifications()
    {
        auth()
            ->user()
            ->unreadNotifications
            ->markAsRead();

        return back();
    }

}
