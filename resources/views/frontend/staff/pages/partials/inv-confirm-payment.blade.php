<div class="modal-content">
    <div class="modal-header d-flex align-items-center">
        <h4 class="modal-title">
            Confirm Payment - {{ $invoice->student->name }}
        </h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
    </div>

    <form action="{{ route('staff.invoice.confirm-payment', $invoice->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="modal-body">

            <!-- Course Info -->
            <div class="mb-3">
                <strong>Course:</strong> {{ $invoice->course->title }} <br>
                <strong>Schedule:</strong>
                {{ ucfirst($invoice->course->schedule->study_day ?? '') }}
                ({{ $invoice->course->schedule->start_time ?? '' }} -
                {{ $invoice->course->schedule->end_time ?? '' }})
            </div>

            <hr>

            <!-- Price Details -->
            <div class="row g-3">

                <div class="col-md-6">
                    <label class="form-label">Original Price</label>
                    <input type="text" class="form-control" value="{{ number_format($invoice->course->price, 2) }}"
                        readonly>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Discount</label>
                    <input type="text" class="form-control" value="{{ number_format($invoice->discount, 2) }}"
                        readonly>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Total Amount</label>
                    <input type="text" id="modalTotal" class="form-control"
                        value="{{ number_format($invoice->total_amount, 2) }}" readonly>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Already Paid</label>
                    <input type="text" id="modalPaid" class="form-control"
                        value="{{ number_format($invoice->paid_amount, 2) }}" readonly>
                </div>

                <div class="col-md-6">
                    <label class="form-label text-danger">Remaining Balance</label>
                    <input type="text" id="modalRemaining" class="form-control"
                        value="{{ number_format($invoice->remaining_amount, 2) }}" readonly>
                </div>

                <!-- New Payment -->
                @if ($invoice->remaining_amount > 0)
                    <div class="col-md-6">
                        <label class="form-label">Pay Now</label>
                        <input type="number" step="0.01" min="0" max="{{ $invoice->remaining_amount }}"
                            name="additional_payment" id="additionalPayment" class="form-control"
                            placeholder="Enter amount to pay">
                    </div>
                @endif
            </div>

        </div>

        <div class="modal-footer">

            @if ($invoice->remaining_amount > 0)
                <button type="submit" class="btn btn-info">
                    <i class="ti ti-cash me-2"></i> Confirm
                </button>
            @endif

            <button type="button" class="btn btn-light-danger text-danger" data-bs-dismiss="modal">
                Close
            </button>
        </div>
    </form>
</div>

<script>
    $(document).on('keyup change', '#additionalPayment', function() {

        let originalRemaining = parseFloat("{{ $invoice->remaining_amount }}") || 0;
        let payNow = parseFloat($(this).val()) || 0;

        // Prevent negative
        if (payNow < 0) {
            payNow = 0;
            $(this).val(0);
        }

        // Prevent overpay
        if (payNow > originalRemaining) {
            payNow = originalRemaining;
            $(this).val(originalRemaining.toFixed(2));
        }

        let newRemaining = originalRemaining - payNow;

        $('#modalRemaining').val(newRemaining.toFixed(2));
    });
</script>
