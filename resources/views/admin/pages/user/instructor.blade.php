@extends('admin.layouts.master')
@section('page_title', isset($page_title) ? $page_title : 'Page Title Here')
@section('content')
    <div class="row">
        <!-- Page Header -->
        <div class="col-lg-12 col-md-12 col-12">
            <div class="border-bottom pb-3 mb-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div class="mb-2 mb-lg-0">
                    <h1 class="mb-1 h2 fw-bold">
                        Teacher
                        <span class="fs-5">( {{ $instructors->total() }} )</span>
                    </h1>
                    <!-- Breadcrumb -->
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">User</li>
                            <li class="breadcrumb-item active" aria-current="page">Teacher</li>
                        </ol>
                    </nav>
                </div>
                <div class="nav btn-group" role="tablist">
                    <button class="btn btn-outline-secondary" data-tab="grid" data-bs-toggle="tab"
                        data-bs-target="#tabPaneGrid">
                        <span class="fe fe-grid"></span>
                    </button>
                    <button class="btn btn-outline-secondary" data-tab="list" data-bs-toggle="tab"
                        data-bs-target="#tabPaneList">
                        <span class="fe fe-list"></span>
                    </button>
                    <button class="btn btn-primary add_teacher_btn" type="button" data-bs-toggle="modal"
                        data-bs-target="#teacherModal">
                        <span class="fe fe-plus"></span>
                        Add Teacher
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-3">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" id="filterForm" class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label">Search</label>
                            <input type="search" class="form-control" placeholder="Search name or email"
                                name="search" value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Subject</label>
                            <select name="subject" class="form-select" onchange="document.getElementById('filterForm').submit()">
                                <option value="">All Subject</option>
                                @foreach ($subjects as $subject)
                                    <option value="{{ $subject }}" @selected(request('subject') == $subject)>
                                        {{ $subject }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select" onchange="document.getElementById('filterForm').submit()">
                                <option value="">All Status</option>
                                <option value="Active" @selected(request('status') == 'active')>Active</option>
                                <option value="On_Leave" @selected(request('status') == 'On_Leave')>On Leave</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Gender</label>
                            <select name="gender" class="form-select" onchange="document.getElementById('filterForm').submit()">
                                <option value="">All Gender</option>
                                <option value="Male" @selected(request('gender') == 'Male')>Male</option>
                                <option value="Female" @selected(request('gender') == 'Female')>Female</option>
                            </select>
                        </div>
                        <div class="col-md-2 d-flex gap-2">
                            <button type="submit" class="btn btn-primary flex-fill">
                                <i class="fe fe-search"></i> Search
                            </button>
                            @if (request('search') || request('subject') || request('status') || request('gender'))
                                <a href="{{ route('admin.instructor.index') }}" class="btn btn-outline-danger">
                                    <i class="fe fe-x-circle"></i>
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 col-md-12 col-12">
            <!-- Tab -->
            <div class="tab-content">
                <!-- Grid pane -->
                <div class="tab-pane fade" id="tabPaneGrid" role="tabpanel" aria-labelledby="tabPaneGrid">
                    <div class="row">
                        @forelse ($instructors as $teacher)
                            @php
                                $isImgChecked =
                                    empty($teacher->image) || $teacher->image === 'no-img.jpg'
                                        ? '/default-images/user/both.jpg'
                                        : $teacher->image;
                                $status = $teacher->status ?? 'active';
                                $subjectList = $teacher->courses->pluck('title')->unique()->take(3)->implode(', ') ?: 'No subject assigned';
                                $attendanceHours = $teacher->total_actual_hours ?? 0;
                            @endphp
                            <div class="col-xl-3 col-lg-6 col-md-6 col-12">
                                <!-- Card -->
                                <div class="card mb-4">
                                    <!-- Card body -->
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <span class="badge {{ $status === 'active' ? 'bg-success' : 'bg-danger' }}">
                                                {{ $status === 'active' ? 'active' : 'on_leave' }}
                                            </span>
                                            <span class="dropdown dropstart">
                                                <a class="btn-icon btn btn-ghost btn-sm rounded-circle" href="#"
                                                    role="button" data-bs-toggle="dropdown" data-bs-offset="-20,20"
                                                    aria-expanded="false">
                                                    <i class="fe fe-more-vertical"></i>
                                                </a>
                                                <span class="dropdown-menu">
                                                    <span class="dropdown-header">Settings</span>
                                                    <a class="dropdown-item"
                                                        href="{{ route('admin.instructor.show.detail', $teacher->id) }}">
                                                        <i class="fe fe-eye dropdown-item-icon"></i>
                                                        View Detail
                                                    </a>
                                                    <form action="{{ route('admin.instructor.toggle', $teacher->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('PATCH')
                                                        @if ($status === 'active')
                                                            <button type="submit" class="dropdown-item">
                                                                <i class="fe fe-user-x dropdown-item-icon"></i>
                                                                Mark On Leave
                                                            </button>
                                                        @else
                                                            <button type="submit" class="dropdown-item">
                                                                <i class="fe fe-user-check dropdown-item-icon"></i>
                                                                Mark Active
                                                            </button>
                                                        @endif
                                                    </form>
                                                    <a class="dropdown-item del_teacher_btn" href="javascript:void(0)"
                                                        data-url="{{ route('admin.instructor.destroy', $teacher->id) }}">
                                                        <i class="fe fe-trash dropdown-item-icon"></i>
                                                        Remove
                                                    </a>
                                                </span>
                                            </span>
                                        </div>
                                        <a href="{{ route('admin.instructor.show.detail', $teacher->id) }}"
                                            class="text-decoration-none text-reset">
                                            <div class="text-center">
                                                <img src="{{ $isImgChecked }}" class="rounded-circle avatar-xl mb-3"
                                                    alt="{{ $teacher->name }}">
                                                <h4 class="mb-0 text-capitalize">
                                                    {{ $teacher->name }}
                                                </h4>
                                                <p class="mb-0 text-muted small">
                                                    {{ $teacher->email }}
                                                </p>
                                            </div>
                                        </a>
                                        <div class="text-center mt-2">
                                            <span class="badge bg-light text-dark border">
                                                {{ Str::limit($subjectList, 40) }}
                                            </span>
                                        </div>
                                        <div class="d-flex justify-content-between border-bottom py-2 mt-3">
                                            <span>Courses</span>
                                            <span class="text-dark fw-semibold">{{ $teacher->courses_count }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between border-bottom py-2">
                                            <span>Students</span>
                                            <span class="text-dark fw-semibold">{{ $teacher->student_count }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between border-bottom py-2">
                                            <span>Reports Filed</span>
                                            <span class="text-dark fw-semibold">{{ $teacher->reports_count }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between border-bottom py-2">
                                            <span>Actual Teaching Hrs</span>
                                            <span class="text-dark fw-semibold">{{ number_format($attendanceHours, 1) }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between py-2">
                                            <span>Joined</span>
                                            <span class="text-dark">{{ $teacher->created_at->format('d M, Y') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body text-center">
                                        <h4 class="mb-0">No teachers found.</h4>
                                        <p class="mb-0">There are currently no approved instructors with uploaded
                                            documents.</p>
                                    </div>
                                </div>
                            </div>
                        @endforelse
                    </div>
                    {{-- Pagination --}}
                    @if ($instructors->hasPages())
                        <div class="d-flex justify-content-center">
                            {{ $instructors->onEachSide(1)->links() }}
                        </div>
                    @endif
                </div>

                <!-- List pane -->
                <div class="tab-pane fade" id="tabPaneList" role="tabpanel" aria-labelledby="tabPaneList">
                    <div class="card">
                        <div class="table-responsive">
                            <table class="table mb-0 text-nowrap table-hover table-centered">
                                <thead class="table-light">
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Subject</th>
                                        <th>Courses</th>
                                        <th>Students</th>
                                        <th>Reports</th>
                                        <th>Actual Hrs</th>
                                        <th>Status</th>
                                        <th>Joined</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($instructors as $teacher)
                                        @php
                                            $isImgChecked =
                                                empty($teacher->image) || $teacher->image === 'no-img.jpg'
                                                    ? '/default-images/user/both.jpg'
                                                    : $teacher->image;
                                            $status = $teacher->status ?? 'active';
                                            $subjectList = $teacher->courses->pluck('title')->unique()->take(3)->implode(', ') ?: 'No subject assigned';
                                            $attendanceHours = $teacher->total_actual_hours ?? 0;
                                        @endphp
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ $isImgChecked }}" alt=""
                                                        class="rounded-circle avatar-md me-2">
                                                    <h5 class="mb-0 text-capitalize">
                                                        <a href="{{ route('admin.instructor.show.detail', $teacher->id) }}"
                                                            class="text-reset">{{ $teacher->name }}</a>
                                                    </h5>
                                                </div>
                                            </td>
                                            <td>{{ $teacher->email }}</td>
                                            <td class="text-wrap" style="max-width: 220px;">
                                                {{ Str::limit($subjectList, 40) }}
                                            </td>
                                            <td>{{ $teacher->courses_count }}</td>
                                            <td>{{ $teacher->student_count }}</td>
                                            <td>{{ $teacher->reports_count }}</td>
                                            <td>{{ number_format($attendanceHours, 1) }}</td>
                                            <td>
                                                <span class="badge {{ $status === 'active' ? 'bg-success' : 'bg-danger' }}">
                                                    {{ $status === 'active' ? 'active' : 'on_leave' }}
                                                </span>
                                            </td>
                                            <td>{{ $teacher->created_at->format('d M, Y') }}</td>
                                            <td>
                                                <div class="hstack gap-4">
                                                    <span class="dropdown dropstart">
                                                        <a class="btn-icon btn btn-ghost btn-sm rounded-circle"
                                                            href="#" role="button" data-bs-toggle="dropdown"
                                                            data-bs-offset="-20,20" aria-expanded="false">
                                                            <i class="fe fe-more-vertical"></i>
                                                        </a>
                                                        <span class="dropdown-menu">
                                                            <span class="dropdown-header">Settings</span>
                                                            <a class="dropdown-item"
                                                                href="{{ route('admin.instructor.show.detail', $teacher->id) }}">
                                                                <i class="fe fe-eye dropdown-item-icon"></i>
                                                                View Detail
                                                            </a>
                                                            <form
                                                                action="{{ route('admin.instructor.toggle', $teacher->id) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('PATCH')
                                                                @if ($status === 'active')
                                                                    <button type="submit" class="dropdown-item">
                                                                        <i class="fe fe-user-x dropdown-item-icon"></i>
                                                                        Mark On Leave
                                                                    </button>
                                                                @else
                                                                    <button type="submit" class="dropdown-item">
                                                                        <i class="fe fe-user-check dropdown-item-icon"></i>
                                                                        Mark Active
                                                                    </button>
                                                                @endif
                                                            </form>
                                                            <a class="dropdown-item del_teacher_btn"
                                                                href="javascript:void(0)"
                                                                data-url="{{ route('admin.instructor.destroy', $teacher->id) }}">
                                                                <i class="fe fe-trash dropdown-item-icon"></i>
                                                                Remove
                                                            </a>
                                                        </span>
                                                    </span>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10" class="text-center">
                                                <h5 class="mb-0">No teachers found.</h5>
                                                <p class="mb-0">There are currently no approved instructors with
                                                    uploaded documents.</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            {{-- Pagination --}}
                            @if ($instructors->hasPages())
                                <div class="card-footer">
                                    {{ $instructors->onEachSide(1)->links() }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Teacher Modal -->
    <div class="modal fade" id="teacherModal" tabindex="-1" aria-labelledby="teacherModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="addTeacherForm" action="{{ route('admin.instructor.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="teacherModalLabel">Add Teacher</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="name" placeholder="Enter teacher name"
                                    required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" name="email"
                                    placeholder="Enter teacher email" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Password <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" name="password"
                                    placeholder="Enter password" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" name="password_confirmation"
                                    placeholder="Confirm password" required>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Document <span class="text-danger">*</span></label>
                                <input type="file" class="form-control" name="document" accept=".pdf,.doc,.docx,.jpg,.png"
                                    required>
                                <div class="form-text">PDF, DOC, DOCX, JPG or PNG, up to 12MB.</div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Teacher</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $('.del_teacher_btn').on('click', function(e) {
            e.preventDefault();
            let url = $(this).data('url');
            Swal.fire({
                title: "Are you sure?",
                text: "This action cannot be undone.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        method: "DELETE",
                        url: url,
                        data: {
                            _token: csrf_token,
                        },
                        success: function(data) {
                            iziToast.success({
                                message: data.message,
                                position: 'bottomRight'
                            });
                            setTimeout(() => {
                                window.location.reload();
                            }, 500);
                        },
                        error: function(xhr, status, data) {
                            iziToast.error({
                                message: xhr.responseJSON?.message || 'Something went wrong.',
                                position: 'bottomRight'
                            });
                        },
                    })
                }
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            const activeTab = localStorage.getItem('teacher_active_tab') || 'grid';
            const trigger = document.querySelector(`[data-tab="${activeTab}"]`);
            if (trigger) {
                new bootstrap.Tab(trigger).show();
            }
        });

        document.querySelectorAll('[data-tab]').forEach(btn => {
            btn.addEventListener('click', function() {
                localStorage.setItem('teacher_active_tab', this.dataset.tab);
            });
        });
    </script>
@endpush
