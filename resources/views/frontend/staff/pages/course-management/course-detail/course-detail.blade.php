@extends('frontend.staff.layout.master')
@section('page_title', isset($page_title) ? $page_title : 'Page Title Here')
@push('styles')
    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="/admin/assets/dist/libs/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
    @include('frontend.staff.pages.course-management.course-detail.style.style')
    @include('frontend.staff.pages.course-management.course-detail.style.pills-student-attendance')
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
        @include('frontend.staff.pages.course-management.course-detail.partials.tab-contents.pills-students')
        @include('frontend.staff.pages.course-management.course-detail.partials.tab-contents.pills-attendance')
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
                        <div class="col-md-3"><strong>Phone:</strong> {{ $data['form_metadata']['lecturer_phone'] ?? '-' }}
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
        <div class="tab-pane fade show active" id="pills-student-report" role="tabpanel">
            <div class="report-wrapper mt-4 p-4 bg-white rounded-3 shadow-sm">

                <!-- HEADER -->
                <div class="mb-4">
                    <h5 class="fw-bold mb-3 text-center text-uppercase">
                        Student Report
                    </h5>

                    <div class="row small">
                        <div class="col-md-6">
                            <p class="text-capitalize"><strong>Instructor:</strong>
                                {{ $course->instructor->name }}</p>
                            <p class="text-capitalize"><strong>Course:</strong>
                                {{ $course->title }}</p>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <p><strong>Room:</strong> {{ $course->room ?? 'A' }}</p>
                            <p><strong>Schedule:</strong>
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
                        </div>
                    </div>
                </div>

                <!-- TABLE -->
                <div class="table-responsive">
                    <table class="table table-bordered text-center align-middle report-table">

                        <thead class="table-light">
                            <tr>
                                <th rowspan="2">No</th>
                                <th rowspan="2">Name</th>
                                {{-- <th rowspan="2">Gender</th> --}}
                                <th colspan="3">Attendance</th>
                                <th rowspan="2">Assignment</th>
                                <th rowspan="2">Mini Project</th>
                                <th rowspan="2">Final Project</th>
                                <th rowspan="2">Total</th>
                                <th rowspan="2">Result</th>
                                {{-- <th rowspan="2">Remark</th> --}}
                            </tr>
                            <tr>
                                <th>P</th>
                                <th>A</th>
                                <th>AP</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($course->studentReports as $i => $report)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td class="text-start">{{ $report->student->name }}</td>
                                    {{-- <td>{{ $report->student->gender }}</td> --}}

                                    <!-- Attendance -->
                                    <td>{{ $report->present }}</td>
                                    <td>{{ $report->absent }}</td>
                                    <td>{{ $report->permission }}</td>

                                    <!-- Editable Scores -->
                                    <td>
                                        <input type="number" class="form-control score-input"
                                            data-id="{{ $report->id }}" data-field="assignment_score"
                                            value="{{ number_format($report->assignment_score) }}"
                                            readonly>
                                    </td>

                                    <td>
                                        <input type="number" class="form-control score-input"
                                            data-id="{{ $report->id }}" data-field="mini_project_score"
                                            value="{{ number_format($report->mini_project_score) }}"
                                            readonly>
                                    </td>

                                    <td>
                                        <input type="number" class="form-control score-input"
                                            data-id="{{ $report->id }}" data-field="final_project_score"
                                            value="{{ number_format($report->final_project_score) }}"
                                            readonly>
                                    </td>

                                    <!-- Auto -->
                                    <td class="fw-bold total-score">
                                        {{ $report->total_score }}
                                    </td>

                                    <td>
                                        <span class="badge bg-{{ $report->result == 'pass' ? 'success' : 'danger' }}">
                                            {{ ucfirst($report->result) }}
                                        </span>
                                    </td>

                                    {{-- <td>{{ $report->remark }}</td> --}}
                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>

                <!-- FOOTER -->
                <div class="row mt-5 text-center small">
                    <div class="col-md-6">
                        <p>Seen and Approved by</p>
                        <br><br>
                        <strong>ICT Training Center</strong>
                    </div>

                    <div class="col-md-6">
                        <p>Prepared by</p>
                        <br><br>
                        <strong class="text-capitalize">Teacher:
                            {{ $course->instructor->name }}</strong>
                    </div>
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
        $(document).on('change', '.score-input', function() {

            let input = $(this);
            let id = input.data('id');
            let field = input.data('field');
            let value = input.val();

            $.ajax({
                url: `/staff/student-report/update/${id}`,
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    field: field,
                    value: value
                },
                success: function(res) {

                    input.closest('tr').find('.total-score').text(res.total_score);

                    let badge = input.closest('tr').find('.badge');
                    badge.text(res.result);
                    badge.removeClass('bg-success bg-danger');
                    badge.addClass(res.result === 'pass' ? 'bg-success' : 'bg-danger');
                }
            });
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
