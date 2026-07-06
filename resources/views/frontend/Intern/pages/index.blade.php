@extends('frontend.Intern.layout.master')
@section('page_title', isset($page_title) ? $page_title : 'Page Title Here')
@push('styles')
@endpush
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column">
                <div class="flex-grow-1">
                    <h4 class="fs-18 text-uppercase fw-bold m-0">Dashboard</h4>
                </div>
                <div class="mt-3 mt-sm-0">
                    <form action="{{ route('intern.dashboard') }}" method="GET">
                        <div class="row g-2 mb-0 align-items-center">
                            {{-- <div class="col-auto">
                                <a href="javascript: void(0);" class="btn btn-outline-primary">
                                    <i class="ti ti-sort-ascending me-1"></i> Sort By
                                </a>
                            </div> --}}
                            <!--end col-->
                            <div class="col-sm-auto">
                                <div class="input-group">
                                    <input type="text" name="date_range" class="form-control" data-provider="flatpickr"
                                        value="{{ request('date_range') }}" data-deafult-date="01 May to 31 May"
                                        data-date-format="d M" data-range-date="true">
                                    <span class="input-group-text bg-primary border-primary text-white">
                                        <i class="ti ti-calendar fs-15"></i>
                                    </span>
                                </div>
                            </div>
                            <!--end col-->
                            <div class="col-auto">
                                <button type="submit" class="btn btn-primary">Apply</button>
                            </div>
                        </div>
                        <!--end row-->
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- end row-->
    <div class="row">
        <div class="col-xxl-3 col-md-4 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="text-muted fs-13 text-uppercase" title="Number of Reports">
                        Total Reports
                    </h5>
                    <div class="d-flex align-items-center gap-2 my-2 py-1">
                        <div class="user-img fs-42 flex-shrink-0">
                            <span class="avatar-title text-bg-primary rounded-circle fs-22">
                                <iconify-icon icon="solar:document-text-bold-duotone"></iconify-icon>
                            </span>
                        </div>
                        <h3 class="mb-0 fw-bold">{{ $reportCount ?? 0 }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end row-->
@endsection
@push('scripts')
@endpush
