<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InternReport extends Model
{
    use HasFactory;

    protected $table = 'intern_reports';

    protected $fillable = [
        'reported_by',
        'report_date',
        'report_content',
        'status',
        'reviewed_by_admin_id',
        'reviewed_by_staff_id',
        'reviewed_at',
    ];

    protected $casts = [
        'report_date' => 'date',
        'reviewed_at' => 'datetime',
    ];

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reported_by');
    }

    public function reviewedByAdmin()
    {
        return $this->belongsTo(Admin::class, 'reviewed_by_admin_id');
    }

    public function reviewedByStaff()
    {
        return $this->belongsTo(User::class, 'reviewed_by_staff_id');
    }

    // Convenience accessor so views don't need to check both
    public function getReviewerAttribute()
    {
        return $this->reviewedByAdmin ?? $this->reviewedByStaff;
    }
}
