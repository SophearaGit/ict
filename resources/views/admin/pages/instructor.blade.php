@extends('admin.layouts.master')
@section('page_title', isset($page_title) ? $page_title : 'Page Title Here')
@section('content')
    <div class="row">
        <!-- Page Header -->
        <div class="col-lg-12 col-md-12 col-12">
            <div class="border-bottom pb-3 mb-3 d-flex justify-content-between align-items-center">
                <div class="mb-2 mb-lg-0">
                    <h1 class="mb-1 h2 fw-bold">
                        Instructor
                        <span class="fs-5">( {{ $instructors->count() }} )</span>
                    </h1>
                    <!-- Breadcrumb  -->
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">User</li>
                            <li class="breadcrumb-item active" aria-current="page">Instructor</li>
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
                <!-- Tab pane -->
                <div class="tab-pane fade" id="tabPaneGrid" role="tabpanel" aria-labelledby="tabPaneGrid">
                    <div class="mb-4">
                        {{-- <input type="search" class="form-control" placeholder="Search Instructor"> --}}
                    </div>
                    <div class="row">
                        @forelse ($instructors as $instructor)
                            @php
                                $isImgChecked =
                                    $instructor->image == 'no-img.jpg'
                                        ? '/default-images/user/both.jpg'
                                        : $instructor->image;
                            @endphp
                            <div class="col-xl-3 col-lg-6 col-md-6 col-12">
                                <!-- Card -->
                                <div class="card mb-4">
                                    <!-- Card body -->
                                    <div class="card-body">
                                        <div class="text-center">
                                            <img src="{{ $isImgChecked }}" class="rounded-circle avatar-xl mb-3"
                                                alt="">
                                            <h4 class="mb-0">
                                                {{ $instructor->name }}
                                            </h4>
                                            <p class="mb-0">
                                                {{ $instructor->headline ?? 'No headline provided' }}
                                            </p>
                                        </div>
                                        <div class="d-flex justify-content-between border-bottom py-2 mt-4">
                                            <span>Students</span>
                                            <span class="text-dark">
                                                {{ rand(10, 20) }}
                                            </span>
                                        </div>
                                        <div class="d-flex justify-content-between border-bottom py-2">
                                            <span>Instructor Rating</span>
                                            <span class="text-warning">
                                                {{ number_format(rand(30, 50) / 10, 1) }}
                                                <span>
                                                    <i class="fe fe-star"></i>
                                                </span>
                                            </span>
                                        </div>
                                        <div class="d-flex justify-content-between pt-2">
                                            <span>Courses</span>
                                            <span class="text-dark">
                                                {{ rand(1, 5) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body text-center">
                                        <h4 class="mb-0">No instructors found.</h4>
                                        <p class="mb-0">There are currently no approved instructors with uploaded
                                            documents.</p>
                                    </div>
                                </div>
                            </div>
                        @endforelse
                        <!-- Pagination Below -->

                    </div>
                </div>
                <!-- tab pane -->
                <div class="tab-pane fade" id="tabPaneList" role="tabpanel" aria-labelledby="tabPaneList">
                    <!-- card -->
                    <div class="card">
                        <!-- card header -->
                        <div class="card-header">
                            {{-- <input type="search" class="form-control" placeholder="Search Instructor"> --}}
                            <h4 class="mb-1">
                                Instructors
                            </h4>
                            <p>
                                Manage all instructors from here.
                            </p>
                        </div>
                        <!-- table -->
                        <div class="table-responsive">
                            <table class="table mb-0 text-nowrap table-hover table-centered">
                                <thead class="table-light">
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Headline</th>
                                        <th>Students</th>
                                        <th>Joined</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($instructors as $instructor)
                                        @php
                                            $isImgChecked =
                                                $instructor->image == 'no-img.jpg'
                                                    ? '/default-images/user/both.jpg'
                                                    : $instructor->image;
                                        @endphp
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ $isImgChecked }}" alt=""
                                                        class="rounded-circle avatar-md me-2">
                                                    <h5 class="mb-0">
                                                        {{ $instructor->name }}
                                                    </h5>
                                                </div>
                                            </td>
                                            <td>
                                                {{ $instructor->email }}
                                            </td>
                                            <td>
                                                {{ $instructor->headline ?? 'N/A' }}
                                            </td>
                                            <td>
                                                {{ rand(10, 20) }}
                                            </td>
                                            <td>
                                                {{ $instructor->created_at->format('d M, Y') }}
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
                                                            {{-- view --}}
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
                                                <h5 class="mb-0">No instructors found.</h5>
                                                <p class="mb-0">There are currently no approved instructors with
                                                    uploaded documents.</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            <!-- Pagination Below -->

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
            const activeTab = localStorage.getItem('instructor_active_tab') || 'grid';

            const trigger = document.querySelector(`[data-tab="${activeTab}"]`);
            if (trigger) {
                new bootstrap.Tab(trigger).show();
            }
        });
        document.querySelectorAll('[data-tab]').forEach(btn => {
            btn.addEventListener('click', function() {
                localStorage.setItem('instructor_active_tab', this.dataset.tab);
            });
        });
    </script>
@endpush
