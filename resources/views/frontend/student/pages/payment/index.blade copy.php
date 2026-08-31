@extends('frontend.layouts.new.master')
@section('page_title', 'Checkout — ' . $invoice->course->title)
@push('styles')
<style>
    .pw-wrap { max-width: 640px; margin: 48px auto; padding: 0 16px; }
    .pw-card {
        background: #fff; border: 1px solid #E4E7EC; border-radius: 16px;
        padding: 28px; box-shadow: 0 8px 24px rgba(16,24,40,.06);
    }
    .pw-course { display: flex; gap: 14px; align-items: center; padding-bottom: 20px; border-bottom: 1px solid #E4E7EC; margin-bottom: 20px; }
    .pw-course img { width: 72px; height: 72px; border-radius: 10px; object-fit: cover; }
    .pw-course h3 { font-size: 16px; font-weight: 700; color: #101828; margin: 0 0 4px; }
    .pw-course p { font-size: 13px; color: #667085; margin: 0; }
    .pw-row { display: flex; justify-content: space-between; font-size: 14px; color: #344054; padding: 6px 0; }
    .pw-row.total { font-size: 18px; font-weight: 700; color: #101828; border-top: 1px solid #E4E7EC; margin-top: 10px; padding-top: 14px; }
    .pw-hint { font-size: 13px; color: #667085; margin: 20px 0 14px; text-align: center; }
    .pw-btn {
        width: 100%; padding: 14px; border: none; border-radius: 12px;
        background: #0057FF; color: #fff; font-size: 15px; font-weight: 700;
        cursor: pointer; margin-top: 6px;
    }
    .pw-btn:disabled { background: #98A2B3; cursor: not-allowed; }
    .pw-status {
        display: none; text-align: center; margin-top: 16px; font-size: 13px; color: #667085;
    }
    .pw-status.visible { display: block; }

    .pw-schedule {
        display: flex; align-items: center; justify-content: space-between;
        gap: 12px; padding: 14px 16px; border: 1px solid #E4E7EC; border-radius: 12px;
        margin-bottom: 20px; background: #F9FAFB;
    }
    .pw-schedule-info { font-size: 13px; color: #344054; }
    .pw-schedule-info strong { display: block; font-size: 14px; color: #101828; margin-bottom: 2px; }
    .pw-schedule-change {
        border: 1px solid #D0D5DD; background: #fff; color: #344054;
        font-size: 13px; font-weight: 600; padding: 8px 14px; border-radius: 8px; cursor: pointer;
        white-space: nowrap;
    }
    .pw-schedule-change:hover { background: #F9FAFB; }

    .sched-backdrop {
        display: none; position: fixed; inset: 0; background: rgba(16,24,40,.5);
        z-index: 1050; align-items: center; justify-content: center;
    }
    .sched-backdrop.open { display: flex; }
    .sched-modal {
        background: #fff; border-radius: 18px; width: 100%; max-width: 480px;
        margin: 16px; box-shadow: 0 24px 64px rgba(0,0,0,.18); overflow: hidden;
    }
    .sched-modal-head { padding: 22px 22px 0; display: flex; align-items: flex-start; justify-content: space-between; gap: 12px; }
    .sched-modal-head h2 { font-size: 17px; font-weight: 700; color: #101828; margin: 0; }
    .sched-modal-head p { font-size: 13px; color: #667085; margin: 4px 0 0; }
    .sched-close { background: none; border: none; cursor: pointer; color: #667085; padding: 2px; }
    .sched-modal-body { padding: 18px 22px; }
    .sched-list { display: flex; flex-direction: column; gap: 10px; max-height: 320px; overflow-y: auto; }
    .sched-option {
        display: flex; align-items: flex-start; gap: 12px; padding: 13px 15px;
        border: 1.5px solid #E4E7EC; border-radius: 12px; cursor: pointer;
    }
    .sched-option:has(input:checked) { border-color: #0057FF; background: #EEF3FF; }
    .sched-option:has(input:disabled) { opacity: .5; cursor: not-allowed; }
    .sched-option input { margin-top: 3px; accent-color: #0057FF; }
    .sched-option-title { font-size: 14px; font-weight: 600; color: #101828; }
    .sched-option-meta { font-size: 12px; color: #667085; margin-top: 4px; }
    .sched-badge { font-size: 11px; font-weight: 600; padding: 3px 10px; border-radius: 20px; flex-shrink: 0; }
    .sched-badge.open { background: #ECFDF3; color: #027A48; }
    .sched-badge.full { background: #FEF3F2; color: #B42318; }
    .sched-badge.current { background: #EEF3FF; color: #0057FF; }
    .sched-empty { text-align: center; padding: 24px 16px; color: #667085; font-size: 13px; }
    .sched-modal-foot { padding: 0 22px 22px; display: flex; gap: 10px; justify-content: flex-end; }
    .btn-sched-cancel { padding: 9px 18px; border: 1px solid #E4E7EC; border-radius: 8px; background: #fff; font-size: 13px; font-weight: 500; cursor: pointer; color: #344054; }
    .btn-sched-confirm { padding: 9px 24px; background: #0057FF; color: #fff; border: none; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer; }
    .btn-sched-confirm:disabled { opacity: .4; cursor: not-allowed; }
</style>
@endpush
@section('content')
<div class="pw-wrap">
    <div class="pw-card">
        <div class="pw-course">
            <img src="{{ $invoice->course->thumbnail ? asset($invoice->course->thumbnail) : 'frontend/asset/images/Course-Language/default.jpg' }}"
                 alt="{{ $invoice->course->title }}">
            <div>
                <h3>{{ $invoice->course->title }}</h3>
                <p>Invoice {{ $invoice->invoice_code }}</p>
            </div>
        </div>

        <div class="pw-schedule">
            <div class="pw-schedule-info">
                <strong>Schedule</strong>
                @if ($invoice->course->schedule)
                    {{ $invoice->course->schedule->short_days }} · {{ $invoice->course->schedule->formatted_time }}
                    @if ($invoice->course->schedule->shift_label)
                        ({{ $invoice->course->schedule->shift_label }})
                    @endif
                @else
                    Not scheduled yet
                @endif
            </div>
            <button type="button" class="pw-schedule-change" onclick="openScheduleModal()">Change</button>
        </div>

        <div class="pw-row">
            <span>Course price</span>
            <span>${{ number_format($invoice->price, 2) }}</span>
        </div>
        @if ($invoice->discount > 0)
            <div class="pw-row">
                <span>Discount</span>
                <span>-${{ number_format($invoice->discount, 2) }}</span>
            </div>
        @endif
        @if ($invoice->extra_charge > 0)
            <div class="pw-row">
                <span>Extra charge</span>
                <span>${{ number_format($invoice->extra_charge, 2) }}</span>
            </div>
        @endif
        <div class="pw-row total">
            <span>Total due</span>
            <span>${{ number_format($invoice->remaining_amount, 2) }}</span>
        </div>

        <p class="pw-hint">You'll choose KHQR or card on the next screen — PayWay handles that.</p>

        <button id="pw-checkout-btn" class="pw-btn">Pay ${{ number_format($invoice->remaining_amount, 2) }}</button>

        <div id="pw-status" class="pw-status">Waiting for payment confirmation…</div>
    </div>
</div>

{{-- Hidden form posted to PayWay's purchase endpoint by the checkout widget.
     IMPORTANT: action must point at PayWay, not this page — the JS SDK opens
     a popup/modal named "aba_webservice" and renders PayWay's hosted
     checkout HTML inside it via this form's target.
     IMPORTANT: nothing below may edit these field values after page load —
     every value here was baked into the server-side hash. Changing even one
     character (including payment_option) invalidates the hash and PayWay
     rejects the request with "Wrong Hash". Payment method selection (KHQR
     vs card) happens on PayWay's own hosted page, not here. --}}
<form id="aba_merchant_request" method="POST" target="aba_webservice"
      action="{{ config('services.payway.api_url') }}/api/payment-gateway/v1/payments/purchase"
      style="display:none">
    @foreach ($paywayFields as $key => $value)
        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
    @endforeach
</form>

<script src="{{ $paywayCheckoutJsUrl }}"></script>

{{-- Change-schedule modal — lets the student switch to a sibling batch of
     this course (same title, different schedule_id) before paying. --}}
<div class="sched-backdrop" id="sched-backdrop">
    <div class="sched-modal" role="dialog" aria-modal="true" aria-labelledby="sched-title">
        <div class="sched-modal-head">
            <div>
                <h2 id="sched-title">Choose a Schedule</h2>
                <p>Switching updates your invoice — no payment has been made yet.</p>
            </div>
            <button class="sched-close" onclick="closeScheduleModal()" aria-label="Close">✕</button>
        </div>
        <div class="sched-modal-body">
            <div id="sched-list-wrap" class="sched-list">
                <div class="sched-empty">Loading schedules…</div>
            </div>
        </div>
        <div class="sched-modal-foot">
            <button class="btn-sched-cancel" onclick="closeScheduleModal()">Cancel</button>
            <button class="btn-sched-confirm" id="btn-sched-confirm" disabled onclick="confirmScheduleSwitch()">
                Switch Schedule
            </button>
        </div>
    </div>
</div>

<form id="switch-schedule-form" action="{{ route('student.payment.switch-schedule', $invoice->id) }}" method="POST" style="display:none">
    @csrf
    <input type="hidden" name="course_id" id="switch-schedule-course-id">
</form>

<script>
(function () {
    var CURRENT_COURSE_ID = @json($invoice->course_id);
    var SCHEDULES_URL = @json(route('student.course.schedules', $invoice->course_id));
    var COURSE_TITLE = @json($invoice->course->title);
    var selectedCourseId = null;

    function openScheduleModal() {
        selectedCourseId = null;
        document.getElementById('btn-sched-confirm').disabled = true;
        document.getElementById('sched-backdrop').classList.add('open');
        loadSections();
    }

    function closeScheduleModal() {
        document.getElementById('sched-backdrop').classList.remove('open');
    }

    function loadSections() {
        var wrap = document.getElementById('sched-list-wrap');
        wrap.innerHTML = '<div class="sched-empty">Loading schedules…</div>';

        fetch(SCHEDULES_URL + '?title=' + encodeURIComponent(COURSE_TITLE), {
            headers: { 'Accept': 'application/json' }
        })
            .then(function (res) { return res.json(); })
            .then(function (res) {
                var sections = res.sections || [];
                if (!sections.length) {
                    wrap.innerHTML = '<div class="sched-empty">No other schedules available for this course right now.</div>';
                    return;
                }
                wrap.innerHTML = sections.map(function (s) {
                    var isCurrent = s.id === CURRENT_COURSE_ID;
                    var badgeClass = isCurrent ? 'current' : (s.is_full ? 'full' : 'open');
                    var badgeLabel = isCurrent ? 'Current' : (s.is_full ? 'Full' : 'Open');
                    var meta = [s.time];
                    if (s.instructor) meta.push(s.instructor);
                    if (s.start_date) meta.push('Starts ' + s.start_date);
                    return '' +
                        '<label class="sched-option">' +
                            '<input type="radio" name="section" value="' + s.id + '"' +
                                (isCurrent ? ' checked' : '') +
                                (s.is_full && !isCurrent ? ' disabled' : '') + '>' +
                            '<div>' +
                                '<div class="sched-option-title">' + s.days + '</div>' +
                                '<div class="sched-option-meta">' + meta.join(' · ') + '</div>' +
                            '</div>' +
                            '<span class="sched-badge ' + badgeClass + '" style="margin-left:auto">' + badgeLabel + '</span>' +
                        '</label>';
                }).join('');

                if (sections.some(function (s) { return s.id === CURRENT_COURSE_ID; })) {
                    selectedCourseId = CURRENT_COURSE_ID;
                }
                document.getElementById('btn-sched-confirm').disabled = !selectedCourseId;

                wrap.querySelectorAll('input[type=radio]').forEach(function (radio) {
                    radio.addEventListener('change', function () {
                        selectedCourseId = parseInt(this.value, 10);
                        document.getElementById('btn-sched-confirm').disabled = false;
                    });
                });
            })
            .catch(function () {
                wrap.innerHTML = '<div class="sched-empty">Could not load schedules. Please try again.</div>';
            });
    }

    function confirmScheduleSwitch() {
        if (!selectedCourseId || selectedCourseId === CURRENT_COURSE_ID) {
            closeScheduleModal();
            return;
        }
        document.getElementById('switch-schedule-course-id').value = selectedCourseId;
        document.getElementById('switch-schedule-form').submit();
    }

    document.getElementById('sched-backdrop').addEventListener('click', function (e) {
        if (e.target === this) closeScheduleModal();
    });

    window.openScheduleModal = openScheduleModal;
    window.closeScheduleModal = closeScheduleModal;
    window.confirmScheduleSwitch = confirmScheduleSwitch;
})();
</script>

<script>
(function () {
    var checkoutBtn = document.getElementById('pw-checkout-btn');
    var statusBox = document.getElementById('pw-status');
    var statusUrl = @json(route('student.payment.status', $invoice->id));
    var successUrl = @json(route('student.my.course.detail', $invoice->course_id));
    var pollTimer = null;
    var pollAttempts = 0;
    var MAX_ATTEMPTS = 20; // ~2 minutes at 6s interval

    function pollStatus() {
        pollAttempts++;
        fetch(statusUrl, { headers: { 'Accept': 'application/json' } })
            .then(function (res) { return res.json(); })
            .then(function (data) {
                if (data.status === 'paid') {
                    clearInterval(pollTimer);
                    statusBox.textContent = 'Payment confirmed! Redirecting…';
                    window.location.href = successUrl;
                    return;
                }
                if (pollAttempts >= MAX_ATTEMPTS) {
                    clearInterval(pollTimer);
                    statusBox.textContent = 'Still waiting on confirmation — refresh this page once you\'ve completed payment.';
                }
            })
            .catch(function () {
                // transient network hiccup — keep polling silently
            });
    }

    checkoutBtn.addEventListener('click', function () {
        checkoutBtn.disabled = true;
        checkoutBtn.textContent = 'Opening secure checkout…';

        // AbaPayway.checkout() opens PayWay's hosted popup/bottom-sheet
        // using the hidden #aba_merchant_request form above, untouched
        // since it was rendered.
        AbaPayway.checkout();

        statusBox.classList.add('visible');
        pollTimer = setInterval(pollStatus, 6000);

        checkoutBtn.textContent = 'Waiting for payment…';
    });
})();
</script>
@endsection
