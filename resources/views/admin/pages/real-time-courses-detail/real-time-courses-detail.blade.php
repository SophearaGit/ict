@extends('admin.layouts.master')
@section('page_title', isset($page_title) ? $page_title : 'Page Title Here')
@php
    $status = $course->studentReports->first()?->approval_status;
@endphp
@push('styles')
    {{-- The old "Student's Attendance" tab (daily mark/view grid) was removed —
         admin has no use for it: the Student Attendance tab (internally
         still the "session log" — id="session-log", route
         admin.student-attendance.session-log) already gives the full
         per-session history, and Student Report gives the summary.
         All of that tab's markup, its `admin.student-attendance.get` AJAX
         call, and its supporting CSS/JS (loadAttendance, setStatus,
         updateSummary, setDate, etc.) were removed together with it. --}}
    @include('frontend.instructor.pages.course-real-time.styling.style')
@endpush
@section('content')

    <!-- Page header -->
    <section class="pt-lg-8 pb-8
        d-flex align-items-center"
        style="background-image: url({{ asset('/admin/assets/dist/images/banner/banner_space.jpg') }}); background-size: cover; background-position: center;">
        <div class="container pb-lg-8">
            <div class="row align-items-center">
                <div class="col-xl-7 col-lg-7 col-md-12">
                    <div>
                        {{-- button going back --}}
                        <a href="{{ route('admin.courses.realtime.index') }}" class="btn btn-sm btn-light mb-4">
                            <i class="fe fe-arrow-left me-2"></i>
                            Back to courses
                        </a>
                        <h1 class="text-white display-4 fw-semibold text-capitalize mb-3">
                            {{ $course->title }}
                        </h1>
                        <p class="text-white mb-3 lead">
                            @if ($course->schedule)
                                @php
                                    $days = collect(explode('-', $course->schedule->study_day))
                                        ->map(fn($day) => ucfirst($day))
                                        ->implode(' • ');
                                    $start = \Carbon\Carbon::parse($course->schedule->start_time)->format('g:i ');
                                    $end = \Carbon\Carbon::parse($course->schedule->end_time)->format('g:i A');
                                    $shift = ucfirst($course->schedule->shift);
                                @endphp
                                {{ $days }} | {{ $shift }} (
                                {{ $start }}
                                –
                                {{ $end }} )
                            @else
                                <span class="text-muted">No schedule</span>
                            @endif
                        </p>
                        <div class="d-flex align-items-center">
                            <span class="text-white ms-0">
                                <i class="fe fe-user"></i>
                                {{ $course->enrollments->count() ?? 0 }} Enrolled
                            </span>
                            <div>
                                <span class="fs-6 ms-4 align-text-top">
                                    {{-- loop 5 time --}}
                                    @for ($i = 0; $i < 5; $i++)
                                        <i class="bi bi-star-fill text-warning"></i>
                                    @endfor
                                </span>
                                <span class="text-white">
                                    (5.0)
                                </span>
                            </div>
                            <span class="text-white ms-4 d-none d-md-block">
                                <i class="bi bi-bar-chart-fill"></i>
                                <span class="align-middle">Beginner</span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Page content -->
    <section class="pb-8">
        <div class="container">
            <div class="row">
                <div class="col-lg-9 col-md-12 col-12 mt-n8 mb-4 mb-lg-0">

                    <!-- Card -->
                    <div class="card rounded-3">

                        <!-- Card header -->
                        <div class="card-header border-bottom-0 p-0">
                            <div>

                                <!-- Nav -->
                                <ul class="nav nav-lb-tab" id="tab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" id="students-tab" data-bs-toggle="pill" href="#students"
                                            role="tab" aria-controls="students" aria-selected="false"
                                            tabindex="-1">Students</a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" id="session-log-tab" data-bs-toggle="pill" href="#session-log"
                                            role="tab" aria-controls="session-log" aria-selected="false" tabindex="-1">
                                            Student Attendance
                                        </a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" id="report-tab" data-bs-toggle="pill" href="#report"
                                            role="tab" aria-controls="report" aria-selected="false"
                                            tabindex="-1">Student Report</a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link " id="attendance-tab" data-bs-toggle="pill" href="#attendance"
                                            role="tab" aria-controls="attendance" aria-selected="false"
                                            tabindex="-1">Teacher Attendance</a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- Card Body -->
                        <div class="card-body">
                            <div class="tab-content" id="tabContent">
                                @include('frontend.instructor.pages.course-real-time.partials.my-attendance-tab')
                                @include('frontend.instructor.pages.course-real-time.partials.students-tab')
                                {{-- Student Attendance Tab (id/route names still say "session-log" internally) --}}
                                <div class="tab-pane fade" id="session-log" role="tabpanel">
                                    <div class="d-flex justify-content-end mb-3" id="sessionLogFilterBar"
                                         style="display:none;">
                                        <div class="d-flex align-items-center gap-2 rounded-pill"
                                             style="background:#f8f9fa; border:1px solid #e9ecef; padding:6px 8px 6px 14px;">
                                            <i class="fe fe-user text-muted" style="font-size:13px;"></i>
                                            <select id="sessionLogStudentFilter"
                                                    class="form-select form-select-sm border-0 bg-transparent shadow-none py-0"
                                                    style="min-width:170px; font-size:13px; box-shadow:none;">
                                                <option value="">All students</option>
                                            </select>
                                            <button type="button" id="sessionLogFilterClear"
                                                    class="btn-close"
                                                    style="display:none; font-size:9px; flex-shrink:0;"
                                                    title="Clear filter" aria-label="Clear filter"></button>
                                        </div>
                                    </div>
                                    <div id="sessionLogContainer">
                                        <div class="d-flex justify-content-center py-5">
                                            <div class="spinner-border spinner-border-sm text-muted" role="status"></div>
                                        </div>
                                    </div>
                                </div>
                                {{-- student report empty content --}}
                                <div class="tab-pane fade" id="report" role="tabpanel">
                                    {{-- Report Header --}}
                                    <div class="card-body border-bottom">
                                        <h5 class="text-uppercase text-center fw-bold mb-4">Student Report</h5>
                                        <div class="row small">
                                            <div class="col-md-6">
                                                <p class="mb-1">
                                                    <span class="text-muted">Instructor:</span>
                                                    <span
                                                        class="fw-semibold text-capitalize ms-1">{{ $course->instructor->name }}</span>
                                                </p>
                                                <p class="mb-0">
                                                    <span class="text-muted">Course:</span>
                                                    <span class="fw-semibold ms-1">{{ $course->title }}</span>
                                                </p>
                                            </div>
                                            <div class="col-md-6 text-md-end mt-3 mt-md-0">
                                                <p class="mb-1">
                                                    <span class="text-muted">Room:</span>
                                                    <span class="fw-semibold ms-1">{{ $course->room ?? 'A' }}</span>
                                                </p>
                                                <p class="mb-0">
                                                    <span class="text-muted">Schedule:</span>
                                                    @if ($course->schedule)
                                                        @php
                                                            $days = collect(explode('-', $course->schedule->study_day))
                                                                ->map(fn($day) => ucfirst($day))
                                                                ->implode(' • ');
                                                            $start = \Carbon\Carbon::parse(
                                                                $course->schedule->start_time,
                                                            )->format('g:i');
                                                            $end = \Carbon\Carbon::parse(
                                                                $course->schedule->end_time,
                                                            )->format('g:i A');
                                                            $shift = ucfirst($course->schedule->shift);
                                                        @endphp
                                                        <span class="fw-semibold ms-1">{{ $days }} |
                                                            {{ $shift }} ({{ $start }} –
                                                            {{ $end }})</span>
                                                    @else
                                                        <span class="text-muted ms-1">No schedule</span>
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- Report Table --}}
                                    <div class="table-responsive">
                                        <table
                                            class="table table-bordered table-hover table-centered mb-0 text-nowrap align-middle">
                                            <thead class="table-light">
                                                <tr>
                                                    <th rowspan="2" class="text-center">No</th>
                                                    <th rowspan="2">Name</th>
                                                    <th colspan="2" class="text-center">Attendance ( 10% )</th>
                                                    <th rowspan="2" class="text-center">Assignment ( 30% )</th>
                                                    <th rowspan="2" class="text-center">Mini Project ( 20% )</th>
                                                    <th rowspan="2" class="text-center">Final Project ( 40% )</th>
                                                    <th rowspan="2" class="text-center">Total</th>
                                                    <th rowspan="2" class="text-center">Result</th>
                                                </tr>
                                                <tr>
                                                    <th class="text-center">P</th>
                                                    <th class="text-center">A</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($course->studentReports as $i => $report)
                                                    <tr id="report-row-{{ $report->student_id }}">
                                                        <td class="text-center">{{ $i + 1 }}</td>
                                                        <td class="text-capitalize">{{ $report->student->name }}</td>
                                                        {{-- Attendance (read-only, auto-calculated from attendance tab) --}}
                                                        <td class="text-center present">{{ $report->present }}</td>
                                                        <td class="text-center absent">{{ $report->absent }}</td>
                                                        {{-- Scores — view-only for admin; editing lives on the
                                                             instructor's own copy of this page --}}
                                                        <td class="text-center">{{ $report->assignment_score }}</td>
                                                        <td class="text-center">{{ $report->mini_project_score }}</td>
                                                        <td class="text-center">{{ $report->final_project_score }}</td>
                                                        <td class="text-center fw-bold total-score">
                                                            {{ $report->total_score }}
                                                        </td>
                                                        <td class="text-center">
                                                            <span
                                                                class="badge bg-{{ $report->result === 'pass' ? 'success' : 'danger' }}">
                                                                {{ ucfirst($report->result) }}
                                                            </span>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    {{-- Report Footer --}}
                                    <div class="card-body border-top">
                                        <div class="row mt-4 text-center small">
                                            <div class="col-md-6">
                                                <p class="text-muted mb-0">Seen and approved by</p>
                                                <div class="mt-5 pt-3 mx-auto position-relative" style="width: 160px;">
                                                    @if ($status === 'approved')
                                                        <div
                                                            class="position-relative d-flex align-items-center justify-content-center mb-3">
                                                            <hr class="w-100 m-0">
                                                            <a href="#" class="position-absolute"
                                                                data-bs-toggle="tooltip" data-placement="top"
                                                                aria-label="Verified" data-bs-original-title="Verified">
                                                                <img src="/frontend/assets/images/svg/checked-mark.svg"
                                                                    alt="checked" height="40" width="40">
                                                            </a>
                                                        </div>
                                                    @else
                                                        <hr>
                                                    @endif
                                                    <p class="fw-semibold mb-0">ICT Training Center</p>
                                                </div>
                                            </div>
                                            <div class="col-md-6 mt-5 mt-md-0">
                                                <p class="text-muted mb-0">Prepared by</p>
                                                <div class="mt-5 pt-3 mx-auto position-relative" style="width: 160px;">
                                                    @if ($status === 'approved')
                                                        <div
                                                            class="position-relative d-flex align-items-center justify-content-center mb-3">
                                                            <hr class="w-100 m-0">
                                                            <a href="#" class="position-absolute"
                                                                data-bs-toggle="tooltip" data-placement="top"
                                                                aria-label="Verified" data-bs-original-title="Verified">
                                                                <img src="/frontend/assets/images/svg/checked-mark.svg"
                                                                    alt="checked" height="40" width="40">
                                                            </a>
                                                        </div>
                                                    @else
                                                        <hr>
                                                    @endif
                                                    <p class="fw-semibold mb-0 text-capitalize">Teacher:
                                                        {{ $course->instructor->name }}
                                                    </p>
                                                </div>
                                                {{-- Admin can only approve/reject once the teacher has
                                                     requested approval — requesting/cancelling that request
                                                     is the instructor's own action, done on their copy of
                                                     this page. --}}
                                                <div class="mt-4">
                                                    @if ($status === 'pending')
                                                        <div class="d-flex gap-2 justify-content-md-end">
                                                            <form action="{{ route('admin.student-report.approve', $course->id) }}"
                                                                method="POST"
                                                                onsubmit="return confirm('Approve this student report?')">
                                                                @csrf
                                                                <button type="submit" class="btn btn-success btn-sm">
                                                                    <i class="fe fe-check me-1"></i> Approve
                                                                </button>
                                                            </form>
                                                            <form action="{{ route('admin.student-report.reject', $course->id) }}"
                                                                method="POST"
                                                                onsubmit="return confirm('Reject this student report? It will be sent back to the teacher as a draft.')">
                                                                @csrf
                                                                @method('PATCH')
                                                                <button type="submit" class="btn btn-danger btn-sm">
                                                                    <i class="fe fe-x me-1"></i> Reject
                                                                </button>
                                                            </form>
                                                        </div>
                                                    @elseif ($status === 'draft' || !$status)
                                                        <span class="text-muted small">
                                                            <i class="fe fe-clock me-1"></i>
                                                            Waiting for the teacher to submit this report for approval.
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-12 col-12 mt-lg-n8">

                    <!-- Card -->
                    <div class="card  mb-4">
                        <div class="p-1">
                            <div class="d-flex justify-content-center align-items-center rounded border-white border rounded-3 bg-cover"
                                style="background-image: url({{ asset($course->thumbnail == '' ? asset('\default-images\staff\no-course-img.png') : asset($course->thumbnail)) }}); height: 210px">
                            </div>
                        </div>
                    </div>

                    <!-- Card -->
                    <div class="card mb-4 shadow-sm border-0 rounded-4">
                        <div class="card-body">

                            <!-- Enrolled -->
                            <div class="d-flex justify-content-between align-items-center mb-3 p-3 rounded-3 bg-light">
                                <div>
                                    <h6 class="mb-0 text-muted">Students</h6>
                                    <h5 class="mb-0 fw-bold">{{ $course->enrollments->count() ?? 0 }}</h5>
                                </div>
                                <div class="text-primary fs-3">
                                    <i class="fe fe-users"></i>
                                </div>
                            </div>

                            <!-- Start Date -->
                            <div class="d-flex justify-content-between align-items-center mb-3 p-3 rounded-3 bg-light">
                                <div>
                                    <h6 class="mb-0 text-muted">Start Date</h6>
                                    <h6 class="mb-0 fw-semibold">
                                        {{ $course->start_date ? $course->start_date->format('d M, Y') : 'N/A' }}
                                    </h6>
                                </div>
                                <div class="text-info fs-3">
                                    <i class="fe fe-calendar"></i>
                                </div>
                            </div>

                            <!-- Duration -->
                            <div class="d-flex justify-content-between align-items-center mb-3 p-3 rounded-3 bg-light">
                                <div>
                                    <h6 class="mb-0 text-muted">Duration</h6>
                                    <h6 class="mb-0 fw-semibold">{{ $course->duration ?? 0 }} hrs</h6>
                                </div>
                                <div class="text-warning fs-3">
                                    <i class="fe fe-clock"></i>
                                </div>
                            </div>

                            <!-- Sessions -->
                            <div class="d-flex justify-content-between align-items-center mb-3 p-3 rounded-3 bg-light">
                                <div>
                                    <h6 class="mb-0 text-muted">Total Sessions</h6>
                                    <h6 class="mb-0 fw-semibold">{{ $course->total_sessions }}</h6>
                                </div>
                                <div class="text-purple fs-3">
                                    <i class="fe fe-layers"></i>
                                </div>
                            </div>

                            <!-- Progress -->
                            @php
                                $progress =
                                    $course->total_sessions > 0
                                        ? ($course->completed_sessions / $course->total_sessions) * 100
                                        : 0;
                                // Determine color
                                if ($progress <= 50) {
                                    $progressColor = 'bg-danger';
                                } elseif ($progress <= 80) {
                                    $progressColor = 'bg-warning';
                                } else {
                                    $progressColor = 'bg-success';
                            } @endphp <div class="mt-4">
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="fw-semibold">Progress</span>
                                    <span class="fw-bold">
                                        {{ round($progress) }}%
                                    </span>
                                </div>
                                <div class="progress" style="height: 12px;">
                                    <div class="progress-bar {{ $progressColor }}" role="progressbar"
                                        style="width: {{ $progress }}%; transition: all 0.6s ease;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if ($other_courses->count() > 0)

                <!-- Card -->
                <div class="pt-8 pb-3">
                    <div class="row d-md-flex align-items-center mb-4">
                        <div class="col-12">
                            <h2 class="mb-0 text-capitalize">
                                Other Courses by {{ $course->instructor->name ?? 'This Instructor' }}
                            </h2>
                        </div>
                    </div>
                    <div class="row">
                        {{-- IMPORTANT: this loop must NOT reuse the `$course` variable name.
                             PHP's foreach/forelse leaks its loop variable into the surrounding
                             scope after the loop ends, so anything rendered further down the
                             page (e.g. the Student Attendance tab's AJAX `course_id: "{{ $course->id }}"`
                             a few hundred lines below) would silently pick up the LAST "other
                             course" iterated here instead of the actual course being viewed. --}}
                        @forelse ($other_courses as $otherCourse)
                            <div class="col-lg-3 col-md-6 col-12">

                                <!-- Card -->
                                <div class="card mb-4 card-hover">
                                    <a href="{{ route('admin.courses.realtime.show', $otherCourse->id) }}">
                                        <img src="{{ asset($otherCourse->thumbnail == '' ? asset('\default-images\staff\no-course-img.png') : asset($otherCourse->thumbnail)) }}"
                                            alt="course" class="card-img-top"
                                            style="height: 160px; object-fit: cover;">
                                    </a>

                                    <!-- Card body -->
                                    <div class="card-body">
                                        <h4 class="mb-2 text-truncate-line-2">
                                            <a href="{{ route('admin.courses.realtime.show', $otherCourse->id) }}"
                                                class="text-inherit text-capitalize">
                                                {{ $otherCourse->title }}
                                            </a>
                                        </h4>
                                        <ul class="mb-3 list-inline">
                                            <li class="list-inline-item">
                                                <span>
                                                    <i class="bi bi-clock"></i>
                                                </span>
                                                <span>
                                                    {{ $otherCourse->duration ?? 'N/A' }}h
                                                </span>
                                            </li>
                                            <li class="list-inline-item">
                                                <span>
                                                    <i class="bi bi-people"></i>
                                                </span>
                                                <span>
                                                    {{ $otherCourse->enrollments->count() ?? 0 }} Enrolled
                                                </span>
                                            </li>
                                        </ul>
                                        <div class="mt-3 d-flex align-baseline lh-1">
                                            <span class="fs-6">
                                                {{ $otherCourse->start_date ? $otherCourse->start_date->format('d M, Y') : 'N/A' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="card mb-4">
                                    <div class="card-body text-center">
                                        <h4 class="mb-0">You have no other courses.</h4>
                                    </div>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            @endif
        </div>
    </section>

    {{-- The "Send for Approval" / "Cancel Request" modals used to live
         here, but those are instructor-only actions (requesting or
         withdrawing approval) — admin's role on this tab is limited to
         Approve/Reject once a request comes in, handled by plain forms
         above rather than a modal. --}}

    @include('admin.pages.invoices.partials.quick-view-modal')
@endsection
@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const tabButtons = document.querySelectorAll('#tab a[data-bs-toggle="pill"]');
            // 🔥 Default tab = Teacher Attendance
            const defaultTab = '#attendance';
            // 🔁 Restore from localStorage OR use default
            let activeTab = localStorage.getItem('instructorActiveTab') || defaultTab;
            let triggerEl = document.querySelector(`#tab a[href="${activeTab}"]`);
            if (triggerEl) {
                let tab = new bootstrap.Tab(triggerEl);
                tab.show();
            }
            // 💾 Save selected tab
            tabButtons.forEach(button => {
                button.addEventListener('shown.bs.tab', function(event) {
                    let target = event.target.getAttribute('href');
                    localStorage.setItem('instructorActiveTab', target);
                });
            });
        });
        document.addEventListener("DOMContentLoaded", function() {
            const studentTabButtons = document.querySelectorAll('#students [data-bs-toggle="tab"]');
            let savedView = localStorage.getItem('studentInnerTab');

            function activateTab(targetSelector) {
                const trigger = document.querySelector(`#students [data-bs-target="${targetSelector}"]`);
                if (trigger) {
                    // Activate using Bootstrap
                    new bootstrap.Tab(trigger).show();
                    // 🔥 Force correct button state (important fix)
                    studentTabButtons.forEach(btn => btn.classList.remove('active'));
                    trigger.classList.add('active');
                }
            }
            if (savedView) {
                activateTab(savedView);
            } else {
                activateTab('#tabPaneListStudent'); // default
            }
            // Save on change
            studentTabButtons.forEach(btn => {
                btn.addEventListener('shown.bs.tab', function(e) {
                    localStorage.setItem('studentInnerTab', e.target.getAttribute(
                        'data-bs-target'));
                });
            });
        });
        /* =========================
           SESSION LOG TAB
        ========================= */
        (function() {
            let loaded = false;
            // ✅ Cached from the last fetch so the student filter can
            // re-render instantly without another AJAX round-trip
            let lastSessions = [];
            let lastStudents = [];
            let selectedStudentId = '';
            // ✅ Exposed in case other tabs ever need to force a refresh
            window.reloadSessionLog = function() {
                fetchAndRender();
            };

            function loadSessionLog() {
                if (loaded) return;
                loaded = true;
                fetchAndRender();
            }

            function fetchAndRender() {
                $.ajax({
                    url: "{{ route('admin.student-attendance.session-log') }}",
                    type: 'GET',
                    cache: false, // the URL is otherwise identical every call — never serve a stale cached response
                    data: {
                        course_id: "{{ $course->id }}"
                    },
                    success: function(res) {
                        if (!res.success) {
                            $('#sessionLogContainer').html(
                                '<div class="text-center text-muted py-5">No data returned.</div>'
                            );
                            return;
                        }
                        lastSessions = res.sessions || [];
                        lastStudents = res.students || [];
                        renderStudentFilter(lastStudents);
                        renderSessionLog(lastSessions, lastStudents);
                    },
                    error: function() {
                        $('#sessionLogContainer').html(
                            '<div class="text-center text-muted py-5">Failed to load student attendance.</div>'
                        );
                    }
                });
            }
            // ✅ Delegated — catches pill tab reliably regardless of init order
            $(document).on('shown.bs.tab', 'a[href="#session-log"]', function() {
                loadSessionLog();
            });
            // ✅ Handle localStorage tab restore
            $(document).ready(function() {
                if ($('#session-log').hasClass('active show')) {
                    loadSessionLog();
                }
            });

            function renderStudentFilter(students) {
                if (!students || !students.length) {
                    $('#sessionLogFilterBar').hide();
                    return;
                }
                let options = students.map(s =>
                    `<option value="${s.id}" ${String(s.id) === String(selectedStudentId) ? 'selected' : ''}>${s.name}</option>`
                ).join('');
                $('#sessionLogStudentFilter').html(`<option value="">All students</option>${options}`);
                $('#sessionLogFilterClear').toggle(!!selectedStudentId);
                $('#sessionLogFilterBar').show();
            }

            // ✅ One handler for the life of the page — reads whatever was
            // last fetched, no extra request needed to switch students
            $(document).off('change', '#sessionLogStudentFilter').on('change', '#sessionLogStudentFilter',
                function() {
                    selectedStudentId = $(this).val() || '';
                    $('#sessionLogFilterClear').toggle(!!selectedStudentId);
                    renderSessionLog(lastSessions, lastStudents);
                });
            $(document).off('click', '#sessionLogFilterClear').on('click', '#sessionLogFilterClear', function() {
                selectedStudentId = '';
                $('#sessionLogStudentFilter').val('');
                $(this).hide();
                renderSessionLog(lastSessions, lastStudents);
            });

            function renderSessionLog(sessions, students) {
                if (!sessions || !sessions.length) {
                    $('#sessionLogContainer').html(
                        `<div class="text-center py-5">
                    <i class="fe fe-calendar fs-1 text-muted d-block mb-3"></i>
                    <p class="text-muted mb-0">No attendance recorded yet.</p>
                </div>`
                    );
                    return;
                }
                if (selectedStudentId) {
                    renderStudentSessionLog(sessions, students, selectedStudentId);
                    return;
                }
                let totalPresent = 0;
                let totalAbsent = 0;
                let totalPermission = 0;
                let totalSessions = sessions.length;
                sessions.forEach(s => {
                    totalPresent += parseInt(s.present_count || 0);
                    totalAbsent += parseInt(s.absent_count || 0);
                    totalPermission += parseInt(s.permission_count || 0);
                });
                // ✅ Remember which sessions were open so we can restore after re-render
                let openIndexes = new Set();
                $('#sessionLogContainer .session-row').each(function() {
                    let idx = $(this).data('index');
                    if ($(`.session-detail-${idx}`).is(':visible')) {
                        openIndexes.add(idx);
                    }
                });
                let html = `
            <div class="row g-3 mb-4">
                <div class="col">
                    <div class="p-3 rounded-3 bg-light text-center">
                        <div class="text-muted small mb-1">Total Sessions</div>
                        <div class="fw-bold fs-5">${totalSessions}</div>
                    </div>
                </div>
                <div class="col">
                    <div class="p-3 rounded-3 text-center" style="background:#d1e7dd;">
                        <div class="small mb-1" style="color:#0a3622;">Total Present</div>
                        <div class="fw-bold fs-5" style="color:#0a3622;">${totalPresent}</div>
                    </div>
                </div>
                <div class="col">
                    <div class="p-3 rounded-3 text-center" style="background:#f8d7da;">
                        <div class="small mb-1" style="color:#58151c;">Total Absent</div>
                        <div class="fw-bold fs-5" style="color:#58151c;">${totalAbsent}</div>
                    </div>
                </div>
                <div class="col">
                    <div class="p-3 rounded-3 text-center" style="background:#fff3cd;">
                        <div class="small mb-1" style="color:#664d03;">Total Permission</div>
                        <div class="fw-bold fs-5" style="color:#664d03;">${totalPermission}</div>
                    </div>
                </div>
            </div>
            <div class="mb-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
                <span class="text-muted small">
                    <i class="fe fe-list me-1"></i>${totalSessions} session${totalSessions !== 1 ? 's' : ''} recorded
                </span>
                <div class="d-flex gap-3 small text-muted">
                    <span><span class="badge bg-success me-1">&nbsp;</span>Present</span>
                    <span><span class="badge bg-danger me-1">&nbsp;</span>Absent</span>
                    <span><span class="badge bg-warning me-1">&nbsp;</span>Permission</span>
                    <span><span class="badge bg-light border me-1">&nbsp;</span>Unmarked</span>
                </div>
            </div>`;
                sessions.forEach((session, index) => {
                    let totalStudents = parseInt(session.total_students || 0);
                    let presentCount = parseInt(session.present_count || 0);
                    let absentCount = parseInt(session.absent_count || 0);
                    let permissionCount = parseInt(session.permission_count || 0);
                    let unmarkedCount = parseInt(session.unmarked_count || 0);
                    let attendanceRate = totalStudents > 0 ?
                        Math.round((presentCount / totalStudents) * 100) :
                        0;
                    let rateColor = attendanceRate >= 80 ? 'text-success' :
                        attendanceRate >= 50 ? 'text-warning' :
                        'text-danger';
                    let records = session.records || {};
                    let dots = students.map(student => {
                        let record = records[student.id];
                        let status = record ? record.status : null;
                        let color = status === 'present' ? '#198754' :
                            status === 'absent' ? '#dc3545' :
                            status === 'permission' ? '#ffc107' :
                            '#dee2e6';
                        return `<span title="${student.name}: ${status ?? 'unmarked'}" style="
                    display:inline-block; width:10px; height:10px;
                    border-radius:50%; background:${color}; margin:1px;"></span>`;
                    }).join('');
                    let detailRows = students.map(student => {
                        let record = records[student.id];
                        let status = record ? record.status : null;
                        let note = record ? (record.note ?? '') : '';
                        let badgeClass = status === 'present' ? 'bg-success' :
                            status === 'absent' ? 'bg-danger' :
                            status === 'permission' ? 'bg-warning text-dark' :
                            'bg-light text-dark border';
                        let initials = student.name.substring(0, 2).toUpperCase();
                        return `
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar" style="width:28px;height:28px;font-size:10px;flex-shrink:0;">
                                    ${initials}
                                </div>
                                <span class="text-capitalize small">${student.name}</span>
                            </div>
                        </td>
                        <td class="text-center">
                            <span class="badge ${badgeClass}" style="font-size:11px;">
                                ${status ? status.charAt(0).toUpperCase() + status.slice(1) : '—'}
                            </span>
                        </td>
                        <td class="text-muted small">${note || '—'}</td>
                    </tr>`;
                    }).join('');
                    // ✅ Restore open state after re-render
                    let isOpen = openIndexes.has(index);
                    let detailDisplay = isOpen ? 'block' : 'none';
                    let chevronRotate = isOpen ? 'rotate(180deg)' : 'rotate(0deg)';
                    html += `
                <div class="card mb-2 border rounded-3 overflow-hidden">
                    <div class="p-3 d-flex align-items-center gap-3 session-row"
                         data-index="${index}" style="cursor:pointer; user-select:none;">
                        <div class="text-center flex-shrink-0" style="width:46px;">
                            <div class="fw-bold" style="font-size:18px;line-height:1;">${session.day}</div>
                            <div class="text-muted" style="font-size:11px;text-transform:uppercase;">${session.month}</div>
                            <div class="text-muted" style="font-size:11px;">${session.year}</div>
                        </div>
                        <div style="width:1px;height:40px;background:#dee2e6;flex-shrink:0;"></div>
                        <div class="flex-grow-1 min-w-0">
                            <div class="mb-1 d-flex align-items-center gap-2">
                                <span class="small fw-semibold ${rateColor}">${attendanceRate}%</span>
                                <span class="text-muted small">${presentCount}P / ${absentCount}A / ${permissionCount}Perm
                                    ${unmarkedCount > 0 ? `/ ${unmarkedCount} unmarked` : ''}
                                </span>
                            </div>
                            <div style="line-height:1;">${dots}</div>
                        </div>
                        <div class="text-muted flex-shrink-0 session-chevron" data-index="${index}"
                             style="transform:${chevronRotate}; transition:transform 0.2s;">
                            <i class="fe fe-chevron-down"></i>
                        </div>
                    </div>
                    <div class="session-detail-${index}"
                         style="display:${detailDisplay}; border-top:1px solid #dee2e6;">
                        <div class="table-responsive">
                            <table class="table table-sm align-middle mb-0" style="font-size:13px;">
                                <thead class="table-light">
                                    <tr>
                                        <th>Student</th>
                                        <th class="text-center">Status</th>
                                        <th>Note</th>
                                    </tr>
                                </thead>
                                <tbody>${detailRows}</tbody>
                            </table>
                        </div>
                    </div>
                </div>`;
                });
                $('#sessionLogContainer').html(html);
                $('#sessionLogContainer').off('click', '.session-row').on('click', '.session-row', function() {
                    let idx = $(this).data('index');
                    let detail = $(`.session-detail-${idx}`);
                    let chevron = $(`[data-index="${idx}"].session-chevron`);
                    let isOpen = detail.is(':visible');
                    detail.slideToggle(150);
                    chevron.css('transform', isOpen ? 'rotate(0deg)' : 'rotate(180deg)');
                });
            }

            // ✅ Single-student view: one row per session (no expand/collapse
            // needed since there's only one student's status to show)
            function renderStudentSessionLog(sessions, students, studentId) {
                let student = students.find(s => String(s.id) === String(studentId));
                let studentName = student ? student.name : 'Student';
                let present = 0;
                let absent = 0;
                let permission = 0;
                let rows = sessions.map(session => {
                    let record = (session.records || {})[studentId];
                    let status = record ? record.status : null;
                    let note = record ? (record.note ?? '') : '';
                    if (status === 'present') present++;
                    else if (status === 'absent') absent++;
                    else if (status === 'permission') permission++;
                    let badgeClass = status === 'present' ? 'bg-success' :
                        status === 'absent' ? 'bg-danger' :
                        status === 'permission' ? 'bg-warning text-dark' :
                        'bg-light text-dark border';
                    return `
                <tr>
                    <td>
                        <span class="fw-semibold">${session.day}</span>
                        <span class="text-muted small text-uppercase ms-1">${session.month} ${session.year}</span>
                    </td>
                    <td class="text-center">
                        <span class="badge ${badgeClass}" style="font-size:11px;">
                            ${status ? status.charAt(0).toUpperCase() + status.slice(1) : 'Unmarked'}
                        </span>
                    </td>
                    <td class="text-muted small">${note || '—'}</td>
                </tr>`;
                }).join('');
                let totalSessions = sessions.length;
                let rate = totalSessions > 0 ? Math.round((present / totalSessions) * 100) : 0;
                let rateColor = rate >= 80 ? 'text-success' : rate >= 50 ? 'text-warning' : 'text-danger';
                let initials = studentName.substring(0, 2).toUpperCase();
                let html = `
            <div class="d-flex align-items-center gap-2 mb-3">
                <div class="avatar" style="width:32px;height:32px;font-size:11px;flex-shrink:0;">${initials}</div>
                <div>
                    <div class="fw-semibold text-capitalize">${studentName}</div>
                    <div class="small ${rateColor}">${rate}% attendance</div>
                </div>
            </div>
            <div class="row g-3 mb-4">
                <div class="col">
                    <div class="p-3 rounded-3 bg-light text-center">
                        <div class="text-muted small mb-1">Total Sessions</div>
                        <div class="fw-bold fs-5">${totalSessions}</div>
                    </div>
                </div>
                <div class="col">
                    <div class="p-3 rounded-3 text-center" style="background:#d1e7dd;">
                        <div class="small mb-1" style="color:#0a3622;">Present</div>
                        <div class="fw-bold fs-5" style="color:#0a3622;">${present}</div>
                    </div>
                </div>
                <div class="col">
                    <div class="p-3 rounded-3 text-center" style="background:#f8d7da;">
                        <div class="small mb-1" style="color:#58151c;">Absent</div>
                        <div class="fw-bold fs-5" style="color:#58151c;">${absent}</div>
                    </div>
                </div>
                <div class="col">
                    <div class="p-3 rounded-3 text-center" style="background:#fff3cd;">
                        <div class="small mb-1" style="color:#664d03;">Permission</div>
                        <div class="fw-bold fs-5" style="color:#664d03;">${permission}</div>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-sm align-middle" style="font-size:13px;">
                    <thead class="table-light">
                        <tr>
                            <th>Date</th>
                            <th class="text-center">Status</th>
                            <th>Note</th>
                        </tr>
                    </thead>
                    <tbody>${rows}</tbody>
                </table>
            </div>`;
                $('#sessionLogContainer').html(html);
            }
        })();
    </script>
@endpush
