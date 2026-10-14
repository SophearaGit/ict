@php
    // Computed here (not passed in) so this partial is self-contained and
    // always agrees with the invoice's own stored total, whether it's
    // rendered on first page load or swapped in fresh by setPlan()/
    // switchSchedule()'s AJAX response.
    $halfInstallment = $invoice->total_amount / 2;
@endphp
<div class="order-row">
    <span>Subtotal</span>
    <span>${{ number_format($invoice->price, 2) }}</span>
</div>
@if ($invoice->discount > 0)
    <div class="order-row order-row--discount">
        <span>Pay in Full discount</span>
        <span>-${{ number_format($invoice->discount, 2) }}</span>
    </div>
@endif
@if ($invoice->extra_charge > 0)
    <div class="order-row order-row--surcharge">
        <span>Pay Half surcharge</span>
        <span>+${{ number_format($invoice->extra_charge, 2) }}</span>
    </div>
@endif

{{-- Pay Half gets its own layout below instead of reusing the plain
     total pill for the 2-payment total: showing the full total as the
     single biggest number on the card made students think that was what
     they owed right now. "Total plan cost" is a small, informational
     line; the big highlighted card below is always "Due Today" —
     whichever installment is actually being charged. --}}
@if ($isHalfPlan && $invoice->payment_status !== 'paid')
    <div class="order-row order-row--plan-total">
        <span>Total plan cost <span class="order-row-note">(2 payments)</span></span>
        <span>${{ number_format($invoice->total_amount, 2) }}</span>
    </div>

    @if ($invoice->payment_status === 'half_paid')
        <div class="order-row order-row--installment order-row--paid">
            <span><i class="fa-solid fa-circle-check" aria-hidden="true"></i> Payment 1 of 2 — paid</span>
            <span>${{ number_format($invoice->paid_amount, 2) }}</span>
        </div>
        <div class="price-highlight">
            <span class="price-highlight-label">
                <i class="fa-solid fa-wallet" aria-hidden="true"></i>
                Due Today <span class="price-highlight-note">(2 of 2 — final payment)</span>
            </span>
            <span class="price-highlight-amount" id="orderTotal">${{ number_format($invoice->remaining_amount, 2) }}</span>
        </div>
    @else
        <div class="price-highlight">
            <span class="price-highlight-label">
                <i class="fa-solid fa-wallet" aria-hidden="true"></i>
                Due Today <span class="price-highlight-note">(1 of 2)</span>
            </span>
            <span class="price-highlight-amount" id="orderTotal">${{ number_format($halfInstallment, 2) }}</span>
        </div>
        <div class="order-row order-row--installment order-row--upcoming">
            <span><i class="fa-regular fa-clock" aria-hidden="true"></i> Then, payment 2 of 2</span>
            <span>${{ number_format($invoice->total_amount - $halfInstallment, 2) }}</span>
        </div>
    @endif
@else
    <div class="price-highlight {{ $invoice->payment_status === 'paid' ? 'price-highlight--paid' : '' }}">
        @if ($invoice->payment_status === 'paid')
            <span class="price-highlight-label">
                <i class="fa-solid fa-circle-check" aria-hidden="true"></i>
                Total Paid
            </span>
            <span class="price-highlight-amount" id="orderTotal">${{ number_format($invoice->paid_amount, 2) }}</span>
        @else
            <span class="price-highlight-label">
                <i class="fa-solid fa-wallet" aria-hidden="true"></i>
                Total
            </span>
            <span class="price-highlight-amount" id="orderTotal">${{ number_format($invoice->total_amount, 2) }}</span>
        @endif
    </div>
@endif
