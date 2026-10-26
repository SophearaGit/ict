@extends('admin.layouts.master')
@section('page_title', isset($page_title) ? $page_title : 'ICT | ADMIN | INVOICES')

@push('styles')
    <style>
        /* ── Invoice mobile card list ─────────────────────────────────────────
           Below md, the 10-column table is replaced with stacked cards so
           nothing requires horizontal scrolling to read on a phone. */
        .invoice-card {
            padding: 1rem 1.25rem;
            transition: background-color .15s ease;
        }

        .invoice-card:hover {
            background-color: var(--gk-list-group-action-hover-bg);
        }

        .invoice-card-totals {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: .5rem;
        }

        .invoice-card-totals .label {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: .04em;
        }
    </style>
@endpush

@section('content')
    <div class="row">
        <div class="col-lg-12 col-md-12 col-12">
            <div class="border-bottom pb-3 mb-3 d-md-flex align-items-center justify-content-between">
                <div class="mb-2 mb-md-0">
                    <h1 class="mb-1 h2 fw-bold">Invoices</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Invoices</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 col-md-12 col-12">
            <div class="card">
                <div class="card-header">
                    <form action="{{ route('admin.invoices.index') }}" method="GET"
                        class="d-flex flex-wrap align-items-center gap-2">
                        <div class="input-group" style="flex: 1 1 280px;">
                            <span class="input-group-text bg-transparent">
                                <i class="fe fe-search text-muted"></i>
                            </span>
                            <input type="search" class="form-control border-start-0" name="search"
                                placeholder="Search by invoice code, student or course"
                                value="{{ $search ?? '' }}">
                        </div>
                        <select name="status" class="form-select" style="max-width: 180px;"
                            onchange="this.form.submit()">
                            <option value="">All statuses</option>
                            <option value="paid" {{ $status === 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="half_paid" {{ $status === 'half_paid' ? 'selected' : '' }}>Half Paid
                            </option>
                            <option value="unpaid" {{ $status === 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                            <option value="free" {{ $status === 'free' ? 'selected' : '' }}>Free</option>
                        </select>
                        <button type="submit" class="btn btn-primary">
                            <i class="fe fe-filter me-1"></i> Filter
                        </button>
                        @if ($search || $status)
                            <a href="{{ route('admin.invoices.index') }}" class="btn btn-outline-secondary">
                                Clear
                            </a>
                        @endif
                    </form>
                </div>

                {{-- ── Desktop / tablet (>= md): table ─────────────────────────────── --}}
                <div class="table-responsive d-none d-md-block">
                    <table class="table mb-0 text-nowrap table-hover table-centered">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Invoice</th>
                                <th>Student</th>
                                <th>Course</th>
                                <th>Total</th>
                                <th>Paid</th>
                                <th>Remaining</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($invoices as $invoice)
                                @php
                                    $statusClass = in_array($invoice->payment_status, [
                                        'paid',
                                        'half_paid',
                                        'unpaid',
                                        'free',
                                    ])
                                        ? $invoice->payment_status
                                        : 'unpaid';
                                    $statusColor = match ($statusClass) {
                                        'paid' => 'bg-success-soft text-success',
                                        'half_paid' => 'bg-warning-soft text-warning',
                                        'free' => 'bg-info-soft text-info',
                                        default => 'bg-danger-soft text-danger',
                                    };
                                    $statusIcon = match ($statusClass) {
                                        'paid' => 'fe-check-circle',
                                        'half_paid' => 'fe-clock',
                                        'free' => 'fe-gift',
                                        default => 'fe-x-circle',
                                    };
                                    $statusLabel = $statusClass === 'half_paid' ? 'Half Paid' : ucfirst($statusClass);
                                @endphp
                                <tr>
                                    <td>{{ $invoices->firstItem() + $loop->index }}</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div
                                                class="icon-shape icon-sm rounded-circle bg-light-primary text-primary flex-shrink-0">
                                                <i class="fe fe-file-text"></i>
                                            </div>
                                            <span class="fw-semibold">{{ $invoice->invoice_code }}</span>
                                        </div>
                                    </td>
                                    <td class="text-capitalize">{{ $invoice->student->name ?? '—' }}</td>
                                    <td class="text-capitalize">{{ $invoice->course->title ?? '—' }}</td>
                                    <td>${{ number_format($invoice->total_amount, 2) }}</td>
                                    <td>${{ number_format($invoice->paid_amount, 2) }}</td>
                                    <td>${{ number_format($invoice->remaining_amount, 2) }}</td>
                                    <td>
                                        <span class="badge {{ $statusColor }}">
                                            <i class="fe {{ $statusIcon }} me-1"></i>{{ $statusLabel }}
                                        </span>
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($invoice->created_at)->format('d M, Y') }}</td>
                                    <td class="text-end">
                                        <button type="button" class="btn btn-sm btn-outline-primary"
                                            data-bs-toggle="modal" data-bs-target="#quickInvoiceModal"
                                            data-invoice-id="{{ $invoice->id }}">
                                            <i class="fe fe-eye me-1"></i> Quick View
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center py-5">
                                        <div
                                            class="icon-shape icon-lg rounded-circle bg-light-secondary text-secondary mx-auto mb-3">
                                            <i class="fe fe-file-text fs-3"></i>
                                        </div>
                                        <h5 class="mb-0">No invoices found.</h5>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- ── Mobile (< md): stacked cards ────────────────────────────────── --}}
                <div class="d-md-none">
                    @forelse ($invoices as $invoice)
                        @php
                            $statusClass = in_array($invoice->payment_status, [
                                'paid',
                                'half_paid',
                                'unpaid',
                                'free',
                            ])
                                ? $invoice->payment_status
                                : 'unpaid';
                            $statusColor = match ($statusClass) {
                                'paid' => 'bg-success-soft text-success',
                                'half_paid' => 'bg-warning-soft text-warning',
                                'free' => 'bg-info-soft text-info',
                                default => 'bg-danger-soft text-danger',
                            };
                            $statusIcon = match ($statusClass) {
                                'paid' => 'fe-check-circle',
                                'half_paid' => 'fe-clock',
                                'free' => 'fe-gift',
                                default => 'fe-x-circle',
                            };
                            $statusLabel = $statusClass === 'half_paid' ? 'Half Paid' : ucfirst($statusClass);
                        @endphp
                        <div class="invoice-card border-bottom">
                            <div class="d-flex align-items-center justify-content-between mb-2 gap-2">
                                <div class="d-flex align-items-center gap-2 min-w-0">
                                    <div
                                        class="icon-shape icon-sm rounded-circle bg-light-primary text-primary flex-shrink-0">
                                        <i class="fe fe-file-text"></i>
                                    </div>
                                    <span class="fw-semibold text-truncate">{{ $invoice->invoice_code }}</span>
                                </div>
                                <span class="badge {{ $statusColor }} flex-shrink-0">
                                    <i class="fe {{ $statusIcon }} me-1"></i>{{ $statusLabel }}
                                </span>
                            </div>
                            <div class="small text-capitalize mb-1">
                                <i class="fe fe-user me-1 text-muted"></i>{{ $invoice->student->name ?? '—' }}
                            </div>
                            <div class="small text-muted text-capitalize mb-3">
                                <i class="fe fe-book me-1"></i>{{ $invoice->course->title ?? '—' }}
                            </div>
                            <div class="invoice-card-totals small mb-3">
                                <div>
                                    <div class="label text-muted">Total</div>
                                    <div class="fw-semibold">${{ number_format($invoice->total_amount, 2) }}</div>
                                </div>
                                <div>
                                    <div class="label text-muted">Paid</div>
                                    <div class="fw-semibold text-success">${{ number_format($invoice->paid_amount, 2) }}</div>
                                </div>
                                <div>
                                    <div class="label text-muted">Remaining</div>
                                    <div class="fw-semibold">${{ number_format($invoice->remaining_amount, 2) }}</div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center justify-content-between gap-2">
                                <small class="text-muted">
                                    <i class="fe fe-calendar me-1"></i>{{ \Carbon\Carbon::parse($invoice->created_at)->format('d M, Y') }}
                                </small>
                                <button type="button" class="btn btn-sm btn-outline-primary"
                                    data-bs-toggle="modal" data-bs-target="#quickInvoiceModal"
                                    data-invoice-id="{{ $invoice->id }}">
                                    <i class="fe fe-eye me-1"></i> Quick View
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-5">
                            <div class="icon-shape icon-lg rounded-circle bg-light-secondary text-secondary mx-auto mb-3">
                                <i class="fe fe-file-text fs-3"></i>
                            </div>
                            <p class="mb-0 small">No invoices found.</p>
                        </div>
                    @endforelse
                </div>

                @if ($invoices->hasPages())
                    <div class="p-3">
                        @include('admin.partials.pagination', ['paginator' => $invoices])
                    </div>
                @endif
            </div>
        </div>
    </div>

    @include('admin.pages.invoices.partials.quick-view-modal')
@endsection
