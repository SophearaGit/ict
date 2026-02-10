@extends('admin.layouts.master')
@section('content')
    @include('admin.pages.partials.breadcrumb')
    <div class="card w-100 position-relative overflow-hidden">
        <div class="px-4 py-3 border-bottom">
            <h5 class="card-title fw-semibold mb-0 lh-sm">Instructor Requests</h5>
        </div>
        <div class="card-body p-4">
            <div class="table-responsive rounded-2 mb-4">
                <table class="table border text-nowrap customize-table mb-0 align-middle">
                    <thead class="text-dark fs-4">
                        <tr>
                            <th>
                                <h6 class="fs-4 fw-semibold mb-0">Name</h6>
                            </th>
                            <th>
                                <h6 class="fs-4 fw-semibold mb-0">Email</h6>
                            </th>
                            <th>
                                <h6 class="fs-4 fw-semibold mb-0">Status</h6>
                            </th>
                            <th>
                                <h6 class="fs-4 fw-semibold mb-0">Resume</h6>
                            </th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($instructor_requests as $instructor_request)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        {{-- <img src="{{ $instructor_request->image }}" class="rounded-circle" width="40"
                                            height="40"> --}}
                                        <div class="ms-3">
                                            <h6 class="fs-4 fw-semibold mb-0 text-capitalize">
                                                {{ $instructor_request->name }}
                                            </h6>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <p class="mb-0 fw-normal">
                                        {{ $instructor_request->email }}
                                    </p>
                                </td>
                                <td>
                                    @php
                                        $checkStatus = $instructor_request->approval_status;
                                    @endphp
                                    @if ($checkStatus == 'pending')
                                        <span class="badge bg-light-warning rounded-3 py-8 text-warning fw-semibold fs-2">
                                            pending
                                        </span>
                                    @elseif ($checkStatus == 'approved')
                                        <span class="badge bg-light-success rounded-3 py-8 text-success fw-semibold fs-2">
                                            approved
                                        </span>
                                    @elseif ($checkStatus == 'rejected')
                                        <span class="badge bg-light-danger rounded-3 py-8 text-danger fw-semibold fs-2">
                                            rejected
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.instructor-doc-download', $instructor_request->id) }}"
                                        class="btn btn-sm btn-outline-primary">
                                        Downlaod <i class="ti ti-download fs-3"></i>
                                    </a>
                                </td>
                                <td>
                                    <form action="{{ route('admin.instructor-request.update', $instructor_request->id) }}"
                                        class="status-{{ $instructor_request->id }}" method="post">
                                        @csrf
                                        @method('PUT')
                                        <div class="input-group" name="" id="">
                                            <select name="status" class="form-select" id="">
                                                <option @selected($checkStatus == 'pending') value="pending">Pending</option>
                                                <option @selected($checkStatus == 'approved') value="approved">Approve</option>
                                                <option @selected($checkStatus == 'rejected') value="rejected">Reject</option>
                                            </select>
                                            <button class="btn btn-outline-secondary" type="button"
                                                onclick="
                                                var status = $('.status-{{ $instructor_request->id }} select').value;
                                                $('.status-{{ $instructor_request->id }}').submit();
                                                ">
                                                <i class="ti ti-device-floppy fs-4"></i>
                                            </button>
                                        </div>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">
                                    <h6 class="fs-4 fw-semibold mb-0">No instructor request found</h6>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
