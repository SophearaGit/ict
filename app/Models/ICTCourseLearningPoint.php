<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ICTCourseLearningPoint extends Model
{
    /** @use HasFactory<\Database\Factories\ICTCourseLearningPointFactory> */
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
