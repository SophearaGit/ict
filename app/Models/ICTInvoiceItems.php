<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ICTInvoiceItems extends Model
{
    /** @use HasFactory<\Database\Factories\ICTInvoiceItemsFactory> */
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'course_id',
        'price',
        'discount',
        'extra_charge',
        'total',
    ];

    public function course()
    {
        return $this->belongsTo(ICTCourse::class);
    }

    public function invoice()
    {
        return $this->belongsTo(ICTInvoice::class);
    }

}
