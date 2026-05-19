@extends('admin.layouts.master')
@section('page_title', isset($page_title) ? $page_title : 'Page Title Here')
@section('content')
    <div class="row">
        <div class="col-lg-12 col-md-12 col-12">
            <div class="border-bottom pb-3 mb-3 d-lg-flex justify-content-between align-items-center">
                <div class="mb-3 mb-lg-0">
                    <h1 class="mb-0 h2 fw-bold">Dashboard</h1>
                </div>
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <form method="GET" action="{{ route('admin.dashboard') }}"
                        class="d-flex align-items-center gap-2 flex-wrap">
                        {{-- Quick preset buttons --}}
                        <a href="{{ route('admin.dashboard', ['preset' => 'today']) }}"
                            class="btn btn-sm {{ request('preset') === 'today' ? 'btn-primary' : 'btn-outline-secondary' }}">
                            Today
                        </a>
                        <a href="{{ route('admin.dashboard', ['preset' => 'week']) }}"
                            class="btn btn-sm {{ request('preset') === 'week' ? 'btn-primary' : 'btn-outline-secondary' }}">
                            This Week
                        </a>
                        <a href="{{ route('admin.dashboard', ['preset' => 'month']) }}"
                            class="btn btn-sm {{ request('preset') === 'month' ? 'btn-primary' : 'btn-outline-secondary' }}">
                            This Month
                        </a>
                        <a href="{{ route('admin.dashboard', ['preset' => 'year']) }}"
                            class="btn btn-sm {{ request('preset') === 'year' ? 'btn-primary' : 'btn-outline-secondary' }}">
                            This Year
                        </a>
                        <a href="{{ route('admin.dashboard', ['preset' => 'all']) }}"
                            class="btn btn-sm {{ request('preset') === 'all' || !request('preset') ? 'btn-primary' : 'btn-outline-secondary' }}">
                            All Time
                        </a>
                        <div class="vr mx-1"></div>
                        {{-- Custom date range --}}
                        <div class="input-group" style="width: 160px;">
                            <input class="form-control flatpickr" type="text" name="from" id="from_date"
                                placeholder="From date" value="{{ $from }}" autocomplete="off">
                            <span class="input-group-text"><i class="fe fe-calendar"></i></span>
                        </div>
                        <div class="input-group" style="width: 160px;">
                            <input class="form-control flatpickr" type="text" name="to" id="to_date"
                                placeholder="To date" value="{{ $to }}" autocomplete="off">
                            <span class="input-group-text"><i class="fe fe-calendar"></i></span>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fe fe-filter me-1"></i> Filter
                        </button>
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                            <i class="fe fe-refresh-cw me-1"></i> Reset
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-3 col-lg-6 col-md-12 col-12">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3 lh-1">
                        <div>
                            <span class="fs-6 text-uppercase fw-semibold ls-md">Sales</span>
                        </div>
                        <div>
                            <span class="fe fe-shopping-bag fs-3 text-primary"></span>
                        </div>
                    </div>
                    <h2 class="fw-bold mb-1">
                        ${{ $total_revenue }}
                    </h2>
                    <span class="{{ $is_up ? 'text-success' : 'text-danger' }} fw-semibold">
                        <i class="fe {{ $is_up ? 'fe-trending-up' : 'fe-trending-down' }} me-1"></i>
                        {{ $is_up ? '+' : '' }}{{ $change }}$
                    </span>
                    <span class="ms-1 fw-medium">Number of sales</span>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-12 col-12">

            <!-- Card -->
            <div class="card mb-4">

                <!-- Card body -->
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3 lh-1">
                        <div>
                            <span class="fs-6 text-uppercase fw-semibold ls-md">Courses</span>
                        </div>
                        <div>
                            <span class="fe fe-book-open fs-3 text-primary"></span>
                        </div>
                    </div>
                    <h2 class="fw-bold mb-1">
                        {{ $total_courses }}
                    </h2>
                    <span class="text-danger fw-semibold">
                        {{ $pending_courses }}+
                    </span>
                    <span class="ms-1 fw-medium">Number of pending</span>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-12 col-12">

            <!-- Card -->
            <div class="card mb-4">

                <!-- Card body -->
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3 lh-1">
                        <div>
                            <span class="fs-6 text-uppercase fw-semibold ls-md">Students</span>
                        </div>
                        <div>
                            <span class="fe fe-users fs-3 text-primary"></span>
                        </div>
                    </div>
                    <h2 class="fw-bold mb-1">
                        {{ $total_students }}
                    </h2>
                    <span class="text-success fw-semibold">
                        <i class="fe fe-trending-up me-1"></i>
                        +{{ $new_students }}
                    </span>
                    <span class="ms-1 fw-medium">Students</span>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-12 col-12">

            <!-- Card -->
            <div class="card mb-4">

                <!-- Card body -->
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3 lh-1">
                        <div>
                            <span class="fs-6 text-uppercase fw-semibold ls-md">Instructor</span>
                        </div>
                        <div>
                            <span class="fe fe-user-check fs-3 text-primary"></span>
                        </div>
                    </div>
                    <h2 class="fw-bold mb-1">
                        {{ $total_instructors }}
                    </h2>
                    <span class="text-success fw-semibold">
                        <i class="fe fe-trending-up me-1"></i>
                        +{{ $new_instructors }}
                    </span>
                    <span class="ms-1 fw-medium">Instructor</span>
                </div>
            </div>
        </div>
    </div>
    {{-- <div class="row">
        <div class="col-xl-8 col-lg-12 col-md-12 col-12">

            <!-- Card -->
            <div class="card mb-4">

                <!-- Card header -->
                <div
                    class="card-header align-items-center card-header-height d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0">Earnings</h4>
                    </div>
                    <div>
                        <div class="dropdown dropstart">
                            <a class="btn-icon btn btn-ghost btn-sm rounded-circle" href="#" role="button"
                                id="courseDropdown1" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fe fe-more-vertical"></i>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="courseDropdown1">
                                <span class="dropdown-header">Settings</span>
                                <a class="dropdown-item" href="#">
                                    <i class="fe fe-external-link dropdown-item-icon"></i>
                                    Export
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fe fe-mail dropdown-item-icon"></i>
                                    Email Report
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fe fe-download dropdown-item-icon"></i>
                                    Download
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card body -->
                <div class="card-body">

                    <!-- Earning chart -->
                    <div id="earning" class="apex-charts"></div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-12 col-md-12 col-12">

            <!-- Card -->
            <div class="card mb-4">

                <!-- Card header -->
                <div
                    class="card-header align-items-center card-header-height d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0">Traffic</h4>
                    </div>
                    <div>
                        <div class="dropdown dropstart">
                            <a class="btn-icon btn btn-ghost btn-sm rounded-circle" href="#" role="button"
                                id="courseDropdown2" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fe fe-more-vertical"></i>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="courseDropdown2">
                                <span class="dropdown-header">Settings</span>
                                <a class="dropdown-item" href="#">
                                    <i class="fe fe-external-link dropdown-item-icon"></i>
                                    Export
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fe fe-mail dropdown-item-icon"></i>
                                    Email Report
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fe fe-download dropdown-item-icon"></i>
                                    Download
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card body -->
                <div class="card-body">
                    <div id="traffic" class="apex-charts d-flex justify-content-center"></div>
                </div>
            </div>
        </div>
    </div> --}}
    <div class="row">
        <div class="col-xl-4 col-lg-12 col-md-12 col-12 mb-4">

            <!-- Card -->
            <div class="card h-100">

                <!-- Card header -->
                <div class="card-header d-flex align-items-center justify-content-between card-header-height">
                    <h4 class="mb-0">Popular Teacher</h4>
                    <a href="{{ route('admin.instructor.index') }}" class="btn btn-outline-secondary btn-sm">View all</a>
                </div>

                <!-- Card body -->
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        @forelse($new_instructors_list as $index => $instructor)
                            <li class="list-group-item px-0 {{ $loop->first ? 'pt-0' : '' }}">
                                <div class="row">
                                    <div class="col-auto">
                                        <div class="avatar avatar-md">
                                            <img alt="avatar" src="{{ $instructor['image'] }}" class="rounded-circle">
                                        </div>
                                    </div>
                                    <div class="col ms-n3">
                                        <h4 class="mb-0 h5">{{ $instructor['name'] }}</h4>
                                        <span class="me-2 fs-6">
                                            <span
                                                class="text-dark me-1 fw-semibold">{{ $instructor['course_count'] }}</span>
                                            Courses
                                        </span>
                                        <span class="fs-6">
                                            <span
                                                class="text-dark me-1 fw-semibold">{{ number_format($instructor['student_count']) }}</span>
                                            Students
                                        </span>
                                    </div>
                                    <div class="col-auto">
                                        <span class="dropdown dropstart">
                                            <a class="btn-icon btn btn-ghost btn-sm rounded-circle" href="#"
                                                role="button" id="instructorDropdown{{ $index }}"
                                                data-bs-toggle="dropdown" data-bs-offset="-20,20" aria-expanded="false">
                                                <i class="fe fe-more-vertical"></i>
                                            </a>
                                            <span class="dropdown-menu"
                                                aria-labelledby="instructorDropdown{{ $index }}">
                                                <span class="dropdown-header">Settings</span>
                                                <a class="dropdown-item" href="#">
                                                    <i class="fe fe-edit dropdown-item-icon"></i>
                                                    Edit
                                                </a>
                                                <a class="dropdown-item" href="#">
                                                    <i class="fe fe-trash dropdown-item-icon"></i>
                                                    Remove
                                                </a>
                                            </span>
                                        </span>
                                    </div>
                                </div>
                            </li>
                        @empty
                            <li class="list-group-item px-0 pt-0">
                                <div class="text-center text-muted py-3">
                                    <i class="fe fe-user-x fs-3 d-block mb-2"></i>
                                    No new instructors in this period
                                </div>
                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-12 col-md-12 col-12 mb-4">

            <!-- Card -->
            <div class="card h-100">

                <!-- Card header -->
                <div class="card-header d-flex align-items-center justify-content-between card-header-height">
                    <h4 class="mb-0">Recent Courses</h4>
                    <a href="{{ route('admin.courses.realtime.index') }}" class="btn btn-outline-secondary btn-sm">View
                        all</a>
                </div>

                <!-- Card body -->
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        @forelse($recent_courses as $index => $course)
                            <li class="list-group-item px-0 {{ $loop->first ? 'pt-0' : '' }}">
                                <div class="row">
                                    <div class="col-md-3 col-12 mb-3 mb-md-0">
                                        <a href="#">
                                            <img src="{{ $course['thumbnail'] }}" alt="{{ $course['title'] }}"
                                                class="img-fluid rounded">
                                        </a>
                                    </div>
                                    <div class="col-md-8 col-10">
                                        <a href="#">
                                            <h5 class="text-primary-hover">{{ Str::limit($course['title'], 40) }}</h5>
                                        </a>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $course['instructor_image'] }}"
                                                alt="{{ $course['instructor_name'] }}"
                                                class="rounded-circle avatar-xs me-2">
                                            <span class="fs-6">{{ $course['instructor_name'] }}</span>
                                        </div>
                                    </div>
                                    <div class="col-1 col-auto d-flex justify-content-center">
                                        <span class="dropdown dropstart">
                                            <a class="btn-icon btn btn-ghost btn-sm rounded-circle" href="#"
                                                role="button" id="courseDropdown{{ $index }}"
                                                data-bs-toggle="dropdown" data-bs-offset="-20,20" aria-expanded="false">
                                                <i class="fe fe-more-vertical"></i>
                                            </a>
                                            <span class="dropdown-menu"
                                                aria-labelledby="courseDropdown{{ $index }}">
                                                <span class="dropdown-header">Settings</span>
                                                <a class="dropdown-item" href="#">
                                                    <i class="fe fe-edit dropdown-item-icon"></i>Edit
                                                </a>
                                                <a class="dropdown-item" href="#">
                                                    <i class="fe fe-trash dropdown-item-icon"></i>Remove
                                                </a>
                                            </span>
                                        </span>
                                    </div>
                                </div>
                            </li>
                        @empty
                            <li class="list-group-item px-0 pt-0">
                                <div class="text-center text-muted py-3">
                                    <i class="fe fe-book fs-3 d-block mb-2"></i>
                                    No courses in this period
                                </div>
                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-12 col-md-12 col-12 mb-4">

            <!-- Card -->
            <div class="card h-100">

                <!-- Card header -->
                <div class="card-header card-header-height d-flex align-items-center">
                    <h4 class="mb-0">Activity</h4>
                </div>

                <!-- Card body -->
                <div class="card-body">
                    <ul class="list-group list-group-flush list-timeline-activity">
                        @forelse($activities as $activity)
                            <li class="list-group-item px-0 pt-0 border-0 mb-2">
                                <div class="row">
                                    <div class="col-auto">
                                        <div
                                            class="avatar avatar-md avatar-indicators {{ $activity['is_read'] ? 'avatar-offline' : 'avatar-online' }}">
                                            <img alt="avatar" src="{{ $activity['image'] }}" class="rounded-circle">
                                        </div>
                                    </div>
                                    <div class="col ms-n2">
                                        <h4 class="mb-0 h5">{{ $activity['title'] }}</h4>
                                        <p class="mb-1">{{ $activity['message'] }}</p>
                                        <span class="fs-6">{{ $activity['time'] }}</span>
                                    </div>
                                </div>
                            </li>
                        @empty
                            <li class="list-group-item px-0 pt-0 border-0">
                                <div class="text-center text-muted py-3">
                                    <i class="fe fe-bell-off fs-3 d-block mb-2"></i>
                                    No activity in this period
                                </div>
                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
