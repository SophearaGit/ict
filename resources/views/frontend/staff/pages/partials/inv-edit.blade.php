<div class="inv-detail-wrap">
    <div class="inv-topbar no-print">
        <div class="inv-topbar-left">
            <h4 class="mb-0">
                Edit Invoice
            </h4>
        </div>
        <div class="inv-topbar-actions">
            <button class="inv-btn inv-btn-outline btn_cancel_edit" data-id="{{ $invoice->id }}">
                <i class="ti ti-arrow-left"></i>
                Cancel
            </button>
            <button class="inv-btn inv-btn-success btn_save_invoice" data-id="{{ $invoice->id }}">
                <i class="ti ti-device-floppy"></i>
                Save Changes
            </button>
        </div>
    </div>
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form id="invoiceEditForm">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            Student
                        </label>
                        <input class="form-control" readonly value="{{ $invoice->student->name }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            Course
                        </label>
                        <input class="form-control" readonly value="{{ $invoice->course->title }}">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <label>Price</label>
                        <input type="number" step="0.01" name="price" id="price"
                            class="form-control invoice-calc" value="{{ $invoice->price }}">
                    </div>
                    <div class="col-md-4">
                        <label>Discount</label>
                        <input type="number" step="0.01" name="discount" id="discount"
                            class="form-control invoice-calc" value="{{ $invoice->discount }}">
                    </div>
                    <div class="col-md-4">
                        <label>Extra Charge</label>
                        <input type="number" step="0.01" name="extra_charge" id="extra_charge"
                            class="form-control invoice-calc" value="{{ $invoice->extra_charge }}">
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-4">
                        <h6>Total</h6>
                        <h3 id="invoice_total">
                            ${{ number_format($invoice->total_amount, 2) }}
                        </h3>
                    </div>
                    <div class="col-md-4">
                        <h6>Paid</h6>
                        <h3 class="text-success">
                            ${{ number_format($invoice->paid_amount, 2) }}
                        </h3>
                    </div>
                    <div class="col-md-4">
                        <h6>Remaining</h6>
                        <h3 id="invoice_remaining">
                            ${{ number_format($invoice->remaining_amount, 2) }}
                        </h3>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    function calculateInvoice() {
        let price = parseFloat($('#price').val()) || 0;
        let discount = parseFloat($('#discount').val()) || 0;
        let extra = parseFloat($('#extra_charge').val()) || 0;
        let paid = {
            {
                $invoice - > paid_amount
            }
        };
        let total = (price - discount) + extra;
        let remaining = total - paid;
        if (remaining < 0) {
            remaining = 0;
        }
        $('#invoice_total').html('$' + total.toFixed(2));
        $('#invoice_remaining').html('$' + remaining.toFixed(2));
    }
    $(document).on('keyup change', '.invoice-calc', calculateInvoice);
</script>
<script>
    $(document).off('click', '.btn_save_invoice');
    $(document).on('click', '.btn_save_invoice', function(e) {
        e.preventDefault();
        $.ajax({
            url: "{{ route('staff.invoice.update', $invoice->id) }}",
            type: 'POST',
            data: $('#invoiceEditForm').serialize(),
            success: function(res) {
                iziToast.success({
                    message: res.message
                });
                $('.btn_view_invoice_detail[data-invoice-id="{{ $invoice->id }}"]').trigger(
                    'click');
            },
            error: function(xhr) {
                iziToast.error({
                    message: xhr.responseJSON.message
                });
            }
        });
    });
</script>
<script>
    $(document).on('click', '.btn_cancel_edit', function() {
        $('.btn_view_invoice_detail[data-invoice-id="{{ $invoice->id }}"]').trigger('click');
    });
</script>
