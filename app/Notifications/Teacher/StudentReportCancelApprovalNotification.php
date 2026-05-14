<?php

namespace App\Notifications\Teacher;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StudentReportCancelApprovalNotification extends Notification
{
    protected $course;

    public function __construct($course)
    {
        $this->course = $course;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Student Report: Cancelled!',
            'message' => auth()->user()->name .
                ' cancelled approval request for course: ' .
                $this->course->title,
            'course_id' => $this->course->id,
            'teacher_name' => auth()->user()->name,
            'message_type' => 'cancel_approval',
            'teacher_image' => auth()->user()->image
                ? asset(auth()->user()->image)
                : asset('default-images/avatar.png'),
        ];
    }

}
