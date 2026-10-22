@extends('frontend.staff.layout.master')
@section('page_title', isset($page_title) ? $page_title : 'Page Title Here')
@push('styles')
<style>
  /* ── Hero / welcome banner ── light, airy — matches the rest of the
     page instead of a heavy saturated block ── */
  .dash-hero {
    border-radius: 18px;
    padding: 26px 30px;
    background: linear-gradient(120deg, #eef2ff 0%, #eaf1ff 55%, #eef7ff 100%);
    border: 1px solid #e3eaf8;
    color: #1e2a3a;
    position: relative;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    flex-wrap: wrap;
  }
  .dash-hero::before {
    content: "";
    position: absolute;
    top: -60px;
    right: -30px;
    width: 220px;
    height: 220px;
    border-radius: 50%;
    background: rgba(37, 99, 235, .05);
  }
  .dash-hero::after {
    content: "";
    position: absolute;
    bottom: -80px;
    right: 140px;
    width: 160px;
    height: 160px;
    border-radius: 50%;
    background: rgba(37, 99, 235, .04);
  }
  .dash-hero .hero-text {
    position: relative;
    z-index: 1;
  }
  .dash-hero .hero-text h4 {
    font-weight: 800;
    margin-bottom: 4px;
    font-size: 1.4rem;
    color: #1e2a3a;
  }
  .dash-hero .hero-text p {
    margin: 0;
    color: #5b6b85;
    font-size: .88rem;
  }
  .dash-hero .hero-actions {
    position: relative;
    z-index: 1;
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
  }
  .dash-hero .hero-actions .btn {
    border-radius: 10px;
    font-size: .82rem;
    font-weight: 600;
    padding: 9px 16px;
    white-space: nowrap;
    display: inline-flex;
    align-items: center;
    gap: 6px;
  }
  .dash-hero .btn-light-action {
    background: rgba(37, 99, 235, .08);
    border: 1px solid rgba(37, 99, 235, .18);
    color: #1d4ed8;
  }
  .dash-hero .btn-light-action:hover {
    background: rgba(37, 99, 235, .14);
    color: #1d4ed8;
  }
  .dash-hero .btn-solid-action {
    background: #2563eb;
    color: #fff;
    border: 1px solid #2563eb;
    box-shadow: 0 6px 16px rgba(37, 99, 235, .25);
  }
  .dash-hero .btn-solid-action:hover {
    background: #1d4ed8;
    color: #fff;
  }
  /* ── Stat grid (replaces the old auto-playing owl-carousel row) ──
     A plain responsive grid is more legible for glanceable KPIs than a
     carousel that auto-advances every few seconds — the numbers here are
     exactly the ones staff want to read at a glance, not browse through. */
  .stat-grid {
    display: grid;
    grid-template-columns: repeat(6, 1fr);
    gap: 16px;
  }
  @media (max-width: 1399.98px) {
    .stat-grid {
      grid-template-columns: repeat(3, 1fr);
    }
  }
  @media (max-width: 767.98px) {
    .stat-grid {
      grid-template-columns: repeat(2, 1fr);
      gap: 12px;
    }
  }
  .stat-card {
    border-radius: 16px;
    padding: 18px 16px;
    display: flex;
    align-items: flex-start;
    gap: 12px;
    transition: transform .2s ease, box-shadow .2s ease;
    cursor: default;
    min-width: 0;
  }
  .stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 32px rgba(0, 0, 0, .10) !important;
  }
  .stat-icon {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
  }
  .stat-icon svg {
    width: 21px;
    height: 21px;
  }
  .stat-card .stat-label {
    font-size: .7rem;
    font-weight: 700;
    letter-spacing: .05em;
    text-transform: uppercase;
    opacity: .7;
    margin-bottom: 3px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }
  .stat-card .stat-value {
    font-size: 1.5rem;
    font-weight: 800;
    line-height: 1;
  }
  .stat-card .stat-trend {
    display: inline-flex;
    align-items: center;
    gap: 3px;
    font-size: .68rem;
    font-weight: 700;
    margin-top: 6px;
    opacity: .85;
  }
  /* ── Section header ── */
  .section-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    margin-bottom: 20px;
    gap: 10px;
    flex-wrap: wrap;
  }
  .section-header h5 {
    font-size: 1rem;
    font-weight: 700;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
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
  .pending-badge {
    font-size: .68rem;
    font-weight: 700;
    background: #fef3c7;
    color: #b45309;
    padding: 2px 9px;
    border-radius: 20px;
  }
  /* ── Today Course Card (NFT-style) — also reused for Recent Courses ── */
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
  /* ── Teacher Card (grid tile) ── */
  .teacher-grid-card {
    display: block;
    text-align: center;
    padding: 22px 16px;
    border-radius: 16px;
    border: 1px solid #f0f3f8;
    background: #fff;
    text-decoration: none;
    height: 100%;
    transition: transform .2s ease, box-shadow .2s ease;
  }
  .teacher-grid-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 32px rgba(0, 0, 0, .09);
  }
  .teacher-grid-card .avatar {
    width: 64px;
    height: 64px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid #eef4ff;
    margin: 0 auto 12px;
    display: block;
  }
  .teacher-grid-card .tc-name {
    font-size: .88rem;
    font-weight: 700;
    color: #1e2a3a;
    margin-bottom: 2px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
  }
  .teacher-grid-card .tc-sub {
    font-size: .72rem;
    color: #8a97a8;
    margin-bottom: 10px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
  }
  .teacher-grid-card .tc-badge {
    display: inline-block;
    background: #eef4ff;
    color: #2563eb;
    font-size: .7rem;
    font-weight: 700;
    padding: 4px 12px;
    border-radius: 20px;
  }
  /* ── Report item row (My Recent Reports) ── */
  .report-item {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    padding: 12px 14px;
    border-radius: 14px;
    border: 1px solid #f0f3f8;
    transition: background .18s, box-shadow .18s;
  }
  .report-item:hover {
    background: #f8fbff;
    box-shadow: 0 4px 18px rgba(0, 0, 0, .07);
  }
  .report-item .ri-icon {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: #fffbeb;
    color: #d97706;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
  }
  .report-item .ri-text {
    font-size: .82rem;
    color: #1e2a3a;
    line-height: 1.4;
    margin-bottom: 2px;
  }
  .report-item .ri-meta {
    font-size: .7rem;
    color: #8a97a8;
  }
  .report-item .ri-status {
    margin-left: auto;
    flex-shrink: 0;
    font-size: .66rem;
    font-weight: 700;
    padding: 3px 10px;
    border-radius: 20px;
    white-space: nowrap;
  }
  /* ── Chart / insight cards ── */
  .chart-card {
    border-radius: 18px;
  }
  .chart-legend-dot {
    width: 9px;
    height: 9px;
    border-radius: 50%;
    display: inline-block;
    margin-right: 6px;
  }
  .chart-legend-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-size: .8rem;
    padding: 6px 0;
  }
  .chart-legend-row + .chart-legend-row {
    border-top: 1px solid #f0f3f8;
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
  @media (max-width: 575.98px) {
    .dash-hero {
      padding: 20px 18px;
    }
    .dash-hero .hero-actions {
      width: 100%;
    }
    .dash-hero .hero-actions .btn {
      flex: 1 1 auto;
      justify-content: center;
    }
  }
</style>
@endpush
@section('content')
{{-- ══════════════════════════════════════
     WELCOME HERO
══════════════════════════════════════ --}}
<div class="dash-hero mb-4">
  <div class="hero-text">
    <h4>Welcome back, {{ explode(' ', Auth::user()->name)[0] ?? 'Staff' }} 👋</h4>
    <p>{{ now()->format('l, F j, Y') }} &nbsp;·&nbsp; Here's what's happening across the platform today.</p>
  </div>
  <div class="hero-actions">
    <a href="{{ route('staff.courses.create') }}" class="btn btn-solid-action">
      <i class="ti ti-plus fs-5"></i> Add Course
    </a>
    <a href="{{ route('staff.student.registration') }}" class="btn btn-light-action">
      <i class="ti ti-user-plus fs-5"></i> Register Student
    </a>
    <a href="{{ route('staff.teacher.index') }}" class="btn btn-light-action">
      <i class="ti ti-school fs-5"></i> Add Teacher
    </a>
    <a href="{{ route('staff.invoices') }}" class="btn btn-light-action">
      <i class="ti ti-file-invoice fs-5"></i> Invoices
    </a>
    <a href="{{ route('staff.blogs.create') }}" class="btn btn-light-action">
      <i class="ti ti-pencil fs-5"></i> Write Blog
    </a>
  </div>
</div>
{{-- ══════════════════════════════════════
     STAT COUNTERS
══════════════════════════════════════ --}}
<div class="stat-grid mb-4">
  {{-- Students --}}
  <div class="stat-card shadow-sm" style="background:#f0fdf4;">
    <div class="stat-icon" style="background:#dcfce7;">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M3 7l9 -4l9 4l-9 4z" />
        <path d="M7 10v4c0 1.657 2.239 3 5 3s5 -1.343 5 -3v-4" />
      </svg>
    </div>
    <div class="min-w-0">
      <div class="stat-label" style="color:#16a34a;">Students</div>
      <div class="stat-value" style="color:#16a34a;">{{ $students_count ?? 0 }}</div>
      @if (($students_new_this_week ?? 0) > 0 && ($students_new_this_week ?? 0) < ($students_count ?? 0))
      <div class="stat-trend" style="color:#16a34a;">
        <i class="ti ti-arrow-up-right"></i> +{{ $students_new_this_week }} this week
      </div>
      @endif
    </div>
  </div>
  {{-- Courses --}}
  <div class="stat-card shadow-sm" style="background:#eef4ff;">
    <div class="stat-icon" style="background:#dbeafe;">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M4 19.5a2.5 2.5 0 0 1 2.5 -2.5h13.5" />
        <path d="M6.5 17a2.5 2.5 0 0 0 0 5" />
        <path d="M8 7h6" />
        <path d="M8 11h8" />
        <path d="M8 15h5" />
        <path d="M4 5a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v14" />
      </svg>
    </div>
    <div class="min-w-0">
      <div class="stat-label" style="color:#2563eb;">Courses</div>
      <div class="stat-value" style="color:#2563eb;">{{ $courses_count ?? 0 }}</div>
      @if (($courses_new_this_week ?? 0) > 0 && ($courses_new_this_week ?? 0) < ($courses_count ?? 0))
      <div class="stat-trend" style="color:#2563eb;">
        <i class="ti ti-arrow-up-right"></i> +{{ $courses_new_this_week }} this week
      </div>
      @endif
    </div>
  </div>
  {{-- Enrollments --}}
  <div class="stat-card shadow-sm" style="background:#ecfeff;">
    <div class="stat-icon" style="background:#cffafe;">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#0891b2" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M9 12l2 2l4 -4" />
        <rect x="4" y="4" width="16" height="16" rx="3" />
      </svg>
    </div>
    <div class="min-w-0">
      <div class="stat-label" style="color:#0891b2;">Enrollments</div>
      <div class="stat-value" style="color:#0891b2;">{{ $enrollments_count ?? 0 }}</div>
    </div>
  </div>
  {{-- Reports --}}
  <div class="stat-card shadow-sm" style="background:#fffbeb;">
    <div class="stat-icon" style="background:#fef3c7;">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#d97706" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M9 12l2 2l4 -4" />
        <path d="M12 3c7.2 0 9 1.8 9 9s-1.8 9 -9 9s-9 -1.8 -9 -9s1.8 -9 9 -9z" />
      </svg>
    </div>
    <div class="min-w-0">
      <div class="stat-label" style="color:#d97706;">Reports</div>
      <div class="stat-value" style="color:#d97706;">{{ $reports_count ?? 0 }}</div>
    </div>
  </div>
  {{-- Staffs --}}
  <div class="stat-card shadow-sm" style="background:#fdf2f8;">
    <div class="stat-icon" style="background:#fce7f3;">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#db2777" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
        <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
      </svg>
    </div>
    <div class="min-w-0">
      <div class="stat-label" style="color:#db2777;">Staffs</div>
      <div class="stat-value" style="color:#db2777;">{{ $staffs_count ?? 0 }}</div>
    </div>
  </div>
  {{-- Drafts --}}
  <div class="stat-card shadow-sm" style="background:#f5f3ff;">
    <div class="stat-icon" style="background:#ede9fe;">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#7c3aed" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M14 3v4a1 1 0 0 0 1 1h4" />
        <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
        <path d="M9 12h6" />
        <path d="M9 16h6" />
      </svg>
    </div>
    <div class="min-w-0">
      <div class="stat-label" style="color:#7c3aed;">Drafts</div>
      <div class="stat-value" style="color:#7c3aed;">{{ $draft_courses_count ?? 0 }}</div>
    </div>
  </div>
</div>
{{-- ══════════════════════════════════════
     MY RECENT REPORTS + COURSE STATUS
══════════════════════════════════════ --}}
<div class="row g-4 mb-4">
  <div class="col-xl-7">
    <div class="card border-0 shadow-sm chart-card h-100">
      <div class="card-body p-4">
        <div class="section-header">
          <div>
            <h5>
              My Recent Reports
              @if (($my_pending_reports_count ?? 0) > 0)
              <span class="pending-badge">{{ $my_pending_reports_count }} pending</span>
              @endif
            </h5>
            <p>Reports you've submitted recently</p>
          </div>
          <a href="{{ route('staff.reports.index') }}" class="text-primary">
            View All <i class="ti ti-arrow-right"></i>
          </a>
        </div>
        <div class="d-flex flex-column gap-2">
          @forelse ($my_recent_reports as $report)
          <div class="report-item">
            <div class="ri-icon">
              <i class="ti ti-flag fs-5"></i>
            </div>
            <div class="flex-grow-1 min-w-0">
              <div class="ri-text">{{ Str::limit($report->report_content, 110) }}</div>
              <div class="ri-meta">{{ $report->created_at->diffForHumans() }}</div>
            </div>
            @if ($report->status == 'pending')
            <span class="ri-status" style="background:#fffbeb;color:#b45309;">PENDING</span>
            @else
            <span class="ri-status" style="background:#f0fdf4;color:#16a34a;">REVIEWED</span>
            @endif
          </div>
          @empty
          <div class="empty-state">
            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
              <path d="M9 12l2 2l4 -4" />
              <path d="M12 3c7.2 0 9 1.8 9 9s-1.8 9 -9 9s-9 -1.8 -9 -9s1.8 -9 9 -9z" />
            </svg>
            <p>You haven't submitted any reports yet.</p>
          </div>
          @endforelse
        </div>
      </div>
    </div>
  </div>
  <div class="col-xl-5">
    <div class="card border-0 shadow-sm chart-card h-100">
      <div class="card-body p-4">
        <div class="section-header">
          <div>
            <h5>Course Status</h5>
            <p>Breakdown across all courses</p>
          </div>
        </div>
        <div id="staffCourseStatusChart"></div>
        <div class="mt-2">
          <div class="chart-legend-row">
            <span><span class="chart-legend-dot" style="background:#16a34a;"></span>Active</span>
            <strong>{{ $active_courses_count ?? 0 }}</strong>
          </div>
          <div class="chart-legend-row">
            <span><span class="chart-legend-dot" style="background:#7c3aed;"></span>Draft</span>
            <strong>{{ $draft_courses_count ?? 0 }}</strong>
          </div>
          <div class="chart-legend-row">
            <span><span class="chart-legend-dot" style="background:#94a3b8;"></span>Inactive</span>
            <strong>{{ $inactive_courses_count ?? 0 }}</strong>
          </div>
          <div class="chart-legend-row">
            <span><span class="chart-legend-dot" style="background:#d97706;"></span>Awaiting Payment</span>
            <a href="{{ route('staff.invoices') }}" class="text-decoration-none fw-bold" style="color:#d97706;">{{ $outstanding_invoices_count ?? 0 }}</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
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
              <img src="{{ asset($course->thumbnail == '' ? '/default-images/staff/no-course-img.png' : $course->thumbnail) }}" class="card-img" alt="{{ $course->title }}">
              @if ($course->status == 'active')
              <span class="badge bg-success">OPEN</span>
              @elseif ($course->status == 'draft')
              <span class="badge bg-secondary">DRAFT</span>
              @else
              <span class="badge bg-secondary">CLOSED</span>
              @endif
            </div>
            {{-- Content --}}
            <div class="card-content">
              {{-- Instructor avatar overlapping banner --}}
              <div class="avatar-overlap">
                <img src="{{ asset($course->instructor->image == 'no-img.jpg' ? '/default-images/user/both.jpg' : $course->instructor->image) }}" alt="{{ $course->instructor->name }}" title="{{ $course->instructor->name }}">
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
          <svg xmlns="http://www.w3.org/2000/svg" width="56" height="56" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
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
     RECENT COURSES — grid of course cards
══════════════════════════════════════ --}}
<div class="card border-0 shadow-sm mb-4" style="border-radius:18px;">
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
    <div class="row g-3">
      @forelse ($recent_courses as $course)
      <div class="col-sm-6 col-xl-4">
        <a href="{{ route('staff.courses.show', $course->id) }}" class="text-decoration-none">
          <div class="card course-card shadow-sm">
            <div class="banner-wrap">
              <img src="{{ asset($course->thumbnail == '' ? '/default-images/staff/no-course-img.png' : $course->thumbnail) }}" class="card-img" alt="{{ $course->title }}">
              @if ($course->status == 'active')
              <span class="badge bg-success">OPEN</span>
              @elseif ($course->status == 'draft')
              <span class="badge bg-secondary">DRAFT</span>
              @else
              <span class="badge bg-secondary">CLOSED</span>
              @endif
            </div>
            <div class="card-content">
              <div class="avatar-overlap">
                <img src="{{ asset($course->instructor->image == 'no-img.jpg' ? '/default-images/user/both.jpg' : $course->instructor->image) }}" alt="{{ $course->instructor->name }}" title="{{ $course->instructor->name }}">
              </div>
              <h6 class="course-title mb-0">{{ Str::limit($course->title, 48) }}</h6>
              <div class="stats-row">
                <div class="text-start">
                  <div class="stat-lbl">Added</div>
                  <p class="stat-val">{{ $course->created_at->diffForHumans(null, true) }} ago</p>
                </div>
                <div class="text-end">
                  <div class="stat-lbl">Price</div>
                  <p class="stat-val">${{ number_format($course->price, 2) }}</p>
                </div>
              </div>
              <div class="schedule-row">
                <div class="sch-icon">
                  <i class="ti ti-user fs-4 text-muted"></i>
                </div>
                <div>
                  <p class="sch-days mb-0">{{ $course->instructor->name }}</p>
                  <span class="sch-time">Instructor</span>
                </div>
              </div>
            </div>
          </div>
        </a>
      </div>
      @empty
      <div class="col-12">
        <div class="empty-state">
          <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
            <path d="M4 19.5a2.5 2.5 0 0 1 2.5-2.5H20" />
            <path d="M6.5 17a2.5 2.5 0 0 0 0 5" />
            <path d="M8 7h6" />
            <path d="M8 11h8" />
            <path d="M4 5a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v14" />
          </svg>
          <p>No recent courses found.</p>
        </div>
      </div>
      @endforelse
    </div>
  </div>
</div>
{{-- ══════════════════════════════════════
     POPULAR TEACHERS — grid of teacher cards
══════════════════════════════════════ --}}
<div class="card border-0 shadow-sm" style="border-radius:18px;">
  <div class="card-body p-4">
    <div class="section-header">
      <div>
        <h5>Popular Teachers</h5>
        <p>Instructors with the most courses</p>
      </div>
      <a href="{{ route('staff.teacher.index') }}" class="text-primary">
        View All <i class="ti ti-arrow-right"></i>
      </a>
    </div>
    <div class="row g-3">
      @forelse ($popular_teachers as $teacher)
      <div class="col-6 col-md-4 col-xl-3">
        <div class="teacher-grid-card">
          <img class="avatar" src="{{ asset($teacher->image == 'no-img.jpg' ? '/default-images/user/both.jpg' : $teacher->image) }}" alt="{{ $teacher->name }}">
          <div class="tc-name">{{ $teacher->name }}</div>
          <div class="tc-sub">{{ $teacher->email }}</div>
          <span class="tc-badge">
          {{ $teacher->courses_count }} {{ Str::plural('course', $teacher->courses_count) }}
          </span>
        </div>
      </div>
      @empty
      <div class="col-12">
        <div class="empty-state">
          <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
            <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
            <path d="M6 21v-2a4 4 0 0 1 4-4h4a4 4 0 0 1 4 4v2" />
          </svg>
          <p>No teachers found.</p>
        </div>
      </div>
      @endforelse
    </div>
  </div>
</div>
@endsection
@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function () {
    var statusEl = document.querySelector('#staffCourseStatusChart');
    if (statusEl && window.ApexCharts) {
      new ApexCharts(statusEl, {
        chart: { type: 'donut', height: 220, fontFamily: 'inherit' },
        series: [
          {{ $active_courses_count ?? 0 }},
          {{ $draft_courses_count ?? 0 }},
          {{ $inactive_courses_count ?? 0 }},
        ],
        labels: ['Active', 'Draft', 'Inactive'],
        colors: ['#16a34a', '#7c3aed', '#94a3b8'],
        legend: { show: false },
        dataLabels: { enabled: false },
        plotOptions: {
          pie: {
            donut: {
              size: '72%',
              labels: {
                show: true,
                total: {
                  show: true,
                  label: 'Total',
                  fontSize: '.8rem',
                  color: '#8a97a8',
                },
              },
            },
          },
        },
      }).render();
    }
  });
</script>
@endpush
