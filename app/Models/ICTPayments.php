<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ICTPayments extends Model
{
    /** @use HasFactory<\Database\Factories\ICTPaymentsFactory> */
    use HasFactory;

    protected $table = 'i_c_t_payments';

    protected $fillable = [
        'invoice_id',
        'amount',
        'payment_method',
        'paid_by',
        'paid_at',
        'note',
    ];

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(IctInvoice::class, 'invoice_id');
    }

}
