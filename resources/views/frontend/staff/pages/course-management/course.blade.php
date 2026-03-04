@extends('frontend.staff.layout.master')
@section('page_title', isset($page_title) ? $page_title : 'Page Title Here')
@section('content')
    @include('frontend.staff.pages.partials.breadcrumb')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    {{-- <div class="d-md-flex align-items-center mb-9">
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

                            <a href="{{ route('staff.courses.create') }}" class="btn btn-primary">
                                <i class="ti ti-circle-plus me-1"></i> Create Course
                            </a>
                        </div>
                    </div> --}}
                    <div class="row">
                        <div class="col-md-4 col-xl-3">
                            <form class="position-relative" action="{{ route('staff.courses.index') }}" method="GET">
                                <input type="search" class="form-control product-search ps-5" id="input-search"
                                    placeholder="Search Course Title..." name="search"
                                    value="{{ request()->search ?? '' }}">
                                <i
                                    class="ti ti-search position-absolute top-50 start-0 translate-middle-y fs-6 text-dark ms-3"></i>
                            </form>

                            {{-- <form action="{{ route('admin.instructor.index') }}" method="GET">
                                <input type="search" class="form-control" placeholder="Search Instructor" name="search"
                                    value="{{ request()->search ?? '' }}">
                            </form> --}}




                        </div>
                        <div
                            class="col-md-8 col-xl-9 text-end d-flex justify-content-md-end justify-content-center mt-3 mt-md-0">
                            {{-- <div class="action-btn show-btn" >
                                <a href="javascript:void(0)"
                                    class="delete-multiple btn-light-danger btn me-2 text-danger d-flex align-items-center font-medium">
                                    <i class="ti ti-trash text-danger me-1 fs-5"></i> Delete All Row
                                </a>
                            </div> --}}
                            <a href="{{ route('staff.courses.create') }}" id="btn-add-contact"
                                class="btn btn-info d-flex align-items-center">
                                <i class="ti ti-circle-plus text-white me-1 fs-5"></i> Add Course
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
                                            <th scope="col" class="ps-0">Schedule</th>
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
                                                            <img src="{{ asset($course->thumbnail == '' ? '/default-images/staff/no-course-img.png' : $course->thumbnail) }}"
                                                                class="rounded" alt="p1" width="80">
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-1 fw-semibold">
                                                                {{ $course->title }}
                                                            </h6>

                                                            <div class="d-flex align-items-center gap-2">
                                                                <img src="{{ $course->instructor->image == 'no-img.jpg' ? asset('/default-images/user/both.jpg') : asset($course->instructor->image) }}"
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

                                                <td class="ps-0">
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
                                                                    href="{{ route('staff.courses.edit', $course->id) }}"><i
                                                                        class="fs-4 ti ti-edit"></i>Edit</a>
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

                                <div class="d-flex align-items-center justify-content-end py-1">
                                    <x-ui-pagination :paginator="$courses" />
                                </div>


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
