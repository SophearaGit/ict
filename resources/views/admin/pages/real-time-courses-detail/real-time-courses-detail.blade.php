@extends('admin.layouts.master')
@section('page_title', isset($page_title) ? $page_title : 'Page Title Here')
@push('styles')
    <link rel="stylesheet" href="/admin/assets/dist/libs/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
    @include('admin.pages.real-time-courses-detail.style.teacher-attendant-table')
    @include('admin.pages.real-time-courses-detail.style.attendanceTable_students')
@endpush
@section('content')
    <div class="row">
        <div class="col-lg-12 col-md-12 col-12">
            <!-- Page Header -->
            <div class="border-bottom pb-3 mb-3 d-md-flex align-items-center justify-content-between">
                <div class="mb-3 mb-md-0">
                    <h1 class="mb-1 h2 fw-bold">
                        Courses
                    </h1>
                    <!-- Breadcrumb -->
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item active">
                                <a href="javascript:void;">
                                    Courses
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                {{ $course->title }}
                            </li>
                        </ol>
                    </nav>
                </div>
                <div class="nav btn-group" role="tablist">
                    <a href="{{ route('admin.courses.realtime.index') }}" class="btn btn-primary">
                        {{-- <i class="bi bi-arrow-left"></i>  --}}
                        Back to List
                    </a>
                </div>
            </div>
        </div>
    </div>
    <section class="pt-lg-8 pb-8 bg-primary rounded">
        <div class="container pb-lg-8">
            <div class="row align-items-center">
                <div class="col-xl-7 col-lg-7 col-md-12">
                    <div>
                        <h1 class="text-white display-4 fw-semibold">
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
                            {{-- <a href="#" class="bookmark text-white">
                                <i class="fe fe-bookmark fs-4 me-2"></i>
                                Bookmark
                            </a> --}}
                            <span class="text-white">
                                <i class="fe fe-user"></i>
                                {{ $course->enrollments->count() }} Enrolled
                            </span>
                            <div>
                                <span class="text-white ms-4 d-none d-md-block">
                                    <i class="fe fe-clock"></i>
                                    {{ $course->duration }} Hours
                                </span>
                            </div>
                            <span class="text-white ms-4 d-none d-md-block">
                                <i class="fe fe-calendar"></i>
                                <span class="align-middle">
                                    {{ $course->total_sessions ?? 0 }} Sessions
                                </span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="pb-8">
        <div class="container">
            <div class="row">
                <div class="col-lg-9 col-md-12 col-12 mt-n8 mb-4 mb-lg-0">

                    <!-- Card -->
                    <div class="card rounded-3">

                        <!-- Card header -->
                        <div class="card-header border-bottom-0 p-0">
                            <div>
                                @include('admin.pages.real-time-courses-detail.partials.tabs.tabs')
                            </div>
                        </div>

                        <!-- Card Body -->
                        <div class="card-body">
                            <div class="tab-content" id="tabContent">
                                @include('admin.pages.real-time-courses-detail.partials.tab-contents.students-tab')
                                {{-- teacher attendance emtpy tab content --}}
                                <div class="tab-pane fade" id="teacher-attendance" role="tabpanel"
                                    aria-labelledby="teacher-attendance-tab">
                                    <div class="sheet">

                                        <!-- Header -->
                                        <div class="top-header">
                                            <div class="logo">
                                                <i class="fe fe-calendar text-primary fs-1 me-2"></i>
                                            </div>
                                            <div>
                                                <div class="title">ICT Professional Training Center</div>
                                                <div class="sub-title">Teacher's Attendant</div>
                                            </div>
                                        </div>
                                        <form method="GET" class="mb-3">
                                            <div class="filter-bar p-3 rounded-3 bg-light border">
                                                <div class="row g-2 align-items-end">

                                                    <!-- Date Range -->
                                                    <div class="col-12 col-md-6 col-lg-7">
                                                        <label class="form-label fw-semibold mb-1">
                                                            <i class="ti ti-calendar me-1"></i> Date Range
                                                        </label>
                                                        <div class="input-daterange input-group" id="date-range">
                                                            <input type="text" class="form-control" name="from_date"
                                                                placeholder="From"
                                                                value="{{ request('from_date', now()->startOfMonth()->format('Y-m-d')) }}">
                                                            <span
                                                                class="input-group-text bg-primary text-white px-2 px-md-3">
                                                                TO
                                                            </span>
                                                            <input type="text" class="form-control" name="to_date"
                                                                placeholder="To"
                                                                value="{{ request('to_date', now()->endOfMonth()->format('Y-m-d')) }}">
                                                        </div>
                                                    </div>

                                                    <!-- Buttons -->
                                                    <div class="col-12 col-md-6 col-lg-5">
                                                        <div class="d-flex flex-column flex-md-row gap-2">
                                                            <button class="btn btn-primary w-100">
                                                                <i class="ti ti-search me-1"></i> Filter
                                                            </button>
                                                            <a href="{{ route('admin.courses.realtime.show', $course->id) }}"
                                                                class="btn btn-outline-secondary w-100">
                                                                Reset
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                        @if (request('from_date') && request('to_date'))
                                            <div
                                                class="alert alert-primary d-flex justify-content-between align-items-center mb-3">
                                                <div>
                                                    <strong>Filtered:</strong>
                                                    {{ request('from_date') }} → {{ request('to_date') }}
                                                </div>
                                                <div class="d-flex gap-4">
                                                    <span><strong>Hours:</strong> {{ $course->filtered_hours ?? 0 }}</span>
                                                    <span><strong>Sessions:</strong>
                                                        {{ $course->filtered_sessions ?? 0 }}</span>
                                                    <span><strong>Earnings:</strong>
                                                        ${{ $course->filtered_earnings ?? 0 }}</span>
                                                </div>
                                            </div>
                                        @endif

                                        <!-- Teacher -->
                                        <div class="info-row">
                                            <div class="info-label">
                                                Teacher's Name:
                                            </div>
                                            <div class="info-value text-capitalize text-center" contenteditable="false">
                                                <strong class="text-black">
                                                    {{ $course->instructor->name ?? 'No Instructor' }}
                                                </strong>
                                            </div>
                                        </div>

                                        <!-- Subject -->
                                        <div class="info-row highlight">
                                            <div class="info-label">Subject:</div>
                                            <div class="info-value text-capitalize text-center" contenteditable="false">
                                                <strong class="text-black">
                                                    {{ $course->title }} |
                                                    @if ($course->schedule)
                                                        @php
                                                            $days = collect(explode('-', $course->schedule->study_day))
                                                                ->map(fn($day) => ucfirst($day))
                                                                ->implode(' • ');
                                                            $start = \Carbon\Carbon::parse(
                                                                $course->schedule->start_time,
                                                            )->format('g:i ');
                                                            $end = \Carbon\Carbon::parse(
                                                                $course->schedule->end_time,
                                                            )->format('g:i A');
                                                            $shift = ucfirst($course->schedule->shift);
                                                        @endphp
                                                        <strong>
                                                            {{ $days }} | {{ $shift }} (
                                                            {{ $start }}
                                                            –
                                                            {{ $end }} )
                                                        </strong>
                                                    @else
                                                        <span class="text-muted">No schedule</span>
                                                    @endif
                                                </strong>
                                            </div>
                                        </div>

                                        <!-- Table -->
                                        <table id="attendanceTable">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Date</th>
                                                    <th>Time in</th>
                                                    <th>Time out</th>
                                                    <th>T H</th>
                                                    <th>A T H</th>
                                                    {{-- <th>Room</th> --}}
                                                    <th>
                                                        នាទីខ្វះ
                                                    </th>
                                                    <th>Note</th>
                                                </tr>
                                            </thead>
                                            <tbody id="attendanceBody">
                                                @php
                                                    $attendances = $course->teacherAttendances;
                                                @endphp
                                                @if ($attendances->isNotEmpty())
                                                    @foreach ($attendances as $index => $attendance)
                                                        <tr>
                                                            <td style="padding: 10px">{{ $index + 1 }}</td>
                                                            <td style="display:none;">
                                                                <input type="hidden"
                                                                    name="attendances[{{ $index }}][id]"
                                                                    value="{{ $attendance->id }}">
                                                            </td>
                                                            <td>
                                                                <input type="date"
                                                                    name="attendances[{{ $index }}][date]"
                                                                    value="{{ $attendance->date }}"
                                                                    class="form-control text-dark" readonly>
                                                            </td>
                                                            <td>
                                                                <input type="time"
                                                                    name="attendances[{{ $index }}][start_time]"
                                                                    value="{{ \Carbon\Carbon::parse($attendance->start_time)->format('H:i') }}"
                                                                    class="form-control text-dark" readonly>
                                                            </td>
                                                            <td>
                                                                <input type="time"
                                                                    name="attendances[{{ $index }}][end_time]"
                                                                    value="{{ \Carbon\Carbon::parse($attendance->end_time)->format('H:i') }}"
                                                                    class="form-control text-dark" readonly>
                                                            </td>
                                                            <td>
                                                                <input type="text"
                                                                    name="attendances[{{ $index }}][total_hours]"
                                                                    value="{{ number_format($attendance->total_hours) }}"
                                                                    class="form-control total-hours text-uppercase text-dark text-center"
                                                                    readonly>
                                                            </td>
                                                            <td>
                                                                <input type="text"
                                                                    name="attendances[{{ $index }}][actual_hours]"
                                                                    value="{{ number_format($attendance->actual_hours) }}"
                                                                    class="form-control actual-hours text-uppercase text-dark text-center"
                                                                    readonly>
                                                            </td>
                                                            {{-- <td>
                                                                <input type="text"
                                                                    name="attendances[{{ $index }}][room]"
            value="{{ $attendance->room }}"
            class="form-control text-uppercase text-dark text-center">
            </td> --}}
                                                            <td>
                                                                <input type="number"
                                                                    name="attendances[{{ $index }}][late_minutes]"
                                                                    value="{{ $attendance->late_minutes }}"
                                                                    class="form-control text-center" readonly>
                                                            </td>
                                                            <td>
                                                                <input type="text"
                                                                    name="attendances[{{ $index }}][late_reason]"
                                                                    value="{{ $attendance->late_reason }}"
                                                                    class="form-control" readonly>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane fade " id="student-attendance" role="tabpanel">
                                    @php
                                        $data = $attendanceData;
                                        $dates = array_slice($data['table_structure']['columns'], 5);
                                    @endphp
                                    <div class="attendance-wrapper mt-4">

                                        <!-- HEADER CARD -->
                                        {{-- <div class="card mb-3">
                                            <div class="card-body">
                                                <div class="row text-sm">
                                                    <div class="col-md-3"><strong>Start:</strong>
                                                        {{ $data['form_metadata']['class_start'] }}
        </div>
        <div class="col-md-3"><strong>Room:</strong>
         {{ $data['form_metadata']['room'] }}
        </div>
        <div class="col-md-3"><strong>Lecturer:</strong>
         {{ $data['form_metadata']['lecturer_name'] }}
        </div>
        <div class="col-md-3"><strong>Phone:</strong>
         {{ $data['form_metadata']['lecturer_phone'] ?? '-' }}
        </div>
       </div>
      </div>
     </div> --}}

                                        <!-- TABLE -->
                                        <div class="card">
                                            <div class="card-body p-0">
                                                <div class="table-responsive">
                                                    <table id="attendanceTable_students"
                                                        class="table table-bordered table-hover align-middle text-center mb-0">

                                                        <!-- TITLE -->
                                                        <thead class="table-light">
                                                            <tr>
                                                                <th colspan="{{ count($data['table_structure']['columns']) }}"
                                                                    class="text-center fw-bold">
                                                                    {{ $data['form_metadata']['class_title'] }}
                                                                </th>
                                                            </tr>

                                                            <!-- HEADER -->
                                                            <tr>
                                                                @foreach ($data['table_structure']['columns'] as $col)
                                                                    <th>{{ $col }}</th>
                                                                @endforeach
                                                            </tr>
                                                        </thead>

                                                        <!-- BODY -->
                                                        <tbody>
                                                            @foreach ($data['table_structure']['data_rows'] as $row)
                                                                <tr>

                                                                    <!-- No -->
                                                                    <td>{{ $row['no'] }}</td>

                                                                    <!-- Student Name -->
                                                                    <td class="text-start">
                                                                        <div class="fw-semibold text-capitalize">
                                                                            {{ $row['student_name'] }}
                                                                        </div>
                                                                    </td>

                                                                    <!-- Gender -->
                                                                    <td>
                                                                        {{ $row['sex'] == 'M' ? 'Male' : ($row['sex'] == 'F' ? 'Female' : '-') }}
                                                                    </td>

                                                                    <!-- Day -->
                                                                    <td class="text-capitalize">
                                                                        {{ ucfirst($row['day']) }}
                                                                    </td>

                                                                    <!-- Shift -->
                                                                    <td class="text-capitalize">
                                                                        {{ ucfirst($row['shift']) }}
                                                                    </td>

                                                                    <!-- Attendance -->
                                                                    @foreach ($dates as $date)
                                                                        @php
                                                                            $status = $row['attendance'][$date] ?? null;
                                                                        @endphp
                                                                        <td>
                                                                            @if ($status == 'P')
                                                                                <span class="badge bg-success">P</span>
                                                                            @elseif ($status == 'A')
                                                                                <span class="badge bg-danger">A</span>
                                                                            @elseif ($status == 'L')
                                                                                <span
                                                                                    class="badge bg-warning text-dark">Late</span>
                                                                            @else
                                                                                <span class="text-muted">—</span>
                                                                            @endif
                                                                        </td>
                                                                    @endforeach
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- student-report-tab (empty) --}}
                                <div class="tab-pane fade" id="student-report" role="tabpanel"
                                    aria-labelledby="student-report-tab">
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
                                                                max="30" readonly>
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="number"
                                                                class="form-control form-control-sm text-center score-input"
                                                                style="width: 70px; margin: auto;"
                                                                data-id="{{ $report->id }}"
                                                                data-field="mini_project_score"
                                                                value="{{ $report->mini_project_score }}" min="0"
                                                                max="20" readonly>
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="number"
                                                                class="form-control form-control-sm text-center score-input"
                                                                style="width: 70px; margin: auto;"
                                                                data-id="{{ $report->id }}"
                                                                data-field="final_project_score"
                                                                value="{{ $report->final_project_score }}" min="0"
                                                                max="40" readonly>
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
                                                    @php
                                                        $status = $course->studentReports->first()?->approval_status;
                                                    @endphp
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
                                                    @elseif ($status === 'pending')
                                                        <div class="d-flex gap-2">
                                                            {{-- Approve Button --}}
                                                            <button type="button"
                                                                class="btn btn-success w-100 rounded-pill shadow-sm py-2"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#approveReportModal">
                                                                <i class="fe fe-check-circle me-1"></i>
                                                                Approve
                                                            </button>
                                                            {{-- Reject Button --}}
                                                            <button type="button"
                                                                class="btn btn-outline-danger w-100 rounded-pill py-2"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#rejectReportModal">
                                                                <i class="fe fe-x-circle me-1"></i>
                                                                Reject
                                                            </button>
                                                        </div>
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
                {{-- APPROVE MODAL --}}
                <div class="modal fade" id="approveReportModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-0 rounded-4 shadow">
                            <div class="modal-body p-5 text-center">
                                <div class="mb-4">
                                    <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex p-4">
                                        <i class="fe fe-check-circle text-light fs-1"></i>
                                    </div>
                                </div>
                                <h4 class="fw-bold mb-2">
                                    Approve Student Report?
                                </h4>
                                <p class="text-muted mb-4">
                                    This will officially approve all student
                                    reports for this course.
                                </p>
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-light w-50 rounded-pill"
                                        data-bs-dismiss="modal">
                                        Cancel
                                    </button>
                                    <form action="{{ route('admin.student-report.approve', $course->id) }}"
                                        method="POST" class="w-50">
                                        @csrf
                                        <button class="btn btn-success w-100 rounded-pill">
                                            Yes, Approve
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- REJECT MODAL --}}
                <div class="modal fade" id="rejectReportModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-0 rounded-4 shadow">
                            <div class="modal-body p-5 text-center">
                                <div class="mb-4">
                                    <div class="bg-danger bg-opacity-10 rounded-circle d-inline-flex p-4">
                                        <i class="fe fe-x-circle text-light fs-1"></i>
                                    </div>
                                </div>
                                <h4 class="fw-bold mb-2">
                                    Reject Student Report?
                                </h4>
                                <p class="text-muted mb-4">
                                    This action will move the report back to
                                    draft status.
                                </p>
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-light w-50 rounded-pill"
                                        data-bs-dismiss="modal">
                                        Cancel
                                    </button>
                                    <form action="{{ route('admin.student-report.reject', $course->id) }}" method="POST"
                                        class="w-50">
                                        @csrf
                                        @method('PATCH')
                                        <button class="btn btn-danger w-100 rounded-pill">
                                            Yes, Reject
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-12 col-12 mt-lg-n8">

                    <!-- Card -->
                    <div class="card mb-4">
                        <div class="p-1">
                            <div class="d-flex justify-content-center align-items-center rounded border-white border rounded-3 bg-cover"
                                style="background-image: url({{ $course->thumbnail === '' ? asset('/default-images/staff/no-course-img.png') : asset($course->thumbnail) }}); height: 210px">
                                {{-- <a class="glightbox icon-shape rounded-circle btn-play icon-xl"
                                    href="https://www.youtube.com/watch?v=Nfzi7034Kbg">
                                    <i class="fe fe-camera"></i>
                                </a> --}}
                            </div>
                        </div>

                        <!-- Card body -->
                        {{-- <div class="card-body">

                            <!-- Price single page -->
                            <div class="mb-3">
                                <span class="text-dark fw-bold h2">
                                    Course Revenue:
                                    ${{ number_format($course->enrollments->sum(fn($enrollment) => $enrollment->course->price)) }}
   </span><br>
   <span class="fs-4">
                                    Teacher Earning:
                                    ${{ number_format($course->enrollments->sum(fn($enrollment) => $enrollment->course->price) * 0.7) }}
                                </span>
  </div>
  <div class="d-grid">
   <a href="#" class="btn btn-primary mb-2">Start Free Month</a>
   <a href="pricing.html" class="btn btn-outline-primary">Get Full Access</a>
  </div>
 </div> --}}
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

                    <!-- Card -->
                    <div class="card border-0 rounded-4 overflow-hidden">
                        {{-- Avatar + name + tags + rating --}}
                        <div class="card-body border-bottom text-center pb-3">
                            <div class="position-relative d-inline-block mb-3">
                                <img src="{{ $course->instructor->image === 'no-img.jpg' ? '/default-images/user/both.jpg' : $course->instructor->image }}"
                                    alt="instructor" class="rounded-circle"
                                    style="width:64px;height:64px;object-fit:cover;">
                                <span
                                    class="position-absolute bottom-0 end-0 bg-success rounded-circle border border-2 border-white d-flex align-items-center justify-content-center"
                                    style="width:18px;height:18px;">
                                    <i class="fe fe-check" style="font-size:9px;color:#fff;"></i>
                                </span>
                            </div>
                            <h5 class="mb-1 fw-semibold text-capitalize">
                                {{ $course->instructor->name ?? 'No Instructor' }}
                            </h5>
                            @if ($course->instructor->courses->isNotEmpty())
                                <p class="text-muted mb-2" style="font-size:12px;">
                                    {{ $course->instructor->courses->unique('title')->take(3)->pluck('title')->implode(' · ') }}
                                    ...
                                </p>
                            @endif
                            <div class="d-flex align-items-center justify-content-center gap-1" style="font-size:12px;">
                                <i class="fe fe-star text-warning" style="font-size:13px;"></i>
                                <span class="fw-semibold">4.5</span>
                                <span class="text-muted">instructor rating</span>
                            </div>
                        </div>
                        {{-- Stats row --}}
                        <div class="row g-0 border-bottom text-center">
                            <div class="col border-end py-3">
                                <h6 class="mb-0 fw-semibold">
                                    {{ $course->instructor->courses->sum(fn($c) => $c->enrollments->count()) }}
                                </h6>
                                <small class="text-muted">Students</small>
                            </div>
                            <div class="col border-end py-3">
                                <h6 class="mb-0 fw-semibold">{{ $course->instructor->courses->count() }}</h6>
                                <small class="text-muted">Courses</small>
                            </div>
                            <div class="col py-3">
                                <h6 class="mb-0 fw-semibold">12,230</h6>
                                <small class="text-muted">Reviews</small>
                            </div>
                        </div>
                        {{-- Bio + link --}}
                        <div class="card-body">
                            {{-- <p class="text-muted mb-3" style="font-size:13px;line-height:1.6;">
                                {{ $course->instructor->headline ?? 'No bio available for this instructor.' }}
   </p> --}}
                            <a href="instructor-profile.html"
                                class="d-inline-flex align-items-center gap-1 text-primary fw-semibold"
                                style="font-size:13px;text-decoration:none;">
                                View full profile <i class="fe fe-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card -->
            @if ($other_courses->isNotEmpty())
                <div class="pt-8 pb-3">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div style="width:4px;height:28px;background:#4f46e5;border-radius:2px;flex-shrink:0;"></div>
                        <div>
                            <p class="mb-0 text-muted text-uppercase fw-semibold"
                                style="font-size:11px;letter-spacing:.08em;">More from</p>
                            <h2 class="mb-0 fw-semibold text-capitalize" style="font-size:18px;">
                                {{ $course->instructor->name ?? 'No Instructor' }}
                            </h2>
                        </div>
                    </div>
                    <div class="row">
                        {{-- course cards ... --}}
                        <div class="row">
                            @forelse ($other_courses as $course)
                                <div class="col-lg-3 col-md-6 col-12">

                                    <!-- Card -->
                                    <div class="card mb-4 card-hover">
                                        <a href="{{ route('admin.courses.realtime.show', $course->id) }}"><img
                                                src="{{ $course->thumbnail === '' ? asset('/default-images/staff/no-course-img.png') : asset($course->thumbnail) }}"
                                                alt="course-thumbnail" class="card-img-top"></a>

                                        <!-- Card body -->
                                        <div class="card-body">
                                            <h4 class="mb-2 text-truncate-line-2">
                                                <a href="{{ route('admin.courses.realtime.show', $course->id) }}"
                                                    class="text-inherit text-capitalize">
                                                    {{ $course->title }}
                                                </a>
                                            </h4>
                                            <ul class="mb-3 list-inline">
                                                <li class="list-inline-item">
                                                    <span>
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="12"
                                                            height="12" fill="currentColor"
                                                            class="bi bi-clock align-baseline" viewBox="0 0 16 16">
                                                            <path
                                                                d="M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71V3.5z">
                                                            </path>
                                                            <path
                                                                d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm7-8A7 7 0 1 1 1 8a7 7 0 0 1 14 0z">
                                                            </path>
                                                        </svg>
                                                    </span>
                                                    <span>
                                                        {{ $course->duration }} Hours
                                                    </span>
                                                </li>
                                                <li class="list-inline-item">
                                                    <span>
                                                        <i class="fe fe-users align-middle me-1"></i>
                                                    </span>
                                                    <span>
                                                        {{ $course->enrollments->count() }} Enrolled
                                                    </span>
                                                </li>
                                            </ul>
                                            <div class="mt-3 d-flex align-baseline lh-1">
                                                <span class="fs-6">
                                                    @for ($i = 0; $i < 5; $i++)
                                                        <i class="fe fe-star text-warning"></i>
                                                    @endfor
                                                </span>
                                                <span class="text-warning mx-1">
                                                    4.5
                                                </span>
                                                <span class="fs-6">
                                                    (2,500)
                                                </span>
                                            </div>
                                        </div>

                                        <!-- Card footer -->
                                        <div class="card-footer">
                                            <div class="row align-items-center g-0">
                                                <div class="col-auto">
                                                    <img src="
                                            {{ $course->instructor->image === 'no-img.jpg' ? '/default-images/user/both.jpg' : $course->instructor->image }}
                                        "
                                                        class="rounded-circle avatar-xs" alt="avatar">
                                                </div>
                                                <div class="col ms-2">
                                                    <span class="text-capitalize">
                                                        {{ $course->instructor->name ?? 'No Instructor' }}
                                                    </span>
                                                </div>
                                                {{-- <div class="col-auto">
                                        <a href="#" class="text-reset bookmark">
                                            <i class="fe fe-bookmark fs-4"></i>
                                        </a>
                                    </div> --}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12">
                                    <div class="card mb-4">
                                        <div class="card-body text-center">
                                            <h4 class="mb-0">
                                                No other courses found for this instructor.
                                            </h4>
                                        </div>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>
@endsection
@push('scripts')
    <script src="/admin/assets/dist/libs/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const tabs = document.querySelectorAll('#tab a[data-bs-toggle="pill"]');
            // Restore active tab
            let activeTab = localStorage.getItem('activeTab');
            if (activeTab) {
                let triggerEl = document.querySelector(`#tab a[href="${activeTab}"]`);
                if (triggerEl) {
                    new bootstrap.Tab(triggerEl).show();
                }
            } else {
                // Default tab (optional)
                let defaultTab = document.querySelector('#tab a[href="#teacher-attendance"]');
                if (defaultTab) {
                    new bootstrap.Tab(defaultTab).show();
                }
            }
            // Save active tab on change
            tabs.forEach(tab => {
                tab.addEventListener('shown.bs.tab', function(event) {
                    localStorage.setItem('activeTab', event.target.getAttribute('href'));
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
        $('#date-range').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true,
        });
    </script>
@endpush
