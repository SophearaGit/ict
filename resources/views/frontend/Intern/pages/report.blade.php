@extends('frontend.Intern.layout.master')
@section('page_title', isset($page_title) ? $page_title : 'Reports')
@push('styles')
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
            -webkit-line-clamp: 5;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
@endpush
@section('content')
    <div class="page-container">
        <div class="row">
            <div class="col-12">
                <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column">
                    <div class="flex-grow-1">
                        <h4 class="fs-18 text-uppercase fw-bold m-0">Reports</h4>
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
                                        <th width="180">Submitted At</th>
                                        <th width="120" class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($reports as $report)
                                        <tr>
                                            <td>
                                                {{ $loop->iteration + ($reports->currentPage() - 1) * $reports->perPage() }}
                                            </td>
                                            <td>
                                                {!! Str::limit(strip_tags($report->report_content), 120) !!}
                                            </td>
                                            <td>
                                                {{ $report->created_at->format('d M Y h:i A') }}
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
                                            <td colspan="4" class="text-center text-muted py-4">
                                                No reports found. Click "Add Report" to create one.
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
                <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
                    <div class="card mb-0 h-100 d-flex flex-column">
                        <div class="card-body d-flex flex-column">
                            <span class="badge badge-soft-primary mb-2 align-self-start">
                                #{{ $loop->iteration + ($reports->currentPage() - 1) * $reports->perPage() }}
                            </span>
                            <div class="report-preview text-muted fs-13 mb-3">
                                {!! Str::limit(strip_tags($report->report_content), 140) !!}
                            </div>
                            <div
                                class="d-flex align-items-center justify-content-between mt-auto pt-2 border-top fs-12 text-muted">
                                <span><i
                                        class="ti ti-clock me-1"></i>{{ $report->created_at->format('d M Y h:i A') }}</span>
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
                    <div class="card">
                        <div class="card-body text-center text-muted py-4">
                            No reports found. Click "Add Report" to create one.
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
                    <div class="modal-header">
                        <h5 class="modal-title" id="reportModalTitle">Add Report</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-3">
                        <div class="mb-3">
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
    <script src="/admin/assets/dist/libs/tinymce/tinymce.min.js"></script>
    <script>
        const internName = @json(auth()->user()->name);
        const today = @json(now()->format('d M Y'));
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
            tinymce.get('report_content').setContent(reportTemplate);
        }
        document.getElementById('reportForm').addEventListener('submit', function() {
            tinymce.triggerSave();
        });

        function openEditModal(report) {
            document.getElementById('reportForm').action =
                `/intern/report/${report.id}`;
            document.getElementById('formMethod').value = 'PUT';
            document.getElementById('reportModalTitle').innerText =
                'Edit Report';
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
    </script>
@endpush
