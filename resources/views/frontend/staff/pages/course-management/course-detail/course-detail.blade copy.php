@extends('frontend.staff.layout.master')
@section('page_title', isset($page_title) ? $page_title : 'Page Title Here')
@push('styles')
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
    {{-- ── Breadcrumb ── --}}
    <div class="card bg-light-info shadow-none position-relative overflow-hidden mb-4">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-semibold mb-8">Course Details</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a class="text-muted"
                                    href="{{ route('staff.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item text-muted" aria-current="page">Course Management</li>
                            <li class="breadcrumb-item"><a class="text-muted"
                                    href="{{ route('staff.courses.index') }}">Courses</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($course->title, 30) }}</li>
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
    {{-- ══════════════════════════════════════
     MAIN COURSE CARD
══════════════════════════════════════ --}}
    <div class="card overflow-hidden">
        <div class="card-body p-0">
            {{-- ── Thumbnail with overlaid avatar + Edit button ── --}}
            <div class="position-relative">
                <img src="{{ asset($course->thumbnail == '' ? '/default-images/staff/no-course-img.png' : $course->thumbnail) }}"
                    alt="{{ $course->title }}" class="img-fluid w-100" style="height:200px; object-fit:cover;">
                {{-- Dark gradient at bottom so overlays are readable --}}
                <div class="position-absolute bottom-0 start-0 end-0"
                    style="height:100px; background:linear-gradient(to top, rgba(0,0,0,.55), transparent); pointer-events:none;">
                </div>
                {{-- Status badge — top left --}}
                @if ($course->status == 'active')
                    <span class="badge bg-success position-absolute top-0 start-0 m-3 fs-2">OPEN</span>
                @else
                    <span class="badge bg-secondary position-absolute top-0 start-0 m-3 fs-2">CLOSED</span>
                @endif
                {{-- Edit Course — top right --}}
                <div class="position-absolute top-0 end-0 m-3">
                    <a href="{{ route('staff.courses.edit', $course->id) }}"
                        class="btn btn-primary btn-sm d-flex align-items-center gap-1">
                        <i class="ti ti-edit fs-4"></i> Edit Course
                    </a>
                </div>
                {{-- Instructor avatar — centered, overlapping bottom edge --}}
                <div class="position-absolute bottom-0 start-50 translate-middle-x mb-n5" style="z-index:5;">
                    <div class="linear-gradient d-flex align-items-center justify-content-center rounded-circle"
                        style="width:80px; height:80px;">
                        <div class="border border-4 border-white d-flex align-items-center justify-content-center rounded-circle overflow-hidden"
                            style="width:72px; height:72px;">
                            <img src="{{ asset($course->instructor->image == 'no-img.jpg' ? 'default-images/user/both.jpg' : $course->instructor->image) }}"
                                alt="{{ $course->instructor->name }}" class="w-100 h-100" style="object-fit:cover;">
                        </div>
                    </div>
                </div>
            </div>
            {{-- ── Instructor name + socials ── --}}
            <div class="row align-items-center mt-5 pt-2 pb-2 px-4">
                {{-- Spacer col to keep layout balanced on left --}}
                <div class="col-lg-4 order-lg-1 order-2">
                    <ul
                        class="list-unstyled d-flex align-items-center justify-content-center justify-content-lg-start gap-3 mb-0">
                        <li><a class="text-white d-flex align-items-center justify-content-center bg-primary p-2 fs-4 rounded-circle"
                                href="javascript:void(0)"><i class="ti ti-brand-facebook"></i></a></li>
                        <li><a class="text-white bg-secondary d-flex align-items-center justify-content-center p-2 fs-4 rounded-circle"
                                href="javascript:void(0)"><i class="ti ti-brand-twitter"></i></a></li>
                        <li><a class="text-white bg-secondary d-flex align-items-center justify-content-center p-2 fs-4 rounded-circle"
                                href="javascript:void(0)"><i class="ti ti-brand-dribbble"></i></a></li>
                        <li><a class="text-white bg-danger d-flex align-items-center justify-content-center p-2 fs-4 rounded-circle"
                                href="javascript:void(0)"><i class="ti ti-brand-youtube"></i></a></li>
                    </ul>
                </div>
                {{-- Center: name --}}
                <div class="col-lg-4 order-lg-2 order-1 text-center">
                    <h5 class="fs-5 mb-0 fw-semibold">{{ Str::limit($course->title, 30) }}</h5>
                    <p class="mb-0 fs-4 text-capitalize text-muted">{{ $course->instructor->name ?? 'No Instructor' }}</p>
                </div>
                {{-- Right: stats --}}
                <div class="col-lg-4 order-last">
                    <div class="d-flex align-items-center justify-content-center justify-content-lg-end gap-4">
                        <div class="text-center">
                            <i class="ti ti-user-check fs-6 d-block mb-1"></i>
                            <h4 class="mb-0 fw-semibold lh-1">{{ $course->students->count() ?? 0 }}</h4>
                            <p class="mb-0 fs-4 text-muted">Students</p>
                        </div>
                        <div class="text-center">
                            <i class="ti ti-clock fs-6 d-block mb-1"></i>
                            <h4 class="mb-0 fw-semibold lh-1">
                                @if (request('from_date') && request('to_date'))
                                    {{ $course->filtered_hours ?? 'N/A' }}h
                                @else
                                    {{ $course->duration ?? 'N/A' }}h
                                @endif
                            </h4>
                            <p class="mb-0 fs-4 text-muted">Duration</p>
                        </div>
                        <div class="text-center">
                            <i class="ti ti-calendar-check fs-6 d-block mb-1"></i>
                            <h4 class="mb-0 fw-semibold lh-1">
                                @if (request('from_date') && request('to_date'))
                                    {{ $course->filtered_sessions ?? 'N/A' }}
                                @else
                                    {{ $course->completed_sessions ?? 0 }}/{{ $course->total_sessions ?? 0 }}
                                @endif
                            </h4>
                            <p class="mb-0 fs-4 text-muted">Sessions</p>
                        </div>
                        <div class="text-center">
                            <i class="ti ti-currency-dollar fs-6 d-block mb-1"></i>
                            <h4 class="mb-0 fw-semibold lh-1">
                                @if (request('from_date') && request('to_date'))
                                    ${{ $course->filtered_earnings ?? 'N/A' }}
                                @else
                                    ${{ $course->getRevenueAttribute() }}
                                @endif
                            </h4>
                            <p class="mb-0 fs-4 text-muted">Earning</p>
                        </div>
                    </div>
                </div>
            </div>
            {{-- ── Tabs ── --}}
            @include('frontend.staff.pages.course-management.course-detail.partials.tab')
        </div>
    </div>
    {{-- ══════════════════════════════════════
     TAB CONTENT
══════════════════════════════════════ --}}
    <div class="tab-content mt-4" id="pills-tabContent">
        {{-- Students --}}
        @include('frontend.staff.pages.course-management.course-detail.partials.tab-contents.pills-students')
        {{-- ── Teacher Attendance ── --}}
        <div class="tab-pane fade" id="pills-attendance" role="tabpanel" tabindex="0">
            <div class="card">
                <div class="card-body">
                    {{-- Sheet header --}}
                    <div class="top-header">
                        <div class="logo">
                            <i class="ti ti-calendar-check fs-12 me-2"></i>
                        </div>
                        <div>
                            <div class="title">ICT Professional Training Center</div>
                            <div class="sub-title">Teacher's Attendance</div>
                        </div>
                    </div>
                    {{-- Filter --}}
                    <form method="GET" class="mb-4">
                        <div class="p-3 rounded-3 bg-light border">
                            <div class="row g-2 align-items-end">
                                <div class="col-12 col-md-7">
                                    <label class="form-label fw-semibold mb-1">
                                        <i class="ti ti-calendar me-1"></i> Date Range
                                    </label>
                                    <div class="input-daterange input-group" id="date-range">
                                        <input type="text" class="form-control" name="from_date" placeholder="From"
                                            value="{{ request('from_date', now()->startOfMonth()->format('Y-m-d')) }}">
                                        <span class="input-group-text bg-primary text-white px-3">TO</span>
                                        <input type="text" class="form-control" name="to_date" placeholder="To"
                                            value="{{ request('to_date', now()->endOfMonth()->format('Y-m-d')) }}">
                                    </div>
                                </div>
                                <div class="col-12 col-md-5">
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-primary w-100"><i
                                                class="ti ti-search me-1"></i>Filter</button>
                                        <a href="{{ route('staff.courses.show', $course->id) }}"
                                            class="btn btn-outline-secondary w-100">Reset</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    {{-- Info rows --}}
                    <div class="info-row">
                        <div class="info-label">Teacher's Name:</div>
                        <div class="info-value text-capitalize text-center">
                            <strong>{{ $course->instructor->name ?? 'No Instructor' }}</strong>
                        </div>
                    </div>
                    <div class="info-row highlight">
                        <div class="info-label">Subject:</div>
                        <div class="info-value text-capitalize text-center">
                            <strong>
                                {{ $course->title }}
                                @if ($course->schedule)
                                    @php
                                        $days = collect(explode('-', $course->schedule->study_day))
                                            ->map(fn($d) => ucfirst($d))
                                            ->implode(' • ');
                                        $start = \Carbon\Carbon::parse($course->schedule->start_time)->format('g:i');
                                        $end = \Carbon\Carbon::parse($course->schedule->end_time)->format('g:i A');
                                        $shift = ucfirst($course->schedule->shift);
                                    @endphp
                                    | {{ $days }} | {{ $shift }}
                                    ({{ $start }}–{{ $end }})
                                @else
                                    <span class="text-muted">No schedule</span>
                                @endif
                            </strong>
                        </div>
                    </div>
                    {{-- Table --}}
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
                                    <th>នាទីខ្វះ</th>
                                    <th>Note</th>
                                </tr>
                            </thead>
                            <tbody id="attendanceBody">
                                @php $attendances = $course->teacherAttendances; @endphp
                                @foreach ($attendances as $index => $attendance)
                                    <tr>
                                        <td style="padding:10px">{{ $index + 1 }}</td>
                                        <td style="display:none;"><input type="hidden"
                                                name="attendances[{{ $index }}][id]"
                                                value="{{ $attendance->id }}"></td>
                                        <td><input type="date" name="attendances[{{ $index }}][date]"
                                                value="{{ $attendance->date }}" class="form-control text-dark" readonly>
                                        </td>
                                        <td><input type="time" name="attendances[{{ $index }}][start_time]"
                                                value="{{ \Carbon\Carbon::parse($attendance->start_time)->format('H:i') }}"
                                                class="form-control text-dark"></td>
                                        <td><input type="time" name="attendances[{{ $index }}][end_time]"
                                                value="{{ \Carbon\Carbon::parse($attendance->end_time)->format('H:i') }}"
                                                class="form-control text-dark"></td>
                                        <td><input type="text" name="attendances[{{ $index }}][total_hours]"
                                                value="{{ number_format($attendance->total_hours) }}"
                                                class="form-control total-hours text-center text-dark" readonly></td>
                                        <td><input type="text" name="attendances[{{ $index }}][actual_hours]"
                                                value="{{ number_format($attendance->actual_hours) }}"
                                                class="form-control actual-hours text-center text-dark" readonly></td>
                                        <td><input type="text" name="attendances[{{ $index }}][room]"
                                                value="{{ $attendance->room }}"
                                                class="form-control text-uppercase text-center text-dark"></td>
                                        <td><input type="number" name="attendances[{{ $index }}][late_minutes]"
                                                value="{{ $attendance->late_minutes }}" class="form-control text-center">
                                        </td>
                                        <td><input type="text" name="attendances[{{ $index }}][late_reason]"
                                                value="{{ $attendance->late_reason }}" class="form-control"></td>
                                    </tr>
                                @endforeach
                                @php $nextIndex = $attendances->count(); @endphp
                                <tr>
                                    <td style="padding:10px">{{ $nextIndex + 1 }}</td>
                                    <td style="display:none;"><input type="hidden"
                                            name="attendances[{{ $nextIndex }}][id]" value=""></td>
                                    <td><input type="date" name="attendances[{{ $nextIndex }}][date]"
                                            value="{{ now()->format('Y-m-d') }}" class="form-control" readonly></td>
                                    <td><input type="time" name="attendances[{{ $nextIndex }}][start_time]"
                                            class="form-control"></td>
                                    <td><input type="time" name="attendances[{{ $nextIndex }}][end_time]"
                                            class="form-control"></td>
                                    <td><input type="text" name="attendances[{{ $nextIndex }}][total_hours]"
                                            class="form-control total-hours" readonly></td>
                                    <td><input type="text" name="attendances[{{ $nextIndex }}][actual_hours]"
                                            class="form-control actual-hours" readonly></td>
                                    <td><input type="text" name="attendances[{{ $nextIndex }}][room]"
                                            class="form-control text-uppercase text-center"></td>
                                    <td><input type="number" name="attendances[{{ $nextIndex }}][late_minutes]"
                                            class="form-control"></td>
                                    <td><input type="text" name="attendances[{{ $nextIndex }}][late_reason]"
                                            class="form-control"></td>
                                </tr>
                            </tbody>
                        </table>
                        <button type="submit" class="btn btn-primary mt-3">
                            <i class="ti ti-device-floppy me-1"></i> Save Attendance
                        </button>
                    </form>
                </div>
            </div>
        </div>
        {{-- ── Student Attendance ── --}}
        <div class="tab-pane fade" id="pills-student-attendance" role="tabpanel">
            @php
                $data = $attendanceData;
                $dates = array_slice($data['table_structure']['columns'], 5);
            @endphp
            <div class="attendance-wrapper mt-4">
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
                <div class="attendance-table-container">
                    <table class="attendance-table">
                        <thead>
                            <tr class="title-row">
                                <th colspan="{{ count($data['table_structure']['columns']) }}">
                                    {{ $data['form_metadata']['class_title'] }}
                                </th>
                            </tr>
                            <tr class="header-row">
                                @foreach ($data['table_structure']['columns'] as $col)
                                    <th>{{ $col }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data['table_structure']['data_rows'] as $row)
                                <tr>
                                    <td>{{ $row['no'] }}</td>
                                    <td class="text-start fw-semibold">{{ $row['student_name'] }}</td>
                                    <td>{{ $row['sex'] }}</td>
                                    <td>{{ $row['day'] }}</td>
                                    <td>{{ $row['shift'] }}</td>
                                    @foreach ($dates as $date)
                                        @php $status = $row['attendance'][$date] ?? ''; @endphp
                                        <td
                                            class="status-cell {{ $status == 'P' ? 'status-present' : ($status == 'A' ? 'status-absent' : ($status == 'L' ? 'status-late' : '')) }}">
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
        {{-- ── Student Report ── --}}
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
                {{-- Header banner --}}
                <div class="card-body bg-primary pb-0">
                    <div class="d-flex align-items-start justify-content-between mb-3">
                        <div>
                            <span class="badge bg-white text-primary fw-semibold fs-2 mb-2">
                                <i class="ti ti-file-certificate me-1"></i> Student Report
                            </span>
                            <h4 class="text-white fw-semibold mb-1">{{ $course->title }}</h4>
                            <p class="fs-3 mb-0" style="color:rgba(255,255,255,0.75);">ICT Professional Training Center
                            </p>
                        </div>
                    </div>
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
                                <i class="ti ti-clock me-1"></i>{{ $shift }}
                                ({{ $start }}–{{ $end }})
                            </span>
                        @endif
                    </div>
                </div>
                {{-- Table --}}
                <div class="card-body p-0 mt-3">
                    <div class="table-responsive">
                        <table class="table align-middle text-nowrap mb-0">
                            <thead>
                                <tr class="text-muted fw-semibold">
                                    <th class="ps-4" style="width:48px;">#</th>
                                    <th>Name</th>
                                    <th class="text-center text-success">P</th>
                                    <th class="text-center text-danger">A</th>
                                    <th class="text-center">Assignment (30%)</th>
                                    <th class="text-center">Mini Project (20%)</th>
                                    <th class="text-center">Final Project (40%)</th>
                                    <th class="text-center">Total (100%)</th>
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
                                        <td class="ps-4 text-muted fs-2">{{ $i + 1 }}</td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="d-flex align-items-center justify-content-center rounded-circle flex-shrink-0 {{ $avatarBg[$idx] }}"
                                                    style="width:35px;height:35px;">
                                                    <span
                                                        class="fs-2 fw-semibold {{ $avatarText[$idx] }}">{{ $initials }}</span>
                                                </div>
                                                <h6 class="mb-0 fw-semibold fs-3">{{ $report->student->name }}</h6>
                                            </div>
                                        </td>
                                        <td class="text-center"><span
                                                class="fw-semibold text-success fs-3">{{ $report->present }}</span></td>
                                        <td class="text-center"><span
                                                class="fw-semibold text-danger fs-3">{{ $report->absent }}</span></td>
                                        <td class="text-center" style="min-width:90px;">
                                            <p class="mb-1 fs-3 fw-semibold">
                                                {{ number_format($report->assignment_score) }}
                                            </p>
                                            <div class="progress {{ $trackClass }}" style="height:4px;">
                                                <div class="progress-bar {{ $barClass }}"
                                                    style="width:{{ $assignPct }}%"></div>
                                            </div>
                                        </td>
                                        <td class="text-center" style="min-width:90px;">
                                            <p class="mb-1 fs-3 fw-semibold">
                                                {{ number_format($report->mini_project_score) }}
                                            </p>
                                            <div class="progress {{ $trackClass }}" style="height:4px;">
                                                <div class="progress-bar {{ $barClass }}"
                                                    style="width:{{ $miniPct }}%"></div>
                                            </div>
                                        </td>
                                        <td class="text-center" style="min-width:90px;">
                                            <p class="mb-1 fs-3 fw-semibold">
                                                {{ number_format($report->final_project_score) }}
                                            </p>
                                            <div class="progress {{ $trackClass }}" style="height:4px;">
                                                <div class="progress-bar {{ $barClass }}"
                                                    style="width:{{ $finalPct }}%"></div>
                                            </div>
                                        </td>
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
                                        <td class="text-center pe-4">
                                            @if (!$isFail)
                                                <span class="badge bg-light-success text-success fw-semibold py-1 px-3"><i
                                                        class="ti ti-check me-1"></i>Pass</span>
                                            @else
                                                <span class="badge bg-light-danger text-danger fw-semibold py-1 px-3"><i
                                                        class="ti ti-x me-1"></i>Fail</span>
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
                {{-- Signature footer --}}
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
                            <h6 class="fw-semibold fs-3 mb-0 text-capitalize">Teacher:
                                {{ $course->instructor->name ?? 'N/A' }}
                            </h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>{{-- /pills-student-report --}}
    </div>{{-- /tab-content --}}
@endsection
@push('scripts')
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="/admin/assets/dist/libs/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
    <script>
        /* Select2 */
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
                width: '100%'
            });
        }
        /* Tab persistence */
        document.addEventListener('DOMContentLoaded', function() {
            const tabButtons = document.querySelectorAll('#pills-tab button[data-bs-toggle="pill"]');
            const defaultTab = '#pills-attendance';
            let activeTab = localStorage.getItem('activeTab') || defaultTab;
            let triggerEl = document.querySelector(`#pills-tab button[data-bs-target="${activeTab}"]`);
            if (triggerEl) new bootstrap.Tab(triggerEl).show();
            tabButtons.forEach(btn => btn.addEventListener('shown.bs.tab', e => {
                localStorage.setItem('activeTab', e.target.getAttribute('data-bs-target'));
            }));
        });
        /* Datepicker */
        $('#date-range').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true
        });
        /* Attendance hours calculation */
        function calculateRow(row) {
            const start = row.querySelector('input[name*="[start_time]"]');
            const end = row.querySelector('input[name*="[end_time]"]');
            const total = row.querySelector('.total-hours');
            const actual = row.querySelector('.actual-hours');

            function calc() {
                if (!start.value || !end.value) return;
                let diff = (new Date(`1970-01-01T${end.value}:00`) - new Date(`1970-01-01T${start.value}:00`)) / 3600000;
                if (diff < 0) diff += 24;
                total.value = diff.toFixed(2);
                let prevRow = row.previousElementSibling;
                let prevATH = prevRow ? parseFloat(prevRow.querySelector('.actual-hours')?.value) || 0 : 0;
                actual.value = (prevATH + diff).toFixed(2);
            }
            start.addEventListener('change', calc);
            end.addEventListener('change', calc);
        }
        document.querySelectorAll('#attendanceTable tbody tr').forEach(r => calculateRow(r));
    </script>
@endpush
