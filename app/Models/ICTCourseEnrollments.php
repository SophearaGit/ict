<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ICTCourseEnrollments extends Model
{
    /** @use HasFactory<\Database\Factories\ICTCourseEnrollmentsFactory> */
    use HasFactory;

    protected $table = "i_c_t_course_enrollments";

    protected $fillable = [
        'student_id',
        'course_id',
        'enrolled_by',
        'status',
        'enrolled_at',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(IctCourse::class, 'course_id');
    }

}
