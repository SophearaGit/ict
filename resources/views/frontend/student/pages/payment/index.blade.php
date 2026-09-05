@extends('frontend.layouts.new.master')
@section('page_title', 'Checkout — ' . $invoice->course->title)
@push('styles')
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- jQuery -->

    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <link rel="stylesheet" href="/frontend/asset/css/checkout.css">
@endpush
@section('content')

    <main class="checkout-wrap">

        <!-- Breadcrumb -->
        <nav class="checkout-crumb" aria-label="Breadcrumb" data-aos="fade-up">
            <a href="{{ route('course') }}">Courses</a>
            <i class="fa-solid fa-chevron-right"></i>
            <a href="{{ route('course.details', $invoice->course->slug) }}">{{ $invoice->course->title }}</a>
            <i class="fa-solid fa-chevron-right"></i>
            <span aria-current="page">Checkout</span>
        </nav>

        <!-- Stepper -->
        <ol class="checkout-stepper" data-aos="fade-up">
            <li class="step is-done">
                <span class="step-dot"><i class="fa-solid fa-check"></i></span>
                <span class="step-label">Course</span>
            </li>
            <li class="step-line is-done"></li>
            <li class="step is-done" id="step-checkout">
                <span class="step-dot"><i class="fa-solid fa-check"></i></span>
                <span class="step-label">Checkout</span>
            </li>
            <li class="step-line is-done" id="step-line-payment"></li>
            <li class="step is-active" id="step-payment">
                <span class="step-dot">3</span>
                <span class="step-label">Payment</span>
            </li>
            <li class="step-line" id="step-line-success"></li>
            <li class="step" id="step-success">
                <span class="step-dot">4</span>
                <span class="step-label">Success</span>
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

                    <div class="detail-item">
                        <span class="detail-icon"><i class="fa-regular fa-calendar"></i></span>
                        <div class="detail-text">
                            <span class="detail-label">Schedule</span>
                            {{-- Placeholder is the default selection pre-payment
                                 — even when this invoice already has a schedule
                                 attached — so checkout.css's
                                 :has(#scheduleSelect option[value=""]:checked)
                                 rule keeps the payment card collapsed until
                                 the student actively picks a schedule
                                 (reselecting their current one counts).
                                 Once paid, there's nothing left to pick —
                                 switching schedules is already blocked
                                 post-payment — so just show the real one. --}}
                            <select id="scheduleSelect" class="detail-select"
                                @if ($invoice->payment_status === 'paid') disabled @endif>
                                <option value="" @if ($invoice->payment_status !== 'paid') selected @endif>
                                    Select your schedule
                                </option>
                                <option value="{{ $invoice->course_id }}"
                                    data-start="{{ $invoice->course->start_date?->format('F j, Y') ?? 'Depends on schedule' }}"
                                    @if ($invoice->payment_status === 'paid') selected @endif>
                                    @if ($invoice->course->schedule)
                                        {{ $invoice->course->schedule->short_days }} ·
                                        {{ $invoice->course->schedule->formatted_time }}
                                    @else
                                        Current schedule
                                    @endif
                                </option>
                            </select>
                            @if ($invoice->payment_status !== 'paid')
                                <p class="schedule-hint" id="scheduleHint">
                                    <i class="fa-solid fa-circle-info"></i> Pick a schedule to continue to payment
                                </p>
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

            <!-- RIGHT: order summary + payment -->
            <aside class="checkout-aside" data-aos="fade-up">

                <div class="co-card order-summary-card">
                    <h3 class="co-card-title">Order Summary</h3>
                    <div class="order-row">
                        <span>Subtotal</span>
                        <span>${{ number_format($invoice->price, 2) }}</span>
                    </div>
                    @if ($invoice->discount > 0)
                        <div class="order-row order-row--discount">
                            <span>Discount</span>
                            <span>-${{ number_format($invoice->discount, 2) }}</span>
                        </div>
                    @endif
                    @if ($invoice->extra_charge > 0)
                        <div class="order-row">
                            <span>Extra charge</span>
                            <span>${{ number_format($invoice->extra_charge, 2) }}</span>
                        </div>
                    @endif
                    <div class="order-row order-row--total">
                        @if ($invoice->payment_status === 'paid')
                            <span>Total Paid</span>
                            <span id="orderTotal">${{ number_format($invoice->paid_amount, 2) }}</span>
                        @else
                            <span>Total</span>
                            <span id="orderTotal">${{ number_format($invoice->remaining_amount, 2) }}</span>
                        @endif
                    </div>
                </div>

                <div class="co-card payment-card" id="paymentMethodCard" style="display:none">
                    <h3 class="co-card-title payment-card-title">Payment Method</h3>

                    <button type="button" class="payment-option is-selected mmb-3" id="abaOption">
                        <span class="payment-option-logo">
                            <img src="{{ asset('/frontend/asset/images/aba-khqr.jpg') }}" alt="ABA KHQR">
                        </span>
                        <span class="payment-option-text">
                            <span class="payment-option-name" id="abaOptionName">ABA KHQR</span>
                            <span class="payment-option-sub" id="abaOptionSub">Scan to pay with any banking app — tap to
                                continue</span>
                        </span>
                        <i class="fa-solid fa-chevron-right payment-option-chevron"></i>
                    </button>

                    <p class="secure-note" id="pw-status" style="display:none">
                        <span class="pw-waiting-dots" aria-hidden="true"><span></span><span></span><span></span></span>
                        Waiting for payment confirmation…
                    </p>
                </div>

                {{-- Step 4 success panel — hidden until payment is confirmed. --}}
                <div class="co-card pw-success-card" id="successCard" style="display:none">
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

                {{-- Loading state shown while we check whether this invoice is
                     already paid (e.g. right after PayWay's redirect back).
                     Prevents a flash of "pay now" UI before we know which
                     panel (payment vs success) actually belongs on screen. --}}
                <div class="co-card" id="statusLoadingCard">
                    <div class="pw-loading-inner">
                        <span class="pw-spinner" aria-hidden="true"></span>
                        <p>Checking your payment status…</p>
                    </div>
                </div>
            </aside>
        </div>
    </main>

    <style>
        .pw-success-card {
            text-align: center;
            padding: 36px 28px 28px;
        }

        .pw-success-badge {
            width: 68px;
            height: 68px;
            margin: 0 auto 18px;
        }

        .pw-success-badge svg {
            width: 100%;
            height: 100%;
        }

        .pw-success-badge__circle {
            stroke: #16a34a;
            stroke-width: 3;
            stroke-linecap: round;
            stroke-dasharray: 151;
            stroke-dashoffset: 151;
            animation: pwCircleDraw .5s ease-out forwards;
        }

        .pw-success-badge__check {
            stroke: #16a34a;
            stroke-width: 4;
            stroke-linecap: round;
            stroke-linejoin: round;
            stroke-dasharray: 36;
            stroke-dashoffset: 36;
            animation: pwCheckDraw .35s ease-out .45s forwards;
        }

        @keyframes pwCircleDraw {
            to {
                stroke-dashoffset: 0;
            }
        }

        @keyframes pwCheckDraw {
            to {
                stroke-dashoffset: 0;
            }
        }

        .pw-success-title {
            font-size: 22px;
            font-weight: 700;
            margin: 0 0 6px;
        }

        .pw-success-sub {
            color: var(--co-muted, #6b7280);
            font-size: 14px;
            margin: 0 0 22px;
        }

        .pw-success-details {
            text-align: left;
            background: var(--co-surface-muted, #f9fafb);
            border-radius: 12px;
            padding: 4px 16px;
            margin-bottom: 22px;
        }

        .pw-success-details .order-row {
            padding: 11px 0;
            border-bottom: 1px solid var(--co-border, #e5e7eb);
        }

        .pw-success-details .order-row:last-child {
            border-bottom: none;
        }

        .pw-success-details .order-row span:first-child {
            color: var(--co-muted, #6b7280);
            font-size: 13px;
        }

        .pw-success-details .order-row span:last-child {
            font-weight: 600;
            text-align: right;
        }

        .pw-success-details .order-row--total {
            border-top: 1px dashed var(--co-border, #e5e7eb);
            border-bottom: none;
            margin-top: 2px;
        }

        .pw-success-details .order-row--total span:last-child {
            color: #16a34a;
            font-size: 16px;
        }

        .pw-success-actions {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .schedule-hint {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 12.5px;
            color: var(--co-primary, #3777ff);
            margin: 8px 0 0;
        }

        .schedule-hint i {
            font-size: 12px;
        }

        /* Same :has() pattern checkout.css uses to collapse .payment-card —
           the hint only makes sense while nothing real is selected yet, so
           it disappears the instant a schedule is actually picked. */
        .checkout-grid:not(:has(#scheduleSelect option[value=""]:checked)) .schedule-hint {
            display: none;
        }

        .pw-waiting-dots {
            display: inline-flex;
            gap: 4px;
            vertical-align: middle;
            margin-right: 6px;
        }

        .pw-waiting-dots span {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: currentColor;
            opacity: .25;
            animation: pwDotPulse 1.2s infinite ease-in-out;
        }

        .pw-waiting-dots span:nth-child(2) {
            animation-delay: .2s;
        }

        .pw-waiting-dots span:nth-child(3) {
            animation-delay: .4s;
        }

        @keyframes pwDotPulse {

            0%,
            80%,
            100% {
                opacity: .25;
                transform: scale(0.85);
            }

            40% {
                opacity: 1;
                transform: scale(1);
            }
        }

        .pw-loading-inner {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 14px;
            padding: 32px 16px;
            text-align: center;
            color: var(--co-muted, #6b7280);
        }

        .pw-spinner {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            border: 3px solid rgba(0, 0, 0, .08);
            border-top-color: currentColor;
            color: var(--co-primary, #3b82f6);
            animation: pwSpin .7s linear infinite;
        }

        @keyframes pwSpin {
            to {
                transform: rotate(360deg);
            }
        }

        .btn-checkout-secondary {
            display: block;
            text-align: center;
            text-decoration: none;
            background: transparent;
            border: 1px solid var(--co-border, #e5e7eb);
            color: inherit;
        }
    </style>

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
        action="{{ config('services.payway.api_url') }}/api/payment-gateway/v1/payments/purchase" style="display:none">
        @if ($paywayFields)
            @foreach ($paywayFields as $key => $value)
                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
            @endforeach
        @endif
    </form>

    {{-- Hidden form used to switch to a sibling schedule/batch — submitting
         this reloads the checkout page against a re-targeted invoice with a
         freshly signed PayWay payload. --}}
    <form id="switch-schedule-form" action="{{ route('student.payment.switch-schedule', $invoice->id) }}" method="POST"
        style="display:none">
        @csrf
        <input type="hidden" name="course_id" id="switch-schedule-course-id">
    </form>

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
            var select = document.getElementById('scheduleSelect');
            var startDateValue = document.getElementById('startDateValue');

            function applyStartDate() {
                var opt = select.options[select.selectedIndex];
                if (opt && startDateValue) {
                    startDateValue.textContent = opt.dataset.start || 'Depends on schedule';
                }
            }

            function loadOtherSchedules() {
                // Capture whatever the student actually has selected right
                // now (the placeholder, by default) so it can be restored
                // after appending options below — Select2 loses track of
                // the real selection once new <option> elements are
                // appended after init and defaults its display to the
                // first option in the list.
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
                        var sections = res.sections || [];
                        sections.forEach(function(s) {
                            if (s.id == CURRENT_COURSE_ID) return; // already rendered server-side (loose compare: AJAX ids may come back as strings)
                            var opt = document.createElement('option');
                            opt.value = s.id;
                            opt.dataset.start = s.start_date ? s.start_date : 'Depends on schedule';
                            var label = s.days + ' · ' + s.time;
                            if (s.is_full) label += ' (Full)';
                            opt.textContent = label;
                            if (s.is_full) opt.disabled = true;
                            select.appendChild(opt);
                        });
                        select.value = previousValue;
                        $scheduleSelect.trigger('change');
                    })
                    .catch(function() {
                        // Sibling schedules just won't be selectable — current one still works.
                    });
            }

            // Picking a schedule used to submit #switch-schedule-form and
            // reload the whole page against a re-targeted invoice (needed
            // if a sibling schedule has a different price/date, so PayWay's
            // signed payload matches). That reload was breaking the
            // intended flow of "pick a schedule → generate the QR" in one
            // go, so for now this only updates the displayed start date —
            // no reload, and the ABA payload keeps referring to whichever
            // schedule this invoice was created for. If switching to a
            // sibling schedule needs to actually re-price the payment
            // later, that'll need an AJAX endpoint that re-signs the
            // PayWay fields in place instead of a full page reload.
            $scheduleSelect.on('change', function() {
                applyStartDate();
            });

            applyStartDate();
            loadOtherSchedules();

            /* ── Stepper: purely visual — the page loads directly on
                   "Payment" (step 3) since there's only one payment method
                   to choose from. This just advances to "Success" once the
                   payment is actually confirmed. ── */
            function advanceStep(target) {
                if (target === 'success') {
                    document.getElementById('step-payment').classList.replace('is-active', 'is-done');
                    document.getElementById('step-payment').querySelector('.step-dot').innerHTML = '<i class="fa-solid fa-check"></i>';
                    document.getElementById('step-line-success').classList.add('is-done');
                    document.getElementById('step-success').classList.add('is-active');
                }
            }

            function showSuccessPanel(invoice) {
                document.getElementById('paymentMethodCard').style.display = 'none';

                document.getElementById('rc-course').textContent = invoice.course_title;
                document.getElementById('rc-schedule').textContent = invoice.schedule;
                document.getElementById('rc-invoice').textContent = invoice.invoice_code;
                document.getElementById('rc-option').textContent = invoice.payment_option || '—';
                document.getElementById('rc-tranid').textContent = invoice.tran_id || '—';
                document.getElementById('rc-paidat').textContent = invoice.paid_at || '—';
                document.getElementById('rc-amount').textContent = '$' + invoice.amount_paid;

                var receiptBtn = document.getElementById('downloadReceiptBtn');
                receiptBtn.onclick = function() {
                    window.location.href = invoice.receipt_url;
                };

                document.getElementById('successCard').style.display = 'block';
            }

            /* ── Payment: clicking the ABA option opens PayWay's hosted checkout,
                   then polls our status endpoint until confirmed. ── */
            var abaOption = document.getElementById('abaOption');
            var abaOptionSub = document.getElementById('abaOptionSub');
            var statusBox = document.getElementById('pw-status');
            var statusUrl = @json(route('student.payment.status', $invoice->id));
            var pollTimer = null;
            var pollAttempts = 0;
            var MAX_ATTEMPTS = 20; // ~2 minutes at 6s interval

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
                        if (pollAttempts >= MAX_ATTEMPTS) {
                            clearInterval(pollTimer);
                            statusBox.innerHTML =
                                'Still waiting on confirmation — refresh this page once you\'ve completed payment.';
                        }
                    })
                    .catch(function() {
                        // transient network hiccup — keep polling silently
                    });
            }

            var paywayFieldsPresent = @json((bool) $paywayFields);

            if (paywayFieldsPresent) {
                // Where to send the student if they back out of PayWay's
                // checkout without paying, instead of leaving them stuck
                // looking at a closed modal and a disabled button.
                var COURSE_DETAILS_URL = @json(route('course.details', $invoice->course->slug));

                abaOption.addEventListener('click', function() {
                    abaOption.disabled = true;
                    abaOptionSub.textContent = 'Opening secure checkout…';

                    // AbaPayway.checkout() renders PayWay's own hosted
                    // checkout modal/bottom-sheet in-page — it manages its
                    // own UI entirely, so nothing here needs to open (or
                    // pre-open) any window of our own for it.
                    //
                    // PayWay's SDK doesn't expose a documented close/cancel
                    // callback, but its own modal close (X) button calls
                    // AbaPayway.closeCheckout() directly — that's the only
                    // hook available, so we wrap it (once AbaPayway is
                    // actually loaded, which it will be by the time the
                    // student can click this) to notice when the modal
                    // closes without a completed payment.
                    if (typeof AbaPayway !== 'undefined' &&
                        typeof AbaPayway.closeCheckout === 'function' &&
                        !AbaPayway.__redirectOnCloseWrapped) {
                        var originalCloseCheckout = AbaPayway.closeCheckout.bind(AbaPayway);
                        AbaPayway.closeCheckout = function(isAsk) {
                            originalCloseCheckout(isAsk);
                            // pollStatus() may have already confirmed the
                            // payment and swapped in the success panel by
                            // the time the modal closes — don't redirect
                            // away from a successful payment.
                            var alreadyPaid = document.getElementById('successCard')
                                .style.display === 'block';
                            if (!alreadyPaid) {
                                clearInterval(pollTimer);
                                window.location.href = COURSE_DETAILS_URL;
                            }
                        };
                        AbaPayway.__redirectOnCloseWrapped = true;
                    }

                    AbaPayway.checkout();

                    abaOptionSub.textContent = 'Waiting for payment…';
                    statusBox.style.display = 'block';
                    pollTimer = setInterval(pollStatus, 6000);
                });
            }

            /* ── On page load, check once whether this invoice is already
                   paid. PayWay's continue_success_url redirect lands here as
                   a brand-new page load — any in-memory poll state from
                   before the redirect is gone — so without this check the
                   page just falls back to showing Step 3 again even though
                   payment succeeded. If it's not paid yet (e.g. the webhook
                   hasn't landed a beat after the redirect), start the same
                   polling loop the click handler uses so it resolves within
                   a few seconds instead of leaving the student stuck. ── */
            function checkStatusOnLoad() {
                var loadingCard = document.getElementById('statusLoadingCard');

                fetch(statusUrl, {
                        headers: {
                            'Accept': 'application/json'
                        }
                    })
                    .then(function(res) {
                        return res.json();
                    })
                    .then(function(data) {
                        loadingCard.style.display = 'none';

                        if (data.status === 'paid') {
                            advanceStep('success');
                            showSuccessPanel(data.invoice);
                            return;
                        }

                        // Not paid — show the payment card so the student
                        // can pay.
                        document.getElementById('paymentMethodCard').style.display = 'block';

                        // If a tran_id already exists (a checkout was
                        // already started), start polling in case the
                        // webhook/confirmation is still in flight.
                        if (@json((bool) $invoice->payway_tran_id) && data.status !== 'unpaid') {
                            abaOption.disabled = true;
                            abaOptionSub.textContent = 'Waiting for payment…';
                            statusBox.style.display = 'block';
                            pollTimer = setInterval(pollStatus, 6000);
                        }
                    })
                    .catch(function() {
                        // transient — fall back to showing the payment card
                        // so the student isn't stuck on a loading spinner
                        loadingCard.style.display = 'none';
                        document.getElementById('paymentMethodCard').style.display = 'block';
                    });
            }

            checkStatusOnLoad();
        })();
    </script>
@endsection
