{{-- Quick-view fragment — injected as raw HTML into #quickInvoiceModalBody by
     quick-view-modal.blade.php's fetch handler. Self-contained (own <style>)
     since it can land on any page that includes the modal shell; each fetch
     replaces the whole body so the style tag never piles up. Read-only,
     mirrors the full admin.pages.invoice-payment-detail.student-invoice page
     but condensed for a modal. --}}
<style>
    .qv-wrap {
        font-size: 13.5px;
    }

    .qv-banner {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        padding: 22px 28px 18px;
        color: #fff;
    }

    .qv-banner .qv-code {
        font-size: 10px;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        color: rgba(255, 255, 255, .65);
        font-weight: 600;
    }

    .qv-banner h4 {
        color: #fff;
        font-size: 19px;
        font-weight: 700;
        margin: 3px 0 0;
    }

    .qv-status {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        letter-spacing: .4px;
        text-transform: uppercase;
    }

    .qv-status.paid {
        background: rgba(16, 185, 129, .2);
        color: #6ee7b7;
        border: 1px solid rgba(16, 185, 129, .3);
    }

    .qv-status.half_paid {
        background: rgba(245, 158, 11, .2);
        color: #fcd34d;
        border: 1px solid rgba(245, 158, 11, .3);
    }

    .qv-status.unpaid {
        background: rgba(239, 68, 68, .2);
        color: #fca5a5;
        border: 1px solid rgba(239, 68, 68, .3);
    }

    .qv-status.free {
        background: rgba(255, 255, 255, .18);
        color: #fff;
        border: 1px solid rgba(255, 255, 255, .3);
    }

    .qv-meta-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 1px;
        background: #e5e7eb;
        border-bottom: 1px solid #e5e7eb;
    }

    .qv-meta-cell {
        background: #fff;
        padding: 14px 20px;
    }

    .qv-meta-cell .label {
        font-size: 10px;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: .8px;
        font-weight: 600;
        margin-bottom: 3px;
    }

    .qv-meta-cell .value {
        font-size: 13px;
        color: #111827;
        font-weight: 600;
    }

    .qv-section {
        padding: 18px 28px;
    }

    .qv-section-title {
        font-size: 10px;
        letter-spacing: 1.2px;
        text-transform: uppercase;
        color: #6b7280;
        font-weight: 700;
        margin-bottom: 10px;
    }

    .qv-totals {
        background: #f9fafb;
        border-radius: 10px;
        padding: 14px 18px;
    }

    .qv-total-row {
        display: flex;
        justify-content: space-between;
        padding: 3px 0;
        color: #374151;
    }

    .qv-total-row .lbl {
        color: #6b7280;
    }

    .qv-total-row.grand {
        font-size: 14.5px;
        font-weight: 700;
        color: #111827;
        border-top: 1px solid #e5e7eb;
        margin-top: 6px;
        padding-top: 8px;
    }

    .qv-total-row.grand .val {
        color: #4f46e5;
    }

    .qv-remaining {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 5px 12px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
    }

    .qv-remaining.zero {
        background: #d1fae5;
        color: #065f46;
    }

    .qv-remaining.nonzero {
        background: #fee2e2;
        color: #991b1b;
    }

    .qv-payment-row {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 0;
        border-bottom: 1px solid #e5e7eb;
    }

    .qv-payment-row:last-child {
        border-bottom: none;
    }

    .qv-payment-icon {
        width: 34px;
        height: 34px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 15px;
        flex-shrink: 0;
        background: #ede9fe;
        color: #4f46e5;
    }

    .qv-payment-icon.cash {
        background: #d1fae5;
        color: #10b981;
    }

    .qv-payment-icon.payway {
        background: #fef3c7;
        color: #f59e0b;
    }

    .qv-footer {
        background: #f9fafb;
        border-top: 1px solid #e5e7eb;
        padding: 14px 28px;
        display: flex;
        justify-content: flex-end;
    }
</style>

@php
    $statusClass = in_array($invoice->payment_status, ['paid', 'half_paid', 'unpaid', 'free'])
        ? $invoice->payment_status
        : 'unpaid';
    $statusLabel = match ($statusClass) {
        'half_paid' => 'Half Paid',
        default => ucfirst($statusClass),
    };
    $statusIcon = match ($statusClass) {
        'paid' => 'fe-check-circle',
        'half_paid' => 'fe-clock',
        'free' => 'fe-gift',
        default => 'fe-x-circle',
    };
    $paymentOptionLabel = match ($invoice->payment_option) {
        'full' => 'Full Payment',
        'half' => 'Half Payment Plan',
        'multi' => 'Multi-Course Discount',
        'normal' => 'Standard',
        'free' => 'Free Enrollment',
        'other' => 'Custom Plan',
        default => '—',
    };
@endphp

<div class="qv-wrap">
    <div class="qv-banner">
        <div class="d-flex align-items-start justify-content-between flex-wrap gap-2">
            <div>
                <div class="qv-code">Invoice</div>
                <h4>{{ $invoice->invoice_code }}</h4>
                <p class="mb-0 mt-1" style="color:rgba(255,255,255,.7);font-size:12px;">
                    <i class="fe fe-calendar me-1"></i>
                    {{ \Carbon\Carbon::parse($invoice->created_at)->format('d M Y, g:i A') }}
                </p>
            </div>
            <div class="qv-status {{ $statusClass }}">
                <i class="fe {{ $statusIcon }}"></i> {{ $statusLabel }}
            </div>
        </div>
    </div>

    <div class="qv-meta-grid">
        <div class="qv-meta-cell">
            <div class="label"><i class="fe fe-user me-1"></i>Student</div>
            <div class="value">{{ $invoice->student->name ?? '—' }}</div>
        </div>
        <div class="qv-meta-cell">
            <div class="label"><i class="fe fe-book me-1"></i>Course</div>
            <div class="value text-capitalize">{{ $invoice->course->title ?? '—' }}</div>
        </div>
        <div class="qv-meta-cell">
            <div class="label"><i class="fe fe-user-check me-1"></i>Staff</div>
            <div class="value">{{ $invoice->staff->name ?? '—' }}</div>
        </div>
        <div class="qv-meta-cell">
            <div class="label"><i class="fe fe-credit-card me-1"></i>Plan</div>
            <div class="value">{{ $paymentOptionLabel }}</div>
        </div>
        @if ($invoice->paid_at)
            <div class="qv-meta-cell">
                <div class="label"><i class="fe fe-calendar me-1"></i>Paid At</div>
                <div class="value">{{ \Carbon\Carbon::parse($invoice->paid_at)->format('d M Y') }}</div>
            </div>
        @endif
    </div>

    <div class="qv-section" style="border-bottom:1px solid #e5e7eb;">
        <div class="qv-section-title">Summary</div>
        <div class="qv-totals">
            <div class="qv-total-row">
                <span class="lbl">Subtotal</span>
                <span class="val">${{ number_format($invoice->price, 2) }}</span>
            </div>
            @if ($invoice->discount > 0)
                <div class="qv-total-row">
                    <span class="lbl">Discount</span>
                    <span class="val" style="color:#10b981;">−${{ number_format($invoice->discount, 2) }}</span>
                </div>
            @endif
            @if ($invoice->extra_charge > 0)
                <div class="qv-total-row">
                    <span class="lbl">Extra Charge</span>
                    <span class="val" style="color:#f59e0b;">+${{ number_format($invoice->extra_charge, 2) }}</span>
                </div>
            @endif
            <div class="qv-total-row grand">
                <span class="lbl">Total</span>
                <span class="val">${{ number_format($invoice->total_amount, 2) }}</span>
            </div>
            <div class="qv-total-row">
                <span class="lbl">Paid</span>
                <span class="val fw-semibold" style="color:#10b981;">${{ number_format($invoice->paid_amount, 2) }}</span>
            </div>
            <div class="qv-total-row align-items-center">
                <span class="lbl">Remaining</span>
                <span class="qv-remaining {{ $invoice->remaining_amount == 0 ? 'zero' : 'nonzero' }}">
                    @if ($invoice->remaining_amount == 0)
                        <i class="fe fe-check"></i> Fully Paid
                    @else
                        <i class="fe fe-alert-circle"></i> ${{ number_format($invoice->remaining_amount, 2) }}
                    @endif
                </span>
            </div>
        </div>
    </div>

    <div class="qv-section">
        <div class="qv-section-title">Payment History</div>
        @forelse ($invoice->payments as $payment)
            @php
                $method = strtolower($payment->payment_method);
                [$iconClass, $methodLabel] = match ($method) {
                    'cash' => ['fe-dollar-sign', 'Cash'],
                    'card' => ['fe-credit-card', 'Card'],
                    'bank_transfer' => ['fe-repeat', 'Bank Transfer'],
                    'online' => ['fe-globe', 'Online'],
                    'payway' => ['fe-smartphone', 'ABA KHQR'],
                    default => ['fe-dollar-sign', ucfirst($method)],
                };
            @endphp
            <div class="qv-payment-row">
                <div class="qv-payment-icon {{ $method }}">
                    <i class="fe {{ $iconClass }}"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="d-flex align-items-center gap-2">
                        <span class="fw-semibold">${{ number_format($payment->amount, 2) }}</span>
                        <span class="text-muted" style="font-size:11px;">{{ $methodLabel }}</span>
                    </div>
                    <div class="text-muted" style="font-size:11.5px;">
                        <i class="fe fe-calendar me-1"></i>
                        {{ \Carbon\Carbon::parse($payment->paid_at)->format('d M Y, g:i A') }}
                        @if ($payment->paidBy)
                            &middot; by {{ $payment->paidBy->name }}
                        @endif
                    </div>
                </div>
                <span class="badge bg-light-success text-success">
                    <i class="fe fe-check me-1"></i>Received
                </span>
            </div>
        @empty
            <div class="text-center py-3 text-muted">
                <i class="fe fe-inbox d-block mb-1"></i>
                No payment records found.
            </div>
        @endforelse
    </div>

    <div class="qv-footer">
        @if ($invoice->course_id && $invoice->student_id)
            <a href="{{ route('admin.courses.student.invoice', ['course' => $invoice->course_id, 'student' => $invoice->student_id]) }}"
                class="btn btn-sm btn-primary" target="_blank" rel="noopener">
                View Full Invoice <i class="fe fe-arrow-right ms-1"></i>
            </a>
        @endif
    </div>
</div>
