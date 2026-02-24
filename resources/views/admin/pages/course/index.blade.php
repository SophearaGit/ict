@extends('admin.layouts.master')
@section('page_title', isset($page_title) ? $page_title : 'Page Title Here')
@section('content')
    @include('admin.pages.partials.breadcrumb')
    <div class="row">
        <div class="col-lg-12 col-md-12 col-12">
            <!-- Card -->
            <div class="card rounded-3">
                <!-- Card header -->
                <div class="card-header p-0">
                    <div>
                        <!-- Nav -->
                        <ul class="nav nav-lb-tab border-bottom-0" id="tab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link status-tab  {{ request()->status == '' ? 'active' : '' }}"
                                    id="courses-tab" data-bs-toggle="pill" href="javascript:;" role="tab"
                                    aria-controls="courses" aria-selected="false" tabindex="-1" data-status=""
                                    onclick="
                                    event.preventDefault();
                                    this.nextElementSibling.submit();
                                    ">All</a>
                                <form action="{{ route('admin.courses.index') }}" method="GET">
                                    <input type="hidden" name="status" value="">
                                    <input type="hidden" name="search" value="{{ request('search') }}">
                                </form>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link status-tab  {{ request()->status == 'approved' ? 'active' : '' }}"
                                    id="approved-tab" data-bs-toggle="pill" href="javascript:;" role="tab"
                                    aria-controls="approved" aria-selected="false" tabindex="-1" data-status="approved"
                                    onclick="
                                    event.preventDefault();
                                    this.nextElementSibling.submit();
                                    ">Approved</a>
                                <form action="{{ route('admin.courses.index') }}" method="GET">
                                    <input type="hidden" name="status" value="approved">
                                    <input type="hidden" name="search" value="{{ request('search') }}">
                                </form>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link status-tab  {{ request()->status == 'pending' ? 'active' : '' }}"
                                    id="pending-tab" data-bs-toggle="pill" href="javascript:;" role="tab"
                                    aria-controls="pending" aria-selected="true" data-status="pending"
                                    onclick="
                                    event.preventDefault();
                                    this.nextElementSibling.submit();
                                    ">Pending</a>
                                <form action="{{ route('admin.courses.index') }}" method="GET">
                                    <input type="hidden" name="status" value="pending">
                                    <input type="hidden" name="search" value="{{ request('search') }}">
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="p-4 row">
                    <!-- Form -->
                    <form class="d-flex align-items-center col-12 col-md-12 col-lg-12"
                        action="{{ route('admin.courses.index') }}" method="get">
                        <span class="position-absolute ps-3 search-icon"><i class="fe fe-search"></i></span>
                        <input type="hidden" name="status" value="{{ request('status') }}">
                        <input type="search" class="form-control ps-6" placeholder="Search Course"
                            value="{{ request()->search ?? '' }}" name="search">
                    </form>
                </div>
                <div>
                    <!-- Table -->
                    <div class="tab-content" id="tabContent">
                        <!--Tab pane -->
                        <div class="tab-pane fade active show" id="pending" role="tabpanel" aria-labelledby="pending-tab">
                            <div class="table-responsive border-0 overflow-y-hidden">
                                <table class="table mb-0 text-nowrap table-centered table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Courses</th>
                                            <th>Instructor</th>
                                            <th>STATUS</th>
                                            <th>ACTION</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($courses as $course)
                                            @php
                                                $isImgChecked =
                                                    $course->instructor->image == 'no-img.jpg'
                                                        ? '/default-images/user/both.jpg'
                                                        : $course->instructor->image;
                                            @endphp
                                            <tr>
                                                <td>
                                                    <a href="#" class="text-inherit">
                                                        <div class="d-flex align-items-center">
                                                            <div>
                                                                <img style="height: 4.5rem !important; object-fit: cover !important;"
                                                                    src="{{ $course->thumbnail }}"
                                                                    alt="{{ $course->title }}" class="img-4by3-lg rounded">
                                                            </div>
                                                            <div class="ms-3">
                                                                <h4 class="mb-1 text-primary-hover">
                                                                    {{ $course->title }}
                                                                </h4>
                                                                <span>Added on
                                                                    {{ $course->created_at->format('M d, Y') }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <img src="{{ $isImgChecked }}"
                                                            alt="{{ $course->instructor->name }}"
                                                            class="rounded-circle avatar-xs me-2">
                                                        <h5 class="mb-0">
                                                            {{ $course->instructor->name }}
                                                        </h5>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span
                                                        class="badge-dot bg-{{ $course->is_approved === 'pending'
                                                            ? 'warning'
                                                            : ($course->is_approved === 'approved'
                                                                ? 'success'
                                                                : ($course->is_approved === 'rejected'
                                                                    ? 'danger'
                                                                    : 'secondary')) }}"></span>
                                                    {{ ucfirst($course->is_approved) }}
                                                </td>
                                                <td>
                                                    <a href="#" class="btn btn-outline-secondary btn-sm">Reject</a>
                                                    <a href="#" class="btn btn-success btn-sm">Approved</a>
                                                </td>
                                                <td>

                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center">No courses found.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Card Footer -->
                {{-- <div class="card-footer">
                    <nav>
                        <ul class="pagination justify-content-center mb-0">
                            <li class="page-item disabled">
                                <a class="page-link mx-1 rounded" href="#">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10"
                                        fill="currentColor" class="bi bi-chevron-left" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd"
                                            d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z">
                                        </path>
                                    </svg>
                                </a>
                            </li>
                            <li class="page-item active">
                                <a class="page-link mx-1 rounded" href="#">1</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link mx-1 rounded" href="#">2</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link mx-1 rounded" href="#">3</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link mx-1 rounded" href="#">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10"
                                        fill="currentColor" class="bi bi-chevron-right" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd"
                                            d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z">
                                        </path>
                                    </svg>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div> --}}
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $('.status-tab').on('click', function(e) {
            e.preventDefault();

            const status = $(this).data('status'); // approved | pending | ""

            const url = new URL(window.location.origin + window.location.pathname);

            if (status !== '') {
                url.searchParams.set('status', status);
            }

            // 🚨 clear search
            url.searchParams.delete('search');
            url.searchParams.delete('page'); // optional but recommended

            window.location.href = url.toString();
        });
    </script>
@endpush
