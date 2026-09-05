@extends('frontend.layouts.new.master')
@section('page_title', isset($page_title) ? $page_title : $course->title)
@push('styles')
    <style>
        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        .tab-item {
            cursor: pointer;
        }

        .tab-item.active {
            color: #2563eb;
            font-weight: 600;
            border-bottom: 2px solid #2563eb;
        }

    </style>
@endpush
@section('content')
    <div class="headerdetail" data-aos="fade-up">
        <a href="{{ route('home') }}">Home/ </a><a href="{{ route('course') }}">Courses/ </a>
        @if ($course->category)
            <a href="{{ route('course', ['category' => $course->category_id]) }}">{{ $course->category->name }}/ </a>
        @endif
        <a href="#">{{ $course->title }}</a> <br><br>
        <h2>{{ $course->title }}</h2>
        <p>{{ $course->short_description ?? strip_tags($course->description ?? '') }}</p>
        <div class="typeaboutdetail">
            <div class="header-deatil">
                <i class="fa-solid fa-star" style="color:gold; font-size: 16px;"></i>
                <span>{{ $course->rating ?? '4.9' }} ({{ number_format($course->reviews_count ?? 0) }} review)</span>
            </div>
            <div class="header-deatil">
                <i class="fa-solid fa-user-graduate"></i>
                <span>{{ number_format($course->students_count ?? $course->students()->count()) }} students</span>
            </div>
            <div class="header-deatil">
                <i class="fa-solid fa-language"></i>
                <span>{{ $course->language ?? 'English' }}</span>
            </div>
            <div class="header-deatil">
                <i class="fa-regular fa-clock"></i>
                <span>Last updated {{ $course->updated_at?->format('m/Y') }}</span>
            </div>
        </div>
    </div>

    <!-- =============================body-section-start========================================= -->
    <div class="overview-section">
        <div class="overview-information-instruction">
            <ul>
                <li class="tab-item active" data-target="#section1">Overview</li>
                <li class="tab-item" data-target="#section2">Curriculum</li>
                {{-- <li class="tab-item" data-target="#section3">Instructor</li> --}}
            </ul>
        </div>
        <div class="detail-body">
            <div class="section-oci">
                <section id="section1" class="tab-content active">
                    <div id="what-you-learn">
                        <h3>What you'll learn</h3>
                        @forelse ($course->learningPoints as $point)
                            <div class="text-detail-block">
                                <i class="fa-solid fa-check"></i>
                                <p>{{ $point->content }}</p><br>
                            </div>
                        @empty
                            <p class="no-content">Learning outcomes coming soon.</p>
                        @endforelse
                    </div>
                    <div id="requiment-detail">
                        <h3>Requirements</h3>
                        @forelse ($course->requirements as $req)
                            <p>- {{ $req->content }}</p>
                        @empty
                            <p class="no-content">No specific requirements listed.</p>
                        @endforelse
                    </div>
                    <div id="detail-descrip-block">
                        <h3>Description</h3>
                        @php
                            $descriptionParagraphs = collect(
                                preg_split(
                                    '/\r\n\r\n|\n\n/',
                                    (string) ($course->description ?? ''),
                                    -1,
                                    PREG_SPLIT_NO_EMPTY,
                                ),
                            )
                                ->map(fn($p) => trim($p))
                                ->filter();
                        @endphp
                        @forelse ($descriptionParagraphs as $paragraph)
                            <p>{!! $paragraph !!}</p>
                        @empty
                            <p class="no-content">No description provided yet.</p>
                        @endforelse
                    </div>
                </section>
                <section id="section2" class="tab-content">
                    <div class="curriculum-setion">
                        <h3> <b>Course Content</b></h3>
                        @php
                            $chapterCount = $course->chapters->count();
                            $lectureCount = $course->chapters->sum(fn($chapter) => $chapter->lessons->count());
                        @endphp
                        <div class="section-lecture-hour">
                            <p>{{ $chapterCount }} {{ Str::plural('Chapter', $chapterCount) }}</p>
                            <p>{{ $lectureCount }} {{ Str::plural('Lecture', $lectureCount) }}</p>
                        </div>
                    </div>
                    <div class="section-content">
                        <div class="acc-wrap">
                            @forelse ($course->chapters as $index => $chapter)
                                @php $chapterInputId = 'chapter-' . $chapter->id; @endphp
                                <input type="checkbox" id="{{ $chapterInputId }}" />
                                <div class="acc-item">
                                    <label for="{{ $chapterInputId }}">
                                        <div class="acc-left">
                                            <div class="arrow-key"></div>
                                            <span class="acc-title">Chapter {{ $index + 1 }}:
                                                {{ $chapter->title }}</span>
                                        </div>
                                        <span class="acc-meta">
                                            {{ $chapter->lessons->count() }}
                                            {{ Str::plural('Lecture', $chapter->lessons->count()) }}
                                        </span>
                                    </label>
                                    <div class="acc-body">
                                        <div class="acc-inner">
                                            <ul>
                                                @forelse ($chapter->lessons as $lesson)
                                                    <li>{{ $lesson->title }}</li>
                                                @empty
                                                    <li>No lessons added yet.</li>
                                                @endforelse
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p class="no-content">Curriculum coming soon.</p>
                            @endforelse
                        </div>
                    </div>
                </section>
                <section id="section3" class="tab-content">
                    @php
                        $instructor = $course->instructor;
                        $expertiseList = collect(
                            is_array($instructor->expertise ?? null)
                                ? $instructor->expertise
                                : preg_split(
                                    '/\r\n|\n|,/',
                                    (string) ($instructor->expertise ?? ''),
                                    -1,
                                    PREG_SPLIT_NO_EMPTY,
                                ),
                        )
                            ->map(fn($skill) => trim($skill))
                            ->filter();
                        $bioParagraphs = collect(
                            preg_split('/\r\n\r\n|\n\n/', (string) ($instructor->bio ?? ''), -1, PREG_SPLIT_NO_EMPTY),
                        )
                            ->map(fn($p) => trim($p))
                            ->filter();
                    @endphp
                    <div class="profile-card">
                        <div class="top-info">
                            <div class="profile-image">
                                <img src="{{ $instructor && $instructor->avatar ? asset($instructor->avatar) : 'frontend/asset/images/Teacher/default.jpg' }}"
                                    alt="{{ $instructor->name ?? 'Instructor' }}">
                            </div>
                            <div class="stats">
                                {{-- Only real, data-backed stats are shown here. Add ->withCount(['courses',
                                         'enrollments as students_count']) to the 'instructor' relation in
                                         CoursePageController@courseDetails to avoid an extra query per stat. --}}
                                <p><i class="fa-solid fa-users"></i>
                                    {{ number_format($instructor->students_count ?? ($instructor->enrollments()->count() ?? 0)) }}+
                                    Students
                                </p>
                                <p><i class="fa-regular fa-bookmark"></i>
                                    {{ $instructor->courses_count ?? ($instructor->courses()->count() ?? 0) }} Courses
                                </p>
                            </div>
                        </div>
                        <h3>{{ $instructor->name ?? 'Instructor' }}</h3>
                        <h5>{{ $instructor->headline ?? ($instructor->title ?? 'Instructor') }}</h5>
                        <div class="descriptionn">
                            @forelse ($bioParagraphs as $paragraph)
                                <p>{{ $paragraph }}</p>
                            @empty
                                <p class="no-content">No biography added yet.</p>
                            @endforelse
                        </div>
                        @if ($expertiseList->isNotEmpty())
                            <div class="expertise">
                                <h5>Expertise</h5>
                                <ul>
                                    @foreach ($expertiseList as $skill)
                                        <li>{{ $skill }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                </section>
            </div>
            <div class="boxcard">
                <img src="{{ $course->thumbnail ? asset($course->thumbnail) : asset('default-images/course-default.jpg') }}"
                    alt="{{ $course->title }}">
                <div class="coursedetail-price-rating">
                    <h3>${{ number_format($course->price ?? 0, 2) }}</h3>
                    <div class="starate">
                        <p>{{ $course->rating ?? '4.9' }}</p>
                        @for ($i = 0; $i < 5; $i++)
                            <i class="fa-solid fa-star" style="color:gold;"></i>
                        @endfor
                    </div>
                </div>
                @if ($alreadyEnrolled)
                    <button id="enroll-course" type="button" disabled>Already Enrolled</button>
                @else
                    <form action="{{ route('student.course.enroll', $course->id) }}" method="POST" style="display:contents">
                        @csrf
                        <button id="enroll-course" type="submit">Enroll Now</button>
                    </form>
                @endif
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
                <p id="includes-course">This course includes:</p>
                <div class="certificate-completed">
                    <i class="fa-solid fa-award"></i>
                    <p>Certificate of Completion</p>
                </div>
                <div class="hour-with-icon">
                    <i class="fa-regular fa-clock"></i>
                    <p class="hour">48 hours</p>
                </div>
                <div class="weekschedule">
                    <i class="fa-regular fa-calendar-days"></i>
                    <p>Weekly Schedule</p>
                </div>
                <p class="pweekly">
                    @forelse ($batches->unique('schedule_id') as $batch)
                        @if ($batch->schedule)
                            . {{ ucfirst($batch->schedule->study_day) }}
                            ({{ \Carbon\Carbon::parse($batch->schedule->start_time)->format('g:iA') }} -
                            {{ \Carbon\Carbon::parse($batch->schedule->end_time)->format('g:iA') }})
                            <br>
                        @endif
                    @empty
                        Schedule to be announced
                    @endforelse
                </p>
            </div>
        </div>
    </div>

    <!-- =============================body-section-end============================================= -->
    <h2 id="h2-more-course" data-aos="fade-up">More Course</h2>
    <div class="more-course-detail">
        <div class="mainbox">
            @forelse ($moreCourses as $moreTitle => $moreGroup)
                @php $moreCourse = $moreGroup->first(); @endphp
                <a href="{{ route('course.details', $moreCourse->slug) }}" class="boxcard" data-aos="fade-up">
                    <img src="{{ $moreCourse->thumbnail ? asset($moreCourse->thumbnail) : asset('default-images/course-default.jpg') }}"
                        alt="{{ $moreCourse->title }}">
                    <div class="detail-boxcard">
                        <button>{{ $moreCourse->category->name ?? 'Development' }}</button>
                        <h2>{{ $moreCourse->title }}</h2>
                        <div class="weekschedule">
                            <i class="fa-regular fa-calendar-days"></i>
                            <p>Weekly Schedule</p>
                            <p class="hour">{{ $moreCourse->duration_hours ?? '48' }} hours</p>
                        </div>
                        <p class="pweekly">
                            @forelse ($moreGroup->unique('schedule_id') as $moreBatch)
                                @if ($moreBatch->schedule)
                                    . {{ ucfirst($moreBatch->schedule->study_day) }}
                                    ({{ \Carbon\Carbon::parse($moreBatch->schedule->start_time)->format('g:iA') }} -
                                    {{ \Carbon\Carbon::parse($moreBatch->schedule->end_time)->format('g:iA') }})
                                    <br>
                                @endif
                            @empty
                                Schedule to be announced
                            @endforelse
                        </p>
                    </div>
                    <div class="prnrate">
                        <h3>${{ number_format($moreCourse->price ?? 0, 2) }}</h3>
                        <div class="starate">
                            <p>{{ $moreCourse->rating ?? '4.9' }}</p>
                            @for ($i = 0; $i < 5; $i++)
                                <i class="fa-solid fa-star" style="color:gold;"></i>
                            @endfor
                        </div>
                    </div>
                </a>
            @empty
                <p class="no-courses">No related courses right now.</p>
            @endforelse
        </div>
    </div>
    <script>
        (function() {
            /* ── Tab navigation — loops whatever .tab-item/.tab-content pairs exist,
                   auto-activates the first tab on load. ── */
            function initTabs() {
                var tabs = document.querySelectorAll('.tab-item');
                var panels = document.querySelectorAll('.tab-content');

                function activate(tab) {
                    tabs.forEach(function(t) {
                        t.classList.remove('active');
                    });
                    panels.forEach(function(p) {
                        p.classList.remove('active');
                    });
                    tab.classList.add('active');
                    var target = document.querySelector(tab.dataset.target);
                    if (target) target.classList.add('active');
                }
                tabs.forEach(function(tab) {
                    tab.addEventListener('click', function() {
                        activate(tab);
                    });
                });
                if (tabs.length) activate(tabs[0]);
            }

            function init() {
                initTabs();
            }
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', init);
            } else {
                init();
            }
        })();
    </script>
@endsection
@push('scripts')
@endpush
