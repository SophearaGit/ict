@extends('frontend.layouts.master')
@section('page_title', isset($page_title) ? $page_title : 'Page Title Here')
@push('styles')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            /* =========================
               DATE FUNCTIONS
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
                document.getElementById('attendance-date').value = formatted;

                updateDateLabel();
            };

            document.getElementById('attendance-date')
                .addEventListener('change', updateDateLabel);


            /* =========================
               STATUS FUNCTIONS
            ========================= */
            window.setStatus = function(el, status) {
                let parent = el.parentElement;
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
            };

            function updateSummary() {
                let present = 0;
                let absent = 0;
                let late = 0;

                document.querySelectorAll('#attendanceTable .status-toggle').forEach(group => {
                    let active = group.querySelector('.active');

                    if (!active) return;

                    let text = active.innerText.trim();

                    if (text === 'Present') present++;
                    if (text === 'Absent') absent++;
                    if (text === 'Late') late++;
                });

                document.getElementById('presentCount').innerText = present;
                document.getElementById('absentCount').innerText = absent;
                document.getElementById('lateCount').innerText = late;
            }

            window.markAllPresent = function() {
                document.querySelectorAll('#attendanceTable .status-toggle').forEach(group => {
                    let presentBtn = group.querySelector('span:nth-child(1)');

                    group.querySelectorAll('span').forEach(b => {
                        b.classList.remove('bg-success', 'bg-danger', 'bg-warning', 'active');
                        b.classList.add('bg-light', 'text-dark');
                    });

                    presentBtn.classList.remove('bg-light', 'text-dark');
                    presentBtn.classList.add('bg-success', 'active');
                });

                updateSummary();
            };


            /* =========================
               ROW CLICK (UX)
            ========================= */
            document.querySelectorAll('#attendanceTable tr').forEach(row => {
                row.addEventListener('click', function(e) {

                    // ✅ Ignore status buttons
                    if (e.target.closest('.status-toggle')) return;

                    // ✅ Ignore input
                    if (e.target.tagName === 'INPUT') return;

                    let presentBtn = row.querySelector('.status-toggle span:nth-child(1)');
                    setStatus(presentBtn, 'present');
                });
            });

            /* =========================
               SEARCH
            ========================= */
            document.getElementById('searchStudent')
                .addEventListener('keyup', function() {

                    let value = this.value.toLowerCase();

                    document.querySelectorAll('.student-row').forEach(row => {
                        row.style.display =
                            row.dataset.name.includes(value) ? '' : 'none';
                    });
                });


            /* =========================
               INIT
            ========================= */
            updateSummary();
            updateDateLabel();

        });
    </script>

    <script>
        function saveAttendance() {

            let attendances = [];

            $('#attendanceTable .student-row').each(function() {

                let studentId = $(this).data('student-id');

                let status = $(this).find('.status-toggle .active').text().trim().toLowerCase();

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
                beforeSend: function() {
                    $('.btn-primary').prop('disabled', true).text('Saving...');
                },
                success: function(res) {
                    $('.btn-primary').prop('disabled', false).text('💾 Save Attendance');

                    if (res.success) {
                        // showToast('✅ Attendance saved successfully');
                    }
                },
                error: function(err) {
                    $('.btn-primary').prop('disabled', false).text('💾 Save Attendance');

                    console.error(err);
                }
            });
        }
    </script>

    <style>
        /* TAB STUDENT ATTENDANCE START */
        .attendance-box {
            background: #fff;
            padding: 20px;
            border-radius: 12px;
        }

        /* Summary */
        .summary-card {
            background: #f8f9fa;
            padding: 12px;
            border-radius: 10px;
        }

        .summary-card h5 {
            margin: 5px 0 0;
        }

        .summary-card.success h5 {
            color: green;
        }

        .summary-card.danger h5 {
            color: red;
        }

        .summary-card.warning h5 {
            color: orange;
        }

        /* Avatar */
        .avatar {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background: #dee2e6;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
        }

        /* Status */
        .status-toggle span {
            cursor: pointer;
            margin-right: 5px;
        }

        /* TAB STUDENT ATTENDANCE END */


        .text-purple {
            color: #6f42c1;
        }

        .sheet {
            width: 100%;
            background: #fff;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
            overflow: hidden;
        }

        /* Header */
        .top-header {
            display: grid;
            grid-template-columns: 80px 1fr;
            border-bottom: 1px solid #e5e7eb;
        }

        .logo {
            border-right: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 18px;
        }

        .title {
            text-align: center;
            font-weight: 600;
            padding: 10px;
            font-size: 16px;
        }

        .sub-title {
            background: #facc15;
            text-align: center;
            font-weight: 600;
            padding: 6px;
            font-size: 14px;
        }

        /* Info rows */
        .info-row {
            display: grid;
            grid-template-columns: 140px 1fr;
            border-top: 1px solid #e5e7eb;
        }

        .info-label {
            border-right: 1px solid #e5e7eb;
            padding: 8px;
            font-weight: 600;
            background: #f9fafb;
        }

        .info-value {
            padding: 8px;
        }

        .highlight {
            background: #fef9c3;
        }

        /* Table wrapper (important for responsive) */
        .table-responsive {
            width: 100%;
            overflow-x: auto;
        }

        /* Table */
        .sheet table {
            width: 100%;
            min-width: 900px;
            border-collapse: collapse;
        }

        .sheet th,
        .sheet td {
            border: 1px solid #e5e7eb;
            text-align: center;
            padding: 8px;
            font-size: 14px;
        }

        .sheet th {
            background: #f3f4f6;
            font-weight: 600;
        }

        /* Zebra rows */
        .sheet tbody tr:nth-child(even) {
            background: #fafafa;
        }

        /* Mobile */
        @media (max-width: 768px) {
            .info-row {
                grid-template-columns: 1fr;
            }

            .info-label {
                border-right: none;
                border-bottom: 1px solid #e5e7eb;
            }

            .top-header {
                grid-template-columns: 1fr;
            }

            .logo {
                border-right: none;
                border-bottom: 1px solid #e5e7eb;
                padding: 10px;
            }
        }
    </style>
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
                                {{-- My Attendance --}}
                                <div class="tab-pane fade " id="attendance" role="tabpanel"
                                    aria-labelledby="attendance-tab">
                                    <div class="col-lg-12 col-md-9 col-12">
                                        <!-- Card -->
                                        <div class="card mb-4">
                                            <!-- Card body -->
                                            <div class="p-4 d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h3 class="mb-0">My Attendance</h3>
                                                    <span>Track your attendance for this course.</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-content">
                                            <div class="tab-pane fade pb-4 active show" id="" role="tabpanel"
                                                aria-labelledby="tabPaneGrid">
                                                <div class="row">
                                                    <div class="col-xl-12 col-lg-12 col-12 mb-3">
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <div class="sheet">

                                                                    <!-- Header -->
                                                                    <div class="top-header">
                                                                        <div class="logo">
                                                                            {{-- logo image --}}
                                                                            ICT
                                                                        </div>
                                                                        <div>
                                                                            <div class="title">ICT Professional
                                                                                Training
                                                                                Center</div>
                                                                            <div class="sub-title">Teacher's Attendant
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <!-- Teacher -->
                                                                    <div class="info-row">
                                                                        <div class="info-label">
                                                                            Teacher's Name:
                                                                        </div>
                                                                        <div class="info-value text-capitalize text-center"
                                                                            contenteditable="false">
                                                                            <strong class="text-black">
                                                                                {{ $course->instructor->name ?? 'No Instructor' }}
                                                                            </strong>
                                                                        </div>
                                                                    </div>
                                                                    <!-- Subject -->
                                                                    <div class="info-row highlight">
                                                                        <div class="info-label">Subject:</div>
                                                                        <div class="info-value text-capitalize text-center"
                                                                            contenteditable="false">
                                                                            <strong class="text-black">
                                                                                {{ $course->title }} |
                                                                                @if ($course->schedule)
                                                                                    @php
                                                                                        $days = collect(
                                                                                            explode(
                                                                                                '-',
                                                                                                $course->schedule
                                                                                                    ->study_day,
                                                                                            ),
                                                                                        )
                                                                                            ->map(
                                                                                                fn($day) => ucfirst(
                                                                                                    $day,
                                                                                                ),
                                                                                            )
                                                                                            ->implode(' • ');
                                                                                        $start = \Carbon\Carbon::parse(
                                                                                            $course->schedule
                                                                                                ->start_time,
                                                                                        )->format('g:i ');
                                                                                        $end = \Carbon\Carbon::parse(
                                                                                            $course->schedule->end_time,
                                                                                        )->format('g:i A');
                                                                                        $shift = ucfirst(
                                                                                            $course->schedule->shift,
                                                                                        );
                                                                                    @endphp
                                                                                    <strong>
                                                                                        {{ $days }} |
                                                                                        {{ $shift }} (
                                                                                        {{ $start }}
                                                                                        –
                                                                                        {{ $end }} )
                                                                                    </strong>
                                                                                @else
                                                                                    <span class="text-muted">No
                                                                                        schedule</span>
                                                                                @endif
                                                                            </strong>
                                                                        </div>
                                                                    </div>


                                                                    <div class="table-responsive">
                                                                        <table>
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>No</th>
                                                                                    <th>Date</th>
                                                                                    <th>Time In</th>
                                                                                    <th>Time Out</th>
                                                                                    <th>T.H</th>
                                                                                    <th>A.T.H</th>
                                                                                    <th>Room</th>
                                                                                    <th>Late (min)</th>
                                                                                    <th>Note</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                @forelse ($course->teacherAttendances as $index => $attendance)
                                                                                    <tr>
                                                                                        <td>{{ $index + 1 }}</td>
                                                                                        <td>{{ $attendance->formatted_date }}
                                                                                        </td>
                                                                                        <td>{{ $attendance->formatted_start_time }}
                                                                                        </td>
                                                                                        <td>{{ $attendance->formatted_end_time }}
                                                                                        </td>
                                                                                        <td>{{ $attendance->formatted_total_hours }}
                                                                                        </td>
                                                                                        <td>{{ $attendance->formatted_actual_hours }}
                                                                                        </td>
                                                                                        <td class="text-uppercase">
                                                                                            {{ $attendance->formatted_room }}
                                                                                        </td>
                                                                                        <td>{{ $attendance->formatted_late }}
                                                                                        </td>
                                                                                        <td>{{ $attendance->formatted_note }}
                                                                                        </td>
                                                                                    </tr>
                                                                                @empty
                                                                                    <tr>
                                                                                        <td colspan="9"
                                                                                            class="text-muted">
                                                                                            No attendance records
                                                                                        </td>
                                                                                    </tr>
                                                                                @endforelse
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="students" role="tabpanel"
                                    aria-labelledby="students-tab">
                                    <div class="col-lg-12 col-md-9 col-12">
                                        <!-- Card -->
                                        <div class="card mb-4">
                                            <!-- Card body -->
                                            <div class="p-4 d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h3 class="mb-0">Students</h3>
                                                    <span>Meet people taking your course.</span>
                                                </div>
                                                <!-- Nav -->
                                                <div class="nav btn-group flex-nowrap" role="tablist">
                                                    <button class="btn btn-outline-secondary" data-bs-toggle="tab"
                                                        data-bs-target="#tabPaneGrid" role="tab"
                                                        aria-controls="tabPaneGrid" aria-selected="false" tabindex="-1">
                                                        <span class="fe fe-grid"></span>
                                                    </button>
                                                    <button class="btn btn-outline-secondary active" data-bs-toggle="tab"
                                                        data-bs-target="#tabPaneList" role="tab"
                                                        aria-controls="tabPaneList" aria-selected="true">
                                                        <span class="fe fe-list"></span>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Tab content -->
                                        <div class="tab-content">
                                            <div class="tab-pane fade pb-4" id="tabPaneGrid" role="tabpanel"
                                                aria-labelledby="tabPaneGrid">
                                                <div class="row">
                                                    <div class="col-xl-12 col-lg-12 col-12 mb-3">
                                                        <!-- Content -->
                                                        <div class="row">
                                                            <div class="col pe-0">
                                                                <!-- Form -->
                                                                <form>
                                                                    <input type="search" class="form-control"
                                                                        placeholder="Search by Name">
                                                                </form>
                                                            </div>
                                                            <!-- Button -->
                                                            <div class="col-auto">
                                                                <a href="#" class="btn btn-secondary">
                                                                    Export CSV
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @forelse ($students as $student)
                                                        <div class="col-lg-4 col-md-6 col-12">
                                                            <!-- Card -->
                                                            <div class="card mb-4">
                                                                <!-- Card body -->
                                                                <div class="card-body">
                                                                    <div class="text-center">
                                                                        <img src=" {{ $student->image == 'no-img.jpg' ? asset('\default-images\user\both.jpg') : asset($student->image) }}"
                                                                            class="rounded-circle avatar-xl mb-3"
                                                                            alt="avatar">
                                                                        <h4 class="mb-1">
                                                                            {{ $student->name }}
                                                                        </h4>
                                                                        <p class="mb-0">
                                                                            <i class="fe fe-map-pin me-1"></i>
                                                                            {{ $student->location ?? 'Unknown' }}
                                                                        </p>
                                                                        {{-- <a href="#"
                                                                            class="btn btn-sm btn-outline-secondarymt-3">Message</a> --}}
                                                                    </div>
                                                                    <div
                                                                        class="d-flex justify-content-between  py-2 mt-4 fs-6">
                                                                        <span>Enrolled</span>
                                                                        <span class="text-dark">
                                                                            {{ $student->pivot->created_at->format('d M, Y') }}
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @empty
                                                        <div class="col-12">
                                                            <div class="card mb-4">
                                                                <div class="card-body text-center">
                                                                    <h4 class="mb-0">No students enrolled yet.</h4>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforelse
                                                    <div class="col-lg-12 col-md-12 col-12">
                                                        <!-- Pagination -->
                                                        <nav>
                                                            <ul class="pagination justify-content-center mb-0">

                                                                {{-- Previous --}}
                                                                <li
                                                                    class="page-item {{ $students->onFirstPage() ? 'disabled' : '' }}">
                                                                    <a class="page-link mx-1 rounded"
                                                                        href="{{ $students->previousPageUrl() ?? '#' }}">
                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                            width="10" height="10"
                                                                            fill="currentColor" class="bi bi-chevron-left"
                                                                            viewBox="0 0 16 16">
                                                                            <path fill-rule="evenodd"
                                                                                d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z">
                                                                            </path>
                                                                        </svg>
                                                                    </a>
                                                                </li>

                                                                {{-- Page Numbers --}}
                                                                @for ($i = 1; $i <= $students->lastPage(); $i++)
                                                                    <li
                                                                        class="page-item {{ $students->currentPage() == $i ? 'active' : '' }}">
                                                                        <a class="page-link mx-1 rounded"
                                                                            href="{{ $students->url($i) }}">
                                                                            {{ $i }}
                                                                        </a>
                                                                    </li>
                                                                @endfor

                                                                {{-- Next --}}
                                                                <li
                                                                    class="page-item {{ !$students->hasMorePages() ? 'disabled' : '' }}">
                                                                    <a class="page-link mx-1 rounded"
                                                                        href="{{ $students->nextPageUrl() ?? '#' }}">
                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                            width="10" height="10"
                                                                            fill="currentColor"
                                                                            class="bi bi-chevron-right"
                                                                            viewBox="0 0 16 16">
                                                                            <path fill-rule="evenodd"
                                                                                d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z">
                                                                            </path>
                                                                        </svg>
                                                                    </a>
                                                                </li>

                                                            </ul>
                                                        </nav>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Tab pane -->
                                            <div class="tab-pane fade active show" id="tabPaneList" role="tabpanel"
                                                aria-labelledby="tabPaneList">
                                                <div class="card">
                                                    <div class="card-header border-bottom-0">
                                                        <div class="row">
                                                            <div class="col pe-0">
                                                                <form>
                                                                    <input type="search" class="form-control"
                                                                        placeholder="Search by Name">
                                                                </form>
                                                            </div>
                                                            <div class="col-auto">
                                                                <a href="#" class="btn btn-secondary">Export
                                                                    CSV</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- Table -->
                                                    <div class="table-responsive">
                                                        <table class="table table-hover table-centered">
                                                            <thead class="table-light">
                                                                <tr>
                                                                    <th>Name</th>
                                                                    <th>Enrolled</th>
                                                                    <th>Locations</th>
                                                                    <th></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @forelse ($students as $student)
                                                                    <tr>
                                                                        <td>
                                                                            <div class="d-flex align-items-center">
                                                                                <img src="
                                                                                    {{ $student->image == 'no-img.jpg' ? asset('\default-images\user\both.jpg') : asset($student->image) }}"
                                                                                    alt=""
                                                                                    class="rounded-circle avatar-md me-2">
                                                                                <h5 class="mb-0">
                                                                                    {{ $student->name }}
                                                                                </h5>
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            {{ $student->pivot->created_at->format('d M, Y') }}
                                                                        </td>
                                                                        <td>
                                                                            <span class="fs-6">
                                                                                <i class="fe fe-map-pin me-1"></i>
                                                                                {{ $student->location ?? 'Unknown' }}
                                                                            </span>
                                                                        </td>
                                                                        <td>
                                                                            <span class="dropdown dropstart">
                                                                                <a class="btn-icon btn btn-ghost btn-sm rounded-circle"
                                                                                    href="#" role="button"
                                                                                    id="courseDropdown"
                                                                                    data-bs-toggle="dropdown"
                                                                                    data-bs-offset="-20,20"
                                                                                    aria-expanded="false">
                                                                                    <i class="fe fe-more-vertical"></i>
                                                                                </a>
                                                                                <span class="dropdown-menu"
                                                                                    aria-labelledby="courseDropdown">
                                                                                    <span
                                                                                        class="dropdown-header">Setting</span>
                                                                                    <a class="dropdown-item"
                                                                                        href="#">
                                                                                        <i
                                                                                            class="fe fe-edit dropdown-item-icon"></i>
                                                                                        Edit
                                                                                    </a>
                                                                                    <a class="dropdown-item"
                                                                                        href="#">
                                                                                        <i
                                                                                            class="fe fe-trash dropdown-item-icon"></i>
                                                                                        Remove
                                                                                    </a>
                                                                                </span>
                                                                            </span>
                                                                        </td>

                                                                    </tr>
                                                                @empty
                                                                    <tr>
                                                                        <td colspan="6" class="text-center">
                                                                            No students enrolled yet.
                                                                        </td>
                                                                    </tr>
                                                                @endforelse
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <div class="pt-2 pb-4">
                                                        <nav>
                                                            <ul class="pagination justify-content-center mb-0">

                                                                {{-- Previous --}}
                                                                <li
                                                                    class="page-item {{ $students->onFirstPage() ? 'disabled' : '' }}">
                                                                    <a class="page-link mx-1 rounded"
                                                                        href="{{ $students->previousPageUrl() ?? '#' }}">
                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                            width="10" height="10"
                                                                            fill="currentColor" class="bi bi-chevron-left"
                                                                            viewBox="0 0 16 16">
                                                                            <path fill-rule="evenodd"
                                                                                d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z" />
                                                                        </svg>
                                                                    </a>
                                                                </li>

                                                                {{-- Page Numbers --}}
                                                                @for ($i = 1; $i <= $students->lastPage(); $i++)
                                                                    <li
                                                                        class="page-item {{ $students->currentPage() == $i ? 'active' : '' }}">
                                                                        <a class="page-link mx-1 rounded"
                                                                            href="{{ $students->url($i) }}">
                                                                            {{ $i }}
                                                                        </a>
                                                                    </li>
                                                                @endfor

                                                                {{-- Next --}}
                                                                <li
                                                                    class="page-item {{ !$students->hasMorePages() ? 'disabled' : '' }}">
                                                                    <a class="page-link mx-1 rounded"
                                                                        href="{{ $students->nextPageUrl() ?? '#' }}">
                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                            width="10" height="10"
                                                                            fill="currentColor"
                                                                            class="bi bi-chevron-right"
                                                                            viewBox="0 0 16 16">
                                                                            <path fill-rule="evenodd"
                                                                                d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z" />
                                                                        </svg>
                                                                    </a>
                                                                </li>

                                                            </ul>
                                                        </nav>
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- tab for taking student attendance --}}
                                        </div>
                                    </div>
                                </div>
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
                                            <div class="col">
                                                <div class="summary-card">
                                                    <small>Total</small>
                                                    <h5 id="totalCount">
                                                        {{ $students->count() }}
                                                    </h5>
                                                </div>
                                            </div>
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
                                            <div class="col">
                                                <div class="summary-card warning">
                                                    <small>Late</small>
                                                    <h5 id="lateCount">0</h5>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- SEARCH -->
                                        <div class="mb-3">
                                            <input type="text" id="searchStudent" class="form-control"
                                                placeholder="🔍 Search student...">
                                        </div>

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
                                                                <div class="status-toggle">

                                                                    <span class="badge bg-success active"
                                                                        onclick="event.stopPropagation(); setStatus(this, 'present')">
                                                                        Present
                                                                    </span>

                                                                    <span class="badge bg-light text-dark"
                                                                        onclick="event.stopPropagation(); setStatus(this, 'absent')">
                                                                        Absent
                                                                    </span>

                                                                    <span class="badge bg-light text-dark"
                                                                        onclick="event.stopPropagation(); setStatus(this, 'late')">
                                                                        Late
                                                                    </span>

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

                                            <button class="btn btn-primary" onclick="saveAttendance()">
                                                💾 Save Attendance
                                            </button>
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
@endsection
