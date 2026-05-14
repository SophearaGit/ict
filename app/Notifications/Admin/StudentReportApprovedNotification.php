<?php

namespace App\Notifications\Admin;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StudentReportApprovedNotification extends Notification
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
            'title' => 'Student Report Approved!',
            'message' => 'Admin approved reports for course: '
                . $this->course->title,

            'course_id' => $this->course->id,
            'admin_image' => auth('admin')->user()->image,
            'redirect_url' => route('instructor.courses.real_time.show', $this->course->id),
        ];
    }

}
