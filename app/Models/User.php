<?php
namespace App\Models;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        // Basic Information
        'image',
        'name',
        'khmer_name',
        'email',
        'password',
        'bio',
        'headline',
        'designation',
        'expertise',

        // Personal Information
        'gender',
        'dob',
        'nationality',
        'document',
        'location',

        // Contact Information
        'phone',
        'alternate_phone',

        // Social Media
        'facebook',
        'x',
        'linkedin',
        'website',
        'github',
        'instagram',
        'telegram',
        'tiktok',
        'youtube',

        // Account Information
        'role',
        'approval_status',
        'status',
        'email_verified_at',
        'remember_token',

        // System Information
        'login_as',
        'registered_by_staff_id',
        'admin_approval_edit_staff',
    ];
    protected $casts = [
        'admin_approval_edit_staff' => 'boolean',
    ];
    public function payments(): HasManyThrough
    {
        return $this->hasManyThrough(
            ICTPayments::class,
            ICTInvoice::class,
            'student_id',   // FK on invoices table referencing users.id
            'invoice_id',   // FK on ict_payments table referencing invoices.id
            'id',           // local key on users
            'id',           // local key on invoices
        );
    }
    public function student_attendances()
    {
        return $this->hasMany(StudentAttendances::class, 'student_id');
    }
    public function attendances()
    {
        return $this->hasMany(TeacherAttendances::class, 'teacher_id');
    }
    public function reports(): HasMany
    {
        return $this->hasMany(ICTStaffReport::class, 'reported_by');
    }
    public function students(): HasMany
    {
        return $this->hasMany(User::class, 'registered_by_staff_id', 'id')->where('role', 'student');
    }
    public function courses(): HasMany
    {
        return $this->hasMany(ICTCourse::class, 'instructor_id');
    }
    public function enrollments(): HasMany
    {
        return $this->hasMany(ICTCourseEnrollments::class, 'student_id');
    }
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'expertise' => 'array',
        ];
    }
}
