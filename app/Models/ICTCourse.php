<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User;

class ICTCourse extends Model
{
    /** @use HasFactory<\Database\Factories\ICTCourseFactory> */
    use HasFactory;

    protected $fillable = [
        'instructor_id',
        'schedule_id',
        'thumbnail',
        'title',
        'slug',
        'description',
        'start_date',
        'end_date',
        'price',
        'status',
    ];

    public function instructor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(ICTSchedule::class, 'schedule_id');
    }


}
