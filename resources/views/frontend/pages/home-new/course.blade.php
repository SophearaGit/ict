@extends('frontend.layouts.new.master')
@section('page_title', isset($page_title) ? $page_title : 'Page Title Here')
@push('styles')
    <style>
        .empty-course-state {
            text-align: center;
            padding: 60px 20px;
        }

        .empty-course-state i {
            font-size: 50px;
            color: #999;
            margin-bottom: 15px;
        }

        .empty-course-state h3 {
            margin-bottom: 10px;
        }

        .empty-course-state p {
            color: #777;
        }
    </style>
@endpush
@section('content')

    <!-- body all course detail -->
    <div class="all-course-header">
        <div class="descrip-with-searchbox">
            <h2>Explore All Course</h2>
            <p>Discover thousands of courses from top instructors. Learn new skills and advance your career.</p>
            <div class="search-course">
                <div class="fds">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" id="seainput" value="{{ request('search') }}"
                        placeholder="What do you want to learn?">
                </div>
                {{-- <button id="search-find-course">Search</button> --}}
                @if (request()->filled('search'))
                    <button id="search-find-course" onclick="window.location.href='{{ route('course') }}'">
                        Clear
                    </button>
                @endif
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
                    <span>Clear All</span>
                </div>
                <h5>Category</h5>
                <form id="category-filter-form">
                    <div class="dffge">
                        <input type="radio" name="course-filters" value="all" checked>
                        <label>All</label>
                    </div>
                    @foreach ($categories as $category)
                        <div class="dffge">
                            <input type="radio" name="course-filters" value="{{ $category->id }}">
                            <label>{{ $category->name }}</label>
                        </div>
                    @endforeach
                </form>
            </div>
        </div>
        <div class="filters-course-block">
            <div class="mainbox">
                @foreach ($courses as $title => $group)
                    @php
                        $course = $group->first();
                    @endphp
                    <a href="{{ route('course.details', $course->slug) }}" class="boxcard"
                        data-category="{{ $course->category_id }}" data-title="{{ strtolower($course->title) }}">
                        <img id="course-imgg"
                            src="{{ asset(empty($course->thumbnail) ? 'default-images/ict-courses/loading.gif' : ltrim($course->thumbnail, '/')) }}"
                            alt="{{ $course->title }}">
                        <div class="teacher">
                            <div class="teach-name">
                                <img src="{{ asset(
                                    empty($course->instructor?->image) || $course->instructor?->image === 'no-img.jpg'
                                        ? 'default-images/user/both.jpg'
                                        : ltrim($course->instructor->image, '/'),
                                ) }}"
                                    alt="{{ $course->instructor?->name }}">
                                <p>{{ $course->instructor?->name }}</p>
                            </div>
                            <button>
                                {{ $course->category?->name }}
                            </button>
                        </div>
                        <h2>{{ $title }}</h2>
                        <div class="weekschedule">
                            <i class="fa-regular fa-calendar-days"></i>
                            <p>{{ $group->count() }} Weekly Schedule{{ $group->count() > 1 ? 's' : '' }}</p>
                            <p class="hour">{{ $course->duration }} hrs</p>
                        </div>
                        <p class="pweekly">
                            @foreach ($group->unique('schedule_id')->take(3) as $scheduleCourse)
                                • {{ $scheduleCourse->schedule?->short_days }}
                                ({{ $scheduleCourse->schedule?->formatted_time }})
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
                        <div class="prnrate">
                            <h3>${{ number_format($course->price, 2) }}</h3>
                            <div class="starate">
                                <p>4.9</p>
                                <i class="fa-solid fa-star" style="color:gold;"></i>
                                <i class="fa-solid fa-star" style="color:gold;"></i>
                                <i class="fa-solid fa-star" style="color:gold;"></i>
                                <i class="fa-solid fa-star" style="color:gold;"></i>
                                <i class="fa-solid fa-star" style="color:gold;"></i>
                            </div>
                        </div>
                    </a>
                @endforeach
                <div id="no-course-found" style="display:none;">
                    <div class="empty-course-state">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <h3>No Courses Found</h3>
                        <p>Try searching with different keywords or selecting another category.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if ($courses->lastPage() > 1)
        <div class="pagination">
            {{-- Previous --}}
            @if ($courses->onFirstPage())
                <div class="page-btn disabled">&#10094;</div>
            @else
                <a href="{{ $courses->previousPageUrl() }}" class="page-btn">
                    &#10094;
                </a>
            @endif
            {{-- Pages --}}
            @for ($i = 1; $i <= $courses->lastPage(); $i++)
                <a href="{{ $courses->url($i) }}" class="page-btn {{ $courses->currentPage() == $i ? 'active' : '' }}">
                    {{ $i }}
                </a>
            @endfor
            {{-- Next --}}
            @if ($courses->hasMorePages())
                <a href="{{ $courses->nextPageUrl() }}" class="page-btn">
                    &#10095;
                </a>
            @else
                <div class="page-btn disabled">&#10095;</div>
            @endif
        </div>
    @endif
@endsection
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const searchInput = document.getElementById('seainput');
            const radios = document.querySelectorAll(
                'input[name="course-filters"]'
            );

            function toggleEmptyState() {
                const cards = document.querySelectorAll('.boxcard');
                const emptyState = document.getElementById('no-course-found');
                const visibleCards = [...cards].filter(card =>
                    card.style.display !== 'none'
                );
                emptyState.style.display =
                    visibleCards.length === 0 ? 'block' : 'none';
            }

            function applyFilters() {
                const keyword = searchInput.value
                    .toLowerCase()
                    .trim();
                const selectedCategory =
                    document.querySelector(
                        'input[name="course-filters"]:checked'
                    )?.value ?? 'all';
                document.querySelectorAll('.boxcard')
                    .forEach(card => {
                        const title = card.dataset.title;
                        const category = card.dataset.category;
                        const matchesSearch =
                            title.includes(keyword);
                        const matchesCategory =
                            selectedCategory === 'all' ||
                            category === selectedCategory;
                        card.style.display =
                            matchesSearch && matchesCategory ?
                            '' :
                            'none';
                    });
                toggleEmptyState();
            }
            // Search
            searchInput.addEventListener(
                'keyup',
                applyFilters
            );
            // Category Filter
            radios.forEach(radio => {
                radio.addEventListener(
                    'change',
                    applyFilters
                );
            });
            // Clear All
            document.querySelector('.icon-filters span')
                ?.addEventListener('click', () => {
                    searchInput.value = '';
                    document.querySelector(
                        'input[name="course-filters"][value="all"]'
                    ).checked = true;
                    applyFilters();
                });
            // Initial Load
            applyFilters();
        });
    </script>
@endpush
