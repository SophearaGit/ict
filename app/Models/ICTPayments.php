<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;
use App\Models\ICTInvoice;

class ICTPayments extends Model
{
    /** @use HasFactory<\Database\Factories\ICTPaymentsFactory> */
    use HasFactory;

    protected $table = 'i_c_t_payments';

    protected $fillable = ['invoice_id', 'amount', 'payment_method', 'note', 'paid_by', 'paid_at', 'gateway_reference', 'gateway_approval_code', 'gateway_response'];
    protected $casts = ['gateway_response' => 'array'];

    public function paidBy()
    {
        return $this->belongsTo(User::class, 'paid_by');
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(ICTInvoice::class, 'invoice_id');
    }
}
