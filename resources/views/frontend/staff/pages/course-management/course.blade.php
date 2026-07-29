@extends('frontend.staff.layout.master')
@section('page_title', isset($page_title) ? $page_title : 'Page Title Here')
@push('styles')
<style>
  .course-group-card {
    border: 1px solid var(--bs-border-color);
    border-radius: 12px;
    overflow: hidden;
    margin-bottom: 1rem;
  }
  .course-group-header {
    background: var(--bs-light);
    padding: 14px 16px;
    display: flex;
    align-items: center;
    gap: 12px;
    cursor: pointer;
  }
  .course-group-thumb {
    width: 44px;
    height: 34px;
    border-radius: 6px;
    object-fit: cover;
    flex-shrink: 0;
  }
  .course-group-meta {
    font-size: 12px;
    color: var(--bs-secondary-color);
    margin: 0;
  }
  .course-batch-row {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 10px 16px 10px 60px;
    border-top: 1px solid var(--bs-border-color);
    font-size: 13px;
    flex-wrap: wrap;
  }
  .course-batch-row:hover {
    background: var(--bs-light);
  }
  .course-batch-status {
    min-width: 56px;
    text-align: center;
    font-size: 11px;
    font-weight: 600;
    border-radius: 5px;
    padding: 3px 6px;
  }
  .course-group-chevron {
    transition: transform .2s;
  }
  .course-group-header.collapsed .course-group-chevron {
    transform: rotate(-90deg);
  }
  .filter-toolbar .dropdown-toggle::after {
    margin-left: 6px;
  }
  .filter-toolbar .btn {
    font-size: 13px;
  }
  .active-filter-chip {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: var(--bs-light-info);
    color: var(--bs-info);
    border-radius: 20px;
    padding: 3px 10px 3px 12px;
    font-size: 12px;
    font-weight: 600;
  }
  .active-filter-chip a {
    color: var(--bs-info);
    line-height: 1;
  }
</style>
@endpush
@section('content')
@include('frontend.staff.pages.partials.breadcrumb')
{{-- ============ FILTER TOOLBAR ============ --}}
<div class="card card-body">
  <form action="{{ route('staff.courses.index') }}" method="GET" id="filterForm">
    @if ($showingAll ?? false)
    <input type="hidden" name="per_page" value="all">
    @endif
    <input type="hidden" name="sort" value="{{ $sortField }}">
    <input type="hidden" name="direction" value="{{ $sortDirection }}">
    @if (request('status'))
    <input type="hidden" name="status" value="{{ request('status') }}">
    @endif
    @if (request('category'))
    <input type="hidden" name="category" value="{{ request('category') }}">
    @endif
    @if (request('featured'))
    <input type="hidden" name="featured" value="{{ request('featured') }}">
    @endif
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
      {{-- Left: search + filters, wraps as one unit --}}
      <div class="d-flex flex-wrap align-items-center gap-2 filter-toolbar">
        <div class="position-relative" style="min-width:310px;">
          <input type="search" class="form-control ps-5" placeholder="Search course" name="search" value="{{ request('search') }}">
          <i class="ti ti-search position-absolute top-50 start-0 translate-middle-y fs-6 text-dark ms-3"></i>
        </div>
        <div class="dropdown">
          <button class="btn btn-outline-secondary dropdown-toggle d-flex align-items-center gap-1" type="button" data-bs-toggle="dropdown">
            <i class="ti ti-filter fs-6"></i> Status: {{ ucfirst(request('status', 'all')) }}
          </button>
          <ul class="dropdown-menu">
            @foreach (['all' => 'All', 'active' => 'Active', 'inactive' => 'Inactive'] as $value => $label)
            <li><a class="dropdown-item {{ request('status', 'all') === $value ? 'active' : '' }}" href="{{ request()->fullUrlWithQuery(['status' => $value === 'all' ? null : $value, 'page' => null]) }}">{{ $label }}</a></li>
            @endforeach
          </ul>
        </div>
        <div class="dropdown">
          <button class="btn btn-outline-secondary dropdown-toggle d-flex align-items-center gap-1" type="button" data-bs-toggle="dropdown">
            <i class="ti ti-category fs-6"></i>
            Category: {{ ($categories ?? collect())->firstWhere('id', (int) request('category'))->name ?? 'All' }}
          </button>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item {{ !request('category') ? 'active' : '' }}" href="{{ request()->fullUrlWithQuery(['category' => null, 'page' => null]) }}">All</a></li>
            @foreach (($categories ?? []) as $category)
            <li><a class="dropdown-item {{ (int) request('category') === $category->id ? 'active' : '' }}" href="{{ request()->fullUrlWithQuery(['category' => $category->id, 'page' => null]) }}">{{ $category->name }}</a></li>
            @endforeach
          </ul>
        </div>
        <a href="{{ request()->fullUrlWithQuery(['featured' => request('featured') == 1 ? null : 1, 'page' => null]) }}" class="btn btn-outline-warning d-flex align-items-center gap-1 {{ request('featured') == 1 ? 'active' : '' }}">
          <i class="ti ti-star fs-6"></i> Featured
        </a>
        <div class="dropdown">
          <button class="btn btn-outline-dark dropdown-toggle d-flex align-items-center gap-1" type="button" data-bs-toggle="dropdown">
            <i class="ti ti-arrows-sort fs-6"></i> Sort
          </button>
          <ul class="dropdown-menu">
            @php
            $sortOptions = [
            'title' => 'Course Title',
            'price' => 'Price',
            'start_date' => 'Start Date',
            'duration' => 'Duration',
            'created_at' => 'Date Added',
            ];
            @endphp
            @foreach ($sortOptions as $field => $label)
            <li>
              <a class="dropdown-item d-flex justify-content-between align-items-center {{ $sortField === $field ? 'active' : '' }}" href="{{ request()->fullUrlWithQuery(['sort' => $field, 'direction' => $sortField === $field && $sortDirection === 'asc' ? 'desc' : 'asc', 'page' => null]) }}">
                {{ $label }}
                @if ($sortField === $field)
                <i class="ti ti-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }} fs-5 ms-2"></i>
                @endif
              </a>
            </li>
            @endforeach
          </ul>
        </div>
        @if (request()->anyFilled(['search', 'status', 'featured', 'category']))
        <a href="{{ route('staff.courses.index') }}" class="btn btn-link text-muted d-flex align-items-center gap-1 px-2">
          <i class="ti ti-refresh fs-6"></i> Reset
        </a>
        @endif
      </div>
      {{-- Right: primary action, stays pinned to this row --}}
      <a href="{{ route('staff.courses.create') }}" id="btn-add-contact" class="btn btn-info d-flex align-items-center flex-shrink-0">
        <i class="ti ti-circle-plus text-white me-1 fs-5"></i> Add Course
      </a>
    </div>
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mt-3 pt-2 border-top">
      <div class="form-check d-flex align-items-center gap-2 mb-0">
        <input class="form-check-input" type="checkbox" id="selectAllCourses" style="width:1.1em;height:1.1em;cursor:pointer;">
        <label class="form-check-label fs-3 text-muted mb-0" for="selectAllCourses" style="cursor:pointer;">Select all</label>
      </div>
      <div class="btn-group" role="group" aria-label="View toggle">
        <button type="button" class="btn btn-outline-primary btn-sm" id="btnListView" title="List view">
          <i class="ti ti-list me-1"></i> List
        </button>
        <button type="button" class="btn btn-outline-primary btn-sm active" id="btnGridView" title="Grid view">
          <i class="ti ti-layout-grid me-1"></i> Grid
        </button>
      </div>
    </div>
  </form>
</div>
<div class="card card-body">
  {{-- ============ LIST (TABLE) VIEW ============ --}}
  <div class="table-responsive" id="courseListView" style="display:none;">
    <table class="table align-middle mb-0 text-nowrap">
      <thead>
        <tr class="text-muted fw-semibold">
          <th class="ps-0" style="width:36px;"></th>
          <th class="ps-0">Course / batch</th>
          <th class="ps-0">Price</th>
          <th class="ps-0">Schedule</th>
          <th class="ps-0">Duration</th>
          <th class="ps-0">Capacity</th>
          <th class="ps-0">Status</th>
          <th class="ps-0">Dates</th>
          <th class="text-end ps-0">Action</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($groupedCourses as $group)
        <tr class="table-light">
          <td class="ps-0"></td>
          <td colspan="6" class="ps-0">
            <div class="d-flex align-items-center gap-2 py-1">
              <span class="fw-semibold">{{ $group['title'] }}</span>
              <span class="badge bg-light-info text-info">{{ $group['batch_count'] }} {{ Str::plural('batch', $group['batch_count']) }}</span>
            </div>
          </td>
          <td class="ps-0"></td>
          <td class="text-end ps-0">
            <a href="{{ route('staff.courses.duplicate', $group['batches']->first()->id) }}" class="btn btn-sm btn-outline-info">
              <i class="ti ti-copy me-1"></i> Add batch
            </a>
          </td>
        </tr>
        @foreach ($group['batches'] as $course)
        <tr class="course-row" data-status="{{ $course->status }}">
          <td class="ps-0">
            <input type="checkbox" class="form-check-input course-select" value="{{ $course->id }}">
          </td>
          <td class="ps-0">
            <div class="d-flex align-items-center gap-2 ms-3">
              <span class="text-muted">&#8627;</span>
              <img src="{{ $course->instructor->image == 'no-img.jpg' ? asset('/default-images/user/both.jpg') : asset($course->instructor->image) }}" class="rounded-circle" width="22" height="22" style="object-fit:cover;">
              <a href="{{ route('staff.courses.show', $course->id) }}" class="text-decoration-none text-dark">{{ $course->instructor->name }}</a>
              @if ($course->telegram_group_link)
              <a href="{{ $course->telegram_group_link }}" target="_blank" rel="noopener" class="text-info" title="Open Telegram group">
                <i class="ti ti-brand-telegram fs-4"></i>
              </a>
              @endif
            </div>
          </td>
          <td class="ps-0">
            <span class="badge bg-light-success text-dark"><strong>${{ number_format($course->price, 2) }}</strong></span>
          </td>
          <td class="ps-0">
            @if ($course->schedule)
            @php
            $days = collect(explode('-', $course->schedule->study_day))->map(fn($day) => ucfirst($day))->implode(' • ');
            $start = \Carbon\Carbon::parse($course->schedule->start_time)->format('g:i A');
            $end = \Carbon\Carbon::parse($course->schedule->end_time)->format('g:i A');
            @endphp
            <div class="fw-semibold">{{ $days }}</div>
            <small class="text-muted">{{ $start }} - {{ $end }}</small>
            @else
            <span class="text-muted">No schedule</span>
            @endif
          </td>
          <td class="ps-0">{{ $course->duration ?? '-' }}hr</td>
          <td class="ps-0">
            @if ($course->capacity)
            <span class="fw-semibold">{{ $course->students_count ?? 0 }}/{{ $course->capacity }}</span>
            @else
            <span class="text-muted">Unlimited</span>
            @endif
          </td>
          <td class="ps-0">
            <div class="d-flex align-items-center gap-2">
              @if ($course->status == 'active')
              <span class="badge bg-success">OPEN</span>
              @elseif($course->status == 'inactive')
              <span class="badge bg-danger">CLOSE</span>
              @endif
              @if ($course->featured)
              <i class="ti ti-star-filled text-warning fs-5" title="Featured"></i>
              @endif
            </div>
          </td>
          <td class="ps-0">
            <div>{{ \Carbon\Carbon::parse($course->start_date)->format('d M Y') }}</div>
            @if ($course->end_date)
            <small class="text-muted">to {{ \Carbon\Carbon::parse($course->end_date)->format('d M Y') }}</small>
            @endif
          </td>
          <td class="text-end ps-0">
            <div class="dropdown dropstart">
              <a href="#" class="text-muted" data-bs-toggle="dropdown">
                <i class="ti ti-dots-vertical fs-6"></i>
              </a>
              <ul class="dropdown-menu">
                <li>
                  <a class="dropdown-item d-flex align-items-center gap-3" href="{{ route('staff.courses.show', $course->id) }}">
                    <i class="ti ti-eye fs-4"></i> View
                  </a>
                </li>
                <li>
                  <a class="dropdown-item d-flex align-items-center gap-3" href="{{ route('staff.courses.edit', [$course, 'redirect' => url()->full()]) }}">
                    <i class="ti ti-edit fs-4"></i> Edit
                  </a>
                </li>
                <li>
                  <a class="dropdown-item d-flex align-items-center gap-3" href="{{ route('staff.courses.duplicate', $course->id) }}">
                    <i class="ti ti-copy fs-4"></i> Duplicate as new batch
                  </a>
                </li>
                <li>
                  <form action="{{ route('staff.courses.toggle-featured', $course->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="dropdown-item d-flex align-items-center gap-3">
                      <i class="ti ti-star{{ $course->featured ? '-filled text-warning' : '' }} fs-4"></i>
                      {{ $course->featured ? 'Unmark Featured' : 'Mark as Featured' }}
                    </button>
                  </form>
                </li>
                <li>
                  <a class="dropdown-item d-flex align-items-center gap-3 text-danger btn-delete-course" href="javascript:void(0)" data-id="{{ $course->id }}" data-title="{{ $course->title }}">
                    <i class="ti ti-trash fs-4"></i> Delete
                  </a>
                </li>
              </ul>
            </div>
          </td>
        </tr>
        @endforeach
        @empty
        <tr>
          <td colspan="9" class="text-center text-muted py-5">No courses available.</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  {{-- ============ GRID VIEW — grouped by title ============ --}}
  <div id="courseGridView">
    @forelse ($groupedCourses as $gi => $group)
    <div class="course-group-card">
      <div class="course-group-header js-group-toggle" data-target="#batches-{{ $gi }}">
        <img src="{{ asset($group['thumbnail'] == '' ? '/default-images/staff/no-course-img.png' : $group['thumbnail']) }}" class="course-group-thumb" loading="lazy">
        <div class="flex-grow-1">
          <div class="d-flex align-items-center gap-2">
            <h6 class="mb-0 fw-semibold">{{ $group['title'] }}</h6>
          </div>
          @if ($group['khmer_title'])
          <p class="course-group-meta mb-0">{{ $group['khmer_title'] }}</p>
          @endif
          <p class="course-group-meta">
            {{ $group['batch_count'] }} {{ Str::plural('batch', $group['batch_count']) }} &middot;
            ${{ number_format($group['min_price'], 2) }}{{ $group['max_price'] > $group['min_price'] ? ' – $' . number_format($group['max_price'], 2) : '' }}
            &middot; {{ $group['open_count'] }} open, {{ $group['closed_count'] }} closed
          </p>
        </div>
        <a href="{{ route('staff.courses.duplicate', $group['batches']->first()->id) }}" class="btn btn-sm btn-outline-info js-add-batch">
          <i class="ti ti-copy me-1"></i> Add batch
        </a>
        <i class="ti ti-chevron-down course-group-chevron fs-5 text-muted"></i>
      </div>
      <div id="batches-{{ $gi }}" class="collapse {{ $gi === 0 ? 'show' : '' }}">
        @foreach ($group['batches'] as $course)
        <div class="course-batch-row" data-status="{{ $course->status }}">
          <input type="checkbox" class="form-check-input course-select" value="{{ $course->id }}">
          <span class="course-batch-status {{ $course->status == 'active' ? 'bg-light-success text-success' : 'bg-light-danger text-danger' }}">
          {{ $course->status == 'active' ? 'OPEN' : 'CLOSE' }}
          </span>
          @if ($course->featured)
          <i class="ti ti-star-filled text-warning fs-5" title="Featured"></i>
          @endif
          <img src="{{ $course->instructor->image == 'no-img.jpg' ? asset('/default-images/user/both.jpg') : asset($course->instructor->image) }}" class="rounded-circle" width="24" height="24" style="object-fit:cover;">
          <span style="min-width:120px;">{{ $course->instructor->name }}</span>
          <span style="min-width:200px;">
            @if ($course->schedule)
            @php
            $days = collect(explode('-', $course->schedule->study_day))->map(fn($day) => ucfirst($day))->implode(' • ');
            $start = \Carbon\Carbon::parse($course->schedule->start_time)->format('g:i A');
            $end = \Carbon\Carbon::parse($course->schedule->end_time)->format('g:i A');
            @endphp
            {{ $days }}, {{ $start }} - {{ $end }}
            @else
            <span class="text-muted">No schedule</span>
          @endif
          </span>
          <span class="text-success fw-semibold" style="min-width:70px;">${{ number_format($course->price, 2) }}</span>
          <span class="text-muted" style="min-width:60px;">{{ $course->duration ?? '-' }}hr</span>
          <span class="text-muted" style="min-width:70px;">
          {{ $course->capacity ? ($course->students_count ?? 0) . '/' . $course->capacity : 'Unlimited' }}
          </span>
          <span class="text-muted flex-grow-1">
          {{ \Carbon\Carbon::parse($course->start_date)->format('d M Y') }}
          @if ($course->end_date)
          – {{ \Carbon\Carbon::parse($course->end_date)->format('d M Y') }}
          @endif
          </span>
          @if ($course->telegram_group_link)
          <a href="{{ $course->telegram_group_link }}" target="_blank" rel="noopener" class="text-info" title="Open Telegram group">
            <i class="ti ti-brand-telegram fs-4"></i>
          </a>
          @endif
          <div class="dropdown dropstart">
            <a href="#" class="text-muted" data-bs-toggle="dropdown">
              <i class="ti ti-dots-vertical fs-6"></i>
            </a>
            <ul class="dropdown-menu">
              <li>
                <a class="dropdown-item d-flex align-items-center gap-3" href="{{ route('staff.courses.show', $course->id) }}">
                  <i class="ti ti-eye fs-4"></i> View
                </a>
              </li>
              <li>
                <a class="dropdown-item d-flex align-items-center gap-3" href="{{ route('staff.courses.edit', [$course, 'redirect' => url()->full()]) }}">
                  <i class="ti ti-edit fs-4"></i> Edit
                </a>
              </li>
              <li>
                <a class="dropdown-item d-flex align-items-center gap-3" href="{{ route('staff.courses.duplicate', $course->id) }}">
                  <i class="ti ti-copy fs-4"></i> Duplicate as new batch
                </a>
              </li>
              <li>
                <form action="{{ route('staff.courses.toggle-featured', $course->id) }}" method="POST">
                  @csrf
                  @method('PATCH')
                  <button type="submit" class="dropdown-item d-flex align-items-center gap-3">
                    <i class="ti ti-star{{ $course->featured ? '-filled text-warning' : '' }} fs-4"></i>
                    {{ $course->featured ? 'Unmark Featured' : 'Mark as Featured' }}
                  </button>
                </form>
              </li>
              <li>
                <a class="dropdown-item d-flex align-items-center gap-3 text-danger btn-delete-course" href="javascript:void(0)" data-id="{{ $course->id }}" data-title="{{ $course->title }}">
                  <i class="ti ti-trash fs-4"></i> Delete
                </a>
              </li>
            </ul>
          </div>
        </div>
        @endforeach
      </div>
    </div>
    @empty
    <div class="text-center text-muted py-5">No courses available.</div>
    @endforelse
  </div>
  {{-- ============ SHARED FOOTER ============ --}}
  @php
  $hasMultiplePages = !($showingAll ?? false) && method_exists($courses, 'hasPages') && $courses->hasPages();
  @endphp
  @if ($hasMultiplePages || ($showingAll ?? false))
  <div class="d-flex flex-wrap justify-content-end align-items-center mt-4 pt-3 border-top gap-3">
    @if ($hasMultiplePages)
    {{ $courses->appends(request()->except('page'))->links('frontend.staff.pages.pagination.custom') }}
    <a href="{{ request()->fullUrlWithQuery(['per_page' => 'all', 'page' => null]) }}" class="btn btn-outline-info btn-sm">
      <i class="ti ti-list-details me-1"></i> Show All
    </a>
    @else
    <span class="text-muted small">Showing all {{ $courses->count() }} results</span>
    <a href="{{ request()->fullUrlWithQuery(['per_page' => 10, 'page' => null]) }}" class="btn btn-outline-secondary btn-sm">
      <i class="ti ti-layout-list me-1"></i> Paginate
    </a>
    @endif
  </div>
  @endif
  {{-- DELETE MODAL --}}
  <div class="modal fade" id="deleteTeacherModal" tabindex="-1" aria-hidden="true" style="display:none;">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header d-flex align-items-center">
          <h5 class="modal-title text-danger"><i class="ti ti-trash me-2"></i> Delete Course</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body text-center py-4">
          <i class="ti ti-alert-triangle text-warning" style="font-size: 3rem;"></i>
          <h5 class="mt-3">Are you sure?</h5>
          <p class="text-muted mb-0">
            Do you really want to delete the course "<span id="delete-course-title" class="fw-semibold"></span>"? <br>
            This action cannot be undone.
          </p>
        </div>
        <div class="modal-footer justify-content-center">
          <form id="deleteCourseForm" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger rounded-pill px-4">
              <i class="ti ti-trash me-1"></i> Yes, Delete
            </button>
          </form>
          <button class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">
            <i class="ti ti-x me-1"></i> Cancel
          </button>
        </div>
      </div>
    </div>
  </div>
</div>
{{-- BULK FEATURED ACTION BAR --}}
<div id="bulkActionBar" class="d-none position-fixed bottom-0 start-50 translate-middle-x mb-4 shadow-lg rounded-pill bg-dark text-white px-4 py-2 d-flex align-items-center gap-3" style="z-index:1050;">
  <span class="fs-3"><strong id="selectedCount">0</strong> selected</span>
  <button type="button" class="btn btn-warning btn-sm rounded-pill" id="btnMarkFeatured">
    <i class="ti ti-star-filled me-1"></i> Mark Featured
  </button>
  <button type="button" class="btn btn-outline-light btn-sm rounded-pill" id="btnUnmarkFeatured">
    <i class="ti ti-star-off me-1"></i> Unmark
  </button>
  <button type="button" class="btn btn-link text-white btn-sm p-0" id="btnClearSelection">
    <i class="ti ti-x fs-5"></i>
  </button>
</div>
<form id="bulkFeaturedForm" action="{{ route('staff.courses.bulk-featured') }}" method="POST" class="d-none">
  @csrf
  <input type="hidden" name="featured" id="bulkFeaturedValue">
  <div id="bulkFeaturedIds"></div>
</form>
@include('frontend.staff.pages.course-management.partials.curriculum-modal')
@endsection
@push('scripts')
@include('frontend.staff.pages.course-management.partials.curriculum-scripts')
<script>
  // ─── Delete ─────────────────────────────────────────────────────────────────
  document.querySelectorAll('.btn-delete-course').forEach(function(btn) {
    btn.addEventListener('click', function() {
      const id = this.dataset.id;
      const title = this.dataset.title;
      document.getElementById('delete-course-title').textContent = title;
      document.getElementById('deleteCourseForm').action = `/staff/courses/${id}`;
      new bootstrap.Modal(document.getElementById('deleteTeacherModal')).show();
    });
  });
  // ─── View toggle (List / Grid) — GRID is default ───────────────────────────
  const listView = document.getElementById('courseListView');
  const gridView = document.getElementById('courseGridView');
  const btnListView = document.getElementById('btnListView');
  const btnGridView = document.getElementById('btnGridView');
  function applyView(view) {
    if (view === 'list') {
      gridView.style.display = 'none';
      listView.style.display = 'block';
      btnListView.classList.add('active');
      btnGridView.classList.remove('active');
    } else {
      listView.style.display = 'none';
      gridView.style.display = 'block';
      btnGridView.classList.add('active');
      btnListView.classList.remove('active');
    }
    localStorage.setItem('staffCourseView', view);
  }
  btnListView.addEventListener('click', () => applyView('list'));
  btnGridView.addEventListener('click', () => applyView('grid'));
  applyView(localStorage.getItem('staffCourseView') || 'grid');
  // ─── Group header: toggle batches, but let "Add batch" navigate normally ──
  document.querySelectorAll('.js-group-toggle').forEach(function(header) {
    const target = document.querySelector(header.dataset.target);
    if (!target) return;
    if (!target.classList.contains('show')) header.classList.add('collapsed');
    target.addEventListener('show.bs.collapse', () => header.classList.remove('collapsed'));
    target.addEventListener('hide.bs.collapse', () => header.classList.add('collapsed'));
    header.addEventListener('click', function(e) {
      if (e.target.closest('.js-add-batch')) {
        return; // let the link navigate, don't toggle the accordion
      }
      const instance = bootstrap.Collapse.getOrCreateInstance(target, {
        toggle: false
      });
      instance.toggle();
    });
  });
  // ─── Bulk select & featured action ─────────────────────────────
  (function() {
    const selectAll = document.getElementById('selectAllCourses');
    const bar = document.getElementById('bulkActionBar');
    const countEl = document.getElementById('selectedCount');
    const bulkForm = document.getElementById('bulkFeaturedForm');
    const bulkIdsContainer = document.getElementById('bulkFeaturedIds');
    const bulkFeaturedValue = document.getElementById('bulkFeaturedValue');
    function allCheckboxesForId(id) {
      return document.querySelectorAll(`.course-select[value="${id}"]`);
    }
    function updateBar() {
      const checkedIds = new Set(
        Array.from(document.querySelectorAll('.course-select:checked')).map(cb => cb.value)
      );
      countEl.textContent = checkedIds.size;
      bar.classList.toggle('d-none', checkedIds.size === 0);
      const totalUniqueIds = new Set(
        Array.from(document.querySelectorAll('.course-select')).map(cb => cb.value)
      ).size;
      selectAll.checked = checkedIds.size > 0 && checkedIds.size === totalUniqueIds;
    }
    document.addEventListener('change', e => {
      if (e.target.classList.contains('course-select')) {
        allCheckboxesForId(e.target.value).forEach(cb => cb.checked = e.target.checked);
        updateBar();
      }
    });
    selectAll.addEventListener('change', function() {
      document.querySelectorAll('.course-select').forEach(cb => cb.checked = this.checked);
      updateBar();
    });
    document.getElementById('btnClearSelection').addEventListener('click', () => {
      document.querySelectorAll('.course-select').forEach(cb => cb.checked = false);
      updateBar();
    });
    function submitBulk(featuredValue) {
      const ids = [...new Set(
        Array.from(document.querySelectorAll('.course-select:checked')).map(cb => cb.value)
      )];
      if (ids.length === 0) return;
      bulkIdsContainer.innerHTML = '';
      ids.forEach(id => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'course_ids[]';
        input.value = id;
        bulkIdsContainer.appendChild(input);
      });
      bulkFeaturedValue.value = featuredValue;
      bulkForm.submit();
    }
    document.getElementById('btnMarkFeatured').addEventListener('click', () => submitBulk('1'));
    document.getElementById('btnUnmarkFeatured').addEventListener('click', () => submitBulk('0'));
  })();
</script>
@endpush
