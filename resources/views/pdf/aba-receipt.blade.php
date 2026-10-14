<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Receipt {{ $invoice->invoice_code }}</title>
    <style>
        /* dompdf has limited CSS support — no flexbox/grid, table-based
           layout only (see the note on downloadReceipt() in
           PayWayPaymentController). This mirrors the staff invoice's
           design (resources/views/frontend/staff/pages/partials/
           inv-body.blade.php's #printableArea) so a receipt a student
           downloads and the invoice staff see in the admin panel read as
           the same document family — same header bar, meta strip, parties
           block, course table, payment history and summary — just rebuilt
           with <table> rows instead of flexbox, since dompdf doesn't
           support flex at all. */
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #1D2939; margin: 0; padding: 0; }
        .wrap { max-width: 760px; margin: 0 auto; }

        /* Header */
        table.header-table { width: 100%; background: #0f0e17; }
        table.header-table td { padding: 22px 30px; vertical-align: middle; }
        .logo { height: 46px; }
        .doc-title { text-align: right; font-size: 30px; font-weight: bold; color: #fff; letter-spacing: 2px; }
        .doc-sub { text-align: right; font-size: 11px; color: #9a99a8; margin-top: 4px; }

        /* Meta strip */
        table.meta-table { width: 100%; background: #f4f4f8; border-bottom: 1px solid #e8e8f0; }
        table.meta-table td { padding: 14px 30px; vertical-align: top; }
        .meta-label { font-size: 9px; font-weight: bold; letter-spacing: 0.7px; text-transform: uppercase; color: #aaa; margin-bottom: 3px; }
        .meta-value { font-size: 12.5px; font-weight: bold; color: #222; }
        .meta-amount-label { font-size: 9px; font-weight: bold; letter-spacing: 0.7px; text-transform: uppercase; color: #aaa; text-align: right; }
        .meta-amount { font-size: 19px; font-weight: 800; text-align: right; }
        .meta-note { font-size: 10px; font-weight: 600; color: #ca8a04; text-align: right; margin-top: 3px; }

        /* Parties */
        table.parties-table { width: 100%; }
        table.parties-table td { padding: 24px 30px 0; vertical-align: top; width: 50%; }
        .party-label { font-size: 9px; font-weight: bold; letter-spacing: 0.8px; text-transform: uppercase; color: #aaa; margin-bottom: 7px; }
        .party-name { font-size: 16px; font-weight: 800; color: #0f0e17; margin-bottom: 4px; }
        .party-detail { font-size: 11.5px; color: #666; line-height: 1.7; }

        /* Course table */
        .course-wrap { padding: 22px 30px 0; }
        table.course-table { width: 100%; border-collapse: collapse; }
        table.course-table thead th { background: #0f0e17; color: #b5b4bf; font-size: 10px; font-weight: bold; letter-spacing: 0.6px; text-transform: uppercase; padding: 11px 14px; text-align: left; }
        table.course-table thead th.price-col { text-align: right; }
        table.course-table tbody td { padding: 12px 14px; border-bottom: 1px solid #f0f0f5; font-size: 12px; color: #333; vertical-align: top; }
        table.course-table tbody td.course-name { font-weight: bold; color: #0f0e17; }
        table.course-table tbody td.price-col { text-align: right; font-weight: bold; color: #0f0e17; white-space: nowrap; }
        .item-note { font-size: 9.5px; color: #aaa; margin-top: 2px; }
        .schedule-time { color: #888; }

        /* Payment history */
        .history-wrap { padding: 20px 30px 0; }
        .history-title { font-size: 9px; font-weight: bold; letter-spacing: 0.7px; text-transform: uppercase; color: #aaa; margin-bottom: 10px; }
        table.history-row { width: 100%; background: #f8f8fb; border-radius: 10px; margin-bottom: 8px; }
        table.history-row td { padding: 10px 14px; vertical-align: top; }
        .history-method { font-size: 12.5px; font-weight: bold; color: #0f0e17; }
        .history-pill { display: inline-block; font-size: 9px; font-weight: bold; padding: 2px 7px; border-radius: 9px; margin-left: 5px; }
        .pill-online { color: #16a34a; background: #dcfce7; }
        .pill-installment { color: #ca8a04; background: #fef9c3; }
        .history-sub { font-size: 10px; color: #888; margin-top: 3px; }
        .history-ref { font-size: 10px; color: #aaa; margin-top: 3px; }
        .history-amount { text-align: right; font-size: 13px; font-weight: 800; color: #0f0e17; white-space: nowrap; }

        /* Summary */
        .summary-wrap { padding: 22px 30px 28px; }
        table.summary-outer { width: 100%; }
        table.summary-outer td { vertical-align: top; }
        .terms-box { background: #fff8f8; border-left: 3px solid #ef4444; padding: 12px 15px; }
        .terms-title { font-size: 10px; font-weight: bold; letter-spacing: 0.5px; text-transform: uppercase; color: #ef4444; margin-bottom: 5px; }
        .terms-body { font-size: 11px; color: #555; line-height: 1.6; }
        table.breakdown { width: 260px; }
        table.breakdown td { padding: 7px 0; border-bottom: 1px solid #f0f0f5; font-size: 12px; }
        table.breakdown td.val { text-align: right; }
        table.breakdown tr.total-row td { font-weight: bold; padding-top: 11px; }
        table.breakdown tr.no-border td { border-bottom: none; }
        .remaining-box { background: #0f0e17; border-radius: 10px; padding: 14px 16px; margin-top: 12px; }
        table.remaining-inner { width: 100%; }
        table.remaining-inner td { color: #fff; font-size: 13px; }
        table.remaining-inner td.val { text-align: right; font-weight: bold; }

        /* Footer */
        .footer { padding: 16px 30px; background: #f4f4f8; border-top: 1px solid #e8e8f0; }
        table.footer-table { width: 100%; }
        .footer-thanks { font-size: 14px; font-weight: 800; color: #0f0e17; }
        .footer-contact { text-align: right; font-size: 10.5px; color: #888; }
    </style>
</head>
<body>
    @php
        // Same lookup the staff invoice uses — kept local here rather than
        // shared, since one is Blade-only (view-model concern) and dompdf
        // can't reach a shared partial the same way the live page can.
        $paymentMeta = fn(string $method): array => match ($method) {
            'payway' => ['label' => 'ABA PayWay'],
            'card' => ['label' => 'Card'],
            'bank_transfer' => ['label' => 'Bank Transfer'],
            'online' => ['label' => 'Online'],
            default => ['label' => 'Cash'],
        };
        $paymentMethodsUsed = $invoice->payments->pluck('payment_method')->unique();
        $isFree = $invoice->payment_status === 'free';

        $paymentOptionLabel = match ($invoice->payment_option) {
            'full' => 'Full Payment',
            'half' => 'Half Payment Plan',
            'multi' => 'Multi-Course Discount',
            'normal' => 'Standard',
            'free' => 'Free Enrollment',
            'other' => 'Custom Plan',
            default => null,
        };

        $items = $invoice->items->isNotEmpty() ? $invoice->items : collect();
    @endphp
    <div class="wrap">
        {{-- Header --}}
        <table class="header-table" cellpadding="0" cellspacing="0">
            <tr>
                <td style="width:50%;">
                    <img src="{{ public_path('frontend/assets/ictImg/logo/ictBannerLogo.png') }}" class="logo">
                </td>
                <td style="width:50%;">
                    <div class="doc-title">RECEIPT</div>
                    <div class="doc-sub">#{{ $invoice->invoice_code }}</div>
                </td>
            </tr>
        </table>

        {{-- Meta strip --}}
        <table class="meta-table" cellpadding="0" cellspacing="0">
            <tr>
                <td style="width:22%;">
                    <div class="meta-label">Invoice Date</div>
                    <div class="meta-value">{{ $invoice->created_at->format('d M Y') }}</div>
                </td>
                <td style="width:22%;">
                    <div class="meta-label">Payment Method</div>
                    <div class="meta-value">
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
                </td>
                <td style="width:22%;">
                    @if ($paymentOptionLabel)
                        <div class="meta-label">Payment Plan</div>
                        <div class="meta-value">{{ $paymentOptionLabel }}</div>
                    @endif
                </td>
                <td style="width:34%;">
                    <div class="meta-amount-label">
                        {{ $isFree ? 'Free Enrollment' : ($invoice->payment_status === 'paid' ? 'Fully Paid' : 'To Pay') }}
                    </div>
                    <div class="meta-amount" style="color: {{ $isFree || $invoice->payment_status === 'paid' ? '#16a34a' : '#0f0e17' }};">
                        {{ $isFree ? 'Free' : '$' . number_format($invoice->remaining_amount, 2) }}
                    </div>
                    @if ($invoice->payment_status === 'half_paid')
                        <div class="meta-note">Installment 2 of 2</div>
                    @endif
                </td>
            </tr>
        </table>

        {{-- Parties --}}
        <table class="parties-table" cellpadding="0" cellspacing="0">
            <tr>
                <td>
                    <div class="party-label">Receipt To</div>
                    <div class="party-name">{{ $invoice->student->name }}</div>
                    <div class="party-detail">
                        @if ($invoice->student->email)
                            {{ $invoice->student->email }}<br>
                        @endif
                        @if ($invoice->student->phone)
                            {{ $invoice->student->phone }}
                        @endif
                    </div>
                </td>
                <td style="text-align:right;">
                    <div class="party-label">From</div>
                    <div class="party-name">ICT Professional Training Center</div>
                    <div class="party-detail">
                        House No. 240B, Street 132<br>
                        Sangkat Teuk Laok 01, Khan Toul Kork<br>
                        Phnom Penh<br>
                        +855 097-702-175 / 096-639-3985
                    </div>
                </td>
            </tr>
        </table>

        {{-- Course table --}}
        <div class="course-wrap">
            <table class="course-table" cellpadding="0" cellspacing="0">
                <thead>
                    <tr>
                        <th>Course</th>
                        <th>Schedule</th>
                        <th class="price-col">Price</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($items as $item)
                        @php $sched = $item->course->schedule ?? null; @endphp
                        <tr>
                            <td class="course-name">{{ $item->course->title ?? '—' }}</td>
                            <td>
                                @if ($sched)
                                    {{ $sched->study_day }}<br>
                                    <span class="schedule-time">
                                        {{ \Carbon\Carbon::parse($sched->start_time)->format('h:i') }} –
                                        {{ \Carbon\Carbon::parse($sched->end_time)->format('h:i A') }}
                                    </span>
                                @else
                                    —
                                @endif
                            </td>
                            <td class="price-col">
                                ${{ number_format($item->total ?? $invoice->price, 2) }}
                                @if ($item->discount > 0 || $item->extra_charge > 0)
                                    <div class="item-note">
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
                    @empty
                        {{-- Older invoices predating the items table — fall back to
                             the invoice's own course/price, same as the simple
                             receipt this replaced always did. --}}
                        @php $sched = $invoice->course->schedule ?? null; @endphp
                        <tr>
                            <td class="course-name">{{ $invoice->course->title ?? '—' }}</td>
                            <td>
                                @if ($sched)
                                    {{ $sched->study_day }}<br>
                                    <span class="schedule-time">
                                        {{ \Carbon\Carbon::parse($sched->start_time)->format('h:i') }} –
                                        {{ \Carbon\Carbon::parse($sched->end_time)->format('h:i A') }}
                                    </span>
                                @else
                                    —
                                @endif
                            </td>
                            <td class="price-col">${{ number_format($invoice->price, 2) }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Payment history --}}
        @if ($invoice->payments->isNotEmpty())
            <div class="history-wrap">
                <div class="history-title">Payment History</div>
                @foreach ($invoice->payments->sortBy('paid_at') as $hist)
                    @php
                        $hMeta = $paymentMeta($hist->payment_method);
                        $isGatewayPayment = in_array($hist->payment_method, ['payway', 'online']);
                        $installmentLabel = $invoice->payment_option === 'half'
                            ? ($loop->first ? 'Installment 1 of 2' : 'Installment 2 of 2 — Final')
                            : null;
                    @endphp
                    <table class="history-row" cellpadding="0" cellspacing="0">
                        <tr>
                            <td style="width:68%;">
                                <div class="history-method">
                                    {{ $hMeta['label'] }}
                                    @if ($isGatewayPayment)
                                        <span class="history-pill pill-online">ONLINE</span>
                                    @endif
                                    @if ($installmentLabel)
                                        <span class="history-pill pill-installment">{{ $installmentLabel }}</span>
                                    @endif
                                </div>
                                <div class="history-sub">
                                    {{ \Carbon\Carbon::parse($hist->paid_at ?? $hist->created_at)->format('d M Y, g:i A') }}
                                    @if ($isGatewayPayment)
                                        &middot; paid via ABA PayWay
                                    @elseif ($hist->paidBy)
                                        &middot; recorded by {{ $hist->paidBy->name }}
                                    @endif
                                </div>
                                @if ($hist->gateway_reference || $hist->gateway_approval_code)
                                    <div class="history-ref">
                                        @if ($hist->gateway_reference)
                                            Ref: {{ $hist->gateway_reference }}
                                        @endif
                                        @if ($hist->gateway_reference && $hist->gateway_approval_code)
                                            &middot;
                                        @endif
                                        @if ($hist->gateway_approval_code)
                                            Approval: {{ $hist->gateway_approval_code }}
                                        @endif
                                    </div>
                                @endif
                            </td>
                            <td class="history-amount" style="width:32%;">${{ number_format($hist->amount, 2) }}</td>
                        </tr>
                    </table>
                @endforeach
            </div>
        @endif

        {{-- Summary --}}
        <div class="summary-wrap">
            <table class="summary-outer" cellpadding="0" cellspacing="0">
                <tr>
                    <td style="width:56%; padding-right:24px;">
                        <div class="terms-box">
                            <div class="terms-title">Terms &amp; Conditions</div>
                            <div class="terms-body">All payments are strictly non-refundable.</div>
                        </div>
                    </td>
                    <td style="width:44%;">
                        <table class="breakdown" cellpadding="0" cellspacing="0" style="width:100%;">
                            <tr>
                                <td>Full Price</td>
                                <td class="val">${{ number_format($invoice->price, 2) }}</td>
                            </tr>
                            <tr>
                                <td>Discount</td>
                                <td class="val">${{ number_format($invoice->discount, 2) }}</td>
                            </tr>
                            <tr>
                                <td>Extra Charge</td>
                                <td class="val">${{ number_format($invoice->extra_charge, 2) }}</td>
                            </tr>
                            <tr class="total-row">
                                <td>Total</td>
                                <td class="val">${{ number_format($invoice->total_amount, 2) }}</td>
                            </tr>
                            <tr class="no-border">
                                <td>Paid</td>
                                <td class="val">${{ number_format($invoice->paid_amount, 2) }}</td>
                            </tr>
                        </table>
                        <div class="remaining-box">
                            <table class="remaining-inner" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td>Remaining</td>
                                    <td class="val">${{ number_format($invoice->remaining_amount, 2) }}</td>
                                </tr>
                            </table>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        {{-- Footer --}}
        <div class="footer">
            <table class="footer-table" cellpadding="0" cellspacing="0">
                <tr>
                    <td style="width:55%;" class="footer-thanks">Thank you for your business!</td>
                    <td style="width:45%;" class="footer-contact">
                        +855 097-702-175 &nbsp;&middot;&nbsp; Khan Toul Kork, Phnom Penh
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>
