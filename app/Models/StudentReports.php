<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentReports extends Model
{
    /** @use HasFactory<\Database\Factories\StudentReportsFactory> */
    use HasFactory;

    protected $fillable = [
        'course_id',
        'student_id',
        'present',
        'absent',
        'permission',
        'attendance_score',
        'assignment_score',
        'mini_project_score',
        'final_project_score',
        'total_score',
        'result',
        'remark'
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function course()
    {
        return $this->belongsTo(ICTCourse::class);
    }

}
