@extends('admin.layouts.master')
@section('page_title', isset($page_title) ? $page_title : 'Student Profile')
@section('content')
    @php
        $avatar =
            $student->image && $student->image !== 'no-img.jpg' ? $student->image : '/default-images/user/both.jpg';
        $studentId = 'STU-' . str_pad($student->id, 5, '0', STR_PAD_LEFT);
    @endphp
    {{-- Page Header --}}
    <div class="row">
        <div class="col-12">
            <div class="border-bottom pb-3 mb-4 d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="mb-1 h2 fw-bold">Student Profile</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.student.index') }}">Students</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ $student->name }}</li>
                        </ol>
                    </nav>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.student.index') }}" class="btn btn-outline-secondary">
                        <i class="fe fe-arrow-left me-1"></i> Back
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="row g-4">
        {{-- Left Column: Profile Card --}}
        <div class="col-xl-3 col-lg-4 col-12">
            {{-- Profile Card --}}
            <div class="card mb-4">
                <div class="card-body text-center">
                    <div class="position-relative d-inline-block mb-3">
                        <img src="{{ $avatar }}" class="rounded-circle avatar-xl" alt="{{ $student->name }}">
                        @if ($student->status === 'active')
                            <span
                                class="position-absolute bottom-0 end-0 status bg-success border border-white rounded-circle"
                                style="width:14px;height:14px;"></span>
                        @endif
                    </div>
                    <h4 class="mb-0">{{ $student->name }}</h4>
                    @if ($student->khmer_name)
                        <p class="text-muted small mb-1">{{ $student->khmer_name }}</p>
                    @endif
                    <p class="text-muted mb-2 small">{{ $studentId }}</p>
                    <span
                        class="badge bg-{{ $student->approval_status === 'approved' ? 'success' : 'warning' }}-soft
                                   text-{{ $student->approval_status === 'approved' ? 'success' : 'warning' }}">
                        {{ ucfirst($student->approval_status) }}
                    </span>
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center px-4 py-3">
                        <span class="text-muted small"><i class="fe fe-mail me-2"></i>Email</span>
                        <span class="small fw-semibold text-truncate ms-2" style="max-width:160px"
                            title="{{ $student->email }}">{{ $student->email }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-4 py-3">
                        <span class="text-muted small"><i class="fe fe-phone me-2"></i>Phone</span>
                        <span class="small fw-semibold">{{ $student->phone ?? 'N/A' }}</span>
                    </li>
                    @if ($student->alternate_phone)
                        <li class="list-group-item d-flex justify-content-between align-items-center px-4 py-3">
                            <span class="text-muted small"><i class="fe fe-phone me-2"></i>Alt. Phone</span>
                            <span class="small fw-semibold">{{ $student->alternate_phone }}</span>
                        </li>
                    @endif
                    <li class="list-group-item d-flex justify-content-between align-items-center px-4 py-3">
                        <span class="text-muted small"><i class="fe fe-map-pin me-2"></i>Location</span>
                        <span class="small fw-semibold">{{ $student->location ?? 'N/A' }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-4 py-3">
                        <span class="text-muted small"><i class="fe fe-user me-2"></i>Gender</span>
                        <span class="small fw-semibold">{{ ucfirst($student->gender ?? 'N/A') }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-4 py-3">
                        <span class="text-muted small"><i class="fe fe-flag me-2"></i>Nationality</span>
                        <span class="small fw-semibold">{{ $student->nationality ?? 'N/A' }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-4 py-3">
                        <span class="text-muted small"><i class="fe fe-calendar me-2"></i>Date of Birth</span>
                        <span class="small fw-semibold">
                            {{ $student->dob ? \Carbon\Carbon::parse($student->dob)->format('d M, Y') : 'N/A' }}
                        </span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-4 py-3">
                        <span class="text-muted small"><i class="fe fe-clock me-2"></i>Joined</span>
                        <span class="small fw-semibold">{{ $student->created_at->format('d M, Y') }}</span>
                    </li>
                </ul>
                {{-- Social Links (only shown if any are set) --}}
                @if (
                    $student->facebook ||
                        $student->linkedin ||
                        $student->github ||
                        $student->website ||
                        $student->x ||
                        $student->instagram ||
                        $student->youtube)
                    <div class="card-body border-top d-flex justify-content-center gap-3 flex-wrap">
                        @if ($student->facebook)
                            <a href="{{ $student->facebook }}" target="_blank" class="text-muted fs-5" title="Facebook"><i
                                    class="fe fe-facebook"></i></a>
                        @endif
                        @if ($student->linkedin)
                            <a href="{{ $student->linkedin }}" target="_blank" class="text-muted fs-5" title="LinkedIn"><i
                                    class="fe fe-linkedin"></i></a>
                        @endif
                        @if ($student->github)
                            <a href="{{ $student->github }}" target="_blank" class="text-muted fs-5" title="GitHub"><i
                                    class="fe fe-github"></i></a>
                        @endif
                        @if ($student->website)
                            <a href="{{ $student->website }}" target="_blank" class="text-muted fs-5" title="Website"><i
                                    class="fe fe-globe"></i></a>
                        @endif
                        @if ($student->instagram)
                            <a href="{{ $student->instagram }}" target="_blank" class="text-muted fs-5"
                                title="Instagram"><i class="fe fe-instagram"></i></a>
                        @endif
                        @if ($student->youtube)
                            <a href="{{ $student->youtube }}" target="_blank" class="text-muted fs-5" title="YouTube"><i
                                    class="fe fe-youtube"></i></a>
                        @endif
                    </div>
                @endif
            </div>
            {{-- Quick Stats --}}
            <div class="card">
                <div class="card-header fw-semibold">
                    Learning Summary
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between px-4 py-3">
                        <span class="text-muted small">Total Courses</span>
                        <span class="fw-bold">{{ $totalCourses }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between px-4 py-3">
                        <span class="text-muted small">In Progress</span>
                        <span class="fw-bold text-primary">{{ $inProgressCount }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between px-4 py-3">
                        <span class="text-muted small">Completed</span>
                        <span class="fw-bold text-success">{{ $completedCourses }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between px-4 py-3">
                        <span class="text-muted small">Not Started</span>
                        <span class="fw-bold text-secondary">{{ $notStartedCount }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between px-4 py-3">
                        <span class="text-muted small">Average Progress</span>
                        <span class="fw-bold text-info">{{ $averageProgress }}%</span>
                    </li>
                </ul>
                <div class="card-footer">
                    <small class="text-muted">
                        Overall Learning Progress
                    </small>
                    <div class="progress mt-2" style="height:6px;">
                        <div class="progress-bar" role="progressbar" style="width: {{ $averageProgress }}%">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- Right Column: Tabs --}}
        <div class="col-xl-9 col-lg-8 col-12">
            {{-- Tab Navigation --}}
            <ul class="nav nav-tabs mb-4" id="studentTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#pane-courses" type="button"
                        role="tab">
                        <i class="fe fe-book me-1"></i> Courses
                        <span class="badge bg-primary ms-1">{{ $student->enrollments->count() }}</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#pane-info" type="button"
                        role="tab">
                        <i class="fe fe-info me-1"></i> More Info
                    </button>
                </li>
            </ul>
            <div class="tab-content" id="studentTabsContent">
                {{-- Courses Tab --}}
                <div class="tab-pane fade show active" id="pane-courses" role="tabpanel">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <span class="fw-semibold">Enrolled Courses</span>
                            <span class="text-muted small">{{ $student->enrollments->count() }} total</span>
                        </div>
                        <div class="table-responsive overflow-y-hidden">
                            <table class="table mb-0 text-nowrap table-hover table-centered">
                                <thead class="table-light">
                                    <tr>
                                        <th>Course</th>
                                        {{-- <th>Students</th> --}}
                                        <th>Course Status</th>
                                        <th>Price</th>
                                        <th>Enrolled On</th>
                                        <th>Progress</th>
                                        {{-- <th>Enrollment Status</th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($student->enrollments as $enrollment)
                                        @php
                                            $course = $enrollment->course;
                                            $progress = (int) $course->progress;
                                            $completedSessions = $course->completed_sessions;
                                            $totalSessions = $course->total_sessions;
                                            // Course name — title is the correct column
                                            $courseName =
                                                $course?->title ??
                                                (($course
                                                    ? ucwords(str_replace('-', ' ', $course->slug ?? ''))
                                                    : null) ??
                                                    'Course #' . $enrollment->course_id);
                                            // Thumbnail
                                            $thumb = $course?->thumbnail ?? null;
                                            // Difficulty level
                                            $level = $course?->level ?? null;
                                            $levelBars = match (strtolower($level ?? '')) {
                                                'beginner' => 1,
                                                'intermediate' => 2,
                                                'advanced', 'advance' => 3,
                                                default => 0,
                                            };
                                            // Enrollment status badge (this student's enrollment)
$statusMap = [
    'completed' => ['success', 'Completed'],
    'in_progress' => ['primary', 'In Progress'],
    'dropped' => ['danger', 'Dropped'],
];
[$color, $label] = $statusMap[$enrollment->status] ?? [
    'warning',
    ucfirst($enrollment->status ?? 'Enrolled'),
];
// Course publish status — column is 'status' with values like 'active','inactive','draft'
$courseStatus = $course?->status ?? null;
$courseStatusMap = [
    'active' => ['success', 'Active'],
    'inactive' => ['secondary', 'Inactive'],
    'draft' => ['info', 'Draft'],
    'pending' => ['warning', 'Pending'],
];
[$csBadge, $csLabel] = $courseStatusMap[$courseStatus] ?? [
    'secondary',
    ucfirst($courseStatus ?? '—'),
];
// Total students enrolled in this course
$studentCount = $course?->enrollments_count ?? '—';
// Featured flag
$isFeatured = (bool) ($course?->is_featured ?? false);
// Duration (stored as decimal hours e.g. 62.00)
$duration = $course?->duration
    ? number_format((float) $course->duration) . 'h'
                                                : null;
                                            // Price
                                            $price = $course?->price ?? null;
                                        @endphp
                                        <tr>
                                            {{-- Course: thumbnail + name + slug + meta --}}
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0">
                                                        @if ($thumb)
                                                            <img src="{{ $thumb }}" alt="{{ $courseName }}"
                                                                class="rounded img-4by3-lg"
                                                                style="width:80px;height:60px;object-fit:cover;">
                                                        @else
                                                            <div class="rounded bg-light d-flex align-items-center justify-content-center"
                                                                style="width:80px;height:60px;">
                                                                <i class="fe fe-book text-muted fs-4"></i>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="ms-3">
                                                        <h4 class="mb-1 h5">{{ $courseName }}</h4>
                                                        <ul class="list-inline fs-6 mb-0">
                                                            @if ($duration)
                                                                <li class="list-inline-item">
                                                                    <span class="align-text-bottom">
                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                            width="10" height="10"
                                                                            fill="currentColor" class="bi bi-clock"
                                                                            viewBox="0 0 16 16">
                                                                            <path
                                                                                d="M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71V3.5z" />
                                                                            <path
                                                                                d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm7-8A7 7 0 1 1 1 8a7 7 0 0 1 14 0z" />
                                                                        </svg>
                                                                    </span>
                                                                    <span>{{ $duration }}</span>
                                                                </li>
                                                            @endif
                                                            @if ($levelBars > 0)
                                                                <li class="list-inline-item">
                                                                    <svg class="me-1 mt-n1" width="16" height="16"
                                                                        viewBox="0 0 16 16" fill="none"
                                                                        xmlns="http://www.w3.org/2000/svg">
                                                                        <rect x="3" y="8" width="2" height="6"
                                                                            rx="1"
                                                                            fill="{{ $levelBars >= 1 ? '#754FFE' : '#DBD8E9' }}">
                                                                        </rect>
                                                                        <rect x="7" y="5" width="2" height="9"
                                                                            rx="1"
                                                                            fill="{{ $levelBars >= 2 ? '#754FFE' : '#DBD8E9' }}">
                                                                        </rect>
                                                                        <rect x="11" y="2" width="2" height="12"
                                                                            rx="1"
                                                                            fill="{{ $levelBars >= 3 ? '#754FFE' : '#DBD8E9' }}">
                                                                        </rect>
                                                                    </svg>
                                                                    {{ ucfirst($level) }}
                                                                </li>
                                                            @endif
                                                            @if ($isFeatured)
                                                                <li class="list-inline-item">
                                                                    <span class="badge bg-warning-soft text-warning">
                                                                        <i class="fe fe-star me-1"
                                                                            style="font-size:10px"></i>Featured
                                                                    </span>
                                                                </li>
                                                            @endif
                                                        </ul>
                                                        @if ($progress > 0 && $progress < 100)
                                                            <div class="progress mt-2" style="height:3px;width:160px;">
                                                                <div class="progress-bar bg-success" role="progressbar"
                                                                    style="width:{{ $progress }}%"
                                                                    aria-valuenow="{{ $progress }}" aria-valuemin="0"
                                                                    aria-valuemax="100">
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            {{-- Total students on this course --}}
                                            {{-- <td>{{ $studentCount }}</td> --}}
                                            {{-- Course publish status --}}
                                            <td>
                                                @if ($courseStatus)
                                                    <span class="badge bg-{{ $csBadge }}">{{ $csLabel }}</span>
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                            {{-- Price --}}
                                            <td class="text-muted">
                                                {{ $price ? '$' . number_format($price, 2) : '—' }}
                                            </td>
                                            {{-- Enrolled date --}}
                                            <td class="text-muted">{{ $enrollment->created_at->format('d M, Y') }}</td>
                                            {{-- Progress bar --}}
                                            <td style="min-width:180px">
                                                <div class="d-flex justify-content-between mb-1">
                                                    <small class="fw-semibold">
                                                        {{ $progress }}%
                                                    </small>
                                                    <small class="text-muted">
                                                        {{ $completedSessions }}/{{ $totalSessions }}
                                                    </small>
                                                </div>
                                                <div class="progress" style="height:6px;">
                                                    <div class="progress-bar
                                                        @if ($progress >= 100) bg-success
                                                        @elseif($progress >= 50)
                                                            bg-primary
                                                        @else
                                                            bg-warning @endif"
                                                        role="progressbar" style="width: {{ $progress }}%">
                                                    </div>
                                                </div>
                                                <small class="text-muted d-block mt-1">
                                                    {{ $completedSessions }} of {{ $totalSessions }} sessions completed
                                                </small>
                                            </td>
                                            {{-- Student's enrollment status --}}
                                            {{-- <td>
                                                <span class="badge bg-{{ $color }}">{{ $label }}</span>
     </td> --}}
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-5">
                                                <p class="mb-1 fw-semibold">No courses yet</p>
                                                <small class="text-muted">This student hasn't enrolled in any
                                                    courses.</small>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                {{-- More Info Tab --}}
                <div class="tab-pane fade" id="pane-info" role="tabpanel">
                    <div class="card">
                        <div class="card-header fw-semibold">Additional Information</div>
                        <div class="card-body">
                            <div class="row g-3">
                                @if ($student->bio)
                                    <div class="col-12">
                                        <label class="form-label text-muted small fw-semibold text-uppercase">Bio</label>
                                        <p class="mb-0">{{ $student->bio }}</p>
                                    </div>
                                @endif
                                @if ($student->headline)
                                    <div class="col-12">
                                        <label
                                            class="form-label text-muted small fw-semibold text-uppercase">Headline</label>
                                        <p class="mb-0">{{ $student->headline }}</p>
                                    </div>
                                @endif
                                <div class="col-md-6">
                                    <label class="form-label text-muted small fw-semibold text-uppercase">User
                                        Role</label>
                                    <p class="mb-0 text-uppercase">{{ ucfirst($student->role ?? 'N/A') }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted small fw-semibold text-uppercase">Account
                                        Status</label>
                                    <p class="mb-0">
                                        <span
                                            class="badge bg-{{ $student->status === 'active' ? 'success' : 'danger' }}-soft
                                                       text-{{ $student->status === 'active' ? 'success' : 'danger' }}">
                                            {{ ucfirst($student->status ?? 'N/A') }}
                                        </span>
                                    </p>
                                </div>
                                {{-- <div class="col-md-6">
                                    <label class="form-label text-muted small fw-semibold text-uppercase">Email
                                        Verified</label>
                                    <p class="mb-0">
                                        @if ($student->email_verified_at)
                                            <span class="badge bg-success-soft text-success">
                                                <i class="fe fe-check me-1"></i>
                                                Verified on {{ $student->email_verified_at->format('d M, Y') }}
      </span>
      @else
      <span class="badge bg-warning-soft text-warning">Not Verified</span>
      @endif
      </p>
     </div> --}}
                                <div class="col-md-6">
                                    <label class="form-label text-muted small fw-semibold text-uppercase">Registered
                                        By</label>
                                    <p class="mb-0">
                                        @if ($student->registered_by_staff_id)
                                            Staff ID #{{ $student->registered_by_staff_id }}
                                        @else
                                            Self-registered
                                        @endif
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted small fw-semibold text-uppercase">Last
                                        Updated</label>
                                    <p class="mb-0">{{ $student->updated_at->format('d M, Y · h:i A') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>{{-- end tab-content --}}
        </div>{{-- end right col --}}
    </div>{{-- end row --}}
@endsection
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const key = 'student_profile_tab_{{ $student->id }}';
            const saved = localStorage.getItem(key);
            if (saved) {
                const el = document.querySelector(`[data-bs-target="${saved}"]`);
                if (el) bootstrap.Tab.getOrCreateInstance(el).show();
            }
            document.querySelectorAll('#studentTabs [data-bs-toggle="tab"]').forEach(function(btn) {
                btn.addEventListener('shown.bs.tab', function() {
                    localStorage.setItem(key, btn.getAttribute('data-bs-target'));
                });
            });
        });
    </script>
@endpush
