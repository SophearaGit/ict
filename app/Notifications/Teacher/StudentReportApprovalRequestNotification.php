<?php

namespace App\Notifications\Teacher;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

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
            'message' => auth()->user()->name .
                ' requested approval for course: ' .
                $this->course->title,
            'message_type' => 'approval_request',
            'course_id' => $this->course->id,
            'teacher_name' => auth()->user()->name,

            'teacher_image' => auth()->user()->image
                ? asset(auth()->user()->image)
                : asset('default-images/avatar.png'),
        ];
    }
}
