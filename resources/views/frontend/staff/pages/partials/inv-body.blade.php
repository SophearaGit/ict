{{-- ============================================================
     inv-body.blade.php  —  Invoice detail partial
     ============================================================ --}}
<style>
    @import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&family=Syne:wght@600;700;800&display=swap');

    .inv-detail-wrap {
        font-family: 'DM Sans', sans-serif;
        padding: 24px 28px;
        background: #f8f8fb;
    }

    .inv-topbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
    }

    .inv-topbar-left {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .inv-topbar-actions {
        display: flex;
        gap: 8px;
    }

    .inv-status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .inv-status-badge::before {
        content: '';
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: currentColor;
    }

    .inv-status-badge.paid {
        background: #dcfce7;
        color: #16a34a;
    }

    .inv-status-badge.half {
        background: #fef9c3;
        color: #ca8a04;
    }

    .inv-status-badge.unpaid {
        background: #fee2e2;
        color: #dc2626;
    }

    .inv-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 16px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 500;
        cursor: pointer;
        border: none;
        transition: all 0.15s;
        font-family: 'DM Sans', sans-serif;
        text-decoration: none;
    }

    .inv-btn-dark {
        background: #0f0e17;
        color: #fff;
    }

    .inv-btn-dark:hover {
        background: #1d1c2e;
        color: #fff;
    }

    .inv-btn-success {
        background: #16a34a;
        color: #fff;
    }

    .inv-btn-success:hover {
        background: #15803d;
        color: #fff;
    }

    .inv-btn-outline {
        background: #fff;
        color: #444;
        border: 1px solid #e2e2e8;
    }

    .inv-btn-outline:hover {
        background: #f4f4f8;
    }

    /* Only these classes affect the screen view wrapper — print uses inline styles */
    #printableArea {
        background: #fff;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 2px 16px rgba(0, 0, 0, 0.06);
    }

    @media print {

        .inv-topbar,
        .no-print {
            display: none !important;
        }

        .inv-detail-wrap {
            padding: 0;
            background: #fff;
        }

        #printableArea {
            box-shadow: none;
            border-radius: 0;
        }
    }
</style>
@php
    $editMode = false;
@endphp
<div class="inv-detail-wrap no-select">
    {{-- ── TOPBAR (screen only) ── --}}
    <div class="inv-topbar no-print">
        <div class="inv-topbar-left">
            <a href="{{ route('staff.student.registration') }}" class="inv-btn inv-btn-dark">
                <i class="ti ti-user-plus"></i> Registration
            </a>
            @php
                $badgeClass = match ($invoice->payment_status) {
                    'paid' => 'paid',
                    'half_paid' => 'half',
                    default => 'unpaid',
                };
                $badgeLabel = match ($invoice->payment_status) {
                    'paid' => 'Fully Paid',
                    'half_paid' => 'Half Paid',
                    default => 'Unpaid',
                };
            @endphp
            <span class="inv-status-badge {{ $badgeClass }}">{{ $badgeLabel }}</span>
        </div>
        <div class="inv-topbar-actions">
            <button type="button" class="inv-btn inv-btn-dark btn_enable_edit">
                <i class="ti ti-edit"></i>
                Edit
            </button>
            <button type="button" class="inv-btn inv-btn-success d-none btn_save_invoice">
                <i class="ti ti-device-floppy"></i>
                Save Changes
            </button>
            <button type="button" class="inv-btn inv-btn-outline d-none btn_cancel_edit">
                Cancel
            </button>
            @if ($invoice->payment_status !== 'paid')
                <a href="javascript:void(0)" data-url="{{ route('staff.invoice.confirm-payment', $invoice->id) }}"
                    data-remaining="{{ $invoice->remaining_amount }}"
                    class="inv-btn inv-btn-success btn_confirm_payment">
                    <i class="ti ti-cash"></i>
                    Confirm Payment
                </a>
            @endif
            <button class="inv-btn inv-btn-outline print-page">
                <i class="ti ti-printer"></i>
                Print
            </button>
        </div>
    </div>
    {{-- ── PRINTABLE INVOICE CARD ── --}}
    {{-- ALL styles inside here are inline so print renders correctly --}}
    <div id="printableArea">
        <div style="font-family:Arial,Helvetica,sans-serif;max-width:860px;margin:0 auto;background:#fff;">
            {{-- Header --}}
            <div
                style="background:#0f0e17;padding:26px 36px;display:flex;justify-content:space-between;align-items:center;">
                <img src="{{ asset('/frontend/assets/ictImg/logo/ictBannerLogo.png') }}" class="dark-logo"
                    alt="ICT Logo" style="height:54px;width:auto;border-radius:6px;">
                <div style="text-align:right;">
                    <div
                        style="font-family:Arial,sans-serif;font-size:34px;font-weight:900;color:#fff;letter-spacing:3px;line-height:1;">
                        INVOICE
                    </div>
                    <div style="font-size:12px;color:rgba(255,255,255,0.45);margin-top:5px;letter-spacing:0.5px;">
                        #{{ $invoice->invoice_code }}
                    </div>
                </div>
            </div>
            {{-- Meta strip --}}
            @php $count = $invoice->payments->count(); @endphp
            <div
                style="background:#f4f4f8;padding:14px 36px;display:flex;align-items:center;gap:32px;border-bottom:1px solid #e8e8f0;">
                <div>
                    <div
                        style="font-size:10px;font-weight:700;letter-spacing:0.8px;text-transform:uppercase;color:#aaa;margin-bottom:3px;">
                        Invoice Date</div>
                    <div style="font-size:13px;font-weight:700;color:#222;">{{ $invoice->created_at->format('d M Y') }}
                    </div>
                </div>
                @if ($count === 1)
                    @if ($invoice->payment_status === 'half_paid')
                        <div>
                            <div
                                style="font-size:10px;font-weight:700;letter-spacing:0.8px;text-transform:uppercase;color:#aaa;margin-bottom:3px;">
                                Half-Paid</div>
                            <div style="font-size:13px;font-weight:700;color:#ca8a04;">
                                {{ $invoice->payments->first()->created_at->format('d M Y') }}
                            </div>
                        </div>
                    @elseif ($invoice->payment_status === 'paid')
                        <div>
                            <div
                                style="font-size:10px;font-weight:700;letter-spacing:0.8px;text-transform:uppercase;color:#aaa;margin-bottom:3px;">
                                Paid On</div>
                            <div style="font-size:13px;font-weight:700;color:#16a34a;">
                                {{ $invoice->payments->first()->created_at->format('d M Y') }}
                            </div>
                        </div>
                    @endif
                @elseif ($count > 1)
                    <div>
                        <div
                            style="font-size:10px;font-weight:700;letter-spacing:0.8px;text-transform:uppercase;color:#aaa;margin-bottom:3px;">
                            Half-Paid</div>
                        <div style="font-size:13px;font-weight:700;color:#ca8a04;">
                            {{ $invoice->payments->first()->created_at->format('d M Y') }}
                        </div>
                    </div>
                    <div>
                        <div
                            style="font-size:10px;font-weight:700;letter-spacing:0.8px;text-transform:uppercase;color:#aaa;margin-bottom:3px;">
                            Full-Paid</div>
                        <div style="font-size:13px;font-weight:700;color:#16a34a;">
                            {{ $invoice->payments->last()->created_at->format('d M Y') }}
                        </div>
                    </div>
                @endif
                <div style="margin-left:auto;text-align:right;">
                    <div
                        style="font-size:10px;font-weight:700;letter-spacing:0.8px;text-transform:uppercase;color:#aaa;margin-bottom:3px;">
                        {{ $invoice->payment_status === 'paid' ? 'Fully Paid' : 'To Pay' }}
                    </div>
                    <div
                        style="font-size:20px;font-weight:800;color:{{ $invoice->payment_status === 'paid' ? '#16a34a' : '#0f0e17' }};">
                        ${{ number_format($invoice->remaining_amount, 2) }}
                    </div>
                </div>
            </div>
            {{-- Parties --}}
            <div style="padding:28px 36px 0;display:flex;justify-content:space-between;gap:24px;margin-bottom:24px;">
                <div>
                    <div
                        style="font-size:10px;font-weight:700;letter-spacing:1px;text-transform:uppercase;color:#aaa;margin-bottom:8px;">
                        Invoice To</div>
                    <div
                        style="font-size:18px;font-weight:800;color:#0f0e17;margin-bottom:5px;text-transform:capitalize;">
                        {{ $invoice->student->name }}
                    </div>
                    <div style="font-size:12px;color:#666;">ICT Professional Training Center</div>
                </div>
                <div style="text-align:right;">
                    <div
                        style="font-size:10px;font-weight:700;letter-spacing:1px;text-transform:uppercase;color:#aaa;margin-bottom:8px;">
                        From</div>
                    <div style="font-size:18px;font-weight:800;color:#0f0e17;margin-bottom:5px;">ICT Skills Center</div>
                    <div style="font-size:12px;color:#666;line-height:1.8;">
                        House No. 240B, Street 132<br>
                        Sangkat Teuk Laok 01, Khan Toul Kork<br>
                        Phnom Penh<br>
                        +855 097-702-175 / 096-639-3985
                    </div>
                </div>
            </div>
            {{-- Course table --}}
            <div style="padding:0 36px;">
                <table style="width:100%;border-collapse:collapse;">
                    <thead>
                        <tr style="background:#0f0e17;">
                            <th
                                style="padding:13px 16px;font-size:11px;font-weight:700;letter-spacing:0.8px;text-transform:uppercase;color:rgba(255,255,255,0.6);text-align:left;">
                                Course</th>
                            <th
                                style="padding:13px 16px;font-size:11px;font-weight:700;letter-spacing:0.8px;text-transform:uppercase;color:rgba(255,255,255,0.6);text-align:left;">
                                Schedule</th>
                            <th
                                style="padding:13px 16px;font-size:11px;font-weight:700;letter-spacing:0.8px;text-transform:uppercase;color:rgba(255,255,255,0.6);text-align:right;">
                                Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($invoice->items as $item)
                            <tr style="border-bottom:1px solid #f0f0f5;">
                                <td style="padding:14px 16px;vertical-align:top;">
                                    <a href="{{ route('staff.courses.show', $item->course->id) }}"
                                        style="font-size:13px;font-weight:700;color:#0f0e17;text-decoration:none;text-transform:capitalize;">
                                        {{ $item->course->title }}
                                    </a>
                                </td>
                                <td style="padding:14px 16px;vertical-align:top;">
                                    <div style="font-size:13px;color:#333;text-transform:capitalize;">
                                        {{ $item->course->schedule->study_day }}
                                    </div>
                                    <div style="font-size:12px;color:#888;margin-top:2px;">
                                        {{ \Carbon\Carbon::parse($item->course->schedule->start_time)->format('h:i') }}
                                        –
                                        {{ \Carbon\Carbon::parse($item->course->schedule->end_time)->format('h:i A') }}
                                    </div>
                                </td>
                                <td
                                    style="padding:14px 16px;font-size:13px;font-weight:700;color:#0f0e17;text-align:right;">
                                    <span class="view-mode">
                                        ${{ number_format($invoice->price, 2) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{-- Summary --}}
            <div style="padding:24px 36px;display:flex;justify-content:space-between;gap:32px;">
                {{-- Terms --}}
                <div
                    style="flex:1;background:#fff8f8;border-left:3px solid #ef4444;border-radius:0 8px 8px 0;padding:14px 16px;align-self:flex-start;">
                    <div
                        style="font-size:11px;font-weight:700;letter-spacing:0.5px;text-transform:uppercase;color:#ef4444;margin-bottom:6px;">
                        Terms & Conditions</div>
                    <div style="font-size:12px;color:#555;line-height:1.6;">All payments are strictly non-refundable.
                    </div>
                </div>
                {{-- Breakdown --}}
                <div style="width:260px;flex-shrink:0;">
                    <div
                        style="display:flex;justify-content:space-between;padding:7px 0;border-bottom:1px solid #f0f0f5;">
                        <span>Full Price</span>
                        <span class="view-mode">
                            ${{ number_format($invoice->price, 2) }}
                        </span>
                        <input name="price" id="price" type="number"
                            class="form-control form-control-sm edit-mode d-none invoice-calc"
                            value="{{ $invoice->price }}">
                    </div>
                    <div
                        style="display:flex;justify-content:space-between;padding:7px 0;border-bottom:1px solid #f0f0f5;">
                        <span>Discount</span>
                        <span class="view-mode">
                            ${{ number_format($invoice->discount, 2) }}
                        </span>
                        <input name="discount" id="discount" type="number"
                            class="form-control form-control-sm edit-mode d-none invoice-calc"
                            value="{{ $invoice->discount }}">
                    </div>
                    <div
                        style="display:flex;justify-content:space-between;padding:7px 0;border-bottom:1px solid #f0f0f5;">
                        <span>Extra Charge</span>
                        <span class="view-mode">
                            ${{ number_format($invoice->extra_charge, 2) }}
                        </span>
                        <input name="extra_charge" id="extra_charge" type="number"
                            class="form-control form-control-sm edit-mode d-none invoice-calc"
                            value="{{ $invoice->extra_charge }}">
                    </div>
                    <div
                        style="display:flex;justify-content:space-between;padding:7px 0;border-bottom:1px solid #f0f0f5;">
                        <strong>Total</strong>
                        <strong id="invoice_total">
                            ${{ number_format($invoice->total_amount, 2) }}
                        </strong>
                    </div>
                    <div style="display:flex;justify-content:space-between;padding:7px 0;">
                        <span>Paid</span>
                        <span>
                            ${{ number_format($invoice->paid_amount, 2) }}
                        </span>
                    </div>
                    <div
                        style="margin-top:14px;background:#0f0e17;border-radius:12px;padding:18px;display:flex;justify-content:space-between;">
                        <span style="color:white;">Remaining</span>
                        <strong id="invoice_remaining" style="color:white;">
                            ${{ number_format($invoice->remaining_amount, 2) }}
                        </strong>
                    </div>
                </div>
            </div>
            {{-- Footer --}}
            <div
                style="padding:18px 36px;background:#f4f4f8;border-top:1px solid #e8e8f0;display:flex;justify-content:space-between;align-items:center;">
                <div style="font-size:15px;font-weight:800;color:#0f0e17;">Thank you for your business!</div>
                <div style="display:flex;gap:20px;font-size:11px;color:#888;">
                    <span>📞 +855 097-702-175</span>
                    <span>🌐 ictskills.center</span>
                    <span>📍 Khan Toul Kork, Phnom Penh</span>
                </div>
            </div>
        </div>
    </div>{{-- /#printableArea --}}
</div>
<script>
    $(document).on('click', '.btn_save_invoice', function(e) {
        e.preventDefault();
        $.ajax({
            url: "{{ route('staff.invoice.update', $invoice->id) }}",
            type: "POST",
            data: {
                _token: csrf_token,
                _method: "PUT",
                price: $('#price').val(),
                discount: $('#discount').val(),
                extra_charge: $('#extra_charge').val()
            },
            beforeSend: function() {
                $('.btn_save_invoice')
                    .prop('disabled', true)
                    .html('<i class="ti ti-loader-2"></i> Saving...');
            },
            success: function(res) {
                $('.btn_save_invoice')
                    .prop('disabled', false)
                    .html('<i class="ti ti-device-floppy"></i> Save Changes');
                iziToast.success({
                    message: res.message,
                    position: 'bottomRight'
                });
                $('.btn_view_invoice_detail[data-invoice-id="{{ $invoice->id }}"]').trigger(
                    'click');
            },
            error: function(xhr) {
                $('.btn_save_invoice')
                    .prop('disabled', false)
                    .html('<i class="ti ti-device-floppy"></i> Save Changes');
                iziToast.error({
                    message: xhr.responseJSON?.message ?? 'Something went wrong.',
                    position: 'bottomRight'
                });
            }
        });
    });

    function calculateInvoice() {
        let price = parseFloat($('#price').val()) || 0;
        let discount = parseFloat($('#discount').val()) || 0;
        if (discount > price) {
            iziToast.error({
                message: 'Discount cannot exceed the price.'
            });
            return;
        }
        let extra = parseFloat($('#extra_charge').val()) || 0;
        let paid = {
            {
                $invoice - > paid_amount
            }
        };
        let total = (price - discount) + extra;
        let remaining = Math.max(0, total - paid);
        $('#invoice_total').text('$' + total.toFixed(2));
        $('#invoice_remaining').text('$' + remaining.toFixed(2));
    }
    $(document).on('keyup change', '.invoice-calc', calculateInvoice);
</script>
<script>
    $(document).on('click', '.btn_enable_edit', function() {
        $('.view-mode').hide();
        $('.edit-mode').removeClass('d-none');
        $('.btn_enable_edit').addClass('d-none');
        $('.btn_save_invoice').removeClass('d-none');
        $('.btn_cancel_edit').removeClass('d-none');
    });
    $(document).on('click', '.btn_cancel_edit', function() {
        $('#price').val('{{ $invoice->price }}');
        $('#discount').val('{{ $invoice->discount }}');
        $('#extra_charge').val('{{ $invoice->extra_charge }}');
        calculateInvoice();
        $('.view-mode').show();
        $('.edit-mode').addClass('d-none');
        $('.btn_enable_edit').removeClass('d-none');
        $('.btn_save_invoice').addClass('d-none');
        $('.btn_cancel_edit').addClass('d-none');
    });
</script>
<script>
    $(document).off('click', '.btn_confirm_payment').on('click', '.btn_confirm_payment', function(e) {
        e.preventDefault();
        let url = $(this).data('url');
        let remaining = parseFloat($(this).data('remaining')).toFixed(2);
        Swal.fire({
            title: 'Confirm Payment',
            text: `Confirm receiving $${remaining} from the student?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#16a34a',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, confirm it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    method: 'PUT',
                    url: url,
                    data: {
                        _token: csrf_token,
                        additional_payment: remaining,
                    },
                    success: function(data) {
                        iziToast.success({
                            message: data.message,
                            position: 'bottomRight'
                        });
                        setTimeout(() => {
                            window.location.reload();
                        }, 500);
                    },
                    error: function() {
                        iziToast.error({
                            message: 'Payment failed. Please try again.',
                            position: 'bottomRight'
                        });
                    }
                });
            }
        });
    });
</script>
