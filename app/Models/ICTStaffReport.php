<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ICTStaffReport extends Model
{
    /** @use HasFactory<\Database\Factories\ICTStaffReportFactory> */
    use HasFactory;

    protected $fillable = [
        'reported_by',
        'report_content',
        'status',
    ];

    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reported_by');
    }





}
