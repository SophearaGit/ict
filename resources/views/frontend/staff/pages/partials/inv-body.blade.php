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

    .inv-status-badge.free {
        background: #d1fae5;
        color: #059669;
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

    /* Full Screen mode — toggled by .btn_toggle_fullscreen below. Rather
       than the browser's native Fullscreen API (finicky permissions,
       vendor prefixes, no control over how the backdrop looks), this just
       lifts the whole invoice wrap out of the sidebar/list layout to
       cover the viewport, so staff can review a long invoice without the
       student list crowding it. */
    .inv-detail-wrap.inv-fullscreen-active {
        position: fixed;
        inset: 0;
        z-index: 1080;
        overflow-y: auto;
        background: #f8f8fb;
    }

    body.inv-fullscreen-open {
        overflow: hidden;
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

        .inv-detail-wrap.inv-fullscreen-active {
            position: static;
        }

        #printableArea {
            box-shadow: none;
            border-radius: 0;
        }
    }
</style>
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
                    'free' => 'free',
                    default => 'unpaid',
                };
                $badgeLabel = match ($invoice->payment_status) {
                    'paid' => 'Fully Paid',
                    'half_paid' => 'Half Paid',
                    'free' => 'Free',
                    default => 'Unpaid',
                };
            @endphp
            <span class="inv-status-badge {{ $badgeClass }}">{{ $badgeLabel }}</span>
        </div>
        <div class="inv-topbar-actions">
            <button type="button" class="inv-btn inv-btn-outline btn_delete_invoice"
                style="color:#dc2626;border-color:#fecaca;" data-id="{{ $invoice->id }}"
                data-student-id="{{ $invoice->student_id }}">
                <i class="ti ti-trash"></i>
                Delete &amp; Re-register
            </button>
            @if (!in_array($invoice->payment_status, ['paid', 'free']))
                <a href="javascript:void(0)" data-url="{{ route('staff.invoice.confirm-payment', $invoice->id) }}"
                    data-remaining="{{ $invoice->remaining_amount }}"
                    class="inv-btn inv-btn-success btn_confirm_payment">
                    <i class="ti ti-cash"></i>
                    Confirm Payment
                </a>
            @endif
            <button type="button" class="inv-btn inv-btn-outline btn_toggle_fullscreen">
                <i class="ti ti-maximize"></i>
                <span class="fs-label">Full Screen</span>
            </button>
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
            @php
                // Small lookup for how each payment_method should be
                // labelled/iconed — used here and in the Payment History
                // section below. 'payway' is ABA PayWay's online checkout;
                // 'online'/'card'/'bank_transfer' are other gateway-style
                // methods; anything else (incl. legacy blank values) is
                // treated as a plain cash payment recorded by staff.
                $paymentMeta = fn(string $method): array => match ($method) {
                    'payway' => ['icon' => 'ti-qrcode', 'label' => 'ABA PayWay'],
                    'card' => ['icon' => 'ti-credit-card', 'label' => 'Card'],
                    'bank_transfer' => ['icon' => 'ti-building-bank', 'label' => 'Bank Transfer'],
                    'online' => ['icon' => 'ti-world', 'label' => 'Online'],
                    default => ['icon' => 'ti-cash', 'label' => 'Cash'],
                };
                $paymentMethodsUsed = $invoice->payments->pluck('payment_method')->unique();
                $isFree = $invoice->payment_status === 'free';
                $metaLabelStyle =
                    'font-size:10px;font-weight:700;letter-spacing:0.8px;text-transform:uppercase;color:#aaa;margin-bottom:3px;';
                $metaValueStyle = 'font-size:13px;font-weight:700;color:#222;';

                // How this invoice's discount/extra-charge was decided at
                // registration time (i_c_t_invoices.payment_option). Not
                // always set on older/manually-entered invoices, so the
                // meta chip below is skipped entirely when null.
                $paymentOptionLabel = match ($invoice->payment_option) {
                    'full' => 'Full Payment',
                    'half' => 'Half Payment Plan',
                    'multi' => 'Multi-Course Discount',
                    'normal' => 'Standard',
                    'free' => 'Free Enrollment',
                    'other' => 'Custom Plan',
                    default => null,
                };
            @endphp
            <div
                style="background:#f4f4f8;padding:14px 36px;display:flex;align-items:center;gap:32px;flex-wrap:wrap;border-bottom:1px solid #e8e8f0;">
                <div>
                    <div style="{{ $metaLabelStyle }}">Invoice Date</div>
                    <div style="{{ $metaValueStyle }}">{{ $invoice->created_at->format('d M Y') }}</div>
                </div>
                <div>
                    <div style="{{ $metaLabelStyle }}">Payment Method</div>
                    <div style="{{ $metaValueStyle }}">
                        @if ($isFree)
                            &mdash;
                        @elseif ($paymentMethodsUsed->isEmpty())
                            Not paid yet
                        @elseif ($paymentMethodsUsed->count() > 1)
                            Multiple
                        @else
                            {{ $paymentMeta($paymentMethodsUsed->first())['label'] }}
                        @endif
                    </div>
                </div>
                @if ($paymentOptionLabel)
                    <div>
                        <div style="{{ $metaLabelStyle }}">Payment Plan</div>
                        <div style="{{ $metaValueStyle }}">{{ $paymentOptionLabel }}</div>
                    </div>
                @endif
                @if ($invoice->payway_tran_id && $paymentMethodsUsed->contains('payway'))
                    <div>
                        <div style="{{ $metaLabelStyle }}">Transaction ID</div>
                        <div style="font-size:12px;font-weight:700;color:#222;font-family:'Courier New',monospace;">
                            {{ $invoice->payway_tran_id }}
                        </div>
                    </div>
                @endif
                @if ($invoice->payment_status === 'paid' && $invoice->paid_at)
                    <div>
                        <div style="{{ $metaLabelStyle }}">Paid On</div>
                        <div style="{{ $metaValueStyle }}">
                            {{ \Carbon\Carbon::parse($invoice->paid_at)->format('d M Y') }}
                        </div>
                    </div>
                @endif
                <div style="margin-left:auto;text-align:right;">
                    <div style="{{ $metaLabelStyle }}">
                        {{ $isFree ? 'Free Enrollment' : ($invoice->payment_status === 'paid' ? 'Fully Paid' : 'To Pay') }}
                    </div>
                    <div
                        style="font-size:20px;font-weight:800;color:{{ $isFree || $invoice->payment_status === 'paid' ? '#16a34a' : '#0f0e17' }};">
                        {{ $isFree ? 'Free' : '$' . number_format($invoice->remaining_amount, 2) }}
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
                    @if ($invoice->staff)
                        <div style="font-size:11px;color:#999;margin-top:6px;">
                            Registered by {{ $invoice->staff->name }}
                        </div>
                    @endif
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
                                    ${{ number_format($item->total ?? $invoice->price, 2) }}
                                    {{-- On a multi-course invoice, the discount/extra charge is split
                                         per course and can differ from item to item — the invoice-level
                                         Summary below only shows the combined total, so surface each
                                         item's own adjustment here when it has one. --}}
                                    @if ($item->discount > 0 || $item->extra_charge > 0)
                                        <div style="font-size:10px;font-weight:500;color:#aaa;margin-top:2px;">
                                            @if ($item->discount > 0)
                                                -${{ number_format($item->discount, 2) }} disc
                                            @endif
                                            @if ($item->discount > 0 && $item->extra_charge > 0)
                                                &middot;
                                            @endif
                                            @if ($item->extra_charge > 0)
                                                +${{ number_format($item->extra_charge, 2) }} extra
                                            @endif
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{-- Payment History --}}
            @if ($invoice->payments->isNotEmpty())
                <div style="padding:24px 36px 0;">
                    <div style="{{ $metaLabelStyle }} margin-bottom:12px;">Payment History</div>
                    <div style="display:flex;flex-direction:column;gap:10px;">
                        @foreach ($invoice->payments->sortBy('paid_at') as $payment)
                            @php
                                $pMeta = $paymentMeta($payment->payment_method);
                                $isGatewayPayment = in_array($payment->payment_method, ['payway', 'online']);
                            @endphp
                            <div
                                style="display:flex;align-items:center;gap:14px;padding:12px 14px;background:#f8f8fb;border-radius:10px;">
                                <div
                                    style="width:36px;height:36px;border-radius:50%;background:#0f0e17;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    <i class="ti {{ $pMeta['icon'] }}" style="color:#fff;font-size:16px;"></i>
                                </div>
                                <div style="flex:1;min-width:0;">
                                    <div style="font-size:13px;font-weight:700;color:#0f0e17;">
                                        {{ $pMeta['label'] }}
                                        @if ($isGatewayPayment)
                                            <span
                                                style="font-size:10px;font-weight:700;color:#16a34a;background:#dcfce7;padding:2px 8px;border-radius:10px;margin-left:6px;">ONLINE</span>
                                        @endif
                                    </div>
                                    <div style="font-size:11px;color:#888;margin-top:2px;">
                                        {{-- paid_at isn't cast to a Carbon instance on this model (unlike
                                             created_at, which Eloquent casts automatically), so it comes
                                             back as a plain string — parse it explicitly rather than
                                             calling ->format() directly on it. --}}
                                        {{ \Carbon\Carbon::parse($payment->paid_at ?? $payment->created_at)->format('d M Y, g:i A') }}
                                        @if ($isGatewayPayment)
                                            &middot; paid by student via ABA PayWay
                                        @elseif ($payment->paidBy)
                                            &middot; recorded by {{ $payment->paidBy->name }}
                                        @endif
                                    </div>
                                    @if ($payment->gateway_reference || $payment->gateway_approval_code)
                                        <div
                                            style="font-size:11px;color:#aaa;margin-top:3px;font-family:'Courier New',monospace;">
                                            @if ($payment->gateway_reference)
                                                Ref: {{ $payment->gateway_reference }}
                                            @endif
                                            @if ($payment->gateway_reference && $payment->gateway_approval_code)
                                                &middot;
                                            @endif
                                            @if ($payment->gateway_approval_code)
                                                Approval: {{ $payment->gateway_approval_code }}
                                            @endif
                                        </div>
                                    @endif
                                    {{-- Only shown for staff-recorded (cash) payments — the gateway
                                         note on PayWay payments just repeats the ref already printed
                                         above, so it's skipped here to avoid duplicate text. --}}
                                    @if (!$isGatewayPayment && $payment->note)
                                        <div style="font-size:11px;color:#aaa;margin-top:3px;font-style:italic;">
                                            {{ $payment->note }}
                                        </div>
                                    @endif
                                </div>
                                <div style="font-size:14px;font-weight:800;color:#0f0e17;white-space:nowrap;">
                                    ${{ number_format($payment->amount, 2) }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
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
                        <span>${{ number_format($invoice->price, 2) }}</span>
                    </div>
                    <div
                        style="display:flex;justify-content:space-between;padding:7px 0;border-bottom:1px solid #f0f0f5;">
                        <span>Discount</span>
                        <span>${{ number_format($invoice->discount, 2) }}</span>
                    </div>
                    <div
                        style="display:flex;justify-content:space-between;padding:7px 0;border-bottom:1px solid #f0f0f5;">
                        <span>Extra Charge</span>
                        <span>${{ number_format($invoice->extra_charge, 2) }}</span>
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
    // ── FULL SCREEN TOGGLE ──
    // `position: fixed; inset: 0` alone isn't enough here — the invoice
    // list/detail app sits several levels deep inside the admin layout's
    // wrappers, and (same issue already hit with the flatpickr calendar
    // inside a Bootstrap modal elsewhere in this app) a fixed-position
    // element still gets boxed in by an ancestor rather than truly
    // covering the viewport. Physically moving the wrap to a direct child
    // of <body> while full screen is active sidesteps that entirely, then
    // moves it back to where it came from on exit.
    //
    // Re-bound fresh every time this partial is re-injected (a new
    // invoice click replaces .invoiceing-box's content), so make sure a
    // previous invoice's fullscreen state doesn't linger.
    $('body').removeClass('inv-fullscreen-open');
    $(document).off('keydown.invFullscreen');
    $(document).off('click', '.btn_toggle_fullscreen').on('click', '.btn_toggle_fullscreen', function() {
        const $wrap = $('.inv-detail-wrap');
        const goingFullscreen = !$wrap.hasClass('inv-fullscreen-active');
        if (goingFullscreen) {
            $wrap.data('fullscreen-origin', $wrap.parent());
            $wrap.appendTo(document.body).addClass('inv-fullscreen-active');
        } else {
            const $origin = $wrap.data('fullscreen-origin');
            $wrap.removeClass('inv-fullscreen-active');
            if ($origin && $origin.length) {
                $wrap.appendTo($origin);
            }
        }
        $('body').toggleClass('inv-fullscreen-open', goingFullscreen);
        $(this).find('i').attr('class', goingFullscreen ? 'ti ti-minimize' : 'ti ti-maximize');
        $(this).find('.fs-label').text(goingFullscreen ? 'Exit Full Screen' : 'Full Screen');
    });
    // Escape exits full screen mode
    $(document).on('keydown.invFullscreen', function(e) {
        if (e.key === 'Escape' && $('.inv-detail-wrap').hasClass('inv-fullscreen-active')) {
            $('.btn_toggle_fullscreen').trigger('click');
        }
    });
    $(document).off('click', '.btn_delete_invoice').on('click', '.btn_delete_invoice', function(e) {
        e.preventDefault();
        const invoiceId = $(this).data('id');
        const studentId = $(this).data('student-id');
        Swal.fire({
            title: 'Delete this invoice?',
            html: 'This will permanently delete this invoice, its items, and its payment records — ' +
                'and remove the student\'s enrollment for this course.<br><br>' +
                '<strong>This cannot be undone.</strong>',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, delete it'
        }).then((result) => {
            if (!result.isConfirmed) return;
            $.ajax({
                url: "{{ route('staff.invoice.destroy', $invoice->id) }}",
                type: 'DELETE',
                data: {
                    _token: csrf_token,
                },
                success: function(res) {
                    iziToast.success({
                        message: res.message,
                        position: 'bottomRight'
                    });
                    window.location.href =
                        "{{ route('staff.student.registration') }}?student_id=" +
                        studentId;
                },
                error: function(xhr) {
                    iziToast.error({
                        message: xhr.responseJSON?.message ??
                            'Failed to delete invoice.',
                        position: 'bottomRight'
                    });
                }
            });
        });
    });
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
