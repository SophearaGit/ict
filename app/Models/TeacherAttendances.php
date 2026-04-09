<?php

namespace App\Models;

use Carbon\Carbon;
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

    /* =========================
       DATE
    ========================= */
    public function getFormattedDateAttribute()
    {
        return $this->date
            ? Carbon::parse($this->date)->format('d M Y')
            : '-';
    }

    /* =========================
       TIME
    ========================= */
    public function getFormattedStartTimeAttribute()
    {
        return $this->start_time
            ? Carbon::parse($this->start_time)->format('g:i A')
            : '-';
    }

    public function getFormattedEndTimeAttribute()
    {
        return $this->end_time
            ? Carbon::parse($this->end_time)->format('g:i A')
            : '-';
    }

    /* =========================
       HOURS
    ========================= */
    private function formatHours($hours)
    {
        if (!$hours)
            return '-';

        $h = floor($hours);
        $m = round(($hours - $h) * 60);

        return ($h ? $h . 'h ' : '') . ($m ? $m . 'm' : '');
    }

    public function getFormattedTotalHoursAttribute()
    {
        return $this->formatHours($this->total_hours);
    }

    public function getFormattedActualHoursAttribute()
    {
        return $this->formatHours($this->actual_hours);
    }

    /* =========================
       LATE
    ========================= */
    public function getFormattedLateAttribute()
    {
        return $this->late_minutes
            ? $this->late_minutes . ' min'
            : '-';
    }

    /* =========================
       NOTE
    ========================= */
    public function getFormattedNoteAttribute()
    {
        return $this->late_reason ?: 'No note';
    }

    /* =========================
       ROOM
    ========================= */
    public function getFormattedRoomAttribute()
    {
        return $this->room ?: '-';
    }

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
