@extends('frontend.layouts.new.master')
@section('page_title', isset($page_title) ? $page_title : 'Page Title Here')
@push('styles')
@endpush
@section('content')
    <!-- body all course detail -->
    <form method="GET" action="{{ route('course') }}" id="courseFilterForm">
        <div class="all-course-header">
            <div class="descrip-with-searchbox" data-aos="fade-up">
                <h2>Explore All Course</h2>
                <p>Discover thousands of courses from top instructors. Learn new skills and advance your career.</p>
                <div class="search-course">
                    <div class="fds">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="text" id="seainput" name="search" value="{{ request('search') }}"
                            placeholder="What do you want to learn?">
                    </div>
                    <button id="search-find-course" type="submit">Search</button>
                </div>
            </div>
        </div>
        <div class="category-filter-course">
            <div class="filter-cate-side">
                <div class="radio-course">
                    <div class="icon-filters">
                        <div class="filter-clearall">
                            <i class="fa-solid fa-filter"></i>
                            <p><strong>Filters</strong></p>
                        </div>
                        <a href="{{ route('course') }}" style="text-decoration:none;">
                            <span>Clear All</span>
                        </a>
                    </div>
                    <h5>Category</h5>
                    <div class="dffge">
                        <input type="radio" name="category" value="" id="category-all"
                            {{ request('category') ? '' : 'checked' }}>
                        <label for="category-all">All</label><br>
                    </div>
                    @forelse ($categories as $category)
                        <div class="dffge">
                            <input type="radio" name="category" value="{{ $category->id }}"
                                id="category-{{ $category->id }}"
                                {{ (string) request('category') === (string) $category->id ? 'checked' : '' }}>
                            <label for="category-{{ $category->id }}">{{ $category->name }}</label><br>
                        </div>
                    @empty
                        <p class="no-categories">No categories yet.</p>
                    @endforelse
                </div>
            </div>
            <div class="filters-course-block">
                <div class="mainbox">
                    @forelse ($courses as $courseTitle => $courseGroup)
                        @php
                            // Each group holds every ICTCourse row sharing this title (e.g. different
                            // schedules/batches). We show the most recent one as the representative card.
                            $course = $courseGroup->first();
                        @endphp
                        <a href="{{ route('course.details', $course->slug) }}" class="boxcard">
                            <img id="course-imgg"
                                src="{{ $course->thumbnail ? asset($course->thumbnail) : 'frontend/asset/images/Course-Language/default.jpg' }}"
                                alt="{{ $course->title }}">
                            <div class="detail-boxcard">
                                <button>{{ $course->category->name ?? 'Development' }}</button>
                                <h2>{{ $course->title }}</h2>
                                <div class="weekschedule">
                                    <i class="fa-regular fa-calendar-days"></i>
                                    <p>Weekly Schedule</p>
                                    <p class="hour">{{ $course->duration_hours ?? '48' }} hours</p>
                                </div>
                                <p class="pweekly">
                                    @forelse ($courseGroup->unique('schedule_id') as $groupedCourse)
                                        @if ($groupedCourse->schedule)
                                            . {{ ucfirst($groupedCourse->schedule->study_day) }}
                                            ({{ \Carbon\Carbon::parse($groupedCourse->schedule->start_time)->format('g:iA') }}
                                            -
                                            {{ \Carbon\Carbon::parse($groupedCourse->schedule->end_time)->format('g:iA') }})
                                            <br>
                                        @endif
                                    @empty
                                        Schedule to be announced
                                    @endforelse
                                </p>
                            </div>
                            <div class="prnrate">
                                <h3>${{ number_format($course->price ?? 0, 2) }}</h3>
                                <div class="starate">
                                    <p>{{ $course->rating ?? '4.9' }}</p>
                                    @for ($i = 0; $i < 5; $i++)
                                        <i class="fa-solid fa-star" style="color:gold;"></i>
                                    @endfor
                                </div>
                            </div>
                        </a>
                    @empty
                        <p class="no-courses">No courses match your search or filter.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </form>

    @if ($courses->hasPages())
        <div class="pagination">
            @if ($courses->onFirstPage())
                <div class="page-btn disabled">&#10094;</div>
            @else
                <a href="{{ $courses->previousPageUrl() }}" class="page-btn">&#10094;</a>
            @endif

            @for ($page = 1; $page <= $courses->lastPage(); $page++)
                <a href="{{ $courses->url($page) }}"
                    class="page-btn {{ $courses->currentPage() == $page ? 'active' : '' }}">{{ $page }}</a>
            @endfor

            @if ($courses->hasMorePages())
                <a href="{{ $courses->nextPageUrl() }}" class="page-btn">&#10095;</a>
            @else
                <div class="page-btn disabled">&#10095;</div>
            @endif
        </div>
    @endif

    <!-- End body all course detailsection -->

    <script>
        (function() {
            function initCourseFilterForm() {
                var form = document.getElementById('courseFilterForm');
                if (!form) {
                    return;
                }
                var radios = form.querySelectorAll('input[name="category"]');
                radios.forEach(function(radio) {
                    radio.addEventListener('change', function() {
                        form.submit();
                    });
                });
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initCourseFilterForm);
            } else {
                initCourseFilterForm();
            }
        })();
    </script>
@endsection
@push('scripts')
@endpush
