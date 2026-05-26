@extends('admin.layouts.master')
@section('page_title', isset($page_title) ? $page_title : 'Page Title Here')
@push('styles')
    <style>
        .schedule-card {
            border: var(--gk-card-border-width) solid var(--gk-card-border-color);
            border-radius: 10px;
            overflow: hidden;
        }

        .schedule-header {
            padding: 14px 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            cursor: pointer;
        }

        .schedule-body {
            padding: 12px 16px;
        }

        .shift-title {
            font-weight: 600;
            font-size: 14px;
            margin-top: 10px;
            margin-bottom: 4px;
            color: #6b7280;
        }

        .schedule-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 4px 0;
            font-size: 14px;
        }

        .arrow {
            transition: transform .25s;
        }

        .schedule-header[aria-expanded="true"] .arrow {
            transform: rotate(180deg);
        }
    </style>
@endpush
@section('content')
    {{-- ── Page Header ── --}}
    <div class="row">
        <div class="col-12">
            <div class="border-bottom pb-3 mb-3 d-md-flex align-items-center justify-content-between">
                <div class="mb-3 mb-md-0">
                    <h1 class="mb-1 h2 fw-bold">Courses</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                Course
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                All
                            </li>
                        </ol>
                    </nav>
                </div>
                <div>
                    <button class="btn btn-primary add_new_course_btn">Add New Course</button>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        {{-- ── Toolbar ── --}}
        <div class="col-12 mb-4">
            <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap">
                {{-- Result count --}}
                <h4 class="mb-0">
                    Displaying {{ $courses->count() }} out of {{ $courses->total() }} courses
                    @if (request()->filled('schedule_ids'))
                        <span class="badge bg-primary ms-1">{{ count(request()->schedule_ids) }} schedule filters</span>
                    @endif
                </h4>
                {{-- Controls --}}
                <div class="d-flex align-items-center gap-2 flex-nowrap">
                    {{-- Grid / List toggle --}}
                    <div class="nav btn-group flex-nowrap" role="tablist">
                        <button class="btn btn-outline-secondary" data-bs-toggle="tab" data-bs-target="#tabPaneGrid"
                            role="tab" aria-controls="tabPaneGrid" data-tab="grid">
                            <span class="fe fe-grid"></span>
                        </button>
                        <button class="btn btn-outline-secondary" data-bs-toggle="tab" data-bs-target="#tabPaneList"
                            role="tab" aria-controls="tabPaneList" tabindex="-1" data-tab="list">
                            <span class="fe fe-list"></span>
                        </button>
                    </div>
                    {{-- Month filter --}}
                    <select id="monthFilter" class="form-select flex-shrink-0" style="width:auto;">
                        <option value="">All Months</option>
                        @foreach (range(1, 12) as $m)
                            <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::createFromDate(null, $m, 1)->format('F') }}
                            </option>
                        @endforeach
                    </select>
                    {{-- Sort filter --}}
                    {{-- <select id="sortFilter" class="form-select flex-shrink-0" style="width:auto;">
                        <option value="">Sort By</option>
                        <option value="start_asc" {{ request('sort_by') == 'start_asc' ? 'selected' : '' }}>Start Date ↑
        </option>
        <option value="start_desc" {{ request('sort_by') == 'start_desc' ? 'selected' : '' }}>Start Date ↓
        </option>
        <option value="end_asc" {{ request('sort_by') == 'end_asc' ? 'selected' : '' }}>End Date ↑
        </option>
        <option value="end_desc" {{ request('sort_by') == 'end_desc' ? 'selected' : '' }}>End Date ↓
        </option>
        <option value="students" {{ request('sort_by') == 'students' ? 'selected' : '' }}>Most Students
        </option>
        <option value="revenue" {{ request('sort_by') == 'revenue' ? 'selected' : '' }}>Most Revenue
        </option>
        </select> --}}
                    {{-- Status filter --}}
                    <select id="statusFilter" class="form-select flex-shrink-0" style="width:auto;">
                        <option value="" {{ request('status') == '' ? 'selected' : '' }}>All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Open</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Closed</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    </select>
                </div>
            </div>
        </div>
        {{-- ── Sidebar: Schedule Filter ── --}}
        <div class="col-xl-3 col-lg-3 col-md-4 col-12 mb-4 mb-lg-0">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Filter by Schedule</h4>
                </div>
                <div class="card-body">
                    @if (request()->filled('search_query') ||
                            !empty(request('schedule_ids', [])) ||
                            request()->filled('status') ||
                            request()->filled('month') ||
                            request()->filled('sort_by'))
                        <div class="mb-3">
                            <a href="{{ route('admin.courses.realtime.index') }}" class="btn btn-outline-danger w-100">
                                Reset All Filters
                            </a>
                        </div>
                    @endif
                    <form id="scheduleFilterForm" method="GET" action="{{ route('admin.courses.realtime.index') }}">
                        {{-- Persist all other active filters when a schedule checkbox changes --}}
                        <input type="hidden" name="status" value="{{ request('status') }}">
                        <input type="hidden" name="sort_by" value="{{ request('sort_by') }}">
                        <input type="hidden" name="month" value="{{ request('month') }}">
                        <input type="hidden" name="search_query" value="{{ request('search_query') }}">
                        @foreach ($groupedSchedules as $day => $items)
                            @php
                                $collapseId = 'schedule-' . md5($day);
                                $shiftGroups = collect($items)->groupBy('shift');
                                $shiftOrder = ['morning' => 1, 'afternoon' => 2, 'evening' => 3];
                                $shiftGroups = $shiftGroups->sortBy(fn($v, $k) => $shiftOrder[$k] ?? 99);
                            @endphp
                            <div class="schedule-card mb-2">
                                <div class="schedule-header" data-bs-target="#{{ $collapseId }}">
                                    <div class="fw-bold">{{ ucfirst($day) }}</div>
                                    <i class="fe fe-chevron-down arrow"></i>
                                </div>
                                <div class="collapse schedule-body" id="{{ $collapseId }}">
                                    @foreach ($shiftGroups as $shift => $shiftSchedules)
                                        <div class="shift-title">{{ ucfirst($shift) }}</div>
                                        @foreach ($shiftSchedules as $schedule)
                                            <label class="schedule-item">
                                                <span>
                                                    {{ \Carbon\Carbon::parse($schedule->start_time)->format('g:i') }} –
                                                    {{ \Carbon\Carbon::parse($schedule->end_time)->format('g:i A') }}
                                                </span>
                                                <input type="checkbox" name="schedule_ids[]" value="{{ $schedule->id }}"
                                                    class="schedule-checkbox form-check-input"
                                                    onchange="document.getElementById('scheduleFilterForm').submit()"
                                                    {{ in_array($schedule->id, $selected_schedule_ids) ? 'checked' : '' }}>
                                            </label>
                                        @endforeach
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </form>
                </div>
            </div>
        </div>
        {{-- ── Main content: Grid + List tabs ── --}}
        <div class="col-xl-9 col-lg-9 col-md-8 col-12">
            <div class="tab-content">
                {{-- ════ GRID VIEW ════ --}}
                <div class="tab-pane fade pb-4" id="tabPaneGrid" role="tabpanel">
                    <div class="card rounded-3">
                        {{-- Search --}}
                        <div class="p-4">
                            <form method="GET" action="{{ route('admin.courses.realtime.index') }}"
                                class="d-flex align-items-center position-relative">
                                <span class="position-absolute ps-3 search-icon">
                                    <i class="fe fe-search"></i>
                                </span>
                                <input type="search" name="search_query" value="{{ request('search_query') }}"
                                    class="form-control ps-6" placeholder="Search Course">
                                @foreach (request('schedule_ids', []) as $sid)
                                    <input type="hidden" name="schedule_ids[]" value="{{ $sid }}">
                                @endforeach
                                <input type="hidden" name="status" value="{{ request('status') }}">
                                <input type="hidden" name="sort_by" value="{{ request('sort_by') }}">
                                <input type="hidden" name="month" value="{{ request('month') }}">
                            </form>
                        </div>
                        {{-- Grid --}}
                        <div class="px-4">
                            <div class="row">
                                @forelse ($courses as $course)
                                    <div class="col-lg-4 col-md-6 col-12">
                                        <div class="card mb-4 card-hover">
                                            <a href="{{ route('admin.courses.realtime.show', $course->id) }}">
                                                <img src="{{ asset($course->thumbnail == '' ? '/default-images/staff/no-course-img.png' : $course->thumbnail) }}"
                                                    alt="course" class="card-img-top"
                                                    style="height:180px; object-fit:cover;">
                                            </a>
                                            <div class="card-body">
                                                <h4 class="mb-2 text-truncate-line-2">
                                                    <a href="{{ route('admin.courses.realtime.show', $course->id) }}"
                                                        class="text-inherit">{{ $course->title }}</a>
                                                </h4>
                                                {{-- Instructor --}}
                                                <div class="d-flex align-items-center gap-2 mb-2">
                                                    <img src="{{ asset($course->instructor->image == 'no-img.jpg' ? '/default-images/user/both.jpg' : $course->instructor->image) }}"
                                                        class="rounded-circle"
                                                        style="height:22px;width:22px;object-fit:cover;" alt="">
                                                    <span class="text-muted small">
                                                        {{ $course->instructor ? $course->instructor->name : 'N/A' }}
                                                    </span>
                                                </div>
                                                <div class="d-flex justify-content-between border-bottom py-2">
                                                    <span>Class Start</span>
                                                    <span class="text-dark">
                                                        {{ $course->start_date ? $course->start_date->format('M d, Y') : '—' }}
                                                    </span>
                                                </div>
                                                <div class="d-flex justify-content-between border-bottom py-2">
                                                    <span>Class End</span>
                                                    <span class="text-dark">
                                                        {{ $course->end_date ? $course->end_date->format('M d, Y') : '—' }}
                                                    </span>
                                                </div>
                                                <div class="d-flex justify-content-between border-bottom py-2">
                                                    <span>Schedule</span>
                                                    <span class="text-dark text-end">
                                                        <div class="fw-semibold">{{ $course->schedule->short_days }}</div>
                                                        <div class="text-muted small">
                                                            <span
                                                                class="badge bg-light text-dark">{{ $course->schedule->shift_label }}</span>
                                                            {{ $course->schedule->formatted_time }}
                                                        </div>
                                                    </span>
                                                </div>
                                                <div class="d-flex justify-content-between border-bottom py-2">
                                                    <span>Status</span>
                                                    <span>
                                                        @if ($course->status == 'active')
                                                            <span
                                                                class="badge-dot bg-success me-1 d-inline-block align-middle"></span>
                                                            OPEN
                                                        @elseif ($course->status == 'inactive')
                                                            <span
                                                                class="badge-dot bg-danger me-1 d-inline-block align-middle"></span>
                                                            CLOSED
                                                        @elseif ($course->status == 'draft')
                                                            <span
                                                                class="badge-dot bg-secondary me-1 d-inline-block align-middle"></span>
                                                            DRAFT
                                                        @endif
                                                    </span>
                                                </div>
                                                <div class="d-flex justify-content-between border-bottom py-2">
                                                    <span>Students</span>
                                                    <span class="text-dark">{{ $course->enrollments_count }}</span>
                                                </div>
                                                <div
                                                    class="d-flex justify-content-between align-items-center border-bottom py-2">
                                                    <span>Revenue</span>
                                                    <span
                                                        class="text-dark fw-semibold">${{ number_format($course->total_revenue ?? 0, 2) }}</span>

                                                </div>
                                                <div class="d-flex justify-content-between pt-2">
                                                    <span>Full Price</span>
                                                    <span
                                                        class="badge bg-primary fs-6 px-3 py-2  fw-semibold">${{ number_format($course->price, 2) }}</span>
                                                </div>
                                            </div>
                                            <div class="card-footer">
                                                <div class="d-flex justify-content-end gap-1">
                                                    <a href="javascript:void;"
                                                        class="btn btn-sm btn-outline-secondary edit_course_btn"
                                                        data-course-id="{{ $course->id }}">
                                                        <i class="fe fe-edit"></i>
                                                    </a>
                                                    <a href="{{ route('admin.courses.realtime.destroy', $course->id) }}"
                                                        class="btn btn-sm btn-outline-danger btn_dynamic_delete_course">
                                                        <i class="fe fe-trash"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12">
                                        <div class="card mb-4">
                                            <div class="card-body text-center py-5">
                                                <i class="fe fe-inbox" style="font-size:2.5rem; color:#9ca3af;"></i>
                                                <h3 class="mt-3 mb-2">No courses found</h3>
                                                <p class="text-muted mb-4">Try adjusting your search or filters.</p>
                                                <a href="{{ route('admin.courses.realtime.index') }}"
                                                    class="btn btn-outline-secondary">
                                                    Reset Filters
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforelse
                            </div>
                            <div class="card-footer border-top-0 px-0">
                                @include('admin.pages.real-time-courses.partials.pagination')
                            </div>
                        </div>
                    </div>
                </div>
                {{-- ════ LIST VIEW ════ --}}
                <div class="tab-pane fade pb-4" id="tabPaneList" role="tabpanel">
                    <div class="card rounded-3">
                        {{-- Search --}}
                        <div class="p-4">
                            <form method="GET" action="{{ route('admin.courses.realtime.index') }}"
                                class="d-flex align-items-center position-relative">
                                <span class="position-absolute ps-3 search-icon">
                                    <i class="fe fe-search"></i>
                                </span>
                                <input type="search" name="search_query" value="{{ request('search_query') }}"
                                    class="form-control ps-6" placeholder="Search Course">
                                @foreach (request('schedule_ids', []) as $sid)
                                    <input type="hidden" name="schedule_ids[]" value="{{ $sid }}">
                                @endforeach
                                <input type="hidden" name="status" value="{{ request('status') }}">
                                <input type="hidden" name="sort_by" value="{{ request('sort_by') }}">
                                <input type="hidden" name="month" value="{{ request('month') }}">
                            </form>
                        </div>
                        {{-- Table --}}
                        <div class="table-responsive border-0">
                            <table class="table mb-0 text-nowrap table-centered table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Course</th>
                                        <th>Instructor</th>
                                        <th>Schedule</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Students</th>
                                        <th>Revenue</th>
                                        <th>Full Price</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($courses as $course)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ asset($course->thumbnail == '' ? '/default-images/staff/no-course-img.png' : $course->thumbnail) }}"
                                                        alt="" class="img-4by3-lg rounded">
                                                    <div class="ms-3">
                                                        <h4 class="mb-0 text-primary-hover">
                                                            <a href="{{ route('admin.courses.realtime.show', $course->id) }}"
                                                                class="text-inherit">
                                                                {{ $course->title }}
                                                            </a>
                                                        </h4>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ asset($course->instructor->image == 'no-img.jpg' ? '/default-images/user/both.jpg' : $course->instructor->image) }}"
                                                        alt="" class="rounded-circle me-2"
                                                        style="height:25px;width:25px;object-fit:cover;">
                                                    <span>{{ $course->instructor ? $course->instructor->name : 'N/A' }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="fw-semibold">{{ $course->schedule->short_days }}</div>
                                                <div class="text-muted small">
                                                    <span
                                                        class="badge bg-light text-dark">{{ $course->schedule->shift_label }}</span>
                                                    {{ $course->schedule->formatted_time }}
                                                </div>
                                            </td>
                                            <td>{{ $course->start_date ? $course->start_date->format('M d, Y') : '—' }}
                                            </td>
                                            <td>{{ $course->end_date ? $course->end_date->format('M d, Y') : '—' }}
                                            </td>
                                            <td>{{ $course->enrollments_count }}</td>
                                            <td class="fw-semibold">${{ number_format($course->total_revenue ?? 0, 2) }}
                                            <td><span class="badge bg-primary fs-6 px-2 py-1">${{ number_format($course->price, 2) }}</span></td>
                                            </td>
                                            <td>
                                                @if ($course->status == 'active')
                                                    <span
                                                        class="badge-dot bg-success me-1 d-inline-block align-middle"></span>
                                                    OPEN
                                                @elseif ($course->status == 'inactive')
                                                    <span
                                                        class="badge-dot bg-danger me-1 d-inline-block align-middle"></span>
                                                    CLOSED
                                                @elseif ($course->status == 'draft')
                                                    <span
                                                        class="badge-dot bg-secondary me-1 d-inline-block align-middle"></span>
                                                    DRAFT
                                                @endif
                                            </td>
                                            <td>
                                                <a href="javascript:void;"
                                                    class="btn btn-sm btn-outline-secondary edit_course_btn"
                                                    data-course-id="{{ $course->id }}">
                                                    <i class="fe fe-edit"></i>
                                                </a>
                                                <a href="{{ route('admin.courses.realtime.destroy', $course->id) }}"
                                                    class="btn btn-sm btn-outline-danger btn_dynamic_delete_course">
                                                    <i class="fe fe-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10" class="text-center py-5">
                                                <i class="fe fe-inbox" style="font-size:2.5rem; color:#9ca3af;"></i>
                                                <h3 class="mt-3 mb-2">No courses found</h3>
                                                <p class="text-muted mb-4">Try adjusting your search or filters.</p>
                                                <a href="{{ route('admin.courses.realtime.index') }}"
                                                    class="btn btn-outline-secondary">
                                                    Reset Filters
                                                </a>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer">
                            @include('admin.pages.real-time-courses.partials.pagination')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- ── Add / Edit Course Modal ── --}}
    <div class="modal fade" id="addCourseModal" tabindex="-1">
        <div class="modal-dialog modal-lg dynamic_course_modal_content"></div>
    </div>
@endsection
@push('scripts')
    <script>
        // ── Delete course ──
        $('.btn_dynamic_delete_course').on('click', function(e) {
            e.preventDefault();
            const url = $(this).attr('href');
            Swal.fire({
                title: 'Are you sure?',
                text: 'This action cannot be undone.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        method: 'DELETE',
                        url: url,
                        data: {
                            _token: csrf_token
                        },
                        success: function(data) {
                            iziToast.success({
                                message: data.message,
                                position: 'bottomRight'
                            });
                            setTimeout(() => window.location.href = data.redirect_url, 1000);
                        },
                    });
                }
            });
        });
        // ── Add course ──
        $('.add_new_course_btn').on('click', function(e) {
            e.preventDefault();
            $('#addCourseModal').modal('show');
            $.ajax({
                method: 'GET',
                url: base_url + '/realtime-courses/create',
                beforeSend: function() {
                    $('.dynamic_course_modal_content').html(
                        '<div class="d-flex justify-content-center align-items-center" style="height:200px;">' +
                        '<div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>'
                    );
                },
                success: function(data) {
                    $('.dynamic_course_modal_content').html(data);
                },
            });
        });
        // ── Edit course ──
        $(document).on('click', '.edit_course_btn', function(e) {
            e.preventDefault();
            $('#addCourseModal').modal('show');
            const course_id = $(this).data('course-id');
            $.ajax({
                method: 'GET',
                url: base_url + '/realtime-courses/' + course_id + '/edit',
                beforeSend: function() {
                    $('.dynamic_course_modal_content').html(
                        '<div class="d-flex justify-content-center align-items-center" style="height:200px;">' +
                        '<div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>'
                    );
                },
                success: function(data) {
                    $('.dynamic_course_modal_content').html(data);
                },
            });
        });
        document.addEventListener('DOMContentLoaded', function() {
            // ── Helper: update a single URL param and reload ──
            function applyFilter(key, value) {
                const url = new URL(window.location.href);
                url.searchParams.delete('page');
                value ? url.searchParams.set(key, value) : url.searchParams.delete(key);
                window.location.href = url.toString();
            }
            document.getElementById('monthFilter')?.addEventListener('change', function() {
                applyFilter('month', this.value);
            });
            document.getElementById('sortFilter')?.addEventListener('change', function() {
                applyFilter('sort_by', this.value);
            });
            document.getElementById('statusFilter')?.addEventListener('change', function() {
                applyFilter('status', this.value);
            });
            // ── Schedule collapse init ──
            document.querySelectorAll('.schedule-card .collapse').forEach(el => {
                new bootstrap.Collapse(el, {
                    toggle: false
                });
            });
            document.querySelectorAll('.schedule-card').forEach(card => {
                const collapse = card.querySelector('.collapse');
                // Auto-expand if a checkbox is already checked (filter active)
                if (card.querySelector('.schedule-checkbox:checked')) {
                    bootstrap.Collapse.getOrCreateInstance(collapse).show();
                }
                card.querySelector('.schedule-header').addEventListener('click', function() {
                    const instance = bootstrap.Collapse.getOrCreateInstance(collapse);
                    instance.toggle();
                    this.setAttribute('aria-expanded', collapse.classList.contains('show') ?
                        'false' : 'true');
                });
            });
            // ── Persist active tab across page loads ──
            const savedTab = localStorage.getItem('courses_active_tab');
            if (savedTab) {
                const trigger = document.querySelector('[data-tab="' + savedTab + '"]');
                if (trigger) new bootstrap.Tab(trigger).show();
            }
            document.querySelectorAll('[data-tab]').forEach(btn => {
                btn.addEventListener('click', function() {
                    localStorage.setItem('courses_active_tab', this.dataset.tab);
                });
            });
        });
    </script>
@endpush
