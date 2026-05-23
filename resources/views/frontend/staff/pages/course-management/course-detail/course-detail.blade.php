@extends('frontend.staff.layout.master')
@section('page_title', isset($page_title) ? $page_title : 'Page Title Here')
@push('styles')
    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="/admin/assets/dist/libs/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-container--default .select2-selection--single {
            height: 42px;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            padding: 6px 10px;
            font-size: 14px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 42px;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 28px;
            color: #333;
        }

        .select2-dropdown {
            border-radius: 6px;
            border: 1px solid #dee2e6;
            font-size: 14px;
        }

        .select2-container {
            width: 100% !important;
        }
    </style>
    @include('frontend.staff.pages.course-management.course-detail.style.style')
    @include('frontend.staff.pages.course-management.course-detail.style.pills-student-attendance')
@endpush
@section('content')
    <div class="card bg-light-info shadow-none position-relative overflow-hidden">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-semibold mb-8">
                        Course Details
                    </h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a class="text-muted "
                                    href="{{ route('staff.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item" aria-current="page">
                                Course Management</li>
                            <li class="breadcrumb-item"><a class="text-muted " href="{{ route('staff.courses.index') }}">
                                    Courses
                                </a>
                            </li>
                            <li class="breadcrumb-item" aria-current="page">
                                {{ Str::limit($course->title, 30) }}
                            </li>
                        </ol>
                    </nav>
                </div>
                <div class="col-3">
                    <div class="text-center mb-n5">
                        <img src="{{ asset('/admin/assets/dist/images/breadcrumb/ChatBc.png') }}" alt=""
                            class="img-fluid mb-n4">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card overflow-hidden">
        <div class="card-body p-0">
            <img src="{{ asset($course->thumbnail == '' ? asset('/default-images/staff/no-course-img.png') : asset($course->thumbnail)) }}"
                alt="" class="img-fluid" style="width: 1200px; height: 200px; object-fit: cover;">
            <div class="row align-items-center">
                <div class="col-lg-6 order-lg-1 order-2">
                    <div class="d-flex align-items-center justify-content-around m-4">
                        <div class="text-center">
                            <i class="ti ti-user-check fs-6 d-block mb-2"></i>
                            <h4 class="mb-0 fw-semibold lh-1">
                                {{ $course->students->count() ?? 0 }}
                            </h4>
                            <p class="mb-0 fs-4">
                                Students
                            </p>
                        </div>
                        {{-- duration --}}
                        <div class="text-center">
                            <i class="ti ti-clock fs-6 d-block mb-2"></i>
                            <h4 class="mb-0 fw-semibold lh-1">
                                @if (request('from_date') && request('to_date'))
                                    {{ $course->filtered_hours ?? 'N/A' }}h
                                @else
                                    {{ $course->duration ?? 'N/A' }}h
                                @endif
                            </h4>
                            <p class="mb-0 fs-4">
                                Duration
                            </p>
                        </div>
                        {{-- session --}}
                        <div class="text-center">
                            <i class="ti ti-calendar-check fs-6 d-block mb-2"></i>
                            <h4 class="mb-0 fw-semibold lh-1">
                                @if (request('from_date') && request('to_date'))
                                    {{ $course->filtered_sessions ?? 'N/A' }}
                                @else
                                    {{ $course->completed_sessions ?? 0 }} / {{ $course->total_sessions ?? 0 }}
                                @endif
                            </h4>
                            <p class="mb-0 fs-4">
                                Sessions
                            </p>
                        </div>
                        {{-- Earning --}}
                        <div class="text-center">
                            <i class="ti ti-currency-dollar fs-6 d-block mb-2"></i>
                            <h4 class="mb-0 fw-semibold lh-1">
                                @if (request('from_date') && request('to_date'))
                                    ${{ $course->filtered_earnings ?? 'N/A' }}
                                @else
                                    ${{ $course->getRevenueAttribute() }}
                                @endif
                            </h4>
                            <p class="mb-0 fs-4">
                                Earning
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 mt-n3 order-lg-2 order-1">
                    <div class="mt-n5">
                        <div class="d-flex align-items-center justify-content-center mb-2">
                            <div class="linear-gradient d-flex align-items-center justify-content-center rounded-circle"
                                style="width: 110px; height: 110px;" ;="">
                                <div class=" border-4 border-white d-flex align-items-center justify-content-center rounded-circle overflow-hidden"
                                    style="width: 100px; height: 100px;" ;="">
                                    <img src="{{ asset($course->instructor->image == 'no-img.jpg' ? 'default-images/user/both.jpg' : $course->instructor->image) }}"
                                        alt="" class="w-100 h-100">
                                </div>
                            </div>
                        </div>
                        <div class="text-center">
                            <h5 class="fs-5 mb-0 fw-semibold">
                                {{-- title course --}}
                                {{ Str::limit($course->title, 30) }}
                            </h5>
                            <p class="mb-0 fs-4 text-capitalize">
                                {{-- instructor name --}}
                                {{ $course->instructor->name ?? 'No Instructor' }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 order-last">
                    <ul
                        class="list-unstyled d-flex align-items-center justify-content-center justify-content-lg-start my-3 gap-3">
                        <li class="position-relative">
                            <a class="text-white d-flex align-items-center justify-content-center bg-primary p-2 fs-4 rounded-circle"
                                href="javascript:void(0)" width="30" height="30">
                                <i class="ti ti-brand-facebook"></i>
                            </a>
                        </li>
                        <li class="position-relative">
                            <a class="text-white bg-secondary d-flex align-items-center justify-content-center p-2 fs-4 rounded-circle "
                                href="javascript:void(0)">
                                <i class="ti ti-brand-twitter"></i>
                            </a>
                        </li>
                        <li class="position-relative">
                            <a class="text-white bg-secondary d-flex align-items-center justify-content-center p-2 fs-4 rounded-circle "
                                href="javascript:void(0)">
                                <i class="ti ti-brand-dribbble"></i>
                            </a>
                        </li>
                        <li class="position-relative">
                            <a class="text-white bg-danger d-flex align-items-center justify-content-center p-2 fs-4 rounded-circle "
                                href="javascript:void(0)">
                                <i class="ti ti-brand-youtube"></i>
                            </a>
                        </li>
                        <li>
                            <a class="btn btn-primary" href="{{ route('staff.courses.edit', $course->id) }}">
                                <i class="ti ti-edit me-2 fs-4"></i>
                                Edit Course
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            @include('frontend.staff.pages.course-management.course-detail.partials.tab')
        </div>
    </div>
    <div class="tab-content" id="pills-tabContent">
        @include('frontend.staff.pages.course-management.course-detail.partials.tab-contents.pills-students')
        {{-- @include('frontend.staff.pages.course-management.course-detail.partials.tab-contents.pills-attendance') --}}
        <div class="tab-pane fade" id="pills-attendance" role="tabpanel" aria-labelledby="pills-attendance-tab"
            tabindex="0">
            <div class="row">
                <div class="col-12">
                    <div class="sheet">

                        <!-- Header -->
                        <div class="top-header">
                            <div class="logo">
                                <i class="ti ti-calendar-check fs-12 me-2"></i>
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

                                            <span class="input-group-text bg-primary text-white px-2 px-md-3">
                                                TO
                                            </span>

                                            <input type="text" class="form-control" name="to_date" placeholder="To"
                                                value="{{ request('to_date', now()->endOfMonth()->format('Y-m-d')) }}">
                                        </div>
                                    </div>

                                    <!-- Buttons -->
                                    <div class="col-12 col-md-6 col-lg-5">
                                        <div class="d-flex flex-column flex-md-row gap-2">

                                            <button class="btn btn-primary w-100">
                                                <i class="ti ti-search me-1"></i> Filter
                                            </button>

                                            <a href="{{ route('staff.courses.show', $course->id) }}"
                                                class="btn btn-outline-secondary w-100">
                                                Reset
                                            </a>

                                        </div>
                                    </div>

                                </div>

                            </div>
                        </form>

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
                                            $start = \Carbon\Carbon::parse($course->schedule->start_time)->format(
                                                'g:i ',
                                            );
                                            $end = \Carbon\Carbon::parse($course->schedule->end_time)->format('g:i A');
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
                        <form action="{{ route('staff.teacher.attendance.update') }}" method="POST">
                            @csrf
                            <input type="hidden" name="course_id" value="{{ $course->id }}">
                            <input type="hidden" name="teacher_id" value="{{ $course->instructor->id ?? '' }}">
                            <input type="hidden" name="schedule_id" value="{{ $course->schedule->id ?? '' }}">
                            <table id="attendanceTable">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Date</th>
                                        <th>Time in</th>
                                        <th>Time out</th>
                                        <th>T H</th>
                                        <th>A T H</th>
                                        <th>Room</th>
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
                                                    <input type="hidden" name="attendances[{{ $index }}][id]"
                                                        value="{{ $attendance->id }}">
                                                </td>

                                                <td>
                                                    <input type="date" name="attendances[{{ $index }}][date]"
                                                        value="{{ $attendance->date }}" class="form-control text-dark"
                                                        readonly>
                                                </td>

                                                <td>
                                                    <input type="time"
                                                        name="attendances[{{ $index }}][start_time]"
                                                        value="{{ \Carbon\Carbon::parse($attendance->start_time)->format('H:i') }}"
                                                        class="form-control text-dark">
                                                </td>

                                                <td>
                                                    <input type="time"
                                                        name="attendances[{{ $index }}][end_time]"
                                                        value="{{ \Carbon\Carbon::parse($attendance->end_time)->format('H:i') }}"
                                                        class="form-control text-dark">
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

                                                <td>
                                                    <input type="text" name="attendances[{{ $index }}][room]"
                                                        value="{{ $attendance->room }}"
                                                        class="form-control text-uppercase text-dark text-center">
                                                </td>

                                                <td>
                                                    <input type="number"
                                                        name="attendances[{{ $index }}][late_minutes]"
                                                        value="{{ $attendance->late_minutes }}"
                                                        class="form-control text-center">
                                                </td>
                                                <td>
                                                    <input type="text"
                                                        name="attendances[{{ $index }}][late_reason]"
                                                        value="{{ $attendance->late_reason }}" class="form-control">
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    @php
                                        $nextIndex = $attendances->count();
                                    @endphp

                                    <tr>
                                        <td style="padding: 10px">{{ $nextIndex + 1 }}</td>

                                        <td style="display:none;">
                                            <input type="hidden" name="attendances[{{ $nextIndex }}][id]"
                                                value="">
                                        </td>

                                        <td>
                                            <input type="date" name="attendances[{{ $nextIndex }}][date]"
                                                class="form-control" value="{{ now()->format('Y-m-d') }}" readonly>
                                        </td>

                                        <td>
                                            <input type="time" name="attendances[{{ $nextIndex }}][start_time]"
                                                class="form-control">
                                        </td>

                                        <td>
                                            <input type="time" name="attendances[{{ $nextIndex }}][end_time]"
                                                class="form-control">
                                        </td>

                                        <td>
                                            <input type="text" name="attendances[{{ $nextIndex }}][total_hours]"
                                                class="form-control total-hours" readonly>
                                        </td>

                                        <td>
                                            <input type="text" name="attendances[{{ $nextIndex }}][actual_hours]"
                                                class="form-control actual-hours" readonly>
                                        </td>

                                        <td>
                                            <input type="text" name="attendances[{{ $nextIndex }}][room]"
                                                class="form-control">
                                        </td>

                                        <td>
                                            <input type="number" name="attendances[{{ $nextIndex }}][late_minutes]"
                                                class="form-control">
                                        </td>

                                        <td>
                                            <input type="text" name="attendances[{{ $nextIndex }}][late_reason]"
                                                class="form-control">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <button type="submit" class="btn btn-primary mt-3">
                                Save Attendance
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Student's Attendant --}}
        <div class="tab-pane fade " id="pills-student-attendance" role="tabpanel">
            @php
                $data = $attendanceData;
                $dates = array_slice($data['table_structure']['columns'], 5);
            @endphp

            <div class="attendance-wrapper mt-4">

                <!-- HEADER CARD -->
                <div class="attendance-header-card">
                    <div class="row">
                        <div class="col-md-3"><strong>Start:</strong> {{ $data['form_metadata']['class_start'] }}</div>
                        <div class="col-md-3"><strong>Room:</strong> {{ $data['form_metadata']['room'] }}</div>
                        <div class="col-md-3"><strong>Lecturer:</strong> {{ $data['form_metadata']['lecturer_name'] }}
                        </div>
                        <div class="col-md-3"><strong>Phone:</strong>
                            {{ $data['form_metadata']['lecturer_phone'] ?? '-' }}
                        </div>
                    </div>
                </div>

                <!-- TABLE -->
                <div class="attendance-table-container">
                    <table class="attendance-table">

                        <!-- TITLE -->
                        <thead>
                            <tr class="title-row">
                                <th colspan="{{ count($data['table_structure']['columns']) }}">
                                    {{ $data['form_metadata']['class_title'] }}
                                </th>
                            </tr>

                            <!-- HEADER -->
                            <tr class="header-row">
                                @foreach ($data['table_structure']['columns'] as $col)
                                    <th>{{ $col }}</th>
                                @endforeach
                            </tr>
                        </thead>

                        <!-- BODY -->
                        <tbody>
                            @foreach ($data['table_structure']['data_rows'] as $row)
                                <tr>
                                    <td>{{ $row['no'] }}</td>
                                    <td class="text-start fw-semibold">{{ $row['student_name'] }}</td>
                                    <td>{{ $row['sex'] }}</td>
                                    <td>{{ $row['day'] }}</td>
                                    <td>{{ $row['shift'] }}</td>

                                    @foreach ($dates as $date)
                                        @php
                                            $status = $row['attendance'][$date] ?? '';
                                        @endphp

                                        <td
                                            class="status-cell
                                                {{ $status == 'P' ? 'status-present' : '' }}
                                                {{ $status == 'A' ? 'status-absent' : '' }}
                                                {{ $status == 'L' ? 'status-late' : '' }}
                                            ">
                                            {{ $status }}
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
        {{-- student report --}}
        {{-- Student Report Tab --}}
        <div class="tab-pane fade" id="pills-student-report" role="tabpanel">
            @php
                $totalStudents = $course->studentReports->count();
                $passed = $course->studentReports->where('result', 'pass')->count();
                $failed = $course->studentReports->where('result', 'fail')->count();
                $avgScore = $totalStudents > 0 ? round($course->studentReports->avg('total_score')) : 0;

                $avatarBg = [
                    'bg-light-primary',
                    'bg-light-warning',
                    'bg-light-info',
                    'bg-light-danger',
                    'bg-light-success',
                ];
                $avatarText = ['text-primary', 'text-warning', 'text-info', 'text-danger', 'text-success'];
            @endphp

            <div class="card overflow-hidden">

                {{-- ── HEADER BANNER ── --}}
                <div class="card-body bg-primary pb-0">

                    <div class="d-flex align-items-start justify-content-between mb-3">
                        <div>
                            <span class="badge bg-white text-primary fw-semibold fs-2 mb-2">
                                <i class="ti ti-file-certificate me-1"></i> Student Report
                            </span>
                            <h4 class="text-white fw-semibold mb-1">{{ $course->title }}</h4>
                            <p class="fs-3 mb-0" style="color:rgba(255,255,255,0.75);">
                                ICT Professional Training Center
                            </p>
                        </div>
                    </div>

                    {{-- Meta info chips --}}
                    <div class="d-flex flex-wrap gap-2 mb-4">
                        <span class="badge fw-normal fs-2 py-2 px-3"
                            style="background:rgba(255,255,255,0.15);color:rgba(255,255,255,0.9);border:1px solid rgba(255,255,255,0.2);">
                            <i class="ti ti-user me-1"></i>{{ $course->instructor->name ?? 'N/A' }}
                        </span>
                        <span class="badge fw-normal fs-2 py-2 px-3"
                            style="background:rgba(255,255,255,0.15);color:rgba(255,255,255,0.9);border:1px solid rgba(255,255,255,0.2);">
                            <i class="ti ti-door me-1"></i>Room: {{ $course->room ?? 'N/A' }}
                        </span>
                        @if ($course->schedule)
                            @php
                                $days = collect(explode('-', $course->schedule->study_day))
                                    ->map(fn($d) => ucfirst($d))
                                    ->implode(' • ');
                                $start = \Carbon\Carbon::parse($course->schedule->start_time)->format('g:i');
                                $end = \Carbon\Carbon::parse($course->schedule->end_time)->format('g:i A');
                                $shift = ucfirst($course->schedule->shift);
                            @endphp
                            <span class="badge fw-normal fs-2 py-2 px-3"
                                style="background:rgba(255,255,255,0.15);color:rgba(255,255,255,0.9);border:1px solid rgba(255,255,255,0.2);">
                                <i class="ti ti-calendar me-1"></i>{{ $days }}
                            </span>
                            <span class="badge fw-normal fs-2 py-2 px-3"
                                style="background:rgba(255,255,255,0.15);color:rgba(255,255,255,0.9);border:1px solid rgba(255,255,255,0.2);">
                                <i class="ti ti-clock me-1"></i>{{ $shift }} ({{ $start }} –
                                {{ $end }})
                            </span>
                        @endif
                    </div>



                </div>{{-- /.card-body bg-primary --}}

                {{-- ── TABLE ── --}}
                <div class="card-body p-0 mt-3">
                    <div class="table-responsive">
                        <table class="table align-middle text-nowrap mb-0">
                            <thead>
                                <tr class="text-muted fw-semibold">
                                    <th class="ps-4" style="width:48px;">#</th>
                                    <th>Name</th>
                                    <th class="text-center text-success">P</th>
                                    <th class="text-center text-danger">A</th>
                                    {{-- <th class="text-center text-warning">AP</th> --}}
                                    <th class="text-center">Assignment ( 30% ) </th>
                                    <th class="text-center">Mini Project ( 20% )</th>
                                    <th class="text-center">Final Project ( 40% ) </th>
                                    <th class="text-center">Total ( 100% ) </th>
                                    <th class="text-center pe-4">Result</th>
                                </tr>
                            </thead>
                            <tbody class="border-top">
                                @forelse ($course->studentReports as $i => $report)
                                    @php
                                        $idx = $i % count($avatarBg);
                                        $initials = collect(explode(' ', $report->student->name))
                                            ->take(2)
                                            ->map(fn($w) => strtoupper($w[0]))
                                            ->implode('');
                                        $isFail = $report->result === 'fail';
                                        $assignPct = min(100, (int) (($report->assignment_score / 30) * 100));
                                        $miniPct = min(100, (int) (($report->mini_project_score / 20) * 100));
                                        $finalPct = min(100, (int) (($report->final_project_score / 40) * 100));
                                        $totalPct = min(100, (int) $report->total_score);
                                        $barClass = $isFail ? 'bg-danger' : '';
                                        $trackClass = $isFail ? 'bg-light-danger' : 'bg-light-primary';
                                    @endphp
                                    <tr>
                                        {{-- No --}}
                                        <td class="ps-4 text-muted fs-2">{{ $i + 1 }}</td>

                                        {{-- Name --}}
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="d-flex align-items-center justify-content-center rounded-circle flex-shrink-0 {{ $avatarBg[$idx] }}"
                                                    style="width:35px;height:35px;">
                                                    <span class="fs-2 fw-semibold {{ $avatarText[$idx] }}">
                                                        {{ $initials }}
                                                    </span>
                                                </div>
                                                <h6 class="mb-0 fw-semibold fs-3">{{ $report->student->name }}</h6>
                                            </div>
                                        </td>

                                        {{-- Attendance --}}
                                        <td class="text-center">
                                            <span class="fw-semibold text-success fs-3">{{ $report->present }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="fw-semibold text-danger fs-3">{{ $report->absent }}</span>
                                        </td>
                                        {{-- <td class="text-center">
                                            <span class="fw-semibold text-warning fs-3">{{ $report->permission }}</span>
                                        </td> --}}

                                        {{-- Assignment --}}
                                        <td class="text-center" style="min-width:90px;">
                                            <p class="mb-1 fs-3 fw-semibold">
                                                {{ number_format($report->assignment_score) }}</p>
                                            <div class="progress {{ $trackClass }}" style="height:4px;">
                                                <div class="progress-bar {{ $barClass }}"
                                                    style="width:{{ $assignPct }}%"></div>
                                            </div>
                                        </td>

                                        {{-- Mini Project --}}
                                        <td class="text-center" style="min-width:90px;">
                                            <p class="mb-1 fs-3 fw-semibold">
                                                {{ number_format($report->mini_project_score) }}</p>
                                            <div class="progress {{ $trackClass }}" style="height:4px;">
                                                <div class="progress-bar {{ $barClass }}"
                                                    style="width:{{ $miniPct }}%"></div>
                                            </div>
                                        </td>

                                        {{-- Final Project --}}
                                        <td class="text-center" style="min-width:90px;">
                                            <p class="mb-1 fs-3 fw-semibold">
                                                {{ number_format($report->final_project_score) }}</p>
                                            <div class="progress {{ $trackClass }}" style="height:4px;">
                                                <div class="progress-bar {{ $barClass }}"
                                                    style="width:{{ $finalPct }}%"></div>
                                            </div>
                                        </td>

                                        {{-- Total --}}
                                        <td class="text-center" style="min-width:90px;">
                                            <h6
                                                class="mb-1 fw-semibold fs-5 {{ $isFail ? 'text-danger' : 'text-primary' }}">
                                                {{ number_format($report->total_score, 0) }}
                                            </h6>
                                            <div class="progress {{ $trackClass }}" style="height:4px;">
                                                <div class="progress-bar {{ $barClass }}"
                                                    style="width:{{ $totalPct }}%"></div>
                                            </div>
                                        </td>

                                        {{-- Result --}}
                                        <td class="text-center pe-4">
                                            @if (!$isFail)
                                                <span class="badge bg-light-success text-success fw-semibold py-1 px-3">
                                                    <i class="ti ti-check me-1"></i>Pass
                                                </span>
                                            @else
                                                <span class="badge bg-light-danger text-danger fw-semibold py-1 px-3">
                                                    <i class="ti ti-x me-1"></i>Fail
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center py-5 text-muted">
                                            <i class="ti ti-inbox fs-6 d-block mb-2"></i>
                                            <span class="fs-3">No student reports found.</span>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- ── FOOTER SIGNATURES ── --}}
                <div class="card-footer bg-light mt-2">
                    <div class="row text-center py-3">
                        <div class="col-6 border-end">
                            <p class="text-muted fs-3 mb-5">Seen and Approved by</p>
                            <div class="border-top mx-auto mb-2" style="width:60%;"></div>
                            <h6 class="fw-semibold fs-3 mb-0">ICT Training Center</h6>
                        </div>
                        <div class="col-6">
                            <p class="text-muted fs-3 mb-5">Prepared by</p>
                            <div class="border-top mx-auto mb-2" style="width:60%;"></div>
                            <h6 class="fw-semibold fs-3 mb-0 text-capitalize">
                                Teacher: {{ $course->instructor->name ?? 'N/A' }}
                            </h6>
                        </div>
                    </div>
                </div>
            </div>{{-- /.card --}}
        </div>{{-- /.tab-pane --}}
    </div>
@endsection
@push('scripts')
    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="/admin/assets/dist/libs/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
    <script>
        // Select2 CDN
        if (typeof $.fn.select2 === 'undefined') {
            const s2 = document.createElement('script');
            s2.src = 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js';
            s2.onload = () => initSelect2();
            document.head.appendChild(s2);
        } else {
            initSelect2();
        }

        function initSelect2() {
            $('#targetCourseSelect').select2({
                dropdownParent: $('#moveCourseModal'),
                placeholder: '— Choose course —',
                allowClear: true,
                width: '100%',
            });
        }

        // TAB STATE PERSISTENCE
        document.addEventListener("DOMContentLoaded", function() {

            const tabButtons = document.querySelectorAll('#pills-tab button[data-bs-toggle="pill"]');

            // 🔹 Set default tab (Teacher's Attendance)
            const defaultTab = '#pills-attendance';

            // 🔹 Get saved tab or fallback to default
            let activeTab = localStorage.getItem('activeTab') || defaultTab;

            let triggerEl = document.querySelector(`#pills-tab button[data-bs-target="${activeTab}"]`);

            if (triggerEl) {
                let tab = new bootstrap.Tab(triggerEl);
                tab.show();
            }

            // 🔹 Save clicked tab
            tabButtons.forEach(button => {
                button.addEventListener('shown.bs.tab', function(event) {
                    let target = event.target.getAttribute('data-bs-target');
                    localStorage.setItem('activeTab', target);
                });
            });

        });


        // DATEPICKER
        $('#date-range').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true,
        });

        // TEACHER ATTENDANCE CALCULATION
        let attendanceIndex = {{ $course->teacherAttendances->count() }};

        function formatDate(date) {
            return date.toISOString().split('T')[0];
        }

        function calculateRow(row) {
            const start = row.querySelector('input[name*="[start_time]"]');
            const end = row.querySelector('input[name*="[end_time]"]');
            const total = row.querySelector('.total-hours');
            const actual = row.querySelector('.actual-hours');

            function calculate() {
                if (start.value && end.value) {
                    const startTime = new Date(`1970-01-01T${start.value}:00`);
                    const endTime = new Date(`1970-01-01T${end.value}:00`);

                    let diff = (endTime - startTime) / (1000 * 60 * 60);

                    if (diff < 0) diff += 24;

                    total.value = diff.toFixed(2);

                    // Calculate ATH (cumulative)
                    let prevRow = row.previousElementSibling;
                    let prevATH = 0;
                    if (prevRow) {
                        const prevActual = prevRow.querySelector('.actual-hours');
                        if (prevActual && prevActual.value) {
                            prevATH = parseFloat(prevActual.value) || 0;
                        }
                    }
                    actual.value = (prevATH + diff).toFixed(2);
                }
            }
            start.addEventListener('change', calculate);
            end.addEventListener('change', calculate);
        } // Apply to all rows


        document.querySelectorAll('#attendanceTable tbody tr').forEach(row => {
            calculateRow(row);
        });
    </script>
@endpush
