@extends('frontend.layouts.new.master')
@section('page_title', isset($page_title) ? $page_title : 'Course Details')
@push('styles')
    <style>
        /* ── Empty / No-data shimmer skeleton ── */
        @keyframes shimmer {
            0% {
                background-position: -600px 0;
            }

            100% {
                background-position: 600px 0;
            }
        }

        .skeleton {
            background: linear-gradient(90deg,
                    var(--skeleton-base, #e8e8e8) 25%,
                    var(--skeleton-shine, #f5f5f5) 50%,
                    var(--skeleton-base, #e8e8e8) 75%);
            background-size: 600px 100%;
            animation: shimmer 1.4s ease-in-out infinite;
            border-radius: 6px;
        }

        [data-theme="dark"] {
            --skeleton-base: #2a2a2a;
            --skeleton-shine: #3a3a3a;
        }

        /* ── No-data placeholder block ── */
        .no-data-block {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 36px 20px;
            border-radius: 10px;
            border: 1.5px dashed #ccc;
            color: #aaa;
            font-size: 14px;
            text-align: center;
        }

        [data-theme="dark"] .no-data-block {
            border-color: #444;
            color: #666;
        }

        .no-data-block i {
            font-size: 32px;
            opacity: .45;
        }

        /* ── Skeleton lines used inside no-data zones ── */
        .skeleton-line {
            height: 14px;
            border-radius: 4px;
            margin-bottom: 8px;
        }

        .skeleton-line.w-80 {
            width: 80%;
        }

        .skeleton-line.w-60 {
            width: 60%;
        }

        .skeleton-line.w-40 {
            width: 40%;
        }

        /* ── Fade-in for real content ── */
        .content-ready {
            animation: fadeIn .4s ease forwards;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(6px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
@endpush
@section('content')
    <div class="headerdetail">
        {{-- Breadcrumb --}}
        <a href="{{ route('home') }}">Home/ </a>
        <a href="{{ route('course') }}">Courses/ </a>
        @if ($course->category)
            <a href="{{ route('course') }}?category={{ $course->category->id }}">{{ $course->category->name }}/ </a>
        @endif
        <a href="#">{{ $course->title }}</a>
        <br><br>
        <h2>{{ $course->title }}</h2>
        {{-- Short description or fallback --}}
        <p>
            @if ($course->short_description)
                {{ $course->short_description }}
            @else
                No description available for this course yet.
            @endif
        </p>
        <div class="typeaboutdetail">
            {{-- Rating — static display until you add a ratings relation --}}
            <div class="header-deatil">
                <i class="fa-solid fa-star" style="color:gold; font-size: 16px;"></i>
                <span>No ratings yet</span>
            </div>
            {{-- Students --}}
            <div class="header-deatil">
                <i class="fa-solid fa-user-graduate"></i>
                <span>
                    {{ $course->students_count ?? $course->students->count() }}
                    student{{ ($course->students_count ?? $course->students->count()) !== 1 ? 's' : '' }}
                </span>
            </div>
            {{-- Level --}}
            @if ($course->level)
                <div class="header-deatil">
                    <i class="fa-solid fa-layer-group"></i>
                    <span>{{ ucfirst($course->level) }}</span>
                </div>
            @endif
            {{-- Duration --}}
            @if ($course->duration)
                <div class="header-deatil">
                    <i class="fa-regular fa-clock"></i>
                    <span>{{ $course->duration }} hrs total</span>
                </div>
            @endif
            {{-- Dates --}}
            @if ($course->start_date)
                <div class="header-deatil">
                    <i class="fa-regular fa-calendar-days"></i>
                    <span>Starts {{ $course->start_date->format('M d, Y') }}</span>
                </div>
            @endif
        </div>
    </div>

    <!-- ============================== BODY ============================== -->
    <div class="overview-section">
        <div class="overview-information-instruction">
            <ul>
                <li id="Overview">Overview</li>
                <li id="curriculum">Curriculum</li>
                <li id="instructor">Instructor</li>
            </ul>
            <hr>
        </div>
        <div class="detail-body">
            <div class="section-oci">

                <!-- ═══ SECTION 1 : Overview ═══ -->
                <section id="section1" class="active">
                    {{-- Description --}}
                    <div id="detail-descrip-block">
                        <h3>Description</h3>
                        @if ($course->description)
                            <div class="content-ready">
                                {!! nl2br(e($course->description)) !!}
                            </div>
                        @else
                            <div class="no-data-block">
                                <i class="fa-regular fa-file-lines"></i>
                                <p>No description has been added for this course yet.</p>
                                <div class="skeleton skeleton-line w-80"></div>
                                <div class="skeleton skeleton-line w-60"></div>
                                <div class="skeleton skeleton-line w-40"></div>
                            </div>
                        @endif
                    </div>
                    {{-- Requirements — no dedicated field, show placeholder --}}
                    <div id="requiment-detail">
                        <h3>Requirements</h3>
                        <div class="no-data-block">
                            <i class="fa-solid fa-list-check"></i>
                            <p>Requirements will be listed here once the instructor adds them.</p>
                        </div>
                    </div>
                    {{-- What You'll Learn — no dedicated field, show placeholder --}}
                    <div id="what-you-learn">
                        <h3>What you'll learn</h3>
                        <div class="no-data-block">
                            <i class="fa-solid fa-graduation-cap"></i>
                            <p>Learning outcomes haven't been set yet. Check back soon.</p>
                        </div>
                    </div>
                </section>

                <!-- ═══ SECTION 2 : Curriculum ═══ -->
                <section id="section2">
                    <div class="curriculum-setion">
                        <h3><b>Course Content</b></h3>
                        {{-- Session stats derived from model attributes --}}
                        <div class="section-lecture-hour">
                            <p>{{ $course->total_sessions }} Sessions</p>
                            <p>{{ $course->duration }} Hours total</p>
                            @if ($course->completed_sessions > 0)
                                <p>{{ $course->completed_sessions }} Completed</p>
                            @endif
                        </div>
                    </div>
                    <div class="section-content">
                        {{-- No chapter/lesson data yet — show placeholder --}}
                        <div class="no-data-block" style="margin-top: 16px;">
                            <i class="fa-solid fa-book-open"></i>
                            <strong>Curriculum coming soon</strong>
                            <p>The instructor hasn't uploaded the course content yet.</p>
                            <div class="skeleton skeleton-line w-80"></div>
                            <div class="skeleton skeleton-line w-60"></div>
                            <div class="skeleton skeleton-line w-80"></div>
                            <div class="skeleton skeleton-line w-40"></div>
                        </div>
                    </div>
                </section>

                <!-- ═══ SECTION 3 : Instructor ═══ -->
                <section id="section3">
                    @if ($course->instructor)
                        <div class="profile-card content-ready">
                            <div class="top-info">
                                <div class="profile-image">
                                    @if ($course->instructor->avatar)
                                        <img src="{{ asset('storage/' . $course->instructor->avatar) }}"
                                            alt="{{ $course->instructor->name }}">
                                    @else
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($course->instructor->name) }}&background=random&size=128"
                                            alt="{{ $course->instructor->name }}">
                                    @endif
                                </div>
                                <div class="stats">
                                    <p><i class="fa-solid fa-star"></i> No rating yet</p>
                                    <p><i class="fa-regular fa-circle-check"></i> No reviews yet</p>
                                    <p>
                                        <i class="fa-solid fa-users"></i>
                                        {{ $course->instructor->taught_students_count ?? '—' }} Students
                                    </p>
                                    <p>
                                        <i class="fa-regular fa-bookmark"></i>
                                        {{ $course->instructor->courses_count ?? '—' }} Courses
                                    </p>
                                </div>
                            </div>
                            <h3>{{ $course->instructor->name }}</h3>
                            <h5>
                                {{ $course->instructor->job_title ?? 'Instructor' }}
                            </h5>
                            <div class="descriptionn">
                                @if ($course->instructor->bio)
                                    <p>{{ $course->instructor->bio }}</p>
                                @else
                                    <div class="no-data-block" style="border: none; padding: 12px 0;">
                                        <i class="fa-regular fa-user"></i>
                                        <p>Instructor bio not available yet.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="no-data-block">
                            <i class="fa-solid fa-chalkboard-user"></i>
                            <strong>Instructor not assigned</strong>
                            <p>This course hasn't been assigned an instructor yet.</p>
                        </div>
                    @endif
                </section>
            </div> <!-- /.section-oci -->

            <!-- ═══ SIDEBAR CARD ═══ -->
            <div class="boxcard">
                @if ($course->thumbnail)
                    <img src="{{ asset($course->thumbnail) }}" alt="{{ $course->title }}">
                @else
                    <div class="skeleton" style="width:100%; height:180px; border-radius:8px;"></div>
                @endif
                <h5>{{ $course->title }}</h5>
                <h3>${{ number_format($course->price, 2) }}</h3>
                <div class="fav-share">
                    <div class="fav">
                        <i class="fa-regular fa-heart"></i>
                        <p>Favorite</p>
                    </div>
                    <div class="share">
                        <i class="fa-solid fa-share-nodes"></i>
                        <p>Share</p>
                    </div>
                </div>
                {{-- Schedule --}}
                <div class="weekschedule">
                    <i class="fa-regular fa-calendar-days"></i>
                    <p>Weekly Schedule</p>
                    <p class="hour">{{ $course->duration }} hours</p>
                </div>
                @if ($course->schedule)
                    <p class="pweekly">
                        @foreach ($batches->unique('schedule_id')->take(3) as $batch)
                            • {{ $batch->schedule?->short_days }}
                            ({{ $batch->schedule?->formatted_time }})
                            @if (!$loop->last)
                                <br>
                            @endif
                        @endforeach
                        @if ($batches->unique('schedule_id')->count() > 3)
                            <br>
                            +{{ $batches->unique('schedule_id')->count() - 3 }} more batches
                        @endif
                    </p>
                @else
                    <div class="no-data-block" style="margin-top:10px; padding:16px;">
                        <i class="fa-regular fa-calendar-xmark"></i>
                        <p>Schedule not set yet.</p>
                    </div>
                @endif
            </div> <!-- /.boxcard -->
        </div> <!-- /.detail-body -->
    </div> <!-- /.overview-section -->

    <!-- ============================== /BODY ============================== -->
    <h2 id="h2-more-course">More Courses</h2>
    <div class="more-course-detail">
        @if ($moreCourses->isNotEmpty())
            <div class="mainbox">
                @foreach ($moreCourses as $title => $group)
                    @php
                        $mc = $group->first();
                    @endphp
                    <a href="{{ route('course.details', $mc->slug) }}" class="boxcard">
                        @if ($mc->thumbnail)
                            <img src="{{ asset($mc->thumbnail) }}" alt="{{ $mc->title }}">
                        @else
                            <div class="skeleton" style="width:100%;height:160px;border-radius:8px 8px 0 0;"></div>
                        @endif
                        <div class="teacher">
                            <div class="teach-name">
                                @if ($mc->instructor)
                                    <img src="{{ $mc->instructor->avatar
                                        ? asset('storage/' . $mc->instructor->avatar)
                                        : 'https://ui-avatars.com/api/?name=' . urlencode($mc->instructor->name) . '&size=40&background=random' }}"
                                        alt="{{ $mc->instructor->name }}">
                                    <p>{{ $mc->instructor->name }}</p>
                                @else
                                    <p>—</p>
                                @endif
                            </div>
                            @if ($mc->category)
                                <button>{{ $mc->category->name }}</button>
                            @endif
                        </div>
                        <h2>{{ $mc->title }}</h2>
                        <div class="weekschedule">
                            <i class="fa-regular fa-calendar-days"></i>
                            <p>Weekly Schedule</p>
                            <p class="hour">{{ $mc->duration }} hours</p>
                        </div>
                        @if ($group->isNotEmpty())
                            <p class="pweekly">
                                @foreach ($group->unique('schedule_id')->take(3) as $batch)
                                    • {{ $batch->schedule?->short_days }}
                                    ({{ $batch->schedule?->formatted_time }})
                                    @if (!$loop->last)
                                        <br>
                                    @endif
                                @endforeach
                                @if ($group->unique('schedule_id')->count() > 3)
                                    <br>
                                    +{{ $group->unique('schedule_id')->count() - 3 }}
                                    more schedules
                                @endif
                            </p>
                        @endif
                        <div class="prnrate">
                            <h3>${{ number_format($mc->price, 2) }}</h3>
                            <div class="starate">
                                <p>—</p>
                                <i class="fa-regular fa-star" style="color:gold;"></i>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="no-data-block" style="margin: 0 auto 40px; max-width:500px;">
                <i class="fa-solid fa-magnifying-glass"></i>
                <strong>No other courses found</strong>
                <p>There are no other courses in this category right now.</p>
                <a href="{{ route('course') }}" style="font-size:13px; color:inherit; text-decoration:underline;">
                    Browse all courses →
                </a>
            </div>
        @endif
    </div>
@endsection
@push('scripts')
    <script>
        // body section
        $('#Overview').click(function() {
            $('section').removeClass('active')
            $('#section1').addClass('active')
            // Add CSS styles to active tab
            $('#Overview').css({
                'color': '#007bff',
                'border-bottom': '3px solid #007bff',
                'font-weight': 'bold'
            })
            // Remove styles from other tab
            $('#curriculum').css({
                'color': '',
                'border-bottom': '',
                'font-weight': 'normal'
            })
            $('#instructor').css({
                'color': '',
                'border-bottom': '',
                'font-weight': 'normal'
            })
        })
        $('#curriculum').click(function() {
            $('section').removeClass('active')
            $('#section2').addClass('active')
            // Add CSS styles to active tab
            $('#curriculum').css({
                'color': '#007bff',
                'border-bottom': '3px solid #007bff',
                'font-weight': 'bold'
            })
            // Remove styles from other tab
            $('#Overview').css({
                'color': '',
                'border-bottom': '',
                'font-weight': 'normal'
            })
            $('#instructor').css({
                'color': '',
                'border-bottom': '',
                'font-weight': 'normal'
            })
        })
        $('#instructor').click(function() {
            $('section').removeClass('active')
            $('#section3').addClass('active')
            // Add CSS styles to active tab
            $('#instructor').css({
                'color': '#007bff',
                'border-bottom': '3px solid #007bff',
                'font-weight': 'bold'
            })
            // Remove styles from other tab
            $('#Overview').css({
                'color': '',
                'border-bottom': '',
                'font-weight': 'normal'
            })
            $('#curriculum').css({
                'color': '',
                'border-bottom': '',
                'font-weight': 'normal'
            })
        })
    </script>
@endpush
