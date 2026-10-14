@extends('frontend.layouts.new.master')
@section('page_title', 'Checkout — ' . $invoice->course->title)
@push('styles')
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <link rel="stylesheet" href="/frontend/asset/css/checkout.css?v={{ @filemtime(public_path('frontend/asset/css/checkout.css')) }}">
@endpush
@section('content')

    @php
        // Shared display math — kept in one place so the plan toggle and
        // price breakdown agree on which plan is active. Mirrors
        // CourseEnrollmentController's own server-side computation (see
        // paymentDisplayFields()/buildPaymentContext()).
        $isHalfPlan = $invoice->payment_option === 'half';
        $isSecondInstallmentPending = $isHalfPlan && $invoice->payment_status === 'half_paid';
        $planLocked = $invoice->payment_status !== 'unpaid';
        $scheduleLabel = $invoice->course->schedule
            ? $invoice->course->schedule->short_days . ' · ' . $invoice->course->schedule->formatted_time
            : 'Current schedule';
    @endphp

    <main class="checkout-wrap">

        <!-- Breadcrumb -->
        <nav class="checkout-crumb" aria-label="Breadcrumb" data-aos="fade-up">
            <a href="{{ route('course') }}">Courses</a>
            <i class="fa-solid fa-chevron-right"></i>
            <a href="{{ route('course.details', $invoice->course->slug) }}">{{ $invoice->course->title }}</a>
            <i class="fa-solid fa-chevron-right"></i>
            <span aria-current="page">Checkout</span>
        </nav>

        <!-- Stepper: Select Schedule already happened (see the dedicated
             step before this page — CourseEnrollmentController@selectSchedule)
             so it renders here as done; this page itself is "Review & Pay". -->
        <ol class="checkout-stepper checkout-stepper--three" data-aos="fade-up">
            <li class="step is-done">
                <span class="step-dot"><i class="fa-solid fa-check"></i></span>
                <span class="step-label">Select Schedule</span>
            </li>
            <li class="step-line is-done"></li>
            <li class="step is-active" id="step-payment">
                <span class="step-dot">2</span>
                <span class="step-label">Review &amp; Pay</span>
            </li>
            <li class="step-line" id="step-line-success"></li>
            <li class="step" id="step-success">
                <span class="step-dot">3</span>
                <span class="step-label">Confirmed</span>
            </li>
        </ol>

        <!-- Main grid -->
        <div class="checkout-grid">

            <!-- LEFT: course summary -->
            <section class="co-card course-summary-card" data-aos="fade-up">
                <div class="course-summary-top">
                    <div class="course-thumb">
                        <img src="{{ $invoice->course->thumbnail ? asset($invoice->course->thumbnail) : asset('default-images/course-default.jpg') }}"
                            alt="{{ $invoice->course->title }} thumbnail">
                    </div>
                    <div class="course-summary-info">
                        <span class="course-badge">{{ $invoice->course->category->name ?? 'Course' }}</span>
                        <h2 class="course-title">{{ $invoice->course->title }}</h2>
                        <div class="course-teacher">
                            <i class="fa-solid fa-chalkboard-user"></i>
                            <span>{{ $invoice->course->instructor->name ?? 'Instructor TBA' }}</span>
                        </div>
                    </div>
                </div>

                <div class="course-detail-grid">
                    <div class="detail-item">
                        <span class="detail-icon"><i class="fa-regular fa-clock"></i></span>
                        <div class="detail-text">
                            <span class="detail-label">Duration</span>
                            <span class="detail-value">{{ $invoice->course->duration ?? 48 }} hours</span>
                        </div>
                    </div>

                    {{-- Schedule: shown as a plain fact with a "Change" link
                         rather than a dropdown sitting in the main flow — a
                         student only needs to touch this if they actually
                         want a different batch. Clicking "Change" reveals an
                         inline picker; applying it re-prices the invoice via
                         AJAX (see switchSchedule()) instead of a full page
                         reload. Locked once any payment has landed, same
                         rule as the plan toggle. --}}
                    <div class="detail-item schedule-field" id="scheduleField">
                        <span class="detail-icon"><i class="fa-regular fa-calendar"></i></span>
                        <div class="detail-text">
                            <span class="detail-label">Schedule</span>

                            <div class="schedule-display" id="scheduleDisplay">
                                <span class="schedule-display-value" id="scheduleDisplayValue">{{ $scheduleLabel }}</span>
                                @if (!$planLocked)
                                    <button type="button" class="schedule-change-link" id="scheduleChangeBtn">
                                        Change
                                    </button>
                                @endif
                            </div>

                            @if (!$planLocked)
                                <div class="schedule-picker" id="schedulePicker" style="display:none">
                                    <select id="scheduleSelect" class="detail-select">
                                        <option value="{{ $invoice->course_id }}" selected
                                            data-start="{{ $invoice->course->start_date?->format('F j, Y') ?? 'Depends on schedule' }}"
                                            data-label="{{ $scheduleLabel }}">
                                            {{ $scheduleLabel }}
                                        </option>
                                    </select>
                                    <div class="schedule-picker-actions">
                                        <button type="button" class="schedule-picker-btn schedule-picker-btn--apply"
                                            id="scheduleApplyBtn">Apply</button>
                                        <button type="button" class="schedule-picker-btn schedule-picker-btn--cancel"
                                            id="scheduleCancelBtn">Cancel</button>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="detail-item">
                        <span class="detail-icon"><i class="fa-regular fa-note-sticky"></i></span>
                        <div class="detail-text">
                            <span class="detail-label">Certificate</span>
                            <span class="detail-value">Included on completion</span>
                        </div>
                    </div>

                    <div class="detail-item">
                        <span class="detail-icon"><i class="fa-regular fa-calendar-check"></i></span>
                        <div class="detail-text">
                            <span class="detail-label">Start Date</span>
                            <span class="detail-value" id="startDateValue">
                                {{ $invoice->course->start_date?->format('F j, Y') ?? 'Depends on schedule' }}
                            </span>
                        </div>
                    </div>
                </div>
            </section>

            <!-- RIGHT: one state-morphing payment card instead of several
                 stacked boxes — it shows exactly one of: a loading check,
                 the pay flow (plan + price + CTA), the Pay Half milestone,
                 or the final success receipt. -->
            <aside class="checkout-aside" data-aos="fade-up">
                <div class="co-card checkout-payment-card" id="paymentCard">

                    {{-- Loading state: shown while we check whether this
                         invoice is already settled (e.g. right after
                         PayWay's redirect back). --}}
                    <div id="loadingState" class="pw-loading-inner">
                        <span class="pw-spinner" aria-hidden="true"></span>
                        <p>Checking your payment status…</p>
                    </div>

                    {{-- Pay state: plan toggle + price breakdown + one CTA. --}}
                    <div id="payState" style="display:none">
                        <div class="payment-card-head">
                            <h3 class="co-card-title payment-card-title">
                                Payment
                                <span id="installmentBadgeWrap">
                                    @if ($installmentLabel)
                                        <span class="installment-badge" id="installmentBadge">{{ $installmentLabel }}</span>
                                    @endif
                                </span>
                            </h3>
                        </div>

                        @if ($invoice->payment_status !== 'paid')
                            <div id="planToggleWrap">
                                @include('frontend.student.pages.payment.partials.plan-toggle', [
                                    'invoice' => $invoice,
                                    'isHalfPlan' => $isHalfPlan,
                                    'planLocked' => $planLocked,
                                ])
                            </div>
                        @endif

                        <div class="price-breakdown" id="priceBreakdownWrap">
                            @include('frontend.student.pages.payment.partials.price-breakdown', [
                                'invoice' => $invoice,
                                'isHalfPlan' => $isHalfPlan,
                            ])
                        </div>

                        {{-- A plain rule instead of just whitespace — makes it obvious
                             at a glance that everything below belongs to a new group
                             ("how" you're paying) rather than being more of the price
                             breakdown above it ("what" you're paying). --}}
                        <hr class="pay-cta-divider">

                        <div class="pay-cta-section" id="payCtaSection">
                            <div class="pay-cta-head">
                                <span class="pay-cta-label">Payment Option</span>
                                <span class="pw-countdown" id="pw-countdown" style="display:none">
                                    <i class="fa-regular fa-clock" aria-hidden="true"></i>
                                    <span id="pw-countdown-text"></span>
                                </span>
                            </div>

                            {{-- IMPORTANT: nothing here may edit these hidden
                                 field values after page load — every value
                                 was baked into the server-side hash; changing
                                 even one character invalidates it. This
                                 mirrors PayWay's own checkout2-0.js
                                 integration sample exactly: the widget binds
                                 to this form's `submit` event (see the
                                 script below), not to a button's click, so
                                 Pay is this form's real submit control
                                 instead of a separate element that calls
                                 AbaPayway.checkout() on its own. Falls back
                                 gracefully too — if the widget script ever
                                 fails to load, this is a real form that
                                 still posts straight to PayWay's hosted
                                 checkout instead of a dead button. --}}
                            <form id="aba_merchant_request" method="POST" target="aba_webservice"
                                action="{{ config('services.payway.api_url') }}/api/payment-gateway/v1/payments/purchase">
                                <div id="paywayHiddenFields">
                                    @include('frontend.student.pages.payment.partials.payway-form-fields', ['paywayFields' => $paywayFields])
                                </div>

                                {{-- Styled as ABA's own "payment method" card (logo
                                     badge + title + subtitle + chevron) instead of a
                                     generic branded button — this is the pattern
                                     students already recognize from the ABA app and
                                     PayWay's own checkout, so it reads as trustworthy
                                     at a glance. #payButtonText/#payButtonIcon keep
                                     their ids: all the JS below (waiting state, QR
                                     expiry, opening checkout) still targets them the
                                     same way — only the surrounding markup changed. --}}
                                <button type="submit" class="btn-aba-khqr" id="payButton">
                                    <span class="aba-khqr-btn-icon">
                                        <img src="{{ asset('/frontend/asset/images/aba-khqr.jpg') }}" alt="ABA KHQR">
                                    </span>
                                    <span class="aba-khqr-btn-copy">
                                        <span class="aba-khqr-btn-title">ABA KHQR</span>
                                        <span class="aba-khqr-btn-sub" id="payButtonText">{{ $payButtonText }}</span>
                                    </span>
                                    <i class="fa-solid fa-chevron-right" id="payButtonIcon" aria-hidden="true"></i>
                                </button>
                            </form>

                            <p class="secure-note" id="pw-status" style="display:none"></p>

                            <p class="payment-trust-row">
                                <i class="fa-solid fa-shield-halved" aria-hidden="true"></i>
                                Your payment details are encrypted
                            </p>
                        </div>

                        {{-- Shown while setPlan()/switchSchedule() are in
                             flight — dims the plan/price/CTA area and
                             blocks clicks on it so a student can't fire a
                             second request (or pay against a stale amount)
                             while the first one is still updating. --}}
                        <div class="pay-state-busy-overlay" id="payStateBusyOverlay">
                            <span class="pay-state-busy-pill">
                                <span class="pw-spinner pw-spinner--sm" aria-hidden="true"></span>
                                <span id="payStateBusyLabel">Updating…</span>
                            </span>
                        </div>
                    </div>

                    {{-- Step 2 success state. --}}
                    <div id="successState" class="pw-success-card" style="display:none">
                        <div class="pw-success-badge" aria-hidden="true">
                            <svg viewBox="0 0 52 52">
                                <circle class="pw-success-badge__circle" cx="26" cy="26" r="24" fill="none" />
                                <path class="pw-success-badge__check" fill="none" d="M14 27l7 7 17-17" />
                            </svg>
                        </div>

                        <h3 class="pw-success-title">Payment Successful</h3>
                        <p class="pw-success-sub">Your enrollment is confirmed — see you in class!</p>

                        <div class="pw-success-details">
                            <div class="order-row"><span>Course</span><span id="rc-course"></span></div>
                            <div class="order-row"><span>Schedule</span><span id="rc-schedule"></span></div>
                            <div class="order-row"><span>Invoice</span><span id="rc-invoice"></span></div>
                            <div class="order-row"><span>Payment Option</span><span id="rc-option"></span></div>
                            <div class="order-row"><span>Transaction ID</span><span id="rc-tranid"></span></div>
                            <div class="order-row"><span>Paid At</span><span id="rc-paidat"></span></div>
                            <div class="order-row order-row--total"><span>Amount Paid</span><span id="rc-amount"></span></div>
                        </div>

                        <div class="pw-success-actions">
                            <button type="button" class="btn-checkout-primary" id="downloadReceiptBtn">
                                <i class="fa-solid fa-file-arrow-down"></i> Download Receipt
                            </button>
                            <a href="{{ route('student.dashboard') }}" class="btn-checkout-primary btn-checkout-secondary"
                                id="goToDashboardLink">
                                <i class="fa-solid fa-gauge"></i> Go to Dashboard
                            </a>
                        </div>
                    </div>

                    {{-- Pay Half milestone state — shown the moment the 1st
                         installment clears. Deliberately NOT the success
                         state above: the invoice isn't actually done, so
                         this reads as "milestone reached, one step left"
                         (amber accent, clear "remaining" amount, a button
                         that takes them straight back to pay it) rather than
                         a final receipt. --}}
                    <div id="milestoneState" class="pw-success-card pw-success-card--partial" style="display:none">
                        <div class="pw-success-badge" aria-hidden="true">
                            <svg viewBox="0 0 52 52">
                                <circle class="pw-success-badge__circle" cx="26" cy="26" r="24" fill="none" />
                                <path class="pw-success-badge__check" fill="none" d="M14 27l7 7 17-17" />
                            </svg>
                        </div>

                        <h3 class="pw-success-title">Installment 1 of 2 Paid!</h3>
                        <p class="pw-success-sub">
                            You're enrolled and already have access to the course.
                            Pay the remaining half to complete this invoice.
                        </p>

                        <div class="pw-success-details">
                            <div class="order-row"><span>Course</span><span id="ip-course"></span></div>
                            <div class="order-row"><span>Invoice</span><span id="ip-invoice"></span></div>
                            <div class="order-row pw-success-details__paid">
                                <span><i class="fa-solid fa-circle-check" aria-hidden="true"></i> Paid today (1 of
                                    2)</span>
                                <span id="ip-paid"></span>
                            </div>
                        </div>

                        {{-- Same gradient treatment as the Review & Pay card's
                             "Due Today" box — reusing it here (instead of the
                             old flat amber row) keeps "the number that
                             actually matters" looking the same everywhere it
                             shows up, paid-so-far or still-owed. --}}
                        <div class="price-highlight price-highlight--remaining">
                            <span class="price-highlight-label">
                                <i class="fa-solid fa-hourglass-half" aria-hidden="true"></i>
                                Remaining <span class="price-highlight-note">(2 of 2 — final payment)</span>
                            </span>
                            <span class="price-highlight-amount" id="ip-remaining"></span>
                        </div>

                        <div class="pw-success-actions">
                            <button type="button" class="btn-checkout-primary" id="payRemainingBtn">
                                <i class="fa-solid fa-arrow-right"></i> Pay Remaining Now
                            </button>
                            {{-- Installment 1 is a real, standalone payment — the
                                 receipt route already allows half_paid invoices
                                 (see PayWayPaymentController::downloadReceipt),
                                 this panel just never offered a way to reach it. --}}
                            <button type="button" class="btn-checkout-primary btn-checkout-secondary"
                                id="downloadReceiptBtnMilestone">
                                <i class="fa-solid fa-file-arrow-down"></i> Download Receipt
                            </button>
                            <a href="{{ route('student.dashboard') }}" class="btn-checkout-primary btn-checkout-secondary">
                                <i class="fa-solid fa-gauge"></i> Go to Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </main>

    <script>
        var $scheduleSelect = $('#scheduleSelect').select2({
            width: '100%',
            minimumResultsForSearch: Infinity,
            placeholder: 'Select your schedule',
            allowClear: false
        });
    </script>

    <script src="{{ $paywayCheckoutJsUrl }}"></script>
    <script>
        (function() {
            var CURRENT_COURSE_ID = @json($invoice->course_id);
            var SCHEDULES_URL = @json(route('student.course.schedules', $invoice->course_id));
            var COURSE_TITLE = @json($invoice->course->title);
            var CSRF_TOKEN = @json(csrf_token());
            var SET_PLAN_URL = @json(route('student.payment.set-plan', $invoice->id));
            var SWITCH_SCHEDULE_URL = @json(route('student.payment.switch-schedule', $invoice->id));

            var select = document.getElementById('scheduleSelect');
            var startDateValue = document.getElementById('startDateValue');

            /* ── Schedule: "Change" reveals an inline picker instead of a
                   dropdown sitting in the main flow. Applying it calls
                   switchSchedule() via fetch() and swaps in the returned
                   fragments — no full page reload. ── */
            var scheduleDisplay = document.getElementById('scheduleDisplay');
            var scheduleDisplayValue = document.getElementById('scheduleDisplayValue');
            var scheduleChangeBtn = document.getElementById('scheduleChangeBtn');
            var schedulePicker = document.getElementById('schedulePicker');
            var scheduleApplyBtn = document.getElementById('scheduleApplyBtn');
            var scheduleCancelBtn = document.getElementById('scheduleCancelBtn');
            var priceBreakdownWrap = document.getElementById('priceBreakdownWrap');
            var installmentBadgeWrap = document.getElementById('installmentBadgeWrap');
            var payButtonText = document.getElementById('payButtonText');
            // Only the hidden fields wrapper gets swapped on a plan/schedule
            // change — NOT the #aba_merchant_request form itself, since that
            // form also contains the visible Pay button now (see below).
            var abaFormWrap = document.getElementById('paywayHiddenFields');
            var scheduleRequestInFlight = false;
            var schedulesLoaded = false;

            /* ── Busy overlay: shown over the plan/price/CTA area whenever
                   setPlan() or switchSchedule() is in flight (see
                   #payStateBusyOverlay in the Blade above). Dims that
                   region and blocks clicks on it — on top of each
                   button's own disabled state — so a double-click or a
                   second action fired mid-request can't hit the server
                   twice or let the student pay against an amount that's
                   about to change. ── */
            var payStateEl = document.getElementById('payState');
            var payStateBusyOverlay = document.getElementById('payStateBusyOverlay');
            var payStateBusyLabel = document.getElementById('payStateBusyLabel');

            function setPayStateBusy(isBusy, label) {
                if (!payStateEl || !payStateBusyOverlay) return;
                payStateEl.classList.toggle('is-busy', isBusy);
                payStateBusyOverlay.classList.toggle('is-visible', isBusy);
                if (isBusy && payStateBusyLabel && label) {
                    payStateBusyLabel.textContent = label;
                }
            }

            function openSchedulePicker() {
                if (!schedulePicker) return;
                scheduleDisplay.style.display = 'none';
                schedulePicker.style.display = 'block';
                if (!schedulesLoaded) {
                    loadOtherSchedules();
                }
            }

            function closeSchedulePicker() {
                if (!schedulePicker) return;
                schedulePicker.style.display = 'none';
                scheduleDisplay.style.display = 'flex';
            }

            function applyStartDate() {
                // select is absent once the plan is locked (no schedule
                // picker is rendered at all in that case — see
                // scheduleField in the Blade above).
                if (!select) return;
                var opt = select.options[select.selectedIndex];
                if (opt && startDateValue) {
                    startDateValue.textContent = opt.dataset.start || 'Depends on schedule';
                }
            }

            function loadOtherSchedules() {
                var previousValue = select.value;

                fetch(SCHEDULES_URL + '?title=' + encodeURIComponent(COURSE_TITLE), {
                        headers: {
                            'Accept': 'application/json'
                        }
                    })
                    .then(function(res) {
                        return res.json();
                    })
                    .then(function(res) {
                        schedulesLoaded = true;
                        var sections = res.sections || [];
                        sections.forEach(function(s) {
                            if (s.id == CURRENT_COURSE_ID) return; // already rendered server-side (loose compare: AJAX ids may come back as strings)
                            var opt = document.createElement('option');
                            opt.value = s.id;
                            opt.dataset.start = s.start_date ? s.start_date : 'Depends on schedule';
                            var label = s.days + ' · ' + s.time;
                            if (s.is_full) label += ' (Full)';
                            opt.dataset.label = label;
                            opt.textContent = label;
                            if (s.is_full) opt.disabled = true;
                            select.appendChild(opt);
                        });
                        select.value = previousValue;
                        if (window.$ && $scheduleSelect) $scheduleSelect.trigger('change');
                    })
                    .catch(function() {
                        // Sibling schedules just won't be selectable — current one still works.
                    });
            }

            if (scheduleChangeBtn) {
                scheduleChangeBtn.addEventListener('click', openSchedulePicker);
            }

            if (scheduleCancelBtn) {
                scheduleCancelBtn.addEventListener('click', function() {
                    select.value = CURRENT_COURSE_ID;
                    if (window.$ && $scheduleSelect) $scheduleSelect.trigger('change');
                    closeSchedulePicker();
                });
            }

            if (select) {
                select.addEventListener('change', applyStartDate);
            }

            if (scheduleApplyBtn) {
                scheduleApplyBtn.addEventListener('click', function() {
                    if (scheduleRequestInFlight) return;

                    var selectedId = select.value;

                    if (!selectedId || selectedId == CURRENT_COURSE_ID) {
                        // Nothing actually changed — no need for a round trip.
                        closeSchedulePicker();
                        return;
                    }

                    scheduleRequestInFlight = true;
                    scheduleApplyBtn.disabled = true;
                    scheduleApplyBtn.textContent = 'Applying…';
                    setPayStateBusy(true, 'Updating schedule…');

                    fetch(SWITCH_SCHEDULE_URL, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': CSRF_TOKEN
                            },
                            body: JSON.stringify({
                                course_id: selectedId
                            })
                        })
                        .then(function(res) {
                            if (!res.ok) {
                                return res.json().then(function(err) {
                                    throw new Error(err.error || 'Could not switch schedule.');
                                });
                            }
                            return res.json();
                        })
                        .then(function(data) {
                            scheduleDisplayValue.textContent = data.scheduleLabel;
                            startDateValue.textContent = data.startDate;
                            CURRENT_COURSE_ID = selectedId;
                            if (priceBreakdownWrap) priceBreakdownWrap.innerHTML = data.priceBreakdownHtml;
                            if (installmentBadgeWrap) {
                                installmentBadgeWrap.innerHTML = data.installmentLabel ?
                                    '<span class="installment-badge" id="installmentBadge">' + data.installmentLabel +
                                    '</span>' : '';
                            }
                            if (payButtonText) payButtonText.textContent = data.payButtonText;
                            if (abaFormWrap) abaFormWrap.innerHTML = data.paywayFormHtml;
                            PAYWAY_EXPIRES_AT = data.paywayExpiresAt;
                            stopCountdown();
                            closeSchedulePicker();
                        })
                        .catch(function(err) {
                            alert(err.message || 'Could not switch schedule. Please try again.');
                        })
                        .finally(function() {
                            scheduleRequestInFlight = false;
                            scheduleApplyBtn.disabled = false;
                            scheduleApplyBtn.textContent = 'Apply';
                            setPayStateBusy(false);
                        });
                });
            }

            applyStartDate();

            /* ── Payment Plan: clicking Full/Half calls setPlan() via fetch
                   and swaps in the returned HTML fragments — no full page
                   reload. The listener is bound to the stable
                   #planToggleWrap container (event delegation) rather than
                   the .plan-toggle-option buttons themselves, because those
                   buttons get replaced wholesale by planToggleHtml on every
                   successful switch. Locked once a payment has landed, so
                   the buttons are server-rendered `disabled` in that case
                   and this never fires. ── */
            var planToggleWrap = document.getElementById('planToggleWrap');
            var planSwitchInFlight = false;

            if (planToggleWrap) {
                planToggleWrap.addEventListener('click', function(e) {
                    var btn = e.target.closest('.plan-toggle-option');
                    if (!btn || btn.disabled || btn.classList.contains('is-active') || planSwitchInFlight) {
                        return;
                    }

                    planSwitchInFlight = true;
                    planToggleWrap.querySelectorAll('.plan-toggle-option').forEach(function(b) {
                        b.disabled = true;
                    });
                    setPayStateBusy(true, 'Updating plan…');

                    fetch(SET_PLAN_URL, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': CSRF_TOKEN
                            },
                            body: JSON.stringify({
                                plan: btn.dataset.plan
                            })
                        })
                        .then(function(res) {
                            if (!res.ok) {
                                return res.json().then(function(err) {
                                    throw new Error(err.error || 'Could not update your payment plan.');
                                });
                            }
                            return res.json();
                        })
                        .then(function(data) {
                            // Re-render from the server's fragments so every
                            // panel (plan toggle, price breakdown, badge,
                            // CTA label, PayWay form) stays in sync with the
                            // invoice's new amounts — the same markup the
                            // initial page load used, just swapped in
                            // instead of re-requested.
                            planToggleWrap.innerHTML = data.planToggleHtml;
                            if (priceBreakdownWrap) priceBreakdownWrap.innerHTML = data.priceBreakdownHtml;
                            if (installmentBadgeWrap) {
                                installmentBadgeWrap.innerHTML = data.installmentLabel ?
                                    '<span class="installment-badge" id="installmentBadge">' + data.installmentLabel +
                                    '</span>' : '';
                            }
                            if (payButtonText) payButtonText.textContent = data.payButtonText;
                            if (abaFormWrap) abaFormWrap.innerHTML = data.paywayFormHtml;
                            PAYWAY_EXPIRES_AT = data.paywayExpiresAt;
                            // The old QR (if any) was minted for the old
                            // amount and is now stale — the countdown tied
                            // to it no longer applies. It'll restart fresh
                            // if/when the student actually opens the new QR.
                            stopCountdown();
                        })
                        .catch(function(err) {
                            // Most likely the plan got locked by a payment
                            // that landed in another tab between page load
                            // and this click — reload to show the real,
                            // current state rather than leaving the UI stale.
                            alert(err.message || 'Could not update your payment plan. Reloading…');
                            window.location.reload();
                        })
                        .finally(function() {
                            planSwitchInFlight = false;
                            setPayStateBusy(false);
                        });
                });
            }

            /* ── Stepper: purely visual — the page loads directly on
                   "Review & Pay" since there's nothing before it in this
                   flow. This just advances to "Confirmed" once the payment
                   is actually confirmed. ── */
            function advanceStep(target) {
                if (target === 'success') {
                    var stepPayment = document.getElementById('step-payment');
                    stepPayment.classList.replace('is-active', 'is-done');
                    stepPayment.querySelector('.step-dot').innerHTML = '<i class="fa-solid fa-check"></i>';
                    document.getElementById('step-line-success').classList.add('is-done');
                    document.getElementById('step-success').classList.add('is-active');
                }
            }

            /* ── Countdown: mirrors the deadline PayWay itself enforces on
                   this tran_id (see CourseEnrollmentController's `lifetime`
                   + payway_tran_started_at) so the student sees the same
                   clock PayWay is actually running, not one we invented.
                   Only starts once the QR is actually opened (the payButton
                   click, or on reload if a checkout was already in flight)
                   rather than the instant the payment card appears — but
                   PAYWAY_EXPIRES_AT itself is fixed at page-render time
                   (== the purchase payload's req_time), so if the student
                   sits on the page a while before clicking, the countdown
                   correctly opens already partway elapsed rather than
                   showing a fresh 5:00 that PayWay wouldn't honor. ── */
            var PAYWAY_EXPIRES_AT = @json($paywayExpiresAt);
            var countdownEl = document.getElementById('pw-countdown');
            var countdownTextEl = document.getElementById('pw-countdown-text');
            var countdownTimer = null;

            function stopCountdown() {
                if (countdownTimer) {
                    clearInterval(countdownTimer);
                    countdownTimer = null;
                }
                if (countdownEl) {
                    countdownEl.style.display = 'none';
                }
            }

            function startCountdown() {
                if (!PAYWAY_EXPIRES_AT || !countdownEl || countdownTimer) return;

                var expiresAtMs = new Date(PAYWAY_EXPIRES_AT).getTime();

                function tick() {
                    var remainingMs = expiresAtMs - Date.now();

                    if (remainingMs <= 0) {
                        clearInterval(countdownTimer);
                        countdownTimer = null;
                        countdownTextEl.textContent = 'QR code expired';
                        countdownEl.classList.add('pw-countdown--expired');
                        // The polling loop (or PayWay's own modal) is what
                        // actually surfaces "Try Again" — this badge just
                        // stops ticking rather than duplicating that UI.
                        return;
                    }

                    var totalSeconds = Math.ceil(remainingMs / 1000);
                    var mins = Math.floor(totalSeconds / 60);
                    var secs = totalSeconds % 60;
                    countdownTextEl.textContent = 'Expires in ' + mins + ':' + (secs < 10 ? '0' : '') + secs;
                    countdownEl.classList.toggle('pw-countdown--warning', remainingMs <= 30000);
                }

                countdownEl.style.display = 'inline-flex';
                countdownEl.classList.remove('pw-countdown--expired');
                tick();
                countdownTimer = setInterval(tick, 1000);
            }

            /* ── State switching: show exactly one of loading / pay /
                   success / milestone inside the single consolidated card. ── */
            var loadingState = document.getElementById('loadingState');
            var payState = document.getElementById('payState');
            var successState = document.getElementById('successState');
            var milestoneState = document.getElementById('milestoneState');

            function showState(target) {
                loadingState.style.display = target === 'loading' ? 'block' : 'none';
                payState.style.display = target === 'pay' ? 'block' : 'none';
                successState.style.display = target === 'success' ? 'block' : 'none';
                milestoneState.style.display = target === 'milestone' ? 'block' : 'none';
            }

            function showSuccessPanel(invoice) {
                // invoice can be null (see checkStatusOnLoad's .catch() —
                // Blade already says this invoice is 'paid', but the
                // enrichment fetch that would normally supply these
                // receipt details failed). Fall back to what Blade itself
                // knows rather than throwing on a null read.
                invoice = invoice || {};
                stopCountdown();

                document.getElementById('rc-course').textContent = invoice.course_title || @json($invoice->course->title);
                document.getElementById('rc-schedule').textContent = invoice.schedule || @json($scheduleLabel);
                document.getElementById('rc-invoice').textContent = invoice.invoice_code || @json($invoice->invoice_code);
                document.getElementById('rc-option').textContent = invoice.payment_option || 'ABA KHQR';
                document.getElementById('rc-tranid').textContent = invoice.tran_id || @json($invoice->payway_tran_id) || '—';
                document.getElementById('rc-paidat').textContent = invoice.paid_at ||
                    @json(optional($invoice->paid_at)->format('d M Y, h:i A')) || '—';
                document.getElementById('rc-amount').textContent = '$' +
                    (invoice.paid_amount_total || invoice.amount_paid || @json(number_format($invoice->paid_amount, 2)));

                var receiptBtn = document.getElementById('downloadReceiptBtn');
                receiptBtn.onclick = function() {
                    window.location.href = invoice.receipt_url || @json(route('student.payment.receipt', $invoice->id));
                };

                showState('success');
            }

            /* ── Payment: clicking the CTA opens PayWay's hosted checkout,
                   then polls our status endpoint until confirmed. ── */
            var payButton = document.getElementById('payButton');
            var payButtonIcon = document.getElementById('payButtonIcon');
            var statusBox = document.getElementById('pw-status');
            var statusUrl = @json(route('student.payment.status', $invoice->id));
            // Where a cancelled payment sends the student back to — see
            // cancelPayment() below.
            var COURSE_DETAILS_URL = @json(route('course.details', $invoice->course->slug));
            // What this page was actually rendered with, server-side.
            // checkStatusOnLoad() trusts THIS (not the live /status fetch)
            // to decide whether to show the milestone panel — see the long
            // comment on that decision inside checkStatusOnLoad() for why
            // the fetch can't be trusted for that specific call.
            var INITIAL_PAYMENT_STATUS = @json($invoice->payment_status);
            // Navigating here with ?pay=1 (see the milestone panel's "Pay
            // Remaining Now" button) tells checkStatusOnLoad() "the student
            // already saw the milestone and chose to continue — skip it
            // and go straight to the installment-2 payment card" instead
            // of showing the same milestone again on the very next load.
            var CONTINUE_PAYMENT_URL = window.location.pathname + '?pay=1';
            var skipMilestoneOnLoad = new URLSearchParams(window.location.search).get('pay') === '1';
            var pollTimer = null;
            var pollAttempts = 0;
            var POLL_INTERVAL_MS = 3000; // PayWay asks for a ~3s check-transaction cadence
            var MAX_ATTEMPTS = 100; // 5 minutes at 3s — matches the purchase payload's `lifetime` (see CourseEnrollmentController)
            // Pay Half's 1st installment lands on 'half_paid', not 'paid' —
            // once that happens the QR just used is dead, so the pay state
            // is swapped out for the milestone state instead of reopening
            // PayWay.
            var installmentPromptActive = false;
            // Guards cancelPayment() (below) against firing twice for the
            // same cancelled checkout.
            var cancelPaymentInFlight = false;

            /* One place for "a checkout is in flight" — used both right
               after the student clicks and when a reload finds one already
               started. Swaps the icon for a spinner (it stopped being
               something you can tap) and the button's label for a
               "waiting" message with animated dots. */
            function setWaitingUI() {
                cancelPaymentInFlight = false;
                payButton.disabled = true;
                payButtonText.innerHTML = 'Waiting for payment' +
                    '<span class="pw-waiting-dots" aria-hidden="true"><span></span><span></span><span></span></span>';
                if (payButtonIcon) {
                    payButtonIcon.className = 'fa-solid fa-circle-notch fa-spin';
                }
                startCountdown();
                pollTimer = setInterval(pollStatus, POLL_INTERVAL_MS);
            }

            /* The student backed out of PayWay's checkout without paying
               (closed its QR and confirmed its own "Are you sure you want
               to close?" prompt) — or, on the reload-detection path below,
               a short grace window after returning from it passed with
               nothing resolved. Send them straight back to the course
               page, same as if they'd never clicked Enroll — no alert to
               dismiss first. Deliberately doesn't try to reset this page's
               pay state in place (stop the timers, restore the button,
               etc.): they're leaving it anyway, and clicking Enroll again
               from the course page lands them right back on this same
               invoice (see startEnrollment()'s existing-invoice check)
               with a clean, freshly-rendered pay card instead of one
               patched up by hand. */
            function cancelPayment() {
                if (cancelPaymentInFlight || successState.style.display === 'block' || installmentPromptActive) {
                    return;
                }
                cancelPaymentInFlight = true;
                clearInterval(pollTimer);
                stopCountdown();
                window.location.href = COURSE_DETAILS_URL;
            }

            /* Called only from checkStatusOnLoad(): a tran_id already
               existed before this page load and PayWay's live status for
               it isn't definitively "unpaid" — which is exactly what a
               reload looks like both right after a successful payment
               (the webhook just hasn't landed yet) and right after the
               student closed PayWay's checkout without paying (that close
               sends the whole page through a redirect back to this same
               URL — see the submit handler's comment on continue_success_url
               — with no chance for any JS here to tell "cancelled" apart
               from "still settling" before the reload happens). Can't tell
               those two apart yet, so: poll briefly in case it's a slow
               webhook, and if nothing resolves within that short window,
               run the normal cancel flow — no button needed, this reload
               already IS the signal that the student is done with this
               attempt. */
            function waitBrieflyThenAssumeCancelled() {
                payButton.disabled = true;
                payButtonText.innerHTML = 'Checking your payment status' +
                    '<span class="pw-waiting-dots" aria-hidden="true"><span></span><span></span><span></span></span>';
                if (payButtonIcon) {
                    payButtonIcon.className = 'fa-solid fa-circle-notch fa-spin';
                }

                var graceAttempts = 0;
                var MAX_GRACE_ATTEMPTS = 4; // ~12s at POLL_INTERVAL_MS — generous for a slow webhook, short enough nobody sits waiting on a dead end

                pollTimer = setInterval(function() {
                    graceAttempts++;
                    fetch(statusUrl, {
                            headers: {
                                'Accept': 'application/json'
                            }
                        })
                        .then(function(res) {
                            return res.json();
                        })
                        .then(function(data) {
                            if (data.status === 'paid') {
                                clearInterval(pollTimer);
                                advanceStep('success');
                                showSuccessPanel(data.invoice);
                                return;
                            }
                            if (data.status === 'half_paid') {
                                clearInterval(pollTimer);
                                showInstallmentPaidPrompt(data.invoice);
                                return;
                            }
                            if (graceAttempts >= MAX_GRACE_ATTEMPTS) {
                                clearInterval(pollTimer);
                                cancelPayment();
                            }
                        })
                        .catch(function() {
                            if (graceAttempts >= MAX_GRACE_ATTEMPTS) {
                                clearInterval(pollTimer);
                                cancelPayment();
                            }
                        });
                }, POLL_INTERVAL_MS);
            }

            /* Pay Half's 1st installment just cleared — the course is
               already unlocked (access is granted from the first payment),
               but there's still a 2nd installment due. Rather than quietly
               repurposing the CTA button's label (easy to miss — the
               student is left wondering whether they overpaid, underpaid,
               or need to do anything else), swap in the milestone state: a
               clear "installment 1 of 2 paid" confirmation that states the
               remaining amount up front. "Pay Remaining Now" navigates to
               CONTINUE_PAYMENT_URL, which mints a fresh, correctly-priced
               QR for installment 2 (see CourseEnrollmentController's
               paymentPage()) and tells this panel not to show itself again
               on that next load. */
            function showInstallmentPaidPrompt(invoiceData) {
                stopCountdown();
                installmentPromptActive = true;

                // The enrollment itself IS confirmed the moment installment 1
                // clears — the student already has course access, only the
                // invoice isn't fully settled yet. So "Confirmed" (step 3)
                // should light up here too, same as the full-payment case,
                // instead of leaving the stepper stuck on "Review & Pay" with
                // no visual sign anything happened. Living here (rather than
                // at every call site) means every path that can show this
                // panel — in-page polling, the modal-close check, and both
                // page-load branches — gets it automatically.
                advanceStep('success');

                document.getElementById('ip-course').textContent = (invoiceData && invoiceData.course_title) || @json($invoice->course->title);
                document.getElementById('ip-invoice').textContent = (invoiceData && invoiceData.invoice_code) || @json($invoice->invoice_code);
                document.getElementById('ip-paid').textContent = '$' +
                    ((invoiceData && invoiceData.amount_paid) || @json(number_format($invoice->total_amount / 2, 2)));
                document.getElementById('ip-remaining').textContent = '$' +
                    ((invoiceData && invoiceData.remaining_amount) || @json(number_format($invoice->remaining_amount, 2)));

                var payRemainingBtn = document.getElementById('payRemainingBtn');
                if (payRemainingBtn) {
                    payRemainingBtn.onclick = function() {
                        window.location.href = CONTINUE_PAYMENT_URL;
                    };
                }

                // Same route showSuccessPanel()'s button uses — it already
                // allows a half_paid invoice (see downloadReceipt()'s
                // abort_unless), this panel just never offered a link to it.
                var milestoneReceiptBtn = document.getElementById('downloadReceiptBtnMilestone');
                if (milestoneReceiptBtn) {
                    milestoneReceiptBtn.onclick = function() {
                        window.location.href = (invoiceData && invoiceData.receipt_url) ||
                            @json(route('student.payment.receipt', $invoice->id));
                    };
                }

                showState('milestone');
            }

            function pollStatus() {
                pollAttempts++;
                fetch(statusUrl, {
                        headers: {
                            'Accept': 'application/json'
                        }
                    })
                    .then(function(res) {
                        return res.json();
                    })
                    .then(function(data) {
                        if (data.status === 'paid') {
                            clearInterval(pollTimer);
                            advanceStep('success');
                            showSuccessPanel(data.invoice);
                            return;
                        }
                        if (data.status === 'half_paid') {
                            clearInterval(pollTimer);
                            showInstallmentPaidPrompt(data.invoice);
                            return;
                        }
                        if (pollAttempts >= MAX_ATTEMPTS) {
                            expireSession();
                        }
                    })
                    .catch(function() {
                        // transient network hiccup — keep polling silently
                    });
            }

            /* This lines up with PayWay's own purchase `lifetime` (see
               CourseEnrollmentController::PAYWAY_LIFETIME_MINUTES) — by the
               time our own polling gives up, that tran_id is dead on
               PayWay's side too, and its own checkout modal shows a
               "Transaction is expired. Please re-initiate the
               transaction." error whose own "Try Again" button can't
               actually fix anything (it just resubmits the same expired
               tran_id). Reloading this page is what actually works, since
               the server now mints a fresh tran_id whenever the current
               one has expired — so that's what our own "Try Again" does. */
            function expireSession() {
                clearInterval(pollTimer);
                stopCountdown();

                if (typeof AbaPayway !== 'undefined' && typeof AbaPayway.closeCheckout === 'function') {
                    try {
                        AbaPayway.closeCheckout(false);
                    } catch (e) {
                        // modal may already be closed — nothing to do
                    }
                }

                payButtonText.textContent = 'This QR code has expired';
                if (payButtonIcon) {
                    payButtonIcon.className = 'fa-solid fa-rotate-right';
                }

                statusBox.style.display = 'block';
                statusBox.innerHTML = 'This payment session has expired.' +
                    ' <button type="button" id="pwRetryBtn" ' +
                    'style="display:inline-block;margin-left:6px;padding:6px 16px;' +
                    'border:none;border-radius:8px;background:var(--co-primary, #3777ff);' +
                    'color:#fff;font-weight:600;font-size:13px;cursor:pointer;">Try Again</button>';

                var retryBtn = document.getElementById('pwRetryBtn');
                if (retryBtn) {
                    retryBtn.addEventListener('click', function() {
                        window.location.reload();
                    });
                }
            }

            var paywayFieldsPresent = @json((bool) $paywayFields);
            var paywayForm = document.getElementById('aba_merchant_request');

            if (paywayFieldsPresent && paywayForm) {
                // Binds to the form's `submit` event rather than the
                // button's `click` — this is PayWay's own documented
                // checkout2-0.js integration pattern (their sample does
                // exactly this: preventDefault() the real form submit,
                // then call AbaPayway.checkout() to open the modal/bottom
                // sheet in its place) rather than a custom button wired up
                // on its own. Keeps us aligned with how the widget expects
                // to be triggered, and the Pay button stays a genuine
                // submit control — if the widget script ever fails to
                // load, clicking it still POSTs straight to PayWay's
                // hosted checkout instead of doing nothing.
                paywayForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    if (installmentPromptActive) {
                        window.location.href = CONTINUE_PAYMENT_URL;
                        return;
                    }

                    payButton.disabled = true;
                    payButtonText.textContent = 'Opening secure checkout…';

                    // AbaPayway.checkout() renders PayWay's own hosted
                    // checkout modal/bottom-sheet in-page — it manages its
                    // own UI entirely, so nothing here needs to open (or
                    // pre-open) any window of our own for it.
                    //
                    // Closing it (its own X, confirmed) can't be hooked
                    // from here — confirmed by live testing: that close
                    // sends the WHOLE PAGE through a top-level redirect
                    // back to this exact URL (see continue_success_url,
                    // below) before any JS here gets a chance to run. So
                    // instead of trying to catch the close itself, the
                    // reload it causes is what's detected — see
                    // checkStatusOnLoad()'s grace-period branch further
                    // down, which is what actually sends a student who
                    // cancelled back to the course page.
                    AbaPayway.checkout();
                    setWaitingUI();
                });
            }

            /* ── On page load, check once whether this invoice is already
                   paid. PayWay's continue_success_url redirect lands here as
                   a brand-new page load — any in-memory poll state from
                   before the redirect is gone — so without this check the
                   page just falls back to showing the pay state again even
                   though payment succeeded. If it's not paid yet (e.g. the
                   webhook hasn't landed a beat after the redirect), start
                   the same polling loop the click handler uses so it
                   resolves within a few seconds instead of leaving the
                   student stuck.

                   IMPORTANT: for 'paid' and 'half_paid' this trusts
                   INITIAL_PAYMENT_STATUS (what Blade rendered the page
                   with), not data.status from the fetch below — the fetch
                   is used only to enrich the panel with receipt details.
                   Reason: buildPaymentContext() (see
                   CourseEnrollmentController's paymentPage()) mints a
                   FRESH, never-attempted payway_tran_id on every GET to
                   this page whenever the current one is empty — which it
                   always is right after Pay Half's 1st installment clears
                   (markInvoicePaid() clears it on purpose, so the next load
                   gets a correctly-priced QR for installment 2). That
                   minting happens server-side, synchronously, BEFORE this
                   JS ever runs. So by the time this fetch reaches
                   PayWayPaymentController@status, payway_tran_id is no
                   longer empty — status() takes that as "go check this
                   tran_id with PayWay" and ends up asking PayWay about a
                   QR nobody has even seen yet, which obviously isn't
                   APPROVED, and status() reports back something other
                   than 'half_paid' as a result — silently masking a
                   genuinely half-paid invoice as not-yet-paid. That's what
                   made the milestone state (showInstallmentPaidPrompt)
                   flash correctly the moment the payment first landed, via
                   pollStatus()/closeCheckout's in-page detection, only to
                   vanish and fall back to the plain, context-free pay
                   state the instant PayWay's own redirect reloaded the
                   page a moment later. Blade's own $invoice->payment_status
                   has no such race — it's a plain read of the DB column at
                   render time — so it's what decides which state to show. ── */
            function checkStatusOnLoad() {
                fetch(statusUrl, {
                        headers: {
                            'Accept': 'application/json'
                        }
                    })
                    .then(function(res) {
                        return res.json();
                    })
                    .then(function(data) {
                        if (INITIAL_PAYMENT_STATUS === 'paid') {
                            advanceStep('success');
                            showSuccessPanel(data.invoice);
                            return;
                        }

                        if (INITIAL_PAYMENT_STATUS === 'half_paid') {
                            if (skipMilestoneOnLoad) {
                                // The student already saw the milestone
                                // panel and clicked "Pay Remaining Now"
                                // (see CONTINUE_PAYMENT_URL) — or came
                                // straight here via the dashboard's own
                                // "Pay Remaining" link, which uses this same
                                // ?pay=1 flag to skip the milestone outright
                                // — this load is the one meant to show the
                                // actual installment-2 pay state, not the
                                // milestone again. Enrollment is still
                                // confirmed either way, so the stepper needs
                                // advanceStep() here too — showInstallment-
                                // PaidPrompt() (which calls it internally)
                                // never runs on this branch to do it.
                                advanceStep('success');
                                showState('pay');
                                return;
                            }

                            // Covers both "this installment just cleared —
                            // PayWay's redirect landed here" and "the
                            // student is simply revisiting later to pay
                            // the rest". Either way, lead with the same
                            // "installment 1 of 2 paid, here's what's
                            // left" summary the in-page poller shows,
                            // rather than silently dropping them back into
                            // the raw pay state with no explanation of
                            // what just happened. showInstallmentPaidPrompt()
                            // falls back to Blade-rendered values for
                            // anything data.invoice doesn't carry, so this
                            // is safe even when the fetch above mis-reports
                            // status (see the comment above this function).
                            showInstallmentPaidPrompt(data.invoice);
                            return;
                        }

                        // Genuinely unpaid at render time. tran_id isn't
                        // being eagerly re-minted out from under us here
                        // the way it is for 'half_paid' above (it only
                        // gets reset once an installment actually
                        // clears), so data.status from this live fetch is
                        // trustworthy — it's how the original "webhook
                        // landed a beat after the redirect" case this
                        // function was built for still gets caught.
                        if (data.status === 'paid') {
                            advanceStep('success');
                            showSuccessPanel(data.invoice);
                            return;
                        }

                        if (data.status === 'half_paid') {
                            showInstallmentPaidPrompt(data.invoice);
                            return;
                        }

                        // Still unpaid — show the pay state so the student
                        // can pay. The countdown itself only starts once
                        // the student actually opens the QR (see the
                        // payButton click handler above) — except in the
                        // branch just below, where a checkout was already
                        // opened before this page load, so the clock is
                        // already running whether we display it or not.
                        showState('pay');

                        // A tran_id already existed before this load, and
                        // PayWay's live status for it isn't definitively
                        // "unpaid" — this is exactly what a reload looks
                        // like both right after a successful payment (the
                        // webhook just hasn't landed yet) AND right after
                        // the student closed PayWay's checkout without
                        // paying (see the submit handler's comment on why
                        // that close can't be caught directly — this
                        // reload is the only signal of it we ever get).
                        // Can't tell those two apart yet, so give it a
                        // short grace window to resolve on its own before
                        // treating it as a cancel.
                        if (@json((bool) $invoice->payway_tran_id) && data.status !== 'unpaid') {
                            waitBrieflyThenAssumeCancelled();
                        }
                    })
                    .catch(function() {
                        // transient — fall back to whatever Blade already
                        // knows was true at render time (no data.invoice
                        // available here, but both panels tolerate that —
                        // see their fallbacks) rather than unconditionally
                        // showing the raw pay state, which would wrongly
                        // prompt an already-paid or already-half-paid
                        // student to scan again just because this one
                        // fetch failed.
                        if (INITIAL_PAYMENT_STATUS === 'paid') {
                            advanceStep('success');
                            showSuccessPanel(null);
                            return;
                        }

                        if (INITIAL_PAYMENT_STATUS === 'half_paid' && !skipMilestoneOnLoad) {
                            showInstallmentPaidPrompt(null);
                            return;
                        }

                        // Same ?pay=1 case as the try block above — still
                        // half_paid (so still enrolled/confirmed), just
                        // skipping the milestone panel, and this fetch
                        // failing doesn't change that.
                        if (INITIAL_PAYMENT_STATUS === 'half_paid' && skipMilestoneOnLoad) {
                            advanceStep('success');
                        }

                        showState('pay');
                    });
            }

            checkStatusOnLoad();
        })();
    </script>
@endsection
