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
    transition: background-color .15s ease;
  }
  .schedule-header:hover {
    background-color: var(--gk-list-group-action-hover-bg);
  }
  .schedule-card:has(.schedule-checkbox:checked) .schedule-header {
    background-color: rgba(117, 79, 254, .1);
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

  /* ── Toolbar: let filter controls wrap instead of overflowing on narrow screens ── */
  .courses-toolbar-controls {
    flex-wrap: wrap;
  }
  .courses-toolbar-controls > div:not(:first-child) {
    flex: 1 1 130px;
    min-width: 110px;
  }
  .courses-toolbar-controls .form-select {
    width: 100% !important;
  }

  /* ── Grid card: status-colored top accent ── */
  .course-card {
    border-top-width: 3px;
    border-top-style: solid;
  }
  .course-card-active {
    border-top-color: var(--gk-success);
  }
  .course-card-inactive {
    border-top-color: var(--gk-danger);
  }
  .course-card-draft {
    border-top-color: var(--gk-warning);
  }

  /* ── Mobile list card (List tab, below md) ── */
  .course-list-card {
    padding: 1rem 1.25rem;
    transition: background-color .15s ease;
  }
  .course-list-card:hover {
    background-color: var(--gk-list-group-action-hover-bg);
  }
  .course-list-card-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: .5rem;
  }
  .course-list-card-grid .label {
    font-size: 10px;
    text-transform: uppercase;
    letter-spacing: .04em;
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
      {{-- <div>
                    <button class="btn btn-primary add_new_course_btn">Add New Course</button>
                </div> --}}
    </div>
  </div>
</div>
<div class="row">
  {{-- ── Toolbar ── --}}
  <div class="col-12 mb-4">
    <div class="d-flex align-items-end justify-content-between gap-3 flex-wrap">
      {{-- Result count + active filter chips --}}
      <div>
        <h4 class="mb-1">
          Displaying {{ $courses->count() }} out of {{ $courses->total() }} courses
        </h4>
        @php
        $hasActiveFilters = request()->filled('search_query') ||
        !empty(request('schedule_ids', [])) ||
        request()->filled('status') ||
        request()->filled('month') ||
        request()->filled('sort_by');
        $statusLabels = ['active' => 'Open', 'inactive' => 'Closed', 'draft' => 'Draft'];
        $sortLabels = [
        'start_asc' => 'Start Date ↑',
        'start_desc' => 'Start Date ↓',
        'end_asc' => 'End Date ↑',
        'end_desc' => 'End Date ↓',
        'students' => 'Most Students',
        'revenue' => 'Most Revenue',
        ];
        @endphp
        @if ($hasActiveFilters)
        <div class="d-flex align-items-center flex-wrap gap-2">
          @if (request()->filled('search_query'))
          <a href="{{ request()->fullUrlWithQuery(['search_query' => null, 'page' => null]) }}" class="badge bg-light text-dark border d-inline-flex align-items-center gap-1 text-decoration-none">
            Search: "{{ Str::limit(request('search_query'), 20) }}"
            <i class="fe fe-x"></i>
          </a>
          @endif
          @if (request()->filled('status'))
          <a href="{{ request()->fullUrlWithQuery(['status' => null, 'page' => null]) }}" class="badge bg-light text-dark border d-inline-flex align-items-center gap-1 text-decoration-none">
            Status: {{ $statusLabels[request('status')] ?? request('status') }}
            <i class="fe fe-x"></i>
          </a>
          @endif
          @if (request()->filled('month'))
          <a href="{{ request()->fullUrlWithQuery(['month' => null, 'page' => null]) }}" class="badge bg-light text-dark border d-inline-flex align-items-center gap-1 text-decoration-none">
            Month:
            {{ \Carbon\Carbon::createFromDate(null, request('month'), 1)->format('F') }}
            <i class="fe fe-x"></i>
          </a>
          @endif
          @if (request()->filled('sort_by'))
          <a href="{{ request()->fullUrlWithQuery(['sort_by' => null, 'page' => null]) }}" class="badge bg-light text-dark border d-inline-flex align-items-center gap-1 text-decoration-none">
            Sort: {{ $sortLabels[request('sort_by')] ?? request('sort_by') }}
            <i class="fe fe-x"></i>
          </a>
          @endif
          @if (!empty(request('schedule_ids', [])))
          <a href="{{ request()->fullUrlWithQuery(['schedule_ids' => null, 'page' => null]) }}" class="badge bg-light text-dark border d-inline-flex align-items-center gap-1 text-decoration-none">
            {{ count(request('schedule_ids')) }}
            {{ Str::plural('schedule', count(request('schedule_ids'))) }}
            <i class="fe fe-x"></i>
          </a>
          @endif
          <a href="{{ route('admin.courses.realtime.index') }}" class="badge bg-danger-subtle text-danger border-0 text-decoration-none">
            Clear all
          </a>
        </div>
        @endif
      </div>
      {{-- Controls --}}
      <div class="d-flex align-items-end gap-2 courses-toolbar-controls">
        {{-- Grid / List toggle --}}
        <div class="nav btn-group flex-shrink-0" role="tablist">
          <button class="btn btn-outline-secondary active" data-bs-toggle="tab" data-bs-target="#tabPaneGrid" role="tab" aria-controls="tabPaneGrid" aria-selected="true" data-tab="grid" title="Grid view">
            <span class="fe fe-grid"></span>
          </button>
          <button class="btn btn-outline-secondary" data-bs-toggle="tab" data-bs-target="#tabPaneList" role="tab" aria-controls="tabPaneList" aria-selected="false" tabindex="-1" data-tab="list" title="List view">
            <span class="fe fe-list"></span>
          </button>
        </div>
        {{-- Month filter --}}
        <div class="d-flex flex-column">
          <label for="monthFilter" class="form-label small text-muted mb-1">
            <i class="fe fe-calendar me-1"></i>Month
          </label>
          <select id="monthFilter" class="form-select flex-shrink-0">
            <option value="">All Months</option>
            @foreach (range(1, 12) as $m)
            <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>
              {{ \Carbon\Carbon::createFromDate(null, $m, 1)->format('F') }}
            </option>
            @endforeach
          </select>
        </div>
        {{-- Sort filter --}}
        <div class="d-flex flex-column">
          <label for="sortFilter" class="form-label small text-muted mb-1">
            <i class="fe fe-bar-chart-2 me-1"></i>Sort by
          </label>
          <select id="sortFilter" class="form-select flex-shrink-0">
            <option value="">Default</option>
            <option value="start_asc" {{ request('sort_by') == 'start_asc' ? 'selected' : '' }}>
              Start Date ↑
            </option>
            <option value="start_desc" {{ request('sort_by') == 'start_desc' ? 'selected' : '' }}>
              Start Date ↓
            </option>
            <option value="end_asc" {{ request('sort_by') == 'end_asc' ? 'selected' : '' }}>
              End Date ↑
            </option>
            <option value="end_desc" {{ request('sort_by') == 'end_desc' ? 'selected' : '' }}>
              End Date ↓
            </option>
            <option value="students" {{ request('sort_by') == 'students' ? 'selected' : '' }}>
              Most Students
            </option>
            <option value="revenue" {{ request('sort_by') == 'revenue' ? 'selected' : '' }}>
              Most Revenue
            </option>
          </select>
        </div>
        {{-- Status filter --}}
        <div class="d-flex flex-column">
          <label for="statusFilter" class="form-label small text-muted mb-1">
            <i class="fe fe-flag me-1"></i>Status
          </label>
          <select id="statusFilter" class="form-select flex-shrink-0">
            <option value="" {{ request('status') == '' ? 'selected' : '' }}>All Status</option>
            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Open</option>
            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Closed
            </option>
            <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
          </select>
        </div>
      </div>
    </div>
  </div>
  {{-- ── Sidebar: Schedule Filter ── --}}
  <div class="col-xl-3 col-lg-3 col-md-4 col-12 mb-4 mb-lg-0">
    <div class="card">
      <div class="card-header">
        <h4 class="mb-0 d-flex align-items-center gap-2">
          <span class="icon-shape icon-sm rounded-circle bg-light-primary text-primary">
            <i class="fe fe-filter"></i>
          </span>
          Filter by Schedule
        </h4>
      </div>
      <div class="card-body">
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
                <input type="checkbox" name="schedule_ids[]" value="{{ $schedule->id }}" class="schedule-checkbox form-check-input" onchange="document.getElementById('scheduleFilterForm').submit()" {{ in_array($schedule->id, $selected_schedule_ids) ? 'checked' : '' }}>
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
      {{-- Grid is the default view: it needs "show active" here so the
           page renders something before the localStorage-restore script
           below runs. Without a default, a first-ever visit (or any
           visit where localStorage has no saved preference) left BOTH
           tab-panes hidden until the user clicked a toggle button. --}}
      <div class="tab-pane fade show active pb-4" id="tabPaneGrid" role="tabpanel">
        <div class="card rounded-3">
          {{-- Search --}}
          <div class="p-4">
            <form method="GET" action="{{ route('admin.courses.realtime.index') }}" class="d-flex align-items-center position-relative">
              <span class="position-absolute ps-3 search-icon">
                                    <i class="fe fe-search"></i>
                                </span>
              <input type="search" name="search_query" value="{{ request('search_query') }}" class="form-control ps-6" placeholder="Search Course">
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
              @php
              $courseCardAccent = in_array($course->status, ['active', 'inactive', 'draft']) ? $course->status : 'inactive';
              $statusMap = [
              'active' => ['label' => 'OPEN', 'class' => 'bg-success-soft text-success', 'icon' => 'fe-check-circle'],
              'inactive' => ['label' => 'CLOSED', 'class' => 'bg-danger-soft text-danger', 'icon' => 'fe-x-circle'],
              'draft' => ['label' => 'DRAFT', 'class' => 'bg-warning-soft text-warning', 'icon' => 'fe-edit-3'],
              ];
              $statusInfo = $statusMap[$course->status] ?? ['label' => ucfirst($course->status), 'class' => 'bg-secondary-soft text-secondary', 'icon' => 'fe-help-circle'];
              @endphp
              <div class="col-lg-4 col-md-6 col-12">
                <div class="card mb-4 card-hover course-card course-card-{{ $courseCardAccent }}">
                  <a href="{{ route('admin.courses.realtime.show', $course->id) }}">
                    <img src="{{ asset($course->thumbnail == '' ? '/default-images/staff/no-course-img.png' : $course->thumbnail) }}" alt="course" class="card-img-top" style="height:180px; object-fit:cover;">
                  </a>
                  <div class="card-body">
                    <h4 class="mb-2 text-truncate-line-2">
                      <a href="{{ route('admin.courses.realtime.show', $course->id) }}" class="text-inherit">{{ $course->title }}</a>
                    </h4>
                    {{-- Instructor --}}
                    <div class="d-flex align-items-center justify-content-between mb-2">
                      <div class="d-flex align-items-center gap-2">
                        <img src="{{ asset($course->instructor->image == 'no-img.jpg' ? '/default-images/user/both.jpg' : $course->instructor->image) }}" class="rounded-circle" style="height:22px;width:22px;object-fit:cover;" alt="">
                        <span class="text-muted small">
                        {{ $course->instructor ? $course->instructor->name : 'N/A' }}
                        </span>
                      </div>
                      @if ($course->telegram_group_link)
                      <a href="{{ $course->telegram_group_link }}" target="_blank" rel="noopener" class="text-info" title="Open Telegram group">
                        <i class="fe fe-send"></i>
                      </a>
                      @endif
                    </div>
                    <div class="d-flex justify-content-between border-bottom py-2">
                      <span><i class="fe fe-play-circle me-1 text-muted"></i>Class Start</span>
                      <span class="text-dark">
                      {{ $course->start_date ? $course->start_date->format('M d, Y') : '—' }}
                      </span>
                    </div>
                    <div class="d-flex justify-content-between border-bottom py-2">
                      <span><i class="fe fe-flag me-1 text-muted"></i>Class End</span>
                      <span class="text-dark">
                      {{ $course->end_date ? $course->end_date->format('M d, Y') : '—' }}
                      </span>
                    </div>
                    <div class="d-flex justify-content-between border-bottom py-2">
                      <span><i class="fe fe-calendar me-1 text-muted"></i>Schedule</span>
                      <span class="text-dark text-end">
                                                        <div class="fw-semibold">{{ $course->schedule->short_days }}</div>
                                                        <div class="text-muted small">
                                                            <span
                                                                class="badge bg-light text-dark">{{ $course->schedule->shift_label }}</span>
                      {{ $course->schedule->formatted_time }}
                    </div>
                    </span>
                  </div>
                  <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                    <span><i class="fe fe-activity me-1 text-muted"></i>Status</span>
                    <span class="badge {{ $statusInfo['class'] }} d-inline-flex align-items-center gap-1">
                      <i class="fe {{ $statusInfo['icon'] }}"></i>{{ $statusInfo['label'] }}
                    </span>
                  </div>
                  <div class="d-flex justify-content-between border-bottom py-2">
                    <span><i class="fe fe-users me-1 text-muted"></i>Students</span>
                    <span class="text-dark">{{ $course->enrollments_count }}</span>
                  </div>
                  <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                    <span><i class="fe fe-dollar-sign me-1 text-muted"></i>Revenue</span>
                    <span
                                                        class="text-dark fw-semibold">${{ number_format($course->total_revenue ?? 0, 2) }}</span>
                  </div>
                  <div class="d-flex justify-content-between align-items-center pt-2">
                    <span>Full Price</span>
                    <span
                                                        class="badge bg-primary fs-6 px-3 py-2  fw-semibold">${{ number_format($course->price, 2) }}</span>
                  </div>
                </div>
              </div>
            </div>
            @empty
            <div class="col-12">
              <div class="card mb-4">
                <div class="card-body text-center py-5">
                  <div class="icon-shape icon-lg rounded-circle bg-light-secondary text-secondary mx-auto mb-3">
                    <i class="fe fe-inbox fs-3"></i>
                  </div>
                  <h3 class="mt-3 mb-2">No courses found</h3>
                  <p class="text-muted mb-4">Try adjusting your search or filters.</p>
                  <a href="{{ route('admin.courses.realtime.index') }}" class="btn btn-outline-secondary">
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
          <form method="GET" action="{{ route('admin.courses.realtime.index') }}" class="d-flex align-items-center position-relative">
            <span class="position-absolute ps-3 search-icon">
                                    <i class="fe fe-search"></i>
                                </span>
            <input type="search" name="search_query" value="{{ request('search_query') }}" class="form-control ps-6" placeholder="Search Course">
            @foreach (request('schedule_ids', []) as $sid)
            <input type="hidden" name="schedule_ids[]" value="{{ $sid }}">
            @endforeach
            <input type="hidden" name="status" value="{{ request('status') }}">
            <input type="hidden" name="sort_by" value="{{ request('sort_by') }}">
            <input type="hidden" name="month" value="{{ request('month') }}">
          </form>
        </div>
        {{-- Desktop / tablet (>= md): table --}}
        <div class="table-responsive border-0 d-none d-md-block">
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
              </tr>
            </thead>
            <tbody>
              @forelse ($courses as $course)
              @php
              $statusMap = [
              'active' => ['label' => 'OPEN', 'class' => 'bg-success-soft text-success', 'icon' => 'fe-check-circle'],
              'inactive' => ['label' => 'CLOSED', 'class' => 'bg-danger-soft text-danger', 'icon' => 'fe-x-circle'],
              'draft' => ['label' => 'DRAFT', 'class' => 'bg-warning-soft text-warning', 'icon' => 'fe-edit-3'],
              ];
              $statusInfo = $statusMap[$course->status] ?? ['label' => ucfirst($course->status), 'class' => 'bg-secondary-soft text-secondary', 'icon' => 'fe-help-circle'];
              @endphp
              <tr>
                <td>
                  <div class="d-flex align-items-center">
                    <img src="{{ asset($course->thumbnail == '' ? '/default-images/staff/no-course-img.png' : $course->thumbnail) }}" alt="" class="img-4by3-lg rounded">
                    <div class="ms-3">
                      <h4 class="mb-0 text-primary-hover d-flex align-items-center gap-2">
                        <a href="{{ route('admin.courses.realtime.show', $course->id) }}" class="text-inherit">
                          {{ $course->title }}
                        </a>
                        @if ($course->telegram_group_link)
                        <a href="{{ $course->telegram_group_link }}" target="_blank" rel="noopener" class="text-info" title="Open Telegram group">
                          <i class="fe fe-send"></i>
                        </a>
                        @endif
                      </h4>
                    </div>
                  </div>
                </td>
                <td>
                  <div class="d-flex align-items-center">
                    <img src="{{ asset($course->instructor->image == 'no-img.jpg' ? '/default-images/user/both.jpg' : $course->instructor->image) }}" alt="" class="rounded-circle me-2" style="height:25px;width:25px;object-fit:cover;">
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
                <td class="fw-semibold">${{ number_format($course->total_revenue ?? 0, 2) }}</td>
                <td><span
                                                    class="badge bg-primary fs-6 px-2 py-1">${{ number_format($course->price, 2) }}</span>
                </td>
                <td>
                  <span class="badge {{ $statusInfo['class'] }} d-inline-flex align-items-center gap-1">
                    <i class="fe {{ $statusInfo['icon'] }}"></i>{{ $statusInfo['label'] }}
                  </span>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="9" class="text-center py-5">
                  <div class="icon-shape icon-lg rounded-circle bg-light-secondary text-secondary mx-auto mb-3">
                    <i class="fe fe-inbox fs-3"></i>
                  </div>
                  <h3 class="mt-3 mb-2">No courses found</h3>
                  <p class="text-muted mb-4">Try adjusting your search or filters.</p>
                  <a href="{{ route('admin.courses.realtime.index') }}" class="btn btn-outline-secondary">
                    Reset Filters
                  </a>
                </td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
        {{-- Mobile (< md): stacked cards --}}
        <div class="d-md-none">
          @forelse ($courses as $course)
          @php
          $statusMap = [
          'active' => ['label' => 'OPEN', 'class' => 'bg-success-soft text-success', 'icon' => 'fe-check-circle'],
          'inactive' => ['label' => 'CLOSED', 'class' => 'bg-danger-soft text-danger', 'icon' => 'fe-x-circle'],
          'draft' => ['label' => 'DRAFT', 'class' => 'bg-warning-soft text-warning', 'icon' => 'fe-edit-3'],
          ];
          $statusInfo = $statusMap[$course->status] ?? ['label' => ucfirst($course->status), 'class' => 'bg-secondary-soft text-secondary', 'icon' => 'fe-help-circle'];
          @endphp
          <div class="course-list-card border-bottom">
            <div class="d-flex gap-3 mb-2">
              <img src="{{ asset($course->thumbnail == '' ? '/default-images/staff/no-course-img.png' : $course->thumbnail) }}" alt="" class="rounded flex-shrink-0" style="width:72px;height:54px;object-fit:cover;">
              <div class="flex-grow-1 min-w-0">
                <h5 class="mb-1 text-truncate-line-2">
                  <a href="{{ route('admin.courses.realtime.show', $course->id) }}" class="text-inherit">{{ $course->title }}</a>
                </h5>
                <div class="d-flex align-items-center gap-2 small text-muted">
                  <img src="{{ asset($course->instructor->image == 'no-img.jpg' ? '/default-images/user/both.jpg' : $course->instructor->image) }}" class="rounded-circle flex-shrink-0" style="height:18px;width:18px;object-fit:cover;" alt="">
                  <span class="text-truncate">{{ $course->instructor ? $course->instructor->name : 'N/A' }}</span>
                  @if ($course->telegram_group_link)
                  <a href="{{ $course->telegram_group_link }}" target="_blank" rel="noopener" class="text-info flex-shrink-0" title="Open Telegram group">
                    <i class="fe fe-send"></i>
                  </a>
                  @endif
                </div>
              </div>
              <span class="badge {{ $statusInfo['class'] }} flex-shrink-0 align-self-start d-inline-flex align-items-center gap-1">
                <i class="fe {{ $statusInfo['icon'] }}"></i>{{ $statusInfo['label'] }}
              </span>
            </div>
            <div class="small text-muted mb-2">
              <i class="fe fe-calendar me-1"></i>{{ $course->schedule->short_days }}
              <span class="badge bg-light text-dark ms-1">{{ $course->schedule->shift_label }}</span>
              {{ $course->schedule->formatted_time }}
            </div>
            <div class="course-list-card-grid small mb-2">
              <div>
                <div class="label text-muted">Start</div>
                <div class="fw-semibold">{{ $course->start_date ? $course->start_date->format('M d, Y') : '—' }}</div>
              </div>
              <div>
                <div class="label text-muted">End</div>
                <div class="fw-semibold">{{ $course->end_date ? $course->end_date->format('M d, Y') : '—' }}</div>
              </div>
              <div>
                <div class="label text-muted">Students</div>
                <div class="fw-semibold">{{ $course->enrollments_count }}</div>
              </div>
              <div>
                <div class="label text-muted">Revenue</div>
                <div class="fw-semibold">${{ number_format($course->total_revenue ?? 0, 2) }}</div>
              </div>
            </div>
            <div class="d-flex justify-content-between align-items-center">
              <span class="text-muted small">Full Price</span>
              <span class="badge bg-primary fs-6 px-2 py-1">${{ number_format($course->price, 2) }}</span>
            </div>
          </div>
          @empty
          <div class="text-center py-5">
            <div class="icon-shape icon-lg rounded-circle bg-light-secondary text-secondary mx-auto mb-3">
              <i class="fe fe-inbox fs-3"></i>
            </div>
            <h3 class="mt-3 mb-2">No courses found</h3>
            <p class="text-muted mb-4">Try adjusting your search or filters.</p>
            <a href="{{ route('admin.courses.realtime.index') }}" class="btn btn-outline-secondary">
              Reset Filters
            </a>
          </div>
          @endforelse
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
  // Admin is view-only for existing courses now — the Edit and Delete
  // buttons/routes for a course were removed, so their click handlers
  // (and the "Delete course" AJAX call) were removed here too. Creating
  // a new course is still allowed, via the modal below.
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
