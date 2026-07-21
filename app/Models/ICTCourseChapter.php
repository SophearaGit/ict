<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
class ICTCourseChapter extends Model
{
    protected $fillable = ['course_id', 'title', 'order', 'status'];
    protected $casts = [
        'status' => 'boolean',
    ];
    public function course(): BelongsTo
    {
        return $this->belongsTo(ICTCourse::class, 'course_id');
    }
    public function lessons(): HasMany
    {
        return $this->hasMany(ICTCourseChapterLesson::class, 'chapter_id')->orderBy('order');
    }
}
