@extends('frontend.staff.layout.master')
@section('page_title', isset($page_title) ? $page_title : 'Page Title Here')
@push('styles')
    <style>
        /* ── Counter Carousel ── */
        .stat-card {
            border-radius: 16px;
            padding: 24px 20px;
            display: flex;
            align-items: center;
            gap: 16px;
            transition: transform .2s ease, box-shadow .2s ease;
            cursor: default;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 32px rgba(0, 0, 0, .10) !important;
        }

        .stat-icon {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .stat-card .stat-label {
            font-size: .75rem;
            font-weight: 600;
            letter-spacing: .06em;
            text-transform: uppercase;
            opacity: .7;
            margin-bottom: 2px;
        }

        .stat-card .stat-value {
            font-size: 1.75rem;
            font-weight: 800;
            line-height: 1;
        }

        /* ── Section header ── */
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-bottom: 20px;
        }

        .section-header h5 {
            font-size: 1rem;
            font-weight: 700;
            margin: 0;
        }

        .section-header p {
            font-size: .8rem;
            color: #8a97a8;
            margin: 4px 0 0;
        }

        .section-header a {
            font-size: .8rem;
            font-weight: 600;
            text-decoration: none;
            white-space: nowrap;
        }

        /* ── Today Course Card (NFT-style) ── */
        .course-card {
            border-radius: 16px !important;
            overflow: visible !important;
            /* allow avatar to overflow banner */
            border: none !important;
            transition: transform .22s ease, box-shadow .22s ease;
            height: 100%;
            background: #fff;
            box-shadow: 0 2px 12px rgba(0, 0, 0, .07);
        }

        .course-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 16px 40px rgba(0, 0, 0, .13) !important;
        }

        /* Banner — clip overflow here so img stays rounded */
        .course-card .banner-wrap {
            position: relative;
            overflow: hidden;
            border-radius: 16px 16px 0 0;
            line-height: 0;
        }

        .course-card .card-img {
            width: 100%;
            height: 155px;
            object-fit: cover;
            transition: transform .4s ease;
            display: block;
        }

        .course-card:hover .card-img {
            transform: scale(1.04);
        }

        /* Status badge on banner — use Bootstrap .badge, just position it */
        .course-card .banner-wrap .badge {
            position: absolute;
            top: 12px;
            right: 12px;
            z-index: 2;
            font-size: .7rem;
            font-weight: 700;
            letter-spacing: .04em;
            padding: 5px 12px;
            border-radius: 30px;
        }

        /* Content sits below banner; avatar pulls up with negative margin */
        .course-card .card-content {
            padding: 0 16px 16px;
            text-align: center;
        }

        /* Avatar — negative margin pulls it over the banner edge */
        .course-card .avatar-overlap {
            display: block;
            margin: -22px auto 8px;
            width: 48px;
            position: relative;
            z-index: 3;
        }

        .course-card .avatar-overlap img {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            border: 3px solid #fff;
            object-fit: cover;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .18);
            display: block;
        }

        /* Course title */
        .course-card .course-title {
            font-size: .875rem;
            font-weight: 700;
            color: #1e2a3a;
            line-height: 1.35;
            margin-bottom: 0;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* Schedule / time two-column row */
        .course-card .stats-row {
            display: flex;
            justify-content: space-between;
            margin-top: 12px;
            padding-bottom: 12px;
            border-bottom: 1px solid #f0f3f8;
        }

        .course-card .stats-row .stat-lbl {
            font-size: .7rem;
            color: #9aa5b8;
            margin-bottom: 2px;
        }

        .course-card .stats-row .stat-val {
            font-size: .82rem;
            font-weight: 700;
            color: #1e2a3a;
            margin: 0;
        }

        /* Instructor + price bottom row */
        .course-card .schedule-row {
            display: flex;
            align-items: center;
            gap: 10px;
            padding-top: 12px;
        }

        .course-card .schedule-row .sch-icon {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .course-card .schedule-row .sch-days {
            font-size: .8rem;
            font-weight: 700;
            color: #1e2a3a;
            margin: 0;
        }

        .course-card .schedule-row .sch-time {
            font-size: .7rem;
            color: #8a97a8;
        }

        .course-card .schedule-row .sch-price {
            margin-left: auto;
            font-size: .9rem;
            font-weight: 800;
            color: #2563eb;
        }

        /* ── Recent Course Card (horizontal) ── */
        .recent-card {
            display: flex;
            gap: 14px;
            align-items: flex-start;
            padding: 14px;
            border-radius: 14px;
            border: 1px solid #f0f3f8;
            transition: background .18s, box-shadow .18s;
            text-decoration: none;
        }

        .recent-card:hover {
            background: #f8fbff;
            box-shadow: 0 4px 18px rgba(0, 0, 0, .07);
        }

        .recent-card img {
            width: 72px;
            height: 62px;
            object-fit: cover;
            border-radius: 10px;
            flex-shrink: 0;
        }

        .recent-card .rc-title {
            font-size: .83rem;
            font-weight: 700;
            color: #1e2a3a;
            margin-bottom: 4px;
            line-height: 1.3;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .recent-card .rc-meta {
            font-size: .72rem;
            color: #8a97a8;
        }

        .recent-card .rc-price {
            font-size: .8rem;
            font-weight: 800;
            color: #2563eb;
            margin-top: 4px;
        }

        /* ── Teacher Card ── */
        .teacher-card {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 12px 14px;
            border-radius: 14px;
            border: 1px solid #f0f3f8;
            transition: background .18s, box-shadow .18s;
            text-decoration: none;
        }

        .teacher-card:hover {
            background: #f8fbff;
            box-shadow: 0 4px 18px rgba(0, 0, 0, .07);
        }

        .teacher-card .avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            object-fit: cover;
            flex-shrink: 0;
            border: 2px solid #e3ecf9;
        }

        .teacher-card .tc-name {
            font-size: .85rem;
            font-weight: 700;
            color: #1e2a3a;
            margin-bottom: 2px;
        }

        .teacher-card .tc-sub {
            font-size: .72rem;
            color: #8a97a8;
        }

        .teacher-card .tc-badge {
            margin-left: auto;
            background: #eef4ff;
            color: #2563eb;
            font-size: .7rem;
            font-weight: 700;
            padding: 3px 10px;
            border-radius: 20px;
            flex-shrink: 0;
        }

        /* ── Empty state ── */
        .empty-state {
            text-align: center;
            padding: 40px 20px;
        }

        .empty-state svg {
            opacity: .3;
            margin-bottom: 12px;
        }

        .empty-state p {
            font-size: .85rem;
            color: #9aa5b8;
            margin: 0;
        }
    </style>
@endpush
@section('content')
    {{-- ══════════════════════════════════════
     STAT COUNTERS
══════════════════════════════════════ --}}
    <div class="owl-carousel counter-carousel owl-theme mb-4">
        {{-- Staffs --}}
        <div class="item">
            <div class="stat-card shadow-sm" style="background:#fdf2f8;">
                <div class="stat-icon" style="background:#fce7f3;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none"
                        stroke="#db2777" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                        <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                    </svg>
                </div>
                <div>
                    <div class="stat-label" style="color:#db2777;">Staffs</div>
                    <div class="stat-value" style="color:#db2777;">{{ $staffs_count ?? 0 }}</div>
                </div>
            </div>
        </div>
        {{-- Reports --}}
        <div class="item">
            <div class="stat-card shadow-sm" style="background:#fffbeb;">
                <div class="stat-icon" style="background:#fef3c7;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none"
                        stroke="#d97706" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 12l2 2l4 -4" />
                        <path d="M12 3c7.2 0 9 1.8 9 9s-1.8 9 -9 9s-9 -1.8 -9 -9s1.8 -9 9 -9z" />
                    </svg>
                </div>
                <div>
                    <div class="stat-label" style="color:#d97706;">Reports</div>
                    <div class="stat-value" style="color:#d97706;">{{ $reports_count ?? 0 }}</div>
                </div>
            </div>
        </div>
        {{-- Students --}}
        <div class="item">
            <div class="stat-card shadow-sm" style="background:#f0fdf4;">
                <div class="stat-icon" style="background:#dcfce7;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none"
                        stroke="#16a34a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 7l9 -4l9 4l-9 4z" />
                        <path d="M7 10v4c0 1.657 2.239 3 5 3s5 -1.343 5 -3v-4" />
                    </svg>
                </div>
                <div>
                    <div class="stat-label" style="color:#16a34a;">Students</div>
                    <div class="stat-value" style="color:#16a34a;">{{ $students_count ?? 0 }}</div>
                </div>
            </div>
        </div>
        {{-- Courses --}}
        <div class="item">
            <div class="stat-card shadow-sm" style="background:#eef4ff;">
                <div class="stat-icon" style="background:#dbeafe;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none"
                        stroke="#2563eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 19.5a2.5 2.5 0 0 1 2.5 -2.5h13.5" />
                        <path d="M6.5 17a2.5 2.5 0 0 0 0 5" />
                        <path d="M8 7h6" />
                        <path d="M8 11h8" />
                        <path d="M8 15h5" />
                        <path d="M4 5a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v14" />
                    </svg>
                </div>
                <div>
                    <div class="stat-label" style="color:#2563eb;">Courses</div>
                    <div class="stat-value" style="color:#2563eb;">{{ $courses_count ?? 0 }}</div>
                </div>
            </div>
        </div>
    </div>{{-- /counter-carousel --}}
    {{-- ══════════════════════════════════════
     TODAY'S COURSES
══════════════════════════════════════ --}}
    <div class="card border-0 shadow-sm mb-4" style="border-radius:18px;">
        <div class="card-body p-4">
            <div class="section-header">
                <div>
                    <h5>Today's Courses</h5>
                    <p>Courses scheduled for {{ now()->format('l, F j') }}</p>
                </div>
                <a href="{{ route('staff.courses.index') }}" class="text-primary">
                    View All <i class="ti ti-arrow-right"></i>
                </a>
            </div>
            <div class="row g-3">
                @forelse ($today_courses as $course)
                    <div class="col-sm-6 col-xl-4">
                        <a href="{{ route('staff.courses.show', $course->id) }}" class="text-decoration-none">
                            <div class="card course-card shadow-sm">
                                {{-- Banner image --}}
                                <div class="banner-wrap">
                                    <img src="{{ asset($course->thumbnail == '' ? '/default-images/staff/no-course-img.png' : $course->thumbnail) }}"
                                        class="card-img" alt="{{ $course->title }}">
                                    @if ($course->status == 'active')
                                        <span class="badge bg-success">OPEN</span>
                                    @else
                                        <span class="badge bg-secondary">CLOSED</span>
                                    @endif
                                </div>
                                {{-- Content --}}
                                <div class="card-content">
                                    {{-- Instructor avatar overlapping banner --}}
                                    <div class="avatar-overlap">
                                        <img src="{{ asset($course->instructor->image == 'no-img.jpg' ? '/default-images/user/both.jpg' : $course->instructor->image) }}"
                                            alt="{{ $course->instructor->name }}" title="{{ $course->instructor->name }}">
                                    </div>
                                    {{-- Course title --}}
                                    <h6 class="course-title mb-0">{{ Str::limit($course->title, 48) }}</h6>
                                    {{-- Schedule + Price stats ── mirroring the NFT Volume / Floor Price row --}}
                                    @if ($course->schedule)
                                        @php
                                            $days = collect(explode('-', $course->schedule->study_day))
                                                ->map(fn($d) => ucfirst(substr($d, 0, 3)))
                                                ->implode(' • ');
                                            $start = \Carbon\Carbon::parse($course->schedule->start_time)->format(
                                                'g:i A',
                                            );
                                        @endphp
                                        <div class="stats-row">
                                            <div class="text-start">
                                                <div class="stat-lbl">Schedule</div>
                                                <p class="stat-val">{{ $days }}</p>
                                            </div>
                                            <div class="text-end">
                                                <div class="stat-lbl">Start Time</div>
                                                <p class="stat-val">{{ $start }}</p>
                                            </div>
                                        </div>
                                    @endif
                                    {{-- Bottom row: instructor name + price ── mirroring the ETH/price row --}}
                                    <div class="schedule-row">
                                        <div class="sch-icon">
                                            <i class="ti ti-user fs-4 text-muted"></i>
                                        </div>
                                        <div>
                                            <p class="sch-days mb-0">{{ $course->instructor->name }}</p>
                                            <span class="sch-time">Instructor</span>
                                        </div>
                                        <span class="sch-price">${{ number_format($course->price, 2) }}</span>
                                    </div>
                                </div>{{-- /card-content --}}
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="empty-state">
                            <svg xmlns="http://www.w3.org/2000/svg" width="56" height="56" viewBox="0 0 24 24"
                                fill="none" stroke="#94a3b8" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round">
                                <rect x="4" y="4" width="16" height="16" rx="2" />
                                <path d="M8 12h8" />
                                <path d="M12 8v8" />
                            </svg>
                            <p class="fw-semibold mb-1" style="color:#64748b;">No courses today</p>
                            <p>Check back later or view the full course list.</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
    {{-- ══════════════════════════════════════
     RECENT COURSES + POPULAR TEACHERS
══════════════════════════════════════ --}}
    <div class="row g-4">
        {{-- Recent Courses ── left column ── --}}
        <div class="col-xl-7">
            <div class="card border-0 shadow-sm h-100" style="border-radius:18px;">
                <div class="card-body p-4">
                    <div class="section-header">
                        <div>
                            <h5>Recent Courses</h5>
                            <p>Latest courses added to the platform</p>
                        </div>
                        <a href="{{ route('staff.courses.index') }}" class="text-primary">
                            View All <i class="ti ti-arrow-right"></i>
                        </a>
                    </div>
                    <div class="d-flex flex-column gap-2">
                        @forelse ($recent_courses as $course)
                            <a href="{{ route('staff.courses.show', $course->id) }}" class="recent-card">
                                <img src="{{ asset($course->thumbnail == '' ? '/default-images/staff/no-course-img.png' : $course->thumbnail) }}"
                                    alt="{{ $course->title }}">
                                <div class="flex-grow-1 min-w-0">
                                    <div class="rc-title">{{ Str::limit($course->title, 55) }}</div>
                                    <div class="rc-meta">
                                        <i class="ti ti-user me-1"></i>{{ $course->instructor->name }}
                                        &nbsp;·&nbsp;
                                        <i class="ti ti-calendar me-1"></i>{{ $course->created_at->diffForHumans() }}
                                    </div>
                                    <div class="rc-price">${{ number_format($course->price, 2) }}</div>
                                </div>
                                @if ($course->status == 'active')
                                    <span class="badge rounded-pill"
                                        style="background:#f0fdf4;color:#16a34a;font-size:.65rem;font-weight:700;height:fit-content;white-space:nowrap;">OPEN</span>
                                @else
                                    <span class="badge rounded-pill"
                                        style="background:#f1f5f9;color:#64748b;font-size:.65rem;font-weight:700;height:fit-content;white-space:nowrap;">CLOSED</span>
                                @endif
                            </a>
                        @empty
                            <div class="empty-state">
                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48"
                                    viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M4 19.5a2.5 2.5 0 0 1 2.5-2.5H20" />
                                    <path d="M6.5 17a2.5 2.5 0 0 0 0 5" />
                                    <path d="M8 7h6" />
                                    <path d="M8 11h8" />
                                    <path d="M4 5a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v14" />
                                </svg>
                                <p>No recent courses found.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
        {{-- Popular Teachers ── right column ── --}}
        <div class="col-xl-5">
            <div class="card border-0 shadow-sm h-100" style="border-radius:18px;">
                <div class="card-body p-4">
                    <div class="section-header">
                        <div>
                            <h5>Popular Teachers</h5>
                            <p>Instructors with the most courses</p>
                        </div>
                    </div>
                    <div class="d-flex flex-column gap-2">
                        @forelse ($popular_teachers as $teacher)
                            <div class="teacher-card">
                                <img class="avatar"
                                    src="{{ asset($teacher->image == 'no-img.jpg' ? '/default-images/user/both.jpg' : $teacher->image) }}"
                                    alt="{{ $teacher->name }}">
                                <div class="flex-grow-1 min-w-0">
                                    <div class="tc-name">{{ $teacher->name }}</div>
                                    <div class="tc-sub">{{ $teacher->email }}</div>
                                </div>
                                <span class="tc-badge">
                                    {{ $teacher->courses_count }} {{ Str::plural('course', $teacher->courses_count) }}
                                </span>
                            </div>
                        @empty
                            <div class="empty-state">
                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48"
                                    viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                                    <path d="M6 21v-2a4 4 0 0 1 4-4h4a4 4 0 0 1 4 4v2" />
                                </svg>
                                <p>No teachers found.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>{{-- /row --}}
@endsection
