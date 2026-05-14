@extends('admin.layouts.master')
@section('page_title', isset($page_title) ? $page_title : 'Student Report')
@section('content')

    <div class="row">
        <div class="col-lg-12 col-md-12 col-12">
            <div class="border-bottom pb-3 mb-3 d-md-flex align-items-center justify-content-between">
                <div class="mb-3 mb-md-0">
                    <h1 class="mb-1 h2 fw-bold">Student Report</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ url('admin/dashboard') }}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Student Report</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card rounded-3">

                {{-- Tabs --}}
                <div class="card-header p-0">
                    <ul class="nav nav-lb-tab border-bottom-0" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" data-bs-toggle="pill" href="#pending" role="tab">
                                Pending
                                <span class="badge bg-warning text-dark ms-1">{{ $pendingReports->count() }}</span>
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" data-bs-toggle="pill" href="#approved" role="tab">Approved</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" data-bs-toggle="pill" href="#rejected" role="tab">Rejected</a>
                        </li>
                    </ul>
                </div>

                <div class="tab-content">

                    {{-- ===== PENDING ===== --}}
                    <div class="tab-pane fade show active" id="pending" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table text-nowrap mb-0 table-centered table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Course</th>
                                        <th>Instructor</th>
                                        <th>Students</th>
                                        <th>Submitted</th>
                                        <th>Report Link</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($pendingReports as $courseId => $reports)
                                        @php
                                            $report = $reports->first();
                                            $course = $report->course;
                                            $instructor = $course->instructor ?? null;
                                        @endphp
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>

                                            {{-- Course --}}
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if ($course->thumbnail ?? null)
                                                        <img src="{{ asset($course->thumbnail) }}" class="rounded me-3"
                                                            style="width:50px;height:38px;object-fit:cover" alt="">
                                                    @else
                                                        <div class="icon-shape icon-md bg-light-primary rounded me-3">
                                                            <i class="fe fe-book text-primary"></i>
                                                        </div>
                                                    @endif
                                                    <h5 class="mb-0">{{ $course->title ?? 'N/A' }}</h5>
                                                </div>
                                            </td>

                                            {{-- Instructor --}}
                                            <td class="text-capitalize">{{ $instructor->name ?? 'N/A' }}</td>

                                            {{-- Student count --}}
                                            <td>
                                                <span class="badge bg-light text-dark border">
                                                    {{ $reports->count() }} student(s)
                                                </span>
                                            </td>

                                            {{-- Submitted --}}
                                            <td>
                                                {{ \Carbon\Carbon::parse($report->created_at)->format('M d, Y') }}
                                                <br>
                                                <small class="text-muted">
                                                    {{ \Carbon\Carbon::parse($report->created_at)->diffForHumans() }}
                                                </small>
                                            </td>

                                            {{-- Report file --}}
                                            {{-- <td>
                                                @if ($report->file_path ?? null)
                                                    <a href="{{ asset($report->file_path) }}" target="_blank"
                                                        class="btn btn-outline-primary btn-sm">
                                                        <i class="fe fe-file-text me-1"></i> View
                                                    </a>
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td> --}}

                                            {{-- link to course detail and click on tab student report --}}
                                            <td>
                                                <a href="{{ route('admin.courses.realtime.show', $course->id) }}"
                                                    class="btn btn-outline-primary btn-sm">
                                                    <i class="fe fe-file-text me-1"></i> View
                                                </a>
                                            </td>




                                            {{-- Approve / Reject --}}
                                            <td>
                                                <div class="dropdown">
                                                    <a class="text-body text-primary-hover" href="#" role="button"
                                                        id="dropdownOne" data-bs-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false">
                                                        <i class="fe fe-more-vertical"></i>
                                                    </a>
                                                    <div class="dropdown-menu" aria-labelledby="dropdownOne">
                                                        <div class="d-flex gap-2 px-3 py-1">


                                                            <form
                                                                action="{{ route('admin.student-report.approve', $course->id) }}"
                                                                method="POST">

                                                                @csrf

                                                                <button class="btn btn-success btn-sm">
                                                                    <i class="fe fe-check me-1"></i> Approve
                                                                </button>

                                                            </form>

                                                            <form action="#" method="POST"
                                                                onsubmit="return confirm('Reject this report?')">
                                                                @csrf @method('PATCH')
                                                                <button class="btn btn-danger btn-sm">
                                                                    <i class="fe fe-x me-1"></i> Reject
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            {{-- <td>
                                                <div class="d-flex gap-2">
                                                    <form action="" method="POST"
                                                        onsubmit="return confirm('Approve this report?')">
                                                        @csrf @method('PATCH')
                                                        <button class="btn btn-success btn-sm">
                                                            <i class="fe fe-check me-1"></i> Approve
                                                        </button>
                                                    </form>

                                                    <form action="" method="POST"
                                                        onsubmit="return confirm('Reject this report?')">
                                                        @csrf @method('PATCH')
                                                        <button class="btn btn-danger btn-sm">
                                                            <i class="fe fe-x me-1"></i> Reject
                                                        </button>
                                                    </form>
                                                </div>
                                            </td> --}}
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-6">
                                                <i class="fe fe-inbox text-muted" style="font-size:2.5rem"></i>
                                                <p class="text-muted mt-2 mb-0">No pending reports to review.</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- ===== APPROVED ===== --}}
                    <div class="tab-pane fade" id="approved" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table text-nowrap mb-0 table-centered table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Course</th>
                                        <th>Instructor</th>
                                        <th>Students</th>
                                        <th>Approved On</th>
                                        <th>Report</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- Wire up $approvedReports from controller when ready --}}
                                    <tr>
                                        <td colspan="6" class="text-center py-6">
                                            <i class="fe fe-check-circle text-success" style="font-size:2.5rem"></i>
                                            <p class="text-muted mt-2 mb-0">No approved reports yet.</p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- ===== REJECTED ===== --}}
                    <div class="tab-pane fade" id="rejected" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table text-nowrap mb-0 table-centered table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Course</th>
                                        <th>Instructor</th>
                                        <th>Students</th>
                                        <th>Rejected On</th>
                                        <th>Report</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- Wire up $rejectedReports from controller when ready --}}
                                    <tr>
                                        <td colspan="6" class="text-center py-6">
                                            <i class="fe fe-x-circle text-danger" style="font-size:2.5rem"></i>
                                            <p class="text-muted mt-2 mb-0">No rejected reports yet.</p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>{{-- end tab-content --}}
            </div>
        </div>
    </div>

@endsection
