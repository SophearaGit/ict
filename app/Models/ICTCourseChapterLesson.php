<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class ICTCourseChapterLesson extends Model
{
    protected $fillable = ['chapter_id', 'course_id', 'title', 'order', 'status'];
    protected $casts = [
        'status' => 'boolean',
    ];
    public function chapter(): BelongsTo
    {
        return $this->belongsTo(ICTCourseChapter::class, 'chapter_id');
    }
    public function course(): BelongsTo
    {
        return $this->belongsTo(ICTCourse::class, 'course_id');
    }
}
