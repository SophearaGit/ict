@extends('admin.layouts.master')
@section('page_title', isset($page_title) ? $page_title : 'Page Title Here')
@section('content')
    <div class="row">
        <div class="col-lg-12 col-md-12 col-12">
            <div class="border-bottom pb-3 mb-3 d-flex justify-content-between align-items-center">
                <div class="mb-2 mb-lg-0">
                    <h1 class="mb-1 h2 fw-bold">
                        Student
                        <span class="fs-5">( {{ $total_students }} )</span>
                    </h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">User</li>
                            <li class="breadcrumb-item active" aria-current="page">Student</li>
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
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 col-md-12 col-12">
            <div class="tab-content">

                {{-- ── GRID VIEW ─────────────────────────────────────────────── --}}
                <div class="tab-pane fade" id="tabPaneGrid" role="tabpanel" aria-labelledby="tabPaneGrid">
                    <div class="mb-4">
                        <form action="{{ route('admin.student.index') }}" method="GET">
                            <input type="search" class="form-control" placeholder="Search by name or email" name="search"
                                value="{{ request()->search ?? '' }}">
                        </form>
                    </div>
                    <div class="row">
                        @forelse ($students as $student)
                            @php
                                $isImgChecked =
                                    $student->image == 'no-img.jpg' ? '/default-images/user/both.jpg' : $student->image;
                                $statusColor = match ($student->status) {
                                    'active' => 'bg-success',
                                    'on_leave' => 'bg-warning',
                                    default => 'bg-secondary',
                                };
                                $statusLabel = match ($student->status) {
                                    'active' => 'Active',
                                    'on_leave' => 'On Leave',
                                    default => ucfirst($student->status ?? 'Unknown'),
                                };
                            @endphp
                            <div class="col-xl-3 col-lg-6 col-md-6 col-12">
                                <div class="card mb-4">
                                    <div class="card-body">
                                        <div class="text-center">
                                            <div class="position-relative d-inline-block">
                                                <img src="{{ $isImgChecked }}" class="rounded-circle avatar-xl mb-3"
                                                    alt="">
                                                <span class="status {{ $statusColor }} position-absolute"
                                                    style="bottom: 14px; right: 4px;" title="{{ $statusLabel }}"></span>
                                            </div>
                                            <h4 class="mb-0">{{ $student->name }}</h4>
                                            <p class="mb-0 small text-truncate">{{ $student->email }}</p>
                                            <p class="mb-0">
                                                <i class="fe fe-map-pin me-1 fs-6"></i>
                                                {{ $student->location ?? 'N/A' }}
                                            </p>
                                            <span
                                                class="badge {{ $statusColor == 'bg-success' ? 'bg-success-soft text-success' : ($statusColor == 'bg-warning' ? 'bg-warning-soft text-warning' : 'bg-secondary-soft text-secondary') }} mt-2">
                                                {{ $statusLabel }}
                                            </span>
                                        </div>
                                        <div class="d-flex justify-content-between border-bottom py-2 mt-4">
                                            <span>Total Paid</span>
                                            <span class="text-dark fw-semibold">
                                                ${{ number_format($student->payments_sum_amount ?? 0, 2) }}
                                            </span>
                                        </div>
                                        <div class="d-flex justify-content-between border-bottom py-2">
                                            <span>Joined at</span>
                                            <span>{{ $student->created_at->format('d M, Y') }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between border-bottom py-2">
                                            <span>Phone</span>
                                            <span>{{ $student->phone ?? 'N/A' }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between pt-2">
                                            <span>Courses</span>
                                            <span class="text-dark fw-semibold">
                                                {{ $student->enrollments_count }}
                                            </span>
                                        </div>
                                        <div class="mt-3 d-grid">
                                            <a href="{{ route('admin.student.show', $student) }}"
                                                class="btn btn-outline-primary btn-sm">
                                                <i class="fe fe-eye me-1"></i> View Profile
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body text-center">
                                        <h4 class="mb-0">No students found.</h4>
                                        <p class="mb-0">There are currently no students yet.</p>
                                    </div>
                                </div>
                            </div>
                        @endforelse
                    </div>
                    <div class="mt-3">{{ $students->links() }}</div>
                </div>

                {{-- ── LIST VIEW ─────────────────────────────────────────────── --}}
                <div class="tab-pane fade" id="tabPaneList" role="tabpanel" aria-labelledby="tabPaneList">
                    <div class="card">
                        <div class="card-header">
                            <form action="{{ route('admin.student.index') }}" method="GET">
                                <input type="search" class="form-control" placeholder="Search by name or email"
                                    name="search" value="{{ request()->search ?? '' }}">
                            </form>
                        </div>
                        <div class="table-responsive">
                            <table class="table mb-0 text-nowrap table-hover table-centered">
                                <thead class="table-light">
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Enrolled</th>
                                        <th>Joined At</th>
                                        <th>Total Payment</th>
                                        <th>Status</th>
                                        <th>Location</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($students as $student)
                                        @php
                                            $isImgChecked =
                                                $student->image == 'no-img.jpg'
                                                    ? '/default-images/user/both.jpg'
                                                    : $student->image;
                                            $statusColor = match ($student->status) {
                                                'active' => 'bg-success',
                                                'on_leave' => 'bg-warning',
                                                default => 'bg-secondary',
                                            };
                                            $statusLabel = match ($student->status) {
                                                'active' => 'Active',
                                                'on_leave' => 'On Leave',
                                                default => ucfirst($student->status ?? 'Unknown'),
                                            };
                                        @endphp
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="position-relative">
                                                        <img src="{{ $isImgChecked }}" alt=""
                                                            class="rounded-circle avatar-md me-2">
                                                        <span class="status {{ $statusColor }} position-absolute"
                                                            style="bottom: 2px; right: 6px;"
                                                            title="{{ $statusLabel }}"></span>
                                                    </div>
                                                    <h5 class="mb-0">{{ $student->name }}</h5>
                                                </div>
                                            </td>
                                            <td>{{ $student->email }}</td>
                                            <td>{{ $student->phone ?? 'N/A' }}</td>
                                            <td>{{ $student->enrollments_count }}</td>
                                            <td>{{ $student->created_at->format('d M, Y') }}</td>
                                            <td class="fw-semibold">
                                                ${{ number_format($student->payments_sum_amount ?? 0, 2) }}
                                            </td>
                                            <td>
                                                <span
                                                    class="badge-dot {{ $statusColor }} me-1 d-inline-block align-middle"></span>
                                                {{ $statusLabel }}
                                            </td>
                                            <td>{{ $student->location ?? 'N/A' }}</td>
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
                                                                href="{{ route('admin.student.show', $student) }}">
                                                                <i class="fe fe-eye dropdown-item-icon"></i> View
                                                            </a>
                                                            <a class="dropdown-item" href="#">
                                                                <i class="fe fe-edit dropdown-item-icon"></i> Edit
                                                            </a>
                                                            <a class="dropdown-item text-danger" href="#">
                                                                <i class="fe fe-trash dropdown-item-icon"></i> Remove
                                                            </a>
                                                        </span>
                                                    </span>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center">
                                                <h5 class="mb-0">No students found.</h5>
                                                <p class="mb-0">There are currently no students yet.</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            <div class="p-3">{{ $students->links() }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const activeTab = localStorage.getItem('student_active_tab') || 'grid';
            const trigger = document.querySelector(`[data-tab="${activeTab}"]`);
            if (trigger) new bootstrap.Tab(trigger).show();
        });
        document.querySelectorAll('[data-tab]').forEach(btn => {
            btn.addEventListener('click', function() {
                localStorage.setItem('student_active_tab', this.dataset.tab);
            });
        });
    </script>
@endpush
