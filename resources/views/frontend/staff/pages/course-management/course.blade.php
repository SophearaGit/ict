@extends('frontend.staff.layout.master')
@section('page_title', isset($page_title) ? $page_title : 'Page Title Here')
@section('content')
    @include('frontend.staff.pages.partials.breadcrumb')
    <div class="card card-body">
        <div class="row">
            <div class="col-md-4 col-xl-3">
                <form class="position-relative" action="{{ route('staff.courses.index') }}" method="GET">
                    <input type="search" class="form-control product-search ps-5" id="input-search" placeholder="Search Course"
                        name="search" value="{{ request()->search ?? '' }}">
                    <i class="ti ti-search position-absolute top-50 start-0 translate-middle-y fs-6 text-dark ms-3"></i>
                </form>
            </div>
            <div class="col-md-8 col-xl-9 text-end d-flex justify-content-md-end justify-content-center mt-3 mt-md-0">
                <a href="{{ route('staff.courses.create') }}" id="btn-add-contact"
                    class="btn btn-info d-flex align-items-center">
                    <i class="ti ti-circle-plus text-white me-1 fs-5"></i> Add Course
                </a>
            </div>
        </div>
    </div>
    <div class="card card-body">
        <div class="table-responsive">
            <table class="table align-middle mb-0 text-nowrap">
                <thead>
                    <tr class="text-muted fw-semibold">
                        <th class="ps-0">Course</th>
                        <th class="ps-0">Price</th>
                        <th class="ps-0">Schedule</th>
                        <th class="ps-0">Duration</th>
                        <th class="ps-0">Status</th>
                        <th class="ps-0">Start Date</th>
                        <th class="text-end ps-0">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($courses as $course)
                        <tr>
                            {{-- Course --}}
                            <td class="ps-0">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="flex-shrink-0">
                                        <a href="{{ route('staff.courses.show', $course->id) }}">
                                            <img src="{{ asset($course->thumbnail == '' ? '/default-images/staff/no-course-img.png' : $course->thumbnail) }}"
                                                class="rounded" width="80" height="60" style="object-fit: cover;">
                                        </a>
                                    </div>
                                    <div>
                                        <a href="{{ route('staff.courses.show', $course->id) }}"
                                            class="text-decoration-none text-dark">
                                            <h6 class="mb-1 fw-semibold">
                                                {{ $course->title }}
                                            </h6>
                                        </a>
                                        <div class="d-flex align-items-center gap-2">
                                            <img src="{{ $course->instructor->image == 'no-img.jpg'
                                                ? asset('/default-images/user/both.jpg')
                                                : asset($course->instructor->image) }}"
                                                class="rounded-circle" width="28" height="28"
                                                style="object-fit: cover;">
                                            <span class="fs-2">
                                                {{ $course->instructor->name }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            {{-- Price --}}
                            <td class="ps-0">
                                <span class="badge bg-light-success text-dark">
                                    <strong>
                                        ${{ number_format($course->price, 2) }}
                                    </strong>
                                </span>
                            </td>
                            {{-- Schedule --}}
                            <td class="ps-0">
                                @if ($course->schedule)
                                    @php
                                        $days = collect(explode('-', $course->schedule->study_day))
                                            ->map(fn($day) => ucfirst($day))
                                            ->implode(' • ');
                                        $start = \Carbon\Carbon::parse($course->schedule->start_time)->format('g:i A');
                                        $end = \Carbon\Carbon::parse($course->schedule->end_time)->format('g:i A');
                                    @endphp
                                    <div class="fw-semibold">
                                        {{ $days }}
                                    </div>
                                    <small class="text-muted">
                                        {{ $start }} - {{ $end }}
                                    </small>
                                @else
                                    <span class="text-muted">No schedule</span>
                                @endif
                            </td>
                            {{-- Duration --}}
                            <td class="ps-0">
                                {{ $course->duration ?? '-' }}hr
                            </td>
                            {{-- Status --}}
                            <td class="ps-0">
                                @if ($course->status == 'active')
                                    <span class="badge bg-success">OPEN</span>
                                @elseif($course->status == 'inactive')
                                    <span class="badge bg-danger">CLOSE</span>
                                @endif
                            </td>
                            {{-- Start Date --}}
                            <td class="ps-0">
                                {{ \Carbon\Carbon::parse($course->start_date)->format('d M Y') }}
                            </td>
                            {{-- Action --}}
                            <td class="text-end ps-0">
                                <div class="dropdown dropstart">
                                    <a href="#" class="text-muted" data-bs-toggle="dropdown">
                                        <i class="ti ti-dots-vertical fs-6"></i>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a class="dropdown-item d-flex align-items-center gap-3"
                                                href="{{ route('staff.courses.show', $course->id) }}">
                                                <i class="ti ti-eye fs-4"></i>
                                                View
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item d-flex align-items-center gap-3"
                                                href="{{ route('staff.courses.edit', $course->id) }}">
                                                <i class="ti ti-edit fs-4"></i>
                                                Edit
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item d-flex align-items-center gap-3 text-danger"
                                                href="#">
                                                <i class="ti ti-trash fs-4"></i>
                                                Delete
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-5">
                                No courses available.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            {{-- Pagination --}}
            @if ($courses->hasPages())
                {{ $courses->links('frontend.staff.pages.pagination.custom') }}
            @endif
        </div>
    </div>
@endsection
