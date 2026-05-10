<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentReports extends Model
{
    /** @use HasFactory<\Database\Factories\StudentReportsFactory> */
    use HasFactory;

    protected static function booted()
    {
        static::saving(function ($report) {

            $report->attendance_score =
                min(10, max(0, $report->attendance_score));

            $report->assignment_score =
                min(30, max(0, $report->assignment_score));

            $report->mini_project_score =
                min(20, max(0, $report->mini_project_score));

            $report->final_project_score =
                min(40, max(0, $report->final_project_score));
        });
    }

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
