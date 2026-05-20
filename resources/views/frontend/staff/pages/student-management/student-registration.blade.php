@extends('frontend.staff.layout.master')
@section('page_title', isset($page_title) ? $page_title : 'Student Registration')
@push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&family=DM+Mono:wght@400;500&display=swap');

        .reg-wrap {
            font-family: 'DM Sans', sans-serif;
        }

        /* ── Card shell ── */
        .reg-card {
            background: #ffffff;
            border: 1px solid #e8eaed;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, .04), 0 8px 32px rgba(0, 0, 0, .06);
        }

        /* ── Left accent strip ── */
        .reg-card-inner {
            display: flex;
            min-height: 100%;
        }

        .reg-sidebar {
            width: 5px;
            background: linear-gradient(180deg, #0ea5e9 0%, #6366f1 50%, #8b5cf6 100%);
            flex-shrink: 0;
            border-radius: 0;
        }

        .reg-body {
            flex: 1;
            padding: 2.25rem 2.5rem;
        }

        /* ── Page header ── */
        .reg-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 2rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid #f1f3f5;
        }

        .reg-title {
            font-size: 1.35rem;
            font-weight: 600;
            color: #0f172a;
            letter-spacing: -.3px;
            margin: 0 0 .25rem;
        }

        .reg-subtitle {
            font-size: .825rem;
            color: #94a3b8;
            margin: 0;
        }

        .btn-invoices {
            font-family: 'DM Sans', sans-serif;
            font-size: .8rem;
            font-weight: 500;
            color: #64748b;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: .45rem 1.1rem;
            text-decoration: none;
            transition: all .18s ease;
            white-space: nowrap;
        }

        .btn-invoices:hover {
            background: #f1f5f9;
            color: #334155;
            border-color: #cbd5e1;
        }

        /* ── Section labels ── */
        .reg-section-label {
            font-size: .7rem;
            font-weight: 600;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: #94a3b8;
            margin: 0 0 1rem;
            display: flex;
            align-items: center;
            gap: .5rem;
        }

        .reg-section-label::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #f1f3f5;
        }

        .reg-divider {
            height: 1px;
            background: #f1f3f5;
            margin: 1.75rem 0;
        }

        /* ── Floating inputs ── */
        .reg-field {
            position: relative;
        }

        .reg-field input,
        .reg-field select {
            font-family: 'DM Sans', sans-serif;
            font-size: .875rem;
            width: 100%;
            padding: 1.4rem 1rem .5rem;
            background: #f8fafc;
            border: 1.5px solid #e8eaed;
            border-radius: 12px;
            color: #0f172a;
            outline: none;
            transition: border-color .18s, background .18s, box-shadow .18s;
            appearance: none;
            -webkit-appearance: none;
        }

        .reg-field input:focus,
        .reg-field select:focus {
            border-color: #0ea5e9;
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(14, 165, 233, .08);
        }

        .reg-field input[readonly] {
            background: #f1f5f9;
            color: #64748b;
            cursor: default;
        }

        .reg-field input[readonly]:focus {
            border-color: #e2e8f0;
            box-shadow: none;
        }

        .reg-field label {
            position: absolute;
            top: 50%;
            left: 1rem;
            transform: translateY(-50%);
            font-size: .8rem;
            color: #94a3b8;
            pointer-events: none;
            transition: all .15s ease;
            font-weight: 400;
        }

        .reg-field input:focus~label,
        .reg-field input:not(:placeholder-shown)~label,
        .reg-field select:focus~label,
        .reg-field select:not([value=""])~label,
        .reg-field.has-value label {
            top: .55rem;
            transform: none;
            font-size: .68rem;
            font-weight: 500;
            color: #0ea5e9;
        }

        .reg-field .field-icon {
            position: absolute;
            right: .9rem;
            top: 50%;
            transform: translateY(-50%);
            color: #cbd5e1;
            font-size: .95rem;
            pointer-events: none;
        }

        .reg-field select~.field-icon {
            pointer-events: none;
        }

        /* readonly field label always floated */
        .reg-field input[readonly]~label {
            top: .55rem;
            transform: none;
            font-size: .68rem;
            font-weight: 500;
            color: #94a3b8;
        }

        /* ── Select2 override ── */
        .reg-field .select2-container {
            width: 100% !important;
        }

        .reg-field .select2-container--default .select2-selection--single,
        .reg-field .select2-container--default .select2-selection--multiple {
            font-family: 'DM Sans', sans-serif;
            font-size: .875rem;
            background: #f8fafc;
            border: 1.5px solid #e8eaed;
            border-radius: 12px;
            min-height: 54px;
            padding: 1.4rem 1rem .5rem;
            outline: none;
        }

        .reg-field .select2-container--default.select2-container--focus .select2-selection--multiple,
        .reg-field .select2-container--default.select2-container--open .select2-selection--single {
            border-color: #0ea5e9;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(14, 165, 233, .08);
        }

        .reg-field .select2-container--default .select2-selection--multiple .select2-selection__rendered {
            padding: 0;
            display: flex;
            flex-wrap: wrap;
            gap: 4px;
        }

        .reg-field .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background: #e0f2fe;
            border: none;
            border-radius: 6px;
            padding: 2px 8px;
            font-size: .75rem;
            color: #0369a1;
            font-weight: 500;
        }

        .reg-field .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            color: #7dd3fc;
            margin-right: 4px;
        }

        .reg-field .select2-container--default .select2-selection--single .select2-selection__rendered {
            padding: 0;
            font-size: .875rem;
            color: #0f172a;
            line-height: 1.4;
        }

        .reg-field .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 54px;
            right: 10px;
        }

        /* ── Eye toggle ── */
        .eye-toggle {
            position: absolute;
            right: .9rem;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #94a3b8;
            font-size: 1rem;
            z-index: 5;
            transition: color .15s;
        }

        .eye-toggle:hover {
            color: #64748b;
        }

        /* ── Payment option badges ── */
        .payment-badges {
            display: flex;
            flex-wrap: wrap;
            gap: .5rem;
            margin-bottom: 1rem;
        }

        .pay-badge {
            font-family: 'DM Sans', sans-serif;
            font-size: .75rem;
            font-weight: 500;
            padding: .35rem .85rem;
            border-radius: 20px;
            border: 1.5px solid #e2e8f0;
            background: #f8fafc;
            color: #64748b;
            cursor: pointer;
            transition: all .18s ease;
            user-select: none;
            white-space: nowrap;
        }

        .pay-badge:hover {
            border-color: #0ea5e9;
            color: #0ea5e9;
            background: #f0f9ff;
        }

        .pay-badge.active {
            border-color: #0ea5e9;
            background: #0ea5e9;
            color: #fff;
        }

        .pay-badge.active.free {
            background: #10b981;
            border-color: #10b981;
        }

        .pay-badge.active.full {
            background: #6366f1;
            border-color: #6366f1;
        }

        .pay-badge.active.half {
            background: #f59e0b;
            border-color: #f59e0b;
        }

        .pay-badge.active.multi {
            background: #8b5cf6;
            border-color: #8b5cf6;
        }

        .pay-badge.active.other {
            background: #0f172a;
            border-color: #0f172a;
        }

        .pay-badge.active.normal {
            background: #0ea5e9;
            border-color: #0ea5e9;
        }

        .pay-badge.disabled-badge {
            opacity: .38;
            pointer-events: none;
        }

        /* ── Summary cards ── */
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: .75rem;
            margin-top: .5rem;
        }

        .summary-card {
            background: #f8fafc;
            border: 1.5px solid #e8eaed;
            border-radius: 14px;
            padding: .9rem 1rem;
            transition: border-color .2s;
        }

        .summary-card.highlight {
            background: #f0f9ff;
            border-color: #bae6fd;
        }

        .summary-card.highlight-green {
            background: #f0fdf4;
            border-color: #bbf7d0;
        }

        .summary-card.highlight-amber {
            background: #fffbeb;
            border-color: #fde68a;
        }

        .summary-card-label {
            font-size: .68rem;
            font-weight: 600;
            letter-spacing: .05em;
            text-transform: uppercase;
            color: #94a3b8;
            margin-bottom: .3rem;
        }

        .summary-card-value {
            font-family: 'DM Mono', monospace;
            font-size: 1.15rem;
            font-weight: 500;
            color: #0f172a;
            min-height: 1.6rem;
        }

        .summary-card-value.muted {
            color: #94a3b8;
        }

        /* ── Status badge display ── */
        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: .35rem;
            font-size: .75rem;
            font-weight: 600;
            padding: .3rem .8rem;
            border-radius: 20px;
            margin-top: .4rem;
        }

        .status-pill.paid {
            background: #dcfce7;
            color: #16a34a;
        }

        .status-pill.half_paid {
            background: #fef9c3;
            color: #ca8a04;
        }

        .status-pill.unpaid {
            background: #fee2e2;
            color: #dc2626;
        }

        .status-pill.free {
            background: #d1fae5;
            color: #059669;
        }

        .status-pill.empty {
            background: #f1f5f9;
            color: #94a3b8;
        }

        /* ── Manual entry panel ── */
        .manual-panel {
            /* linear red */
            background: linear-gradient(135deg, #fee2e2, #fef9c3);

            border: 1.5px solid #fde68a;
            border-radius: 16px;
            padding: 1.25rem 1.5rem;
            margin-top: .75rem;
        }

        .manual-panel-title {
            font-size: .75rem;
            font-weight: 600;
            letter-spacing: .06em;
            text-transform: uppercase;
            color: #92400e;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: .4rem;
        }

        /* ── Multi-course note ── */
        .multi-note {
            font-size: .75rem;
            font-weight: 500;
            color: #059669;
            background: #ecfdf5;
            border: 1px solid #bbf7d0;
            border-radius: 8px;
            padding: .35rem .75rem;
            display: inline-flex;
            align-items: center;
            gap: .3rem;
            margin-top: .5rem;
        }

        /* ── Submit button ── */
        .btn-submit {
            font-family: 'DM Sans', sans-serif;
            font-size: .875rem;
            font-weight: 600;
            color: #fff;
            background: linear-gradient(135deg, #0ea5e9, #6366f1);
            border: none;
            border-radius: 12px;
            padding: .75rem 2rem;
            cursor: pointer;
            transition: opacity .18s, transform .15s, box-shadow .18s;
            letter-spacing: .01em;
            box-shadow: 0 4px 14px rgba(99, 102, 241, .25);
        }

        .btn-submit:hover {
            opacity: .92;
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(99, 102, 241, .3);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        /* ── Student type toggle tabs ── */
        .type-tabs {
            display: flex;
            background: #f1f5f9;
            border-radius: 12px;
            padding: 3px;
            gap: 3px;
            width: fit-content;
            margin-bottom: 1.5rem;
        }

        .type-tab {
            font-family: 'DM Sans', sans-serif;
            font-size: .8rem;
            font-weight: 500;
            padding: .45rem 1.2rem;
            border-radius: 9px;
            border: none;
            background: transparent;
            color: #64748b;
            cursor: pointer;
            transition: all .18s ease;
        }

        .type-tab.active {
            background: #ffffff;
            color: #0f172a;
            box-shadow: 0 1px 4px rgba(0, 0, 0, .1);
        }

        /* ── Input error ── */
        .field-error {
            font-size: .72rem;
            color: #ef4444;
            margin-top: .3rem;
            padding-left: .25rem;
        }

        /* ── Responsive ── */
        @media (max-width: 768px) {
            .reg-body {
                padding: 1.5rem;
            }

            .summary-grid {
                grid-template-columns: 1fr 1fr;
            }

            .reg-header {
                flex-direction: column;
                gap: 1rem;
            }
        }

        @media (max-width: 576px) {
            .summary-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush
@section('content')
    @include('frontend.staff.pages.partials.breadcrumb')
    <div class="reg-wrap">
        <div class="reg-card">
            <div class="reg-card-inner">
                <div class="reg-sidebar"></div>
                <div class="reg-body">
                    {{-- Header --}}
                    <div class="reg-header">
                        <div>
                            <h4 class="reg-title">Student Registration</h4>
                            <p class="reg-subtitle">Register a new student and assign course payment.</p>
                        </div>
                        <a href="{{ route('staff.invoices') }}" class="btn-invoices">
                            <i class="ti ti-file-invoice me-1"></i> Invoices
                        </a>
                    </div>
                    <form action="{{ route('staff.student.registration.submit') }}" method="POST" id="regForm">
                        @csrf
                        {{-- ── STUDENT SELECTION ── --}}
                        <p class="reg-section-label">Student</p>
                        <div class="type-tabs">
                            <button type="button" class="type-tab active" data-type="new">New Student</button>
                            <button type="button" class="type-tab" data-type="existing">Existing Student</button>
                        </div>
                        <input type="hidden" name="student_type" id="studentTypeInput" value="new">
                        {{-- Existing student selector --}}
                        <div class="d-none" id="existingStudentWrapper">
                            <div class="reg-field mb-3">
                                <select class="form-select" name="student_id" id="studentSelect">
                                    <option value="" disabled selected></option>
                                    @foreach ($students as $student)
                                        <option value="{{ $student->id }}">
                                            {{ $student->name }} — {{ $student->email }}
                                        </option>
                                    @endforeach
                                </select>
                                <label>Search existing student</label>
                            </div>
                        </div>
                        {{-- New student fields --}}
                        <div id="newStudentWrapper">
                            <p class="reg-section-label">Basic Information</p>
                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <div class="reg-field">
                                        <input type="text" name="name" placeholder=" ">
                                        <label>Full Name <span class="text-danger">*</span></label>
                                    </div>
                                    <x-input-error :messages="$errors->get('name')" class="field-error" />
                                </div>
                                <div class="col-md-6">
                                    <div class="reg-field">
                                        <input type="email" name="email" placeholder=" ">
                                        <label>Email Address <span class="text-danger">*</span></label>
                                    </div>
                                    <x-input-error :messages="$errors->get('email')" class="field-error" />
                                </div>
                                <div class="col-md-6">
                                    <div class="reg-field">
                                        <input type="password" name="password" id="pass1" placeholder=" ">
                                        <label>Password <span class="text-danger">*</span></label>
                                        <span class="eye-toggle" data-target="pass1"><i class="ti ti-eye-off"></i></span>
                                    </div>
                                    <x-input-error :messages="$errors->get('password')" class="field-error" />
                                </div>
                                <div class="col-md-6">
                                    <div class="reg-field">
                                        <input type="password" name="password_confirmation" id="pass2" placeholder=" ">
                                        <label>Confirm Password <span class="text-danger">*</span></label>
                                        <span class="eye-toggle" data-target="pass2"><i class="ti ti-eye-off"></i></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="reg-field">
                                        <input type="text" name="phone" placeholder=" ">
                                        <label>Phone Number <span class="text-danger">*</span></label>
                                    </div>
                                    <x-input-error :messages="$errors->get('phone')" class="field-error" />
                                </div>
                                <div class="col-md-6">
                                    <div class="reg-field">
                                        <input type="text" name="alternate_phone" placeholder=" ">
                                        <label>Alternate Phone</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="reg-divider"></div>
                        {{-- ── COURSE & PAYMENT ── --}}
                        <p class="reg-section-label">Course & Payment</p>
                        <div class="row g-3 mb-3">
                            <div class="col-12">
                                <div class="reg-field">
                                    <select id="courseSelect" name="course_ids[]" multiple placeholder=" ">
                                        @foreach ($courses as $course)
                                            @php
                                                $schedule = $course->schedule;
                                                if ($schedule) {
                                                    $days = collect(explode('-', $schedule->study_day))
                                                        ->map(fn($d) => ucfirst($d))
                                                        ->implode(' • ');
                                                    $shift = ucfirst($schedule->shift);
                                                    $start = \Carbon\Carbon::parse($schedule->start_time)->format(
                                                        'g:i',
                                                    );
                                                    $end = \Carbon\Carbon::parse($schedule->end_time)->format('g:i A');
                                                }
                                            @endphp
                                            <option value="{{ $course->id }}" data-price="{{ $course->price }}">
                                                ${{ $course->price }} · {{ $course->title }}
                                                @if ($schedule)
                                                    · {{ $days }} · {{ $shift }}
                                                    ({{ $start }}–{{ $end }})
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    <label>Select Course(s) <span class="text-danger">*</span></label>
                                </div>
                                <x-input-error :messages="$errors->get('course_ids')" class="field-error" />
                            </div>
                        </div>
                        {{-- Payment option badges --}}
                        <p class="reg-section-label" style="margin-top:1rem">Payment Option</p>
                        <input type="hidden" name="payment_option" id="paymentOptionInput" value="">
                        <div class="payment-badges" id="paymentBadges">
                            <span class="pay-badge normal" data-value="normal">Pay Full</span>
                            <span class="pay-badge full" data-value="full">Full + $10 Off</span>
                            <span class="pay-badge half" data-value="half">Half + $20 Fee</span>
                            <span class="pay-badge multi" data-value="multi">Multi ($25 Off)</span>
                            <span class="pay-badge free" data-value="free">Free</span>
                            <span class="pay-badge other" data-value="other">Other / Manual</span>
                        </div>
                        <div id="multiNoteWrapper" class="d-none">
                            <span class="multi-note">
                                <i class="ti ti-sparkles"></i> $25 multi-course discount applied
                            </span>
                        </div>
                        {{-- ── MANUAL ENTRY PANEL ── --}}
                        <div class="manual-panel d-none" id="manualEntryWrapper">
                            <p class="manual-panel-title">
                                <i class="ti ti-edit"></i> Manual Entry
                            </p>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="reg-field">
                                        <input type="number" step="0.01" min="0" id="manualDiscount"
                                            name="manual_discount" placeholder=" " value="0">
                                        <label>Discount ($)</label>
                                    </div>
                                    <x-input-error :messages="$errors->get('manual_discount')" class="field-error" />
                                </div>
                                {{-- <div class="col-md-4">
                                    <div class="reg-field">
                                        <input type="number" step="0.01" min="0" id="manualExtraCharge"
                                            name="manual_extra_charge" placeholder=" " value="0">
                                        <label>Extra Charge ($)</label>
                                    </div>
                                    <x-input-error :messages="$errors->get('manual_extra_charge')" class="field-error" />
                                </div> --}}
                                <div class="col-md-4">
                                    <div class="reg-field">
                                        <input type="number" step="0.01" min="0" id="manualTotalAmount"
                                            name="manual_total_amount" placeholder=" ">
                                        <label>Total Amount ($) <span class="text-danger">*</span></label>
                                    </div>
                                    <x-input-error :messages="$errors->get('manual_total_amount')" class="field-error" />
                                </div>
                                <div class="col-md-4">
                                    <div class="reg-field">
                                        <input type="number" step="0.01" min="0" id="manualPaidAmount"
                                            name="manual_paid_amount" placeholder=" ">
                                        <label>Paid Amount ($) <span class="text-danger">*</span></label>
                                    </div>
                                    <x-input-error :messages="$errors->get('manual_paid_amount')" class="field-error" />
                                </div>
                                {{-- <div class="col-md-6">
                                    <div class="reg-field">
                                        <input type="number" step="0.01" id="manualRemainingAmount" placeholder=" "
                                            readonly>
                                        <label>Remaining ($)</label>
                                    </div>
                                </div> --}}
                            </div>
                        </div>
                        {{-- ── SUMMARY ── --}}
                        <div class="summary-grid" id="summaryGrid" style="margin-top:1.25rem">
                            <div class="summary-card" id="cardTotal">
                                <div class="summary-card-label">Total Amount</div>
                                <div class="summary-card-value muted" id="displayTotal">—</div>
                            </div>
                            <div class="summary-card" id="cardPaid">
                                <div class="summary-card-label">Amount to Pay</div>
                                <div class="summary-card-value muted" id="displayPaid">—</div>
                            </div>
                            <div class="summary-card" id="cardRemaining">
                                <div class="summary-card-label">Remaining</div>
                                <div class="summary-card-value muted" id="displayRemaining">—</div>
                                <div id="statusPillDisplay"></div>
                            </div>
                        </div>
                        {{-- Hidden inputs for form submission --}}
                        <input type="hidden" name="total_amount" id="totalAmount">
                        <input type="hidden" name="paid_amount" id="paidAmount">
                        <input type="hidden" name="payment_status" id="paymentStatus">
                        {{-- ── SUBMIT ── --}}
                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn-submit">
                                <i class="ti ti-send me-2"></i> Submit Registration
                            </button>
                        </div>
                    </form>
                </div>{{-- .reg-body --}}
            </div>{{-- .reg-card-inner --}}
        </div>{{-- .reg-card --}}
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            // ==============================
            // STUDENT TYPE TABS
            // ==============================
            $('.type-tab').on('click', function() {
                const type = $(this).data('type');
                $('.type-tab').removeClass('active');
                $(this).addClass('active');
                $('#studentTypeInput').val(type);
                if (type === 'existing') {
                    $('#existingStudentWrapper').removeClass('d-none');
                    $('#newStudentWrapper').addClass('d-none');
                    $('#newStudentWrapper input').prop('required', false).prop('disabled', true);
                } else {
                    $('#existingStudentWrapper').addClass('d-none');
                    $('#newStudentWrapper').removeClass('d-none');
                    $('#newStudentWrapper input').prop('disabled', false);
                }
                resetPaymentUI();
            });
            // ==============================
            // EYE TOGGLE
            // ==============================
            $('.eye-toggle').on('click', function() {
                const targetId = $(this).data('target');
                const input = $('#' + targetId);
                const icon = $(this).find('i');
                if (input.attr('type') === 'password') {
                    input.attr('type', 'text');
                    icon.removeClass('ti-eye-off').addClass('ti-eye');
                } else {
                    input.attr('type', 'password');
                    icon.removeClass('ti-eye').addClass('ti-eye-off');
                }
            });
            // ==============================
            // SELECT2
            // ==============================
            $('#courseSelect').select2({
                width: '100%',
                placeholder: 'Select one or more courses'
            });
            $('#studentSelect').select2({
                width: '100%',
                placeholder: 'Search student by name or email'
            });
            // ==============================
            // PAYMENT BADGES
            // ==============================
            $('.pay-badge').on('click', function() {
                const val = $(this).data('value');
                if ($(this).hasClass('disabled-badge')) return;
                selectPaymentOption(val);
            });

            function selectPaymentOption(val) {
                $('.pay-badge').removeClass('active');
                $(`.pay-badge[data-value="${val}"]`).addClass('active');
                $('#paymentOptionInput').val(val);
                if (val === 'other') {
                    $('#manualEntryWrapper').removeClass('d-none');
                    clearSummary();
                } else {
                    $('#manualEntryWrapper').addClass('d-none');
                    calculateAmounts();
                }
            }
            // ==============================
            // COURSE CHANGE
            // ==============================
            $('#courseSelect').on('change', function() {
                const count = $(this).find(':selected').length;
                handleMultiCourseUI(count);
                const currentOption = $('#paymentOptionInput').val();
                if (currentOption && currentOption !== 'other') {
                    calculateAmounts();
                }
            });

            function handleMultiCourseUI(count) {
                if (count >= 2) {
                    // lock to multi
                    $('.pay-badge').not('[data-value="multi"]').addClass('disabled-badge');
                    selectPaymentOption('multi');
                    $('#multiNoteWrapper').removeClass('d-none');
                } else {
                    $('.pay-badge').removeClass('disabled-badge');
                    $('#multiNoteWrapper').addClass('d-none');
                    const current = $('#paymentOptionInput').val();
                    if (current === 'multi') {
                        $('.pay-badge').removeClass('active');
                        $('#paymentOptionInput').val('');
                        clearSummary();
                    }
                }
            }
            // ==============================
            // MANUAL ENTRY LIVE CALC
            // ==============================
            $('#manualTotalAmount, #manualPaidAmount').on('input', function() {
                const total = parseFloat($('#manualTotalAmount').val()) || 0;
                const paid = parseFloat($('#manualPaidAmount').val()) || 0;
                const remaining = Math.max(0, total - paid);
                $('#manualRemainingAmount').val(remaining.toFixed(2));
                updateSummary(total, paid, remaining, 'other');
            });
            // ==============================
            // MAIN CALCULATION
            // ==============================
            function calculateAmounts() {
                const selectedCourses = $('#courseSelect').find(':selected');
                const courseCount = selectedCourses.length;
                const totalPrice = getTotalPrice(selectedCourses);
                const paymentOption = $('#paymentOptionInput').val();
                if (!paymentOption || paymentOption === 'other') return;
                let discount = 0;
                let extraCharge = 0;
                if (paymentOption === 'free') {
                    discount = totalPrice;
                }
                if (paymentOption === 'multi' && courseCount >= 2) {
                    discount += 25;
                }
                if (paymentOption === 'full') {
                    discount += 10;
                }
                if (paymentOption === 'half') {
                    extraCharge += 20;
                }
                let total = Math.max(0, totalPrice - discount + extraCharge);
                if (paymentOption === 'free') total = 0;
                let paid = total;
                if (paymentOption === 'half') paid = total / 2;
                if (paymentOption === 'free') paid = 0;
                if (paid > total) paid = total;
                const remaining = total - paid;
                updateSummary(total, paid, remaining, paymentOption);
            }

            function getTotalPrice(selected) {
                let t = 0;
                selected.each(function() {
                    t += parseFloat($(this).data('price')) || 0;
                });
                return t;
            }
            // ==============================
            // UPDATE SUMMARY CARDS
            // ==============================
            function updateSummary(total, paid, remaining, option) {
                // Hidden form values
                $('#totalAmount').val(total.toFixed(2));
                $('#paidAmount').val(paid.toFixed(2));
                // Display values
                $('#displayTotal').text('$' + total.toFixed(2)).removeClass('muted');
                $('#displayPaid').text('$' + paid.toFixed(2)).removeClass('muted');
                $('#displayRemaining').text('$' + remaining.toFixed(2)).removeClass('muted');
                // Card highlight
                $('#cardTotal').addClass('highlight');
                $('#cardPaid').addClass('highlight');
                if (remaining === 0 && paid > 0) {
                    $('#cardRemaining').addClass('highlight-green');
                    $('#cardRemaining').removeClass('highlight-amber');
                } else if (remaining > 0) {
                    $('#cardRemaining').addClass('highlight-amber');
                    $('#cardRemaining').removeClass('highlight-green');
                } else {
                    $('#cardRemaining').removeClass('highlight-green highlight-amber');
                }
                // Status
                let status = '';
                if (option === 'free') status = 'free';
                else if (paid === 0) status = 'unpaid';
                else if (remaining === 0) status = 'paid';
                else status = 'half_paid';
                $('#paymentStatus').val(status);
                const labels = {
                    paid: 'Paid',
                    half_paid: 'Half Paid',
                    unpaid: 'Unpaid',
                    free: 'Free'
                };
                const icons = {
                    paid: 'ti-circle-check',
                    half_paid: 'ti-clock',
                    unpaid: 'ti-alert-circle',
                    free: 'ti-gift'
                };
                $('#statusPillDisplay').html(
                    `<span class="status-pill ${status}">
                <i class="ti ${icons[status] || ''}"></i> ${labels[status] || ''}
            </span>`
                );
            }

            function clearSummary() {
                $('#totalAmount, #paidAmount, #paymentStatus').val('');
                $('#displayTotal, #displayPaid, #displayRemaining').text('—').addClass('muted');
                $('#cardTotal, #cardPaid, #cardRemaining').removeClass('highlight highlight-green highlight-amber');
                $('#statusPillDisplay').html('');
            }

            function resetPaymentUI() {
                $('.pay-badge').removeClass('active disabled-badge');
                $('#paymentOptionInput').val('');
                $('#manualEntryWrapper').addClass('d-none');
                $('#manualDiscount, #manualExtraCharge, #manualTotalAmount, #manualPaidAmount, #manualRemainingAmount')
                    .val('');
                $('#multiNoteWrapper').addClass('d-none');
                clearSummary();
            }
        });
    </script>
@endpush
