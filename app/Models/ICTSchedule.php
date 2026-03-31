<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ICTSchedule extends Model
{
    /** @use HasFactory<\Database\Factories\ICTScheduleFactory> */
    use HasFactory;

    public function courses(): HasMany
    {
        return $this->hasMany(ICTCourse::class, 'schedule_id');
    }

    public function attendances()
    {
        return $this->hasMany(TeacherAttendances::class, 'schedule_id');
    }

    public function getShortDaysAttribute()
    {
        return collect(explode('-', $this->study_day))
            ->map(fn($d) => \Illuminate\Support\Str::of($d)->ucfirst()->substr(0, 3))
            ->join(' • ');
    }

    public function getFormattedTimeAttribute()
    {
        $start = \Carbon\Carbon::parse($this->start_time)->format('g:i');
        $end = \Carbon\Carbon::parse($this->end_time)->format('g:i A');

        return "$start – $end";
    }

    public function getShiftLabelAttribute()
    {
        return ucfirst($this->shift);
    }

}
