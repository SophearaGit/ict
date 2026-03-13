<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User;

class ICTCourse extends Model
{
    /** @use HasFactory<\Database\Factories\ICTCourseFactory> */
    use HasFactory;

    protected $fillable = [
        'instructor_id',
        'schedule_id',
        'thumbnail',
        'title',
        'slug',
        'description',
        'price',
        'status',
    ];

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
        return $this->belongsToMany(User::class, 'i_c_t_invoices', 'course_id', 'student_id')
            ->withTimestamps();
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
            'course_id',   // Foreign key on invoices
            'invoice_id',  // Foreign key on payments
            'id',          // Local key on courses
            'id'           // Local key on invoices
        );
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(ICTCourseEnrollments::class, 'course_id');
    }

}
