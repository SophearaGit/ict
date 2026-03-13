@extends('admin.layouts.master')
@section('page_title', isset($page_title) ? $page_title : 'Page Title Here')
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
                            <li class="breadcrumb-item active" aria-current="page">All</li>
                        </ol>
                    </nav>
                </div>
                <div class="nav btn-group" role="tablist">
                    {{-- <button class="btn btn-outline-secondary active" data-bs-toggle="tab" data-bs-target="#tabPaneGrid"
                        role="tab" aria-controls="tabPaneGrid" aria-selected="true">
                        <span class="fe fe-grid"></span>
                    </button>
                    <button class="btn btn-outline-secondary" data-bs-toggle="tab" data-bs-target="#tabPaneList"
                        role="tab" aria-controls="tabPaneList" aria-selected="false" tabindex="-1">
                        <span class="fe fe-list"></span>
                    </button> --}}
                    <a href="javascript:void;" class="btn btn-primary">Add New Courses</a>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-12 mb-4">
            <div class="row d-md-flex justify-content-between align-items-center">
                <div class="col-md-6 col-lg-8 col-xl-9">
                    <h4 class="mb-3 mb-md-0">Displaying {{ $courses->count() }} out of {{ $courses->total() }} courses</h4>
                </div>
                <div class="d-inline-flex col-md-6 col-lg-4 col-xl-3">
                    <div class="me-2">
                        <!-- Nav -->
                        <div class="nav btn-group flex-nowrap" role="tablist">
                            <button class="btn btn-outline-secondary active" data-bs-toggle="tab"
                                data-bs-target="#tabPaneGrid" role="tab" aria-controls="tabPaneGrid"
                                aria-selected="true">
                                <span class="fe fe-grid"></span>
                            </button>
                            <button class="btn btn-outline-secondary" data-bs-toggle="tab" data-bs-target="#tabPaneList"
                                role="tab" aria-controls="tabPaneList" aria-selected="false" tabindex="-1">
                                <span class="fe fe-list"></span>
                            </button>
                        </div>
                    </div>
                    <!-- List  -->
                    <select class="form-select">
                        <option value="">Sort by</option>
                        <option value="Newest">Newest</option>
                        <option value="Free">Free</option>
                        <option value="Most Popular">Most Popular</option>
                        <option value="Highest Rated">Highest Rated</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-4 col-12 mb-4 mb-lg-0">
            <!-- Card -->
            <div class="card">
                <!-- Card header -->
                <div class="card-header">
                    <h4 class="mb-0">Filter</h4>
                </div>
                <!-- Card body -->
                <div class="card-body">
                    <span class="dropdown-header px-0 mb-2">Schedules</span>
                    @php
                        // Group schedules by day first
                        $groupedByDays = collect($schedules)->groupBy(fn($s) => $s->study_day);
                    @endphp

                    @foreach ($groupedByDays as $day => $daySchedules)
                        @php
                            // Shorten day names
                            $shortDays = collect(explode('-', $day))
                                ->map(fn($d) => \Illuminate\Support\Str::of($d)->substr(0, 3))
                                ->join(' • ');

                            // Group schedules by shift within this day
                            $schedulesByShift = $daySchedules->groupBy(fn($s) => ucfirst($s->shift));
                        @endphp

                        <div class="mb-3">
                            <strong>{{ $shortDays }}</strong> <!-- Days heading -->

                            @foreach ($schedulesByShift as $shift => $shiftSchedules)
                                <div class="ms-3 mb-1">
                                    <strong>{{ $shift }}</strong> <!-- Shift heading -->

                                    @foreach ($shiftSchedules as $schedule)
                                        @php
                                            $start = \Carbon\Carbon::parse($schedule->start_time)->format('g:i ');
                                            $end = \Carbon\Carbon::parse($schedule->end_time)->format('g:i A');
                                        @endphp
                                        <div class="ms-4 form-check mb-1">
                                            <input type="checkbox" class="form-check-input"
                                                id="scheduleCheck{{ $schedule->id }}">
                                            <label class="form-check-label" for="scheduleCheck{{ $schedule->id }}">
                                                {{ $start }} – {{ $end }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>

            </div>
        </div>
        <!-- Tab content -->
        <div class="col-xl-9 col-lg-9 col-md-8 col-12">
            <div class="tab-content">
                <!-- Tab pane -->
                <div class="tab-pane fade show active pb-4" id="tabPaneGrid" role="tabpanel" aria-labelledby="tabPaneGrid">
                    <div class="row">
                        @forelse ($courses as $course)
                            <div class="col-lg-4 col-md-6 col-12">
                                <!-- Card -->
                                <div class="card mb-4 card-hover">
                                    <a href="course-single.html"><img
                                            src="{{ asset($course->thumbnail == '' ? '/default-images/staff/no-course-img.png' : $course->thumbnail) }}"
                                            alt="course" class="card-img-top"
                                            style="height: 230px; object-fit: cover;"></a>
                                    <!-- Card body -->
                                    <div class="card-body">
                                        <h4 class="mb-2 text-truncate-line-2">
                                            <a href="course-single.html" class="text-inherit">
                                                {{ $course->title }}
                                            </a>
                                        </h4>
                                        <div class="d-flex justify-content-between border-bottom py-2 mt-2">
                                            <span>
                                                Created At
                                            </span>
                                            <span class="text-dark">
                                                {{ $course->created_at->format('d M, Y') }}
                                            </span>
                                        </div>
                                        <div class="d-flex justify-content-between border-bottom py-2 mt-2">
                                            <span>
                                                Scehdule
                                            </span>
                                            <span class="text-dark">
                                                @php
                                                    $days = collect(explode('-', $course->schedule->study_day))
                                                        ->map(
                                                            fn($d) => \Illuminate\Support\Str::of($d)
                                                                ->ucfirst()
                                                                ->substr(0, 3),
                                                        )
                                                        ->join(' • ');

                                                    $start = \Carbon\Carbon::parse(
                                                        $course->schedule->start_time,
                                                    )->format('g:i');
                                                    $end = \Carbon\Carbon::parse($course->schedule->end_time)->format(
                                                        'g:i A',
                                                    );
                                                    $shift = ucfirst($course->schedule->shift);
                                                @endphp
                                                <div>
                                                    <div class="fw-semibold">{{ $days }}</div>
                                                    <div class="text-muted small">
                                                        <span class="badge bg-light text-dark">{{ $shift }}</span>
                                                        {{ $start }} – {{ $end }}
                                                    </div>
                                                </div>
                                            </span>
                                        </div>
                                        <div class="d-flex justify-content-between border-bottom py-2 ">
                                            <span>
                                                Status
                                            </span>
                                            <span class="text-dark">
                                                {{ ucfirst($course->status) }}
                                            </span>
                                        </div>
                                        {{-- students amount --}}
                                        <div class="d-flex justify-content-between border-bottom  py-2 ">
                                            <span>
                                                Students
                                            </span>
                                            <span class="text-dark">
                                                {{ $course->enrollments_count }}
                                            </span>
                                        </div>
                                        <div class="d-flex justify-content-between border-bottom py-2 ">
                                            <span>
                                                Price
                                            </span>
                                            <span class="text-dark">
                                                ${{ $course->price }}
                                            </span>
                                        </div>
                                        {{-- earning --}}
                                        <div class="d-flex justify-content-between pt-2 ">
                                            <span>
                                                Revenue
                                            </span>
                                            <span class="text-dark">
                                                ${{ $course->payments->sum('amount') }}
                                            </span>
                                        </div>
                                    </div>
                                    <!-- Card footer -->
                                    <div class="card-footer">
                                        <div class="row align-items-center g-0">
                                            <div class="col-auto">
                                                <img src="{{ $course->instructor->image == 'no-img.jpg' ? '/default-images/user/both.jpg' : $course->instructor->image }}"
                                                    class="rounded-circle avatar-xs" alt="avatar">
                                            </div>
                                            <div class="col ms-2">
                                                <span>
                                                    {{ $course->instructor ? $course->instructor->name : 'N/A' }}
                                                </span>
                                            </div>
                                            <div class="col-auto">
                                                <a href="javascript:void;" class="btn btn-sm btn-outline-secondary">
                                                    <i class="fe fe-phone"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="card mb-4 card-hover">
                                    <div class="card-body text-center">
                                        <h3 class="mb-2">No courses found.</h3>
                                        <p class="mb-4">Try adjusting your search or filter to find what you're looking
                                            for.</p>
                                        <a href="#" class="btn btn-primary">Reset Filters</a>
                                    </div>
                                </div>
                            </div>
                        @endforelse
                    </div>
                    <div class="card-footer">
                        <nav>
                            <ul class="pagination justify-content-center mb-0">
                                {{-- Previous Page Link --}}
                                <li class="page-item {{ $courses->onFirstPage() ? 'disabled' : '' }}">
                                    <a class="page-link mx-1 rounded" href="{{ $courses->previousPageUrl() ?? '#' }}"
                                        aria-label="Previous">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10"
                                            fill="currentColor" class="bi bi-chevron-left" viewBox="0 0 16 16">
                                            <path fill-rule="evenodd"
                                                d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z" />
                                        </svg>
                                    </a>
                                </li>

                                {{-- Page Numbers --}}
                                @foreach ($courses->getUrlRange(1, $courses->lastPage()) as $page => $url)
                                    <li class="page-item {{ $courses->currentPage() == $page ? 'active' : '' }}">
                                        <a class="page-link mx-1 rounded"
                                            href="{{ $url }}">{{ $page }}</a>
                                    </li>
                                @endforeach

                                {{-- Next Page Link --}}
                                <li class="page-item {{ $courses->hasMorePages() ? '' : 'disabled' }}">
                                    <a class="page-link mx-1 rounded" href="{{ $courses->nextPageUrl() ?? '#' }}"
                                        aria-label="Next">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10"
                                            fill="currentColor" class="bi bi-chevron-right" viewBox="0 0 16 16">
                                            <path fill-rule="evenodd"
                                                d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z" />
                                        </svg>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <!-- Tab pane -->
                <div class="tab-pane fade pb-4" id="tabPaneList" role="tabpanel" aria-labelledby="tabPaneList">
                    <div class="table-responsive border-0 overflow-y-hidden">
                        <table class="table mb-0 text-nowrap table-centered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Courses</th>
                                    <th>Instructor</th>
                                    <th>STATUS</th>
                                    <th>ACTION</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <a href="#" class="text-inherit">
                                            <div class="d-flex align-items-center">
                                                <div>
                                                    <img src="/frontend/assets/images/course/course-gatsby.jpg"
                                                        alt="" class="img-4by3-lg rounded">
                                                </div>
                                                <div class="ms-3">
                                                    <h4 class="mb-1 text-primary-hover">Revolutionize how you build the
                                                        web...</h4>
                                                    <span>Added on 7 July, 2023</span>
                                                </div>
                                            </div>
                                        </a>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="/frontend/assets/images/avatar/avatar-7.jpg" alt=""
                                                class="rounded-circle avatar-xs me-2">
                                            <h5 class="mb-0">Reva Yokk</h5>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge-dot bg-warning me-1 d-inline-block align-middle"></span>
                                        Pending
                                    </td>
                                    <td>
                                        <a href="#" class="btn btn-outline-secondary btn-sm">Reject</a>
                                        <a href="#" class="btn btn-success btn-sm">Approved</a>
                                    </td>
                                    <td>
                                        <span class="dropdown dropstart">
                                            <a class="btn-icon btn btn-ghost btn-sm rounded-circle" href="#"
                                                role="button" id="courseDropdown1" data-bs-toggle="dropdown"
                                                data-bs-offset="-20,20" aria-expanded="false">
                                                <i class="fe fe-more-vertical"></i>
                                            </a>
                                            <span class="dropdown-menu" aria-labelledby="courseDropdown1">
                                                <span class="dropdown-header">Settings</span>
                                                <a class="dropdown-item" href="#">
                                                    <i class="fe fe-x-circle dropdown-item-icon"></i>
                                                    Reject with Feedback
                                                </a>
                                            </span>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <a href="#" class="text-inherit">
                                            <div class="d-flex align-items-center">
                                                <div>
                                                    <img src="/frontend/assets/images/course/course-graphql.jpg"
                                                        alt="" class="img-4by3-lg rounded">
                                                </div>
                                                <div class="ms-3">
                                                    <h4 class="mb-1 text-primary-hover">Guide to Static Sites with
                                                        Gatsby...</h4>
                                                    <span>Added on 6 July, 2023</span>
                                                </div>
                                            </div>
                                        </a>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="/frontend/assets/images/avatar/avatar-6.jpg" alt=""
                                                class="rounded-circle avatar-xs me-2">
                                            <h5 class="mb-0">Brooklyn Simmons</h5>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge-dot bg-warning me-1 d-inline-block align-middle"></span>
                                        Pending
                                    </td>
                                    <td>
                                        <a href="#" class="btn btn-outline-secondary btn-sm">Reject</a>
                                        <a href="#" class="btn btn-success btn-sm">Approved</a>
                                    </td>
                                    <td>
                                        <span class="dropdown dropstart">
                                            <a class="btn-icon btn btn-ghost btn-sm rounded-circle" href="#"
                                                role="button" id="courseDropdown2" data-bs-toggle="dropdown"
                                                data-bs-offset="-20,20" aria-expanded="false">
                                                <i class="fe fe-more-vertical"></i>
                                            </a>
                                            <span class="dropdown-menu" aria-labelledby="courseDropdown2">
                                                <span class="dropdown-header">Settings</span>
                                                <a class="dropdown-item" href="#">
                                                    <i class="fe fe-x-circle dropdown-item-icon"></i>
                                                    Reject with Feedback
                                                </a>
                                            </span>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <a href="#" class="text-inherit">
                                            <div class="d-flex align-items-center">
                                                <div>
                                                    <img src="/frontend/assets/images/course/course-html.jpg"
                                                        alt="" class="img-4by3-lg rounded">
                                                </div>
                                                <div class="ms-3">
                                                    <h4 class="mb-1 text-primary-hover">The Modern JavaScript Courses ...
                                                    </h4>
                                                    <span>Added on 5 July, 2023</span>
                                                </div>
                                            </div>
                                        </a>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="/frontend/assets/images/avatar/avatar-5.jpg" alt=""
                                                class="rounded-circle avatar-xs me-2">
                                            <h5 class="mb-0">Miston Wilson</h5>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge-dot bg-warning me-1 d-inline-block align-middle"></span>
                                        Pending
                                    </td>
                                    <td>
                                        <a href="#" class="btn btn-outline-secondary btn-sm">Reject</a>
                                        <a href="#" class="btn btn-success btn-sm">Approved</a>
                                    </td>
                                    <td>
                                        <span class="dropdown dropstart">
                                            <a class="btn-icon btn btn-ghost btn-sm rounded-circle" href="#"
                                                role="button" id="courseDropdown3" data-bs-toggle="dropdown"
                                                data-bs-offset="-20,20" aria-expanded="false">
                                                <i class="fe fe-more-vertical"></i>
                                            </a>
                                            <span class="dropdown-menu" aria-labelledby="courseDropdown3">
                                                <span class="dropdown-header">Settings</span>
                                                <a class="dropdown-item" href="#">
                                                    <i class="fe fe-x-circle dropdown-item-icon"></i>
                                                    Reject with Feedback
                                                </a>
                                            </span>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <a href="#" class="text-inherit">
                                            <div class="d-flex align-items-center">
                                                <div>
                                                    <img src="/frontend/assets/images/course/course-javascript.jpg"
                                                        alt="" class="img-4by3-lg rounded">
                                                </div>
                                                <div class="ms-3">
                                                    <h4 class="mb-1 text-primary-hover">Courses JavaScript Heading Title
                                                        ...</h4>
                                                    <span>Added on 5 July, 2023</span>
                                                </div>
                                            </div>
                                        </a>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="/frontend/assets/images/avatar/avatar-10.jpg" alt=""
                                                class="rounded-circle avatar-xs me-2">
                                            <h5 class="mb-0">Guy Hawkins</h5>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge-dot bg-success me-1 d-inline-block align-middle"></span>
                                        Live
                                    </td>
                                    <td>
                                        <a href="#" class="btn btn-secondary btn-sm">Change Status</a>
                                    </td>
                                    <td>
                                        <span class="dropdown dropstart">
                                            <a class="btn-icon btn btn-ghost btn-sm rounded-circle" href="#"
                                                role="button" id="courseDropdown4" data-bs-toggle="dropdown"
                                                data-bs-offset="-20,20" aria-expanded="false">
                                                <i class="fe fe-more-vertical"></i>
                                            </a>
                                            <span class="dropdown-menu" aria-labelledby="courseDropdown4">
                                                <span class="dropdown-header">Settings</span>
                                                <a class="dropdown-item" href="#">
                                                    <i class="fe fe-x-circle dropdown-item-icon"></i>
                                                    Reject with Feedback
                                                </a>
                                            </span>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <a href="#" class="text-inherit">
                                            <div class="d-flex align-items-center">
                                                <div>
                                                    <img src="/frontend/assets/images/course/course-node.jpg"
                                                        alt="" class="img-4by3-lg rounded">
                                                </div>
                                                <div class="ms-3">
                                                    <h4 class="mb-1 text-primary-hover">Get Start with Node Heading Title
                                                        ...</h4>
                                                    <span>Added on 5 July, 2023</span>
                                                </div>
                                            </div>
                                        </a>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="/frontend/assets/images/avatar/avatar-3.jpg" alt=""
                                                class="rounded-circle avatar-xs me-2">
                                            <h5 class="mb-0">Sina Ray</h5>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge-dot bg-success me-1 d-inline-block align-middle"></span>
                                        Live
                                    </td>
                                    <td>
                                        <a href="#" class="btn btn-secondary btn-sm">Change Status</a>
                                    </td>
                                    <td>
                                        <span class="dropdown dropstart">
                                            <a class="btn-icon btn btn-ghost btn-sm rounded-circle" href="#"
                                                role="button" id="courseDropdown5" data-bs-toggle="dropdown"
                                                data-bs-offset="-20,20" aria-expanded="false">
                                                <i class="fe fe-more-vertical"></i>
                                            </a>
                                            <span class="dropdown-menu" aria-labelledby="courseDropdown5">
                                                <span class="dropdown-header">Settings</span>
                                                <a class="dropdown-item" href="#">
                                                    <i class="fe fe-x-circle dropdown-item-icon"></i>
                                                    Reject with Feedback
                                                </a>
                                            </span>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <a href="#" class="text-inherit">
                                            <div class="d-flex align-items-center">
                                                <div>
                                                    <img src="/frontend/assets/images/course/course-laravel.jpg"
                                                        alt="" class="img-4by3-lg rounded">
                                                </div>
                                                <div class="ms-3">
                                                    <h4 class="mb-1 text-primary-hover">Get Start with Laravel...</h4>
                                                    <span>Added on 5 July, 2023</span>
                                                </div>
                                            </div>
                                        </a>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="/frontend/assets/images/avatar/avatar-9.jpg" alt=""
                                                class="rounded-circle avatar-xs me-2">
                                            <h5 class="mb-0">Sobo Rikhan</h5>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge-dot bg-success me-1 d-inline-block align-middle"></span>
                                        Live
                                    </td>
                                    <td>
                                        <a href="#" class="btn btn-secondary btn-sm">Change Status</a>
                                    </td>
                                    <td>
                                        <span class="dropdown dropstart">
                                            <a class="btn-icon btn btn-ghost btn-sm rounded-circle" href="#"
                                                role="button" id="courseDropdown6" data-bs-toggle="dropdown"
                                                data-bs-offset="-20,20" aria-expanded="false">
                                                <i class="fe fe-more-vertical"></i>
                                            </a>
                                            <span class="dropdown-menu" aria-labelledby="courseDropdown6">
                                                <span class="dropdown-header">Settings</span>
                                                <a class="dropdown-item" href="#">
                                                    <i class="fe fe-x-circle dropdown-item-icon"></i>
                                                    Reject with Feedback
                                                </a>
                                            </span>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <a href="#" class="text-inherit">
                                            <div class="d-flex align-items-center">
                                                <div>
                                                    <img src="/frontend/assets/images/course/course-react.jpg"
                                                        alt="" class="img-4by3-lg rounded">
                                                </div>
                                                <div class="ms-3">
                                                    <h4 class="mb-1 text-primary-hover">Get Start with React...</h4>
                                                    <span>Added on 4 July, 2023</span>
                                                </div>
                                            </div>
                                        </a>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="/frontend/assets/images/avatar/avatar-2.jpg" alt=""
                                                class="rounded-circle avatar-xs me-2">
                                            <h5 class="mb-0">April Noms</h5>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge-dot bg-success me-1 d-inline-block align-middle"></span>
                                        Live
                                    </td>
                                    <td>
                                        <a href="#" class="btn btn-secondary btn-sm">Change Status</a>
                                    </td>
                                    <td>
                                        <span class="dropdown dropstart">
                                            <a class="btn-icon btn btn-ghost btn-sm rounded-circle" href="#"
                                                role="button" id="courseDropdown7" data-bs-toggle="dropdown"
                                                data-bs-offset="-20,20" aria-expanded="false">
                                                <i class="fe fe-more-vertical"></i>
                                            </a>
                                            <span class="dropdown-menu" aria-labelledby="courseDropdown7">
                                                <span class="dropdown-header">Settings</span>
                                                <a class="dropdown-item" href="#">
                                                    <i class="fe fe-x-circle dropdown-item-icon"></i>
                                                    Reject with Feedback
                                                </a>
                                            </span>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <a href="#" class="text-inherit">
                                            <div class="d-flex align-items-center">
                                                <div>
                                                    <img src="/frontend/assets/images/course/course-angular.jpg"
                                                        alt="" class="img-4by3-lg rounded">
                                                </div>
                                                <div class="ms-3">
                                                    <h4 class="mb-1 text-primary-hover">Get Start with Angular...</h4>
                                                    <span>Added on 3 July, 2023</span>
                                                </div>
                                            </div>
                                        </a>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="/frontend/assets/images/avatar/avatar-4.jpg" alt=""
                                                class="rounded-circle avatar-xs me-2">
                                            <h5 class="mb-0">Jacob Jones</h5>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge-dot bg-warning me-1 d-inline-block align-middle"></span>
                                        Pending
                                    </td>
                                    <td>
                                        <a href="#" class="btn btn-outline-secondary btn-sm">Reject</a>
                                        <a href="#" class="btn btn-success btn-sm">Approved</a>
                                    </td>
                                    <td>
                                        <span class="dropdown dropstart">
                                            <a class="btn-icon btn btn-ghost btn-sm rounded-circle" href="#"
                                                role="button" id="courseDropdown8" data-bs-toggle="dropdown"
                                                data-bs-offset="-20,20" aria-expanded="false">
                                                <i class="fe fe-more-vertical"></i>
                                            </a>
                                            <span class="dropdown-menu" aria-labelledby="courseDropdown8">
                                                <span class="dropdown-header">Settings</span>
                                                <a class="dropdown-item" href="#">
                                                    <i class="fe fe-x-circle dropdown-item-icon"></i>
                                                    Reject with Feedback
                                                </a>
                                            </span>
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection
