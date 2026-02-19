@extends('admin.layouts.master')
@section('page_title', isset($page_title) ? $page_title : 'Page Title Here')
@section('content')
    <div class="row">
        <!-- Page Header -->
        <div class="col-lg-12 col-md-12 col-12">
            <div class="border-bottom pb-3 mb-3 d-flex justify-content-between align-items-center">
                <div class="mb-2 mb-lg-0">
                    <h1 class="mb-1 h2 fw-bold">
                        Student
                        <span class="fs-5">( {{ $students->count() }} )</span>
                    </h1>
                    <!-- Breadcrumb  -->
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                            </li>
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
            <!-- Tab -->
            <div class="tab-content">
                <!-- Tab Pane -->
                <div class="tab-pane fade" id="tabPaneGrid" role="tabpanel" aria-labelledby="tabPaneGrid">
                    <div class="mb-4">
                        <form action="{{ route('admin.student.index') }}" method="GET">
                            <input type="search" class="form-control" placeholder="Search Student" name="search"
                                value="{{ request()->search ?? '' }}">
                        </form>
                    </div>
                    <div class="row">
                        @forelse ($students as $student)
                            @php
                                $isImgChecked =
                                    $student->image == 'no-img.jpg' ? '/default-images/user/both.jpg' : $student->image;
                            @endphp
                            <div class="col-xl-3 col-lg-6 col-md-6 col-12">
                                <!-- Card -->
                                <div class="card mb-4">
                                    <!-- Card body -->
                                    <div class="card-body">
                                        <div class="text-center">
                                            <div class="position-relative">
                                                <img src="{{ $isImgChecked }}" class="rounded-circle avatar-xl mb-3"
                                                    alt="">
                                                <a href="#" class="position-absolute mt-8 ms-n5">
                                                    <span class="status bg-success"></span>
                                                </a>
                                            </div>
                                            <h4 class="mb-0">
                                                {{ $student->name }}
                                            </h4>
                                            <p class="mb-0">
                                                <i class="fe fe-map-pin me-1 fs-6"></i>
                                                {{ $student->location ?? 'N/A' }}
                                            </p>
                                        </div>
                                        <div class="d-flex justify-content-between border-bottom py-2 mt-6">
                                            <span>Payments</span>
                                            <span class="text-dark">${{ rand(100, 900) }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between border-bottom py-2">
                                            <span>Joined at</span>
                                            <span>
                                                {{ $student->created_at->format('d M, Y') }}
                                            </span>
                                        </div>
                                        <div class="d-flex justify-content-between pt-2">
                                            <span>Courses</span>
                                            <span class="text-dark">
                                                {{ rand(5, 10) }}
                                            </span>
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
                        {{-- Pagination Below --}}

                    </div>
                </div>
                <!-- Tab Pane -->
                <div class="tab-pane fade" id="tabPaneList" role="tabpanel" aria-labelledby="tabPaneList">
                    <!-- Card -->
                    <div class="card">
                        <!-- Card Header -->
                        <div class="card-header">
                            <form action="{{ route('admin.student.index') }}" method="GET">
                                <input type="search" class="form-control" placeholder="Search Student" name="search"
                                    value="{{ request()->search ?? '' }}">
                            </form>
                        </div>
                        <!-- Table -->
                        <div class="table-responsive">
                            <table class="table mb-0 text-nowrap table-hover table-centered">
                                <thead class="table-light">
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Enrolled</th>
                                        <th>Joined At</th>
                                        <th>TotaL Payment</th>
                                        <th>Locations</th>
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
                                        @endphp
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="position-relative">
                                                        <img src="{{ $isImgChecked }}" alt=""
                                                            class="rounded-circle avatar-md me-2">
                                                        <a href="#" class="position-absolute mt-5 ms-n4">
                                                            <span class="status bg-success"></span>
                                                        </a>
                                                    </div>
                                                    <h5 class="mb-0">
                                                        {{ $student->name }}
                                                    </h5>
                                                </div>
                                            </td>
                                            <td>
                                                {{ $student->email }}
                                            </td>
                                            <td>
                                                {{ rand(5, 10) }}
                                            </td>
                                            <td>
                                                {{ $student->created_at->format('d M, Y') }}
                                            </td>
                                            <td>
                                                ${{ rand(100, 900) }}
                                            </td>
                                            <td>
                                                {{ $student->location ?? 'N/A' }}
                                            </td>
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
                                                            <a class="dropdown-item" href="#">
                                                                <i class="fe fe-eye dropdown-item-icon"></i>
                                                                View
                                                            </a>
                                                            <a class="dropdown-item" href="#">
                                                                <i class="fe fe-edit dropdown-item-icon"></i>
                                                                Edit
                                                            </a>
                                                            <a class="dropdown-item" href="#">
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
                                            <td colspan="6" class="text-center">
                                                <h5 class="mb-0">No students found.</h5>
                                                <p class="mb-0">There are currently no students yet.</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            {{-- Pagination Below --}}

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
            if (trigger) {
                new bootstrap.Tab(trigger).show();
            }
        });
        document.querySelectorAll('[data-tab]').forEach(btn => {
            btn.addEventListener('click', function() {
                localStorage.setItem('student_active_tab', this.dataset.tab);
            });
        });
    </script>
@endpush
