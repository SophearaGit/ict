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
                            <h4 class="fw-bold mb-1">Student Registration</h4>
                            <p class="text-muted mb-0">
                                Register a new student and manage their course payment.
                            </p>
                            <hr class="mt-3">
                        </div>

                        <form action="{{ route('staff.student.registration.submit') }}" method="POST">
                            @csrf

                            <!-- ================= STUDENT TYPE ================= -->
                            <h6 class="fw-semibold text-info mb-3">Student Selection</h6>


                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
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
                            </div>

                            <div class="row g-3 mb-3 d-none" id="existingStudentWrapper">
                                <div class="col-md-6">
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
                                            </label>
                                            <x-input-error :messages="$errors->get('name')" class="text-danger mt-2" />
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="email" class="form-control" placeholder="Email" name="email">
                                            <label>
                                                <i class="ti ti-mail me-2 text-info"></i> Email Address
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
                                            </label>
                                            <x-input-error :messages="$errors->get('password_confirmation')" class="text-danger mt-2" />
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
                                        <select class="form-select" id="courseSelect" name="course_id">
                                            <option value="" disabled selected>Select a course</option>
                                            @foreach ($courses as $course)
                                                <option value="{{ $course->id }}" data-price="{{ $course->price }}">
                                                    ${{ $course->price }} |
                                                    {{ $course->title }} |
                                                    {{ $course->schedule->study_day }} |
                                                    {{ $course->schedule->shift }} |
                                                    {{ \Carbon\Carbon::parse($course->schedule->start_time)->format('h:i A') }}
                                                    -
                                                    {{ \Carbon\Carbon::parse($course->schedule->end_time)->format('h:i A') }}
                                                    |
                                                    {{ \Carbon\Carbon::parse($course->schedule->start_date)->format('d M, Y') }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <x-input-error :messages="$errors->get('course_id')" class="text-danger mt-2" />
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-floating">
                                        <input type="number" class="form-control" id="discount" placeholder="Discount"
                                            name="discount">
                                        {{-- <label>
                                            <i class="ti ti-discount me-2 text-info"></i> Discount % (Optional)

                                        </label> --}}
                                        <label>
                                            <i class="ti ti-discount me-2 text-info"></i> Discount Amount ($ Optional)
                                        </label>
                                        <x-input-error :messages="$errors->get('discount')" class="text-danger mt-2" />
                                    </div>
                                </div>
                            </div>

                            <div class="row g-3 mt-1">
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="number" class="form-control" id="totalAmount"
                                            placeholder="Total Amount" name="total_amount" readonly>
                                        <label>
                                            <i class="ti ti-currency-dollar me-2 text-info"></i> Total Amount
                                        </label>
                                        <x-input-error :messages="$errors->get('total_amount')" class="text-danger mt-2" />
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="number" class="form-control" id="paidAmount"
                                            placeholder="Paid Amount" name="paid_amount">
                                        <label>
                                            <i class="ti ti-cash me-2 text-info"></i> Paid Amount
                                        </label>
                                        <x-input-error :messages="$errors->get('paid_amount')" class="text-danger mt-2" />
                                    </div>
                                </div>
                            </div>

                            <div class="row g-3 mt-1">
                                <div class="col-md-6">
                                    <div class="form-floating">
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

            $('#courseSelect').select2();
            $('#studentSelect').select2();

            // Toggle Student Type
            $('#studentType').on('change', function() {
                let type = $(this).val();

                if (type === 'existing') {
                    $('#existingStudentWrapper').removeClass('d-none');
                    $('#newStudentWrapper').addClass('d-none');

                    // Disable new student inputs
                    $('#newStudentWrapper input').prop('required', false);
                    $('#newStudentWrapper input').prop('disabled', true);
                } else {
                    $('#existingStudentWrapper').addClass('d-none');
                    $('#newStudentWrapper').removeClass('d-none');
                    $('#newStudentWrapper input').prop('disabled', false);

                }
            });

            function calculateAmounts() {

                let selectedOption = $('#courseSelect').find(':selected');
                let price = parseFloat(selectedOption.data('price')) || 0;
                let discount = parseFloat($('#discount').val()) || 0;
                let paid = parseFloat($('#paidAmount').val()) || 0;

                // Prevent negative discount
                if (discount < 0) discount = 0;

                // Prevent discount greater than price
                if (discount > price) {
                    discount = price;
                    $('#discount').val(price.toFixed(2));
                }

                // Calculate total after fixed discount
                let total = price - discount;

                // Prevent paid > total
                if (paid > total) {
                    paid = total;
                    $('#paidAmount').val(total.toFixed(2));
                }

                let remaining = total - paid;

                // Set calculated values
                $('#totalAmount').val(total.toFixed(2));
                $('#remainingAmount').val(remaining.toFixed(2));

                // Auto select payment status
                let status = '';

                if (paid == 0) {
                    status = 'unpaid';
                } else if (remaining == 0) {
                    status = 'paid';
                } else {
                    status = 'half_paid';
                }

                $('#paymentStatusDisplay').val(status);
                $('#paymentStatus').val(status);
            }

            // Trigger events
            $('#courseSelect').on('change', calculateAmounts);
            $('#discount').on('keyup change', calculateAmounts);
            $('#paidAmount').on('keyup change', calculateAmounts);
        });
    </script>
@endpush
