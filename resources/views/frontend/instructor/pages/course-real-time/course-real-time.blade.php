@extends('frontend.layouts.master')
@section('page_title', isset($page_title) ? $page_title : 'Page Title Here')
@section('content')
    <section class="pt-5 pb-5">
        <div class="container">
            @include('frontend.instructor.partials.user-info')
            <!-- Content -->
            @php
                $user = auth()->user();
            @endphp
            <div class="row mt-0 mt-md-4">
                <div class="col-lg-3 col-md-4 col-12">
                    <!-- User profile -->
                    @include('frontend.instructor.partials.side-navbar')
                </div>
                <div class="col-lg-9 col-md-8 col-12">
                    <!-- Card -->
                    <div class="card">
                        <!-- Card header -->
                        @include('frontend.instructor.partials.card-header')
                        <!-- Card body -->
                        <div class="card-body">
                            <!-- Form -->
                            <form class="row gx-3">
                                <div class="col-lg-9 col-md-7 col-12 mb-lg-0 mb-2">
                                    <input type="search" class="form-control" placeholder="Search Your Courses">
                                </div>
                                <div class="col-lg-3 col-md-5 col-12">
                                    <select class="form-select">
                                        <option value="">Date Created</option>
                                        <option value="Newest">Newest</option>
                                        <option value="High Rated">High Rated</option>
                                        <option value="Law Rated">Law Rated</option>
                                        <option value="High Earned">High Earned</option>
                                    </select>
                                </div>
                            </form>
                        </div>
                        <div class="table-responsive overflow-y-hidden">
                            <table class="table mb-0 text-nowrap table-hover table-centered">
                                <thead class="table-light">
                                    <tr>
                                        <th>Courses</th>
                                        <th>Schedule</th>
                                        <th>Status</th>
                                        <th>Student</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($ictcourses as $course)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div>
                                                        <a
                                                            href="{{ route('instructor.courses.real_time.show', $course->id) }}">
                                                            <img src="{{ asset($course->thumbnail == '' ? '/default-images/staff/no-course-img.png' : $course->thumbnail) }}"
                                                                alt="course" class="rounded img-4by3-lg"
                                                                style="width: 120px; height: 80px; object-fit: cover">
                                                        </a>
                                                    </div>
                                                    <div class="ms-3">
                                                        <h4 class="mb-1 h5">
                                                            <a href="{{ route('instructor.courses.real_time.show', $course->id) }}"
                                                                class="text-inherit text-capitalize">
                                                                {{ $course->title }}
                                                            </a>
                                                        </h4>
                                                        <ul class="list-inline fs-6 mb-0">
                                                            <li class="list-inline-item">
                                                                <span class="align-text-bottom">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="10"
                                                                        height="10" fill="currentColor"
                                                                        class="bi bi-clock" viewBox="0 0 16 16">
                                                                        <path
                                                                            d="M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71V3.5z">
                                                                        </path>
                                                                        <path
                                                                            d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm7-8A7 7 0 1 1 1 8a7 7 0 0 1 14 0z">
                                                                        </path>
                                                                    </svg>
                                                                </span>
                                                                <span>
                                                                    {{ $course->duration }} hours
                                                                </span>
                                                            </li>
                                                        </ul>
                                                        @php
                                                            $color =
                                                                $course->progress < 50
                                                                    ? 'bg-danger'
                                                                    : ($course->progress < 80
                                                                        ? 'bg-warning'
                                                                        : 'bg-success');
                                                        @endphp

                                                        <div class="progress mt-2" style="height: 3px">
                                                            <div class="progress-bar {{ $color }}"
                                                                style="width: {{ $course->progress }}%">
                                                            </div>
                                                        </div>
                                                        <small>{{ $course->progress }}%</small>
                                                    </div>
                                                </div>
                                            </td>
                                            {{-- <td>
                                                {{ $course->start_date ? \Carbon\Carbon::parse($course->class_start_date)->format('M d, Y') : 'N/A' }}
                                            </td> --}}
                                            <td>
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
                                                    {{ $days }} <br> {{ $shift }} (
                                                    {{ $start }}
                                                    –
                                                    {{ $end }} )
                                                @else
                                                    <span class="text-muted">No schedule</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $course->status == 'draft' ? 'warning' : ($course->status == 'active' ? 'success' : 'secondary') }}">
                                                    {{ ucfirst($course->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                {{ $course->enrollments_count ?? 0 }}
                                            </td>

                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">
                                                No courses found.
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
    </section>
@endsection
