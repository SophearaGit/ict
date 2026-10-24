@extends('frontend.staff.layout.master')
@section('page_title', isset($page_title) ? $page_title : 'Reports')
@push('styles')
    {{-- Remove this <link> if flatpickr's CSS is already loaded globally by your layout. --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <style>
        /* ── ACTION ROW: stat cards + Add Report button, side by side ── */
        .report-action-row {
            display: grid;
            grid-template-columns: 1fr auto;
            align-items: stretch;
            gap: 14px;
            margin-bottom: 18px;
            font-family: 'DM Sans', sans-serif;
        }

        .report-stat-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 14px;
        }

        .report-stat-card {
            background: #fff;
            border: 1px solid #f0f1f5;
            border-radius: 16px;
            padding: 16px 18px;
            display: flex;
            align-items: center;
            gap: 12px;
            box-shadow: 0 2px 10px rgba(20, 20, 43, 0.04);
            text-decoration: none;
            position: relative;
            overflow: hidden;
            transition: transform 0.15s, box-shadow 0.15s, border-color 0.15s;
        }

        a.report-stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 24px rgba(20, 20, 43, 0.1);
            border-color: #e3e5f0;
            color: inherit;
        }

        .report-stat-card::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: var(--accent, transparent);
            opacity: 0;
            transition: opacity 0.15s;
        }

        .report-stat-card.active::before,
        a.report-stat-card:hover::before {
            opacity: 1;
        }

        .report-stat-card.active {
            border-color: var(--accent, #e3e5f0);
            background: var(--accent-tint, #fff);
        }

        .report-stat-icon {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
        }

        .report-stat-value {
            font-weight: 800;
            font-size: 22px;
            color: #16151f;
            line-height: 1.2;
            font-variant-numeric: tabular-nums;
        }

        .report-stat-label {
            font-size: 12px;
            color: #8a8a9a;
            font-weight: 500;
            margin-top: 2px;
        }

        .report-toolbar {
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .report-toolbar-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            padding: 12px 20px;
            border-radius: 12px;
            font-size: 13.5px;
            font-weight: 600;
            font-family: 'DM Sans', sans-serif;
            text-decoration: none;
            white-space: nowrap;
            background: #0f0e17;
            color: #fff;
            border: 1px solid #0f0e17;
            transition: all 0.15s;
            height: 100%;
            cursor: pointer;
        }

        .report-toolbar-btn:hover {
            background: #2749c4;
            border-color: #2749c4;
            color: #fff;
        }

        @media (max-width: 991.98px) {
            .report-action-row {
                grid-template-columns: 1fr;
            }

            .report-toolbar-btn {
                height: auto;
            }
        }

        @media (max-width: 575.98px) {
            .report-stat-grid {
                grid-template-columns: 1fr;
                gap: 10px;
            }
        }

        /* ── FILTER BAR ── */
        .report-filter-card {
            background: #fff;
            border: 1px solid #f0f1f5;
            border-radius: 16px;
            padding: 14px 16px;
            margin-bottom: 18px;
            box-shadow: 0 2px 10px rgba(20, 20, 43, 0.04);
        }

        .report-filter-row {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 12px;
        }

        .report-tabs {
            display: inline-flex;
            flex-wrap: wrap;
            gap: 4px;
            background: #f4f4f8;
            border-radius: 11px;
            padding: 4px;
        }

        .report-tab {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 14px;
            border-radius: 8px;
            font-family: 'DM Sans', sans-serif;
            font-size: 13px;
            font-weight: 600;
            color: #6b6b7a;
            text-decoration: none;
            transition: all 0.15s;
            white-space: nowrap;
        }

        .report-tab:hover {
            color: #22212e;
        }

        .report-tab.active {
            background: #0f0e17;
            color: #fff;
        }

        .report-search-form {
            flex: 1 1 220px;
            min-width: 180px;
            margin-left: auto;
        }

        .report-search-wrap {
            position: relative;
        }

        .report-search-wrap i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #a3a3b0;
            font-size: 16px;
        }

        .report-search-wrap input {
            width: 100%;
            padding: 10px 14px 10px 38px;
            border-radius: 10px;
            border: 1px solid #e5e5ec;
            background: #f8f8fb;
            font-family: 'DM Sans', sans-serif;
            font-size: 13.5px;
            color: #22212e;
            transition: border-color 0.15s, background 0.15s;
        }

        .report-search-wrap input:focus {
            outline: none;
            border-color: #4f46e5;
            background: #fff;
        }

        @media (max-width: 575.98px) {
            .report-search-form {
                flex: 1 1 100%;
                margin-left: 0;
            }
        }

        /* ── LIST WRAPPER ── */
        .report-list-card {
            background: #fff;
            border: 1px solid #f0f1f5;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(20, 20, 43, 0.04);
        }

        .report-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
            padding: 18px;
        }

        @media (max-width: 991.98px) {
            .report-grid {
                grid-template-columns: 1fr;
            }
        }

        .report-card {
            border: 1px solid #f0f1f5;
            border-radius: 14px;
            padding: 16px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            font-family: 'DM Sans', sans-serif;
            transition: box-shadow 0.15s, border-color 0.15s;
        }

        .report-card:hover {
            border-color: #e3e5f0;
            box-shadow: 0 4px 14px rgba(20, 20, 43, 0.05);
        }

        .report-card-top {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 10px;
        }

        .report-card-title-group {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px;
            min-width: 0;
        }

        .report-card-title {
            font-weight: 700;
            font-size: 14.5px;
            color: #16151f;
            margin: 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .report-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11.5px;
            font-weight: 600;
            white-space: nowrap;
        }

        .report-badge-pending {
            background: #fef3c7;
            color: #d97706;
        }

        .report-badge-reviewed {
            background: #dcfce7;
            color: #16a34a;
        }

        .report-card-top .btn-ghost {
            border: none;
            background: transparent;
            color: #9a9aa8;
            width: 32px;
            height: 32px;
            border-radius: 9px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.15s;
            flex-shrink: 0;
        }

        .report-card-top .btn-ghost:hover {
            background: #f4f4f8;
            color: #22212e;
        }

        .report-card-meta {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 8px;
            font-size: 12.5px;
            color: #9a9aa8;
        }

        .report-period-link {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            color: #4f46e5;
            text-decoration: underline;
            text-decoration-color: rgba(79, 70, 229, 0.35);
            font-weight: 600;
        }

        .report-period-link:hover {
            color: #2749c4;
        }

        .report-meta-sep {
            color: #d4d4dc;
        }

        .report-content-body {
            font-size: 13px;
            line-height: 1.55;
            color: #4b4b58;
            max-width: 100%;
            overflow-x: auto;
        }

        .report-content-body p {
            margin: 0 0 8px;
        }

        .report-content-body p:last-child {
            margin-bottom: 0;
        }

        .report-content-body table {
            font-size: 12px;
            max-width: none;
        }

        .report-content-body img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
        }

        .report-reviewed-note {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            color: #9a9aa8;
            padding-top: 8px;
            border-top: 1px dashed #f0f1f5;
        }

        .report-reviewed-note i {
            color: #16a34a;
        }

        .report-card-footer {
            padding: 14px 16px;
            border-top: 1px solid #f0f1f5;
        }

        /* ── EMPTY STATE ── */
        .report-empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 64px 24px;
            text-align: center;
            gap: 6px;
            font-family: 'DM Sans', sans-serif;
        }

        .report-empty-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background: rgba(79, 70, 229, 0.08);
            margin-bottom: 10px;
        }

        .report-empty-icon i {
            font-size: 26px;
            color: #4f46e5;
        }

        .report-empty-state .report-empty-title {
            font-weight: 700;
            font-size: 16px;
            color: #16151f;
        }

        .report-empty-state p {
            font-size: 13.5px;
            color: #9a9aa8;
            margin: 0;
            max-width: 340px;
        }

        .report-empty-cta {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            margin-top: 10px;
            padding: 10px 18px;
            border-radius: 10px;
            background: #0f0e17;
            color: #fff;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            transition: background 0.15s;
            border: none;
            cursor: pointer;
        }

        .report-empty-cta:hover {
            background: #2749c4;
            color: #fff;
        }

        /* ── MODAL (Add / Edit Report) ── */
        #dynamic_report_modal .modal-content {
            border-radius: 16px;
            border: none;
            overflow: hidden;
        }

        #dynamic_report_modal .modal-header {
            font-family: 'DM Sans', sans-serif;
            border-bottom: 1px solid #f0f1f5;
            padding: 18px 22px;
        }

        #dynamic_report_modal .modal-title {
            font-weight: 700;
            font-size: 16.5px;
            color: #16151f;
        }

        #dynamic_report_modal .modal-body {
            padding: 20px 22px;
        }

        #dynamic_report_modal .modal-footer {
            border-top: 1px solid #f0f1f5;
            padding: 16px 22px;
        }

        .report-modal-label {
            font-family: 'DM Sans', sans-serif;
            font-size: 13px;
            font-weight: 600;
            color: #33323f;
            margin-bottom: 7px;
            display: block;
        }

        .report-modal-textarea {
            border-radius: 11px;
            border: 1.5px solid #e5e5ec;
            font-family: 'DM Sans', sans-serif;
            font-size: 14px;
        }

        .report-modal-btn-cancel {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 10px 18px;
            border-radius: 11px;
            font-family: 'DM Sans', sans-serif;
            font-size: 13.5px;
            font-weight: 600;
            background: #fff;
            color: #4b4a56;
            border: 1.5px solid #e5e5ec;
            cursor: pointer;
            transition: all 0.15s;
        }

        .report-modal-btn-cancel:hover {
            background: #f4f4f8;
            color: #22212e;
        }

        .report-modal-btn-submit {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 10px 20px;
            border-radius: 11px;
            font-family: 'DM Sans', sans-serif;
            font-size: 13.5px;
            font-weight: 600;
            background: #0f0e17;
            color: #fff;
            border: 1px solid #0f0e17;
            cursor: pointer;
            transition: all 0.15s;
        }

        .report-modal-btn-submit:hover {
            background: #2749c4;
            border-color: #2749c4;
            color: #fff;
        }

        .report-modal-btn-submit:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }
    </style>
@endpush
@section('content')
    @include('frontend.staff.pages.partials.breadcrumb')

    @php
        $curStatus = request('status', '');
    @endphp

    <div class="report-action-row">
        <div class="report-stat-grid">
            <a href="{{ route('staff.reports.index') }}"
                class="report-stat-card {{ $curStatus === '' ? 'active' : '' }}"
                style="--accent:#4f46e5;--accent-tint:#eef2ff;">
                <span class="report-stat-icon" style="background:#eef2ff;color:#4f46e5;"><i
                        class="ti ti-list"></i></span>
                <div>
                    <div class="report-stat-value">{{ $total_count }}</div>
                    <div class="report-stat-label">Total Reports</div>
                </div>
            </a>
            <a href="{{ route('staff.reports.index', ['status' => 'pending']) }}"
                class="report-stat-card {{ $curStatus === 'pending' ? 'active' : '' }}"
                style="--accent:#d97706;--accent-tint:#fef3c7;">
                <span class="report-stat-icon" style="background:#fef3c7;color:#d97706;"><i
                        class="ti ti-alert-triangle"></i></span>
                <div>
                    <div class="report-stat-value">{{ $pending_count }}</div>
                    <div class="report-stat-label">Not Reviewed</div>
                </div>
            </a>
            <a href="{{ route('staff.reports.index', ['status' => 'reviewed']) }}"
                class="report-stat-card {{ $curStatus === 'reviewed' ? 'active' : '' }}"
                style="--accent:#16a34a;--accent-tint:#dcfce7;">
                <span class="report-stat-icon" style="background:#dcfce7;color:#16a34a;"><i
                        class="ti ti-circle-check"></i></span>
                <div>
                    <div class="report-stat-value">{{ $reviewed_count }}</div>
                    <div class="report-stat-label">Reviewed</div>
                </div>
            </a>
        </div>
        <div class="report-toolbar">
            <a href="javascript:void(0)" class="report-toolbar-btn add_report_btn">
                <i class="ti ti-plus"></i> Add Report
            </a>
        </div>
    </div>

    <div class="report-filter-card">
        <div class="report-filter-row">
            <div class="report-tabs">
                <a href="javascript:void(0)" data-status="" id="all-category"
                    class="status-tab report-tab {{ $curStatus === '' ? 'active' : '' }}">
                    <i class="ti ti-list"></i> <span>All Reports</span>
                </a>
                <a href="javascript:void(0)" data-status="pending" id="note-important"
                    class="status-tab report-tab {{ $curStatus === 'pending' ? 'active' : '' }}">
                    <i class="ti ti-alert-triangle"></i> <span>Not Reviewed</span>
                </a>
                <a href="javascript:void(0)" data-status="reviewed" id="note-social"
                    class="status-tab report-tab {{ $curStatus === 'reviewed' ? 'active' : '' }}">
                    <i class="ti ti-check"></i> <span>Reviewed</span>
                </a>
            </div>
            <form action="{{ route('staff.reports.index') }}" method="GET" class="report-search-form">
                <input type="hidden" name="status" value="{{ $curStatus }}">
                <div class="report-search-wrap">
                    <i class="ti ti-search"></i>
                    <input type="search" name="search" placeholder="Search reports..."
                        value="{{ request('search') }}">
                </div>
            </form>
        </div>
    </div>

    <div class="report-list-card">
        @if ($reports->isEmpty())
            <div class="report-empty-state">
                <div class="report-empty-icon"><i class="ti ti-report"></i></div>
                @if (request('search') || request('status'))
                    <div class="report-empty-title">No reports match this view</div>
                    <p>Try a different search term or clear the current filter.</p>
                    <a href="{{ route('staff.reports.index') }}" class="report-empty-cta">
                        <i class="ti ti-x"></i> Clear filters
                    </a>
                @else
                    <div class="report-empty-title">No reports yet</div>
                    <p>Submit your first weekly report to get started.</p>
                    <a href="javascript:void(0)" class="report-empty-cta add_report_btn">
                        <i class="ti ti-plus"></i> Add Report
                    </a>
                @endif
            </div>
        @else
            <div class="report-grid">
                @foreach ($reports as $report)
                    <div class="report-card">
                        <div class="report-card-top">
                            <div class="report-card-title-group">
                                <h3 class="report-card-title">Weekly Report #{{ $report->id }}</h3>
                                <span
                                    class="report-badge {{ $report->status === 'reviewed' ? 'report-badge-reviewed' : 'report-badge-pending' }}">
                                    <i
                                        class="ti {{ $report->status === 'reviewed' ? 'ti-circle-check' : 'ti-clock-hour-4' }}"></i>
                                    {{ ucfirst($report->status) }}
                                </span>
                            </div>
                            <div class="dropdown">
                                <button class="btn-ghost" type="button" data-bs-toggle="dropdown">
                                    <i class="ti ti-dots-vertical"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item edit-note edit_report_btn" href="javascript:void(0)"
                                            data-id="{{ $report->id }}">
                                            <i class="ti ti-edit dropdown-item-icon"></i> Edit
                                        </a></li>
                                    <li><a class="dropdown-item change-period-btn" href="javascript:void(0)"
                                            data-id="{{ $report->id }}"
                                            data-start="{{ $report->period_start ?? $report->created_at->format('Y-m-d') }}"
                                            data-end="{{ $report->period_end ?? ($report->period_start ?? $report->created_at->format('Y-m-d')) }}"
                                            data-url="{{ route('staff.reports.updatePeriod', $report->id) }}">
                                            <i class="ti ti-calendar-event dropdown-item-icon"></i> Change Period
                                        </a></li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li><a class="dropdown-item text-danger delete-note del_report_btn"
                                            href="javascript:void(0)"
                                            data-url="{{ route('staff.reports.destroy', $report->id) }}">
                                            <i class="ti ti-trash dropdown-item-icon"></i> Delete
                                        </a></li>
                                </ul>
                            </div>
                        </div>

                        <div class="report-card-meta">
                            <a href="javascript:void(0)" class="report-period-link change-period-btn"
                                data-id="{{ $report->id }}"
                                data-start="{{ $report->period_start ?? $report->created_at->format('Y-m-d') }}"
                                data-end="{{ $report->period_end ?? ($report->period_start ?? $report->created_at->format('Y-m-d')) }}"
                                data-url="{{ route('staff.reports.updatePeriod', $report->id) }}"
                                title="Click to change the period this report covers">
                                <i class="ti ti-calendar-event"></i> {{ $report->period_label }}
                            </a>
                            <span class="report-meta-sep">&middot;</span>
                            <span class="report-submitted">submitted {{ $report->created_at->diffForHumans() }}</span>
                        </div>

                        <div class="report-content-body">
                            {!! $report->report_content !!}
                        </div>

                        @if ($report->status == 'reviewed' && $report->reviewed_at)
                            <div class="report-reviewed-note">
                                <i class="ti ti-user-check"></i> Reviewed by {{ $report->reviewer->name ?? 'N/A' }} on
                                {{ $report->reviewed_at->format('d M Y') }}
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif

        @if ($reports->hasPages())
            <div class="report-card-footer">
                {{ $reports->links('components.ui-pagination') }}
            </div>
        @endif
    </div>

    <div class="modal fade" id="dynamic_report_modal" tabindex="-1" aria-labelledby="bs-example-modal-lg"
        aria-hidden="true">
        <div class="modal-dialog modal-xl dynamic_report_modal_content"></div>
    </div>
@endsection
@push('scripts')
    {{-- Remove this <script> if flatpickr is already loaded globally by your layout. --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.js"></script>

    <script>
        $('.status-tab').on('click', function(e) {
            e.preventDefault();
            const status = $(this).data('status');
            const url = new URL(window.location.origin + window.location.pathname);
            if (status !== '') url.searchParams.set('status', status);
            url.searchParams.delete('page');
            window.location.href = url.toString();
        });
        let report_loader = `<div class="d-flex justify-content-center align-items-center" style="height: 200px;">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>`;

        function loadReportModal(url) {
            $('#dynamic_report_modal').modal('show');
            $.ajax({
                method: 'GET',
                url: url,
                beforeSend: function() {
                    $('.dynamic_report_modal_content').html(report_loader);
                },
                success: function(data) {
                    $('.dynamic_report_modal_content').html(data);
                },
                error: function() {
                    $('.dynamic_report_modal_content').html(
                        '<div class="card card-body text-danger">Failed to load. Try again.</div>'
                    );
                },
            });
        }
        $('.add_report_btn').on('click', function(e) {
            e.preventDefault();
            loadReportModal(base_url + `/staff/reports/create`);
        });
        $(document).on('click', '.edit_report_btn', function(e) {
            e.preventDefault();
            let report_id = $(this).data('id');
            loadReportModal(base_url + `/staff/reports/${report_id}/edit`);
        });
        $(document).on('click', '.del_report_btn', function(e) {
            e.preventDefault();
            let url = $(this).data('url');
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        method: "DELETE",
                        url: url,
                        data: {
                            _token: csrf_token
                        },
                        success: function(data) {
                            iziToast.success({
                                message: data.message,
                                position: 'bottomRight'
                            });
                            window.location.reload();
                        },
                        error: function(xhr) {
                            iziToast.error({
                                message: xhr.responseJSON?.message || 'Delete failed.',
                                position: 'bottomRight'
                            });
                        },
                    });
                }
            });
        });

        // --- Change Period: click opens a flatpickr RANGE picker anchored near
        // the clicked link. onClose (not onChange) fires exactly once, with the
        // final selection, once the user finishes picking both ends — using
        // onChange instead would catch intermediate/incomplete selection
        // states while the range is still being picked. ---
        let changePeriodUrl = null;
        let changePeriodOriginal = null;

        const changePeriodInput = document.createElement('input');
        changePeriodInput.type = 'text';
        changePeriodInput.style.position = 'absolute';
        changePeriodInput.style.opacity = '0';
        changePeriodInput.style.pointerEvents = 'none';
        document.body.appendChild(changePeriodInput);

        // Build Y-m-d from *local* date parts — Date#toISOString() converts to
        // UTC first, which silently shifts the date back a day in any
        // timezone ahead of UTC.
        function toLocalDateStr(d) {
            const y = d.getFullYear();
            const m = String(d.getMonth() + 1).padStart(2, '0');
            const day = String(d.getDate()).padStart(2, '0');
            return `${y}-${m}-${day}`;
        }

        const changePeriodPicker = flatpickr(changePeriodInput, {
            mode: 'range',
            dateFormat: 'Y-m-d',
            maxDate: 'today',
            onClose: function(selectedDates) {
                if (selectedDates.length < 2 || !changePeriodUrl) return;

                const startStr = toLocalDateStr(selectedDates[0]);
                const endStr = toLocalDateStr(selectedDates[1]);

                // Closed without actually changing anything — don't bother
                // confirming a no-op update.
                if (changePeriodOriginal && startStr === changePeriodOriginal[0] && endStr ===
                    changePeriodOriginal[1]) {
                    return;
                }

                const label = startStr === endStr ? startStr : `${startStr} → ${endStr}`;

                Swal.fire({
                    title: 'Update report period?',
                    text: `Set this report's period to ${label}?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, save it',
                }).then((result) => {
                    if (!result.isConfirmed) return;

                    $.ajax({
                        method: 'PATCH',
                        url: changePeriodUrl,
                        data: {
                            _token: csrf_token,
                            period_start: startStr,
                            period_end: endStr,
                        },
                        success: function(data) {
                            iziToast.success({
                                message: data.message,
                                position: 'bottomRight',
                            });
                            window.location.reload();
                        },
                        error: function(xhr) {
                            iziToast.error({
                                message: xhr.responseJSON?.message ||
                                    'Failed to update period.',
                                position: 'bottomRight',
                            });
                        },
                    });
                });
            },
        });

        // Capture the clicked element's position in the CAPTURE phase, before
        // Bootstrap's own dropdown-close handler (bound in the bubble phase)
        // can hide the menu and zero out getBoundingClientRect() on anything
        // inside it -- that race was why "Change Period" clicked from the
        // kebab dropdown opened the calendar pinned to the top-left corner
        // instead of near the link.
        let pendingPeriodRect = null;
        document.addEventListener('click', function(e) {
            const btn = e.target.closest('.change-period-btn');
            if (btn) {
                pendingPeriodRect = btn.getBoundingClientRect();
            }
        }, true);

        $(document).on('click', '.change-period-btn', function(e) {
            e.preventDefault();
            changePeriodUrl = $(this).data('url');
            changePeriodOriginal = [$(this).data('start').toString(), $(this).data('end').toString()];

            // Anchor the calendar to OUR OWN hidden input (always attached,
            // never display:none) rather than to `this` -- `this` may be a
            // Bootstrap dropdown-item that's already hidden by the time
            // flatpickr reads a position element's rect inside open(). Move
            // that input to the rect captured above first.
            const rect = pendingPeriodRect || this.getBoundingClientRect();
            changePeriodInput.style.top = `${window.scrollY + rect.bottom}px`;
            changePeriodInput.style.left = `${window.scrollX + rect.left}px`;

            if (changePeriodPicker.isOpen) {
                changePeriodPicker.close();
            }
            changePeriodPicker.set('positionElement', changePeriodInput);
            changePeriodPicker.setDate([$(this).data('start'), $(this).data('end')], false);
            changePeriodPicker.open();
        });
    </script>
@endpush
