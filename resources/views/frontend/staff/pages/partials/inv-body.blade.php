<div class="invoice-header d-flex align-items-center border-bottom p-3">
    <h4 class="font-medium text-uppercase mb-0">
        Invoice
    </h4>
    <div class="ms-auto">
        <h4 class="invoice-number">
            {{ $invoice->invoice_code }}
        </h4>
    </div>
</div>
<div class="p-3" id="custom-invoice">
    <div class="invoice-123" id="printableArea" style="display: block;">
        <div
            style="background: #e9e9e9;box-sizing: border-box;font-family: Arial, Helvetica, sans-serif;width:800px;margin:auto;background:#fff;padding:40px;box-shadow:0 10px 30px rgba(0,0,0,0.15);border-radius:8px;">

            <!-- Top Section -->
            <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:35px;">

                <div>
                    <img src="{{ asset('/frontend/assets/ictImg/logo/ictBannerLogo.png') }}" class="dark-logo"
                        width="420" height="110" alt="ict_dark_logo" style="border-radius: 5px;">
                    {{-- <h1 style="font-size:34px;color:#111;font-weight:800;margin:0;">
                        ICT
                    </h1>
                    <p style="font-size:14px;color:#444;margin-top:4px;letter-spacing:1px;">
                        PROFESSIONAL TRAINING CENTER
                    </p> --}}
                </div>

                <div
                    style="background:#1502a6;color:#fff;padding:18px 35px;border-radius:0 0 0 35px;text-align:center;min-width:290px;">
                    <h2 style="font-size:42px;font-weight:800;margin:0;color:white">INVOICE</h2>
                    <p style="font-size:18px;margin-top:4px;font-weight:bold;">
                        #{{ $invoice->invoice_code }}
                    </p>
                </div>

            </div>

            <!-- Info Section -->
            <div style="display:flex;justify-content:space-between;margin-bottom:30px;">

                <div style="width:50%;">
                    <h4 style="font-size:20px;color:#111;margin-bottom:8px;">Invoice To</h4>
                    <p>
                        <strong class="text-capitalize">
                            {{ $invoice->student->name }}
                        </strong>
                    </p>
                    <p>ICT Professinal Training Center</p>
                    <br>
                    <p><strong>A</strong> 📍Location = House No. 240B, Street 132, Village 06, Sangkat Teuk Laok 01,
                        Khan Toul Kork, Phnom Penh</p>
                    <p><strong>W</strong> ictskills.center</p>
                    <p><strong>P</strong> +885 097-702-175 / 096-639-3985</p>
                </div>

                <div style="width:40%;">
                    <div style="display:flex;gap:30px;margin-bottom:20px;">
                        <div>
                            <h5 style="font-size:15px;color:#333;margin-bottom:5px;">Inv Date</h5>
                            <p style="font-size:16px;font-weight:bold;">
                                {{ date('d/m/Y', strtotime($invoice->created_at)) }}
                            </p>
                        </div>
                        @php $count = $invoice->payments->count(); @endphp

                        @if ($count === 1)
                            @if ($invoice->payment_status == 'half_paid')
                                <div>
                                    <h5 style="font-size:15px;color:#333;margin-bottom:5px;">Half-Paid </h5>
                                    <p style="font-size:16px;font-weight:bold;" class="text-warning">
                                        <strong>

                                            {{ date('d/m/Y', strtotime($invoice->payments->first()->created_at)) }}
                                        </strong>
                                    </p>
                                </div>
                            @elseif($invoice->payment_status == 'paid')
                                <div>
                                    <h5 style="font-size:15px;color:#333;margin-bottom:5px;">Full-Paid </h5>
                                    <p style="font-size:16px;font-weight:bold;" class="text-success">
                                        <strong>
                                            {{ date('d/m/Y', strtotime($invoice->payments->first()->created_at)) }}
                                        </strong>
                                    </p>
                                </div>
                            @endif
                        @elseif ($count > 1)
                            {{-- show half pay and full pay date --}}
                            <div>
                                <h5 style="font-size:15px;color:#333;margin-bottom:5px;">
                                    Half-Paid
                                </h5>
                                <p style="font-size:16px;font-weight:bold;" class="text-warning">
                                    <strong>
                                        {{ date('d/m/Y', strtotime($invoice->payments->first()->created_at)) }}
                                    </strong>
                                </p>
                            </div>
                            <div>
                                <h5 style="font-size:15px;color:#333;margin-bottom:5px;">Full-Paid </h5>
                                <p style="font-size:16px;font-weight:bold;" class="text-success">
                                    <strong>
                                        {{ date('d/m/Y', strtotime($invoice->payments->last()->created_at)) }}
                                    </strong>
                                </p>
                            </div>
                        @else
                            no payment
                        @endif

                    </div>

                    <div style="background:#f2f2f2;padding:20px;border-radius:12px;text-align:center;">
                        <h5 style="color:#555;font-size:18px;margin-bottom:6px;">
                            Total To Pay
                        </h5>
                        <h3 style="font-size:36px;color:#111;">
                            ${{ number_format($invoice->total_amount, 2) }}
                        </h3>
                    </div>
                </div>

            </div>

            <!-- Table -->
            <table style="width:100%;border-collapse:collapse;margin-top:20px;">



                <thead>
                    <tr>
                        <th style="padding:16px;text-align:left; background-color: #222633; color:#fff;">Course</th>
                        <th style="padding:16px;text-align:left; background-color: #1502a6; color:#fff;">Schedule</th>
                        <th style="padding:16px;background-color: #1502a6; color:#fff;">Price</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($invoice->items as $item)
                        <tr>
                            <!-- Course -->
                            <td style="padding:18px;background:#fff;">
                                <strong>
                                    {{ $item->course->title }}
                                </strong><br>
                                <small style="color:#666;">
                                    {{-- {{ $invoice->course->description ?? '' }} --}}
                                </small>
                            </td>

                            <!-- Schedule -->
                            <td style="padding:18px;background:#fff;">
                                <small style="color:#444;">
                                    {{ $item->course->schedule->study_day }} |
                                    {{ $item->course->schedule->shift }} |
                                    {{ \Carbon\Carbon::parse($item->course->schedule->start_time)->format('h:i ') }}
                                    -
                                    {{ \Carbon\Carbon::parse($item->course->schedule->end_time)->format('h:i A') }}
                                    <br>
                                </small>
                            </td>

                            <!-- Price -->
                            <td style="padding:18px;background:#fff;">
                                ${{ number_format($item->course->price, 2) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <!-- Bottom Section -->
            <div style="display:flex;justify-content:space-between;margin-top:35px;gap:30px;">
                <div style="width:48%;">
                    {{-- <h3 style="font-size:26px;margin-bottom:18px;">Payment Methods</h3>
                    <div style="display:flex;gap:30px;margin:20px 0;">
                        <div style="font-size:28px;font-weight:bold;">
                            PayPal <br>
                            <span style="font-size:12px;color:#777;">info@mail.com</span>
                        </div>
                        <div style="font-size:28px;font-weight:bold;">
                            Skrill <br>
                            <span style="font-size:12px;color:#777;">info@mail.com</span>
                        </div>
                    </div> --}}
                    <h3 style="font-size:26px;margin-bottom:18px;">Terms & Conditions</h3>
                    <!-- Note -->
                    <div
                        style="
                                margin-top:12px;
                                padding:12px 16px;
                                background:#fff3f3;
                                border-left:4px solid #d9534f;
                                border-radius:6px;
                                font-size:14px;
                                color:#444;
                            ">
                        <strong style="color:#d9534f;">Note:</strong>
                        All payments are strictly non-refundable.
                    </div>
                </div>

                <div style="width:48%;">
                    <table style="width:100%;">
                        @if ($invoice->extra_charge > 0)
                            {{-- original price --}}
                            <tr>
                                <td style="padding:10px 0;">Original Price</td>
                                <td style="text-align:right;font-weight:bold;">
                                    ${{ number_format($invoice->items->sum('price'), 2) }}
                                </td>
                            </tr>
                            <tr>
                                <td style="padding:10px 0;">Extra Charge</td>
                                <td style="text-align:right;font-weight:bold;">
                                    ${{ number_format($invoice->extra_charge, 2) }}
                                </td>
                            </tr>
                            {{-- Grand total --}}
                            <tr>
                                <td style="padding:10px 0;">Grand Total</td>
                                <td style="text-align:right;font-weight:bold;">
                                    ${{ number_format($invoice->total_amount, 2) }}
                                </td>
                            </tr>
                            {{-- done paid --}}
                            <tr>
                                <td style="padding:10px 0;">Paid Amount</td>
                                <td style="text-align:right;font-weight:bold;">
                                    ${{ number_format($invoice->paid_amount, 2) }}
                                </td>
                            </tr>
                        @elseif ($invoice->discount > 0)
                            {{-- original price --}}
                            <tr>
                                <td style="padding:10px 0;">Original Price</td>
                                <td style="text-align:right;font-weight:bold;">
                                    ${{ number_format($invoice->items->sum('price'), 2) }}
                                </td>
                            </tr>
                            <tr>
                                <td style="padding:10px 0;">Discount</td>
                                <td style="text-align:right;font-weight:bold;">
                                    ${{ number_format($invoice->discount, 2) }}
                                </td>
                            </tr>
                            {{-- Grand total --}}
                            <tr>
                                <td style="padding:10px 0;">Grand Total</td>
                                <td style="text-align:right;font-weight:bold;">
                                    ${{ number_format($invoice->total_amount, 2) }}
                                </td>
                            </tr>
                            {{-- done paid --}}
                            <tr>
                                <td style="padding:10px 0;">Paid Amount</td>
                                <td style="text-align:right;font-weight:bold;">
                                    ${{ number_format($invoice->paid_amount, 2) }}
                                </td>
                            </tr>
                        @endif
                    </table>

                    <div style="margin-top:20px;">

                        <!-- Remaining Total Card -->
                        <div
                            style="
                                background:linear-gradient(135deg,#1502a6,#3a2be2);
                                color:#fff;
                                padding:22px 24px;
                                border-radius:12px;
                                display:flex;
                                justify-content:space-between;
                                align-items:center;
                                font-size:24px;
                                font-weight:600;
                                box-shadow:0 8px 20px rgba(0,0,0,0.15);
                            ">
                            <span style="opacity:0.9;">Remaining Total</span>
                            <span style="font-size:30px;font-weight:700;">
                                ${{ number_format($invoice->remaining_amount, 2) }}
                            </span>
                        </div>



                    </div>




                </div>

            </div>

            <!-- Footer -->
            <div style="margin-top:40px;border-top:1px solid #ddd;padding-top:25px;text-align:center;">
                <h2 style="font-size:26px;margin-bottom:20px;">
                    Thank you for your business!
                </h2>
                <div
                    style="display:flex;justify-content:space-around;flex-wrap:wrap;gap:15px;color:#444;font-size:8px;">
                    <div>📞 +885 097-702-175 / 096-639-3985</div>
                    <div>✉️ ictskills.center</div>
                    <div>📍Location = House No. 240B, Street 132, Village 06, Sangkat Teuk Laok 01,
                        Khan Toul Kork, Phnom Penh</div>
                </div>
            </div>

        </div>
    </div>
</div>
{{-- ===== ACTION BUTTONS ===== --}}
<div class="text-end no-print m-4">
    @if ($invoice->payment_status != 'paid')
        <a href="javascript:void(0)" data-url="{{ route('staff.invoice.confirm-payment', $invoice->id) }}"
            class="btn btn-info btn_confirm_payment">
            <i class="ti ti-cash me-2"></i> Confirm Payment
        </a>
    @endif
    <button class="btn btn-secondary print-page" type="button">
        <i class="ti ti-printer fs-5"></i> Print Invoice
    </button>
</div>

<div class="modal fade" id="dynamic_invoice_modal" tabindex="-1" aria-labelledby="bs-example-modal-lg"
    aria-hidden="true">
    <div class="modal-dialog modal-xl dynamic_invoice_modal_dialog">

        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>



<script>
    $('.btn_confirm_payment').on('click', function(e) {
        e.preventDefault();

        let url = $(this).data('url');

        Swal.fire({
            title: "Are you sure?",
            text: "You are about to confirm the payment for this invoice.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, confirm it!"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    method: "PUT",
                    url: url,
                    data: {
                        _token: csrf_token,
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
                    error: function(xhr, status, data) {},
                })
            }
        });
    })
</script>
