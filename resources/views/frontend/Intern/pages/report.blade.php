@extends('frontend.Intern.layout.master')
@section('page_title', isset($page_title) ? $page_title : 'Reports')
@push('styles')
    {{-- Remove this <link> if flatpickr's CSS is already loaded globally by your layout. --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.10.8/sweetalert2.all.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/izitoast/dist/css/iziToast.min.css">
    <script src="https://cdn.jsdelivr.net/npm/izitoast/dist/js/iziToast.min.js"></script>
    <style>
        /* Only what the theme doesn't already provide: swapping views + clamping preview text */
        #listView,
        #gridView {
            display: none;
        }

        #listView.active {
            display: block;
        }

        #gridView.active {
            display: flex;
        }

        .report-card .report-preview {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .report-card {
            transition: box-shadow .15s ease, transform .15s ease;
        }

        .report-card:hover {
            box-shadow: 0 .5rem 1.5rem rgba(0, 0, 0, .08);
            transform: translateY(-2px);
        }
    </style>
@endpush
@php
    /**
     * The report_content field is the raw HTML of the TinyMCE table template
     * (Date/Name header, Goals box, Task/Progress/Issue table, Comments box).
     * strip_tags() on that collapses every label and cell into one run-on
     * line ("Date Name intern Goals for this Week Date Task Progress
     * Issue..."), which is what the old cards showed. This pulls out just
     * the parts worth previewing: the goals text, how many task rows were
     * actually filled in, and whether an issue was logged.
     */
    function report_preview($html)
    {
        $result = ['goals' => '', 'task_count' => 0, 'has_issue' => false];

        if (empty($html)) {
            return $result;
        }

        $clean = static function ($text) {
            $text = trim(preg_replace('/\s+/u', ' ', $text ?? ''));
            return $text === "\u{00A0}" ? '' : $text;
        };

        libxml_use_internal_errors(true);
        $dom = new \DOMDocument();
        $dom->loadHTML('<?xml encoding="utf-8" ?>' . $html);
        libxml_clear_errors();
        $xpath = new \DOMXPath($dom);

        // Goals box: the div immediately after the "Goals for this Week" label
        $goalsLabel = $xpath->query("//div[contains(., 'Goals for this Week')]");
        if ($goalsLabel->length) {
            $goalsBox = $xpath->query('following-sibling::div[1]', $goalsLabel->item($goalsLabel->length - 1));
            if ($goalsBox->length) {
                $result['goals'] = $clean($goalsBox->item(0)->textContent);
            }
        }

        // Task table: rows after the header row, counting only ones with a filled Task cell
        $rows = $xpath->query('//table[2]//tr[position() > 1]');
        foreach ($rows as $row) {
            $cells = $xpath->query('.//td', $row);
            if ($cells->length < 2) {
                continue;
            }
            if ($clean($cells->item(1)->textContent) !== '') {
                $result['task_count']++;
            }
            if ($cells->length >= 4 && $clean($cells->item(3)->textContent) !== '') {
                $result['has_issue'] = true;
            }
        }

        return $result;
    }
@endphp
@section('content')
    <div class="page-container">
        <div class="row">
            <div class="col-12">
                <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column">
                    <div class="flex-grow-1">
                        <h4 class="fs-18 text-uppercase fw-bold m-0">Reports</h4>
                        <p class="text-muted fs-13 mb-0">Your submitted daily/weekly activity reports</p>
                    </div>
                    <div class="mt-3 mt-sm-0">
                        <div class="row g-2 mb-0 align-items-center">

                            <!-- Date Range -->
                            <div class="col-sm-auto">
                                <div class="input-group">
                                    <input id="dateRange" type="text" name="date_range" class="form-control"
                                        data-provider="flatpickr"
                                        value="{{ request(
                                            'date_range',
                                            now()->startOfMonth()->format('d M Y') . ' to ' . now()->endOfMonth()->format('d M Y'),
                                        ) }}"
                                        data-date-format="d M Y" data-range-date="true">
                                    <span class="input-group-text bg-primary border-primary text-white">
                                        <i class="ti ti-calendar fs-15"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="col-auto">
                                <button type="button" class="btn btn-light" id="resetDateRange">
                                    <i class="ti ti-rotate-2 me-1"></i>
                                    Reset
                                </button>
                            </div>

                            <!-- Add -->
                            <div class="col-auto">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#reportModal" onclick="openCreateModal()">
                                    <i class="ti ti-plus me-1 fs-4"></i>
                                    Add Report
                                </button>
                            </div>

                            <!-- Grid / List -->
                            <div class="col-auto">
                                <div class="btn-group" role="group">
                                    <button type="button" id="listViewBtn" class="btn btn-outline-primary btn-icon"
                                        onclick="setView('list')">
                                        <i class="ti ti-list"></i>
                                    </button>
                                    <button type="button" id="gridViewBtn" class="btn btn-outline-primary btn-icon"
                                        onclick="setView('grid')">
                                        <i class="ti ti-layout-grid"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- LIST VIEW -->
        <div class="row" id="listView">
            <div class="col-12">
                <div class="card">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-custom table-centered table-nowrap table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th width="60">#</th>
                                        <th>Report</th>
                                        <th width="180">Period</th>
                                        <th width="150" class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($reports as $report)
                                        @php
                                            $periodStart =
                                                $report->period_start ?? $report->created_at->format('Y-m-d');
                                            $periodEnd = $report->period_end ?? $periodStart;
                                            $periodLabel =
                                                $periodStart === $periodEnd
                                                    ? \Carbon\Carbon::parse($periodStart)->format('d M Y')
                                                    : \Carbon\Carbon::parse($periodStart)->format('d M') .
                                                        ' – ' .
                                                        \Carbon\Carbon::parse($periodEnd)->format('d M Y');
                                            $preview = report_preview($report->report_content);
                                        @endphp
                                        <tr>
                                            <td>
                                                {{ $loop->iteration + ($reports->currentPage() - 1) * $reports->perPage() }}
                                            </td>
                                            <td>
                                                @if ($preview['goals'] === '' && $preview['task_count'] === 0 && !$preview['has_issue'])
                                                    <div class="text-muted fst-italic">
                                                        <i class="ti ti-file-off me-1"></i>This report has no content yet.
                                                    </div>
                                                @else
                                                    <div class="fw-semibold text-dark">
                                                        {{ $preview['goals'] !== '' ? Str::limit($preview['goals'], 100) : 'No goals noted' }}
                                                    </div>
                                                    <div class="fs-13 text-muted mt-1">
                                                        <i class="ti ti-checklist me-1"></i>{{ $preview['task_count'] }} task{{ $preview['task_count'] === 1 ? '' : 's' }} logged
                                                        @if ($preview['has_issue'])
                                                            <span class="text-warning ms-2">
                                                                <i class="ti ti-alert-triangle me-1"></i>Issue reported
                                                            </span>
                                                        @endif
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="javascript:void(0)"
                                                    class="change-period-btn text-body-color text-decoration-underline"
                                                    data-id="{{ $report->id }}" data-start="{{ $periodStart }}"
                                                    data-end="{{ $periodEnd }}"
                                                    data-url="{{ route('intern.report.updatePeriod', $report->id) }}"
                                                    title="Click to change the period this report covers">
                                                    <i class="ti ti-calendar-event fs-13 me-1"></i>{{ $periodLabel }}
                                                </a>
                                            </td>
                                            <td class="text-end">
                                                <button type="button"
                                                    class="btn btn-sm btn-soft-primary btn-icon rounded-circle"
                                                    data-bs-toggle="modal" data-bs-target="#reportModal"
                                                    onclick='openEditModal(@json($report))'>
                                                    <i class="ti ti-pencil"></i>
                                                </button>
                                                <button type="button"
                                                    class="btn btn-sm btn-soft-danger btn-icon rounded-circle"
                                                    onclick="confirmDelete({{ $report->id }})">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-5">
                                                <i class="ti ti-file-text fs-1 text-muted d-block mb-2"></i>
                                                <span class="text-muted">No reports found. Click "Add Report" to create one.</span>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @if ($reports->hasPages())
                        <div class="card-footer border-0">
                            <div class="d-flex justify-content-end">
                                {{ $reports->appends(request()->query())->links() }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- GRID VIEW -->
        <div class="row g-3" id="gridView">
            @forelse ($reports as $report)
                @php
                    $periodStart = $report->period_start ?? $report->created_at->format('Y-m-d');
                    $periodEnd = $report->period_end ?? $periodStart;
                    $periodLabel =
                        $periodStart === $periodEnd
                            ? \Carbon\Carbon::parse($periodStart)->format('d M Y')
                            : \Carbon\Carbon::parse($periodStart)->format('d M') .
                                ' – ' .
                                \Carbon\Carbon::parse($periodEnd)->format('d M Y');
                    $preview = report_preview($report->report_content);
                @endphp
                <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
                    <div class="card mb-0 h-100 d-flex flex-column report-card border-0 shadow-sm">
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="badge badge-soft-primary">
                                    #{{ $loop->iteration + ($reports->currentPage() - 1) * $reports->perPage() }}
                                </span>
                                @if ($preview['has_issue'])
                                    <span class="badge badge-soft-warning">
                                        <i class="ti ti-alert-triangle me-1"></i>Issue
                                    </span>
                                @endif
                            </div>

                            @if ($preview['goals'] === '' && $preview['task_count'] === 0 && !$preview['has_issue'])
                                <div class="fs-13 text-muted fst-italic mb-3">
                                    <i class="ti ti-file-off me-1"></i>This report has no content yet.
                                </div>
                            @else
                                <div class="mb-1">
                                    <span class="fs-12 fw-semibold text-muted text-uppercase">
                                        <i class="ti ti-target me-1"></i>Goals
                                    </span>
                                </div>
                                <div class="report-preview fs-13 text-dark mb-2">
                                    {{ $preview['goals'] !== '' ? $preview['goals'] : 'No goals noted for this period.' }}
                                </div>

                                <div class="fs-13 text-muted mb-3">
                                    <i class="ti ti-checklist me-1"></i>
                                    {{ $preview['task_count'] }} task{{ $preview['task_count'] === 1 ? '' : 's' }} logged
                                </div>
                            @endif

                            <div
                                class="d-flex align-items-center justify-content-between mt-auto pt-2 border-top fs-12 text-muted">
                                <a href="javascript:void(0)" class="change-period-btn text-muted text-decoration-underline"
                                    data-id="{{ $report->id }}" data-start="{{ $periodStart }}"
                                    data-end="{{ $periodEnd }}"
                                    data-url="{{ route('intern.report.updatePeriod', $report->id) }}"
                                    title="Click to change the period this report covers">
                                    <i class="ti ti-calendar-event me-1"></i>{{ $periodLabel }}
                                </a>
                                <div>
                                    <button type="button" class="btn btn-sm btn-soft-primary btn-icon rounded-circle"
                                        data-bs-toggle="modal" data-bs-target="#reportModal"
                                        onclick='openEditModal(@json($report))'>
                                        <i class="ti ti-pencil"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-soft-danger btn-icon rounded-circle"
                                        onclick="confirmDelete({{ $report->id }})">
                                        <i class="ti ti-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="card border-0">
                        <div class="card-body text-center py-5">
                            <i class="ti ti-file-text fs-1 text-muted d-block mb-2"></i>
                            <h5 class="mb-1 text-muted">No reports found</h5>
                            <p class="text-muted fs-13 mb-0">Click "Add Report" to create one for this period.</p>
                        </div>
                    </div>
                </div>
            @endforelse
            @if ($reports->hasPages())
                <div class="col-12">
                    <div class="d-flex justify-content-end">
                        {{ $reports->appends(request()->query())->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Create / Edit Modal -->
    <div class="modal fade" id="reportModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <form id="reportForm" method="POST" action="{{ route('intern.report.store') }}">
                    @csrf
                    <input type="hidden" name="_method" id="formMethod" value="POST">
                    <div class="modal-header border-0 pb-0">
                        <div class="d-flex align-items-center gap-3">
                            <span class="d-flex align-items-center justify-content-center rounded-circle flex-shrink-0"
                                style="width:44px; height:44px; background: rgba(var(--bs-primary-rgb), .12);">
                                <i class="ti ti-file-text fs-4 text-primary"></i>
                            </span>
                            <div>
                                <h5 class="modal-title mb-0" id="reportModalTitle">Add Report</h5>
                                <p class="text-muted fs-13 mb-0">Log the work you did for a given period</p>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-3 pt-2">
                        <div class="mb-3">
                            <label class="form-label fw-semibold" for="reportPeriodRange">
                                <i class="ti ti-calendar-event me-1 text-primary"></i>Report period
                            </label>
                            <div class="input-group" style="max-width: 320px;">
                                <input id="reportPeriodRange" type="text" class="form-control" readonly>
                                <span class="input-group-text bg-primary border-primary text-white">
                                    <i class="ti ti-calendar fs-15"></i>
                                </span>
                            </div>
                            <input type="hidden" id="period_start" name="period_start">
                            <input type="hidden" id="period_end" name="period_end">
                        </div>
                        <div class="mb-1">
                            <label class="form-label fw-semibold">
                                <i class="ti ti-edit me-1 text-primary"></i>Report content
                            </label>
                            <textarea id="report_content" name="report_content"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer py-2">
                        <button class="btn btn-light" data-bs-dismiss="modal">
                            Cancel
                        </button>
                        <button class="btn btn-primary px-4">
                            Save Report
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <form id="deleteForm" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    <!-- Delete Confirmation Modal -->
    <div id="danger-header-modal" class="modal fade" tabindex="-1" role="dialog"
        aria-labelledby="danger-header-modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header text-bg-danger border-0">
                    <h4 class="modal-title" id="danger-header-modalLabel">Delete Report</h4>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h5 class="mt-0">Are you sure?</h5>
                    <p class="mb-0">This report will be permanently deleted. This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" onclick="submitDelete()">Delete</button>
                </div>
            </div> <!-- /.modal-content -->
        </div> <!-- /.modal-dialog -->
    </div> <!-- /.modal -->
@endsection
@push('scripts')
    {{-- Remove this <script> if flatpickr is already loaded globally by your layout. --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.js"></script>
    <script src="/admin/assets/dist/libs/tinymce/tinymce.min.js"></script>
    <script>
        const internName = @json(auth()->user()->name);
        const today = @json(now()->format('d M Y'));
        const todayIso = @json(now()->format('Y-m-d'));
        const csrf_token = @json(csrf_token());
    </script>
    <script>
        tinymce.init({
            selector: '#report_content',
            height: 550,
            menubar: false,
            plugins: [
                'lists',
                'link',
                'table',
                'code',
                'fullscreen',
                'wordcount'
            ],
            toolbar: 'undo redo | ' +
                'bold italic underline | ' +
                'bullist numlist | ' +
                'alignleft aligncenter alignright | ' +
                'link table | ' +
                'fullscreen code',
            branding: false,
            promotion: false,
            statusbar: false,
            resize: false,
            content_style: `
        body{
            font-family: Arial,sans-serif;
            font-size:15px;
            line-height:1.8;
            padding:20px;
        }
    `
        });
    </script>
    <script>
        const reportTemplate = `
            <div style="max-width: 820px; margin: auto; font-family: Arial, Helvetica, sans-serif; color: #222; line-height: 1.2; font-size: 14px;">
                <table style="width: 100%; border-collapse: collapse; margin-bottom: 22px;">
                    <tbody>
                        <tr>
                            <td style="padding: 6px; border: 1px solid #ddd; width: 25%; background: #f7f7f7;">
                                <strong>📅 Date</strong>
                            </td>
                            <td style="padding: 6px; border: 1px solid #ddd;">
                                ${today}
                            </td>
                        </tr>
                        <tr>
                            <td style="padding: 6px; border: 1px solid #ddd; background: #f7f7f7;">
                                <strong>👤 Name</strong>
                            </td>
                            <td style="padding: 6px; border: 1px solid #ddd;">
                                ${internName}
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div style="font-weight:600;margin-bottom:6px;">
                    🎯 Goals for this Week
                </div>
                <div style="border:1px solid #ddd;border-radius:3px;height:90px;padding:6px;background:#fafafa;margin-bottom:14px;">
                    &nbsp;
                </div>
                <table style="width:100%;border-collapse:collapse;margin-bottom:14px;">
                    <thead>
                        <tr style="background:#f5f5f5;">
                            <th style="border:1px solid #ddd;padding:6px;width:16%;text-align:left;">📅 Date</th>
                            <th style="border:1px solid #ddd;padding:6px;width:28%;text-align:left;">📝 Task</th>
                            <th style="border:1px solid #ddd;padding:6px;width:28%;text-align:left;">✅ Progress</th>
                            <th style="border:1px solid #ddd;padding:6px;width:28%;text-align:left;color:#b02a37;">⚠️ Issue</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${'<tr><td style="border:1px solid #ddd;height:28px;">&nbsp;</td><td style="border:1px solid #ddd;">&nbsp;</td><td style="border:1px solid #ddd;">&nbsp;</td><td style="border:1px solid #ddd;">&nbsp;</td></tr>'.repeat(6)}
                    </tbody>
                </table>
                <div style="font-weight:600;margin-bottom:6px;">
                    💬 Issues & Comments
                </div>
                <div style="border:1px solid #ddd;border-radius:3px;height:90px;padding:6px;background:#fafafa;">
                    &nbsp;
                </div>
            </div>`;

        function openCreateModal() {
            document.getElementById('reportForm').action = "{{ route('intern.report.store') }}";
            document.getElementById('formMethod').value = 'POST';
            document.getElementById('reportModalTitle').innerText = 'Add Report';
            document.getElementById('reportForm').reset();
            reportPeriodPicker.setDate([todayIso, todayIso], true, 'Y-m-d');
            tinymce.get('report_content').setContent(reportTemplate);
        }
        document.getElementById('reportForm').addEventListener('submit', function() {
            tinymce.triggerSave();
        });

        // ---- Report period range picker (drives hidden period_start/period_end) ----
        const reportPeriodPicker = flatpickr('#reportPeriodRange', {
            mode: 'range',
            dateFormat: 'd M Y',
            altInput: false,
            maxDate: 'today',
            onChange: function(selectedDates) {
                if (selectedDates.length < 2) return;
                document.getElementById('period_start').value = toLocalDateStr(selectedDates[0]);
                document.getElementById('period_end').value = toLocalDateStr(selectedDates[1]);
            },
        });

        function openEditModal(report) {
            document.getElementById('reportForm').action =
                `/intern/report/${report.id}`;
            document.getElementById('formMethod').value = 'PUT';
            document.getElementById('reportModalTitle').innerText =
                'Edit Report';
            // period_start/period_end come back as "Y-m-d" strings from the
            // model's date cast — sliced defensively in case that ever changes
            // to a full ISO datetime.
            const start = (report.period_start ?? report.created_at ?? todayIso).toString().slice(0, 10);
            const end = (report.period_end ?? start).toString().slice(0, 10);
            reportPeriodPicker.setDate([start, end], true, 'Y-m-d');
            tinymce.get('report_content').setContent(
                report.report_content ?? ''
            );
        }

        function confirmDelete(id) {
            const form = document.getElementById('deleteForm');
            form.action = `/intern/report/${id}`;
            const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('danger-header-modal'));
            modal.show();
        }

        function submitDelete() {
            document.getElementById('deleteForm').submit();
        }
        // ---- Grid / List view toggle, persisted per user in localStorage ----
        const VIEW_KEY = 'reports_view_mode';

        function setView(mode) {
            const listView = document.getElementById('listView');
            const gridView = document.getElementById('gridView');
            const listBtn = document.getElementById('listViewBtn');
            const gridBtn = document.getElementById('gridViewBtn');
            // .active on btn-outline-primary already gets your theme's active-state
            // colors (--bs-btn-active-bg / border / color) — no extra CSS needed.
            if (mode === 'grid') {
                listView.classList.remove('active');
                gridView.classList.add('active');
                gridBtn.classList.add('active');
                listBtn.classList.remove('active');
            } else {
                mode = 'list';
                gridView.classList.remove('active');
                listView.classList.add('active');
                listBtn.classList.add('active');
                gridBtn.classList.remove('active');
            }
            localStorage.setItem(VIEW_KEY, mode);
        }
        document.addEventListener('DOMContentLoaded', function() {
            const resetBtn = document.getElementById('resetDateRange');
            resetBtn.addEventListener('click', function() {
                const url = new URL(window.location);
                // Remove custom filter
                url.searchParams.delete('date_range');
                // Reload page
                window.location.href = url.toString();
            });
            // Restore last selected view
            const savedView = localStorage.getItem(VIEW_KEY) || 'list';
            setView(savedView);
            // Date Range
            const picker = document.getElementById('dateRange');
            picker.addEventListener('change', function() {
                const url = new URL(window.location);
                if (this.value) {
                    url.searchParams.set('date_range', this.value);
                } else {
                    url.searchParams.delete('date_range');
                }
                window.location.href = url.toString();
            });
        });

        // --- Change Period: same pattern as the staff reports page — a
        // flatpickr RANGE picker anchored near the clicked link, onClose
        // (fires once, with the final selection) triggers a SweetAlert
        // confirm, then an AJAX PATCH to updatePeriod(). ---
        let changePeriodUrl = null;
        let changePeriodOriginal = null;

        const changePeriodInput = document.createElement('input');
        changePeriodInput.type = 'text';
        changePeriodInput.style.position = 'absolute';
        changePeriodInput.style.opacity = '0';
        changePeriodInput.style.pointerEvents = 'none';
        document.body.appendChild(changePeriodInput);

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
