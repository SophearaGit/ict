@extends('frontend.staff.layout.master')
@section('page_title', isset($page_title) ? $page_title : 'Page Title Here')
@section('content')
    @include('frontend.staff.pages.partials.breadcrumb')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-md-flex align-items-center mb-9">
                        <div>
                            <h5 class="card-title fw-semibold mb-2">
                                Courses
                            </h5>
                            <p class="card-subtitle text-muted">
                                You can view details, edit, or delete
                                courses from this page.
                            </p>
                        </div>
                        <div class="ms-auto mt-4 mt-md-0">
                            {{-- use route instead --}}
                            <a href="{{ route('staff.courses.create') }}" class="btn btn-primary">
                                <i class="ti ti-circle-plus me-1"></i> Create Course
                            </a>
                        </div>
                    </div>
                    <!-- Tab panes -->
                    <div class="tab-content mt-3">
                        <div class="tab-pane active" id="home" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table align-middle mb-0 text-nowrap">
                                    <thead>
                                        <tr class="text-muted fw-semibold">
                                            {{-- <th scope="col" class="ps-0">NO</th> --}}
                                            <th scope="col" class="ps-0">Price</th>
                                            <th scope="col" class="ps-0">Subject</th>
                                            <th scope="col" class="ps-0">Day</th>
                                            <th scope="col" class="ps-0">Shift</th>
                                            <th scope="col" class="ps-0">Time</th>
                                            <th scope="col" class="ps-0">Class Start</th>
                                            <th scope="col" class="text-end ps-0"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($courses as $course)
                                            <tr>
                                                {{-- <td class="ps-0">
                                                    {{ $loop->iteration }}
                                                </td> --}}
                                                <td class="ps-0">
                                                    <i
                                                        class="ti ti-currency-dollar fs-3 fw-semibold"></i>{{ $course->price }}
                                                </td>
                                                <td class="ps-0">
                                                    <div class="d-flex align-items-center gap-3">
                                                        <div class="flex-shrink-0">
                                                            <img src="{{ asset($course->thumbnail) }}" class="rounded"
                                                                alt="p1" width="80">
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-1 fw-semibold">
                                                                {{ $course->title }}
                                                            </h6>

                                                            <div class="d-flex align-items-center gap-2">
                                                                <img src="{{ $course->instructor->image }}"
                                                                    alt="{{ $course->instructor->name }}"
                                                                    class="rounded-circle"
                                                                    style="width: 28px; height: 28px; object-fit: cover;">

                                                                <span class="fs-2 mb-0">
                                                                    {{ $course->instructor->name }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="ps-0 text-capitalize">
                                                    {{ $course->schedule->study_day }}
                                                </td>
                                                <td class="ps-0 text-capitalize">
                                                    {{ $course->schedule->shift }}
                                                </td>
                                                <td class="ps-0">
                                                    {{ \Carbon\Carbon::parse($course->schedule->start_time)->format('h:i ') }}
                                                    <i class="ti ti-minus fs-1 mx-0"></i>
                                                    {{ \Carbon\Carbon::parse($course->schedule->end_time)->format('h:i A') }}
                                                </td>
                                                <td class="ps-0 text-capitalize">
                                                    {{ \Carbon\Carbon::parse($course->schedule->start_date)->format('d M, Y') }}
                                                </td>

                                                <td class="text-end ps-0">
                                                    <div class="dropdown dropstart">
                                                        <a href="#" class="text-muted show" id="dropdownMenuButton"
                                                            data-bs-toggle="dropdown" aria-expanded="true">
                                                            <i class="ti ti-dots-vertical fs-6"></i>
                                                        </a>
                                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton"
                                                            style="">
                                                            <li>
                                                                <a class="dropdown-item d-flex align-items-center gap-3"
                                                                    href="#"><i class="fs-4 ti ti-plus"></i>Add</a>
                                                            </li>
                                                            <li>
                                                                <a class="dropdown-item d-flex align-items-center gap-3"
                                                                    href="#"><i class="fs-4 ti ti-edit"></i>Edit</a>
                                                            </li>
                                                            <li>
                                                                <a class="dropdown-item d-flex align-items-center gap-3"
                                                                    href="#"><i
                                                                        class="fs-4 ti ti-trash"></i>Delete</a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center text-muted">
                                                    No courses available.
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
@endsection
