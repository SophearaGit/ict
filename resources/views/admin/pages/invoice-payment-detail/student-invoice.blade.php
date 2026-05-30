@extends('admin.layouts.master')
@section('page_title', isset($page_title) ? $page_title : 'ICT | ADMIN | STUDENT INVOICE')

@push('styles')
    <style>
        :root {
            --inv-primary: #4f46e5;
            --inv-primary-light: #ede9fe;
            --inv-success: #10b981;
            --inv-success-light: #d1fae5;
            --inv-danger: #ef4444;
            --inv-danger-light: #fee2e2;
            --inv-warning: #f59e0b;
            --inv-warning-light: #fef3c7;
            --inv-muted: #6b7280;
            --inv-border: #e5e7eb;
            --inv-surface: #f9fafb;
        }

        .invoice-shell {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, .06), 0 8px 32px rgba(79, 70, 229, .08);
            overflow: hidden;
        }

        .invoice-banner {
            background: linear-gradient(135deg, var(--inv-primary) 0%, #7c3aed 100%);
            padding: 32px 36px 28px;
            position: relative;
            overflow: hidden;
        }

        .invoice-banner::before {
            content: '';
            position: absolute;
            top: -40px;
            right: -40px;
            width: 200px;
            height: 200px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .06);
        }

        .invoice-banner::after {
            content: '';
            position: absolute;
            bottom: -60px;
            left: 30%;
            width: 300px;
            height: 300px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .04);
        }

        .invoice-banner .inv-code {
            font-size: 11px;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: rgba(255, 255, 255, .65);
            font-weight: 600;
        }

        .invoice-banner h2 {
            color: #fff;
            font-size: 22px;
            font-weight: 700;
            margin: 4px 0 0;
        }

        .inv-status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: .4px;
            text-transform: uppercase;
        }

        .inv-status-badge.paid {
            background: rgba(16, 185, 129, .2);
            color: #6ee7b7;
            border: 1px solid rgba(16, 185, 129, .3);
        }

        .inv-status-badge.partial {
            background: rgba(245, 158, 11, .2);
            color: #fcd34d;
            border: 1px solid rgba(245, 158, 11, .3);
        }

        .inv-status-badge.unpaid {
            background: rgba(239, 68, 68, .2);
            color: #fca5a5;
            border: 1px solid rgba(239, 68, 68, .3);
        }

        .inv-meta-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 1px;
            background: var(--inv-border);
            border-top: 1px solid var(--inv-border);
        }

        .inv-meta-cell {
            background: #fff;
            padding: 18px 24px;
        }

        .inv-meta-cell .label {
            font-size: 11px;
            color: var(--inv-muted);
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 600;
            margin-bottom: 4px;
        }

        .inv-meta-cell .value {
            font-size: 14px;
            color: #111827;
            font-weight: 600;
        }

        .inv-section {
            padding: 24px 32px;
        }

        .inv-section-title {
            font-size: 11px;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: var(--inv-muted);
            font-weight: 700;
            margin-bottom: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .inv-section-title::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--inv-border);
        }

        .inv-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13.5px;
        }

        .inv-table thead th {
            background: var(--inv-surface);
            padding: 10px 16px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .8px;
            text-transform: uppercase;
            color: var(--inv-muted);
            border-bottom: 1px solid var(--inv-border);
            text-align: left;
        }

        .inv-table thead th.num {
            text-align: right;
        }

        .inv-table tbody td {
            padding: 14px 16px;
            border-bottom: 1px solid var(--inv-border);
            color: #374151;
            vertical-align: middle;
        }

        .inv-table tbody td.num {
            text-align: right;
            font-weight: 600;
        }

        .inv-table tbody tr:last-child td {
            border-bottom: none;
        }

        .inv-totals {
            background: var(--inv-surface);
            border-radius: 12px;
            padding: 20px 24px;
            min-width: 280px;
        }

        .inv-total-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 6px 0;
            font-size: 13.5px;
            color: #374151;
        }

        .inv-total-row.divider {
            border-top: 1px solid var(--inv-border);
            margin-top: 8px;
            padding-top: 12px;
        }

        .inv-total-row.grand {
            font-size: 16px;
            font-weight: 700;
            color: #111827;
        }

        .inv-total-row .lbl {
            color: var(--inv-muted);
        }

        .inv-total-row.discount .val {
            color: var(--inv-success);
        }

        .inv-total-row.grand .val {
            color: var(--inv-primary);
        }

        .payment-row {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 14px 0;
            border-bottom: 1px solid var(--inv-border);
        }

        .payment-row:last-child {
            border-bottom: none;
        }

        .payment-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: var(--inv-primary-light);
            color: var(--inv-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
        }

        .payment-icon.cash {
            background: var(--inv-success-light);
            color: var(--inv-success);
        }

        .payment-icon.card {
            background: var(--inv-primary-light);
            color: var(--inv-primary);
        }

        .payment-icon.transfer {
            background: var(--inv-warning-light);
            color: var(--inv-warning);
        }

        .payment-method-badge {
            display: inline-block;
            padding: 2px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            text-transform: capitalize;
            letter-spacing: .3px;
            background: var(--inv-surface);
            color: var(--inv-muted);
            border: 1px solid var(--inv-border);
        }

        .remaining-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
        }

        .remaining-pill.zero {
            background: var(--inv-success-light);
            color: #065f46;
        }

        .remaining-pill.nonzero {
            background: var(--inv-danger-light);
            color: #991b1b;
        }

        /* Admin-specific: subtle top indicator */
        .admin-viewer-bar {
            background: #f0f9ff;
            border-bottom: 1px solid #bae6fd;
            padding: 8px 32px;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 12px;
            color: #0369a1;
            font-weight: 500;
        }

        @media print {
            .no-print {
                display: none !important;
            }

            .invoice-shell {
                box-shadow: none;
            }

            .admin-viewer-bar {
                display: none !important;
            }
        }
    </style>
@endpush

@section('content')

    {{-- Breadcrumb --}}
    <div class="row">
        <div class="col-lg-12 col-md-12 col-12">
            <div class="border-bottom pb-3 mb-4 d-md-flex align-items-center justify-content-between">
                <div class="mb-3 mb-md-0">
                    <h1 class="mb-1 h2 fw-bold">Student Invoice</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.courses.realtime.index') }}">Courses (Real Time)</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.courses.realtime.show', $course->id) }}">
                                    {{ Str::limit($course->title, 25) }}
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Invoice</li>
                        </ol>
                    </nav>
                </div>
                <div class="no-print d-flex gap-2">
                    <a href="{{ route('admin.courses.realtime.show', $course->id) }}"
                        class="btn btn-outline-secondary btn-sm d-flex align-items-center gap-1">
                        <i class="fe fe-arrow-left"></i> Back to Course
                    </a>
                    {{-- <button onclick="window.print()" class="btn btn-primary btn-sm d-flex align-items-center gap-1">
                        <i class="fe fe-printer"></i> Print Invoice
                    </button> --}}
                </div>
            </div>
        </div>
    </div>

    @if ($invoice)
        <div class="invoice-shell">

            {{-- Admin viewer indicator bar --}}
            <div class="admin-viewer-bar no-print">
                <i class="fe fe-shield"></i>
                Viewing as <strong>Admin</strong> &mdash; Read-only view
            </div>

            {{-- ── Banner ── --}}
            <div class="invoice-banner">
                <div class="d-flex align-items-start justify-content-between flex-wrap gap-3"
                    style="position:relative;z-index:2;">
                    <div>
                        <div class="inv-code">Invoice</div>
                        <h2>{{ $invoice->invoice_code }}</h2>
                        <p class="mb-0 mt-1" style="color:rgba(255,255,255,.7);font-size:13px;">
                            <i class="fe fe-calendar me-1"></i>
                            Issued {{ \Carbon\Carbon::parse($invoice->created_at)->format('d M Y, g:i A') }}
                        </p>
                    </div>
                    <div class="text-end">
                        @php
                            $statusClass = in_array($invoice->payment_status, ['paid', 'partial', 'unpaid'])
                                ? $invoice->payment_status
                                : 'unpaid';
                            $statusIcon = match ($statusClass) {
                                'paid' => 'fe-check-circle',
                                'partial' => 'fe-clock',
                                default => 'fe-x-circle',
                            };
                        @endphp
                        <div class="inv-status-badge {{ $statusClass }}">
                            <i class="fe {{ $statusIcon }}"></i>
                            {{ ucfirst($invoice->payment_status) }}
                        </div>
                        <p class="mb-0 mt-2" style="color:rgba(255,255,255,.6);font-size:12px;">
                            ICT Professional Training Center
                        </p>
                    </div>
                </div>
            </div>

            {{-- ── Meta grid ── --}}
            <div class="inv-meta-grid">
                <div class="inv-meta-cell">
                    <div class="label"><i class="fe fe-user me-1"></i>Student</div>
                    <div class="value">{{ $student->name }}</div>
                </div>
                <div class="inv-meta-cell">
                    <div class="label"><i class="fe fe-book me-1"></i>Course</div>
                    <div class="value text-capitalize">
                        {{ $invoiceItems->pluck('course.title')->filter()->implode(', ') ?: '—' }}
                    </div>
                </div>
                <div class="inv-meta-cell">
                    <div class="label"><i class="fe fe-user-check me-1"></i>Staff</div>
                    <div class="value">{{ $invoice->staff->name ?? 'Staff #' . $invoice->staff_id }}</div>
                </div>
                <div class="inv-meta-cell">
                    <div class="label"><i class="fe fe-credit-card me-1"></i>Payment Option</div>
                    <div class="value text-capitalize">{{ str_replace('_', ' ', $invoice->payment_option) }}</div>
                </div>
                @if ($invoice->paid_at)
                    <div class="inv-meta-cell">
                        <div class="label"><i class="fe fe-calendar me-1"></i>Paid At</div>
                        <div class="value">{{ \Carbon\Carbon::parse($invoice->paid_at)->format('d M Y') }}</div>
                    </div>
                @endif
            </div>

            {{-- ── Invoice items ── --}}
            <div class="inv-section">
                <div class="inv-section-title"><i class="fe fe-list me-1"></i> Invoice Items</div>
                <div class="table-responsive">
                    <table class="inv-table">
                        <thead>
                            <tr>
                                <th style="width:40px">#</th>
                                <th>Description</th>
                                <th class="num">Price</th>
                                <th class="num">Discount</th>
                                <th class="num">Extra</th>
                                <th class="num">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($invoiceItems as $i => $item)
                                <tr>
                                    <td class="text-muted">{{ $i + 1 }}</td>
                                    <td>
                                        <span class="fw-semibold text-capitalize">
                                            {{ $item->course?->title ?? 'Course #' . $item->course_id }}
                                        </span>
                                    </td>
                                    <td class="num">${{ number_format($item->price, 2) }}</td>
                                    <td class="num">
                                        @if ($item->discount > 0)
                                            <span class="text-success">−${{ number_format($item->discount, 2) }}</span>
                                        @else
                                            <span class="text-muted fw-normal">—</span>
                                        @endif
                                    </td>
                                    <td class="num">
                                        @if ($item->extra_charge > 0)
                                            <span class="text-warning">+${{ number_format($item->extra_charge, 2) }}</span>
                                        @else
                                            <span class="text-muted fw-normal">—</span>
                                        @endif
                                    </td>
                                    <td class="num">${{ number_format($item->total, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Totals --}}
                <div class="d-flex justify-content-end mt-4">
                    <div class="inv-totals">
                        <div class="inv-total-row">
                            <span class="lbl">Subtotal</span>
                            <span class="val">${{ number_format($invoice->price, 2) }}</span>
                        </div>
                        @if ($invoice->discount > 0)
                            <div class="inv-total-row discount">
                                <span class="lbl">Discount</span>
                                <span class="val">−${{ number_format($invoice->discount, 2) }}</span>
                            </div>
                        @endif
                        @if ($invoice->extra_charge > 0)
                            <div class="inv-total-row">
                                <span class="lbl">Extra Charge</span>
                                <span class="val">+${{ number_format($invoice->extra_charge, 2) }}</span>
                            </div>
                        @endif
                        <div class="inv-total-row divider grand">
                            <span class="lbl">Total</span>
                            <span class="val">${{ number_format($invoice->total_amount, 2) }}</span>
                        </div>
                        <div class="inv-total-row" style="color:var(--inv-success);">
                            <span class="lbl" style="color:var(--inv-muted)">Paid</span>
                            <span class="val fw-semibold">${{ number_format($invoice->paid_amount, 2) }}</span>
                        </div>
                        <div class="inv-total-row">
                            <span class="lbl">Remaining</span>
                            <span class="remaining-pill {{ $invoice->remaining_amount == 0 ? 'zero' : 'nonzero' }}">
                                @if ($invoice->remaining_amount == 0)
                                    <i class="fe fe-check"></i> Fully Paid
                                @else
                                    <i class="fe fe-alert-circle"></i> ${{ number_format($invoice->remaining_amount, 2) }}
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Payment history ── --}}
            <div class="inv-section" style="border-top:1px solid var(--inv-border)">
                <div class="inv-section-title"><i class="fe fe-file-text me-1"></i> Payment History</div>

                @forelse ($payments as $payment)
                    @php
                        $method = strtolower($payment->payment_method);
                        $iconClass = match ($method) {
                            'cash' => 'fe-dollar-sign',
                            'card' => 'fe-credit-card',
                            'transfer' => 'fe-repeat',
                            default => 'fe-dollar-sign',
                        };
                    @endphp
                    <div class="payment-row">
                        <div class="payment-icon {{ $method }}">
                            <i class="fe {{ $iconClass }}"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <span class="fw-semibold" style="font-size:14px;">
                                    ${{ number_format($payment->amount, 2) }}
                                </span>
                                <span class="payment-method-badge">{{ ucfirst($payment->payment_method) }}</span>
                            </div>
                            <div class="d-flex align-items-center gap-3 flex-wrap">
                                <span class="text-muted" style="font-size:12px;">
                                    <i class="fe fe-calendar me-1"></i>
                                    {{ \Carbon\Carbon::parse($payment->paid_at)->format('d M Y, g:i A') }}
                                </span>
                                @if ($payment->note)
                                    <span class="text-muted" style="font-size:12px;">
                                        <i class="fe fe-edit-2 me-1"></i>{{ $payment->note }}
                                    </span>
                                @endif
                                @if ($payment->paidBy)
                                    <span class="text-muted" style="font-size:12px;">
                                        <i class="fe fe-user me-1"></i>Processed by {{ $payment->paidBy->name }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="text-end flex-shrink-0">
                            <span class="badge bg-light-success text-success fw-semibold">
                                <i class="fe fe-check me-1"></i>Received
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4 text-muted">
                        <i class="fe fe-inbox fs-5 d-block mb-2"></i>
                        No payment records found.
                    </div>
                @endforelse
            </div>

            {{-- ── Footer ── --}}
            <div style="background:var(--inv-surface);border-top:1px solid var(--inv-border);padding:20px 32px;">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <p class="mb-0 text-muted" style="font-size:12px;">
                            <i class="fe fe-home me-1"></i>
                            ICT Professional Training Center &middot; Generated {{ now()->format('d M Y') }}
                        </p>
                    </div>
                    <div class="col-md-6 text-md-end mt-2 mt-md-0">
                        <p class="mb-0 text-muted" style="font-size:12px;">
                            Invoice Ref: <strong class="text-dark">{{ $invoice->invoice_code }}</strong>
                        </p>
                    </div>
                </div>
            </div>

        </div>
    @else
        <div class="card">
            <div class="card-body text-center py-6">
                <i class="fe fe-file-minus fs-1 text-muted d-block mb-3"></i>
                <h5 class="text-muted">No invoice found for this student in this course.</h5>
                <a href="{{ route('admin.courses.realtime.show', $course->id) }}" class="btn btn-outline-primary mt-3">
                    <i class="fe fe-arrow-left me-1"></i> Back to Course
                </a>
            </div>
        </div>
    @endif

@endsection
