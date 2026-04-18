<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentAttendances extends Model
{
    /** @use HasFactory<\Database\Factories\StudentAttendancesFactory> */
    use HasFactory;

    protected $fillable = [
        'course_id',
        'student_id',
        'date',
        'status',
        'note',
    ];

    public function course()
    {
        return $this->belongsTo(ICTCourse::class, 'course_id');
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

}
