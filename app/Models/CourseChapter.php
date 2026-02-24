<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CourseChapter extends Model
{
    /** @use HasFactory<\Database\Factories\CourseChapterFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'instructor_id',
        'course_id',
    ];

    public function lessons(): HasMany
    {
        return $this->hasMany(CourseChapterLesson::class, 'chapter_id', 'id')->orderBy('order');
    }
}
