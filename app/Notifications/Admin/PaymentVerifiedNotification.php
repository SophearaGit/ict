<?php
// app/Notifications/Admin/PaymentVerifiedNotification.php

namespace App\Notifications\Admin;  // ← fix namespace

use App\Models\ICTInvoice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class PaymentVerifiedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public readonly ICTInvoice $invoice,
        public readonly string $hash,
        public readonly float $paidAmount,
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'payment_verified',
            'invoice_id' => $this->invoice->id,
            'invoice_code' => $this->invoice->invoice_code,
            'course_id' => $this->invoice->course_id,
            'student_id' => $this->invoice->student_id,
            'paid_amount' => $this->paidAmount,
            'hash' => $this->hash,
            'paid_at' => now()->toDateTimeString(),
            'message' => "Payment confirmed for invoice #{$this->invoice->invoice_code} — \${$this->paidAmount}",
        ];
    }
}
