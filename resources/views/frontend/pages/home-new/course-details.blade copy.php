@extends('frontend.layouts.new.master')
@section('page_title', isset($page_title) ? $page_title : 'Course Details')
@push('styles')
    <style>
        /* ── Skeleton shimmer ── */
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

        /* ════════════════════════════
                                                                       Schedule Picker Modal
                                                                       ════════════════════════════ */
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
            animation: modal-up .22s ease;
        }

        @keyframes modal-up {
            from {
                opacity: 0;
                transform: translateY(14px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
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
            transition: color .15s;
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

        .sched-list::-webkit-scrollbar {
            width: 4px;
        }

        .sched-list::-webkit-scrollbar-thumb {
            background: #d0d5dd;
            border-radius: 4px;
        }

        .sched-option {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 14px 16px;
            border: 1.5px solid #E4E7EC;
            border-radius: 12px;
            cursor: pointer;
            transition: border-color .15s, background .15s;
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
            transition: background .15s;
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
            transition: opacity .15s;
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
    {{-- ════════════ Page Header ════════════ --}}
    <div class="headerdetail">
        <a href="{{ route('home') }}">Home/ </a>
        <a href="{{ route('course') }}">Courses/ </a>
        @if ($course->category)
            <a href="{{ route('course') }}?category={{ $course->category->id }}">{{ $course->category->name }}/ </a>
        @endif
        <a href="#">{{ $course->title }}</a>
        <br><br>
        <h2>{{ $course->title }}</h2>
        <p>
            @if ($course->short_description)
                {{ $course->short_description }}
            @else
                No description available for this course yet.
            @endif
        </p>
        <div class="typeaboutdetail">
            <div class="header-deatil">
                <i class="fa-solid fa-star" style="color:gold; font-size: 16px;"></i>
                <span>No ratings yet</span>
            </div>
            <div class="header-deatil">
                <i class="fa-solid fa-user-graduate"></i>
                <span>
                    {{ $course->students_count ?? $course->students->count() }}
                    student{{ ($course->students_count ?? $course->students->count()) !== 1 ? 's' : '' }}
                </span>
            </div>
            @if ($course->level)
                <div class="header-deatil">
                    <i class="fa-solid fa-layer-group"></i>
                    <span>{{ ucfirst($course->level) }}</span>
                </div>
            @endif
            @if ($course->duration)
                <div class="header-deatil">
                    <i class="fa-regular fa-clock"></i>
                    <span>{{ $course->duration }} hrs total</span>
                </div>
            @endif
            @if ($course->start_date)
                <div class="header-deatil">
                    <i class="fa-regular fa-calendar-days"></i>
                    <span>Starts {{ $course->start_date->format('M d, Y') }}</span>
                </div>
            @endif
        </div>
    </div>
    {{-- ════════════ Body ════════════ --}}
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
                {{-- Overview --}}
                <section id="section1" class="active">
                    <div id="detail-descrip-block">
                        <h3>Description</h3>
                        @if ($course->description)
                            <div class="content-ready">{!! nl2br(e($course->description)) !!}</div>
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
                    <div id="requiment-detail">
                        <h3>Requirements</h3>
                        <div class="no-data-block">
                            <i class="fa-solid fa-list-check"></i>
                            <p>Requirements will be listed here once the instructor adds them.</p>
                        </div>
                    </div>
                    <div id="what-you-learn">
                        <h3>What you'll learn</h3>
                        <div class="no-data-block">
                            <i class="fa-solid fa-graduation-cap"></i>
                            <p>Learning outcomes haven't been set yet. Check back soon.</p>
                        </div>
                    </div>
                </section>
                {{-- Curriculum --}}
                <section id="section2">
                    <div class="curriculum-setion">
                        <h3><b>Course Content</b></h3>
                        <div class="section-lecture-hour">
                            <p>{{ $course->total_sessions }} Sessions</p>
                            <p>{{ $course->duration }} Hours total</p>
                            @if ($course->completed_sessions > 0)
                                <p>{{ $course->completed_sessions }} Completed</p>
                            @endif
                        </div>
                    </div>
                    <div class="section-content">
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
                {{-- Instructor --}}
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
                                    <p><i class="fa-solid fa-users"></i>
                                        {{ $course->instructor->taught_students_count ?? '—' }} Students
                                    </p>
                                    <p><i class="fa-regular fa-bookmark"></i>
                                        {{ $course->instructor->courses_count ?? '—' }} Courses
                                    </p>
                                </div>
                            </div>
                            <h3>{{ $course->instructor->name }}</h3>
                            <h5>{{ $course->instructor->job_title ?? 'Instructor' }}</h5>
                            <div class="descriptionn">
                                @if ($course->instructor->bio)
                                    <p>{{ $course->instructor->bio }}</p>
                                @else
                                    <div class="no-data-block" style="border:none; padding:12px 0;">
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
            </div>{{-- /.section-oci --}}
            {{-- ════ Sidebar ════ --}}
            <div class="boxcard">
                {{-- Course Image --}}
                @if ($course->thumbnail)
                    <img src="{{ asset($course->thumbnail) }}" alt="{{ $course->title }}">
                @else
                    <div class="skeleton" style="width:100%; height:180px; border-radius:8px;"></div>
                @endif
                {{-- Price --}}
                <h3>${{ number_format($course->price, 2) }}</h3>
                {{-- Enroll Button --}}
                @if (auth()->check())
                    @if ($alreadyEnrolled)
                        <button class="btn btn-success" disabled>Already Enrolled</button>
                    @else
                        <button class="text-white"
                            onclick="openScheduleModal({{ $course->id }}, '{{ addslashes($course->title) }}')">Enroll
                            Now</button>
                    @endif
                @else
                    <button class="btn btn-primary text-white"
                        onclick="window.location.href='{{ route('login') }}'">Login To
                        Enroll</button>
                @endif
                {{-- Favorite & Share --}}
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
                {{-- Certificate --}}
                <div class="certificate-completed">
                    <i class="fa-solid fa-award"></i>
                    <p>Certificate of Completion</p>
                </div>
                {{-- Weekly Schedule --}}
                <div class="weekschedule">
                    <i class="fa-regular fa-calendar-days"></i>
                    <p>Weekly Schedule</p>
                    <p class="hour">{{ $course->duration }} hours</p>
                </div>
                {{-- Schedule Details --}}
                @if ($course->schedule)
                    <p class="pweekly">
                        • {{ $course->schedule->short_days }} ({{ $course->schedule->formatted_time }})
                        @if (isset($siblingCount) && $siblingCount > 1)
                            <br><small style="color:#667085">
                                +{{ $siblingCount - 1 }} other schedule{{ $siblingCount > 2 ? 's' : '' }} available
                            </small>
                        @endif
                    </p>
                @else
                    <div class="no-data-block" style="margin-top:10px; padding:16px;">
                        <i class="fa-regular fa-calendar-xmark"></i>
                        <p>Schedule not set yet.</p>
                    </div>
                @endif
            </div>{{-- /.boxcard --}}
        </div>{{-- /.detail-body --}}
    </div>{{-- /.overview-section --}}
    {{-- More Courses --}}
    <h2 id="h2-more-course">More Courses</h2>
    <div class="more-course-detail">
        @if ($moreCourses->isNotEmpty())
            <div class="mainbox">
                @foreach ($moreCourses as $title => $group)
                    @php $mc = $group->first(); @endphp
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
                        @if ($mc->schedule)
                            <p class="pweekly">
                                • {{ $mc->schedule->short_days }} ({{ $mc->schedule->formatted_time }})
                                @if ($group->count() > 1)
                                    <br>+{{ $group->count() - 1 }} more schedules
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
            <div class="no-data-block" style="margin:0 auto 40px; max-width:500px;">
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
        /* ── Tab navigation ── */
        ['Overview', 'curriculum', 'instructor'].forEach((key, i) => {
            const secId = '#section' + (i + 1);
            $('#' + key).on('click', function() {
                $('section').removeClass('active');
                $(secId).addClass('active');
                ['Overview', 'curriculum', 'instructor'].forEach(k => {
                    const active = k === key;
                    $('#' + k).css({
                        'color': active ? '#007bff' : '',
                        'border-bottom': active ? '3px solid #007bff' : '',
                        'font-weight': active ? 'bold' : 'normal',
                    });
                });
            });
        });
        /* ════════════════════════════
           Schedule Modal
           ════════════════════════════ */
        let _selectedCourseId = null;

        function openScheduleModal(currentCourseId, courseTitle) {
            _selectedCourseId = null;
            document.getElementById('btn-sched-confirm').disabled = true;
            document.getElementById('sched-backdrop').classList.add('open');
            loadSections(currentCourseId, courseTitle);
        }

        function closeScheduleModal() {
            document.getElementById('sched-backdrop').classList.remove('open');
        }
        document.getElementById('sched-backdrop').addEventListener('click', function(e) {
            if (e.target === this) closeScheduleModal();
        });

        function loadSections(currentCourseId, courseTitle) {
            const wrap = document.getElementById('sched-list-wrap');
            wrap.innerHTML = `<div class="sched-loading">
            <div class="sched-skel-row"></div>
            <div class="sched-skel-row"></div>
            <div class="sched-skel-row"></div>
        </div>`;
            // Uses the existing student.course.schedules route with ?title= param
            $.ajax({
                url: '{{ route('student.course.schedules', ['course' => '__ID__']) }}'.replace('__ID__',
                    currentCourseId),
                method: 'GET',
                data: {
                    title: courseTitle
                },
                success(res) {
                    const sections = res.sections ?? [];
                    if (!sections.length) {
                        wrap.innerHTML = `<div class="sched-empty">
                        <i class="fa-regular fa-calendar-xmark"></i>
                        No schedules are available for this course right now.
                    </div>`;
                        return;
                    }
                    wrap.innerHTML = sections.map(s => {
                        const isCurrent = s.id === currentCourseId;
                        const shiftClass = (s.shift ?? '').toLowerCase();
                        const badgeClass = isCurrent ? 'current' : (s.is_full ? 'full' : 'open');
                        const badgeLabel = isCurrent ? 'Current' : (s.is_full ? 'Full' : 'Open');
                        return `
                    <label class="sched-option">
                        <input type="radio" name="section" value="${s.id}"
                               ${isCurrent ? 'checked' : ''}
                               ${s.is_full && !isCurrent ? 'disabled' : ''}>
                        <div class="sched-option-body">
                            <div class="sched-option-title">${s.days}</div>
                            <div class="sched-option-meta">
                                <span>
                                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>
                                    </svg>
                                    ${s.time}
                                </span>
                                ${s.instructor ? `<span>
                                                                                                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                                                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                                                                                    <circle cx="12" cy="7" r="4"/>
                                                                                                </svg>
                                                                                                ${s.instructor}
                                                                                            </span>` : ''}
                                ${s.start_date ? `<span>
                                                                                                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                                                                    <rect x="3" y="4" width="18" height="18" rx="2"/>
                                                                                                    <path d="M16 2v4M8 2v4M3 10h18"/>
                                                                                                </svg>
                                                                                                Starts ${s.start_date}
                                                                                            </span>` : ''}
                            </div>
                            ${s.shift ? `<span class="sched-shift ${shiftClass}">${s.shift}</span>` : ''}
                        </div>
                        <span class="sched-badge ${badgeClass}">${badgeLabel}</span>
                    </label>`;
                    }).join('');
                    // If only one section, or current section found → pre-select it
                    const preselect = sections.find(s => s.id === currentCourseId);
                    if (preselect || sections.length === 1) {
                        _selectedCourseId = preselect ? currentCourseId : sections[0].id;
                        document.getElementById('btn-sched-confirm').disabled = false;
                    }
                    wrap.querySelectorAll('input[type=radio]').forEach(radio => {
                        radio.addEventListener('change', function() {
                            _selectedCourseId = parseInt(this.value);
                            document.getElementById('btn-sched-confirm').disabled = false;
                        });
                    });
                },
                error() {
                    wrap.innerHTML = `<div class="sched-empty">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    Could not load schedules. Please refresh and try again.
                </div>`;
                }
            });
        }

        function confirmEnroll() {
            if (!_selectedCourseId) return;
            const btn = document.getElementById('btn-sched-confirm');
            btn.disabled = true;
            btn.textContent = 'Enrolling…';
            const form = document.getElementById('enroll-form');
            // student.course.enroll = POST /student/course/{course}/enroll
            form.action = '/student/course/' + _selectedCourseId + '/enroll';
            form.submit();
        }
    </script>
@endpush
