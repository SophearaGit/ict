@extends('frontend.staff.layout.master')
@section('page_title', isset($page_title) ? $page_title : 'Page Title Here')
@push('styles')
    {{-- Remove this <link> if flatpickr's CSS is already loaded globally by your layout. --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.css">
@endpush
@section('content')
    @include('frontend.staff.pages.partials.breadcrumb')
    <ul class="nav nav-pills p-3 mb-3 rounded align-items-center card flex-row">
        <li class="nav-item">
            <a href="javascript:void(0)" data-status=""
                class="status-tab nav-link note-link d-flex align-items-center justify-content-center px-3 px-md-3 me-0 me-md-2 text-body-color {{ request('status', '') == '' ? 'active' : '' }}"
                id="all-category">
                <i class="ti ti-list fill-white me-0 me-md-1"></i>
                <span class="d-none d-md-block font-weight-medium">All Reports</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="javascript:void(0)" data-status="pending" id="note-important"
                class="status-tab nav-link note-link d-flex align-items-center justify-content-center px-3 px-md-3 me-0 me-md-2 text-body-color {{ request('status') == 'pending' ? 'active' : '' }}">
                <i class="ti ti-alert-triangle fill-white me-0 me-md-1"></i>
                <span class="d-none d-md-block font-weight-medium">Not Reviewed</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="javascript:void(0)" data-status="reviewed" id="note-social"
                class="status-tab nav-link note-link d-flex align-items-center justify-content-center px-3 px-md-3 me-0 me-md-2 text-body-color {{ request('status') == 'reviewed' ? 'active' : '' }}">
                <i class="ti ti-check fill-white me-0 me-md-1"></i>
                <span class="d-none d-md-block font-weight-medium">Reviewed</span>
            </a>
        </li>
        <li class="nav-item ms-2" style="min-width:220px;">
            <form action="{{ route('staff.reports.index') }}" method="GET" class="d-flex">
                <input type="hidden" name="status" value="{{ request('status') }}">
                <input type="search" name="search" class="form-control form-control-sm" placeholder="Search reports..."
                    value="{{ request('search') }}">
            </form>
        </li>
        <li class="nav-item ms-auto">
            <a href="javascript:void(0)" class="btn btn-primary d-flex align-items-center px-3 add_report_btn">
                <i class="ti ti-file me-0 me-md-1 fs-4"></i>
                <span class="d-none d-md-block font-weight-medium fs-3">Add Report</span>
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div id="note-full-container" class="note-has-grid row">
            @forelse ($reports as $report)
                <div
                    class="col-md-6 single-note-item all-category {{ $report->status == 'pending' ? 'note-important' : 'note-social' }}">
                    <div class="card card-body">
                        <span class="side-stick"></span>
                        <div class="d-flex align-items-center justify-content-between">
                            <h6 class="note-title text-truncate w-75 mb-0">
                                Weekly Report #{{ $report->id }}
                            </h6>
                            <span class="badge {{ $report->status == 'pending' ? 'bg-warning' : 'bg-success' }}">
                                {{ ucfirst($report->status) }}
                            </span>
                        </div>
                        <p class="note-date fs-2">
                            <a href="javascript:void(0)" class="change-period-btn text-body-color text-decoration-underline"
                                data-id="{{ $report->id }}"
                                data-start="{{ $report->period_start ?? $report->created_at->format('Y-m-d') }}"
                                data-end="{{ $report->period_end ?? ($report->period_start ?? $report->created_at->format('Y-m-d')) }}"
                                data-url="{{ route('staff.reports.updatePeriod', $report->id) }}"
                                title="Click to change the period this report covers">
                                <i class="ti ti-calendar-event fs-3 me-1"></i>{{ $report->period_label }}
                            </a>
                            <strong>( submitted {{ $report->created_at->diffForHumans() }} )</strong>
                        </p>
                        <div class="note-content">
                            <p>{!! $report->report_content !!}</p>
                        </div>
                        @if ($report->status == 'reviewed' && $report->reviewed_at)
                            <p class="text-muted fs-2 mb-0">
                                Reviewed by {{ $report->reviewer->name ?? 'N/A' }}
                                on {{ $report->reviewed_at->format('d M Y') }}
                            </p>
                        @endif
                        <div class="d-flex align-items-center">
                            <div class="ms-auto">
                                <div class="category-selector btn-group">
                                    <a class="nav-link category-dropdown label-group p-0" data-bs-toggle="dropdown"
                                        href="#" role="button" aria-haspopup="true" aria-expanded="true">
                                        <i class="ti ti-dots-vertical fs-5"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right category-menu">
                                        <a class="dropdown-item edit-note edit_report_btn" href="javascript:void(0)"
                                            data-id="{{ $report->id }}">
                                            <i class="ti ti-edit fs-5 me-2"></i> Edit
                                        </a>
                                        <a class="dropdown-item change-period-btn" href="javascript:void(0)"
                                            data-id="{{ $report->id }}"
                                            data-start="{{ $report->period_start ?? $report->created_at->format('Y-m-d') }}"
                                            data-end="{{ $report->period_end ?? ($report->period_start ?? $report->created_at->format('Y-m-d')) }}"
                                            data-url="{{ route('staff.reports.updatePeriod', $report->id) }}">
                                            <i class="ti ti-calendar-event fs-5 me-2"></i> Change Period
                                        </a>
                                        <a class="dropdown-item delete-note del_report_btn" href="javascript:void(0)"
                                            data-url="{{ route('staff.reports.destroy', $report->id) }}">
                                            <i class="ti ti-trash fs-5 me-2"></i> Delete
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="d-flex justify-content-center align-items-center" style="height: 200px;">
                    <p class="text-muted fs-4">No reports found.</p>
                </div>
            @endforelse
        </div>
        @if ($reports->hasPages())
            <div class="mt-3">
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

        $(document).on('click', '.change-period-btn', function(e) {
            e.preventDefault();
            changePeriodUrl = $(this).data('url');
            changePeriodOriginal = [$(this).data('start').toString(), $(this).data('end').toString()];
            changePeriodPicker.set('positionElement', this);
            changePeriodPicker.setDate([$(this).data('start'), $(this).data('end')], false);
            changePeriodPicker.open();
        });
    </script>
@endpush
