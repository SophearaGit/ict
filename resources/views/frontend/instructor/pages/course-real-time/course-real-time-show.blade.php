@extends('frontend.layouts.master')
@section('page_title', isset($page_title) ? $page_title : 'Page Title Here')
@push('styles')
    @include('frontend.instructor.pages.course-real-time.styling.style')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            let saveTimeout = null;
            let originalTableHTML = '';

            /* =========================
               RESET UI
            ========================= */
            function resetAttendanceUI() {
                $('#attendanceTable .student-row').each(function() {

                    let row = $(this);

                    row.find('.status-toggle span')
                        .removeClass('bg-success bg-danger bg-warning active')
                        .addClass('bg-light text-dark');

                    row.find('.status-toggle').attr('data-status', '');

                    row.find('input').val('');
                });

                updateSummary();
            }

            /* =========================
               LOADING (SKELETON)
            ========================= */
            function showLoading() {

                if (!originalTableHTML) {
                    originalTableHTML = $('#attendanceTable').html();
                }

                let rows = '';

                for (let i = 0; i < 5; i++) {
                    rows += `
                <tr class="loading-row">
                    <td><div class="skeleton"></div></td>
                    <td><div class="skeleton"></div></td>
                    <td><div class="skeleton"></div></td>
                    <td><div class="skeleton"></div></td>
                </tr>
            `;
                }

                $('#attendanceTable').html(rows);
            }

            function restoreTable() {
                $('#attendanceTable').html(originalTableHTML);
            }

            /* =========================
               LOAD ATTENDANCE
            ========================= */
            function loadAttendance() {

                let date = $('#attendance-date').val();

                showLoading();

                $.ajax({
                    url: "{{ route('instructor.student-attendance.get') }}",
                    type: "GET",
                    data: {
                        course_id: "{{ $course->id }}",
                        date: date
                    },
                    success: function(res) {

                        restoreTable();

                        setTimeout(() => {

                            resetAttendanceUI();

                            if (!res.success) return;

                            let data = res.data;

                            $('#attendanceTable .student-row').each(function() {

                                let studentId = $(this).data('student-id');

                                if (!data[studentId]) return;

                                let status = data[studentId].status;
                                let note = data[studentId].note;

                                let row = $(this);

                                let btn;

                                if (status === 'present') {
                                    btn = row.find('.status-toggle span:nth-child(1)');
                                } else if (status === 'absent') {
                                    btn = row.find('.status-toggle span:nth-child(2)');
                                } else {
                                    btn = row.find('.status-toggle span:nth-child(3)');
                                }

                                setStatus(btn[0], status,
                                    false); // ❗ no autosave on load
                                row.find('input').val(note);
                            });

                            updateSummary();

                        }, 200);
                    },
                    error: function(err) {
                        console.error(err);
                    }
                });
            }

            /* =========================
               AUTO SAVE
            ========================= */
            function autoSaveAttendance() {

                let attendances = [];

                $('#attendanceTable .student-row').each(function() {

                    let studentId = $(this).data('student-id');
                    let status = $(this).find('.status-toggle').attr('data-status') || '';
                    let note = $(this).find('input').val();

                    attendances.push({
                        student_id: studentId,
                        status: status,
                        note: note
                    });
                });

                $.ajax({
                    url: "{{ route('instructor.student-attendance.store') }}",
                    type: "POST",
                    data: {
                        course_id: "{{ $course->id }}",
                        date: $('#attendance-date').val(),
                        attendances: attendances,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function() {
                        showSavedIndicator();
                    }
                });
            }

            function triggerAutoSave() {
                clearTimeout(saveTimeout);
                saveTimeout = setTimeout(() => {
                    autoSaveAttendance();
                }, 500);
            }

            /* =========================
               STATUS
            ========================= */
            window.setStatus = function(el, status, shouldSave = true) {

                let parent = el.parentElement;

                // 🚀 prevent unnecessary updates
                if (parent.dataset.status === status) return;

                parent.dataset.status = status;

                let badges = parent.querySelectorAll('span');

                badges.forEach(b => {
                    b.classList.remove('bg-success', 'bg-danger', 'bg-warning', 'active');
                    b.classList.add('bg-light', 'text-dark');
                });

                el.classList.remove('bg-light', 'text-dark');

                el.classList.add(
                    status === 'present' ? 'bg-success' :
                    status === 'absent' ? 'bg-danger' : 'bg-warning'
                );

                el.classList.add('active');

                updateSummary();

                if (shouldSave) triggerAutoSave();
            };

            /* =========================
               SUMMARY
            ========================= */
            function updateSummary() {

                let present = 0;
                let absent = 0;
                let late = 0;
                let unmarked = 0;

                document.querySelectorAll('#attendanceTable .status-toggle').forEach(group => {

                    let status = group.dataset.status;

                    if (!status) {
                        unmarked++;
                        return;
                    }

                    if (status === 'present') present++;
                    if (status === 'absent') absent++;
                    if (status === 'late') late++;
                });

                $('#presentCount').text(present);
                $('#absentCount').text(absent);
                $('#lateCount').text(late);
                $('#unmarkedCount').text(unmarked);
            }

            /* =========================
               MARK ALL
            ========================= */
            window.markAllPresent = function() {

                let changed = false;

                document.querySelectorAll('#attendanceTable .status-toggle').forEach(group => {

                    if (group.dataset.status === 'present') return;

                    changed = true;

                    let presentBtn = group.querySelector('span:nth-child(1)');

                    // 🔥 USE YOUR EXISTING FUNCTION (BEST PRACTICE)
                    setStatus(presentBtn, 'present', false);
                });

                updateSummary();

                if (changed) triggerAutoSave();
            };
            /* =========================
               ROW CLICK
            ========================= */
            $(document).on('click', '#attendanceTable tr', function(e) {

                if ($(e.target).closest('.status-toggle').length) return;
                if (e.target.tagName === 'INPUT') return;

                let presentBtn = $(this).find('.status-toggle span:nth-child(1)');
                setStatus(presentBtn[0], 'present');
            });

            /* =========================
               NOTE CHANGE
            ========================= */
            $('#attendanceTable').on('keyup', 'input', function() {
                triggerAutoSave();
            });

            /* =========================
               DATE
            ========================= */
            function updateDateLabel() {

                let input = document.getElementById('attendance-date').value;
                let selected = new Date(input);

                let today = new Date();
                today.setHours(0, 0, 0, 0);
                selected.setHours(0, 0, 0, 0);

                let diff = Math.floor((selected - today) / (1000 * 60 * 60 * 24));

                let label = '';

                if (diff === 0) label = 'Today';
                else if (diff === 1) label = 'Tomorrow';
                else label = selected.toDateString();

                document.querySelector('.attendance-box small').innerText =
                    `${label} — ${selected.toDateString()}`;
            }

            window.setDate = function(days) {
                let date = new Date();
                date.setDate(date.getDate() + days);

                let formatted = date.toISOString().split('T')[0];
                $('#attendance-date').val(formatted).trigger('change');
            };

            $('#attendance-date').on('change', function() {
                updateDateLabel();
                loadAttendance();
            });

            /* =========================
               SAVE INDICATOR
            ========================= */
            function showSavedIndicator() {

                let el = $('#saveStatus');

                el.fadeIn(200);

                setTimeout(() => {
                    el.fadeOut(500);
                }, 1000);
            }

            /* =========================
               INIT
            ========================= */
            updateSummary();
            updateDateLabel();
            loadAttendance();

        });
    </script>
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
                        <a href="{{ route('instructor.courses.real_time') }}" class="btn btn-sm btn-light mb-4">
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
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                        fill="currentColor" class="bi bi-star-fill text-warning" viewBox="0 0 16 16">
                                        <path
                                            d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z">
                                        </path>
                                    </svg>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                        fill="currentColor" class="bi bi-star-fill text-warning" viewBox="0 0 16 16">
                                        <path
                                            d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z">
                                        </path>
                                    </svg>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                        fill="currentColor" class="bi bi-star-fill text-warning" viewBox="0 0 16 16">
                                        <path
                                            d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z">
                                        </path>
                                    </svg>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                        fill="currentColor" class="bi bi-star-fill text-warning" viewBox="0 0 16 16">
                                        <path
                                            d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z">
                                        </path>
                                    </svg>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                        fill="currentColor" class="bi bi-star-fill text-warning" viewBox="0 0 16 16">
                                        <path
                                            d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z">
                                        </path>
                                    </svg>
                                </span>
                                <span class="text-white">(140)</span>
                            </div>
                            <span class="text-white ms-4 d-none d-md-block">
                                <svg width="16" height="16" viewBox="0 0 16
                            16"
                                    fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect x="3" y="8" width="2" height="6" rx="1" fill="#DBD8E9">
                                    </rect>
                                    <rect x="7" y="5" width="2" height="9" rx="1" fill="#DBD8E9">
                                    </rect>
                                    <rect x="11" y="2" width="2" height="12" rx="1" fill="#DBD8E9">
                                    </rect>
                                </svg>
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
                <div class="col-lg-8 col-md-12 col-12 mt-n8 mb-4 mb-lg-0">
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
                                        <a class="nav-link active" id="student-attendance-tab" data-bs-toggle="pill"
                                            href="#student-attendance" role="tab" aria-controls="student-attendance"
                                            aria-selected="false">
                                            Student Attendance
                                        </a>
                                    </li>

                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link " id="attendance-tab" data-bs-toggle="pill" href="#attendance"
                                            role="tab" aria-controls="attendance" aria-selected="false"
                                            tabindex="-1">My Attendance</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <!-- Card Body -->
                        <div class="card-body">
                            <div class="tab-content" id="tabContent">
                                @include('frontend.instructor.pages.course-real-time.partials.my-attendance-tab')
                                @include('frontend.instructor.pages.course-real-time.partials.students-tab')
                                <div class="tab-pane fade active show" id="student-attendance" role="tabpanel">
                                    <div class="attendance-box">
                                        <!-- HEADER -->
                                        <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">
                                            <div>
                                                <h4 class="fw-bold mb-1">📋 Student Attendance</h4>
                                                <small class="text-muted">
                                                    {{-- dynamic date when select eg.Today — Tue, 14 Apr 2026 --}}
                                                </small>
                                            </div>

                                            <div class="d-flex gap-2">
                                                <button class="btn btn-light btn-sm" onclick="setDate(0)">Today</button>
                                                <button class="btn btn-outline-secondary btn-sm"
                                                    onclick="setDate(1)">Tomorrow</button>

                                                <input type="date" id="attendance-date"
                                                    class="form-control form-control-sm w-auto"
                                                    value="{{ now()->format('Y-m-d') }}">
                                            </div>
                                        </div>

                                        <!-- SUMMARY -->
                                        <div class="row text-center mb-4 g-3">
                                            {{-- <div class="col">
                                                <div class="summary-card">
                                                    <small>Total</small>
                                                    <h5 id="totalCount">
                                                        {{ $students->count() }}
                                                    </h5>
                                                </div>
                                            </div> --}}
                                            {{-- <div class="col">
                                                <div class="summary-card secondary">
                                                    <small>Unmarked</small>
                                                    <h5 id="unmarkedCount">0</h5>
                                                </div>
                                            </div> --}}
                                            <div class="col">
                                                <div class="summary-card success">
                                                    <small>Present</small>
                                                    <h5 id="presentCount">0</h5>
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="summary-card danger">
                                                    <small>Absent</small>
                                                    <h5 id="absentCount">0</h5>
                                                </div>
                                            </div>
                                            {{-- <div class="col">
                                                <div class="summary-card warning">
                                                    <small>Late</small>
                                                    <h5 id="lateCount">0</h5>
                                                </div>
                                            </div> --}}
                                        </div>

                                        <!-- SEARCH -->
                                        {{-- <div class="mb-3">
                                            <input type="text" id="searchStudent" class="form-control"
                                                placeholder="🔍 Search student...">
                                        </div> --}}

                                        <!-- TABLE -->
                                        <div class="table-responsive">
                                            <table class="table align-middle">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Student</th>
                                                        <th>Status</th>
                                                        <th style="width: 200px;">Note</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="attendanceTable">

                                                    @foreach ($students as $index => $student)
                                                        <tr class="student-row" data-student-id="{{ $student->id }}"
                                                            data-name="{{ strtolower($student->name) }}"
                                                            data-name="{{ strtolower($student->name) }}">
                                                            <td>{{ $index + 1 }}</td>

                                                            <!-- Student -->
                                                            <td>
                                                                <div class="d-flex align-items-center gap-2">
                                                                    <div class="avatar">
                                                                        {{ strtoupper(substr($student->name, 0, 2)) }}
                                                                    </div>
                                                                    <span>{{ $student->name }}</span>
                                                                </div>
                                                            </td>

                                                            <!-- Status -->
                                                            <td>
                                                                <div class="status-toggle" data-status="">

                                                                    <span class="badge bg-light text-dark"
                                                                        onclick="event.stopPropagation(); setStatus(this, 'present')">
                                                                        Present
                                                                    </span>

                                                                    <span class="badge bg-light text-dark"
                                                                        onclick="event.stopPropagation(); setStatus(this, 'absent')">
                                                                        Absent
                                                                    </span>

                                                                    {{-- <span class="badge bg-light text-dark"
                                                                        onclick="event.stopPropagation(); setStatus(this, 'late')">
                                                                        Late
                                                                    </span> --}}

                                                                </div>
                                                            </td>

                                                            <!-- Note -->
                                                            <td>
                                                                <input type="text" class="form-control form-control-sm"
                                                                    placeholder="Optional...">
                                                            </td>
                                                        </tr>
                                                    @endforeach

                                                </tbody>
                                            </table>
                                        </div>

                                        <!-- ACTION -->
                                        <div class="text-end mt-3">
                                            <button class="btn btn-outline-secondary me-2" onclick="markAllPresent()">
                                                Mark All Present
                                            </button>
                                            {{--
                                            <button class="btn btn-primary" onclick="saveAttendance()">
                                                💾 Save Attendance
                                            </button> --}}
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-12 col-12 mt-lg-n8">
                    <!-- Card -->
                    <div class="card  mb-4">
                        <div class="p-1">
                            <div class="d-flex justify-content-center align-items-center rounded border-white border rounded-3 bg-cover"
                                style="background-image: url({{ asset($course->thumbnail == '' ? '\default-images\staff\no-course-img.png' : $course->thumbnail) }}); height: 210px">
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
                                    $progressColor = 'bg-danger'; // red
                                } elseif ($progress <= 80) {
                                    $progressColor = 'bg-warning'; // yellow
                                } else {
                                    $progressColor = 'bg-success'; // green
                                }
                            @endphp

                            <div class="mt-4">
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
            <!-- Card -->
            <div class="pt-8 pb-3">
                <div class="row d-md-flex align-items-center mb-4">
                    <div class="col-12">
                        <h2 class="mb-0">
                            {{-- Your other courses --}}
                        </h2>
                    </div>
                </div>
                <div class="row">
                    @forelse ($other_courses as $course)
                        <div class="col-lg-3 col-md-6 col-12">
                            <!-- Card -->
                            <div class="card mb-4 card-hover">
                                <a
                                    href="
                                    {{ route('instructor.courses.real_time.show', $course->id) }}
                                "><img
                                        src="
                                        {{ asset($course->thumbnail == '' ? '\default-images\staff\no-course-img.png' : $course->thumbnail) }}
                                    "
                                        alt="course" class="card-img-top"
                                        style="height: 160px; object-fit: cover;"></a>
                                <!-- Card body -->
                                <div class="card-body">
                                    <h4 class="mb-2 text-truncate-line-2">
                                        <a href="
                                            {{ route('instructor.courses.real_time.show', $course->id) }}
                                        "
                                            class="text-inherit text-capitalize">
                                            {{ $course->title }}
                                        </a>
                                    </h4>
                                    <ul class="mb-3 list-inline">
                                        <li class="list-inline-item">
                                            <span>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                                    fill="currentColor" class="bi bi-clock align-baseline"
                                                    viewBox="0 0 16 16">
                                                    <path
                                                        d="M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71V3.5z">
                                                    </path>
                                                    <path
                                                        d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm7-8A7 7 0 1 1 1 8a7 7 0 0 1 14 0z">
                                                    </path>
                                                </svg>
                                            </span>
                                            <span>
                                                {{ $course->duration ?? 'N/A' }}h
                                            </span>
                                        </li>
                                        <li class="list-inline-item">
                                            <span>
                                                <i class="fe fe-users align-middle me-1"></i>
                                            </span>
                                            <span>
                                                {{ $course->enrollments->count() ?? 0 }} Enrolled
                                            </span>
                                        </li>
                                    </ul>
                                    <div class="mt-3 d-flex align-baseline lh-1">
                                        <span class="fs-6">
                                            {{ $course->start_date ? $course->start_date->format('d M, Y') : 'N/A' }}
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
        </div>
    </section>
    <div id="saveStatus" style="position: fixed; bottom: 20px; right: 20px; display:none;" class="badge bg-success">
        Saved ✅
    </div>
@endsection
