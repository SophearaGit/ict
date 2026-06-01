@extends('frontend.staff.layout.master')
@section('page_title', isset($page_title) ? $page_title : 'Page Title Here')
@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="/admin/assets/dist/libs/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    @include('frontend.staff.pages.course-management.course-detail.style.style')
    @include('frontend.staff.pages.course-management.course-detail.style.pills-student-attendance')
@endpush
@section('content')
    {{-- Breadcrumb --}}
    @include('frontend.staff.pages.course-management.course-detail.partials.breadcrumb')
    <div class="row">
        {{-- ════════════════════════════
             LEFT COLUMN — Info + Stats
        ════════════════════════════ --}}
        <div class="col-lg-4 col-xl-3">
            {{-- Hero --}}
            <div class="course-hero">
                <img class="course-hero__img"
                    src="{{ asset($course->thumbnail == '' ? asset('/default-images/staff/no-course-img.png') : asset($course->thumbnail)) }}"
                    alt="{{ $course->title }}">
                <div class="course-hero__overlay"></div>
                <div class="course-hero__body">
                    <div class="course-hero__top">
                        <span class="status-pill {{ $course->status == 'active' ? 'open' : 'closed' }}">
                            {{ strtoupper($course->status == 'active' ? 'Open' : 'Closed') }}
                        </span>
                        <a href="{{ route('staff.courses.edit', $course->id) }}"
                            class="btn btn-sm btn-light d-flex align-items-center gap-1" style="font-size:.78rem;">
                            <i class="ti ti-edit fs-5"></i> Edit
                        </a>
                    </div>
                    <div class="course-hero__bottom">
                        <div>
                            <h2 class="course-hero__title">{{ $course->title }}</h2>
                            @if ($course->schedule)
                                @php
                                    $days = collect(explode('-', $course->schedule->study_day))
                                        ->map(fn($d) => ucfirst($d))
                                        ->implode(' • ');
                                    $start = \Carbon\Carbon::parse($course->schedule->start_time)->format('g:i');
                                    $end = \Carbon\Carbon::parse($course->schedule->end_time)->format('g:i A');
                                    $shift = ucfirst($course->schedule->shift);
                                @endphp
                                <p class="course-hero__sub">{{ $days }} &nbsp;·&nbsp; {{ $shift }}
                                    ({{ $start }}–{{ $end }})</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Instructor chip --}}
            <div class="instructor-chip">
                <img src="{{ asset($course->instructor->image == 'no-img.jpg' ? 'default-images/user/both.jpg' : $course->instructor->image) }}"
                    alt="{{ $course->instructor->name }}">
                <div>
                    <div class="instructor-chip__name text-capitalize">{{ $course->instructor->name }}</div>
                    <div class="instructor-chip__role">Instructor</div>
                </div>
            </div>

            {{-- ── REMAINING highlight ── --}}
            @php
                $sessionDuration = 1.5;
                $totalDuration = $course->duration ?? 0;
                $completedHours = $course->teacherAttendances->sum('total_hours');
                $remainingHours = max(0, round($totalDuration - $completedHours, 1));
                $totalSessions = $totalDuration > 0 ? round($totalDuration / $sessionDuration) : 0;
                $completedSessions = $course->completed_sessions ?? 0;
                $remainingSessions = max(0, $totalSessions - $completedSessions);
                $progress = $course->progress ?? 0;
            @endphp
            <div class="remain-grid">
                <div class="remain-card hours">
                    <div class="remain-card__label">HRS Left</div>
                    <div class="remain-card__value">{{ $remainingHours }}<span
                            style="font-size:.9rem;font-weight:500;">h</span></div>
                    <div class="remain-card__sub">of {{ $totalDuration }}h total</div>
                </div>
                <div class="remain-card sessions">
                    <div class="remain-card__label">Sess Left</div>
                    <div class="remain-card__value">{{ $remainingSessions }}</div>
                    <div class="remain-card__sub">of {{ $totalSessions }} total</div>
                </div>
            </div>

            {{-- ── Stat Cards ── --}}
            <div class="stat-grid" style="grid-template-columns: 1fr 1fr;">
                {{-- Students --}}
                <div class="stat-card">
                    <div class="stat-card__icon" style="background:#ede9fe;">
                        <i class="ti ti-users" style="color:#7c3aed;"></i>
                    </div>
                    <div class="stat-card__label">Students</div>
                    <div class="stat-card__value">{{ $course->students->count() }}</div>
                </div>
                {{-- Earnings --}}
                <div class="stat-card">
                    <div class="stat-card__icon" style="background:#dcfce7;">
                        <i class="ti ti-currency-dollar" style="color:#16a34a;"></i>
                    </div>
                    <div class="stat-card__label">Earned</div>
                    <div class="stat-card__value" style="font-size:1.1rem;">
                        @if (request('from_date') && request('to_date'))
                            ${{ $course->filtered_earnings ?? 0 }}
                        @else
                            ${{ $course->earnings ?? 0 }}
                        @endif
                    </div>
                </div>
                {{-- Completed Sessions --}}
                <div class="stat-card">
                    <div class="stat-card__icon" style="background:#fef9c3;">
                        <i class="ti ti-calendar-check" style="color:#ca8a04;"></i>
                    </div>
                    <div class="stat-card__label">Done</div>
                    <div class="stat-card__value">{{ $completedSessions }}<span
                            style="font-size:.8rem;color:#94a3b8;font-weight:500;"> sess</span></div>
                </div>
                {{-- Start Date --}}
                <div class="stat-card">
                    <div class="stat-card__icon" style="background:#e0f2fe;">
                        <i class="ti ti-calendar" style="color:#0284c7;"></i>
                    </div>
                    <div class="stat-card__label">Start</div>
                    <div class="stat-card__value" style="font-size:.9rem;">
                        {{ $course->start_date ? $course->start_date->format('d M Y') : 'N/A' }}
                    </div>
                </div>
            </div>

            {{-- Progress --}}
            <div class="card border-0 shadow-none"
                style="background:#f8fafc;border:1px solid #e9ecef!important;border-radius:14px;">
                <div class="card-body py-3 px-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="fw-semibold fs-3" style="color:#1e293b;">Course Progress</span>
                        <span class="fw-bold fs-3" style="color:#6366f1;">{{ $progress }}%</span>
                    </div>
                    <div class="stat-progress">
                        @php
                            $pColor = $progress <= 50 ? '#ef4444' : ($progress <= 80 ? '#f59e0b' : '#22c55e');
                        @endphp
                        <div class="stat-progress__bar"
                            style="width:{{ $progress }}%;background:{{ $pColor }};"></div>
                    </div>
                    <div class="d-flex justify-content-between mt-2">
                        <small class="text-muted">{{ $completedSessions }} sessions done</small>
                        <small class="text-muted">{{ $remainingSessions }} remaining</small>
                    </div>
                </div>
            </div>

            {{-- Category / Room quick info --}}
            <div class="info-strip mt-3">
                <div class="info-strip__item">
                    <i class="ti ti-tag"></i>
                    <span><strong>{{ $course->category->name ?? 'Uncategorized' }}</strong></span>
                </div>
                <div class="info-strip__item">
                    <i class="ti ti-door"></i>
                    <span>Room <strong>{{ $course->room ?? 'N/A' }}</strong></span>
                </div>
                @if ($course->end_date)
                    <div class="info-strip__item">
                        <i class="ti ti-calendar-x"></i>
                        <span>Ends <strong>{{ $course->end_date->format('d M Y') }}</strong></span>
                    </div>
                @endif
            </div>
        </div>{{-- /col left --}}
        {{-- ════════════════════════════
             RIGHT COLUMN — Tabs
        ════════════════════════════ --}}
        <div class="col-lg-8 col-xl-9">
            {{-- Tabs nav --}}
            @include('frontend.staff.pages.course-management.course-detail.partials.tab')
            {{-- Tab content --}}
            <div class="tab-content mt-3" id="pills-tabContent">
                {{-- ── Students ── --}}
                @include('frontend.staff.pages.course-management.course-detail.partials.tab-contents.pills-students')
                {{-- ── Teacher Attendance ── --}}
                <div class="tab-pane fade" id="pills-attendance" role="tabpanel" tabindex="0">
                    <div class="card border-0" style="border-radius:14px;box-shadow:0 2px 12px rgba(0,0,0,.06);">
                        <div class="card-body">
                            {{-- Sheet header --}}
                            <div class="top-header mb-3">
                                <div class="logo"><i class="ti ti-calendar-check fs-12 me-2"></i></div>
                                <div>
                                    <div class="title">ICT Professional Training Center</div>
                                    <div class="sub-title">Teacher's Attendance</div>
                                </div>
                            </div>

                            {{-- Info strip --}}
                            <div class="info-strip mb-4">
                                <div class="info-strip__item">
                                    <i class="ti ti-user"></i>
                                    <span><strong
                                            class="text-capitalize">{{ $course->instructor->name ?? 'N/A' }}</strong></span>
                                </div>
                                <div class="info-strip__item">
                                    <i class="ti ti-book"></i>
                                    <span><strong>{{ $course->title }}</strong></span>
                                </div>
                                @if ($course->schedule)
                                    <div class="info-strip__item">
                                        <i class="ti ti-clock"></i>
                                        <span>{{ $shift }} ({{ $start }}–{{ $end }})</span>
                                    </div>
                                @endif
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
                                                <input type="text" class="form-control" name="from_date"
                                                    placeholder="From"
                                                    value="{{ request('from_date', now()->startOfMonth()->format('Y-m-d')) }}">
                                                <span class="input-group-text bg-primary text-white px-3">TO</span>
                                                <input type="text" class="form-control" name="to_date"
                                                    placeholder="To"
                                                    value="{{ request('to_date', now()->endOfMonth()->format('Y-m-d')) }}">
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-5">
                                            <div class="d-flex gap-2">
                                                <button class="btn btn-primary w-100">
                                                    <i class="ti ti-search me-1"></i>Filter
                                                </button>
                                                <a href="{{ route('staff.courses.show', $course->id) }}"
                                                    class="btn btn-outline-secondary w-100">Reset</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>

                            {{-- Attendance Table --}}
                            <form action="{{ route('staff.teacher.attendance.update') }}" method="POST"
                                id="attendanceForm">
                                @csrf
                                <input type="hidden" name="course_id" value="{{ $course->id }}">
                                <input type="hidden" name="teacher_id" value="{{ $course->instructor->id ?? '' }}">
                                <input type="hidden" name="schedule_id" value="{{ $course->schedule->id ?? '' }}">

                                <table id="attendanceTable">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Date</th>
                                            <th>Time In</th>
                                            <th>Time Out</th>
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

                                                {{-- Hidden ID --}}
                                                <td style="display:none;">
                                                    <input type="hidden" name="attendances[{{ $index }}][id]"
                                                        value="{{ $attendance->id }}">
                                                </td>

                                                {{-- DATE — locked by default, unlock button alongside --}}
                                                <td>
                                                    <div class="date-lock-wrap">
                                                        <input type="date"
                                                            name="attendances[{{ $index }}][date]"
                                                            value="{{ $attendance->date }}"
                                                            class="form-control form-control-sm text-dark date-input"
                                                            disabled>
                                                        <button type="button" class="btn-date-lock"
                                                            title="Click to unlock date for editing"
                                                            aria-label="Toggle date lock">
                                                            <i class="ti ti-lock"></i>
                                                        </button>
                                                    </div>
                                                </td>

                                                <td>
                                                    <input type="time"
                                                        name="attendances[{{ $index }}][start_time]"
                                                        value="{{ \Carbon\Carbon::parse($attendance->start_time)->format('H:i') }}"
                                                        class="form-control form-control-sm text-dark">
                                                </td>
                                                <td>
                                                    <input type="time"
                                                        name="attendances[{{ $index }}][end_time]"
                                                        value="{{ \Carbon\Carbon::parse($attendance->end_time)->format('H:i') }}"
                                                        class="form-control form-control-sm text-dark">
                                                </td>
                                                <td>
                                                    <input type="text"
                                                        name="attendances[{{ $index }}][total_hours]"
                                                        value="{{ number_format($attendance->total_hours) }}"
                                                        class="form-control form-control-sm total-hours text-center text-dark"
                                                        readonly>
                                                </td>
                                                <td>
                                                    <input type="text"
                                                        name="attendances[{{ $index }}][actual_hours]"
                                                        value="{{ number_format($attendance->actual_hours) }}"
                                                        class="form-control form-control-sm actual-hours text-center text-dark"
                                                        readonly>
                                                </td>
                                                <td>
                                                    <input type="text" name="attendances[{{ $index }}][room]"
                                                        value="{{ $attendance->room }}"
                                                        class="form-control form-control-sm text-uppercase text-center text-dark">
                                                </td>
                                                <td>
                                                    <input type="number"
                                                        name="attendances[{{ $index }}][late_minutes]"
                                                        value="{{ $attendance->late_minutes }}"
                                                        class="form-control form-control-sm text-center">
                                                </td>
                                                <td>
                                                    <input type="text"
                                                        name="attendances[{{ $index }}][late_reason]"
                                                        value="{{ $attendance->late_reason }}"
                                                        class="form-control form-control-sm">
                                                </td>
                                            </tr>
                                        @endforeach

                                        {{-- ── NEW ROW — date is freely editable, defaults to today ── --}}
                                        @php $nextIndex = $attendances->count(); @endphp
                                        <tr class="table-active">
                                            <td style="padding:10px">{{ $nextIndex + 1 }}</td>

                                            <td style="display:none;">
                                                <input type="hidden" name="attendances[{{ $nextIndex }}][id]"
                                                    value="">
                                            </td>

                                            {{-- No lock button on the new row — always editable --}}
                                            <td>
                                                <div class="new-row-date-wrap">
                                                    <input type="date" name="attendances[{{ $nextIndex }}][date]"
                                                        value="{{ now()->format('Y-m-d') }}"
                                                        class="form-control form-control-sm">
                                                </div>
                                            </td>

                                            <td>
                                                <input type="time" name="attendances[{{ $nextIndex }}][start_time]"
                                                    class="form-control form-control-sm">
                                            </td>
                                            <td>
                                                <input type="time" name="attendances[{{ $nextIndex }}][end_time]"
                                                    class="form-control form-control-sm">
                                            </td>
                                            <td>
                                                <input type="text"
                                                    name="attendances[{{ $nextIndex }}][total_hours]"
                                                    class="form-control form-control-sm total-hours text-center" readonly>
                                            </td>
                                            <td>
                                                <input type="text"
                                                    name="attendances[{{ $nextIndex }}][actual_hours]"
                                                    class="form-control form-control-sm actual-hours text-center" readonly>
                                            </td>
                                            <td>
                                                <input type="text" name="attendances[{{ $nextIndex }}][room]"
                                                    class="form-control form-control-sm text-uppercase text-center">
                                            </td>
                                            <td>
                                                <input type="number"
                                                    name="attendances[{{ $nextIndex }}][late_minutes]"
                                                    class="form-control form-control-sm text-center">
                                            </td>
                                            <td>
                                                <input type="text"
                                                    name="attendances[{{ $nextIndex }}][late_reason]"
                                                    class="form-control form-control-sm">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                                <button type="submit" class="btn btn-primary mt-3">
                                    <i class="ti ti-device-floppy me-1"></i> Save Attendance
                                </button>
                            </form>

                        </div>
                    </div>
                </div>{{-- /pills-attendance --}}

                {{-- ── Student Attendance ── --}}
                <div class="tab-pane fade" id="pills-student-attendance" role="tabpanel">
                    @php
                        $data = $attendanceData;
                        $dates = array_slice($data['table_structure']['columns'], 5);
                    @endphp
                    <div class="attendance-wrapper mt-2">
                        <div class="attendance-header-card">
                            <div class="row">
                                <div class="col-md-3"><strong>Start:</strong> {{ $data['form_metadata']['class_start'] }}
                                </div>
                                <div class="col-md-3"><strong>Room:</strong> {{ $data['form_metadata']['room'] }}</div>
                                <div class="col-md-3"><strong>Lecturer:</strong>
                                    {{ $data['form_metadata']['lecturer_name'] }}</div>
                                <div class="col-md-3"><strong>Phone:</strong>
                                    {{ $data['form_metadata']['lecturer_phone'] ?? '-' }}</div>
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

                    {{-- Summary pills --}}
                    <div class="d-flex flex-wrap gap-3 mb-4">
                        <div class="stat-card flex-row align-items-center gap-3"
                            style="padding:12px 20px;flex:1;min-width:120px;">
                            <div class="stat-card__icon" style="background:#ede9fe;flex-shrink:0;">
                                <i class="ti ti-users" style="color:#7c3aed;"></i>
                            </div>
                            <div>
                                <div class="stat-card__label">Total</div>
                                <div class="stat-card__value">{{ $totalStudents }}</div>
                            </div>
                        </div>
                        <div class="stat-card flex-row align-items-center gap-3"
                            style="padding:12px 20px;flex:1;min-width:120px;">
                            <div class="stat-card__icon" style="background:#dcfce7;flex-shrink:0;">
                                <i class="ti ti-check" style="color:#16a34a;"></i>
                            </div>
                            <div>
                                <div class="stat-card__label">Passed</div>
                                <div class="stat-card__value" style="color:#16a34a;">{{ $passed }}</div>
                            </div>
                        </div>
                        <div class="stat-card flex-row align-items-center gap-3"
                            style="padding:12px 20px;flex:1;min-width:120px;">
                            <div class="stat-card__icon" style="background:#fee2e2;flex-shrink:0;">
                                <i class="ti ti-x" style="color:#dc2626;"></i>
                            </div>
                            <div>
                                <div class="stat-card__label">Failed</div>
                                <div class="stat-card__value" style="color:#dc2626;">{{ $failed }}</div>
                            </div>
                        </div>
                        <div class="stat-card flex-row align-items-center gap-3"
                            style="padding:12px 20px;flex:1;min-width:120px;">
                            <div class="stat-card__icon" style="background:#fef3c7;flex-shrink:0;">
                                <i class="ti ti-chart-bar" style="color:#d97706;"></i>
                            </div>
                            <div>
                                <div class="stat-card__label">Avg Score</div>
                                <div class="stat-card__value" style="color:#d97706;">{{ $avgScore }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="card border-0" style="border-radius:14px;box-shadow:0 2px 12px rgba(0,0,0,.06);">
                        {{-- Header banner --}}
                        <div class="card-body"
                            style="background:linear-gradient(135deg,#4f46e5,#6366f1);border-radius:14px 14px 0 0;">
                            <div class="d-flex align-items-start justify-content-between mb-2">
                                <div>
                                    <span class="badge bg-white fw-semibold fs-2 mb-2" style="color:#4f46e5;">
                                        <i class="ti ti-file-certificate me-1"></i> Student Report
                                    </span>
                                    <h4 class="text-white fw-semibold mb-1">{{ $course->title }}</h4>
                                    <p class="mb-0 fs-3" style="color:rgba(255,255,255,.7);">ICT Professional Training
                                        Center</p>
                                </div>
                            </div>
                            <div class="d-flex flex-wrap gap-2 mt-3">
                                <span class="badge fw-normal fs-2 py-2 px-3"
                                    style="background:rgba(255,255,255,.15);color:rgba(255,255,255,.9);border:1px solid rgba(255,255,255,.2);">
                                    <i class="ti ti-user me-1"></i>{{ $course->instructor->name ?? 'N/A' }}
                                </span>
                                <span class="badge fw-normal fs-2 py-2 px-3"
                                    style="background:rgba(255,255,255,.15);color:rgba(255,255,255,.9);border:1px solid rgba(255,255,255,.2);">
                                    <i class="ti ti-door me-1"></i>Room: {{ $course->room ?? 'N/A' }}
                                </span>
                                @if ($course->schedule)
                                    <span class="badge fw-normal fs-2 py-2 px-3"
                                        style="background:rgba(255,255,255,.15);color:rgba(255,255,255,.9);border:1px solid rgba(255,255,255,.2);">
                                        <i class="ti ti-calendar me-1"></i>{{ $days }}
                                    </span>
                                    <span class="badge fw-normal fs-2 py-2 px-3"
                                        style="background:rgba(255,255,255,.15);color:rgba(255,255,255,.9);border:1px solid rgba(255,255,255,.2);">
                                        <i class="ti ti-clock me-1"></i>{{ $shift }}
                                        ({{ $start }}–{{ $end }})
                                    </span>
                                @endif
                            </div>
                        </div>
                        {{-- Table --}}
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table align-middle text-nowrap mb-0">
                                    <thead>
                                        <tr class="text-muted fw-semibold"
                                            style="font-size:.78rem;text-transform:uppercase;letter-spacing:.04em;">
                                            <th class="ps-4" style="width:48px;">#</th>
                                            <th>Name</th>
                                            <th class="text-center text-success">P</th>
                                            <th class="text-center text-danger">A</th>
                                            <th class="text-center">Assignment (30%)</th>
                                            <th class="text-center">Mini Project (20%)</th>
                                            <th class="text-center">Final Project (40%)</th>
                                            <th class="text-center">Total</th>
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
                                                        <h6 class="mb-0 fw-semibold fs-3">{{ $report->student->name }}
                                                        </h6>
                                                    </div>
                                                </td>
                                                <td class="text-center"><span
                                                        class="fw-semibold text-success fs-3">{{ $report->present }}</span>
                                                </td>
                                                <td class="text-center"><span
                                                        class="fw-semibold text-danger fs-3">{{ $report->absent }}</span>
                                                </td>
                                                <td class="text-center" style="min-width:90px;">
                                                    <p class="mb-1 fs-3 fw-semibold">
                                                        {{ number_format($report->assignment_score) }}</p>
                                                    <div class="progress {{ $trackClass }}" style="height:4px;">
                                                        <div class="progress-bar {{ $barClass }}"
                                                            style="width:{{ $assignPct }}%"></div>
                                                    </div>
                                                </td>
                                                <td class="text-center" style="min-width:90px;">
                                                    <p class="mb-1 fs-3 fw-semibold">
                                                        {{ number_format($report->mini_project_score) }}</p>
                                                    <div class="progress {{ $trackClass }}" style="height:4px;">
                                                        <div class="progress-bar {{ $barClass }}"
                                                            style="width:{{ $miniPct }}%"></div>
                                                    </div>
                                                </td>
                                                <td class="text-center" style="min-width:90px;">
                                                    <p class="mb-1 fs-3 fw-semibold">
                                                        {{ number_format($report->final_project_score) }}</p>
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
                                                        <span
                                                            class="badge bg-light-success text-success fw-semibold py-1 px-3">
                                                            <i class="ti ti-check me-1"></i>Pass
                                                        </span>
                                                    @else
                                                        <span
                                                            class="badge bg-light-danger text-danger fw-semibold py-1 px-3">
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
                                    <h6 class="fw-semibold fs-3 mb-0 text-capitalize">
                                        Teacher: {{ $course->instructor->name ?? 'N/A' }}
                                    </h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>{{-- /pills-student-report --}}

            </div>{{-- /tab-content --}}
        </div>{{-- /col right --}}
    </div>{{-- /row --}}
@endsection

@push('scripts')
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="/admin/assets/dist/libs/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
    <script>
        /* ── Select2 ── */
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

        /* ── Tab persistence ── */
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

        /* ── Datepicker (filter bar) ── */
        $('#date-range').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true
        });

        /* ── Hours auto-calculation ── */
        function calculateRow(row) {
            const start = row.querySelector('input[name*="[start_time]"]');
            const end = row.querySelector('input[name*="[end_time]"]');
            const total = row.querySelector('.total-hours');
            const actual = row.querySelector('.actual-hours');
            if (!start || !end || !total || !actual) return;

            function calc() {
                if (!start.value || !end.value) return;
                let diff = (new Date(`1970-01-01T${end.value}:00`) - new Date(`1970-01-01T${start.value}:00`)) / 3600000;
                if (diff < 0) diff += 24;
                total.value = diff.toFixed(2);

                // accumulate from the previous row's actual hours
                const prevRow = row.previousElementSibling;
                const prevATH = prevRow ? parseFloat(prevRow.querySelector('.actual-hours')?.value) || 0 : 0;
                actual.value = (prevATH + diff).toFixed(2);
            }

            start.addEventListener('change', calc);
            end.addEventListener('change', calc);
        }
        document.querySelectorAll('#attendanceTable tbody tr').forEach(r => calculateRow(r));

        /* ── Date lock / unlock toggle ── */
        document.querySelectorAll('.btn-date-lock').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const wrap = this.closest('.date-lock-wrap');
                const input = wrap.querySelector('.date-input');
                const icon = this.querySelector('i');
                const locked = input.disabled; // currently locked → we unlock

                if (locked) {
                    // Unlock
                    input.disabled = false;
                    input.focus();
                    icon.className = 'ti ti-lock-open';
                    this.title = 'Click to lock date';
                    this.classList.add('is-unlocked');
                } else {
                    // Lock again
                    input.disabled = true;
                    icon.className = 'ti ti-lock';
                    this.title = 'Click to unlock date for editing';
                    this.classList.remove('is-unlocked');
                }
            });
        });

        /* ── Re-enable disabled date inputs before form submit
               so their values are included in the POST payload ── */
        document.getElementById('attendanceForm').addEventListener('submit', function() {
            this.querySelectorAll('.date-input:disabled').forEach(function(input) {
                input.disabled = false;
            });
        });
    </script>
@endpush
