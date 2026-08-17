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

        /* Schedule Picker Modal */
        .sched-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(16, 24, 40, .5);
            z-index: 1050;
            backdrop-filter: blur(4px);
            align-items: center;
            justify-content: center;
        }

        .sched-backdrop.open {
            display: flex;
        }

        .sched-modal {
            background: #fff;
            border-radius: 18px;
            width: 100%;
            max-width: 500px;
            margin: 16px;
            box-shadow: 0 24px 64px rgba(0, 0, 0, .18);
            overflow: hidden;
        }

        .sched-modal-head {
            padding: 24px 24px 0;
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 12px;
        }

        .sched-modal-head h2 {
            font-size: 18px;
            font-weight: 700;
            color: #101828;
            margin: 0;
        }

        .sched-modal-head p {
            font-size: 13px;
            color: #667085;
            margin-top: 4px;
            margin-bottom: 0;
        }

        .sched-close {
            background: none;
            border: none;
            cursor: pointer;
            color: #667085;
            padding: 2px;
            line-height: 1;
            flex-shrink: 0;
        }

        .sched-close:hover {
            color: #101828;
        }

        .sched-modal-body {
            padding: 20px 24px;
        }

        .sched-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
            max-height: 340px;
            overflow-y: auto;
            padding-right: 4px;
        }

        .sched-option {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 14px 16px;
            border: 1.5px solid #E4E7EC;
            border-radius: 12px;
            cursor: pointer;
            user-select: none;
        }

        .sched-option:has(input:checked) {
            border-color: #0057FF;
            background: #EEF3FF;
        }

        .sched-option:hover:not(:has(input:disabled)) {
            border-color: #0057FF;
            background: #F5F8FF;
        }

        .sched-option:has(input:disabled) {
            opacity: .5;
            cursor: not-allowed;
        }

        .sched-option input[type=radio] {
            margin-top: 3px;
            accent-color: #0057FF;
            flex-shrink: 0;
            width: 16px;
            height: 16px;
        }

        .sched-option-body {
            flex: 1;
            min-width: 0;
        }

        .sched-option-title {
            font-size: 14px;
            font-weight: 600;
            color: #101828;
        }

        .sched-option-meta {
            font-size: 12px;
            color: #667085;
            margin-top: 5px;
            display: flex;
            gap: 14px;
            flex-wrap: wrap;
            align-items: center;
        }

        .sched-option-meta span {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .sched-shift {
            display: inline-block;
            font-size: 11px;
            font-weight: 600;
            padding: 2px 8px;
            border-radius: 20px;
            margin-top: 5px;
            background: #F2F4F7;
            color: #344054;
        }

        .sched-shift.morning {
            background: #FFFAEB;
            color: #B54708;
        }

        .sched-shift.afternoon {
            background: #EEF3FF;
            color: #0057FF;
        }

        .sched-shift.evening {
            background: #F4F3FF;
            color: #5925DC;
        }

        .sched-badge {
            font-size: 11px;
            font-weight: 600;
            padding: 3px 10px;
            border-radius: 20px;
            flex-shrink: 0;
            align-self: flex-start;
        }

        .sched-badge.open {
            background: #ECFDF3;
            color: #027A48;
        }

        .sched-badge.full {
            background: #FEF3F2;
            color: #B42318;
        }

        .sched-badge.current {
            background: #EEF3FF;
            color: #0057FF;
        }

        .sched-empty {
            text-align: center;
            padding: 32px 16px;
            color: #667085;
            font-size: 14px;
        }

        .sched-empty i {
            font-size: 28px;
            opacity: .4;
            display: block;
            margin-bottom: 10px;
        }

        .sched-loading {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        @keyframes shimmer {
            0% {
                background-position: -600px 0;
            }

            100% {
                background-position: 600px 0;
            }
        }

        .sched-skel-row {
            height: 80px;
            border-radius: 12px;
            background: linear-gradient(90deg, #f0f0f0 25%, #e8e8e8 50%, #f0f0f0 75%);
            background-size: 600px 100%;
            animation: shimmer 1.4s infinite;
        }

        .sched-modal-foot {
            padding: 0 24px 24px;
            display: flex;
            gap: 10px;
            justify-content: flex-end;
        }

        .btn-sched-cancel {
            padding: 10px 20px;
            border: 1px solid #E4E7EC;
            border-radius: 8px;
            background: #fff;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            color: #344054;
        }

        .btn-sched-cancel:hover {
            background: #F9FAFB;
        }

        .btn-sched-confirm {
            padding: 10px 28px;
            background: #0057FF;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
        }

        .btn-sched-confirm:disabled {
            opacity: .4;
            cursor: not-allowed;
        }

        .btn-sched-confirm:not(:disabled):hover {
            opacity: .88;
        }
    </style>
@endpush
@section('content')
    {{-- ════════════ Schedule Picker Modal ════════════ --}}
    <div class="sched-backdrop" id="sched-backdrop">
        <div class="sched-modal" role="dialog" aria-modal="true" aria-labelledby="sched-title">
            <div class="sched-modal-head">
                <div>
                    <h2 id="sched-title">Choose a Schedule</h2>
                    <p>Each section runs the same course on a different timetable — pick what fits you.</p>
                </div>
                <button class="sched-close" onclick="closeScheduleModal()" aria-label="Close">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5"
                        viewBox="0 0 24 24">
                        <path d="M18 6 6 18M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="sched-modal-body">
                <div id="sched-list-wrap" class="sched-list">
                    <div class="sched-loading">
                        <div class="sched-skel-row"></div>
                        <div class="sched-skel-row"></div>
                        <div class="sched-skel-row"></div>
                    </div>
                </div>
            </div>
            <div class="sched-modal-foot">
                <button class="btn-sched-cancel" onclick="closeScheduleModal()">Cancel</button>
                <button class="btn-sched-confirm" id="btn-sched-confirm" disabled onclick="confirmEnroll()">
                    Confirm &amp; Enroll
                </button>
            </div>
        </div>
    </div>
    {{-- Hidden form — action is set dynamically to the chosen course's enroll URL --}}
    <form id="enroll-form" action="" method="POST" style="display:none">
        @csrf
    </form>
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
                <li class="tab-item" data-target="#section3">Instructor</li>
            </ul>
        </div>
        <div class="detail-body">
            <div class="section-oci">
                <section id="section1" class="tab-content active">
                    <div id="what-you-learn">
                        <h3>What you'll learn</h3>
                        @php
                            // Adjust 'what_you_will_learn' to whatever column your ICTCourse model actually
                            // uses. Supports either a JSON/array-cast column or a newline-separated text column.
                            $learningPoints = collect(
                                is_array($course->what_you_will_learn ?? null)
                                    ? $course->what_you_will_learn
                                    : preg_split(
                                        '/\r\n|\r|\n/',
                                        (string) ($course->what_you_will_learn ?? ''),
                                        -1,
                                        PREG_SPLIT_NO_EMPTY,
                                    ),
                            )
                                ->map(fn($point) => trim($point))
                                ->filter();
                        @endphp
                        @forelse ($learningPoints as $point)
                            <div class="text-detail-block">
                                <i class="fa-solid fa-check"></i>
                                <p>{{ $point }}</p><br>
                            </div>
                        @empty
                            <p class="no-content">Learning outcomes coming soon.</p>
                        @endforelse
                    </div>
                    <div id="requiment-detail">
                        <h3>Requiments</h3>
                        @php
                            // Adjust 'requirements' to your actual column name if different.
                            $requirementPoints = collect(
                                is_array($course->requirements ?? null)
                                    ? $course->requirements
                                    : preg_split(
                                        '/\r\n|\r|\n/',
                                        (string) ($course->requirements ?? ''),
                                        -1,
                                        PREG_SPLIT_NO_EMPTY,
                                    ),
                            )
                                ->map(fn($req) => trim($req))
                                ->filter();
                        @endphp
                        @forelse ($requirementPoints as $req)
                            <p>- {{ $req }}</p>
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
                <img src="{{ $course->thumbnail ? asset($course->thumbnail) : 'frontend/asset/images/Course-Language/default.jpg' }}"
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
                    <button id="enroll-course" type="button"
                        onclick="openScheduleModal({{ $course->id }}, @js($course->title))">
                        Enroll Now
                    </button>
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
                    <img src="{{ $moreCourse->thumbnail ? asset($moreCourse->thumbnail) : 'frontend/asset/images/Course-Language/default.jpg' }}"
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

            /* ════════════════════════════ Schedule Modal ════════════════════════════ */
            var _selectedCourseId = null;

            function openScheduleModal(currentCourseId, courseTitle) {
                _selectedCourseId = null;
                var confirmBtn = document.getElementById('btn-sched-confirm');
                if (confirmBtn) confirmBtn.disabled = true;
                var backdrop = document.getElementById('sched-backdrop');
                if (backdrop) backdrop.classList.add('open');
                loadSections(currentCourseId, courseTitle);
            }

            function closeScheduleModal() {
                var backdrop = document.getElementById('sched-backdrop');
                if (backdrop) backdrop.classList.remove('open');
            }

            function loadSections(currentCourseId, courseTitle) {
                var wrap = document.getElementById('sched-list-wrap');
                if (!wrap) return;

                wrap.innerHTML = '<div class="sched-loading">' +
                    '<div class="sched-skel-row"></div>' +
                    '<div class="sched-skel-row"></div>' +
                    '<div class="sched-skel-row"></div>' +
                    '</div>';

                // Uses the existing student.course.schedules route with ?title= param
                $.ajax({
                    url: '{{ route('student.course.schedules', ['course' => '__ID__']) }}'.replace('__ID__',
                        currentCourseId),
                    method: 'GET',
                    data: {
                        title: courseTitle
                    },
                    success: function(res) {
                        var sections = res.sections || [];
                        if (!sections.length) {
                            wrap.innerHTML = '<div class="sched-empty">' +
                                '<i class="fa-regular fa-calendar-xmark"></i>' +
                                'No schedules are available for this course right now.' +
                                '</div>';
                            return;
                        }

                        wrap.innerHTML = sections.map(function(s) {
                            var isCurrent = s.id === currentCourseId;
                            var shiftClass = (s.shift || '').toLowerCase();
                            var badgeClass = isCurrent ? 'current' : (s.is_full ? 'full' : 'open');
                            var badgeLabel = isCurrent ? 'Current' : (s.is_full ? 'Full' : 'Open');
                            return '' +
                                '<label class="sched-option">' +
                                '<input type="radio" name="section" value="' + s.id + '"' +
                                (isCurrent ? ' checked' : '') +
                                (s.is_full && !isCurrent ? ' disabled' : '') + '>' +
                                '<div class="sched-option-body">' +
                                '<div class="sched-option-title">' + s.days + '</div>' +
                                '<div class="sched-option-meta">' +
                                '<span>' +
                                '<svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">' +
                                '<circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>' +
                                '</svg>' +
                                s.time +
                                '</span>' +
                                (s.instructor ? (
                                    '<span>' +
                                    '<svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">' +
                                    '<path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>' +
                                    '<circle cx="12" cy="7" r="4"/>' +
                                    '</svg>' +
                                    s.instructor +
                                    '</span>'
                                ) : '') +
                                (s.start_date ? (
                                    '<span>' +
                                    '<svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">' +
                                    '<rect x="3" y="4" width="18" height="18" rx="2"/>' +
                                    '<path d="M16 2v4M8 2v4M3 10h18"/>' +
                                    '</svg>' +
                                    'Starts ' + s.start_date +
                                    '</span>'
                                ) : '') +
                                '</div>' +
                                (s.shift ? ('<span class="sched-shift ' + shiftClass + '">' + s
                                    .shift + '</span>') : '') +
                                '</div>' +
                                '<span class="sched-badge ' + badgeClass + '">' + badgeLabel +
                                '</span>' +
                                '</label>';
                        }).join('');

                        var preselect = sections.find(function(s) {
                            return s.id === currentCourseId;
                        });
                        if (preselect || sections.length === 1) {
                            _selectedCourseId = preselect ? currentCourseId : sections[0].id;
                            var confirmBtn = document.getElementById('btn-sched-confirm');
                            if (confirmBtn) confirmBtn.disabled = false;
                        }

                        wrap.querySelectorAll('input[type=radio]').forEach(function(radio) {
                            radio.addEventListener('change', function() {
                                _selectedCourseId = parseInt(this.value, 10);
                                var confirmBtn = document.getElementById(
                                    'btn-sched-confirm');
                                if (confirmBtn) confirmBtn.disabled = false;
                            });
                        });
                    },
                    error: function() {
                        wrap.innerHTML = '<div class="sched-empty">' +
                            '<i class="fa-solid fa-triangle-exclamation"></i>' +
                            'Could not load schedules. Please refresh and try again.' +
                            '</div>';
                    }
                });
            }

            function confirmEnroll() {
                if (!_selectedCourseId) return;
                var btn = document.getElementById('btn-sched-confirm');
                if (btn) {
                    btn.disabled = true;
                    btn.textContent = 'Enrolling…';
                }
                var form = document.getElementById('enroll-form');
                form.action = '/student/course/' + _selectedCourseId + '/enroll';
                form.submit();
            }

            function initScheduleModalBackdrop() {
                var backdrop = document.getElementById('sched-backdrop');
                if (backdrop) {
                    backdrop.addEventListener('click', function(e) {
                        if (e.target === this) closeScheduleModal();
                    });
                }
            }

            // The HTML uses inline onclick="openScheduleModal(...)" / closeScheduleModal() /
            // confirmEnroll() attributes, which resolve against the global `window` object —
            // expose them explicitly so they're reachable regardless of this IIFE's scope.
            window.openScheduleModal = openScheduleModal;
            window.closeScheduleModal = closeScheduleModal;
            window.confirmEnroll = confirmEnroll;

            function init() {
                initTabs();
                initScheduleModalBackdrop();
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
