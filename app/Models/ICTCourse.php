<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
// use Illuminate\Foundation\Auth\User;
use App\Models\User;
class ICTCourse extends Model
{
    /** @use HasFactory<\Database\Factories\ICTCourseFactory> */
    use HasFactory;
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'duration' => 'float',
    ];
    protected $fillable = [
        'instructor_id',
        'schedule_id',
        'thumbnail',
        'title',
        'khmer_title',
        'slug',
        'description',
        'price',
        'price_per_session',
        'status',
        'start_date',
        'end_date',
        'duration',
        'category_id',
        'capacity',
        'telegram_group_link',
        'featured',
    ];
    // In ICTCourse model
    public function scopeFrontendVisible($query)
    {
        /*----------------------------------------------------------------
         | Public-facing courses: everything except drafts. Drafts are
         | either auto-set by the nightly expiry job or manually staged
         | by staff, and should never be reachable by students. Inactive
         | (manually closed) courses stay visible — e.g. so students can
         | still view details on a closed batch or a completed course.
         *----------------------------------------------------------------*/
        return $query->where('status', '!=', 'draft');
    }
    public function chapters(): HasMany
    {
        return $this->hasMany(ICTCourseChapter::class, 'course_id')->orderBy('order');
    }
    public function lessons()
    {
        return $this->hasManyThrough(
            ICTCourseChapterLesson::class,
            ICTCourseChapter::class,
            'course_id',   // Foreign key on chapters table
            'chapter_id',  // Foreign key on lessons table
            'id',          // Local key on courses table
            'id'           // Local key on chapters table
        );
    }
    // Add these two relations inside App\Models\ICTCourse (near chapters()/lessons())

    public function learningPoints(): HasMany
    {
        return $this->hasMany(ICTCourseLearningPoint::class, 'course_id')->orderBy('order');
    }
    public function requirements(): HasMany
    {
        return $this->hasMany(ICTCourseRequirement::class, 'course_id')->orderBy('order');
    }

    // If you only want the active/visible ones on the frontend (mirrors status like chapters):

    public function activeLearningPoints(): HasMany
    {
        return $this->learningPoints()->where('status', true);
    }

    public function activeRequirements(): HasMany
    {
        return $this->requirements()->where('status', true);
    }
    public function studentReports()
    {
        return $this->hasMany(StudentReports::class, 'course_id');
    }
    public function getRevenueAttribute()
    {
        $totalATH = $this->teacherAttendances->last()->actual_hours ?? 0;
        $sessions = $totalATH / 1.5;
        if ($this->price_per_session) {
            return number_format($sessions * $this->price_per_session, 2);
        }
    }
    public function teacherAttendances(): HasMany
    {
        return $this->hasMany(TeacherAttendances::class, 'course_id');
    }
    public function latestTeacherAttendance()
    {
        return $this->hasOne(TeacherAttendances::class, 'course_id')
            ->latestOfMany();
    }
    public function getProgressAttribute(): float
    {
        $totalATH = $this->latestTeacherAttendance->actual_hours ?? 0;
        $duration = $this->duration ?? 0;
        if ($duration <= 0) {
            return 0;
        }
        return min(round(($totalATH / $duration) * 100, 2), 100);
    }
    public function getCompletedSessionsAttribute(): int
    {
        $totalATH = $this->latestTeacherAttendance->actual_hours ?? 0;
        return floor($totalATH / 1.5);
    }
    public function getTotalSessionsAttribute(): int
    {
        return $this->duration > 0
            ? round($this->duration / 1.5)
            : 0;
    }
    public function instructor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }
    public function schedule(): BelongsTo
    {
        return $this->belongsTo(ICTSchedule::class, 'schedule_id');
    }
    public function students(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'i_c_t_course_enrollments', 'course_id', 'student_id')->wherePivot('status', 'active')->withPivot('status')->withTimestamps();
    }
    public function completedStudents(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'i_c_t_course_enrollments', 'course_id', 'student_id')->wherePivot('status', 'completed')->withPivot('status')->withTimestamps();
    }
    public function droppedStudents(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'i_c_t_course_enrollments', 'course_id', 'student_id')->wherePivot('status', 'dropped')->withPivot('status')->withTimestamps();
    }
    public function invoices()
    {
        return $this->hasMany(ICTInvoice::class, 'course_id');
    }
    public function payments()
    {
        return $this->hasManyThrough(
            ICTPayments::class,
            ICTInvoice::class,
            'course_id', // Foreign key on invoices
            'invoice_id', // Foreign key on payments
            'id', // Local key on courses
            'id', // Local key on invoices
        );
    }
    public function enrollments(): HasMany
    {
        return $this->hasMany(ICTCourseEnrollments::class, 'course_id');
    }
    public function invoiceItems()
    {
        return $this->hasMany(ICTInvoiceItems::class, 'course_id');
    }
    public function category()
    {
        return $this->belongsTo(ICTCourseCategory::class, 'category_id');
    }
}
