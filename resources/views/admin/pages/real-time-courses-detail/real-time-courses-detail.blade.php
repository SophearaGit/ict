@extends('admin.layouts.master')
@section('page_title', isset($page_title) ? $page_title : 'Page Title Here')
@push('styles')
    @include('admin.pages.real-time-courses-detail.style.teacher-attendant-table')
@endpush
@section('content')
    <div class="row">
        <div class="col-lg-12 col-md-12 col-12">
            <!-- Page Header -->
            <div class="border-bottom pb-3 mb-3 d-md-flex align-items-center justify-content-between">
                <div class="mb-3 mb-md-0">
                    <h1 class="mb-1 h2 fw-bold">
                        Courses ( Real Time )
                    </h1>
                    <!-- Breadcrumb -->
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item active">
                                <a href="javascript:void;">
                                    Courses ( Real Time )
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
                <div class="col-lg-8 col-md-12 col-12 mt-n8 mb-4 mb-lg-0">
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
                                <div class="tab-pane fade active show" id="teacher-attendance" role="tabpanel"
                                    aria-labelledby="teacher-attendance-tab">

                                    <div class="sheet">

                                        <!-- Header -->
                                        <div class="top-header">
                                            <div class="logo">
                                                <i class="fe fe-calendar align-middle me-2 text-primary fs-2"></i>
                                            </div>
                                            <div>
                                                <div class="title">ICT Professional Training Center</div>
                                                <div class="sub-title">Teacher's Attendant</div>
                                            </div>
                                        </div>

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
                                                    <th>Late (min)</th>
                                                    {{-- <th>Note</th> --}}
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
                                                            {{--
                                                            <td>
                                                                <input type="text"
                                                                    name="attendances[{{ $index }}][room]"
                                                                    value="{{ $attendance->room }}"
                                                                    class="form-control text-uppercase text-dark text-center"
                                                                    readonly>
                                                            </td> --}}

                                                            <td>
                                                                <input type="number"
                                                                    name="attendances[{{ $index }}][late_minutes]"
                                                                    value="{{ $attendance->late_minutes }}"
                                                                    class="form-control text-center" readonly>
                                                            </td>
                                                            {{-- <td>
                                                                <input type="text"
                                                                    name="attendances[{{ $index }}][late_reason]"
                                                                    value="{{ $attendance->late_reason }}"
                                                                    class="form-control"
                                                                    readonly>
                                                            </td> --}}
                                                        </tr>
                                                    @endforeach
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-12 col-12 mt-lg-n8">
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
                    <div class="card mb-4">
                        <div>
                            <!-- Card header -->
                            <div class="card-header">
                                <h4 class="mb-0">
                                    Full Course Details
                                </h4>
                            </div>
                            <ul class="list-group list-group-flush">
                                {{-- enrollments count --}}
                                <li class="list-group-item bg-transparent">
                                    <i class="fe fe-users align-middle me-2 text-primary"></i>
                                    {{ $course->enrollments->count() }} Enrolled
                                </li>
                                {{-- duration --}}
                                <li class="list-group-item bg-transparent">
                                    <i class="fe fe-clock align-middle me-2 text-info"></i>
                                    {{ $course->duration }} Hours
                                </li>
                                {{-- complete sessions and total --}}
                                <li class="list-group-item bg-transparent">
                                    <i class="fe fe-calendar align-middle me-2 text-secondary"></i>
                                    {{ $course->completed_sessions ?? 0 }} / {{ $course->total_sessions ?? 0 }} Sessions
                                    Completed
                                </li>
                            </ul>
                        </div>
                    </div>
                    <!-- Card -->
                    <div class="card">
                        <!-- Card body -->
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="position-relative">
                                    <img src="{{ $course->instructor->image === 'no-img.jpg' ? '/default-images/user/both.jpg' : $course->instructor->image }}"
                                        alt="teacher-image" class="rounded-circle avatar-xl">
                                    <a href="#" class="position-absolute mt-2 ms-n3" data-bs-toggle="tooltip"
                                        data-placement="top" aria-label="Verifed" data-bs-original-title="Verifed">
                                        <img src="{{ asset('/frontend/assets/images/svg/checked-mark.svg') }}"
                                            alt="checked-mark" height="30" width="30">
                                    </a>
                                </div>
                                <div class="ms-4">
                                    <h4 class="mb-0 text-capitalize">
                                        {{ $course->instructor->name ?? 'No Instructor' }}
                                    </h4>


                                    @if ($course->instructor->courses->isNotEmpty())
                                        <div class="tags text-capitalize">
                                            @foreach ($course->instructor->courses->unique('title') as $course)
                                                <span class="tag">
                                                    {{ $course->title }},
                                                </span>
                                            @endforeach...
                                        </div>
                                    @endif




                                    <p class="fs-6 mb-1 d-flex align-items-center">
                                        <span class="text-warning">4.5</span>
                                        <span class="mx-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10"
                                                fill="currentColor" class="bi bi-star-fill text-warning"
                                                viewBox="0 0 16 16">
                                                <path
                                                    d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z">
                                                </path>
                                            </svg>
                                        </span>
                                        Instructor Rating
                                    </p>
                                </div>
                            </div>
                            <div class="border-top row mt-3 border-bottom mb-3 g-0">
                                <div class="col">
                                    <div class="pe-1 ps-2 py-3">
                                        <h5 class="mb-0">
                                            {{ $course->instructor->courses->sum(fn($course) => $course->enrollments->count()) }}
                                        </h5>
                                        <span>Students</span>
                                    </div>
                                </div>
                                <div class="col border-start">
                                    <div class="pe-1 ps-3 py-3">
                                        <h5 class="mb-0">
                                            {{ $course->instructor->courses->count() }}
                                        </h5>
                                        <span>Courses</span>
                                    </div>
                                </div>
                                <div class="col border-start">
                                    <div class="pe-1 ps-3 py-3">
                                        <h5 class="mb-0">12,230</h5>
                                        <span>Reviews</span>
                                    </div>
                                </div>
                            </div>
                            <p>
                                {{ $course->instructor->headline ?? 'No bio available for this instructor.' }}
                            </p>
                            <a href="instructor-profile.html" class="btn btn-outline-secondary btn-sm">
                                View Profile
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Card -->
            <div class="pt-8 pb-3">
                <div class="row d-md-flex align-items-center mb-4">
                    <div class="col-12">
                        <h2 class="mb-0">Related Courses</h2>
                    </div>
                </div>
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
