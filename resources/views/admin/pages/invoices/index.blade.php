@extends('admin.layouts.master')
@section('page_title', isset($page_title) ? $page_title : 'ICT | ADMIN | INVOICES')

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
                        class="row g-2 align-items-center">
                        <div class="col-md-6">
                            <input type="search" class="form-control" name="search"
                                placeholder="Search by invoice code, student or course"
                                value="{{ $search ?? '' }}">
                        </div>
                        <div class="col-md-3">
                            <select name="status" class="form-select" onchange="this.form.submit()">
                                <option value="">All statuses</option>
                                <option value="paid" {{ $status === 'paid' ? 'selected' : '' }}>Paid</option>
                                <option value="half_paid" {{ $status === 'half_paid' ? 'selected' : '' }}>Half Paid
                                </option>
                                <option value="unpaid" {{ $status === 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                                <option value="free" {{ $status === 'free' ? 'selected' : '' }}>Free</option>
                            </select>
                        </div>
                        <div class="col-md-3 d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fe fe-search me-1"></i> Filter
                            </button>
                            @if ($search || $status)
                                <a href="{{ route('admin.invoices.index') }}" class="btn btn-outline-secondary">
                                    Clear
                                </a>
                            @endif
                        </div>
                    </form>
                </div>

                <div class="table-responsive">
                    <table class="table mb-0 text-nowrap table-hover table-centered">
                        <thead class="table-light">
                            <tr>
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
                                    $statusLabel = $statusClass === 'half_paid' ? 'Half Paid' : ucfirst($statusClass);
                                @endphp
                                <tr>
                                    <td class="fw-semibold">{{ $invoice->invoice_code }}</td>
                                    <td class="text-capitalize">{{ $invoice->student->name ?? '—' }}</td>
                                    <td class="text-capitalize">{{ $invoice->course->title ?? '—' }}</td>
                                    <td>${{ number_format($invoice->total_amount, 2) }}</td>
                                    <td>${{ number_format($invoice->paid_amount, 2) }}</td>
                                    <td>${{ number_format($invoice->remaining_amount, 2) }}</td>
                                    <td>
                                        <span class="badge {{ $statusColor }}">{{ $statusLabel }}</span>
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
                                    <td colspan="9" class="text-center">
                                        <h5 class="mb-0">No invoices found.</h5>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    @if ($invoices->hasPages())
                        <div class="p-3">
                            @include('admin.partials.pagination', ['paginator' => $invoices])
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @include('admin.pages.invoices.partials.quick-view-modal')
@endsection
