@extends('frontend.staff.layout.master')
@section('page_title', isset($page_title) ? $page_title : 'Page Title Here')
@section('content')

    <!--  Owl carousel -->
    <div class="owl-carousel counter-carousel owl-theme">
        <div class="item">
            <div class="card border-0 zoom-in bg-light-primary shadow-none">
                <div class="card-body">
                    <div class="text-center">
                        <img src="{{ asset('/admin/assets/dist/images/svgs/icon-user-male.svg') }}" width="50"
                            height="50" class="mb-3" alt="" />
                        <p class="fw-semibold fs-3 text-primary mb-1"> Staffs </p>
                        <h5 class="fw-semibold text-primary mb-0">
                            {{ $staffs_count ?? 0 }}
                        </h5>
                    </div>
                </div>
            </div>
        </div>

        <div class="item">
            <div class="card border-0 zoom-in bg-light-warning shadow-none">
                <div class="card-body">
                    <div class="text-center">
                        <img src="{{ asset('/admin/assets/dist/images/svgs/icon-connect.svg') }}" width="50"
                            height="50" class="mb-3" alt="" />
                        <p class="fw-semibold fs-3 text-warning mb-1">Reports</p>
                        <h5 class="fw-semibold text-warning mb-0">
                            {{ $reports_count ?? 0 }}
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
                    <h5 class="card-title fw-semibold">
                        Today's Courses
                    </h5>
                    <p class="card-subtitle mb-0">
                        All courses scheduled for today.
                    </p>
                    <div class="row mt-4">
                        @forelse ($today_courses as $course)
                            <div class="col-md-4">
                                <a href="{{ route('staff.courses.show', $course->id) }}">
                                    <div class="card overflow-hidden shadow-none border card-hover mb-4 mb-md-0">
                                        <img src="{{ asset($course->thumbnail == '' ? '\default-images\staff\no-course-img.png' : $course->thumbnail) }}"
                                            alt="img">
                                        <div class="card-body p-4">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <div>
                                                    <h6 class="mb-0 fs-5 fw-semibold">
                                                        {{-- course title --}}
                                                        {{ Str::limit($course->title, 30) }}
                                                    </h6>
                                                    {{-- <span>
                                                        Name: {{ $course->instructor->name }} <br>
                                                        Email: {{ $course->instructor->email }}
                                                    </span> --}}
                                                </div>
                                                <img src="{{ asset($course->instructor->image == 'no-img.jpg' ? 'default-images\user\both.jpg' : $course->instructor->image) }}"
                                                    alt="user1" width="35" class="rounded-circle">
                                            </div>
                                            <div class="d-flex align-items-start justify-content-between mt-3">

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
