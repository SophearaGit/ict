@extends('frontend.layouts.master')
@section('page_title', isset($page_title) ? $page_title : 'Page Title Here')
@push('styles')
    <style>
        .text-purple {
            color: #6f42c1;
        }

        .sheet {
            width: 100%;
            max-width: 1100px;
            margin: auto;
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        }

        /* Header */
        .top-header {
            display: flex;
            align-items: center;
            background: linear-gradient(135deg, #6f42c1, #8e63d3);
            color: #fff;
        }

        .logo {
            width: 100px;
            height: 100px;
            background: rgba(255, 255, 255, 0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            border-right: 1px solid rgba(255, 255, 255, 0.2);
        }

        .title {
            flex: 1;
            text-align: center;
            font-size: 20px;
            font-weight: bold;
        }

        .sub-title {
            background: #f4c542;
            text-align: center;
            font-weight: 600;
            padding: 6px;
            font-size: 14px;
        }

        /* Info */
        .info-row {
            display: flex;
            border-bottom: 1px solid #eee;
        }

        .info-label {
            width: 180px;
            padding: 10px;
            font-weight: 600;
            background: #f8f9fa;
        }

        .info-value {
            flex: 1;
            padding: 10px;
        }

        /* Table */
        .sheet table {
            width: 100%;
            border-collapse: collapse;
        }

        .sheet th {
            background: #6f42c1;
            color: #fff;
            position: sticky;
            top: 0;
            z-index: 2;
            font-size: 13px;
        }

        .sheet th,
        .sheet td {
            padding: 8px;
            border: 1px solid #eee;
            text-align: center;
        }

        /* Zebra rows */
        .sheet tbody tr:nth-child(even) {
            background: #fafafa;
        }

        /* Hover effect */
        .sheet tbody tr:hover {
            background: #f1f5ff;
            transition: 0.2s;
        }

        /* Inputs */
        .sheet input {
            width: 100%;
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 6px;
            font-size: 13px;
            text-align: center;
        }

        /* Focus effect */
        .sheet input:focus {
            border-color: #6f42c1;
            box-shadow: 0 0 0 2px rgba(111, 66, 193, 0.15);
            outline: none;
        }

        /* Readonly fields */
        .sheet input[readonly] {
            background: #f5f5f5;
            font-weight: 600;
        }

        /* Button */
        .btn-primary {
            background: #6f42c1;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
        }

        .btn-primary:hover {
            background: #5a34a3;
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
                                        <a class="nav-link active" id="attendance-tab" data-bs-toggle="pill"
                                            href="#attendance" role="tab" aria-controls="attendance"
                                            aria-selected="false" tabindex="-1">My Attendance</a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" id="students-tab" data-bs-toggle="pill" href="#students"
                                            role="tab" aria-controls="students" aria-selected="false"
                                            tabindex="-1">Students</a>
                                    </li>
                                </ul>

                            </div>
                        </div>
                        <!-- Card Body -->
                        <div class="card-body">
                            <div class="tab-content" id="tabContent">
                                {{-- My Attendance --}}
                                <div class="tab-pane fade active show" id="attendance" role="tabpanel"
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


                                                                    <!-- Table -->
                                                                    <form
                                                                        action="{{ route('staff.teacher.attendance.update') }}"
                                                                        method="POST">
                                                                        @csrf
                                                                        <input type="hidden" name="course_id"
                                                                            value="{{ $course->id }}">
                                                                        <input type="hidden" name="teacher_id"
                                                                            value="{{ $course->instructor->id ?? '' }}">
                                                                        <input type="hidden" name="schedule_id"
                                                                            value="{{ $course->schedule->id ?? '' }}">
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
                                                                                    <th>Late (min)</th>
                                                                                    <th>Note</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody id="attendanceBody">
                                                                                @php
                                                                                    $attendances =
                                                                                        $course->teacherAttendances;
                                                                                @endphp

                                                                                @if ($attendances->isNotEmpty())
                                                                                    @foreach ($attendances as $index => $attendance)
                                                                                        <tr>
                                                                                            <td style="padding: 10px">
                                                                                                {{ $index + 1 }}
                                                                                            </td>

                                                                                            <td style="display:none;">
                                                                                                <input type="hidden"
                                                                                                    name="attendances[{{ $index }}][id]"
                                                                                                    value="{{ $attendance->id }}">
                                                                                            </td>

                                                                                            <td>
                                                                                                <input type="date"
                                                                                                    name="attendances[{{ $index }}][date]"
                                                                                                    value="{{ $attendance->date }}"
                                                                                                    class="form-control text-dark"
                                                                                                    readonly>
                                                                                            </td>

                                                                                            <td>
                                                                                                <input type="time"
                                                                                                    name="attendances[{{ $index }}][start_time]"
                                                                                                    value="{{ $attendance->start_time }}"
                                                                                                    class="form-control text-dark">
                                                                                            </td>

                                                                                            <td>
                                                                                                <input type="time"
                                                                                                    name="attendances[{{ $index }}][end_time]"
                                                                                                    value="{{ $attendance->end_time }}"
                                                                                                    class="form-control text-dark">
                                                                                            </td>

                                                                                            <td>
                                                                                                <input type="text"
                                                                                                    name="attendances[{{ $index }}][total_hours]"
                                                                                                    value="{{ $attendance->total_hours }}"
                                                                                                    class="form-control total-hours text-uppercase text-dark text-center"
                                                                                                    readonly>
                                                                                            </td>

                                                                                            <td>
                                                                                                <input type="text"
                                                                                                    name="attendances[{{ $index }}][actual_hours]"
                                                                                                    value="{{ $attendance->actual_hours }}"
                                                                                                    class="form-control actual-hours text-uppercase text-dark text-center"
                                                                                                    readonly>
                                                                                            </td>

                                                                                            <td>
                                                                                                <input type="text"
                                                                                                    name="attendances[{{ $index }}][room]"
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
                                                                                                    value="{{ $attendance->late_reason }}"
                                                                                                    class="form-control">
                                                                                            </td>
                                                                                        </tr>
                                                                                    @endforeach
                                                                                @endif
                                                                                @php
                                                                                    $nextIndex = $attendances->count();
                                                                                @endphp

                                                                                <tr>
                                                                                    <td style="padding: 10px">
                                                                                        {{ $nextIndex + 1 }}</td>

                                                                                    <td style="display:none;">
                                                                                        <input type="hidden"
                                                                                            name="attendances[{{ $nextIndex }}][id]"
                                                                                            value="">
                                                                                    </td>

                                                                                    <td>
                                                                                        <input type="date"
                                                                                            name="attendances[{{ $nextIndex }}][date]"
                                                                                            class="form-control"
                                                                                            value="{{ now()->format('Y-m-d') }}"
                                                                                            readonly>
                                                                                    </td>

                                                                                    <td>
                                                                                        <input type="time"
                                                                                            name="attendances[{{ $nextIndex }}][start_time]"
                                                                                            class="form-control">
                                                                                    </td>

                                                                                    <td>
                                                                                        <input type="time"
                                                                                            name="attendances[{{ $nextIndex }}][end_time]"
                                                                                            class="form-control">
                                                                                    </td>

                                                                                    <td>
                                                                                        <input type="text"
                                                                                            name="attendances[{{ $nextIndex }}][total_hours]"
                                                                                            class="form-control total-hours"
                                                                                            readonly>
                                                                                    </td>

                                                                                    <td>
                                                                                        <input type="text"
                                                                                            name="attendances[{{ $nextIndex }}][actual_hours]"
                                                                                            class="form-control actual-hours"
                                                                                            readonly>
                                                                                    </td>

                                                                                    <td>
                                                                                        <input type="text"
                                                                                            name="attendances[{{ $nextIndex }}][room]"
                                                                                            class="form-control">
                                                                                    </td>

                                                                                    <td>
                                                                                        <input type="number"
                                                                                            name="attendances[{{ $nextIndex }}][late_minutes]"
                                                                                            class="form-control">
                                                                                    </td>

                                                                                    <td>
                                                                                        <input type="text"
                                                                                            name="attendances[{{ $nextIndex }}][late_reason]"
                                                                                            class="form-control">
                                                                                    </td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                        <button type="submit"
                                                                            class="btn btn-primary mt-3">
                                                                            Save Attendance
                                                                        </button>
                                                                    </form>
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
                        <!-- Card body -->
                        <div class="card-body">
                            <!-- Price single page -->
                            <div class="mb-3">
                                <span class="text-dark fw-bold h2">
                                    Your Earning: ${{ number_format($course->revenue, 2) }}</td>
                                </span>
                            </div>
                            {{-- <div class="d-grid">
                                <a href="#" class="btn btn-primary mb-2">Start Free Month</a>
                                <a href="pricing.html" class="btn btn-outline-primary">Get Full Access</a>
                            </div> --}}
                        </div>
                    </div>
                    <!-- Card -->
                    <div class="card mb-4 shadow-sm border-0 rounded-4">
                        {{-- <div class="card-header bg-white border-0">
                            <h4 class="mb-0 fw-bold">📊 Quick Overview</h4>
                        </div> --}}

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
                            Your other courses
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
                                            Earning: ${{ number_format($course->revenue, 2) }}
                                        </span>
                                    </div>
                                </div>
                                <!-- Card footer -->
                                <div class="card-footer">
                                    <div class="row align-items-center g-0">
                                        <div class="col-auto">
                                            <img src="
                                                {{ asset($course->instructor->image == 'no-img.jpg' ? '\default-images\user\both.jpg' : $course->instructor->image) }}
                                            "
                                                class="rounded-circle avatar-xs" alt="avatar">
                                        </div>
                                        <div class="col ms-2">
                                            <span>
                                                {{ $course->instructor->name }}
                                            </span>
                                        </div>
                                        <div class="col-auto">
                                            <a
                                                href="
                                                {{ route('instructor.courses.real_time.show', $course->id) }}
                                            ">
                                                {{-- eye icon --}}
                                                <i
                                                    class="
                                                fe fe-eye
                                                "></i>
                                            </a>
                                        </div>
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
