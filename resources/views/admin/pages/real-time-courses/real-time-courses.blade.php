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
            /* background: #fafafa; */
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
    <div class="row">
        <div class="col-lg-12 col-md-12 col-12">
            <!-- Page Header -->
            <div class="border-bottom pb-3 mb-3 d-md-flex align-items-center justify-content-between">
                <div class="mb-3 mb-md-0">
                    <h1 class="mb-1 h2 fw-bold">
                        Courses ( Real Time )
                    </h1>
                    <!-- Breadcrumb -->
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item active">
                                <a href="javascript:void;">
                                    Courses ( Real Time )
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">All</li>
                        </ol>
                    </nav>
                </div>
                <div class="nav btn-group" role="tablist">
                    <a href="javascript:void;" class="btn btn-primary">Add New Courses</a>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-12 mb-4">
            <div class="row d-md-flex justify-content-between align-items-center">
                <div class="col-md-6 col-lg-8 col-xl-9">
                    <h4 class="mb-3 mb-md-0">Displaying {{ $courses->count() }} out of {{ $courses->total() }} courses
                        @if (request()->filled('schedule_ids'))
                            <span class="badge bg-primary">{{ count(request()->schedule_ids) }} filters</span>
                        @endif
                    </h4>
                </div>
                <div class="d-inline-flex col-md-6 col-lg-4 col-xl-3">
                    <div class="me-2">
                        <!-- Nav -->
                        <div class="nav btn-group flex-nowrap" role="tablist">
                            <button class="btn btn-outline-secondary" data-bs-toggle="tab" data-bs-target="#tabPaneGrid"
                                role="tab" aria-controls="tabPaneGrid" aria-selected="true" data-tab="grid">
                                <span class="fe fe-grid"></span>
                            </button>
                            <button class="btn btn-outline-secondary" data-bs-toggle="tab" data-bs-target="#tabPaneList"
                                role="tab" aria-controls="tabPaneList" aria-selected="false" tabindex="-1"
                                data-tab="list">
                                <span class="fe fe-list"></span>
                            </button>
                        </div>
                    </div>
                    <!-- List  -->
                    <select id="statusFilter" class="form-select">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>
                            Open
                        </option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>
                            Close
                        </option>
                    </select>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-4 col-12 mb-4 mb-lg-0">
            <!-- Card -->
            <div class="card">
                <!-- Card header -->
                <div class="card-header">
                    <h4 class="mb-0">Filter</h4>
                </div>
                <!-- Card body -->
                <div class="card-body">
                    <span class="dropdown-header px-0 mb-3">Schedules</span>
                    @if (request()->filled('search_query') || !empty(request('schedule_ids', [])))
                        <div class="my-3">
                            <a href="{{ route('admin.courses.realtime.index') }}" class="btn btn-outline-danger w-100">
                                Reset Filters
                            </a>
                        </div>
                    @endif
                    @php
                        $shiftOrder = [
                            'morning' => 1,
                            'afternoon' => 2,
                            'evening' => 3,
                        ];
                    @endphp

                    <form id="scheduleFilterForm" method="GET" action="{{ route('admin.courses.realtime.index') }}">
                        @foreach ($groupedSchedules as $day => $items)
                            @php
                                $collapseId = 'schedule-' . md5($day);
                                $shiftGroups = collect($items)->groupBy('shift');
                            @endphp
                            <div class="schedule-card mb-2">
                                <div class="schedule-header" data-bs-toggle="collapse"
                                    data-bs-target="#{{ $collapseId }}">
                                    <div class="fw-bold">{{ ucfirst($day) }}</div>
                                    <i class="fe fe-chevron-down arrow"></i>
                                </div>
                                <div class="collapse schedule-body" id="{{ $collapseId }}">
                                    {{-- <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="text-muted small">Select all</span>
                                        <input type="checkbox" class="form-check-input select-day">
                                    </div> --}}
                                    @foreach ($shiftGroups as $shift => $shiftSchedules)
                                        <div class="shift-title">{{ ucfirst($shift) }}</div>
                                        @foreach ($shiftSchedules as $schedule)
                                            <label class="schedule-item">
                                                <span>{{ \Carbon\Carbon::parse($schedule->start_time)->format('g:i') }} –
                                                    {{ \Carbon\Carbon::parse($schedule->end_time)->format('g:i A') }}</span>
                                                <input type="checkbox" name="schedule_ids[]" value="{{ $schedule->id }}"
                                                    class="schedule-checkbox"
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
        <!-- Tab content -->
        <div class="col-xl-9 col-lg-9 col-md-8 col-12">
            <div class="tab-content">
                <!-- Tab pane -->
                <div class="tab-pane fade pb-4" id="tabPaneGrid" role="tabpanel" aria-labelledby="tabPaneGrid">
                    <div class="card rounded-3">
                        <!-- Card header -->
                        <div class="p-4 row">
                            <!-- Form -->
                            <form method="GET" action="{{ route('admin.courses.realtime.index') }}"
                                class="d-flex align-items-center col-12 col-md-12 col-lg-12">

                                <span class="position-absolute ps-3 search-icon">
                                    <i class="fe fe-search"></i>
                                </span>

                                <input type="search" name="search_query" value="{{ request('search_query') }}"
                                    class="form-control ps-6" placeholder="Search Course">
                                <!-- Hidden inputs for selected schedule IDs -->
                                @foreach (request('schedule_ids', []) as $scheduleId)
                                    <input type="hidden" name="schedule_ids[]" value="{{ $scheduleId }}">
                                @endforeach
                                <!-- ⭐ keep status -->
                                <input type="hidden" name="status" value="{{ request('status') }}">
                            </form>
                        </div>
                        <div class="px-4">
                            <div class="row">
                                @forelse ($courses as $course)
                                    <div class="col-lg-4 col-md-6 col-12">
                                        <!-- Card -->
                                        <div class="card mb-4 card-hover">
                                            <a href="course-single.html"><img
                                                    src="{{ asset($course->thumbnail == '' ? '/default-images/staff/no-course-img.png' : $course->thumbnail) }}"
                                                    alt="course" class="card-img-top"
                                                    style="height: 230px; object-fit: cover;"></a>
                                            <!-- Card body -->
                                            <div class="card-body">
                                                <h4 class="mb-2 text-truncate-line-2">
                                                    <a href="course-single.html" class="text-inherit">
                                                        {{ $course->title }}
                                                    </a>
                                                </h4>
                                                <div class="d-flex justify-content-between border-bottom py-2 mt-2">
                                                    <span>
                                                        Created On
                                                    </span>
                                                    <span class="text-dark">
                                                        {{ $course->created_at->format('d M, Y') }}
                                                    </span>
                                                </div>
                                                <div class="d-flex justify-content-between border-bottom py-2 mt-2">
                                                    <span>
                                                        Scehdule
                                                    </span>
                                                    <span class="text-dark">
                                                        <div>
                                                            <div class="fw-semibold">{{ $course->schedule->short_days }}
                                                            </div>
                                                            <div class="text-muted small">
                                                                <span class="badge bg-light text-dark">
                                                                    {{ $course->schedule->shift_label }}
                                                                </span>
                                                                {{ $course->schedule->formatted_time }}
                                                            </div>
                                                        </div>
                                                    </span>
                                                </div>
                                                <div class="d-flex justify-content-between border-bottom py-2 ">
                                                    <span>
                                                        Status
                                                    </span>
                                                    <span class="text-dark">
                                                        @if ($course->status == 'pending')
                                                            <span
                                                                class="badge-dot bg-warning me-1 d-inline-block align-middle"></span>
                                                            CLOSE
                                                        @elseif ($course->status == 'active')
                                                            <span
                                                                class="badge-dot bg-success me-1 d-inline-block align-middle"></span>
                                                            OPEN
                                                        @endif
                                                    </span>
                                                </div>
                                                {{-- students amount --}}
                                                <div class="d-flex justify-content-between border-bottom  py-2 ">
                                                    <span>
                                                        Students
                                                    </span>
                                                    <span class="text-dark">
                                                        {{ $course->enrollments_count }}
                                                    </span>
                                                </div>
                                                <div class="d-flex justify-content-between border-bottom py-2 ">
                                                    <span>
                                                        Price
                                                    </span>
                                                    <span class="text-dark">
                                                        ${{ $course->price }}
                                                    </span>
                                                </div>
                                                {{-- earning --}}
                                                <div class="d-flex justify-content-between pt-2 ">
                                                    <span>
                                                        Revenue
                                                    </span>
                                                    <span class="text-dark">
                                                        ${{ $course->payments->sum('amount') }}
                                                    </span>
                                                </div>
                                            </div>
                                            <!-- Card footer -->
                                            <div class="card-footer">
                                                <div class="row align-items-center g-0">
                                                    <div class="col-auto">
                                                        <img src="{{ $course->instructor->image == 'no-img.jpg' ? '/default-images/user/both.jpg' : $course->instructor->image }}"
                                                            class="rounded-circle avatar-xs" alt="avatar"
                                                            style="height: 25px; object-fit: cover;">
                                                    </div>
                                                    <div class="col ms-2">
                                                        <span>
                                                            {{ $course->instructor ? $course->instructor->name : 'N/A' }}
                                                        </span>
                                                    </div>
                                                    <div class="col-auto">
                                                        <a href="javascript:void;"
                                                            class="btn btn-sm btn-outline-secondary">
                                                            <i class="fe fe-phone"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12">
                                        <div class="card mb-4 card-hover">
                                            <div class="card-body text-center">
                                                <h3 class="mb-2">No courses found.</h3>
                                                <p class="mb-4">Try adjusting your search or filter to find what you're
                                                    looking
                                                    for.</p>
                                                @if (request()->filled('search_query') || !empty(request('schedule_ids', [])))
                                                    <a href="{{ route('admin.courses.realtime.index') }}"
                                                        class="btn btn-outline-danger ms-2">
                                                        Reset Filters
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforelse
                            </div>
                            <div class="card-footer">
                                @include('admin.pages.real-time-courses.partials.pagination')
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Tab pane -->
                <div class="tab-pane fade pb-4 " id="tabPaneList" role="tabpanel" aria-labelledby="tabPaneList">
                    <div class="card rounded-3">
                        <!-- Card header -->
                        <div class="p-4 row">
                            <!-- Form -->
                            <form method="GET" action="{{ route('admin.courses.realtime.index') }}"
                                class="d-flex align-items-center col-12 col-md-12 col-lg-12">
                                <span class="position-absolute ps-3 search-icon">
                                    <i class="fe fe-search"></i>
                                </span>
                                <input type="search" name="search_query" value="{{ request('search_query') }}"
                                    class="form-control ps-6" placeholder="Search Course">
                                <!-- Hidden inputs for selected schedule IDs -->
                                @foreach (request('schedule_ids', []) as $scheduleId)
                                    <input type="hidden" name="schedule_ids[]" value="{{ $scheduleId }}">
                                @endforeach
                                <!-- ⭐ keep status -->
                                <input type="hidden" name="status" value="{{ request('status') }}">
                            </form>
                        </div>
                        <div>
                            <!-- Table -->
                            <div class="tab-content" id="tabContent">
                                <!--Tab pane -->
                                <div class="tab-pane fade active show" id="courses" role="tabpanel"
                                    aria-labelledby="courses-tab">
                                    <div class="table-responsive border-0 overflow-y-hidden">
                                        <table class="table mb-0 text-nowrap table-centered table-hover">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Courses</th>
                                                    {{-- <th>Instructor</th> --}}
                                                    <th>Schedule</th>
                                                    <th>Students</th>
                                                    {{-- <th>Price</th> --}}
                                                    <th>Revenue</th>
                                                    <th>STATUS</th>
                                                    <th>ACTION</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($courses as $course)
                                                    <tr>
                                                        <td>
                                                            <a href="javascript:void;" class="text-inherit">
                                                                <div class="d-flex align-items-center">
                                                                    <div>
                                                                        <img src="{{ asset($course->thumbnail == '' ? '/default-images/staff/no-course-img.png' : $course->thumbnail) }}"
                                                                            alt="" class="img-4by3-lg rounded">
                                                                    </div>
                                                                    <div class="ms-3">
                                                                        <h4 class="mb-1 text-primary-hover">
                                                                            {{ $course->title }}
                                                                        </h4>
                                                                        <span>Created on
                                                                            {{ $course->created_at->format('d M, Y') }}
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            </a>
                                                        </td>
                                                        {{-- <td>
                                                            <div class="d-flex align-items-center">
                                                                <img src="{{ asset($course->instructor->image == 'no-img.jpg' ? '/default-images/user/both.jpg' : $course->instructor->image) }}"
                                                                    alt="" class="rounded-circle avatar-xs me-2"
                                                                    style="height: 25px; object-fit: cover;">
                                                                <h5 class="mb-0">
                                                                    {{ $course->instructor ? $course->instructor->name : 'N/A' }}
                                                                </h5>
                                                            </div>
                                                        </td> --}}
                                                        {{-- schedule --}}
                                                        <td>
                                                            <div>
                                                                <div class="fw-semibold">
                                                                    {{ $course->schedule->short_days }}</div>
                                                                <div class="text-muted small">
                                                                    <span class="badge bg-light text-dark">
                                                                        {{ $course->schedule->shift_label }}
                                                                    </span>
                                                                    {{ $course->schedule->formatted_time }}
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            {{ $course->enrollments_count }}
                                                        </td>
                                                        {{-- <td>
                                                            ${{ $course->price }}
                                                        </td> --}}
                                                        <td>
                                                            ${{ $course->payments->sum('amount') }}
                                                        </td>
                                                        <td>
                                                            @if ($course->status == 'pending')
                                                                <span
                                                                    class="badge-dot bg-warning me-1 d-inline-block align-middle"></span>
                                                                CLOSE
                                                            @elseif ($course->status == 'active')
                                                                <span
                                                                    class="badge-dot bg-success me-1 d-inline-block align-middle"></span>
                                                                OPEN
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <a href="javascript:void;"
                                                                class="btn btn-sm btn-outline-secondary">
                                                                <i class="fe fe-edit"></i>
                                                            </a>
                                                            <a href="javascript:void;"
                                                                class="btn btn-sm btn-outline-danger">
                                                                <i class="fe fe-trash"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="6" class="text-center">
                                                            <div class="py-4">
                                                                <h3 class="mb-2">No courses found.</h3>
                                                                <p class="mb-4">Try adjusting your search or filter to
                                                                    find
                                                                    what you're looking for.</p>
                                                                @if (request()->filled('search_query') || !empty(request('schedule_ids', [])))
                                                                    <a href="{{ route('admin.courses.realtime.index') }}"
                                                                        class="btn btn-outline-danger ms-2">
                                                                        Reset Filters
                                                                    </a>
                                                                @endif
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Card Footer -->
                        <div class="card-footer">
                            @include('admin.pages.real-time-courses.partials.pagination')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // Status filter
            document.getElementById('statusFilter')?.addEventListener('change', function() {

                const url = new URL(window.location.href);

                url.searchParams.delete('page');

                if (this.value) {
                    url.searchParams.set('status', this.value);
                } else {
                    url.searchParams.delete('status');
                }

                window.location.href = url.toString();
            });

            // Initialize all collapse elements
            document.querySelectorAll('.schedule-card .collapse').forEach(el => {
                new bootstrap.Collapse(el, {
                    toggle: false
                });
            });

            // Open collapse if any checkbox is checked
            document.querySelectorAll('.schedule-card').forEach(card => {

                const collapse = card.querySelector('.collapse');

                if (card.querySelector('.schedule-checkbox:checked')) {
                    bootstrap.Collapse.getOrCreateInstance(collapse).show();
                }

                card.querySelector('.schedule-header')
                    .addEventListener('click', () =>
                        bootstrap.Collapse.getOrCreateInstance(collapse).toggle()
                    );
            });

            // Toggle collapse on header click
            document.querySelectorAll('.schedule-header').forEach(header => {
                header.addEventListener('click', function() {
                    const target = document.querySelector(this.dataset.bsTarget);
                    if (target) {
                        const collapseInstance = bootstrap.Collapse.getOrCreateInstance(target);
                        collapseInstance.toggle();
                        // Update aria-expanded manually
                        this.setAttribute('aria-expanded', target.classList.contains('show') ?
                            'true' : 'false');
                    }
                });
            });

            // Select all functionality
            document.querySelectorAll('.select-day').forEach(selectAll => {
                selectAll.addEventListener('change', function() {
                    let container = this.closest('.schedule-body');
                    container.querySelectorAll('.schedule-checkbox')
                        .forEach(cb => cb.checked = this.checked);
                });
            });

            // Persist active tab
            const savedTab = localStorage.getItem('courses_active_tab');
            if (savedTab) {
                const trigger = document.querySelector(`[data-tab="${savedTab}"]`);
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
