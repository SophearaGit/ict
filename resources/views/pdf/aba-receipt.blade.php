<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Receipt {{ $invoice->invoice_code }}</title>
    <style>
        /* dompdf has limited CSS support — no flexbox/grid, table-based layout only. */
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #1D2939; margin: 0; padding: 0; }
        .wrap { padding: 36px; }
        .header-table { width: 100%; border-bottom: 2px solid #0057FF; padding-bottom: 16px; margin-bottom: 24px; }
        .brand { font-size: 20px; font-weight: bold; color: #0057FF; }
        .brand-sub { font-size: 11px; color: #667085; }
        .receipt-title { text-align: right; font-size: 22px; font-weight: bold; color: #101828; }
        .receipt-sub { text-align: right; font-size: 11px; color: #667085; }
        .status-badge {
            display: inline-block; background: #ECFDF3; color: #027A48;
            font-size: 11px; font-weight: bold; padding: 4px 12px; border-radius: 12px;
            margin-top: 24px;
        }
        table.detail-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table.detail-table td { padding: 8px 0; border-bottom: 1px solid #EAECF0; font-size: 12px; vertical-align: top; }
        table.detail-table td.label { color: #667085; width: 45%; }
        table.detail-table td.value { color: #101828; font-weight: bold; text-align: right; }
        .section-title { font-size: 13px; font-weight: bold; color: #101828; margin-top: 28px; margin-bottom: 6px; }
        .total-row td { font-size: 15px; padding-top: 14px; }
        .footer { margin-top: 40px; padding-top: 16px; border-top: 1px solid #EAECF0; font-size: 10px; color: #98A2B3; text-align: center; }
    </style>
</head>
<body>
    <div class="wrap">
        <table class="header-table">
            <tr>
                <td style="width:50%">
                    <div class="brand">ICT Professional Training Center</div>
                    <div class="brand-sub">Payment Receipt</div>
                </td>
                <td style="width:50%">
                    <div class="receipt-title">RECEIPT</div>
                    <div class="receipt-sub">{{ $invoice->invoice_code }}</div>
                </td>
            </tr>
        </table>

        <span class="status-badge">PAID</span>

        <div class="section-title">Course</div>
        <table class="detail-table">
            <tr>
                <td class="label">Course</td>
                <td class="value">{{ $invoice->course->title ?? '—' }}</td>
            </tr>
            <tr>
                <td class="label">Schedule</td>
                <td class="value">
                    @if ($invoice->course->schedule)
                        {{ $invoice->course->schedule->short_days }} · {{ $invoice->course->schedule->formatted_time }}
                        @if ($invoice->course->schedule->shift_label)
                            ({{ $invoice->course->schedule->shift_label }})
                        @endif
                    @else
                        —
                    @endif
                </td>
            </tr>
            <tr>
                <td class="label">Student</td>
                <td class="value">{{ $invoice->student->name ?? '—' }}</td>
            </tr>
        </table>

        <div class="section-title">Payment</div>
        <table class="detail-table">
            <tr>
                <td class="label">Payment Method</td>
                <td class="value">ABA PayWay
                    @if ($payment && $payment->gateway_response)
                        ({{ $payment->gateway_response['payment_type'] ?? 'Online Payment' }})
                    @endif
                </td>
            </tr>
            <tr>
                <td class="label">Payment Option</td>
                <td class="value">{{ ucfirst($invoice->payment_option ?? '—') }}</td>
            </tr>
            <tr>
                <td class="label">Transaction ID</td>
                <td class="value">{{ $invoice->payway_tran_id ?? '—' }}</td>
            </tr>
            @if ($payment && $payment->gateway_reference)
                <tr>
                    <td class="label">Bank Reference</td>
                    <td class="value">{{ $payment->gateway_reference }}</td>
                </tr>
            @endif
            @if ($payment && $payment->gateway_approval_code)
                <tr>
                    <td class="label">Approval Code</td>
                    <td class="value">{{ $payment->gateway_approval_code }}</td>
                </tr>
            @endif
            <tr>
                <td class="label">Paid At</td>
                <td class="value">{{ optional($invoice->paid_at)->format('d M Y, h:i A') ?? '—' }}</td>
            </tr>
        </table>

        <div class="section-title">Amount</div>
        <table class="detail-table">
            <tr>
                <td class="label">Course Price</td>
                <td class="value">${{ number_format($invoice->price, 2) }}</td>
            </tr>
            @if ($invoice->discount > 0)
                <tr>
                    <td class="label">Discount</td>
                    <td class="value">-${{ number_format($invoice->discount, 2) }}</td>
                </tr>
            @endif
            @if ($invoice->extra_charge > 0)
                <tr>
                    <td class="label">Extra Charge</td>
                    <td class="value">${{ number_format($invoice->extra_charge, 2) }}</td>
                </tr>
            @endif
            <tr class="total-row">
                <td class="label"><strong>Total Paid</strong></td>
                <td class="value">${{ number_format($invoice->paid_amount, 2) }}</td>
            </tr>
        </table>

        <div class="footer">
            This receipt was generated by ICT Professional Training Center from ABA PayWay transaction data.
            For questions about this payment, please contact the training center with the transaction ID above.
        </div>
    </div>
</body>
</html>
