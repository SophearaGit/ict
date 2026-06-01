<?php

namespace App\Notifications\Teacher;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class StudentReportApprovalRequestNotification extends Notification
{
    use Queueable;

    public $course;

    public function __construct($course)
    {
        $this->course = $course;
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'title' => 'Student Report: Approval Request!',
            'message' => auth()->user()->name . ' requested approval for course: ' . $this->course->title,
            'message_type' => 'approval_request',
            'course_id' => $this->course->id,
            'teacher_name' => auth()->user()->name,
            'teacher_image' => auth()->user()->image ? asset(auth()->user()->image) : asset('default-images/avatar.png'),
        ];
    }

    public function sendTelegram(): void
    {
        $token = config('services.telegram.bot_token');
        $chatId = config('services.telegram.admin_chat_id');

        if (!$token || !$chatId) {
            return;
        }

        $instructor = auth()->user()->name;
        $course = $this->course;
        $schedule = $course->schedule;

        $days = $schedule
            ? collect(explode('-', $schedule->study_day))
                ->map(fn($d) => ucfirst($d))
                ->implode(' · ')
            : 'N/A';

        $time = $schedule ? Carbon::parse($schedule->start_time)->format('g:i A') . ' – ' . Carbon::parse($schedule->end_time)->format('g:i A') : 'N/A';

        $shift = $schedule ? ucfirst($schedule->shift) : 'N/A';
        $students = $course->enrollments->count();
        $now = now()->format('d M Y, g:i A');

        $loginUrl = 'https://ictskills.center/admin/x8472/academic-control-center/login';

        $text = "📋 *STUDENT REPORT*\n" . "🔔 _Approval Request_\n" . "━━━━━━━━━━━━━━━━━━━━\n\n" . "📚 *Course:* {$course->title}\n" . "👨‍🏫 *Instructor:* {$instructor}\n" . "👥 *Students:* {$students}\n\n" . "🗓 *Schedule:* {$days}\n" . "⏰ *Time:* {$time} ({$shift})\n\n" . "📅 *Requested at:* {$now}\n\n" . "━━━━━━━━━━━━━━━━━━━━\n" . "👉 [ICT | ADMIN | LOGIN]({$loginUrl})";

        Http::post("https://api.telegram.org/bot{$token}/sendMessage", [
            'chat_id' => $chatId,
            'text' => $text,
            'parse_mode' => 'Markdown',
        ]);
    }
}
