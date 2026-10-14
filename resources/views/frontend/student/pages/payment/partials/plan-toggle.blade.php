<div class="plan-toggle {{ $planLocked ? 'is-locked' : '' }}" id="planToggle" data-plan="{{ $isHalfPlan ? 'half' : 'full' }}"
    role="group" aria-label="Payment plan">
    <span class="plan-toggle-thumb" aria-hidden="true"></span>
    <button type="button" class="plan-toggle-option {{ !$isHalfPlan ? 'is-active' : '' }}" id="planFullOption"
        data-plan="full" {{ $planLocked ? 'disabled' : '' }}>
        <span class="plan-toggle-name">Pay in Full</span>
        <span class="plan-toggle-sub">Save $10</span>
    </button>
    <button type="button" class="plan-toggle-option {{ $isHalfPlan ? 'is-active' : '' }}" id="planHalfOption"
        data-plan="half" {{ $planLocked ? 'disabled' : '' }}>
        <span class="plan-toggle-name">Pay Half Now</span>
        <span class="plan-toggle-sub">2 × ${{ number_format(($invoice->price + 20) / 2, 2) }}</span>
    </button>
</div>
@if ($planLocked)
    <p class="plan-locked-note">
        <i class="fa-solid fa-lock" aria-hidden="true"></i> Your plan is locked in after the first payment.
    </p>
@endif
