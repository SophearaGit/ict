@extends('frontend.staff.layout.master')
@section('page_title', isset($page_title) ? $page_title : 'Page Title Here')
@section('content')
    <!--  Owl carousel -->
    <div class="owl-carousel counter-carousel owl-theme">
        {{-- Staff --}}
        <div class="item">
            <div class="card border-0 zoom-in shadow-none" style="background-color: #FDF2F8;">
                <div class="card-body">
                    <div class="text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none"
                            stroke="#DB2777" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                            <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                        </svg>
                        <p class="fw-semibold fs-3 mb-1 mt-2" style="color: #DB2777;">
                            Staffs
                        </p>
                        <h5 class="fw-bold mb-0" style="color: #DB2777;">
                            {{ $staffs_count ?? 0 }}
                        </h5>
                    </div>
                </div>
            </div>
        </div>
        {{-- Reports --}}
        <div class="item">
            <div class="card border-0 zoom-in bg-light-warning shadow-none">
                <div class="card-body">
                    <div class="text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24"
                            fill="none" stroke="#F59E0B" stroke-width="1.8" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M9 12l2 2l4 -4" />
                            <path d="M12 3c7.2 0 9 1.8 9 9s-1.8 9 -9 9s-9 -1.8 -9 -9s1.8 -9 9 -9z" />
                        </svg>
                        <p class="fw-semibold fs-3 text-warning mb-1 mt-2">
                            Reports
                        </p>
                        <h5 class="fw-bold text-warning mb-0">
                            {{ $reports_count ?? 0 }}
                        </h5>
                    </div>
                </div>
            </div>
        </div>
        {{-- Students --}}
        <div class="item">
            <div class="card border-0 zoom-in bg-light-success shadow-none">
                <div class="card-body">
                    <div class="text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24"
                            fill="none" stroke="#10B981" stroke-width="1.8" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M3 7l9 -4l9 4l-9 4z" />
                            <path d="M7 10v4c0 1.657 2.239 3 5 3s5 -1.343 5 -3v-4" />
                        </svg>
                        <p class="fw-semibold fs-3 text-success mb-1 mt-2">
                            Students
                        </p>
                        <h5 class="fw-bold text-success mb-0">
                            {{ $students_count ?? 0 }}
                        </h5>
                    </div>
                </div>
            </div>
        </div>
        {{-- Courses --}}
        <div class="item">
            <div class="card border-0 zoom-in shadow-none" style="background-color: #EEF4FF;">
                <div class="card-body">
                    <div class="text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24"
                            fill="none" stroke="#3B82F6" stroke-width="1.8" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M4 19.5a2.5 2.5 0 0 1 2.5 -2.5h13.5" />
                            <path d="M6.5 17a2.5 2.5 0 0 0 0 5" />
                            <path d="M8 7h6" />
                            <path d="M8 11h8" />
                            <path d="M8 15h5" />
                            <path d="M4 5a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v14" />
                        </svg>
                        <p class="fw-semibold fs-3 mb-1 mt-2" style="color: #3B82F6;">
                            Courses
                        </p>
                        <h5 class="fw-bold mb-0" style="color: #3B82F6;">
                            {{ $courses_count ?? 0 }}
                        </h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <div>
                            <h5 class="card-title fw-semibold mb-1">
                                Today's Courses
                            </h5>
                            <p class="card-subtitle mb-0">
                                All courses scheduled for today.
                            </p>
                        </div>
                        <a href="{{ route('staff.courses.index') }}" class="text-primary fw-semibold text-decoration-none">
                            View All
                            <i class="ti ti-arrow-right"></i>
                        </a>
                    </div>
                    <div class="row g-4 mt-1">
                        @forelse ($today_courses as $course)
                            <div class="col-md-6 col-xl-4">
                                <a href="{{ route('staff.courses.show', $course->id) }}" class="text-decoration-none">
                                    <div class="card border-0 shadow-sm card-hover overflow-hidden h-100">
                                        {{-- Thumbnail --}}
                                        <div class="position-relative">
                                            <img src="{{ asset($course->thumbnail == '' ? '/default-images/staff/no-course-img.png' : $course->thumbnail) }}"
                                                alt="{{ $course->title }}" class="w-100"
                                                style="height: 180px; object-fit: cover;">
                                            {{-- Status --}}
                                            @if ($course->status == 'active')
                                                <span class="badge bg-success position-absolute top-0 end-0 m-3">
                                                    OPEN
                                                </span>
                                            @else
                                                <span class="badge bg-secondary position-absolute top-0 end-0 m-3">
                                                    CLOSE
                                                </span>
                                            @endif
                                        </div>
                                        {{-- Body --}}
                                        <div class="card-body p-3">
                                            {{-- Title --}}
                                            <h6 class="fw-semibold text-dark mb-2 lh-sm">
                                                {{ Str::limit($course->title, 40) }}
                                            </h6>
                                            {{-- Instructor --}}
                                            <div class="d-flex align-items-center gap-2 mb-3">
                                                <img src="{{ asset(
                                                    $course->instructor->image == 'no-img.jpg' ? '/default-images/user/both.jpg' : $course->instructor->image,
                                                ) }}"
                                                    class="rounded-circle" width="32" height="32"
                                                    style="object-fit: cover;">
                                                <span class="text-muted fs-2">
                                                    {{ $course->instructor->name }}
                                                </span>
                                            </div>
                                            {{-- Schedule --}}
                                            @if ($course->schedule)
                                                @php
                                                    $days = collect(explode('-', $course->schedule->study_day))
                                                        ->map(fn($day) => ucfirst(substr($day, 0, 3)))
                                                        ->implode(' • ');
                                                    $start = \Carbon\Carbon::parse(
                                                        $course->schedule->start_time,
                                                    )->format('g:i A');
                                                @endphp
                                                <div
                                                    class="d-flex align-items-center justify-content-between border-top pt-3">
                                                    <div>
                                                        <small class="text-muted d-block">
                                                            Schedule
                                                        </small>
                                                        <span class="fw-semibold fs-2 text-dark">
                                                            {{ $days }}
                                                        </span>
                                                    </div>
                                                    <div class="text-end">
                                                        <small class="text-muted d-block">
                                                            Time
                                                        </small>
                                                        <span class="fw-semibold fs-2 text-dark">
                                                            {{ $start }}
                                                        </span>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                        {{-- Footer --}}
                                        <div class="card-footer bg-white border-0 pt-0 pb-3 px-3">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <h5 class="text-primary fw-bold mb-0">
                                                    ${{ number_format($course->price, 2) }}
                                                </h5>
                                                <span class="btn btn-light-primary btn-sm">
                                                    View Course
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="card border-0 shadow-none text-center">
                                    <div class="card-body">
                                        <h5 class="card-title fw-semibold">No courses found for today.</h5>
                                        <p class="card-text">Please check back later for new courses.</p>
                                    </div>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
