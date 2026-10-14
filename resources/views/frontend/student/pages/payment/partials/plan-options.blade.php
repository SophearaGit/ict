<div class="plan-options">
    <button type="button"
        class="payment-option plan-option {{ !$isHalfPlan ? 'is-selected' : '' }}"
        id="planFullOption" data-plan="full" {{ $planLocked ? 'disabled' : '' }}>
        <span class="payment-option-text">
            <span class="payment-option-name">Pay in Full</span>
            <span class="payment-option-sub">One payment · save $10</span>
        </span>
        @if (!$isHalfPlan)
            <i class="fa-solid fa-circle-check plan-option-check" aria-hidden="true"></i>
        @endif
    </button>
    <button type="button"
        class="payment-option plan-option {{ $isHalfPlan ? 'is-selected' : '' }}"
        id="planHalfOption" data-plan="half" {{ $planLocked ? 'disabled' : '' }}>
        <span class="payment-option-text">
            <span class="payment-option-name">Pay Half Now</span>
            <span class="payment-option-sub">2 payments of
                ${{ number_format(($invoice->price + 20) / 2, 2) }} · +$20 total</span>
        </span>
        @if ($isHalfPlan)
            <i class="fa-solid fa-circle-check plan-option-check" aria-hidden="true"></i>
        @endif
    </button>
</div>
@if ($planLocked)
    <p class="plan-locked-note">
        <i class="fa-solid fa-lock" aria-hidden="true"></i> Your plan is locked in after the
        first payment.
    </p>
@endif
