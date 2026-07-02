@extends('admin.layouts.master')
@section('page_title', isset($page_title) ? $page_title : 'Page Title Here')
@push('styles')
    <style>
        .btn-icon {
            width: 32px;
            height: 32px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
    </style>
@endpush
@section('content')
    {{-- Toolbar: tabs + view toggle --}}
    <div class="card mb-3 border-0 shadow-sm">
        <div class="card-body py-2 d-flex align-items-center flex-wrap gap-2">
            <ul class="nav nav-pills mb-0 gap-1">
                <li class="nav-item">
                    <a href="{{ route('admin.staff-report.index', request()->except('status')) }}"
                        class="nav-link {{ request('status', '') == '' ? 'active' : '' }}">
                        All
                        <span class="badge bg-white text-dark ms-1">{{ $counts['all'] ?? '' }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.staff-report.index', array_merge(request()->except('status'), ['status' => 'pending'])) }}"
                        class="nav-link {{ request('status') == 'pending' ? 'active' : '' }}">
                        Not Reviewed
                        @if (!empty($counts['pending']))
                            <span class="badge bg-warning text-dark ms-1">{{ $counts['pending'] }}</span>
                        @endif
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.staff-report.index', array_merge(request()->except('status'), ['status' => 'reviewed'])) }}"
                        class="nav-link {{ request('status') == 'reviewed' ? 'active' : '' }}">
                        Reviewed
                    </a>
                </li>
            </ul>
            <div class="btn-group ms-auto" role="group" aria-label="Toggle layout">
                <button type="button"
                    class="btn btn-sm btn-outline-secondary view-toggle-btn {{ request('view', 'list') == 'list' ? 'active' : '' }}"
                    data-view="list" title="List view">
                    <i class="bi bi-list-ul"></i>
                </button>
                <button type="button"
                    class="btn btn-sm btn-outline-secondary view-toggle-btn {{ request('view') == 'grid' ? 'active' : '' }}"
                    data-view="grid" title="Grid view">
                    <i class="bi bi-grid-3x3-gap"></i>
                </button>
            </div>
        </div>
    </div>
    {{-- Filters --}}
    <div class="card mb-3 border-0 shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.staff-report.index') }}" method="GET" class="row g-2 align-items-center"
                id="filter_form">
                <input type="hidden" name="status" value="{{ request('status') }}">
                <input type="hidden" name="view" id="view_input" value="{{ request('view', 'list') }}">
                <div class="col-12 col-md-3">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-search"></i></span>
                        <input type="search" name="search" id="search_input"
                            class="form-control form-control-sm border-start-0" placeholder="Search by content..."
                            value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-6 col-md-2">
                    <select name="sort" class="form-select form-select-sm"
                        onchange="document.getElementById('filter_form').submit()">
                        <option value="">Sort: Latest</option>
                        <option value="staff_asc" {{ request('sort') == 'staff_asc' ? 'selected' : '' }}>Staff A–Z</option>
                        <option value="staff_desc" {{ request('sort') == 'staff_desc' ? 'selected' : '' }}>Staff Z–A
                        </option>
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <select name="year" class="form-select form-select-sm"
                        onchange="document.getElementById('filter_form').submit()">
                        <option value="">Year: All</option>
                        @foreach ($years as $y)
                            <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>
                                {{ $y }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <select name="month" class="form-select form-select-sm"
                        onchange="document.getElementById('filter_form').submit()">
                        <option value="">Month: All</option>
                        @foreach (range(1, 12) as $m)
                            <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <select name="week" class="form-select form-select-sm"
                        onchange="document.getElementById('filter_form').submit()">
                        <option value="">Week: All</option>
                        @foreach (range(1, 5) as $w)
                            <option value="{{ $w }}" {{ request('week') == $w ? 'selected' : '' }}>Week
                                {{ $w }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-1 d-flex gap-2">
                    @if (request()->anyFilled(['search', 'sort', 'year', 'month', 'week']))
                        <a href="{{ route('admin.staff-report.index', request()->only('status')) }}"
                            class="btn btn-sm btn-light w-100" title="Clear filters">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    @else
                        <button type="submit" class="btn btn-sm btn-primary w-100">Go</button>
                    @endif
                </div>
            </form>
        </div>
    </div>
    {{-- Results --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            {{-- LIST VIEW --}}
            <div class="table-responsive {{ request('view', 'list') == 'grid' ? 'd-none' : '' }}" id="list_view">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Staff</th>
                            <th>Submitted</th>
                            <th>Status</th>
                            <th>Reviewed By</th>
                            <th class="text-center" style="width: 220px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($reports as $report)
                            <tr>
                                <td class="text-muted">#{{ $report->id }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar avatar-xs rounded-circle d-flex align-items-center justify-content-center text-white fw-semibold"
                                            style="width:28px;height:28px;background-color: hsl({{ crc32($report->staff->name ?? 'N') % 360 }}, 55%, 45%); font-size:.75rem;">
                                            {{ strtoupper(substr($report->staff->name ?? 'N', 0, 1)) }}
                                        </div>
                                        <span>{{ $report->staff->name ?? 'N/A' }}</span>
                                    </div>
                                </td>
                                <td>
                                    {{ $report->created_at->format('d M Y') }}
                                    <div class="text-muted small">{{ $report->created_at->diffForHumans() }}</div>
                                </td>
                                <td>
                                    @if ($report->status == 'pending')
                                        <span
                                            class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle">
                                            <i class="bi bi-clock-history"></i> Pending
                                        </span>
                                    @else
                                        <span
                                            class="badge bg-success-subtle text-success-emphasis border border-success-subtle">
                                            <i class="bi bi-check-circle"></i> Reviewed
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if ($report->status == 'reviewed')
                                        {{ $report->reviewer->name ?? 'N/A' }}
                                        <div class="text-muted small">{{ $report->reviewed_at?->format('d M Y') }}</div>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-1">
                                        <button class="btn btn-sm btn-icon btn-outline-primary view_report_btn"
                                            data-bs-toggle="modal" data-bs-target="#view_report_modal"
                                            data-report-id="{{ $report->id }}"
                                            data-staff="{{ $report->staff->name ?? 'N/A' }}"
                                            data-date="{{ $report->created_at->format('d M Y, h:i A') }}"
                                            title="View report">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        @if ($report->status == 'pending')
                                            <button class="btn btn-sm btn-icon btn-outline-success mark_reviewed_btn"
                                                data-url="{{ route('admin.staff-report.review', $report->id) }}"
                                                title="Mark reviewed">
                                                <i class="bi bi-check-lg"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="border-0">
                                    @include('admin.pages.Report.Staff.partials.empty-state')
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{-- GRID VIEW --}}
            <div class="row {{ request('view') == 'grid' ? '' : 'd-none' }}" id="grid_view">
                @forelse ($reports as $report)
                    <div class="col-sm-6 col-md-4 col-lg-3 mb-4">
                        <div class="card card-hover smooth-shadow-sm h-100 border-0 shadow-sm">
                            <div class="card-body d-flex flex-column">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar avatar-sm rounded-circle d-flex align-items-center justify-content-center text-white fw-semibold"
                                            style="width:32px;height:32px;background-color: hsl({{ crc32($report->staff->name ?? 'N') % 360 }}, 55%, 45%);">
                                            {{ strtoupper(substr($report->staff->name ?? 'N', 0, 1)) }}
                                        </div>
                                        <span class="fw-semibold fs-5">{{ $report->staff->name ?? 'N/A' }}</span>
                                    </div>
                                    @if ($report->status == 'pending')
                                        <span
                                            class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle">Pending</span>
                                    @else
                                        <span
                                            class="badge bg-success-subtle text-success-emphasis border border-success-subtle">Reviewed</span>
                                    @endif
                                </div>
                                <p class="text-muted small mb-1">
                                    <i class="bi bi-calendar3"></i>
                                    {{ $report->created_at->format('d M Y') }}
                                    ({{ $report->created_at->diffForHumans() }})
                                </p>
                                @if ($report->status == 'reviewed')
                                    <p class="text-muted small mb-3">
                                        <i class="bi bi-person-check"></i>
                                        Reviewed by {{ $report->reviewer->name ?? 'N/A' }}
                                    </p>
                                @endif
                                <div class="d-flex gap-2 mt-auto pt-2">
                                    <button class="btn btn-sm btn-outline-primary flex-fill view_report_btn"
                                        data-bs-toggle="modal" data-bs-target="#view_report_modal"
                                        data-report-id="{{ $report->id }}"
                                        data-staff="{{ $report->staff->name ?? 'N/A' }}"
                                        data-date="{{ $report->created_at->format('d M Y, h:i A') }}">
                                        <i class="bi bi-eye"></i> View
                                    </button>
                                    @if ($report->status == 'pending')
                                        <button class="btn btn-sm btn-success flex-fill mark_reviewed_btn"
                                            data-url="{{ route('admin.staff-report.review', $report->id) }}">
                                            <i class="bi bi-check-lg"></i>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        @include('admin.pages.Report.Staff.partials.empty-state')
                    </div>
                @endforelse
            </div>
            @if ($reports->hasPages())
                <div class="mt-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <span class="text-muted fs-2">
                        Showing {{ $reports->firstItem() }}–{{ $reports->lastItem() }} of {{ $reports->total() }}
                    </span>
                    {{ $reports->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
    {{-- Report modal --}}
    <div class="modal fade" id="view_report_modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <div>
                        <h5 class="modal-title mb-0" id="modal_staff_name">Report Detail</h5>
                        <small class="text-muted" id="modal_report_date"></small>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="report_content_view"></div>
            </div>
        </div>
    </div>
    {{-- Hidden report contents --}}
    <div class="d-none">
        @foreach ($reports as $report)
            <div id="report-content-{{ $report->id }}">
                {!! $report->report_content !!}
            </div>
        @endforeach
    </div>
@endsection
@push('scripts')
    <script>
        // View toggle
        $('.view-toggle-btn').on('click', function() {
            const view = $(this).data('view');
            $('#view_input').val(view);
            $('#filter_form').submit();
        });
        // Debounced live search (submits 500ms after typing stops)
        let searchTimer;
        $('#search_input').on('input', function() {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(() => $('#filter_form').submit(), 500);
        });
        // Report modal
        $('.view_report_btn').on('click', function() {

            const reportId = $(this).data('report-id');

            const content = $('#report-content-' + reportId).html();

            $('#report_content_view').html(content);

            $('#modal_staff_name').text($(this).data('staff') + "'s Report");
            $('#modal_report_date').text($(this).data('date'));
        });
        // Mark reviewed with optimistic UI
        $('.mark_reviewed_btn').on('click', function() {
            const $btn = $(this);
            const url = $btn.data('url');
            Swal.fire({
                title: "Mark as reviewed?",
                text: "This confirms you've gone through this report.",
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "Yes, mark reviewed",
                confirmButtonColor: "#198754"
            }).then((result) => {
                if (!result.isConfirmed) return;
                $btn.prop('disabled', true)
                    .html('<span class="spinner-border spinner-border-sm"></span>');
                $.ajax({
                    method: "PATCH",
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
                    error: function() {
                        iziToast.error({
                            message: 'Something went wrong, try again.',
                            position: 'bottomRight'
                        });
                        $btn.prop('disabled', false).html(
                            '<i class="bi bi-check-lg"></i> Mark Reviewed');
                    }
                });
            });
        });
    </script>
@endpush
