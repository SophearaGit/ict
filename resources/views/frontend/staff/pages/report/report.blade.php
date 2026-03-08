@extends('frontend.staff.layout.master')
@section('page_title', isset($page_title) ? $page_title : 'Page Title Here')
@section('content')
    @include('frontend.staff.pages.partials.breadcrumb')
    <ul class="nav nav-pills p-3 mb-3 rounded align-items-center card flex-row">
        <li class="nav-item">
            <a href="javascript:void(0)" data-status=""
                class="
                    status-tab
                    nav-link
                    note-link
                    d-flex
                    align-items-center
                    justify-content-center
                    px-3 px-md-3
                    me-0 me-md-2 text-body-color
                    {{ request()->status == '' ? 'active' : '' }}
                    "
                id="all-category">
                <i class="ti ti-list fill-white me-0 me-md-1"></i>
                <span class="d-none d-md-block font-weight-medium">All Reports</span>
            </a>
            <form action="{{ route('staff.reports.index') }}" method="GET">
                <input type="hidden" name="status" value="">
                <input type="hidden" name="search" value="{{ request('search') }}">
            </form>
        </li>
        <li class="nav-item">
            <a href="javascript:void(0)" data-status="pending" id="note-important"
                class=" status-tab nav-link note-link d-flex align-items-center justify-content-center px-3 px-md-3 me-0 me-md-2 text-body-color
               {{ request()->status == 'pending' ? 'active' : '' }}">
                <i class="ti ti-alert-triangle fill-white me-0 me-md-1"></i>
                <span class="d-none d-md-block font-weight-medium">
                    Not Reviewed
                </span>
            </a>

            <form action="{{ route('staff.reports.index') }}" method="GET">
                <input type="hidden" name="status" value="pending">
                <input type="hidden" name="search" value="{{ request('search') }}">
            </form>
        </li>
        <li class="nav-item">
            <a href="javascript:void(0)" data-status="reviewed"
                class=" status-tab  nav-link
                      note-link
                      d-flex
                      align-items-center
                      justify-content-center
                      px-3 px-md-3
                      me-0 me-md-2 text-body-color
                        {{ request()->status == 'reviewed' ? 'active' : '' }}
                    "
                id="note-social">
                <i class="ti ti-check fill-white me-0 me-md-1"></i>
                <span class="d-none d-md-block font-weight-medium">Reviewed</span>
            </a>
            <form action="{{ route('staff.reports.index') }}" method="GET">
                <input type="hidden" name="status" value="reviewed">
                <input type="hidden" name="search" value="{{ request('search') }}">
            </form>
        </li>
        <li class="nav-item ms-auto">
            <a href="javascript:void(0)" class="btn btn-primary d-flex align-items-center px-3 add_report_btn">
                <i class="ti ti-file me-0 me-md-1 fs-4"></i>
                <span class="d-none d-md-block font-weight-medium fs-3">Add Report</span>
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div id="note-full-container" class="note-has-grid row">
            @forelse ($reports as $report)
                <div
                    class="col-md-6 single-note-item all-category {{ $report->status == 'pending' ? 'note-important' : 'note-social' }} ">
                    <div class="card card-body">
                        <span class="side-stick"></span>
                        <h6 class="note-title text-truncate w-75 mb-0" data-noteheading="Book a Ticket for Movie">
                            {{-- report number --}}
                            Weekly Report #{{ $report->id }}
                        </h6>
                        <p class="note-date fs-2">
                            {{ $report->created_at->format('d M Y') }}
                            <strong>( {{ $report->created_at->diffForHumans() }} )</strong>
                        </p>
                        <div class="note-content">
                            <p>
                                {!! $report->report_content !!}
                            </p>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="ms-auto">
                                <div class="category-selector btn-group">
                                    <a class="nav-link category-dropdown label-group p-0" data-bs-toggle="dropdown"
                                        href="#" role="button" aria-haspopup="true" aria-expanded="true">
                                        {{-- <div class="category">
                                            <div class="category-business"></div>
                                            <div class="category-social"></div>
                                            <div class="category-important"></div>
                                            <span class="more-options text-dark">
                                            </span>
                                        </div> --}}
                                        <i class="ti ti-dots-vertical fs-5"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right category-menu">
                                        {{-- edit --}}
                                        <a class="dropdown-item edit-note edit_report_btn" href="javascript:void(0)"
                                            data-id="{{ $report->id }}">
                                            <i class="ti ti-edit fs-5 me-2"></i>
                                            Edit
                                        </a>
                                        {{-- delete --}}
                                        <a class="dropdown-item delete-note del_report_btn"
                                            href="{{ route('staff.reports.destroy', $report->id) }}">
                                            <i class="ti ti-trash fs-5 me-2"></i>
                                            Delete
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="d-flex justify-content-center align-items-center" style="height: 200px;">
                    <p class="text-muted fs-4">No reports found.</p>
                </div>
            @endforelse
        </div>
    </div>

    <div class="modal fade" id="dynamic_report_modal" tabindex="-1" aria-labelledby="bs-example-modal-lg"
        aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-xl dynamic_report_modal_content">
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


        let report_loader = `<div class="d-flex justify-content-center align-items-center" style="height: 200px;">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>`;

        $('.add_report_btn').on('click', function(e) {
            e.preventDefault();
            $('#dynamic_report_modal').modal('show');
            $.ajax({
                method: 'GET',
                url: base_url + `/staff/reports/create`,
                data: {},
                beforeSend: function() {
                    $('.dynamic_report_modal_content').html(report_loader);
                },
                success: function(data) {
                    $('.dynamic_report_modal_content').html(data);
                },
                error: function(xhr, status, error) {

                },
            })
        })

        $('.edit_report_btn').on('click', function(e) {
            e.preventDefault();
            $('#dynamic_report_modal').modal('show');
            let report_id = $(this).data('id');
            $.ajax({
                method: 'GET',
                url: base_url + `/staff/reports/${report_id}/edit`,
                data: {},
                beforeSend: function() {
                    $('.dynamic_report_modal_content').html(report_loader);
                },
                success: function(data) {
                    $('.dynamic_report_modal_content').html(data);
                },
                error: function(xhr, status, error) {

                },
            })
        })

        $('.del_report_btn').on('click', function(e) {
            e.preventDefault();
            let url = $(this).attr('href');
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
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
                            window.location.reload();
                        },
                        error: function(xhr, status, data) {},
                    })
                }
            });
        })
    </script>
@endpush
