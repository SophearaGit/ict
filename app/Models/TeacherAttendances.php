<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherAttendances extends Model
{
    /** @use HasFactory<\Database\Factories\TeacherAttendancesFactory> */
    use HasFactory;

    protected $fillable = [
        'course_id',
        'teacher_id',
        'schedule_id',
        'date',
        'start_time',
        'end_time',
        'total_hours',
        'actual_hours',
        'room',
        'late_minutes',
        'late_reason',
        'status',
    ];

    public function invoice()
    {
        return $this->belongsTo(ICTInvoice::class, 'invoice_id');
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function course()
    {
        return $this->belongsTo(ICTCourse::class, 'course_id');
    }

    public function schedule()
    {
        return $this->belongsTo(ICTSchedule::class, 'schedule_id');
    }

}
