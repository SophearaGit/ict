@extends('frontend.staff.layout.master')
@section('page_title', isset($page_title) ? $page_title : 'Page Title Here')
@section('content')
    @include('frontend.staff.pages.partials.breadcrumb')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-body p-4">
                        <!-- Header -->
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h4 class="fw-bold mb-1">Student Registration</h4>
                                    <p class="text-muted mb-0">
                                        Register a new student and manage their course payment.
                                    </p>
                                </div>
                                <a href="{{ route('staff.invoices') }}" class="btn btn-primary rounded-pill">
                                    Invoices
                                </a>
                            </div>
                            <hr class="mt-3">
                        </div>

                        <form action="{{ route('staff.student.registration.submit') }}" method="POST">
                            @csrf
                            <input type="hidden" name="discount" id="discountField">
                            <input type="hidden" name="extra_charge" id="extraChargeField">
                            <!-- ================= STUDENT TYPE ================= -->
                            <h6 class="fw-semibold text-info mb-3">Student Selection</h6>
                            <div class="row g-3 mb-3 align-items-end">
                                <!-- Student Type -->
                                <div class="col-md-4">
                                    <div class="form-floating">
                                        <select class="form-select" id="studentType" name="student_type">
                                            <option value="new" selected>New Student</option>
                                            <option value="existing">Existing Student</option>
                                        </select>
                                        <label>
                                            <i class="ti ti-users me-2 text-info"></i> Student Type
                                        </label>
                                    </div>
                                </div>
                                <!-- Existing Student -->
                                <div class="col-md-8 d-none" id="existingStudentWrapper">
                                    <div class="form-floating">
                                        <select class="form-select select2" name="student_id" id="studentSelect">
                                            <option value="" disabled selected>Select Existing Student</option>
                                            @foreach ($students as $student)
                                                <option value="{{ $student->id }}">
                                                    {{ $student->name }} | {{ $student->email }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div id="newStudentWrapper">
                                <!-- ================= USER INFORMATION ================= -->
                                <h6 class="fw-semibold text-info mb-3">Basic Information</h6>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" placeholder="Username"
                                                name="name">
                                            <label>
                                                <i class="ti ti-user me-2 text-info"></i> Username
                                                <span class="text-danger"><strong>*</strong></span>
                                            </label>
                                            <x-input-error :messages="$errors->get('name')" class="text-danger mt-2" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="email" class="form-control" placeholder="Email" name="email">
                                            <label>
                                                <i class="ti ti-mail me-2 text-info"></i> Email Address
                                                <span class="text-danger"><strong>*</strong></span>

                                            </label>
                                            <x-input-error :messages="$errors->get('email')" class="text-danger mt-2" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row g-3 mt-1">
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="password" class="form-control" placeholder="Password"
                                                name="password">
                                            <label>
                                                <i class="ti ti-lock me-2 text-info"></i> Password
                                                <span class="text-danger"><strong>*</strong></span>
                                            </label>
                                            <x-input-error :messages="$errors->get('password')" class="text-danger mt-2" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="password" class="form-control" placeholder="Confirm Password"
                                                name="password_confirmation">
                                            <label>
                                                <i class="ti ti-lock me-2 text-info"></i> Confirm Password
                                                <span class="text-danger"><strong>*</strong></span>
                                            </label>
                                            <x-input-error :messages="$errors->get('password_confirmation')" class="text-danger mt-2" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" placeholder="Phone Number"
                                                name="phone">
                                            <label>
                                                <i class="ti ti-phone me-2 text-info"></i> Phone Number
                                                <span class="text-danger"><strong>*</strong></span>
                                            </label>
                                            <x-input-error :messages="$errors->get('phone')" class="text-danger mt-2" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" placeholder="Alternate Phone Number"
                                                name="alternate_phone">
                                            <label>
                                                <i class="ti ti-phone me-2 text-info"></i> Alternate Phone
                                            </label>
                                            <x-input-error :messages="$errors->get('alternate_phone')" class="text-danger mt-2" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr class="my-4">
                            <!-- ================= COURSE & PAYMENT ================= -->
                            <h6 class="fw-semibold text-info mb-3">Course & Payment Details</h6>
                            <div class="row g-3">
                                <div class="col-md-8">
                                    <div class="form-floating">
                                        <select class="form-select" id="courseSelect" name="course_ids[]" multiple>
                                            {{-- <option value="" disabled selected>Select a course</option> --}}
                                            @foreach ($courses as $course)
                                                @php
                                                    $schedule = $course->schedule;
                                                    if ($schedule) {
                                                        $days = collect(explode('-', $schedule->study_day))
                                                            ->map(fn($day) => ucfirst($day))
                                                            ->implode(' • ');
                                                        $shift = ucfirst($schedule->shift);

                                                        $start = \Carbon\Carbon::parse($schedule->start_time)->format(
                                                            'g:i',
                                                        );
                                                        $end = \Carbon\Carbon::parse($schedule->end_time)->format(
                                                            'g:i A',
                                                        );

                                                        $startDate = \Carbon\Carbon::parse(
                                                            $schedule->start_date,
                                                        )->format('d M, Y');
                                                    }
                                                @endphp

                                                <option value="{{ $course->id }}" data-price="{{ $course->price }}">
                                                    ${{ $course->price }} |
                                                    {{ $course->title }} |
                                                    {{ $days ?? 'No Schedule' }} |
                                                    {{ $shift ?? '' }}
                                                    ({{ $start ?? '' }} – {{ $end ?? '' }})
                                                    {{-- {{ $startDate ?? '' }} --}}
                                                </option>
                                            @endforeach
                                        </select>
                                        <x-input-error :messages="$errors->get('course_id')" class="text-danger mt-2" />
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-floating">
                                        <select class="form-select" id="paymentOption" name="payment_option" required>
                                            <option value="" disabled selected>Select Payment Option</option>
                                            <option value="free">Free (No Payment Required)</option>
                                            <option value="normal">Pay Full (No Discount)</option>
                                            <option value="full">Pay Full ($10 Discount)</option>
                                            <option value="half">Pay Half (+$20 Charge)</option>
                                            <option value="multi">Multi Course ($25 Discount)</option>
                                        </select>
                                        <label>
                                            <i class="ti ti-credit-card me-2 text-info"></i> Payment Option
                                            <span class="text-danger"><strong>*</strong></span>
                                        </label>
                                    </div>
                                </div>
                                {{-- <div class="col-md-4">
                                    <div class="form-floating">
                                        <input type="number" class="form-control" id="discount" placeholder="Discount"
                                            name="discount">
                                        <label>
                                            <i class="ti ti-discount me-2 text-info"></i> Discount Amount ($ Optional)
                                        </label>
                                        <x-input-error :messages="$errors->get('discount')" class="text-danger mt-2" />
                                    </div>
                                </div> --}}
                            </div>

                            <div class="row g-3 mt-1">
                                <div class="col-md-6">
                                    <div class="form-floating" style="background-color: rgb(234, 239, 244);">
                                        <input type="number" class="form-control" id="totalAmount"
                                            placeholder="Total Amount" name="total_amount" readonly>
                                        <label>
                                            <i class="ti ti-currency-dollar me-2 text-info"></i> Total Amount
                                        </label>
                                        <x-input-error :messages="$errors->get('total_amount')" class="text-danger mt-2" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating" style="background-color: rgb(234, 239, 244);">
                                        <input type="number" class="form-control" id="paidAmount"
                                            placeholder="Paid Amount" name="paid_amount" readonly>
                                        <label>
                                            <i class="ti ti-cash me-2 text-info"></i> Pay Amount
                                        </label>
                                        <x-input-error :messages="$errors->get('paid_amount')" class="text-danger mt-2" />
                                    </div>
                                </div>
                            </div>

                            <div class="row g-3 mt-1">
                                <div class="col-md-6">
                                    <div class="form-floating" style="background-color: rgb(234, 239, 244);">
                                        <input type="number" class="form-control" id="remainingAmount"
                                            placeholder="Remaining Amount" readonly>
                                        <label>
                                            <i class="ti ti-report-money me-2 text-info"></i> Remaining Amount
                                        </label>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <select class="form-select" id="paymentStatusDisplay" disabled>
                                            <option value="" disabled selected>Select payment status</option>
                                            <option value="paid">Paid</option>
                                            <option value="half_paid">Half Paid</option>
                                            <option value="unpaid">Unpaid</option>
                                            <option value="free">Free</option>
                                        </select>

                                        <!-- Hidden input to send value -->
                                        <input type="hidden" name="payment_status" id="paymentStatus">
                                        <label>
                                            <i class="ti ti-wallet me-2 text-info"></i> Payment Status
                                        </label>
                                        <x-input-error :messages="$errors->get('payment_status')" class="text-danger mt-2" />
                                    </div>
                                </div>
                            </div>

                            <!-- ================= SUBMIT ================= -->
                            <div class="text-end mt-4">
                                <button type="submit" class="btn btn-info rounded-pill px-4 py-2">
                                    <i class="ti ti-send me-2"></i> Submit Registration
                                </button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {

            // ==============================
            // INIT SELECT2
            // ==============================
            $('#courseSelect').select2();
            $('#studentSelect').select2({
                width: '100%'
            });


            // ==============================
            // STUDENT TYPE TOGGLE
            // ==============================
            $('#studentType').on('change', function() {
                toggleStudentType($(this).val());
                resetPaymentFields();
            });


            function toggleStudentType(type) {
                if (type === 'existing') {
                    $('#existingStudentWrapper').removeClass('d-none');
                    $('#newStudentWrapper').addClass('d-none');
                    $('#newStudentWrapper input')
                        .prop('required', false)
                        .prop('disabled', true);
                } else {
                    $('#existingStudentWrapper').addClass('d-none');
                    $('#newStudentWrapper').removeClass('d-none');
                    $('#newStudentWrapper input')
                        .prop('disabled', false);
                }
            }


            // ==============================
            // RESET PAYMENT FIELDS
            // ==============================
            function resetPaymentFields() {
                $('#paymentOption').val(null).trigger('change');
                $('#totalAmount').val('');
                $('#paidAmount').val('');
                $('#remainingAmount').val('');
                $('#paymentStatusDisplay').val('');
                $('#paymentStatus').val('');
            }


            // ==============================
            // MAIN CALCULATION
            // ==============================
            function calculateAmounts() {

                let selectedCourses = $('#courseSelect').find(':selected');
                let courseCount = selectedCourses.length;

                let totalPrice = getTotalPrice(selectedCourses);
                let paymentOption = $('#paymentOption').val();

                handleMultiCourseUI(courseCount);
                paymentOption = enforceMultiLogic(courseCount, paymentOption);

                let {
                    discount,
                    extraCharge
                } = calculateAdjustments(paymentOption, courseCount);

                let total = Math.max(0, totalPrice - discount + extraCharge);

                if (paymentOption === 'free') {
                    $('#paidAmount').val(0);
                    $('#remainingAmount').val(0);
                }

                if (paymentOption === 'free') {
                    total = 0;
                }

                let paid = calculatePaidAmount(paymentOption, total);
                let remaining = total - paid;

                updateUI(total, paid, remaining, discount, extraCharge);
                updatePaymentStatus(paid, remaining);
            }


            // ==============================
            // GET TOTAL COURSE PRICE
            // ==============================
            function getTotalPrice(selectedCourses) {
                let total = 0;

                selectedCourses.each(function() {
                    total += parseFloat($(this).data('price')) || 0;
                });

                return total;
            }


            // ==============================
            // MULTI COURSE UI NOTE
            // ==============================
            function handleMultiCourseUI(courseCount) {
                $('#multiNote').remove();

                if (courseCount >= 2) {
                    $('#paymentOption').closest('.col-md-4').append(`
                    <small id="multiNote" class="text-success d-block mt-1">
                        🎉 $25 discount applied for multiple courses
                    </small>
                `);
                }
            }


            // ==============================
            // FORCE MULTI OPTION LOGIC
            // ==============================
            function enforceMultiLogic(courseCount, paymentOption) {

                if (courseCount >= 2 && paymentOption !== 'multi') {

                    // Disable other options
                    $('#paymentOption option')
                        .not('[value="multi"]')
                        .prop('disabled', true);

                    $('#paymentOption').css('background-color', '#e9ecef');

                    $('#paymentOption').val('multi');
                    return 'multi';
                }

                // Enable all options again
                $('#paymentOption option')
                    .not('[value="multi"]')
                    .prop('disabled', false);

                $('#paymentOption').css('background-color', '');

                // Reset if invalid
                if (courseCount < 2 && paymentOption === 'multi') {
                    $('#paymentOption').val('normal');
                    return 'normal';
                }

                return paymentOption;
            }


            // ==============================
            // CALCULATE DISCOUNT & EXTRA
            // ==============================
            function calculateAdjustments(paymentOption, courseCount) {

                let discount = 0;
                let extraCharge = 0;

                if (paymentOption === 'free') {
                    discount = 999999; // force total to 0 (safe fallback)
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

                return {
                    discount,
                    extraCharge
                };
            }


            // ==============================
            // CALCULATE PAID AMOUNT
            // ==============================
            function calculatePaidAmount(paymentOption, total) {
                if (paymentOption === 'half') return total / 2;
                if (paymentOption === 'free') return 0;
                return total;
            }


            // ==============================
            // UPDATE UI VALUES
            // ==============================
            function updateUI(total, paid, remaining, discount, extraCharge) {

                $('#totalAmount').val(total.toFixed(2));
                $('#paidAmount').val(paid.toFixed(2));
                $('#remainingAmount').val(remaining.toFixed(2));

                $('#discountField').val(discount.toFixed(2));
                $('#extraChargeField').val(extraCharge.toFixed(2));
            }


            // ==============================
            // PAYMENT STATUS
            // ==============================
            function updatePaymentStatus(paid, remaining) {

                let status = '';

                let paymentOption = $('#paymentOption').val();

                if (paymentOption === 'free') {
                    status = 'free';
                } else if (paid === 0) {
                    status = 'unpaid';
                } else if (remaining === 0) {
                    status = 'paid';
                } else {
                    status = 'half_paid';
                }

                $('#paymentStatusDisplay').val(status);
                $('#paymentStatus').val(status);
            }


            // ==============================
            // EVENTS
            // ==============================
            $('#courseSelect').on('change', calculateAmounts);
            $('#paymentOption').on('change', calculateAmounts);

        });
    </script>
@endpush
