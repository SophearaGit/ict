@extends('admin.layouts.master')
@section('page_title', isset($page_title) ? $page_title : 'Page Title Here')
@push('styles')
    <link rel="stylesheet" href="https://npmcdn.com/flatpickr/dist/plugins/monthSelect/style.css">
    <style>
        /* ── Schedule course cards ──────────────────────────────────────── */
        .schedule-course-card {
            background: #fff;
            border: 1px solid #eaeaea;
            border-radius: 14px;
            overflow: hidden;
            transition: box-shadow 0.18s, border-color 0.18s;
        }

        .schedule-course-card:hover {
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.08);
            border-color: #d4d4d4;
        }

        /* Thumb */
        .scc-thumb {
            position: relative;
            height: 170px;
            overflow: hidden;
        }

        .scc-thumb-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transition: transform 0.3s;
        }

        .schedule-course-card:hover .scc-thumb-img {
            transform: scale(1.03);
        }

        .scc-thumb-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.48) 0%, transparent 55%);
            pointer-events: none;
        }

        /* Status pill */
        .scc-status {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 11px;
            font-weight: 500;
            padding: 3px 10px;
            border-radius: 20px;
            letter-spacing: 0.02em;
        }

        .scc-status-open {
            background: rgba(25, 200, 110, 0.18);
            color: #0a6e3a;
            border: 1px solid rgba(25, 200, 110, 0.3);
        }

        .scc-status-close {
            background: rgba(220, 50, 50, 0.14);
            color: #9b1c1c;
            border: 1px solid rgba(220, 50, 50, 0.25);
        }

        /* Instructor chip */
        .scc-instructor-chip {
            position: absolute;
            bottom: 10px;
            left: 10px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .scc-instructor-img {
            width: 26px;
            height: 26px;
            border-radius: 50%;
            border: 1.5px solid rgba(255, 255, 255, 0.75);
            object-fit: cover;
        }

        .scc-instructor-name {
            font-size: 12px;
            color: #fff;
            font-weight: 500;
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.45);
            max-width: 140px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        /* Body */
        .scc-body {
            padding: 14px 16px 12px;
        }

        .scc-title {
            font-size: 14px;
            font-weight: 600;
            line-height: 1.45;
            color: #1a1a1a;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .scc-title a {
            color: inherit;
        }

        .scc-title a:hover {
            color: #3d6fd8;
        }

        /* Meta chips */
        .scc-chips {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
        }

        .scc-chip {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 11px;
            padding: 3px 8px;
            border-radius: 6px;
            background: #f5f5f5;
            color: #666;
            border: 1px solid #ebebeb;
            white-space: nowrap;
        }

        .scc-chip i {
            font-size: 11px;
        }

        /* Stats */
        .scc-stats {
            display: grid;
            grid-template-columns: 1fr auto 1fr auto 1fr;
            align-items: center;
            background: #f8f8f8;
            border: 1px solid #ebebeb;
            border-radius: 10px;
            overflow: hidden;
        }

        .scc-stat {
            padding: 8px 10px;
            text-align: center;
        }

        .scc-stat-divider {
            width: 1px;
            height: 30px;
            background: #e4e4e4;
        }

        .scc-stat-label {
            display: block;
            font-size: 10px;
            color: #999;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            margin-bottom: 2px;
        }

        .scc-stat-val {
            font-size: 13px;
            font-weight: 600;
            color: #1a1a1a;
        }

        .scc-stat-green {
            color: #0d7a43;
        }
    </style>
@endpush
@section('content')
    {{-- ─── Header ─────────────────────────────────────────────────────────────── --}}
    <div class="row">
        <div class="col-12">
            <div class="border-bottom pb-3 mb-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
                <h1 class="mb-0 h2 fw-bold">Dashboard</h1>
                <form method="GET" action="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-2"
                    id="month-filter-form">
                    {{-- Prev button --}}
                    <button class="btn btn-sm btn-outline-secondary px-2" type="button" id="prev-month"
                        aria-label="Previous month">
                        <i class="fe fe-chevron-left"></i>
                    </button>
                    {{-- Month picker --}}
                    <input type="text" name="month" id="month-picker" class="form-control form-control-sm text-center"
                        style="width: 160px;" value="{{ $selected_month }}" readonly>
                    {{-- Next button --}}
                    <button class="btn btn-sm btn-outline-secondary px-2" type="button" id="next-month"
                        aria-label="Next month">
                        <i class="fe fe-chevron-right"></i>
                    </button>
                    <button type="submit" class="btn btn-sm btn-primary">
                        <i class="fe fe-filter me-1"></i> Filter
                    </button>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-outline-secondary"
                        title="Reset to current month">
                        <i class="fe fe-refresh-cw"></i>
                    </a>
                </form>
            </div>
        </div>
    </div>
    {{-- ─── Stat cards ─────────────────────────────────────────────────────────── --}}
    <div class="row g-4 mb-4">
        {{-- Sales --}}
        <div class="col-xl-3 col-lg-6 col-md-12">
            <div class="card h-100 mb-0">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <span class="fs-6 text-uppercase fw-semibold ls-md text-muted">Sales</span>
                        <span class="fe fe-shopping-bag fs-3 text-primary"></span>
                    </div>
                    <h2 class="fw-bold mb-1">${{ $total_revenue }}</h2>
                    <span class="{{ $is_up ? 'text-success' : 'text-danger' }} fw-semibold">
                        <i class="fe {{ $is_up ? 'fe-trending-up' : 'fe-trending-down' }} me-1"></i>
                        {{ $is_up ? '+' : '-' }}${{ $change }}
                    </span>
                    <span class="ms-1 text-muted fw-medium small">vs previous month</span>
                </div>
            </div>
        </div>
        {{-- Courses --}}
        <div class="col-xl-3 col-lg-6 col-md-12">
            <div class="card h-100 mb-0">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <span class="fs-6 text-uppercase fw-semibold ls-md text-muted">Courses</span>
                        <span class="fe fe-book-open fs-3 text-primary"></span>
                    </div>
                    <h2 class="fw-bold mb-1">{{ $total_courses }}</h2>
                    <span class="text-danger fw-semibold">{{ $pending_courses }}</span>
                    <span class="ms-1 text-muted fw-medium small">pending approval</span>
                </div>
            </div>
        </div>
        {{-- Students --}}
        <div class="col-xl-3 col-lg-6 col-md-12">
            <div class="card h-100 mb-0">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <span class="fs-6 text-uppercase fw-semibold ls-md text-muted">Students</span>
                        <span class="fe fe-users fs-3 text-primary"></span>
                    </div>
                    <h2 class="fw-bold mb-1">{{ $total_students }}</h2>
                    <span class="text-success fw-semibold">
                        <i class="fe fe-trending-up me-1"></i>+{{ $new_students }}
                    </span>
                    <span class="ms-1 text-muted fw-medium small">this month</span>
                </div>
            </div>
        </div>
        {{-- Instructors --}}
        <div class="col-xl-3 col-lg-6 col-md-12">
            <div class="card h-100 mb-0">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <span class="fs-6 text-uppercase fw-semibold ls-md text-muted">Instructors</span>
                        <span class="fe fe-user-check fs-3 text-primary"></span>
                    </div>
                    <h2 class="fw-bold mb-1">{{ $total_instructors }}</h2>
                    <span class="text-success fw-semibold">
                        <i class="fe fe-trending-up me-1"></i>+{{ $new_instructors }}
                    </span>
                    <span class="ms-1 text-muted fw-medium small">new this month</span>
                </div>
            </div>
        </div>
    </div>
    {{-- ─── Today's Schedule ───────────────────────────────────────────────────── --}}
    <div class=" row g-4">
        <div class="col-12">
            <div class="card mb-0">
                <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-2">
                        <h4 class="mb-0">Today's Schedule</h4>
                        <span class="badge bg-primary rounded-pill">{{ now()->format('l, F j, Y') }}</span>
                        @if (!$todays_courses->isEmpty())
                            <span class="badge bg-secondary rounded-pill">{{ $todays_courses->count() }} courses</span>
                        @endif
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        {{-- View toggle --}}
                        <div class="btn-group" role="group" aria-label="View toggle" id="schedule-view-toggle">
                            <button type="button" class="btn btn-sm btn-outline-secondary active" id="btn-list-view"
                                title="List view" aria-pressed="true">
                                <i class="fe fe-list"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" id="btn-grid-view"
                                title="Grid view" aria-pressed="false">
                                <i class="fe fe-grid"></i>
                            </button>
                        </div>
                        <a href="{{ route('admin.courses.realtime.index') }}" class="btn btn-outline-secondary btn-sm">View
                            all</a>
                    </div>
                </div>
                <div class="card-body p-0" id="schedule-body">
                    @if ($todays_courses->isEmpty())
                        <div class="text-center text-muted py-5">
                            <i class="fe fe-calendar fs-2 d-block mb-2"></i>
                            <p class="mb-0 small">No courses scheduled for today</p>
                        </div>
                    @else
                        {{-- ── LIST VIEW (table) ─────────────────────────────────────── --}}
                        <div id="schedule-list-view">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0 align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="ps-4" style="width: 30%">Course</th>
                                            <th>Instructor</th>
                                            <th>Schedule</th>
                                            <th>Students</th>
                                            <th>Price</th>
                                            <th>Revenue</th>
                                            <th>Status</th>
                                            <th class="pe-4"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($todays_courses as $course)
                                            <tr>
                                                {{-- Course --}}
                                                <td class="ps-4">
                                                    <div class="d-flex align-items-center gap-3">
                                                        <a
                                                            href="{{ route('admin.courses.realtime.show', $course['id']) }}">
                                                            <img src="{{ $course['thumbnail'] }}"
                                                                alt="{{ $course['title'] }}"
                                                                class="rounded flex-shrink-0"
                                                                style="width: 56px; height: 40px; object-fit: cover;">
                                                        </a>
                                                        <div class="min-w-0">
                                                            <p class="mb-0 fw-semibold text-truncate"
                                                                style="max-width: 200px;">
                                                                <a href="{{ route('admin.courses.realtime.show', $course['id']) }}"
                                                                    class="text-inherit text-decoration-none">
                                                                    {{ $course['title'] }}
                                                                </a>
                                                            </p>
                                                            <small class="text-muted">
                                                                Start:
                                                                {{ $course['start_date'] ? \Carbon\Carbon::parse($course['start_date'])->format('M d, Y') : '—' }}
                                                            </small>
                                                        </div>
                                                    </div>
                                                </td>
                                                {{-- Instructor --}}
                                                <td>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <img src="{{ $course['instructor_image'] }}"
                                                            alt="{{ $course['instructor_name'] }}"
                                                            class="rounded-circle flex-shrink-0"
                                                            style="width: 28px; height: 28px; object-fit: cover;">
                                                        <span class="small">{{ $course['instructor_name'] }}</span>
                                                    </div>
                                                </td>
                                                {{-- Schedule --}}
                                                <td>
                                                    <div class="fw-semibold small">{{ $course['schedule_days'] }}</div>
                                                    <div class="text-muted small">
                                                        <span
                                                            class="badge bg-light text-dark">{{ $course['schedule_shift'] }}</span>
                                                        {{ $course['schedule_time'] }}
                                                    </div>
                                                </td>
                                                {{-- Students --}}
                                                <td>
                                                    <span class="small">{{ $course['enrollments_count'] }}</span>
                                                </td>
                                                {{-- Price --}}
                                                <td>
                                                    <span class="small">${{ number_format($course['price'], 2) }}</span>
                                                </td>
                                                {{-- Revenue --}}
                                                <td>
                                                    <span class="small fw-semibold text-success">
                                                        ${{ number_format($course['total_revenue'] ?? 0, 2) }}
                                                    </span>
                                                </td>
                                                {{-- Status --}}
                                                <td>
                                                    @if ($course['status'] == 'inactive')
                                                        <span
                                                            class="badge-dot bg-danger me-1 d-inline-block align-middle"></span>
                                                        <span class="small">CLOSE</span>
                                                    @elseif ($course['status'] == 'active')
                                                        <span
                                                            class="badge-dot bg-success me-1 d-inline-block align-middle"></span>
                                                        <span class="small">OPEN</span>
                                                    @endif
                                                </td>
                                                {{-- Actions --}}
                                                <td class="pe-4 text-end">
                                                    <a href="javascript:void(0);"
                                                        class="btn btn-sm btn-outline-secondary edit_course_btn"
                                                        data-course-id="{{ $course['id'] }}" title="Edit">
                                                        <i class="fe fe-edit"></i>
                                                    </a>
                                                    <a href="{{ route('admin.courses.realtime.destroy', $course['id']) }}"
                                                        class="btn btn-sm btn-outline-danger btn_dynamic_delete_course"
                                                        title="Delete">
                                                        <i class="fe fe-trash"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        {{-- ── GRID VIEW (cards) ─────────────────────────────────────── --}}
                        <div id="schedule-grid-view" class="d-none p-4">
                            <div class="row g-4">
                                @foreach ($todays_courses as $course)
                                    <div class="col-lg-4 col-md-12 col-12">
                                        <div class="schedule-course-card h-100 d-flex flex-column">
                                            {{-- ── Thumbnail ── --}}
                                            <div class="scc-thumb position-relative">
                                                <a href="{{ route('admin.courses.realtime.show', $course['id']) }}">
                                                    <img src="{{ $course['thumbnail'] }}" alt="{{ $course['title'] }}"
                                                        class="scc-thumb-img">
                                                </a>
                                                <div class="scc-thumb-overlay"></div>
                                                {{-- Status pill --}}
                                                @if ($course['status'] == 'active')
                                                    <span class="scc-status scc-status-open">Open</span>
                                                @else
                                                    <span class="scc-status scc-status-close">Closed</span>
                                                @endif
                                                {{-- Instructor chip --}}
                                                <div class="scc-instructor-chip">
                                                    <img src="{{ $course['instructor_image'] }}"
                                                        alt="{{ $course['instructor_name'] }}"
                                                        class="scc-instructor-img">
                                                    <span
                                                        class="scc-instructor-name">{{ $course['instructor_name'] }}</span>
                                                </div>
                                            </div>
                                            {{-- ── Body ── --}}
                                            <div class="scc-body flex-grow-1 d-flex flex-column gap-3">
                                                {{-- Title --}}
                                                <h5 class="scc-title mb-0">
                                                    <a href="{{ route('admin.courses.realtime.show', $course['id']) }}"
                                                        class="text-inherit text-decoration-none">
                                                        {{ $course['title'] }}
                                                    </a>
                                                </h5>
                                                {{-- Meta chips --}}
                                                <div class="scc-chips">
                                                    <span class="scc-chip">
                                                        <i class="fe fe-calendar"></i>
                                                        {{ $course['start_date'] ? \Carbon\Carbon::parse($course['start_date'])->format('M d') : '—' }}
                                                        &rarr;
                                                        {{ $course['end_date'] ? \Carbon\Carbon::parse($course['end_date'])->format('M d, Y') : '—' }}
                                                    </span>
                                                    @if ($course['schedule_days'])
                                                        <span class="scc-chip">
                                                            <i class="fe fe-clock"></i>
                                                            {{ $course['schedule_days'] }}
                                                        </span>
                                                    @endif
                                                    @if ($course['schedule_shift'])
                                                        <span class="scc-chip">
                                                            <i class="fe fe-sun"></i>
                                                            {{ $course['schedule_shift'] }}
                                                            @if ($course['schedule_time'])
                                                                &middot; {{ $course['schedule_time'] }}
                                                            @endif
                                                        </span>
                                                    @endif
                                                </div>
                                                {{-- Stats row (pushed to bottom) --}}
                                                <div class="scc-stats mt-auto">
                                                    <div class="scc-stat">
                                                        <span class="scc-stat-label">Students</span>
                                                        <span
                                                            class="scc-stat-val">{{ $course['enrollments_count'] }}</span>
                                                    </div>
                                                    <div class="scc-stat-divider"></div>
                                                    <div class="scc-stat">
                                                        <span class="scc-stat-label">Price</span>
                                                        <span
                                                            class="scc-stat-val">${{ number_format($course['price'], 2) }}</span>
                                                    </div>
                                                    <div class="scc-stat-divider"></div>
                                                    <div class="scc-stat">
                                                        <span class="scc-stat-label">Revenue</span>
                                                        <span
                                                            class="scc-stat-val scc-stat-green">${{ number_format($course['total_revenue'] ?? 0, 2) }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    {{-- ─── Bottom three panels ─────────────────────────────────────────────────── --}}
    <div class="row g-4 mt-0 ">
        {{-- Popular Teachers --}}
        <div class="col-xl-4 col-lg-12">
            <div class="card h-100 mb-0">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h4 class="mb-0">Popular Teachers</h4>
                    <a href="{{ route('admin.instructor.index') }}" class="btn btn-outline-secondary btn-sm">View all</a>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse($new_instructors_list as $index => $instructor)
                            <li class="list-group-item px-4 py-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar avatar-md flex-shrink-0">
                                        <img alt="{{ $instructor['name'] }}" src="{{ $instructor['image'] }}"
                                            class="rounded-circle">
                                    </div>
                                    <div class="flex-grow-1 min-w-0">
                                        <p class="mb-0 fw-semibold text-truncate">{{ $instructor['name'] }}</p>
                                        <small class="text-muted">
                                            <span class="fw-semibold text-dark">{{ $instructor['course_count'] }}</span>
                                            courses &middot;
                                            <span
                                                class="fw-semibold text-dark">{{ number_format($instructor['student_count']) }}</span>
                                            students
                                        </small>
                                    </div>
                                    <div class="dropdown dropstart flex-shrink-0">
                                        <a class="btn btn-ghost btn-sm btn-icon rounded-circle" href="#"
                                            role="button" id="instructorDrop{{ $index }}"
                                            data-bs-toggle="dropdown" data-bs-offset="-20,20" aria-expanded="false">
                                            <i class="fe fe-more-vertical"></i>
                                        </a>
                                        <div class="dropdown-menu" aria-labelledby="instructorDrop{{ $index }}">
                                            <span class="dropdown-header">Actions</span>
                                            <a class="dropdown-item" href="#">
                                                <i class="fe fe-edit dropdown-item-icon"></i> Edit
                                            </a>
                                            <a class="dropdown-item text-danger" href="#">
                                                <i class="fe fe-trash dropdown-item-icon"></i> Remove
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @empty
                            <li class="list-group-item px-4 py-5">
                                <div class="text-center text-muted">
                                    <i class="fe fe-user-x fs-2 d-block mb-2"></i>
                                    <p class="mb-0 small">No instructors registered this month</p>
                                </div>
                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
        {{-- Recent Courses --}}
        <div class="col-xl-4 col-lg-12">
            <div class="card h-100 mb-0">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h4 class="mb-0">Recent Courses</h4>
                    <a href="{{ route('admin.courses.realtime.index') }}" class="btn btn-outline-secondary btn-sm">View
                        all</a>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse($recent_courses as $index => $course)
                            <li class="list-group-item px-4 py-3">
                                <div class="d-flex align-items-center gap-3">
                                    <img src="{{ $course['thumbnail'] }}" alt="{{ $course['title'] }}"
                                        class="rounded flex-shrink-0"
                                        style="width: 56px; height: 40px; object-fit: cover;">
                                    <div class="flex-grow-1 min-w-0">
                                        <p class="mb-0 fw-semibold text-truncate">
                                            {{ Str::limit($course['title'], 36) }}
                                        </p>
                                        <div class="d-flex align-items-center gap-1 mt-1">
                                            <img src="{{ $course['instructor_image'] }}"
                                                alt="{{ $course['instructor_name'] }}" class="rounded-circle"
                                                style="width: 18px; height: 18px; object-fit: cover;">
                                            <small class="text-muted">{{ $course['instructor_name'] }}</small>
                                        </div>
                                    </div>
                                    <div class="dropdown dropstart flex-shrink-0">
                                        <a class="btn btn-ghost btn-sm btn-icon rounded-circle" href="#"
                                            role="button" id="courseDrop{{ $index }}" data-bs-toggle="dropdown"
                                            data-bs-offset="-20,20" aria-expanded="false">
                                            <i class="fe fe-more-vertical"></i>
                                        </a>
                                        <div class="dropdown-menu" aria-labelledby="courseDrop{{ $index }}">
                                            <span class="dropdown-header">Actions</span>
                                            <a class="dropdown-item" href="#">
                                                <i class="fe fe-edit dropdown-item-icon"></i> Edit
                                            </a>
                                            <a class="dropdown-item text-danger" href="#">
                                                <i class="fe fe-trash dropdown-item-icon"></i> Remove
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @empty
                            <li class="list-group-item px-4 py-5">
                                <div class="text-center text-muted">
                                    <i class="fe fe-book fs-2 d-block mb-2"></i>
                                    <p class="mb-0 small">No courses active this month</p>
                                </div>
                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
        {{-- Activity --}}
        <div class="col-xl-4 col-lg-12">
            <div class="card h-100 mb-0">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h4 class="mb-0">Activity</h4>
                    @if (auth('admin')->user()->unreadNotifications->count())
                        <span class="badge bg-danger rounded-pill">
                            {{ auth('admin')->user()->unreadNotifications->count() }} new
                        </span>
                    @endif
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse($activities as $activity)
                            <li class="list-group-item px-4 py-3">
                                <div class="d-flex align-items-start gap-3">
                                    <div
                                        class="avatar avatar-md flex-shrink-0
                                    avatar-indicators {{ $activity['is_read'] ? 'avatar-offline' : 'avatar-online' }}">
                                        <img alt="avatar" src="{{ $activity['image'] }}" class="rounded-circle">
                                    </div>
                                    <div class="flex-grow-1 min-w-0">
                                        <p class="mb-0 fw-semibold">{{ $activity['title'] }}</p>
                                        <p class="mb-0 small text-muted text-truncate">{{ $activity['message'] }}</p>
                                        <small class="text-muted">{{ $activity['time'] }}</small>
                                    </div>
                                    @if (!$activity['is_read'])
                                        <span class="badge bg-primary-soft text-primary rounded-pill flex-shrink-0"
                                            style="font-size: 10px;">
                                            New
                                        </span>
                                    @endif
                                </div>
                            </li>
                        @empty
                            <li class="list-group-item px-4 py-5">
                                <div class="text-center text-muted">
                                    <i class="fe fe-bell-off fs-2 d-block mb-2"></i>
                                    <p class="mb-0 small">No activity this month</p>
                                </div>
                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="https://npmcdn.com/flatpickr/dist/plugins/monthSelect/index.js"></script>
    <script>
        (function() {
            // ── Month-only Flatpickr ──────────────────────────────────────────────
            const fp = flatpickr("#month-picker", {
                plugins: [
                    new monthSelectPlugin({
                        shorthand: false, // e.g. "January" not "Jan"
                        dateFormat: "Y-m", // value sent to server: 2025-05
                        altFormat: "F Y", // display: "May 2025"
                    })
                ],
                disableMobile: true, // always use flatpickr, not native on mobile
            });
            // ── Prev / Next buttons ───────────────────────────────────────────────
            const form = document.getElementById('month-filter-form');

            function shiftMonth(dir) {
                const val = document.getElementById('month-picker').value; // "Y-m"
                if (!val) return;
                const [y, m] = val.split('-').map(Number);
                let nm = m + dir,
                    ny = y;
                if (nm < 1) {
                    nm = 12;
                    ny--;
                }
                if (nm > 12) {
                    nm = 1;
                    ny++;
                }
                const newVal = `${ny}-${String(nm).padStart(2, '0')}`;
                fp.setDate(newVal + '-01', false, 'Y-m-d'); // set internal date
                document.getElementById('month-picker').value = newVal; // keep raw value
                form.submit();
            }
            document.getElementById('prev-month').addEventListener('click', () => shiftMonth(-1));
            document.getElementById('next-month').addEventListener('click', () => shiftMonth(1));
        })();
        // ── Schedule grid / list toggle ───────────────────────────────────────
        (function() {
            const btnList = document.getElementById('btn-list-view');
            const btnGrid = document.getElementById('btn-grid-view');
            const listView = document.getElementById('schedule-list-view');
            const gridView = document.getElementById('schedule-grid-view');
            if (!btnList || !btnGrid) return; // no courses — elements don't exist
            const STORAGE_KEY = 'dashboard_schedule_view';

            function setView(mode) {
                const isGrid = mode === 'grid';
                // toggle visibility
                listView.classList.toggle('d-none', isGrid);
                gridView.classList.toggle('d-none', !isGrid);
                // toggle button active states
                btnList.classList.toggle('active', !isGrid);
                btnGrid.classList.toggle('active', isGrid);
                btnList.setAttribute('aria-pressed', String(!isGrid));
                btnGrid.setAttribute('aria-pressed', String(isGrid));
                // persist preference
                try {
                    localStorage.setItem(STORAGE_KEY, mode);
                } catch (_) {}
            }
            // restore saved preference
            let saved = 'list';
            try {
                saved = localStorage.getItem(STORAGE_KEY) || 'list';
            } catch (_) {}
            setView(saved);
            btnList.addEventListener('click', () => setView('list'));
            btnGrid.addEventListener('click', () => setView('grid'));
        })();
    </script>
@endpush
