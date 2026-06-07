@extends('frontend.layouts.master')
@section('page_title', isset($page_title) ? $page_title : 'Page Title Here')
@php
    $status = $course->studentReports->first()?->approval_status;
@endphp
@push('styles')
    <style>
        @keyframes savingPulse {
            0% {
                opacity: 1;
            }

            50% {
                opacity: 0.45;
            }

            100% {
                opacity: 1;
            }
        }

        .badge.saving {
            animation: savingPulse 0.8s ease-in-out infinite;
            cursor: wait;
            pointer-events: none;
        }
    </style>
    @include('frontend.instructor.pages.course-real-time.styling.style')
    <script>
        $(document).on('change', '.score-input', function() {
            let input = $(this);
            let id = input.data('id');
            let field = input.data('field');
            let value = parseFloat(input.val()) || 0;
            let max = 100;
            if (field === 'assignment_score') max = 30;
            if (field === 'mini_project_score') max = 20;
            if (field === 'final_project_score') max = 40;
            if (value < 0) value = 0;
            if (value > max) value = max;
            input.val(value);
            $.ajax({
                url: `/instructor/student-report/update/${id}`,
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    field: field,
                    value: value,
                },
                success: function(res) {
                    input.closest('tr').find('.total-score').text(res.total_score);
                    let badge = input.closest('tr').find('.badge');
                    badge.text(res.result);
                    badge.removeClass('bg-success bg-danger');
                    badge.addClass(res.result === 'pass' ? 'bg-success' : 'bg-danger');
                },
            });
        });
        document.addEventListener('DOMContentLoaded', function() {
            let saveTimeout = null;
            let originalTableHTML = '';
            /* =========================
               CONFIRM DIALOG
            ========================= */
            function showConfirm(title, message, confirmLabel, type, onConfirm) {
                $('#confirmOverlay').remove();
                let borderColor = type === 'danger' ? 'var(--bs-danger)' : '#198754';
                let btnClass = type === 'danger' ? 'btn-danger' : 'btn-success';
                let html = `
                    <div id="confirmOverlay" style="
                        position: absolute; inset: 0; z-index: 1050;
                        background: rgba(0,0,0,0.35);
                        display: flex; align-items: center; justify-content: center;
                        border-radius: inherit;">
                        <div style="
                            background: #fff; border-radius: 12px;
                            border: 1px solid ${borderColor};
                            padding: 1.25rem 1.5rem; max-width: 380px; width: 90%;
                            box-shadow: 0 4px 24px rgba(0,0,0,0.12);">
                            <p class="fw-semibold mb-1">${title}</p>
                            <p class="text-muted small mb-3">${message}</p>
                            <div class="d-flex gap-2 justify-content-end">
                                <button class="btn btn-sm btn-secondary" id="confirmNo">Cancel</button>
                                <button class="btn btn-sm ${btnClass}" id="confirmYes">${confirmLabel}</button>
                            </div>
                        </div>
                    </div>`;
                let $anchor = $('.attendance-box').closest('.card');
                $anchor.css('position', 'relative').append(html);
                $('#confirmNo').on('click', function() {
                    $('#confirmOverlay').remove();
                });
                $('#confirmYes').on('click', function() {
                    $('#confirmOverlay').remove();
                    onConfirm();
                });
            }
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
                        </tr>`;
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
                    type: 'GET',
                    data: {
                        course_id: "{{ $course->id }}",
                        date: date,
                    },
                    success: function(res) {
                        restoreTable();
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
                            if (status === 'present')
                                btn = row.find('.status-toggle span:nth-child(1)');
                            else if (status === 'absent')
                                btn = row.find('.status-toggle span:nth-child(2)');
                            else
                                return;
                            setStatus(btn[0], status, false);
                            row.find('input').val(note);
                        });
                        updateSummary();
                    },
                    error: function(err) {
                        console.error(err);
                    },
                });
            }
            // 🔒 Lock attendance if report is pending approval
            @if ($status === 'pending')
                document.querySelectorAll('#attendanceTable .status-toggle span').forEach(btn => {
                    btn.style.pointerEvents = 'none';
                    btn.style.opacity = '0.6';
                });
                document.querySelectorAll('#attendanceTable input').forEach(input => {
                    input.setAttribute('disabled', true);
                    input.setAttribute('placeholder', 'Locked');
                });
                $(document).off('click', '#attendanceTable tr');
                document.querySelector('[onclick="markAllPresent()"]')?.setAttribute('disabled', true);
                document.querySelector('[onclick="resetAttendance()"]')?.setAttribute('disabled', true);
                $('#attendanceTable').off('keyup', 'input');
            @endif
            /* =========================
            AUTO SAVE (silent — no toast)
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
                    type: 'POST',
                    data: {
                        course_id: "{{ $course->id }}",
                        date: $('#attendance-date').val(),
                        attendances: attendances,
                        _token: "{{ csrf_token() }}",
                    },
                    success: function(res) {
                        // ✅ Clear ALL pulsing badges at once
                        $('#attendanceTable .badge.saving').removeClass('saving');
                        let reports = res.reports;
                        Object.keys(reports).forEach(studentId => {
                            let row = $(`#report-row-${studentId}`);
                            if (!row.length) return;
                            let report = reports[studentId];
                            row.find('.present').text(report.present);
                            row.find('.absent').text(report.absent);
                            row.find('.total-score').text(report.total_score);
                            let badge = row.find('.badge');
                            badge.text(report.result);
                            badge.removeClass('bg-success bg-danger');
                            badge.addClass(report.result === 'pass' ? 'bg-success' :
                                'bg-danger');
                        });
                        // ✅ Silently refresh session log if it's open
                        if (typeof window.reloadSessionLog === 'function') {
                            window.reloadSessionLog();
                        }
                    },
                    error: function() {
                        // ✅ Same here — clear all on failure too
                        $('#attendanceTable .badge.saving').removeClass('saving');
                    },
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
                if (parent.dataset.status === status) return;
                parent.dataset.status = status;
                let badges = parent.querySelectorAll('span');
                badges.forEach(b => {
                    b.classList.remove('bg-success', 'bg-danger', 'bg-warning', 'active', 'saving');
                    b.classList.add('bg-light', 'text-dark');
                });
                el.classList.remove('bg-light', 'text-dark');
                el.classList.add(
                    status === 'present' ? 'bg-success' :
                    status === 'absent' ? 'bg-danger' : 'bg-warning'
                );
                el.classList.add('active');
                // ✅ Mark this badge as saving
                if (shouldSave) el.classList.add('saving');
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
               MARK ALL PRESENT (with confirm)
            ========================= */
            window.markAllPresent = function() {
                showConfirm(
                    'Mark all students as present?',
                    `This will overwrite any statuses already set for <strong>${$('#attendance-date').val()}</strong>. Continue?`,
                    'Yes, mark all present',
                    'success',
                    function() {
                        let changed = false;
                        document.querySelectorAll('#attendanceTable .status-toggle').forEach(group => {
                            if (group.dataset.status === 'present') return;
                            changed = true;
                            let presentBtn = group.querySelector('span:nth-child(1)');
                            setStatus(presentBtn, 'present', false);
                        });
                        updateSummary();
                        if (changed) triggerAutoSave();
                    }
                );
            };
            /* =========================
               RESET ATTENDANCE (with confirm)
            ========================= */
            window.resetAttendance = function() {
                let date = $('#attendance-date').val();
                showConfirm(
                    'Reset all attendance for this date?',
                    `All statuses and notes for <strong>${date}</strong> will be permanently cleared.`,
                    'Yes, reset',
                    'danger',
                    function() {
                        $.ajax({
                            url: "{{ route('instructor.student-attendance.reset') }}",
                            type: 'POST',
                            data: {
                                course_id: "{{ $course->id }}",
                                date: date,
                                _token: "{{ csrf_token() }}",
                            },
                            success: function(res) {
                                resetAttendanceUI();
                                // ✅ Sync report table rows
                                let reports = res.reports;
                                Object.keys(reports).forEach(studentId => {
                                    let row = $(`#report-row-${studentId}`);
                                    if (!row.length) return;
                                    let report = reports[studentId];
                                    row.find('.present').text(report.present);
                                    row.find('.absent').text(report.absent);
                                    row.find('.total-score').text(report.total_score);
                                    let badge = row.find('.badge');
                                    badge.text(report.result);
                                    badge.removeClass('bg-success bg-danger');
                                    badge.addClass(report.result === 'pass' ?
                                        'bg-success' : 'bg-danger');
                                });
                                // ✅ Toast only on destructive reset
                                showSavedIndicator();
                                // In resetAttendance success callback, after showSavedIndicator():
                                if (typeof window.reloadSessionLog === 'function') {
                                    window.reloadSessionLog();
                                }
                            },
                            error: function() {
                                alert('Something went wrong. Please try again.');
                            },
                        });
                    }
                );
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
                let label = diff === 0 ? 'Today' : diff === 1 ? 'Tomorrow' : selected.toDateString();
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
                                        <a class="nav-link" id="student-attendance-tab" data-bs-toggle="pill"
                                            href="#student-attendance" role="tab" aria-controls="student-attendance"
                                            aria-selected="false">
                                            Student's Attendance
                                        </a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" id="session-log-tab" data-bs-toggle="pill" href="#session-log"
                                            role="tab" aria-controls="session-log" aria-selected="false" tabindex="-1">
                                            Session Log
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
                                <div class="tab-pane fade " id="student-attendance" role="tabpanel">
                                    <div class="attendance-box">

                                        <!-- HEADER -->
                                        <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">
                                            <div>
                                                <h4 class="fw-bold mb-1">📋 Student Attendance</h4>
                                                @if ($status === 'pending')
                                                    <div class="alert alert-warning d-flex align-items-center mb-4"
                                                        role="alert">
                                                        <i class="fe fe-lock me-2"></i>
                                                        <div>
                                                            Attendance and scores are <strong>locked</strong> while approval
                                                            is pending.
                                                            Cancel the request to make changes.
                                                        </div>
                                                    </div>
                                                @endif
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
                                        <div class="text-end mt-3 d-flex gap-2 justify-content-end">
                                            @if ($status !== 'pending')
                                                <button class="btn btn-outline-danger btn-sm" onclick="resetAttendance()">
                                                    <i class="fe fe-refresh-cw me-1"></i> Reset Attendance
                                                </button>
                                                <button class="btn btn-outline-secondary btn-sm"
                                                    onclick="markAllPresent()">
                                                    Mark All Present
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                {{-- Session Log Tab --}}
                                <div class="tab-pane fade" id="session-log" role="tabpanel">
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
                                                        {{-- Editable Scores --}}
                                                        <td class="text-center">
                                                            <input type="number"
                                                                class="form-control form-control-sm text-center score-input"
                                                                style="width: 70px; margin: auto;"
                                                                data-id="{{ $report->id }}"
                                                                data-field="assignment_score"
                                                                value="{{ $report->assignment_score }}" min="0"
                                                                max="30"
                                                                {{ $status === 'pending' ? 'readonly disabled' : '' }}>
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="number"
                                                                class="form-control form-control-sm text-center score-input"
                                                                style="width: 70px; margin: auto;"
                                                                data-id="{{ $report->id }}"
                                                                data-field="mini_project_score"
                                                                value="{{ $report->mini_project_score }}" min="0"
                                                                max="20"
                                                                {{ $status === 'pending' ? 'readonly disabled' : '' }}>
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="number"
                                                                class="form-control form-control-sm text-center score-input"
                                                                style="width: 70px; margin: auto;"
                                                                data-id="{{ $report->id }}"
                                                                data-field="final_project_score"
                                                                value="{{ $report->final_project_score }}" min="0"
                                                                max="40"
                                                                {{ $status === 'pending' ? 'readonly disabled' : '' }}>
                                                        </td>
                                                        {{-- Auto-updated by JS after save --}}
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
                                                {{-- Show Send For Approval only for draft/pending --}}
                                                <div class="mt-4">
                                                    @if ($status === 'draft')
                                                        <button type="button" class="btn btn-primary btn-sm"
                                                            data-bs-toggle="modal" data-bs-target="#approvalModal">
                                                            <i class="fe fe-send me-1"></i> Send For Approval
                                                        </button>
                                                    @elseif ($status === 'pending')
                                                        <button type="button" class="btn btn-danger btn-sm"
                                                            data-bs-toggle="modal" data-bs-target="#cancelApprovalModal">
                                                            <i class="fe fe-x me-1"></i> Cancel Request
                                                        </button>
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
                            <h2 class="mb-0">
                                MY TEACHING COURSES
                            </h2>
                        </div>
                    </div>
                    <div class="row">
                        @forelse ($other_courses as $course)
                            <div class="col-lg-3 col-md-6 col-12">

                                <!-- Card -->
                                <div class="card mb-4 card-hover">
                                    <a href="{{ route('instructor.courses.real_time.show', $course->id) }}">
                                        <img src="{{ asset($course->thumbnail == '' ? asset('\default-images\staff\no-course-img.png') : asset($course->thumbnail)) }}"
                                            alt="course" class="card-img-top"
                                            style="height: 160px; object-fit: cover;">
                                    </a>

                                    <!-- Card body -->
                                    <div class="card-body">
                                        <h4 class="mb-2 text-truncate-line-2">
                                            <a href="{{ route('instructor.courses.real_time.show', $course->id) }}"
                                                class="text-inherit text-capitalize">
                                                {{ $course->title }}
                                            </a>
                                        </h4>
                                        <ul class="mb-3 list-inline">
                                            <li class="list-inline-item">
                                                <span>
                                                    <i class="bi bi-clock"></i>
                                                </span>
                                                <span>
                                                    {{ $course->duration ?? 'N/A' }}h
                                                </span>
                                            </li>
                                            <li class="list-inline-item">
                                                <span>
                                                    <i class="bi bi-people"></i>
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
            @endif
        </div>
    </section>

    <!-- Modal -->

    <!-- Approval Modal -->
    <div class="modal fade" id="approvalModal" tabindex="-1" role="dialog" aria-hidden="true"
        data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        ⚠️ Send for Approval?
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" id="approvalCloseBtn"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-1">You are about to submit the student report for:</p>
                    <p class="fw-bold text-capitalize mb-3">📚 {{ $course->title }}</p>
                    <div class="alert alert-warning d-flex align-items-start gap-2 mb-0">
                        <i class="fe fe-alert-triangle mt-1"></i>
                        <div>
                            Once submitted, <strong>attendance and scores will be locked</strong>
                            until the admin approves or you cancel the request.
                            Please make sure everything is accurate before proceeding.
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal"
                        id="approvalCancelBtn">
                        Cancel
                    </button>
                    <form id="approvalForm"
                        action="{{ route('instructor.student-report.request-approval', $course->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-sm" id="approvalSubmitBtn">
                            <span id="approvalBtnText">
                                <i class="fe fe-send me-1"></i> Yes, Send For Approval
                            </span>
                            <span id="approvalBtnLoading" style="display: none;">
                                <span class="spinner-border spinner-border-sm me-1" role="status"></span>
                                Sending...
                            </span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Cancel Approval Modal -->
    <div class="modal fade" id="cancelApprovalModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
        data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Cancel Approval Request?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" id="cancelModalCloseBtn"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-1">You are about to withdraw the approval request for:</p>
                    <p class="fw-bold text-capitalize mb-3">📚 {{ $course->title }}</p>
                    <div class="alert alert-danger d-flex align-items-start gap-2 mb-0">
                        <i class="fe fe-alert-triangle mt-1"></i>
                        <div>
                            This will revert the report back to <strong>Draft</strong>.
                            Attendance and scores will be <strong>editable again</strong>.
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal"
                        id="cancelModalKeepBtn">
                        No, Keep Pending
                    </button>
                    <form action="{{ route('instructor.student-report.cancel-approval', $course->id) }}" method="POST"
                        id="cancelApprovalForm">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-sm" id="cancelSubmitBtn">
                            <span id="cancelBtnText">
                                <i class="fe fe-x me-1"></i> Yes, Cancel Request
                            </span>
                            <span id="cancelBtnLoading" style="display: none;">
                                <span class="spinner-border spinner-border-sm me-1" role="status"></span>
                                Cancelling...
                            </span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div id="saveStatus" style="position: fixed; bottom: 20px; right: 20px; display:none;" class="badge bg-success">
        Saved ✅
    </div>
@endsection
@push('scripts')
    <script>
        /* =========================
                                                                                                           APPROVAL MODAL
                                                                                                        ========================= */
        document.getElementById('approvalForm')?.addEventListener('submit', function() {
            const submitBtn = document.getElementById('approvalSubmitBtn');
            const cancelBtn = document.getElementById('approvalCancelBtn');
            const closeBtn = document.getElementById('approvalCloseBtn');
            const btnText = document.getElementById('approvalBtnText');
            const btnLoading = document.getElementById('approvalBtnLoading');
            // Lock everything
            submitBtn.disabled = true;
            cancelBtn.disabled = true;
            closeBtn.disabled = true;
            // Show spinner
            btnText.style.display = 'none';
            btnLoading.style.display = 'inline-flex';
        });
        // Reset approval modal state when closed
        document.getElementById('approvalModal')?.addEventListener('hidden.bs.modal', function() {
            const submitBtn = document.getElementById('approvalSubmitBtn');
            const cancelBtn = document.getElementById('approvalCancelBtn');
            const closeBtn = document.getElementById('approvalCloseBtn');
            const btnText = document.getElementById('approvalBtnText');
            const btnLoading = document.getElementById('approvalBtnLoading');
            submitBtn.disabled = false;
            cancelBtn.disabled = false;
            closeBtn.disabled = false;
            btnText.style.display = 'inline';
            btnLoading.style.display = 'none';
        });
        /* =========================
           CANCEL APPROVAL MODAL
        ========================= */
        document.getElementById('cancelApprovalForm')?.addEventListener('submit', function() {
            const submitBtn = document.getElementById('cancelSubmitBtn');
            const keepBtn = document.getElementById('cancelModalKeepBtn');
            const closeBtn = document.getElementById('cancelModalCloseBtn');
            const btnText = document.getElementById('cancelBtnText');
            const btnLoading = document.getElementById('cancelBtnLoading');
            // Lock everything
            submitBtn.disabled = true;
            keepBtn.disabled = true;
            closeBtn.disabled = true;
            // Show spinner
            btnText.style.display = 'none';
            btnLoading.style.display = 'inline-flex';
        });
        // Reset cancel modal state when closed
        document.getElementById('cancelApprovalModal')?.addEventListener('hidden.bs.modal', function() {
            const submitBtn = document.getElementById('cancelSubmitBtn');
            const keepBtn = document.getElementById('cancelModalKeepBtn');
            const closeBtn = document.getElementById('cancelModalCloseBtn');
            const btnText = document.getElementById('cancelBtnText');
            const btnLoading = document.getElementById('cancelBtnLoading');
            submitBtn.disabled = false;
            keepBtn.disabled = false;
            closeBtn.disabled = false;
            btnText.style.display = 'inline';
            btnLoading.style.display = 'none';
        });
        document.addEventListener("DOMContentLoaded", function() {
            const tabButtons = document.querySelectorAll('#tab a[data-bs-toggle="pill"]');
            // 🔥 Default tab = My Attendance
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
            // ✅ Exposed so autoSaveAttendance can call it
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
                    url: "{{ route('instructor.student-attendance.session-log') }}",
                    type: 'GET',
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
                        renderSessionLog(res.sessions, res.students);
                    },
                    error: function() {
                        $('#sessionLogContainer').html(
                            '<div class="text-center text-muted py-5">Failed to load session log.</div>'
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
                let totalPresent = 0;
                let totalAbsent = 0;
                let totalSessions = sessions.length;
                sessions.forEach(s => {
                    totalPresent += parseInt(s.present_count || 0);
                    totalAbsent += parseInt(s.absent_count || 0);
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
            </div>
            <div class="mb-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
                <span class="text-muted small">
                    <i class="fe fe-list me-1"></i>${totalSessions} session${totalSessions !== 1 ? 's' : ''} recorded
                </span>
                <div class="d-flex gap-3 small text-muted">
                    <span><span class="badge bg-success me-1">&nbsp;</span>Present</span>
                    <span><span class="badge bg-danger me-1">&nbsp;</span>Absent</span>
                </div>
            </div>`;
                sessions.forEach((session, index) => {
                    let totalStudents = parseInt(session.total_students || 0);
                    let presentCount = parseInt(session.present_count || 0);
                    let absentCount = parseInt(session.absent_count || 0);
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
                                <span class="text-muted small">${presentCount}P / ${absentCount}A
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
        })();
    </script>
@endpush
