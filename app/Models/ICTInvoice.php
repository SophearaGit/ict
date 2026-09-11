<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ICTInvoice extends Model
{
    /** @use HasFactory<\Database\Factories\ICTInvoiceFactory> */
    use HasFactory;

    protected $fillable = ['staff_id', 'student_id', 'course_id', 'price', 'discount', 'extra_charge', 'total_amount', 'paid_amount', 'remaining_amount', 'payment_option', 'payment_status', 'invoice_code', 'paid_at', 'bakong_txn_ref', 'bakong_hash', 'payway_tran_id', 'payway_tran_started_at', 'payment_gateway'];

    protected $casts = [
        'payway_tran_started_at' => 'datetime',
        // Without this cast, $invoice->paid_at is a raw DB string, not a
        // Carbon instance. Every optional($invoice->paid_at)->format(...)
        // call (the PayWay receipt's "Paid At", the staff invoice page's
        // "Paid On" chip) was silently returning null either way —
        // optional() only calls through on a real object, so on a plain
        // string it just no-ops instead of erroring, which is why this
        // went unnoticed rather than throwing.
        'paid_at' => 'datetime',
    ];

    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }

    public function paidBy()
    {
        return $this->belongsTo(User::class, 'paid_by');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(ICTCourse::class, 'course_id');
    }

    public function payments()
    {
        return $this->hasMany(ICTPayments::class, 'invoice_id');
    }

    public function items()
    {
        return $this->hasMany(ICTInvoiceItems::class, 'invoice_id');
    }
}
