@extends('frontend.staff.layout.master')
@section('page_title', isset($page_title) ? $page_title : 'Page Title Here')
@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@include('frontend.staff.pages.course-management.course-detail.style.style')
@include('frontend.staff.pages.course-management.course-detail.style.pills-student-attendance')
<style>
  /* ── Teacher's Attendance sheet — clean redesign ── */
  .attendance-toolbar__icon {
    width: 42px;
    height: 42px;
    border-radius: 12px;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(var(--bs-primary-rgb), .1);
    color: var(--bs-primary);
    font-size: 1.2rem;
  }
  .attendance-toolbar {
    padding-bottom: 14px;
    border-bottom: 1px solid #eef0f2;
  }
  .attendance-summary__chip {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 14px;
    border-radius: 10px;
    background: #f8f9fb;
    border: 1px solid #eef0f2;
    font-size: .8125rem;
    color: #666;
  }
  .attendance-summary__chip i {
    color: var(--bs-primary);
    font-size: 1rem;
  }
  .attendance-summary__chip strong {
    color: #111;
    margin-left: 2px;
  }
  .attendance-summary__chip--warn i,
  .attendance-summary__chip--warn strong {
    color: #d97706;
  }
  /* ── Filter bar / date range (flatpickr) ── */
  .filter-bar {
    background: #f8f9fb !important;
    border: 1px solid #eef0f2 !important;
  }
  .fp-field {
    position: relative;
  }
  .fp-field i {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--bs-primary);
    font-size: 1rem;
    pointer-events: none;
    z-index: 2;
  }
  .fp-field input {
    padding-left: 34px !important;
    background: #fff;
    cursor: pointer;
  }
  .fp-field input::placeholder {
    color: #9aa1ac;
  }
  /* ── Table shell ── */
  .attendance-scroll {
    max-height: 60vh;
    overflow: auto;
    border: 1px solid #eef0f2;
    border-radius: 12px;
  }
  .attendance-sheet-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    margin: 0;
    min-width: 1020px;
  }
  .attendance-sheet-table thead th {
    position: sticky;
    top: 0;
    z-index: 2;
    background: #f8f9fb;
    color: #4b5563;
    font-weight: 600;
    font-size: .72rem;
    text-transform: uppercase;
    letter-spacing: .04em;
    padding: 12px 8px;
    text-align: center;
    white-space: nowrap;
    border-bottom: 1px solid #eef0f2;
  }
  .attendance-sheet-table thead th i {
    color: var(--bs-primary);
    margin-right: 4px;
    font-size: .85rem;
  }
  .attendance-sheet-table tbody td {
    padding: 7px 6px;
    border-bottom: 1px solid #f2f3f5;
    vertical-align: middle;
  }
  .attendance-sheet-table tbody tr {
    transition: background .12s ease;
  }
  .attendance-sheet-table tbody tr:hover {
    background: #fafbff;
  }
  .attendance-sheet-table tr.row-late {
    background: rgba(217, 119, 6, .06);
  }
  .attendance-sheet-table tr.row-late td:first-child {
    box-shadow: inset 3px 0 0 #d97706;
  }
  .attendance-sheet-table tr.table-active {
    background: rgba(var(--bs-primary-rgb), .05);
  }
  .attendance-sheet-table tr.table-active td:first-child {
    box-shadow: inset 3px 0 0 var(--bs-primary);
    font-weight: 600;
  }
  /* ── Compact input skin shared by all cells ── */
  .attendance-sheet-table .cell-field {
    position: relative;
    display: flex;
    align-items: center;
  }
  .attendance-sheet-table .cell-field i {
    position: absolute;
    left: 9px;
    color: #9aa1ac;
    font-size: .95rem;
    pointer-events: none;
    z-index: 2;
  }
  .attendance-sheet-table .cell-field input,
  .attendance-sheet-table .cell-field textarea {
    width: 100%;
    padding: 7px 8px 7px 30px;
    font-size: .8125rem;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    background: #fff;
    transition: border-color .15s ease, box-shadow .15s ease, background .15s ease;
  }
  .attendance-sheet-table .cell-field input:not(:disabled):not([readonly]):hover,
  .attendance-sheet-table .cell-field textarea:not(:disabled):not([readonly]):hover {
    border-color: #cfd4db;
  }
  .attendance-sheet-table .cell-field input:not(:disabled):not([readonly]):focus,
  .attendance-sheet-table .cell-field textarea:not(:disabled):not([readonly]):focus {
    outline: none;
    border-color: var(--bs-primary);
    box-shadow: 0 0 0 3px rgba(var(--bs-primary-rgb), .12);
    background: #fff;
  }
  .attendance-sheet-table .cell-field input:disabled,
  .attendance-sheet-table .cell-field input[readonly] {
    background: #f3f4f6;
    color: #6b7280;
    cursor: not-allowed;
  }
  .attendance-sheet-table .cell-field.is-time input {
    text-align: center;
    padding-left: 30px;
  }
  .attendance-sheet-table .cell-field.is-date input {
    text-align: left;
    padding-left: 30px;
  }
  .attendance-sheet-table .cell-field.is-time input:not(:disabled),
  .attendance-sheet-table .cell-field.is-date input:not(:disabled) {
    cursor: pointer;
  }
  .attendance-sheet-table .cell-field.center input {
    text-align: center;
  }
  /* Date lock / unlock control */
  .date-lock-wrap,
  .new-row-date-wrap {
    position: relative;
    display: flex;
    align-items: center;
    gap: 6px;
  }
  .date-lock-wrap .cell-field,
  .new-row-date-wrap .cell-field {
    flex: 1 1 auto;
  }
  .btn-date-lock {
    all: unset;
    box-sizing: border-box;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 30px;
    height: 30px;
    border-radius: 8px;
    background: #f3f4f6;
    color: #9aa1ac;
    cursor: pointer;
    transition: background .12s ease, color .12s ease;
  }
  .btn-date-lock:hover {
    background: rgba(var(--bs-primary-rgb), .1);
    color: var(--bs-primary);
  }
  .btn-date-lock.is-unlocked {
    background: rgba(16, 185, 129, .12);
    color: #059669;
  }
  .btn-duplicate-row {
    all: unset;
    box-sizing: border-box;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 30px;
    height: 30px;
    border-radius: 8px;
    background: #f3f4f6;
    color: #9aa1ac;
    cursor: pointer;
    transition: background .12s ease, color .12s ease;
  }
  .btn-duplicate-row:hover {
    background: rgba(var(--bs-primary-rgb), .1);
    color: var(--bs-primary);
  }
  @keyframes rowFlash {
    0% {
      background-color: rgba(var(--bs-primary-rgb), .16);
    }
    100% {
      background-color: transparent;
    }
  }
  .row-flash td {
    animation: rowFlash 1s ease;
  }
  /* Note column — roomy, multi-line */
  .attendance-sheet-table th:nth-last-child(2),
  .attendance-sheet-table td:nth-last-child(2) {
    min-width: 220px;
  }
  .attendance-sheet-table .cell-field.is-note {
    align-items: flex-start;
  }
  .attendance-sheet-table .cell-field.is-note i {
    top: 11px;
    transform: none;
  }
  .attendance-sheet-table .cell-field.is-note textarea {
    min-height: 56px;
    max-height: 160px;
    line-height: 1.4;
    padding-top: 8px;
    resize: vertical;
    font-family: inherit;
  }
  /* Late minutes stepper — reset to be immune to theme button/input styles */
  .late-stepper {
    display: flex;
    align-items: stretch;
    width: 100%;
    min-width: 108px;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    overflow: hidden;
    background: #fff;
  }
  .late-stepper button {
    all: unset;
    box-sizing: border-box;
    flex: 0 0 28px;
    display: flex;
    align-items: center;
    justify-content: center;
    height: 34px;
    background: #f8f9fb;
    color: #6b7280;
    font-size: 1rem;
    line-height: 1;
    cursor: pointer;
    user-select: none;
    transition: background .12s ease, color .12s ease;
  }
  .late-stepper button:first-child {
    box-shadow: inset -1px 0 0 #e5e7eb;
  }
  .late-stepper button:last-child {
    box-shadow: inset 1px 0 0 #e5e7eb;
  }
  .late-stepper button:hover {
    background: rgba(var(--bs-primary-rgb), .1);
    color: var(--bs-primary);
  }
  .late-stepper input.late-minutes-input {
    all: unset;
    box-sizing: border-box;
    flex: 1 1 auto;
    width: 100%;
    min-width: 0;
    text-align: center;
    font-size: .8125rem;
    font-weight: 600;
    color: #111827;
    padding: 6px 2px;
    background: #fff;
    -moz-appearance: textfield;
  }
  .late-stepper input.late-minutes-input::-webkit-outer-spin-button,
  .late-stepper input.late-minutes-input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
  }
  .late-stepper:focus-within {
    border-color: var(--bs-primary);
    box-shadow: 0 0 0 3px rgba(var(--bs-primary-rgb), .12);
  }
  .late-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    margin-top: 6px;
    padding: 2px 8px;
    border-radius: 20px;
    font-size: .66rem;
    font-weight: 600;
    white-space: nowrap;
  }
  .late-badge.ok {
    background: rgba(16, 185, 129, .12);
    color: #059669;
  }
  .late-badge.warn {
    background: rgba(217, 119, 6, .12);
    color: #d97706;
  }
  .hours-pill {
    width: 100%;
    text-align: center;
    font-weight: 600;
    background: #f8f9fb !important;
  }
  /* ── Full screen mode ── */
  #attendanceCard.is-fullscreen {
    position: fixed;
    inset: 0;
    z-index: 2000;
    border-radius: 0;
    margin: 0;
    overflow-y: auto;
    background: #fff;
    box-shadow: none;
    padding: 20px;
  }
  #attendanceCard.is-fullscreen .attendance-scroll {
    max-height: none;
    overflow: visible;
  }
  body.attendance-fullscreen-open {
    overflow: hidden;
  }
  @media print {
    body * {
      visibility: hidden;
    }
    #attendanceCard,
    #attendanceCard * {
      visibility: visible;
    }
    #attendanceCard {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      box-shadow: none;
    }
    .attendance-toolbar,
    .attendance-summary,
    .filter-bar,
    .attendance-actions {
      display: none !important;
    }
  }
  /* ── Flatpickr theming to match the sheet ── */
  .flatpickr-calendar {
    box-shadow: 0 10px 30px rgba(0, 0, 0, .12);
    border-radius: 12px;
    overflow: hidden;
  }
  .flatpickr-time {
    border-top: none !important;
  }
  .flatpickr-time input,
  .flatpickr-time .flatpickr-am-pm {
    font-weight: 600;
    color: #111827;
  }
  .flatpickr-day.selected,
  .flatpickr-day.startRange,
  .flatpickr-day.endRange {
    background: var(--bs-primary);
    border-color: var(--bs-primary);
  }
  .flatpickr-day.inRange {
    background: rgba(var(--bs-primary-rgb), .12);
    box-shadow: -5px 0 0 rgba(var(--bs-primary-rgb), .12), 5px 0 0 rgba(var(--bs-primary-rgb), .12);
    border-color: transparent;
  }
  /* ── Small screens: cards instead of a squeezed table ── */
  @media (max-width: 900px) {
    .attendance-scroll {
      overflow: visible;
      border: none;
      max-height: none;
    }
    .attendance-sheet-table {
      min-width: 0;
      width: 100%;
      border-spacing: 0;
    }
    .attendance-sheet-table thead {
      display: none;
    }
    .attendance-sheet-table tbody {
      display: block;
    }
    .attendance-sheet-table tr {
      display: flex;
      flex-wrap: wrap;
      background: #fff;
      border: 1px solid #eef0f2;
      border-radius: 14px;
      padding: 14px 16px 4px;
      margin-bottom: 12px;
      box-shadow: 0 1px 2px rgba(16, 24, 40, .04);
    }
    .attendance-sheet-table tr.table-active {
      border-color: rgba(var(--bs-primary-rgb), .35);
      background: #f8f9ff;
    }
    .attendance-sheet-table tr.row-late {
      border-color: rgba(217, 119, 6, .3);
    }
    .attendance-sheet-table tr.row-late td:first-child,
    .attendance-sheet-table tr.table-active td:first-child {
      box-shadow: none;
    }
    .attendance-sheet-table td {
      display: block;
      border: none !important;
      padding: 0;
    }
    .attendance-sheet-table .cell-id {
      display: none !important;
    }
    .attendance-sheet-table .cell-no {
      order: 1;
      flex: 1 1 auto;
      font-weight: 700;
      font-size: .95rem;
      display: flex;
      align-items: center;
      padding-bottom: 10px !important;
    }
    .attendance-sheet-table .cell-actions {
      order: 2;
      flex: 0 0 auto;
      margin-left: auto;
      padding-bottom: 10px !important;
      display: flex;
      align-items: flex-start;
    }
    .attendance-sheet-table .cell-date {
      order: 3;
    }
    .attendance-sheet-table .cell-timein {
      order: 4;
    }
    .attendance-sheet-table .cell-timeout {
      order: 5;
    }
    .attendance-sheet-table .cell-th {
      order: 6;
      flex: 1 1 calc(50% - 8px);
      margin-right: 16px;
    }
    .attendance-sheet-table .cell-ath {
      order: 7;
      flex: 1 1 calc(50% - 8px);
    }
    .attendance-sheet-table .cell-late {
      order: 8;
    }
    .attendance-sheet-table .cell-note {
      order: 9;
    }
    .attendance-sheet-table td[data-label] {
      flex-basis: 100%;
      padding: 10px 0 !important;
      border-top: 1px dashed #eef0f2 !important;
    }
    .attendance-sheet-table td[data-label]::before {
      content: attr(data-label);
      display: block;
      font-size: .68rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: .04em;
      color: #9aa1ac;
      margin-bottom: 6px;
    }
    .btn-duplicate-row {
      width: auto;
      padding: 0 10px;
      gap: 5px;
    }
    .btn-duplicate-row__label {
      display: inline;
      font-size: .72rem;
      font-weight: 600;
    }
  }
  @media (min-width: 901px) {
    .btn-duplicate-row__label {
      display: none;
    }
  }
  /* ══════════════════════════════════════════════════════════════
     Student's Attendance — redesign
     ══════════════════════════════════════════════════════════════ */
  #studentAttendanceCard.is-fullscreen {
    position: fixed;
    inset: 0;
    z-index: 2000;
    border-radius: 0;
    margin: 0;
    overflow-y: auto;
    background: #fff;
    box-shadow: none;
    padding: 20px;
  }
  #studentAttendanceCard.is-fullscreen .attendance-scroll {
    max-height: none;
    overflow: visible;
  }
  /* Legend */
  .attendance-legend__item {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: .78rem;
    color: #6b7280;
  }
  .status-dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    display: inline-block;
  }
  .status-dot.status-present {
    background: #16a34a;
  }
  .status-dot.status-absent {
    background: #dc2626;
  }
  .status-dot.status-late {
    background: #d97706;
  }
  /* Status pills inside the table */
  .student-attendance-table .status-cell {
    text-align: center;
  }
  .status-pill {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 26px;
    height: 26px;
    border-radius: 50%;
    font-size: .72rem;
    font-weight: 700;
  }
  .status-pill.status-present {
    background: rgba(22, 163, 74, .12);
    color: #16a34a;
  }
  .status-pill.status-absent {
    background: rgba(220, 38, 38, .12);
    color: #dc2626;
  }
  .status-pill.status-late {
    background: rgba(217, 119, 6, .12);
    color: #d97706;
  }
  /* Per-student attendance-rate badge */
  .rate-badge {
    display: inline-block;
    padding: 3px 10px;
    border-radius: 999px;
    font-size: .72rem;
    font-weight: 700;
  }
  .rate-badge.rate-good {
    background: rgba(22, 163, 74, .12);
    color: #16a34a;
  }
  .rate-badge.rate-mid {
    background: rgba(217, 119, 6, .12);
    color: #d97706;
  }
  .rate-badge.rate-low {
    background: rgba(220, 38, 38, .12);
    color: #dc2626;
  }
  /* Student avatar initial */
  .student-avatar-dot {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    background: rgba(var(--bs-primary-rgb), .12);
    color: var(--bs-primary);
    font-size: .7rem;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    text-transform: uppercase;
    flex-shrink: 0;
  }
  /* Sticky first two columns so student names stay visible while
     scrolling through many date columns */
  .student-attendance-table th.sticky-col,
  .student-attendance-table td.sticky-col,
  .student-report-table th.sticky-col,
  .student-report-table td.sticky-col {
    position: sticky;
    z-index: 1;
    background: #fff;
  }
  .student-attendance-table thead th.sticky-col,
  .student-report-table thead th.sticky-col {
    z-index: 3;
    background: #f8f9fb;
  }
  .student-attendance-table .sticky-col--no,
  .student-report-table .sticky-col--no {
    left: 0;
    width: 44px;
    text-align: center;
  }
  .student-attendance-table .sticky-col--name,
  .student-report-table .sticky-col--name {
    left: 44px;
    min-width: 170px;
    box-shadow: 2px 0 4px -2px rgba(0, 0, 0, .08);
  }
  .student-attendance-table tbody tr:hover td.sticky-col,
  .student-report-table tbody tr:hover td.sticky-col {
    background: #fafbff;
  }
  @media (max-width: 900px) {
    .student-attendance-table .sticky-col,
    .student-report-table .sticky-col {
      position: static;
      box-shadow: none;
    }
  }
  /* Report table: text alignment differs from attendance sheet — center
     everything except the sticky name/no columns */
  .student-report-table th:not(.sticky-col),
  .student-report-table td:not(.sticky-col) {
    text-align: center;
  }
  #studentReportCard.is-fullscreen {
    position: fixed;
    inset: 0;
    z-index: 2000;
    border-radius: 0;
    margin: 0;
    overflow-y: auto;
    background: #fff;
    box-shadow: none;
    padding: 20px;
  }
  #studentReportCard.is-fullscreen .attendance-scroll {
    max-height: none;
    overflow: visible;
  }
</style>
@endpush
@section('content')
{{-- Breadcrumb --}}
@include('frontend.staff.pages.course-management.course-detail.partials.breadcrumb')
<div class="row">
  {{-- ════════════════════════════
             LEFT COLUMN — Info + Stats
        ════════════════════════════ --}}
  <div class="col-lg-4 col-xl-3">
    {{-- Hero --}}
    <div class="course-hero">
      <img class="course-hero__img" src="{{ asset($course->thumbnail == '' ? asset('/default-images/staff/no-course-img.png') : asset($course->thumbnail)) }}" alt="{{ $course->title }}">
      <div class="course-hero__overlay"></div>
      <div class="course-hero__body">
        <div class="course-hero__top">
          <span class="status-pill {{ $course->status == 'active' ? 'open' : 'closed' }}">
          {{ strtoupper($course->status == 'active' ? 'Open' : 'Closed') }}
          </span>
          <a href="{{ route('staff.courses.edit', $course->id) }}" class="btn btn-sm btn-light d-flex align-items-center gap-1" style="font-size:.78rem;">
            <i class="ti ti-edit fs-5"></i> Edit
          </a>
        </div>
        <div class="course-hero__bottom">
          <div>
            <h2 class="course-hero__title">{{ $course->title }}</h2>
            @if ($course->schedule)
            @php
            $days = collect(explode('-', $course->schedule->study_day))
            ->map(fn($d) => ucfirst($d))
            ->implode(' • ');
            $start = \Carbon\Carbon::parse($course->schedule->start_time)->format('g:i');
            $end = \Carbon\Carbon::parse($course->schedule->end_time)->format('g:i A');
            $shift = ucfirst($course->schedule->shift);
            @endphp
            <p class="course-hero__sub">{{ $days }} &nbsp;·&nbsp; {{ $shift }}
              ({{ $start }}–{{ $end }})</p>
            @endif
          </div>
        </div>
      </div>
    </div>
    {{-- Instructor chip --}}
    <div class="instructor-chip">
      <img src="{{ asset($course->instructor->image == 'no-img.jpg' ? 'default-images/user/both.jpg' : $course->instructor->image) }}" alt="{{ $course->instructor->name }}">
      <div>
        <div class="instructor-chip__name text-capitalize">{{ $course->instructor->name }}</div>
        <div class="instructor-chip__role">Instructor</div>
      </div>
    </div>
    {{-- ── REMAINING highlight ── --}}
    @php
    $sessionDuration = 1.5;
    $totalDuration = $course->duration ?? 0;
    $completedHours = $course->teacherAttendances->sum('total_hours');
    $remainingHours = max(0, round($totalDuration - $completedHours, 1));
    $totalSessions = $totalDuration > 0 ? round($totalDuration / $sessionDuration) : 0;
    $completedSessions = $course->completed_sessions ?? 0;
    $remainingSessions = max(0, $totalSessions - $completedSessions);
    $progress = $course->progress ?? 0;
    @endphp
    <div class="remain-grid">
      <div class="remain-card hours">
        <div class="remain-card__label">HRS Left</div>
        <div class="remain-card__value">{{ $remainingHours }}<span
                            style="font-size:.9rem;font-weight:500;">h</span></div>
        <div class="remain-card__sub">of {{ $totalDuration }}h total</div>
      </div>
      <div class="remain-card sessions">
        <div class="remain-card__label">Sess Left</div>
        <div class="remain-card__value">{{ $remainingSessions }}</div>
        <div class="remain-card__sub">of {{ $totalSessions }} total</div>
      </div>
    </div>
    {{-- ── Stat Cards ── --}}
    <div class="stat-grid" style="grid-template-columns: 1fr 1fr;">
      {{-- Students --}}
      <div class="stat-card">
        <div class="stat-card__icon" style="background:#ede9fe;">
          <i class="ti ti-users" style="color:#7c3aed;"></i>
        </div>
        <div class="stat-card__label">Students</div>
        <div class="stat-card__value">{{ $course->students->count() }}</div>
      </div>
      {{-- Earnings --}}
      <div class="stat-card">
        <div class="stat-card__icon" style="background:#dcfce7;">
          <i class="ti ti-currency-dollar" style="color:#16a34a;"></i>
        </div>
        <div class="stat-card__label">Earned</div>
        <div class="stat-card__value" style="font-size:1.1rem;">
          @if (request('from_date') && request('to_date'))
          ${{ $course->filtered_earnings ?? 0 }}
          @else
          ${{ $course->earnings ?? 0 }}
          @endif
        </div>
      </div>
      {{-- Completed Sessions --}}
      <div class="stat-card">
        <div class="stat-card__icon" style="background:#fef9c3;">
          <i class="ti ti-calendar-check" style="color:#ca8a04;"></i>
        </div>
        <div class="stat-card__label">Done</div>
        <div class="stat-card__value">{{ $completedSessions }}<span
                            style="font-size:.8rem;color:#94a3b8;font-weight:500;"> sess</span></div>
      </div>
      {{-- Start Date --}}
      <div class="stat-card">
        <div class="stat-card__icon" style="background:#e0f2fe;">
          <i class="ti ti-calendar" style="color:#0284c7;"></i>
        </div>
        <div class="stat-card__label">Start</div>
        <div class="stat-card__value" style="font-size:.9rem;">
          {{ $course->start_date ? $course->start_date->format('d M Y') : 'N/A' }}
        </div>
      </div>
    </div>
    {{-- Progress --}}
    <div class="card border-0 shadow-none" style="background:#f8fafc;border:1px solid #e9ecef!important;border-radius:14px;">
      <div class="card-body py-3 px-4">
        <div class="d-flex justify-content-between align-items-center mb-2">
          <span class="fw-semibold fs-3" style="color:#1e293b;">Course Progress</span>
          <span class="fw-bold fs-3" style="color:#6366f1;">{{ $progress }}%</span>
        </div>
        <div class="stat-progress">
          @php
          $pColor = $progress <= 50 ? '#ef4444' : ($progress <=80 ? '#f59e0b' : '#22c55e' ); @endphp <div class="stat-progress__bar" style="width:{{ $progress }}%;background:{{ $pColor }};">
        </div>
      </div>
      <div class="d-flex justify-content-between mt-2">
        <small class="text-muted">{{ $completedSessions }} sessions done</small>
        <small class="text-muted">{{ $remainingSessions }} remaining</small>
      </div>
    </div>
  </div>
  {{-- Category / Room quick info --}}
  <div class="info-strip mt-3">
    <div class="info-strip__item">
      <i class="ti ti-tag"></i>
      <span><strong>{{ $course->category->name ?? 'Uncategorized' }}</strong></span>
    </div>
    <div class="info-strip__item">
      <i class="ti ti-door"></i>
      <span>Room <strong>{{ $course->room ?? 'N/A' }}</strong></span>
    </div>
    @if ($course->end_date)
    <div class="info-strip__item">
      <i class="ti ti-calendar-x"></i>
      <span>Ends <strong>{{ $course->end_date->format('d M Y') }}</strong></span>
    </div>
    @endif
  </div>
</div>{{-- /col left --}}
{{-- ════════════════════════════
             RIGHT COLUMN — Tabs
        ════════════════════════════ --}}
<div class="col-lg-8 col-xl-9">
  {{-- Tabs nav --}}
  @include('frontend.staff.pages.course-management.course-detail.partials.tab')
  {{-- Tab content --}}
  <div class="tab-content mt-3" id="pills-tabContent">
    {{-- ── Students ── --}}
    @include('frontend.staff.pages.course-management.course-detail.partials.tab-contents.pills-students')
    {{-- ── Teacher Attendance ── --}}
    <div class="tab-pane fade" id="pills-attendance" role="tabpanel" tabindex="0">
      <div class="card border-0" style="border-radius:14px;box-shadow:0 2px 12px rgba(0,0,0,.06);">
        <div class="card-body">
          <div class="sheet" id="attendanceCard">
            {{-- Toolbar --}}
            <div class="attendance-toolbar d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
              <div class="d-flex align-items-center gap-3 flex-wrap">
                <div class="attendance-toolbar__icon">
                  <i class="ti ti-calendar-check"></i>
                </div>
                <div>
                  <div class="title mb-0">{{ $course->title }}</div>
                  <div class="sub-title">Teacher's Attendance</div>
                </div>
                <span
                                            class="badge bg-light-primary text-primary fw-semibold rounded-pill px-3 py-2">
                {{ $course->teacherAttendances->count() }} sessions
                </span>
              </div>
              <div class="d-flex align-items-center gap-2">
                <button type="button" class="btn btn-sm btn-outline-primary" id="toggleFullscreenBtn" title="Toggle full screen">
                  <i class="ti ti-maximize"></i>
                  <span class="d-none d-lg-inline ms-1">Full Screen</span>
                </button>
              </div>
            </div>
            {{-- Meta bar — who/what on the left, live totals on the right, one row instead of two --}}
            <div class="attendance-meta d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
              <div class="info-strip mb-0">
                <div class="info-strip__item">
                  <i class="ti ti-user"></i>
                  <span><strong
                                                    class="text-capitalize">{{ $course->instructor->name ?? 'N/A' }}</strong></span>
                </div>
                @if ($course->schedule)
                <div class="info-strip__item">
                  <i class="ti ti-clock"></i>
                  <span>{{ $shift }} ({{ $start }}–{{ $end }})</span>
                </div>
                @endif
              </div>
              <div class="attendance-summary d-flex flex-wrap gap-2">
                <div class="attendance-summary__chip">
                  <i class="ti ti-clock-hour-4"></i>
                  <span>Total Hours: <strong id="sumTotalHours">0</strong></span>
                </div>
                <div class="attendance-summary__chip">
                  <i class="ti ti-hourglass-high"></i>
                  <span>Actual Hours: <strong id="sumActualHours">0</strong></span>
                </div>
                <div class="attendance-summary__chip attendance-summary__chip--warn">
                  <i class="ti ti-clock-exclamation"></i>
                  <span>Late Minutes: <strong id="sumLateMinutes">0</strong></span>
                </div>
              </div>
            </div>
            {{-- Legend --}}
            <div class="d-flex flex-wrap align-items-center gap-3 mb-3">
              <span class="attendance-legend__item"><span class="status-dot status-late"></span> Highlighted row = late arrival</span>
            </div>
            {{-- Filter --}}
            <form method="GET" class="mb-4">
              <div class="filter-bar p-3 rounded-3 bg-light border">
                <div class="row g-2 align-items-end">
                  <div class="col-12 col-md-7">
                    <label class="form-label fw-semibold mb-1">
                      <i class="ti ti-calendar me-1"></i> Date Range
                    </label>
                    <div class="fp-field">
                      <i class="ti ti-calendar-event"></i>
                      <input type="text" class="form-control" id="date-range-picker" placeholder="Select a date range" autocomplete="off" readonly>
                    </div>
                    <input type="hidden" name="from_date" id="from_date" value="{{ request('from_date', now()->startOfMonth()->format('Y-m-d')) }}">
                    <input type="hidden" name="to_date" id="to_date" value="{{ request('to_date', now()->endOfMonth()->format('Y-m-d')) }}">
                  </div>
                  <div class="col-12 col-md-5">
                    <div class="d-flex gap-2">
                      <button class="btn btn-primary w-100">
                        <i class="ti ti-search me-1"></i>Filter
                      </button>
                      <a href="{{ route('staff.courses.show', $course->id) }}" class="btn btn-outline-secondary w-100">Reset</a>
                    </div>
                  </div>
                </div>
              </div>
            </form>
            {{-- Attendance Table --}}
            <form action="{{ route('staff.teacher.attendance.update') }}" method="POST" id="attendanceForm">
              @csrf
              <input type="hidden" name="course_id" value="{{ $course->id }}">
              <input type="hidden" name="teacher_id" value="{{ $course->instructor->id ?? '' }}">
              <input type="hidden" name="schedule_id" value="{{ $course->schedule->id ?? '' }}">
              <div class="attendance-scroll">
                <table id="attendanceTable" class="attendance-sheet-table">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th><i class="ti ti-calendar"></i>Date</th>
                      <th><i class="ti ti-login-2"></i>Time In</th>
                      <th><i class="ti ti-logout"></i>Time Out</th>
                      <th><i class="ti ti-clock-hour-4"></i>T H</th>
                      <th><i class="ti ti-hourglass"></i>A T H</th>
                      <th><i class="ti ti-alarm"></i>នាទីខ្វះ</th>
                      <th><i class="ti ti-note"></i>Note</th>
                      <th class="text-center" style="width:48px;"></th>
                    </tr>
                  </thead>
                  <tbody id="attendanceBody">
                    @php $attendances = $course->teacherAttendances; @endphp
                    @foreach ($attendances as $index => $attendance)
                    <tr class="{{ $attendance->late_minutes > 0 ? 'row-late' : '' }}">
                      <td class="cell-no" style="padding:10px">{{ $index + 1 }}</td>
                      {{-- Hidden ID --}}
                      <td class="cell-id" style="display:none;">
                        <input type="hidden" name="attendances[{{ $index }}][id]" value="{{ $attendance->id }}">
                      </td>
                      {{-- DATE — locked by default, unlock button alongside --}}
                      <td class="cell-date" data-label="Date">
                        <div class="date-lock-wrap">
                          <div class="cell-field is-date">
                            <i class="ti ti-calendar"></i>
                            <input type="text" name="attendances[{{ $index }}][date]" value="{{ $attendance->date }}" class="text-dark date-input js-date-picker" autocomplete="off" disabled>
                          </div>
                          <button type="button" class="btn-date-lock" title="Click to unlock date for editing" aria-label="Toggle date lock">
                            <i class="ti ti-lock"></i>
                          </button>
                        </div>
                      </td>
                      <td class="cell-timein" data-label="Time In">
                        <div class="cell-field is-time">
                          <i class="ti ti-clock"></i>
                          <input type="text" class="js-time-picker" name="attendances[{{ $index }}][start_time]" value="{{ \Carbon\Carbon::parse($attendance->start_time)->format('H:i') }}" autocomplete="off">
                        </div>
                      </td>
                      <td class="cell-timeout" data-label="Time Out">
                        <div class="cell-field is-time">
                          <i class="ti ti-clock"></i>
                          <input type="text" class="js-time-picker" name="attendances[{{ $index }}][end_time]" value="{{ \Carbon\Carbon::parse($attendance->end_time)->format('H:i') }}" autocomplete="off">
                        </div>
                      </td>
                      <td class="cell-th" data-label="Total Hours">
                        <input type="text" name="attendances[{{ $index }}][total_hours]" value="{{ number_format($attendance->total_hours) }}" class="form-control form-control-sm total-hours hours-pill" readonly>
                      </td>
                      <td class="cell-ath" data-label="Actual Hours">
                        <input type="text" name="attendances[{{ $index }}][actual_hours]" value="{{ number_format($attendance->actual_hours) }}" class="form-control form-control-sm actual-hours hours-pill" readonly>
                      </td>
                      <td class="cell-late" data-label="Late Minutes">
                        <div class="late-stepper">
                          <button type="button" class="js-late-minus" tabindex="-1">−</button>
                          <input type="number" min="0" name="attendances[{{ $index }}][late_minutes]" value="{{ $attendance->late_minutes }}" class="late-minutes-input">
                          <button type="button" class="js-late-plus" tabindex="-1">+</button>
                        </div>
                        <span
                                                                class="late-badge {{ $attendance->late_minutes > 0 ? 'warn' : 'ok' }} js-late-badge">
                                                                <i
                                                                    class="ti {{ $attendance->late_minutes > 0 ? 'ti-alert-triangle' : 'ti-check' }}"></i>
                                                                {{ $attendance->late_minutes > 0 ? 'Late' : 'On time' }}
                                                            </span>
                      </td>
                      <td class="cell-note" data-label="Note">
                        <div class="cell-field is-note">
                          <i class="ti ti-note"></i>
                          <textarea rows="2" name="attendances[{{ $index }}][late_reason]" placeholder="Optional note">{{ $attendance->late_reason }}</textarea>
                        </div>
                      </td>
                      <td class="cell-actions text-center">
                        <button type="button" class="btn-duplicate-row" title="Duplicate this record into the new row below" aria-label="Duplicate this record">
                          <i class="ti ti-copy"></i>
                          <span class="btn-duplicate-row__label">Duplicate</span>
                        </button>
                      </td>
                    </tr>
                    @endforeach
                    {{-- ── NEW ROW — date is freely editable, defaults to today ── --}}
                    @php $nextIndex = $attendances->count(); @endphp
                    <tr class="table-active">
                      <td class="cell-no" style="padding:10px">
                        {{ $nextIndex + 1 }}
                        <span class="badge bg-light-primary text-primary rounded-pill ms-1"
                                                            style="font-size:9px;">NEW</span>
                      </td>
                      <td class="cell-id" style="display:none;">
                        <input type="hidden" name="attendances[{{ $nextIndex }}][id]" value="">
                      </td>
                      {{-- No lock button on the new row — always editable --}}
                      <td class="cell-date" data-label="Date">
                        <div class="new-row-date-wrap">
                          <div class="cell-field is-date">
                            <i class="ti ti-calendar"></i>
                            <input type="text" name="attendances[{{ $nextIndex }}][date]" value="{{ now()->format('Y-m-d') }}" class="js-date-picker" autocomplete="off">
                          </div>
                        </div>
                      </td>
                      <td class="cell-timein" data-label="Time In">
                        <div class="cell-field is-time">
                          <i class="ti ti-clock"></i>
                          <input type="text" class="js-time-picker" name="attendances[{{ $nextIndex }}][start_time]" autocomplete="off">
                        </div>
                      </td>
                      <td class="cell-timeout" data-label="Time Out">
                        <div class="cell-field is-time">
                          <i class="ti ti-clock"></i>
                          <input type="text" class="js-time-picker" name="attendances[{{ $nextIndex }}][end_time]" autocomplete="off">
                        </div>
                      </td>
                      <td class="cell-th" data-label="Total Hours">
                        <input type="text" name="attendances[{{ $nextIndex }}][total_hours]" class="form-control form-control-sm total-hours hours-pill" readonly>
                      </td>
                      <td class="cell-ath" data-label="Actual Hours">
                        <input type="text" name="attendances[{{ $nextIndex }}][actual_hours]" class="form-control form-control-sm actual-hours hours-pill" readonly>
                      </td>
                      <td class="cell-late" data-label="Late Minutes">
                        <div class="late-stepper">
                          <button type="button" class="js-late-minus" tabindex="-1">−</button>
                          <input type="number" min="0" name="attendances[{{ $nextIndex }}][late_minutes]" value="0" class="late-minutes-input">
                          <button type="button" class="js-late-plus" tabindex="-1">+</button>
                        </div>
                        <span class="late-badge ok js-late-badge">
                                                            <i class="ti ti-check"></i> On time
                                                        </span>
                      </td>
                      <td class="cell-note" data-label="Note">
                        <div class="cell-field is-note">
                          <i class="ti ti-note"></i>
                          <textarea rows="2" name="attendances[{{ $nextIndex }}][late_reason]" placeholder="Optional note"></textarea>
                        </div>
                      </td>
                      <td class="cell-actions"></td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <div class="attendance-actions d-flex justify-content-end mt-3">
                <button type="submit" class="btn btn-primary px-4">
                  <i class="ti ti-device-floppy me-1"></i> Save Attendance
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>{{-- /pills-attendance --}}
    {{-- ── Student Attendance ── --}}
    <div class="tab-pane fade" id="pills-student-attendance" role="tabpanel">
      @php
      $data = $attendanceData;
      $dates = array_slice($data['table_structure']['columns'], 5);
      $totalStudents = count($data['table_structure']['data_rows']);
      $totalSessions = count($dates);
      $totalMarked = 0;
      $totalPresent = 0;
      $totalAbsent = 0;
      $totalLate = 0;
      foreach ($data['table_structure']['data_rows'] as $row) {
      foreach ($dates as $date) {
      $status = $row['attendance'][$date] ?? '';
      if ($status !== '') {
      $totalMarked++;
      if ($status === 'P') $totalPresent++;
      elseif ($status === 'A') $totalAbsent++;
      elseif ($status === 'L') $totalLate++;
      }
      }
      }
      $overallRate = $totalMarked > 0 ? round((($totalPresent + $totalLate) / $totalMarked) * 100) : null;
      @endphp
      <div class="card border-0" style="border-radius:14px;box-shadow:0 2px 12px rgba(0,0,0,.06);">
        <div class="card-body">
          <div class="sheet" id="studentAttendanceCard">
            {{-- Toolbar --}}
            <div class="attendance-toolbar d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
              <div class="d-flex align-items-center gap-3 flex-wrap">
                <div class="attendance-toolbar__icon">
                  <i class="ti ti-users"></i>
                </div>
                <div>
                  <div class="title mb-0">{{ $data['form_metadata']['class_title'] }}</div>
                  <div class="sub-title">Student's Attendance</div>
                </div>
                <span class="badge bg-light-primary text-primary fw-semibold rounded-pill px-3 py-2">
                {{ $totalSessions }} {{ Str::plural('session', $totalSessions) }}
                </span>
              </div>
              <div class="d-flex align-items-center gap-2">
                <button type="button" class="btn btn-sm btn-outline-primary" id="toggleStudentFullscreenBtn" title="Toggle full screen">
                  <i class="ti ti-maximize"></i>
                  <span class="d-none d-lg-inline ms-1">Full Screen</span>
                </button>
              </div>
            </div>
            {{-- Meta bar --}}
            <div class="attendance-meta d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
              <div class="info-strip mb-0">
                <div class="info-strip__item">
                  <i class="ti ti-calendar-event"></i>
                  <span>Start: <strong>{{ $data['form_metadata']['class_start'] }}</strong></span>
                </div>
                <div class="info-strip__item">
                  <i class="ti ti-door"></i>
                  <span>Room: <strong>{{ $data['form_metadata']['room'] }}</strong></span>
                </div>
                <div class="info-strip__item">
                  <i class="ti ti-user"></i>
                  <span><strong class="text-capitalize">{{ $data['form_metadata']['lecturer_name'] }}</strong></span>
                </div>
                @if ($data['form_metadata']['lecturer_phone'])
                <div class="info-strip__item">
                  <i class="ti ti-phone"></i>
                  <span>{{ $data['form_metadata']['lecturer_phone'] }}</span>
                </div>
                @endif
              </div>
              <div class="attendance-summary d-flex flex-wrap gap-2">
                <div class="attendance-summary__chip">
                  <i class="ti ti-users"></i>
                  <span>Students: <strong>{{ $totalStudents }}</strong></span>
                </div>
                <div class="attendance-summary__chip">
                  <i class="ti ti-chart-bar"></i>
                  <span>Attendance Rate: <strong>{{ $overallRate !== null ? $overallRate . '%' : '–' }}</strong></span>
                </div>
                @if ($totalAbsent > 0)
                <div class="attendance-summary__chip attendance-summary__chip--warn">
                  <i class="ti ti-user-x"></i>
                  <span>Absences: <strong>{{ $totalAbsent }}</strong></span>
                </div>
                @endif
              </div>
            </div>
            {{-- Legend --}}
            <div class="d-flex flex-wrap align-items-center gap-3 mb-3">
              <span class="attendance-legend__item"><span class="status-dot status-present"></span> Present</span>
              <span class="attendance-legend__item"><span class="status-dot status-absent"></span> Absent</span>
              <span class="attendance-legend__item"><span class="status-dot status-late"></span> Late</span>
            </div>
            {{-- Table --}}
            @if ($totalStudents === 0)
            <div class="text-center text-muted py-5">
              <i class="ti ti-calendar-off fs-1 d-block mb-2"></i>
              No students enrolled yet — attendance will appear here once students are added.
            </div>
            @else
            <div class="attendance-scroll">
              <table id="studentAttendanceTable" class="attendance-sheet-table student-attendance-table">
                <thead>
                  <tr>
                    <th class="sticky-col sticky-col--no">No</th>
                    <th class="sticky-col sticky-col--name text-start">Student</th>
                    <th>Sex</th>
                    <th>Day</th>
                    <th>Shift</th>
                    @foreach ($dates as $date)
                    <th>{{ $date }}</th>
                    @endforeach
                    <th>Rate</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($data['table_structure']['data_rows'] as $row)
                  @php
                  $rowMarked = 0;
                  $rowPresent = 0;
                  foreach ($dates as $date) {
                  $status = $row['attendance'][$date] ?? '';
                  if ($status !== '') {
                  $rowMarked++;
                  if ($status === 'P' || $status === 'L') $rowPresent++;
                  }
                  }
                  $rowRate = $rowMarked > 0 ? round(($rowPresent / $rowMarked) * 100) : null;
                  @endphp
                  <tr>
                    <td class="sticky-col sticky-col--no" data-label="No">{{ $row['no'] }}</td>
                    <td class="sticky-col sticky-col--name text-start fw-semibold" data-label="Student">
                      <div class="d-flex align-items-center gap-2">
                        <span class="student-avatar-dot">{{ Str::substr($row['student_name'], 0, 1) }}</span>
                        {{ $row['student_name'] }}
                      </div>
                    </td>
                    <td class="text-capitalize" data-label="Sex">{{ $row['sex'] }}</td>
                    <td class="text-capitalize" data-label="Day">{{ $row['day'] }}</td>
                    <td data-label="Shift">{{ $row['shift'] }}</td>
                    @foreach ($dates as $date)
                    @php $status = $row['attendance'][$date] ?? ''; @endphp
                    <td class="status-cell" data-label="{{ $date }}">
                      @if ($status)
                      <span class="status-pill {{ $status == 'P' ? 'status-present' : ($status == 'A' ? 'status-absent' : 'status-late') }}" title="{{ $status == 'P' ? 'Present' : ($status == 'A' ? 'Absent' : 'Late') }}">{{ $status }}</span>
                      @else
                      <span class="text-muted">–</span>
                      @endif
                    </td>
                    @endforeach
                    <td data-label="Rate">
                      @if ($rowRate !== null)
                      <span class="rate-badge {{ $rowRate >= 80 ? 'rate-good' : ($rowRate >= 50 ? 'rate-mid' : 'rate-low') }}">{{ $rowRate }}%</span>
                      @else
                      <span class="text-muted">–</span>
                      @endif
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
            @endif
          </div>
        </div>
      </div>
    </div>
    {{-- ── Student Report ── --}}
    <div class="tab-pane fade" id="pills-student-report" role="tabpanel">
      @php
      $totalStudents = $course->studentReports->count();
      $passed = $course->studentReports->where('result', 'pass')->count();
      $failed = $course->studentReports->where('result', 'fail')->count();
      $avgScore = $totalStudents > 0 ? round($course->studentReports->avg('total_score')) : 0;
      $passRate = $totalStudents > 0 ? round(($passed / $totalStudents) * 100) : null;
      $avatarBg = [
      'bg-light-primary',
      'bg-light-warning',
      'bg-light-info',
      'bg-light-danger',
      'bg-light-success',
      ];
      $avatarText = ['text-primary', 'text-warning', 'text-info', 'text-danger', 'text-success'];
      @endphp
      <div class="card border-0" style="border-radius:14px;box-shadow:0 2px 12px rgba(0,0,0,.06);">
        <div class="card-body">
          <div class="sheet" id="studentReportCard">
            {{-- Toolbar --}}
            <div class="attendance-toolbar d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
              <div class="d-flex align-items-center gap-3 flex-wrap">
                <div class="attendance-toolbar__icon">
                  <i class="ti ti-file-certificate"></i>
                </div>
                <div>
                  <div class="title mb-0">{{ $course->title }}</div>
                  <div class="sub-title">Student Report</div>
                </div>
                <span class="badge bg-light-primary text-primary fw-semibold rounded-pill px-3 py-2">
                {{ $totalStudents }} {{ Str::plural('student', $totalStudents) }}
                </span>
              </div>
              <div class="d-flex align-items-center gap-2">
                <button type="button" class="btn btn-sm btn-outline-primary" id="toggleReportFullscreenBtn" title="Toggle full screen">
                  <i class="ti ti-maximize"></i>
                  <span class="d-none d-lg-inline ms-1">Full Screen</span>
                </button>
                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="window.print()" title="Print report">
                  <i class="ti ti-printer"></i>
                  <span class="d-none d-lg-inline ms-1">Print</span>
                </button>
              </div>
            </div>
            {{-- Meta bar --}}
            <div class="attendance-meta d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
              <div class="info-strip mb-0">
                <div class="info-strip__item">
                  <i class="ti ti-user"></i>
                  <span><strong class="text-capitalize">{{ $course->instructor->name ?? 'N/A' }}</strong></span>
                </div>
                <div class="info-strip__item">
                  <i class="ti ti-door"></i>
                  <span>Room: <strong>{{ $course->room ?? 'N/A' }}</strong></span>
                </div>
                @if ($course->schedule)
                <div class="info-strip__item">
                  <i class="ti ti-clock"></i>
                  <span>{{ $shift }} ({{ $start }}–{{ $end }})</span>
                </div>
                @endif
              </div>
              <div class="attendance-summary d-flex flex-wrap gap-2">
                <div class="attendance-summary__chip">
                  <i class="ti ti-check"></i>
                  <span>Passed: <strong>{{ $passed }}</strong></span>
                </div>
                @if ($failed > 0)
                <div class="attendance-summary__chip attendance-summary__chip--warn">
                  <i class="ti ti-x"></i>
                  <span>Failed: <strong>{{ $failed }}</strong></span>
                </div>
                @endif
                <div class="attendance-summary__chip">
                  <i class="ti ti-chart-bar"></i>
                  <span>Avg Score: <strong>{{ $avgScore }}</strong></span>
                </div>
                <div class="attendance-summary__chip">
                  <i class="ti ti-trophy"></i>
                  <span>Pass Rate: <strong>{{ $passRate !== null ? $passRate . '%' : '–' }}</strong></span>
                </div>
              </div>
            </div>
            {{-- Legend --}}
            <div class="d-flex flex-wrap align-items-center gap-3 mb-3">
              <span class="attendance-legend__item"><span class="status-dot status-present"></span> Pass</span>
              <span class="attendance-legend__item"><span class="status-dot status-absent"></span> Fail</span>
            </div>
            {{-- Table --}}
            @if ($totalStudents === 0)
            <div class="text-center text-muted py-5">
              <i class="ti ti-inbox fs-1 d-block mb-2"></i>
              No student reports found.
            </div>
            @else
            <div class="attendance-scroll" style="max-height:none;">
              <table id="studentReportTable" class="attendance-sheet-table student-report-table">
                <thead>
                  <tr>
                    <th class="sticky-col sticky-col--no">#</th>
                    <th class="sticky-col sticky-col--name text-start">Student</th>
                    <th class="text-success">P</th>
                    <th class="text-danger">A</th>
                    <th>Assignment (30%)</th>
                    <th>Mini Project (20%)</th>
                    <th>Final Project (40%)</th>
                    <th>Total</th>
                    <th>Result</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($course->studentReports as $i => $report)
                  @php
                  $idx = $i % count($avatarBg);
                  $initials = collect(explode(' ', trim($report->student->name ?? '')))
                  ->filter()
                  ->take(2)
                  ->map(fn($w) => strtoupper(substr($w, 0, 1)))
                  ->implode('');
                  if ($initials === '') {
                  $initials = '--';
                  }
                  $isFail = $report->result === 'fail';
                  $assignPct = min(100, (int) (($report->assignment_score / 30) * 100));
                  $miniPct = min(100, (int) (($report->mini_project_score / 20) * 100));
                  $finalPct = min(100, (int) (($report->final_project_score / 40) * 100));
                  $totalPct = min(100, (int) $report->total_score);
                  $barClass = $isFail ? 'bg-danger' : '';
                  $trackClass = $isFail ? 'bg-light-danger' : 'bg-light-primary';
                  @endphp
                  <tr>
                    <td class="sticky-col sticky-col--no" data-label="#">{{ $i + 1 }}</td>
                    <td class="sticky-col sticky-col--name text-start fw-semibold" data-label="Student">
                      <div class="d-flex align-items-center gap-2">
                        <div class="d-flex align-items-center justify-content-center rounded-circle flex-shrink-0 {{ $avatarBg[$idx] }}" style="width:26px;height:26px;">
                          <span class="fw-semibold {{ $avatarText[$idx] }}" style="font-size:.62rem;">{{ $initials }}</span>
                        </div>
                        {{ $report->student->name }}
                      </div>
                    </td>
                    <td data-label="Present"><span class="rate-badge rate-good">{{ $report->present }}</span></td>
                    <td data-label="Absent"><span class="rate-badge rate-low">{{ $report->absent }}</span></td>
                    <td data-label="Assignment (30%)" style="min-width:90px;">
                      <div class="fw-semibold mb-1">{{ number_format($report->assignment_score) }}</div>
                      <div class="progress {{ $trackClass }}" style="height:4px;">
                        <div class="progress-bar {{ $barClass }}" style="width:{{ $assignPct }}%"></div>
                      </div>
                    </td>
                    <td data-label="Mini Project (20%)" style="min-width:90px;">
                      <div class="fw-semibold mb-1">{{ number_format($report->mini_project_score) }}</div>
                      <div class="progress {{ $trackClass }}" style="height:4px;">
                        <div class="progress-bar {{ $barClass }}" style="width:{{ $miniPct }}%"></div>
                      </div>
                    </td>
                    <td data-label="Final Project (40%)" style="min-width:90px;">
                      <div class="fw-semibold mb-1">{{ number_format($report->final_project_score) }}</div>
                      <div class="progress {{ $trackClass }}" style="height:4px;">
                        <div class="progress-bar {{ $barClass }}" style="width:{{ $finalPct }}%"></div>
                      </div>
                    </td>
                    <td data-label="Total" style="min-width:90px;">
                      <div class="fw-semibold mb-1 {{ $isFail ? 'text-danger' : 'text-primary' }}">
                        {{ number_format($report->total_score, 0) }}
                      </div>
                      <div class="progress {{ $trackClass }}" style="height:4px;">
                        <div class="progress-bar {{ $barClass }}" style="width:{{ $totalPct }}%"></div>
                      </div>
                    </td>
                    <td data-label="Result">
                      @if (!$isFail)
                      <span class="badge bg-light-success text-success fw-semibold py-1 px-3">
                        <i class="ti ti-check me-1"></i>Pass
                      </span>
                      @else
                      <span class="badge bg-light-danger text-danger fw-semibold py-1 px-3">
                        <i class="ti ti-x me-1"></i>Fail
                      </span>
                      @endif
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
            {{-- Signature footer --}}
            <div class="row text-center py-4 mt-2 border-top">
              <div class="col-6 border-end">
                <p class="text-muted mb-5">Seen and Approved by</p>
                <div class="border-top mx-auto mb-2" style="width:60%;"></div>
                <h6 class="fw-semibold mb-0">ICT Training Center</h6>
              </div>
              <div class="col-6">
                <p class="text-muted mb-5">Prepared by</p>
                <div class="border-top mx-auto mb-2" style="width:60%;"></div>
                <h6 class="fw-semibold mb-0 text-capitalize">
                  Teacher: {{ $course->instructor->name ?? 'N/A' }}
                </h6>
              </div>
            </div>
            @endif
          </div>
        </div>
      </div>
    </div>{{-- /pills-student-report --}}
  </div>{{-- /tab-content --}}
</div>{{-- /col right --}}
</div>{{-- /row --}}
@endsection
@push('scripts')
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
  /* ── Select2 ── */
  if (typeof $.fn.select2 === 'undefined') {
    const s2 = document.createElement('script');
    s2.src = 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js';
    s2.onload = () => initSelect2();
    document.head.appendChild(s2);
  } else {
    initSelect2();
  }
  function initSelect2() {
    $('#targetCourseSelect').select2({
      dropdownParent: $('#moveCourseModal'),
      placeholder: '— Choose course —',
      allowClear: true,
      width: '100%'
    });
  }
  /* ── Tab persistence ── */
  document.addEventListener('DOMContentLoaded', function() {
    const tabButtons = document.querySelectorAll('#pills-tab button[data-bs-toggle="pill"]');
    const defaultTab = '#pills-attendance';
    let activeTab = localStorage.getItem('activeTab') || defaultTab;
    let triggerEl = document.querySelector(`#pills-tab button[data-bs-target="${activeTab}"]`);
    if (triggerEl) new bootstrap.Tab(triggerEl).show();
    tabButtons.forEach(btn => btn.addEventListener('shown.bs.tab', e => {
      localStorage.setItem('activeTab', e.target.getAttribute('data-bs-target'));
    }));
  });
  /* ── Flatpickr: date-range filter ── */
  (function() {
    const fromInput = document.getElementById('from_date');
    const toInput = document.getElementById('to_date');
    const display = document.getElementById('date-range-picker');
    if (!display || typeof flatpickr === 'undefined') return;
    flatpickr(display, {
      mode: 'range',
      dateFormat: 'Y-m-d',
      altInput: true,
      altFormat: 'M j, Y',
      altInputClass: 'form-control',
      defaultDate: [fromInput.value, toInput.value],
      onChange: function(selectedDates, dateStr) {
        const [from, to] = dateStr.split(' to ');
        if (from) fromInput.value = from;
        if (to) toInput.value = to;
      }
    });
  })();
  /* ── Flatpickr: per-row date (respects the lock — disabled inputs won't open) ── */
  (function() {
    if (typeof flatpickr === 'undefined') return;
    document.querySelectorAll('.js-date-picker').forEach(el => {
      const fp = flatpickr(el, {
        dateFormat: 'Y-m-d',
        altInput: true,
        altFormat: 'M j, Y',
        altInputClass: 'text-dark',
        clickOpens: !el.disabled
      });
      if (fp.altInput) fp.altInput.disabled = el.disabled;
    });
  })();
  /* ── Flatpickr: per-row time in / time out (12h with AM/PM) ── */
  (function() {
    if (typeof flatpickr === 'undefined') return;
    flatpickr('.js-time-picker', {
      enableTime: true,
      noCalendar: true,
      dateFormat: 'H:i',
      altInput: true,
      altFormat: 'h:i K',
      altInputClass: 'js-time-alt',
      time_24hr: false,
      minuteIncrement: 5
    });
  })();
  /* ── Late-minutes stepper ── */
  (function() {
    document.querySelectorAll('#attendanceTable .late-stepper').forEach(stepper => {
      const input = stepper.querySelector('.late-minutes-input');
      const minus = stepper.querySelector('.js-late-minus');
      const plus = stepper.querySelector('.js-late-plus');
      if (!input) return;
      const step = (delta) => {
        const val = Math.max(0, (parseInt(input.value) || 0) + delta);
        input.value = val;
        input.dispatchEvent(new Event('input', {
          bubbles: true
        }));
      };
      minus?.addEventListener('click', () => step(-5));
      plus?.addEventListener('click', () => step(5));
    });
  })();
  /* ── Hours auto-calculation + live summary ── */
  (function() {
    function calculateRow(row) {
      const start = row.querySelector('input[name*="[start_time]"]');
      const end = row.querySelector('input[name*="[end_time]"]');
      const total = row.querySelector('.total-hours');
      const actual = row.querySelector('.actual-hours');
      if (!start || !end || !total || !actual) return;
      function calc() {
        if (!start.value || !end.value) return;
        let diff = (new Date(`1970-01-01T${end.value}:00`) - new Date(
          `1970-01-01T${start.value}:00`)) / 3600000;
        if (diff < 0) diff += 24;
        total.value = diff.toFixed(2);
        // accumulate from the previous row's actual hours
        const prevRow = row.previousElementSibling;
        const prevATH = prevRow ? parseFloat(prevRow.querySelector('.actual-hours')?.value) ||
          0 : 0;
        actual.value = (prevATH + diff).toFixed(2);
        recomputeSummary();
      }
      start.addEventListener('change', calc);
      end.addEventListener('change', calc);
    }
    document.querySelectorAll('#attendanceTable tbody tr').forEach(r => calculateRow(r));
    const table = document.getElementById('attendanceTable');
    const sumTotal = document.getElementById('sumTotalHours');
    const sumActual = document.getElementById('sumActualHours');
    const sumLate = document.getElementById('sumLateMinutes');
    function recomputeSummary() {
      if (!table || !sumTotal || !sumActual || !sumLate) return;
      let totalHours = 0,
        actualHoursMax = 0,
        lateMinutes = 0;
      table.querySelectorAll('tbody tr').forEach(row => {
        const th = parseFloat(row.querySelector('.total-hours')?.value) || 0;
        const ah = parseFloat(row.querySelector('.actual-hours')?.value) || 0;
        const lm = parseInt(row.querySelector('.late-minutes-input')?.value) || 0;
        totalHours += th;
        actualHoursMax = Math.max(actualHoursMax, ah);
        lateMinutes += lm;
        if (!row.classList.contains('table-active')) {
          row.classList.toggle('row-late', lm > 0);
        }
        const badge = row.querySelector('.js-late-badge');
        if (badge) {
          badge.classList.toggle('warn', lm > 0);
          badge.classList.toggle('ok', lm <= 0);
          badge.innerHTML = lm > 0 ?
            '<i class="ti ti-alert-triangle"></i> Late' :
            '<i class="ti ti-check"></i> On time';
        }
      });
      const fmt = n => (Math.round(n * 100) / 100).toString();
      sumTotal.textContent = fmt(totalHours);
      sumActual.textContent = fmt(actualHoursMax);
      sumLate.textContent = lateMinutes;
    }
    table?.addEventListener('input', recomputeSummary);
    table?.addEventListener('change', recomputeSummary);
    recomputeSummary();
    /* ── Full screen toggle ── */
    const card = document.getElementById('attendanceCard');
    const btn = document.getElementById('toggleFullscreenBtn');
    if (card && btn) {
      const icon = btn.querySelector('i');
      const label = btn.querySelector('span');
      function setFullscreen(isFull) {
        card.classList.toggle('is-fullscreen', isFull);
        document.body.classList.toggle('attendance-fullscreen-open', isFull);
        icon.className = isFull ? 'ti ti-minimize' : 'ti ti-maximize';
        if (label) label.textContent = isFull ? 'Exit Full Screen' : 'Full Screen';
        btn.title = isFull ? 'Exit full screen' : 'Toggle full screen';
      }
      btn.addEventListener('click', () => setFullscreen(!card.classList.contains(
        'is-fullscreen')));
      document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && card.classList.contains('is-fullscreen')) setFullscreen(
          false);
      });
    }
  })();
  /* ── Date lock / unlock toggle ── */
  document.querySelectorAll('.btn-date-lock').forEach(function(btn) {
    btn.addEventListener('click', function() {
      const wrap = this.closest('.date-lock-wrap');
      const input = wrap.querySelector('.date-input');
      const icon = this.querySelector('i');
      const locked = input.disabled; // currently locked → we unlock
      if (locked) {
        // Unlock
        input.disabled = false;
        if (input._flatpickr?.altInput) input._flatpickr.altInput.disabled = false;
        input._flatpickr?.set('clickOpens', true);
        input._flatpickr?.open();
        icon.className = 'ti ti-lock-open';
        this.title = 'Click to lock date';
        this.classList.add('is-unlocked');
      } else {
        // Lock again
        input._flatpickr?.close();
        input.disabled = true;
        if (input._flatpickr?.altInput) input._flatpickr.altInput.disabled = true;
        input._flatpickr?.set('clickOpens', false);
        icon.className = 'ti ti-lock';
        this.title = 'Click to unlock date for editing';
        this.classList.remove('is-unlocked');
      }
    });
  });
  /* ── Re-enable disabled date inputs before form submit
         so their values are included in the POST payload ── */
  document.getElementById('attendanceForm').addEventListener('submit', function() {
    this.querySelectorAll('.date-input:disabled').forEach(function(input) {
      input.disabled = false;
    });
  });
  /* ── Duplicate row: copy time in/out + late minutes into the new row.
         Date and note are deliberately left alone — the new row's date
         already defaults to today, and the note stays whatever the user
         has (or hasn't) typed. ── */
  document.querySelectorAll('.btn-duplicate-row').forEach(function(btn) {
    btn.addEventListener('click', function() {
      const sourceRow = this.closest('tr');
      const newRow = document.querySelector('#attendanceTable tbody tr:last-child');
      if (!sourceRow || !newRow || sourceRow === newRow) return;
      const srcStart = sourceRow.querySelector('input[name*="[start_time]"]');
      const srcEnd = sourceRow.querySelector('input[name*="[end_time]"]');
      const srcLate = sourceRow.querySelector('.late-minutes-input');
      const dstStart = newRow.querySelector('input[name*="[start_time]"]');
      const dstEnd = newRow.querySelector('input[name*="[end_time]"]');
      const dstLate = newRow.querySelector('.late-minutes-input');
      if (srcStart?.value && dstStart) {
        if (dstStart._flatpickr) dstStart._flatpickr.setDate(srcStart.value, true);
        else dstStart.value = srcStart.value;
      }
      if (srcEnd?.value && dstEnd) {
        if (dstEnd._flatpickr) dstEnd._flatpickr.setDate(srcEnd.value, true);
        else dstEnd.value = srcEnd.value;
      }
      if (srcLate && dstLate) {
        dstLate.value = srcLate.value;
        dstLate.dispatchEvent(new Event('input', {
          bubbles: true
        }));
        dstLate.dispatchEvent(new Event('change', {
          bubbles: true
        }));
      }
      newRow.scrollIntoView({
        behavior: 'smooth',
        block: 'center'
      });
      newRow.classList.remove('row-flash');
      void newRow.offsetWidth; // restart animation if clicked twice
      newRow.classList.add('row-flash');
    });
  });
  /* ── Student's Attendance: full screen toggle ── */
  (function() {
    const card = document.getElementById('studentAttendanceCard');
    const btn = document.getElementById('toggleStudentFullscreenBtn');
    if (!card || !btn) return;
    const icon = btn.querySelector('i');
    const label = btn.querySelector('span');
    function setFullscreen(isFull) {
      card.classList.toggle('is-fullscreen', isFull);
      document.body.classList.toggle('attendance-fullscreen-open', isFull);
      icon.className = isFull ? 'ti ti-minimize' : 'ti ti-maximize';
      if (label) label.textContent = isFull ? 'Exit Full Screen' : 'Full Screen';
      btn.title = isFull ? 'Exit full screen' : 'Toggle full screen';
    }
    btn.addEventListener('click', () => setFullscreen(!card.classList.contains('is-fullscreen')));
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape' && card.classList.contains('is-fullscreen')) setFullscreen(false);
    });
  })();
  /* ── Student Report: full screen toggle ── */
  (function() {
    const card = document.getElementById('studentReportCard');
    const btn = document.getElementById('toggleReportFullscreenBtn');
    if (!card || !btn) return;
    const icon = btn.querySelector('i');
    const label = btn.querySelector('span');
    function setFullscreen(isFull) {
      card.classList.toggle('is-fullscreen', isFull);
      document.body.classList.toggle('attendance-fullscreen-open', isFull);
      icon.className = isFull ? 'ti ti-minimize' : 'ti ti-maximize';
      if (label) label.textContent = isFull ? 'Exit Full Screen' : 'Full Screen';
      btn.title = isFull ? 'Exit full screen' : 'Toggle full screen';
    }
    btn.addEventListener('click', () => setFullscreen(!card.classList.contains('is-fullscreen')));
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape' && card.classList.contains('is-fullscreen')) setFullscreen(false);
    });
  })();
</script>
@endpush
