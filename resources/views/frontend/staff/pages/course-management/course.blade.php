@extends('frontend.staff.layout.master')
@section('page_title', isset($page_title) ? $page_title : 'Page Title Here')
@section('content')
    @include('frontend.staff.pages.partials.breadcrumb')
    <div class="card card-body">
        <div class="row g-3 align-items-center">
            {{-- Search --}}
            <div class="col-md-4 col-xl-3">
                <form class="position-relative" action="{{ route('staff.courses.index') }}" method="GET">
                    @if ($showingAll ?? false)
                        <input type="hidden" name="per_page" value="all">
                    @endif
                    @if (request('status'))
                        <input type="hidden" name="status" value="{{ request('status') }}">
                    @endif
                    @if (request('sort'))
                        <input type="hidden" name="sort" value="{{ request('sort') }}">
                    @endif
                    @if (request('direction'))
                        <input type="hidden" name="direction" value="{{ request('direction') }}">
                    @endif
                    <input type="search" class="form-control product-search ps-5" id="input-search"
                        placeholder="Search Course" name="search" value="{{ request()->search ?? '' }}">
                    <i class="ti ti-search position-absolute top-50 start-0 translate-middle-y fs-6 text-dark ms-3"></i>
                </form>
            </div>
            {{-- Status filter + Sort + View toggle --}}
            <div class="col-md-6 col-xl-7 d-flex flex-wrap justify-content-center justify-content-md-start gap-2">
                {{-- Status filter --}}
                <div class="btn-group" role="group" aria-label="Status filter">
                    @php $currentStatus = request('status', 'all'); @endphp
                    <a href="{{ request()->fullUrlWithQuery(['status' => 'all', 'page' => null]) }}"
                        class="btn btn-outline-secondary btn-sm {{ $currentStatus === 'all' ? 'active' : '' }}">All</a>
                    <a href="{{ request()->fullUrlWithQuery(['status' => 'active', 'page' => null]) }}"
                        class="btn btn-outline-success btn-sm {{ $currentStatus === 'active' ? 'active' : '' }}">Active</a>
                    <a href="{{ request()->fullUrlWithQuery(['status' => 'inactive', 'page' => null]) }}"
                        class="btn btn-outline-danger btn-sm {{ $currentStatus === 'inactive' ? 'active' : '' }}">Inactive</a>
                </div>
                {{-- Sort dropdown --}}
                <div class="dropdown">
                    <button class="btn btn-outline-dark btn-sm dropdown-toggle d-flex align-items-center gap-1"
                        type="button" data-bs-toggle="dropdown">
                        <i class="ti ti-arrows-sort fs-5"></i> Sort
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
                                <a class="dropdown-item d-flex justify-content-between align-items-center {{ $sortField === $field ? 'active' : '' }}"
                                    href="{{ request()->fullUrlWithQuery(['sort' => $field, 'direction' => $sortField === $field && $sortDirection === 'asc' ? 'desc' : 'asc', 'page' => null]) }}">
                                    {{ $label }}
                                    @if ($sortField === $field)
                                        <i
                                            class="ti ti-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }} fs-5 ms-2"></i>
                                    @endif
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
                {{-- View toggle --}}
                <div class="btn-group" role="group" aria-label="View toggle">
                    <button type="button" class="btn btn-outline-primary btn-sm" id="btnListView" title="List view">
                        <i class="ti ti-list"></i>
                    </button>
                    <button type="button" class="btn btn-outline-primary btn-sm active" id="btnGridView" title="Grid view">
                        <i class="ti ti-layout-grid"></i>
                    </button>
                </div>
                {{-- Reset --}}
                @if (request()->anyFilled(['search', 'status', 'sort', 'direction', 'per_page']))
                    <a href="{{ route('staff.courses.index') }}"
                        class="btn btn-outline-secondary btn-sm d-flex align-items-center gap-1">
                        <i class="ti ti-refresh fs-5"></i> Reset
                    </a>
                @endif
            </div>
            <div class="col-md-2 col-xl-2 text-end d-flex justify-content-md-end justify-content-center mt-3 mt-md-0">
                <a href="{{ route('staff.courses.create') }}" id="btn-add-contact"
                    class="btn btn-info d-flex align-items-center">
                    <i class="ti ti-circle-plus text-white me-1 fs-5"></i> Add Course
                </a>
            </div>
        </div>
    </div>
    <div class="card card-body">
        {{-- ============ LIST (TABLE) VIEW ============ --}}
        <div class="table-responsive" id="courseListView" style="display:none;">
            <table class="table align-middle mb-0 text-nowrap">
                <thead>
                    <tr class="text-muted fw-semibold">
                        <th class="ps-0">Course</th>
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
                    @forelse ($courses as $course)
                        <tr class="course-row" data-status="{{ $course->status }}">
                            {{-- Course --}}
                            <td class="ps-0">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="flex-shrink-0">
                                        <a href="{{ route('staff.courses.show', $course->id) }}">
                                            <img src="{{ asset($course->thumbnail == '' ? '/default-images/staff/no-course-img.png' : $course->thumbnail) }}"
                                                class="rounded" width="80" height="60" style="object-fit: cover;"
                                                loading="lazy" decoding="async">
                                        </a>
                                    </div>
                                    <div>
                                        <a href="{{ route('staff.courses.show', $course->id) }}"
                                            class="text-decoration-none text-dark">
                                            <h6 class="mb-0 fw-semibold">{{ $course->title }}</h6>
                                        </a>
                                        @if ($course->khmer_title)
                                            <div class="fs-2 text-muted mb-1">{{ $course->khmer_title }}</div>
                                        @endif
                                        <div class="d-flex align-items-center gap-2">
                                            <img src="{{ $course->instructor->image == 'no-img.jpg'
                                                ? asset('/default-images/user/both.jpg')
                                                : asset($course->instructor->image) }}"
                                                class="rounded-circle" width="24" height="24"
                                                style="object-fit: cover;">
                                            <span class="fs-2">{{ $course->instructor->name }}</span>
                                            @if ($course->telegram_group_link)
                                                <a href="{{ $course->telegram_group_link }}" target="_blank"
                                                    rel="noopener" class="text-info ms-1" title="Open Telegram group">
                                                    <i class="ti ti-brand-telegram fs-4"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                            {{-- Price --}}
                            <td class="ps-0">
                                <span class="badge bg-light-success text-dark">
                                    <strong>${{ number_format($course->price, 2) }}</strong>
                                </span>
                            </td>
                            {{-- Schedule --}}
                            <td class="ps-0">
                                @if ($course->schedule)
                                    @php
                                        $days = collect(explode('-', $course->schedule->study_day))
                                            ->map(fn($day) => ucfirst($day))
                                            ->implode(' • ');
                                        $start = \Carbon\Carbon::parse($course->schedule->start_time)->format('g:i A');
                                        $end = \Carbon\Carbon::parse($course->schedule->end_time)->format('g:i A');
                                    @endphp
                                    <div class="fw-semibold">{{ $days }}</div>
                                    <small class="text-muted">{{ $start }} - {{ $end }}</small>
                                @else
                                    <span class="text-muted">No schedule</span>
                                @endif
                            </td>
                            {{-- Duration --}}
                            <td class="ps-0">{{ $course->duration ?? '-' }}hr</td>
                            {{-- Capacity --}}
                            <td class="ps-0">
                                @if ($course->capacity)
                                    <span
                                        class="fw-semibold">{{ $course->students_count ?? 0 }}/{{ $course->capacity }}</span>
                                @else
                                    <span class="text-muted">Unlimited</span>
                                @endif
                            </td>
                            {{-- Status --}}
                            <td class="ps-0">
                                @if ($course->status == 'active')
                                    <span class="badge bg-success">OPEN</span>
                                @elseif($course->status == 'inactive')
                                    <span class="badge bg-danger">CLOSE</span>
                                @endif
                            </td>
                            {{-- Dates --}}
                            <td class="ps-0">
                                <div>{{ \Carbon\Carbon::parse($course->start_date)->format('d M Y') }}</div>
                                @if ($course->end_date)
                                    <small class="text-muted">to
                                        {{ \Carbon\Carbon::parse($course->end_date)->format('d M Y') }}</small>
                                @endif
                            </td>
                            {{-- Action --}}
                            <td class="text-end ps-0">
                                <div class="dropdown dropstart">
                                    <a href="#" class="text-muted" data-bs-toggle="dropdown">
                                        <i class="ti ti-dots-vertical fs-6"></i>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a class="dropdown-item d-flex align-items-center gap-3"
                                                href="{{ route('staff.courses.show', $course->id) }}">
                                                <i class="ti ti-eye fs-4"></i> View
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item d-flex align-items-center gap-3"
                                                href="{{ route('staff.courses.edit', [$course, 'redirect' => url()->full()]) }}">
                                                <i class="ti ti-edit fs-4"></i> Edit
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item d-flex align-items-center gap-3 text-danger btn-delete-course"
                                                href="javascript:void(0)" data-id="{{ $course->id }}"
                                                data-title="{{ $course->title }}">
                                                <i class="ti ti-trash fs-4"></i> Delete
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-5">No courses available.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{-- ============ GRID (CARD) VIEW ============ --}}
        <div class="row g-4" id="courseGridView">
            @forelse ($courses as $course)
                <div class="col-md-4 col-xxl-4 course-grid-item" data-status="{{ $course->status }}">
                    <div class="card overflow-hidden shadow-none border card-hover mb-4 mb-md-0 h-100">
                        {{-- Thumbnail with status badge + action dropdown overlay --}}
                        <div class="position-relative">
                            <a href="{{ route('staff.courses.show', $course->id) }}">
                                <img src="{{ asset($course->thumbnail == '' ? '/default-images/staff/no-course-img.png' : $course->thumbnail) }}"
                                    alt="{{ $course->title }}" class="w-100" style="height:160px;object-fit:cover;"
                                    loading="lazy" decoding="async">
                            </a>
                            <span
                                class="position-absolute top-0 start-0 m-2 badge {{ $course->status == 'active' ? 'bg-success' : 'bg-danger' }}">
                                {{ $course->status == 'active' ? 'OPEN' : 'CLOSE' }}
                            </span>
                            <div class="dropdown dropstart position-absolute top-0 end-0 m-2">
                                <a href="#" class="btn btn-sm btn-light rounded-circle shadow-sm"
                                    data-bs-toggle="dropdown">
                                    <i class="ti ti-dots-vertical fs-6"></i>
                                </a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item d-flex align-items-center gap-3"
                                            href="{{ route('staff.courses.show', $course->id) }}">
                                            <i class="ti ti-eye fs-4"></i> View
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item d-flex align-items-center gap-3"
                                            href="{{ route('staff.courses.edit', [$course, 'redirect' => url()->full()]) }}">
                                            <i class="ti ti-edit fs-4"></i> Edit
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item d-flex align-items-center gap-3 text-danger btn-delete-course"
                                            href="javascript:void(0)" data-id="{{ $course->id }}"
                                            data-title="{{ $course->title }}">
                                            <i class="ti ti-trash fs-4"></i> Delete
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            {{-- Title + instructor avatar --}}
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="pe-2">
                                    <a href="{{ route('staff.courses.show', $course->id) }}"
                                        class="text-decoration-none text-dark">
                                        <h6 class="mb-0 fs-5 fw-semibold text-truncate" style="max-width:170px;">
                                            {{ $course->title }}
                                        </h6>
                                    </a>
                                    <div class="d-flex align-items-center gap-2">
                                        @if ($course->khmer_title)
                                            <span class="fs-2 text-muted">{{ $course->khmer_title }}</span>
                                        @endif
                                        @if ($course->telegram_group_link)
                                            <a href="{{ $course->telegram_group_link }}" target="_blank" rel="noopener"
                                                class="text-info" title="Open Telegram group">
                                                <i class="ti ti-brand-telegram fs-4"></i>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                                <img src="{{ $course->instructor->image == 'no-img.jpg'
                                    ? asset('/default-images/user/both.jpg')
                                    : asset($course->instructor->image) }}"
                                    alt="instructor" width="35" height="35" class="rounded-circle"
                                    style="object-fit:cover;">
                            </div>
                            {{-- Price --}}
                            <div class="d-flex align-items-start justify-content-between mt-3">
                                <span>Price</span>
                                <div class="text-end">
                                    <h5 class="mb-0 fs-5 fw-semibold text-success">
                                        ${{ number_format($course->price, 2) }}
                                    </h5>
                                    <span class="fs-3 text-muted">{{ $course->duration ?? '-' }}hr</span>
                                </div>
                            </div>
                            {{-- Capacity --}}
                            @if ($course->capacity)
                                <div class="d-flex align-items-center justify-content-between mt-2">
                                    <span class="fs-3 text-muted">Enrollment</span>
                                    <span
                                        class="fs-3 fw-semibold">{{ $course->students_count ?? 0 }}/{{ $course->capacity }}</span>
                                </div>
                            @endif
                            {{-- Dates row --}}
                            <div class="d-flex align-items-start justify-content-between mt-3">
                                <span>
                                    <i class="ti ti-calendar-event me-1 fs-4"></i>
                                    {{ \Carbon\Carbon::parse($course->start_date)->format('d M Y') }}
                                </span>
                                @if ($course->end_date)
                                    <span>
                                        <i class="ti ti-calendar-x me-1 fs-4"></i>
                                        {{ \Carbon\Carbon::parse($course->end_date)->format('d M Y') }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center text-muted py-5">No courses available.</div>
            @endforelse
        </div>
        {{-- ============ SHARED FOOTER: results count + Show All/Paginate + pagination links ============ --}}
        <div class="d-flex flex-wrap justify-content-end align-items-center mt-4 pt-3 border-top gap-3">
            @if (!($showingAll ?? false) && method_exists($courses, 'hasPages'))
                {{ $courses->appends(request()->except('page'))->links('frontend.staff.pages.pagination.custom') }}
            @elseif($showingAll ?? false)
                <span class="text-muted small">Showing all {{ $courses->count() }} results</span>
            @endif
            @if (!($showingAll ?? false))
                <a href="{{ request()->fullUrlWithQuery(['per_page' => 'all', 'page' => null]) }}"
                    class="btn btn-outline-info btn-sm">
                    <i class="ti ti-list-details me-1"></i> Show All
                </a>
            @else
                <a href="{{ request()->fullUrlWithQuery(['per_page' => 10, 'page' => null]) }}"
                    class="btn btn-outline-secondary btn-sm">
                    <i class="ti ti-layout-list me-1"></i> Paginate
                </a>
            @endif
        </div>
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
                            Do you really want to delete the course "<span id="delete-course-title"
                                class="fw-semibold"></span>"? <br>
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
    @include('frontend.staff.pages.course-management.partials.curriculum-modal')
@endsection
@push('scripts')
    {{-- ... your existing delete/view-toggle script stays here ... --}}
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
                gridView.style.display = 'flex';
                btnGridView.classList.add('active');
                btnListView.classList.remove('active');
            }
            localStorage.setItem('staffCourseView', view);
        }
        btnListView.addEventListener('click', () => applyView('list'));
        btnGridView.addEventListener('click', () => applyView('grid'));
        applyView(localStorage.getItem('staffCourseView') || 'grid');
    </script>
@endpush
