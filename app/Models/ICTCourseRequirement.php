<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ICTCourseRequirement extends Model
{
    /** @use HasFactory<\Database\Factories\ICTCourseRequirementFactory> */
    use HasFactory;

    protected $fillable = ['course_id', 'content', 'order', 'status'];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(ICTCourse::class, 'course_id');
    }
}
