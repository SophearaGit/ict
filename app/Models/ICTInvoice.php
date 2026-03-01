<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ICTInvoice extends Model
{
    /** @use HasFactory<\Database\Factories\ICTInvoiceFactory> */
    use HasFactory;

    protected $fillable = [
        'staff_id',
        'student_id',
        'course_id',
        'price',
        'discount',
        'total_amount',
        'paid_amount',
        'remaining_amount',
        'payment_status',
        'invoice_code',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(ICTCourse::class, 'course_id');
    }


}
