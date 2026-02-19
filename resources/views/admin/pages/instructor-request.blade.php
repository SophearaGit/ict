@extends('admin.layouts.master')
@section('page_title', isset($page_title) ? $page_title : 'Page Title Here')
@section('content')
    <div class="row">
        <!-- Page Header -->
        <div class="col-lg-12 col-md-12 col-12">
            <div class="border-bottom pb-3 mb-3 d-flex justify-content-between align-items-center">
                <div class="mb-2 mb-lg-0">
                    <h1 class="mb-1 h2 fw-bold">
                        Instructor Request
                        @if ($instructor_requests->count() > 0)
                            <span class="fs-5">({{ $instructor_requests->count() }})</span>
                        @endif
                    </h1>
                    <!-- Breadcrumb  -->
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                Instructor Request
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-12">
            <!-- Tab -->
            <div class="tab-content">
                <!-- tab pane -->
                <div class="tab-pane fade active show" id="tabPaneList" role="tabpanel" aria-labelledby="tabPaneList">
                    <!-- card -->
                    <div class="card">
                        <!-- card header -->
                        <div class="card-header">
                            <form action="{{ route('admin.instructor-request.index') }}" method="GET">
                                <input type="search" class="form-control" placeholder="Search Instructor Request" name="search"
                                    value="{{ request()->search ?? '' }}">
                            </form>
                        </div>
                        <!-- table -->
                        <div class="table-responsive">
                            <table class="table mb-0 text-nowrap table-hover table-centered">
                                <thead class="table-light">
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Status</th>
                                        <th>Resume</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($instructor_requests as $instructor_request)
                                        @php
                                            $isImgChecked =
                                                $instructor_request->image == 'no-img.jpg'
                                                    ? '/default-images/user/both.jpg'
                                                    : $instructor_request->image;
                                        @endphp
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ $isImgChecked }}" alt=""
                                                        class="rounded-circle avatar-md me-2">
                                                    <h5 class="mb-0">
                                                        {{ $instructor_request->name }}
                                                    </h5>
                                                </div>
                                            </td>
                                            <td>
                                                {{ $instructor_request->email }}
                                            </td>
                                            <td>
                                                @php
                                                    $checkStatus = $instructor_request->approval_status;
                                                @endphp
                                                @if ($checkStatus == 'pending')
                                                    <span class="badge bg-warning">
                                                        pending
                                                    </span>
                                                @elseif ($checkStatus == 'approved')
                                                    <span class="badge bg-success">
                                                        approved
                                                    </span>
                                                @elseif ($checkStatus == 'rejected')
                                                    <span class="badge bg-danger">
                                                        rejected
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.instructor-doc-download', $instructor_request->id) }}"
                                                    class="btn btn-sm btn-outline-primary">
                                                    Downlaod
                                                </a>
                                            </td>
                                            {{-- td select with button save --}}
                                            <td>
                                                <form
                                                    action="{{ route('admin.instructor-request.update', $instructor_request->id) }}"
                                                    class="status-{{ $instructor_request->id }}" method="post">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="input-group" name="" id="">
                                                        <select name="status" class="form-select" id="">
                                                            <option @selected($checkStatus == 'pending') value="pending">Pending
                                                            </option>
                                                            <option @selected($checkStatus == 'approved') value="approved">Approve
                                                            </option>
                                                            <option @selected($checkStatus == 'rejected') value="rejected">Reject
                                                            </option>
                                                        </select>
                                                        <button class="btn btn-outline-secondary" type="button"
                                                            onclick="
                                                                var status = $('.status-{{ $instructor_request->id }} select').value;
                                                                $('.status-{{ $instructor_request->id }}').submit();
                                                            ">
                                                            <i class="fe fe-save"></i>
                                                        </button>
                                                    </div>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">
                                                No Instructor Request Found.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            <!-- Pagination -->
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
            </div>
        </div>
    </div>
@endsection
