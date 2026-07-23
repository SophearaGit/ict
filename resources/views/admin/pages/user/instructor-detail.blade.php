@extends('admin.layouts.master')
@section('page_title', isset($page_title) ? $page_title : 'Page Title Here')
@section('content')
    <div class="row">
        <div class="col-lg-12 col-md-12 col-12">
            <div class="border-bottom pb-3 mb-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div class="mb-2 mb-lg-0">
                    <h1 class="mb-1 h2 fw-bold text-capitalize">
                        {{ $instructor->name }}
                    </h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.instructor.index') }}">Teacher</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">{{ $instructor->name }}</li>
                        </ol>
                    </nav>
                </div>
                <a href="{{ route('admin.instructor.index') }}" class="btn btn-outline-secondary">
                    <i class="fe fe-arrow-left"></i> Back to Teacher
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Profile summary -->
        <div class="col-lg-12">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-4 align-items-start">
                        <img class="rounded-circle avatar-xl"
                            src="{{ $instructor->image == 'no-img.jpg' ? asset('/default-images/user/both.jpg') : asset($instructor->image) }}"
                            alt="{{ $instructor->name }}">

                        <div class="flex-grow-1">
                            <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                                <h3 class="mb-0 text-capitalize">
                                    @if (strtolower($instructor->gender ?? '') == 'male')
                                        Mr. {{ $instructor->name }}
                                    @else
                                        Mrs. {{ $instructor->name }}
                                    @endif
                                </h3>

                                @if (strtolower($instructor->status ?? 'active') == 'active')
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">On Leave</span>
                                @endif

                                @if ($instructor->approval_status)
                                    <span class="badge bg-light text-dark border text-capitalize">
                                        {{ str_replace('_', ' ', $instructor->approval_status) }}
                                    </span>
                                @endif
                            </div>

                            @if ($instructor->designation || $instructor->headline)
                                <p class="text-muted mb-2">
                                    {{ $instructor->designation ?? $instructor->headline }}
                                </p>
                            @endif

                            @if ($instructor->courses->isNotEmpty())
                                <div class="d-flex flex-wrap gap-2 mb-3">
                                    @foreach ($instructor->courses->unique('title') as $course)
                                        <span class="badge bg-primary-subtle text-primary">{{ $course->title }}</span>
                                    @endforeach
                                </div>
                            @endif

                            <p class="mb-0 text-muted" style="max-width: 760px;">
                                {{ $instructor->bio ?? 'No biography available for this instructor.' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick stats -->
        <div class="col-lg-12">
            <div class="row">
                <div class="col-xl-3 col-lg-6 col-md-6 col-12">
                    <div class="card mb-4">
                        <div class="card-body text-center">
                            <div class="text-muted small mb-1">Courses</div>
                            <div class="h3 mb-0">{{ $instructor->courses_count }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6 col-md-6 col-12">
                    <div class="card mb-4">
                        <div class="card-body text-center">
                            <div class="text-muted small mb-1">Students</div>
                            <div class="h3 mb-0">{{ $instructor->student_count }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6 col-md-6 col-12">
                    <div class="card mb-4">
                        <div class="card-body text-center">
                            <div class="text-muted small mb-1">Reports Filed</div>
                            <div class="h3 mb-0">{{ $instructor->reports_count }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6 col-md-6 col-12">
                    <div class="card mb-4">
                        <div class="card-body text-center">
                            <div class="text-muted small mb-1">Actual Teaching Hrs</div>
                            <div class="h3 mb-0">{{ number_format($instructor->total_actual_hours ?? 0, 1) }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact & details -->
        <div class="col-lg-6 col-md-12 col-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h4 class="mb-0">Contact Information</h4>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-start gap-3 py-2 border-bottom">
                        <span class="fe fe-mail text-primary"></span>
                        <div>
                            <div class="text-muted small">Email</div>
                            <div class="fw-semibold">{{ $instructor->email }}</div>
                        </div>
                    </div>
                    <div class="d-flex align-items-start gap-3 py-2 border-bottom">
                        <span class="fe fe-phone text-success"></span>
                        <div>
                            <div class="text-muted small">Phone Number</div>
                            <div class="fw-semibold">{{ $instructor->phone ?? 'N/A' }}</div>
                        </div>
                    </div>
                    <div class="d-flex align-items-start gap-3 py-2 border-bottom">
                        <span class="fe fe-phone-call text-success"></span>
                        <div>
                            <div class="text-muted small">Alternate Phone</div>
                            <div class="fw-semibold">{{ $instructor->alternate_phone ?? 'N/A' }}</div>
                        </div>
                    </div>
                    <div class="d-flex align-items-start gap-3 py-2">
                        <span class="fe fe-map-pin text-danger"></span>
                        <div>
                            <div class="text-muted small">Location</div>
                            <div class="fw-semibold">{{ $instructor->location ?? 'N/A' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 col-md-12 col-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h4 class="mb-0">Teacher Details</h4>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-start gap-3 py-2 border-bottom">
                        <span class="fe fe-user text-info"></span>
                        <div>
                            <div class="text-muted small">Gender</div>
                            <div class="fw-semibold text-capitalize">{{ $instructor->gender ?? 'N/A' }}</div>
                        </div>
                    </div>
                    <div class="d-flex align-items-start gap-3 py-2 border-bottom">
                        <span class="fe fe-calendar text-primary"></span>
                        <div>
                            <div class="text-muted small">Date of Birth</div>
                            <div class="fw-semibold">
                                {{ $instructor->dob ? \Carbon\Carbon::parse($instructor->dob)->format('d M, Y') : 'N/A' }}
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-start gap-3 py-2 border-bottom">
                        <span class="fe fe-flag text-warning"></span>
                        <div>
                            <div class="text-muted small">Nationality</div>
                            <div class="fw-semibold">{{ $instructor->nationality ?? 'N/A' }}</div>
                        </div>
                    </div>
                    <div class="d-flex align-items-start gap-3 py-2 {{ $instructor->document ? 'border-bottom' : '' }}">
                        <span class="fe fe-award text-info"></span>
                        <div>
                            <div class="text-muted small">Expertise</div>
                            <div class="fw-semibold">
                                @if (!empty($instructor->expertise))
                                    <div class="d-flex flex-wrap gap-1">
                                        @foreach ((array) $instructor->expertise as $skill)
                                            <span class="badge bg-light text-dark border">{{ $skill }}</span>
                                        @endforeach
                                    </div>
                                @else
                                    N/A
                                @endif
                            </div>
                        </div>
                    </div>
                    @if ($instructor->document)
                        <div class="d-flex align-items-start gap-3 py-2">
                            <span class="fe fe-file-text text-secondary"></span>
                            <div>
                                <div class="text-muted small">Verification Document</div>
                                <a href="{{ route('admin.instructor-doc-download', $instructor->id) }}"
                                    class="fw-semibold">
                                    <i class="fe fe-download"></i> Download Document
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Subjects / courses -->
        <div class="col-lg-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h4 class="mb-0">Subjects Taught</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        @forelse ($instructor->courses as $course)
                            @php
                                $ath = $athByCourse[$course->id]->ath ?? 0;
                                $duration = (float) ($course->duration ?? 0);
                                $ath = min($ath, $duration);
                                $percent = $duration > 0 ? round(($ath / $duration) * 100) : 0;
                                $format = fn($num) => rtrim(rtrim(number_format($num, 2), '0'), '.');
                                $barColor = $percent >= 100 ? '#28a745' : ($percent >= 50 ? '#3d0fe8' : '#f25d5d');
                            @endphp
                            <div class="col-xl-4 col-lg-6 col-md-6 col-12">
                                <div class="border rounded-3 p-3 h-100">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="badge bg-primary-subtle text-primary">{{ $course->title }}</span>
                                        <div class="rounded-circle d-flex align-items-center justify-content-center"
                                            style="width: 60px; height: 60px; font-size: 11px; font-weight: 700;
                                                background: conic-gradient({{ $barColor }} 0 {{ $percent }}%, #eee {{ $percent }}% 100%);">
                                            <div class="rounded-circle bg-white d-flex align-items-center justify-content-center text-center"
                                                style="width: 44px; height: 44px; font-size: 9px;">
                                                {{ $format($ath) }}/{{ $format($duration) }} hr
                                            </div>
                                        </div>
                                    </div>
                                    <p class="mb-1 small">
                                        <b>Class Start:</b>
                                        {{ $course->start_date ? \Carbon\Carbon::parse($course->start_date)->format('d M, Y') : 'N/A' }}
                                    </p>
                                    <p class="mb-0 small">
                                        <b>Duration:</b> {{ $course->duration ? $format($course->duration) . ' hr' : 'N/A' }}
                                    </p>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <p class="text-muted mb-0">No subjects assigned to this instructor.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
@endpush
