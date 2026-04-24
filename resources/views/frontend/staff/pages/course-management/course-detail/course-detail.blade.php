@extends('frontend.staff.layout.master')
@section('page_title', isset($page_title) ? $page_title : 'Page Title Here')
@push('styles')
    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="/admin/assets/dist/libs/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
    @include('frontend.staff.pages.course-management.course-detail.style.style')
@endpush
@section('content')
    @include('frontend.staff.pages.partials.breadcrumb')
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
                        {{-- complete session count
                        <div class="text-center">
                            <i class="ti ti-calendar-check fs-6 d-block mb-2"></i>
                            <h4 class="mb-0 fw-semibold lh-1">
                                {{ $course->completed_sessions ?? 0 }}
                            </h4>
                            <p class="mb-0 fs-4">
                                Completed
                            </p>
                        </div> --}}
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
        <div class="tab-pane fade" id="pills-students" role="tabpanel" aria-labelledby="pills-students-tab" tabindex="0">
            {{-- <div class="d-sm-flex align-items-center justify-content-between mt-3 mb-4">
                <h3 class="mb-3 mb-sm-0 fw-semibold d-flex align-items-center">
                    Students
                    <span class="badge text-bg-secondary fs-2 rounded-4 py-1 px-2 ms-2">
                        {{ $course->students->count() ?? 0 }}
                    </span>
                </h3>
                <form class="position-relative">
                    <input type="text" class="form-control search-chat py-2 ps-5" id="text-srh"
                        placeholder="Search Friends">
                    <i class="ti ti-search position-absolute top-50 start-0 translate-middle-y text-dark ms-3"></i>
                </form>
            </div> --}}
            <div class="row">
                @forelse ($course->students as $student)
                    <div class="col-sm-6 col-lg-4">
                        <div class="card hover-img">
                            <div class="card-body p-4 text-center border-bottom">
                                <img src="
                                    {{ asset($student->image == 'no-img.jpg' ? 'default-images/user/both.jpg' : $student->image) }}
                                "
                                    alt="" class="rounded-circle mb-3" width="80" height="80">
                                <h5 class="fw-semibold mb-0 text-capitalize">
                                    {{ Str::limit($student->name, 20) }}
                                </h5>
                                <span class="text-dark fs-2">
                                    {{ $student->email }}
                                </span>
                            </div>
                            <ul
                                class="px-2 py-2 bg-light list-unstyled d-flex align-items-center justify-content-center mb-0">

                                <li class="position-relative">
                                    <a class="text-primary d-flex align-items-center justify-content-center p-2 fs-5 rounded-circle fw-semibold"
                                        href="javascript:void(0)">
                                        <i class="ti ti-brand-facebook"></i>
                                    </a>
                                </li>
                                <li class="position-relative">
                                    <a class="text-danger d-flex align-items-center justify-content-center p-2 fs-5 rounded-circle fw-semibold "
                                        href="javascript:void(0)">
                                        <i class="ti ti-brand-instagram"></i>
                                    </a>
                                </li>
                                <li class="position-relative">
                                    <a class="text-info d-flex align-items-center justify-content-center p-2 fs-5 rounded-circle fw-semibold "
                                        href="javascript:void(0)">
                                        <i class="ti ti-brand-github"></i>
                                    </a>
                                </li>
                                <li class="position-relative">
                                    <a class="text-secondary d-flex align-items-center justify-content-center p-2 fs-5 rounded-circle fw-semibold "
                                        href="javascript:void(0)">
                                        <i class="ti ti-brand-twitter"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body text-center">
                                <h5 class="mb-0">No students enrolled in this course.</h5>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>





        <div class="tab-pane fade show active" id="pills-attendance" role="tabpanel"
            aria-labelledby="pills-attendance-tab" tabindex="0">
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
                            {{-- <form method="GET" class="row g-2 mb-3">

                                <div class="col-md-6">
                                    <label class="mb-1">Filter by Date Range</label>
                                    <div class="input-daterange input-group" id="date-range">

                                        <input type="text" class="form-control" name="from_date"
                                            placeholder="From date" value="{{ request('from_date') }}"
                                            id="datepicker-autoclose">

                                        <span class="input-group-text bg-info text-white">TO</span>

                                        <input type="text" class="form-control" name="to_date" placeholder="To date"
                                            value="{{ request('to_date') }}">
                                    </div>
                                </div>

                                <div class="col-md-3 d-flex align-items-end">
                                    <button class="btn btn-primary w-100">
                                        <i class="ti ti-search"></i> Filter
                                    </button>
                                </div>

                                <div class="col-md-3 d-flex align-items-end">
                                    <a href="{{ route('staff.courses.show', $course->id) }}"
                                        class="btn btn-secondary w-100">
                                        Reset
                                    </a>
                                </div>
                            </form> --}}
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
                        {{-- @if (request('from_date') && request('to_date'))
                            <div class="alert alert-primary d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <strong>Filtered:</strong>
                                    {{ request('from_date') }} → {{ request('to_date') }}
                                </div>

                                <div class="d-flex gap-4">
                                    <span><strong>Hours:</strong> {{ $course->filtered_hours ?? 0 }}</span>
                                    <span><strong>Sessions:</strong> {{ $course->filtered_sessions ?? 0 }}</span>
                                    <span><strong>Earnings:</strong> ${{ $course->filtered_earnings ?? 0 }}</span>
                                </div>
                            </div>
                        @endif --}}
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
        <div class="tab-pane fade" id="pills-student-attendance" role="tabpanel">
            @php
                $data = $attendanceData;
                $dates = array_slice($data['table_structure']['columns'], 5);
            @endphp
            <div class="sheet mt-4">
                <!-- TOP INFO -->
                <table class="meta-table mb-3">
                    <tr>
                        <td><strong>Class Start</strong></td>
                        <td>{{ $data['form_metadata']['class_start'] }}</td>
                    </tr>
                    <tr>
                        <td><strong>Room</strong></td>
                        <td>{{ $data['form_metadata']['room'] }}</td>
                    </tr>
                    <tr>
                        <td><strong>Lecturer Name</strong></td>
                        <td>{{ $data['form_metadata']['lecturer_name'] }}</td>
                    </tr>
                    <tr>
                        <td><strong>Lecturer Phone</strong></td>
                        <td>{{ $data['form_metadata']['lecturer_phone'] ?? '' }}</td>
                    </tr>
                </table>

                <!-- MAIN TABLE -->
                <div class="table-responsive">
                    <table class="attendance-sheet">

                        <!-- YELLOW TITLE ROW -->
                        <thead>
                            <tr class="title-row">
                                <th colspan="{{ count($data['table_structure']['columns']) }}">
                                    {{ $data['form_metadata']['class_title'] }}
                                </th>
                            </tr>

                            <!-- PINK DATE HEADER -->
                            <tr class="date-row">
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
                                    <td class="text-start">{{ $row['student_name'] }}</td>
                                    <td>{{ $row['sex'] }}</td>
                                    <td>{{ $row['day'] }}</td>
                                    <td>{{ $row['shift'] }}</td>

                                    @foreach ($dates as $date)
                                        <td>
                                            {{ $row['attendance'][$date] ?? '' }}
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
@endsection
@push('scripts')
    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="/admin/assets/dist/libs/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
    <script>
        $('#date-range').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true,
        });
    </script>

    <script>
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

                    // Handle overnight (optional)
                    if (diff < 0) diff += 24;

                    // ✅ Set TH
                    total.value = diff.toFixed(2);

                    // ✅ Calculate ATH (cumulative)
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
        }

        // Apply to all rows
        document.querySelectorAll('#attendanceTable tbody tr').forEach(row => {
            calculateRow(row);
        });
    </script>
@endpush
