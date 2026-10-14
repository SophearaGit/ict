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
     order-row--total pill for the 2-payment total: showing the full
     $240 as the single biggest number on the card made students think
     that was what they owed right now. "Total plan cost" is now a
     small, informational line, and the big highlighted pill is always
     "Due Today" — whichever installment is actually being charged. --}}
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
        <div class="order-row order-row--due-today">
            <span>Due Today <span class="order-row-note">(2 of 2 — final payment)</span></span>
            <span id="orderTotal">${{ number_format($invoice->remaining_amount, 2) }}</span>
        </div>
    @else
        <div class="order-row order-row--due-today">
            <span>Due Today <span class="order-row-note">(1 of 2)</span></span>
            <span id="orderTotal">${{ number_format($halfInstallment, 2) }}</span>
        </div>
        <div class="order-row order-row--installment order-row--upcoming">
            <span><i class="fa-regular fa-clock" aria-hidden="true"></i> Then, payment 2 of 2</span>
            <span>${{ number_format($invoice->total_amount - $halfInstallment, 2) }}</span>
        </div>
    @endif
@else
    <div class="order-row order-row--total">
        @if ($invoice->payment_status === 'paid')
            <span>Total Paid</span>
            <span id="orderTotal">${{ number_format($invoice->paid_amount, 2) }}</span>
        @else
            <span>Total</span>
            <span id="orderTotal">${{ number_format($invoice->total_amount, 2) }}</span>
        @endif
    </div>
@endif
