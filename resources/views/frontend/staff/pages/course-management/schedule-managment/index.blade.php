@extends('frontend.staff.layout.master')
@section('page_title', isset($page_title) ? $page_title : 'Page Title Here')
@section('content')
    @include('frontend.staff.pages.partials.breadcrumb')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-md-flex align-items-center mb-9">
                        <div>
                            <h5 class="card-title fw-semibold mb-2">
                                Schedules
                            </h5>
                            <p class="card-subtitle text-muted">
                                You can manage schedules from this page.
                            </p>
                        </div>
                        <div class="ms-auto mt-4 mt-md-0">
                            {{-- use route instead --}}
                            <a href="{{ route('staff.schedules.create') }}" class="btn btn-primary">
                                <i class="ti ti-circle-plus me-1"></i> Create Schedule
                            </a>
                        </div>
                    </div>
                    <!-- Tab panes -->
                    <div class="tab-content mt-3">
                        <div class="tab-pane active" id="home" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table align-middle mb-0 text-nowrap">
                                    <thead>
                                        <tr class="text-muted fw-semibold">
                                            <th scope="col" class="ps-0">NO</th>
                                            <th scope="col" class="ps-0">Day</th>
                                            <th scope="col" class="ps-0">Shift</th>
                                            <th scope="col" class="ps-0">Time</th>
                                            <th scope="col" class=" ps-0"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($schedules as $schedule)
                                            <tr>
                                                <td class="ps-0">{{ $loop->iteration }}</td>
                                                <td class="text-capitalize ps-0">{{ $schedule->study_day }}</td>
                                                <td class="text-capitalize ps-0">{{ $schedule->shift }}</td>
                                                <td class="ps-0">
                                                    {{ \Carbon\Carbon::parse($schedule->start_time)->format('g:i') }}
                                                    -
                                                    {{ \Carbon\Carbon::parse($schedule->end_time)->format('g:i a') }}
                                                </td>
                                                <td class="">
                                                    <div class="dropdown dropstart">
                                                        <a href="#" class="text-muted" id="dropdownMenuButton"
                                                            data-bs-toggle="dropdown" aria-expanded="false">
                                                            <i class="ti ti-dots-vertical fs-6"></i>
                                                        </a>
                                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton"
                                                            style="">

                                                            <li>
                                                                <a class="dropdown-item d-flex align-items-center gap-3"
                                                                    href="{{ route('staff.schedules.edit', $schedule->id) }}"><i
                                                                        class="fs-4 ti ti-edit"></i>Edit
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a class="btn_dynamic_delete dropdown-item d-flex align-items-center gap-3"
                                                                    href="{{ route('staff.schedules.destroy', $schedule->id) }}"><i
                                                                        class="fs-4 ti ti-trash"></i>Delete</a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center text-muted">
                                                    No schedules available.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>


                                <div class="d-flex align-items-center justify-content-end py-1">
                                    <x-ui-pagination :paginator="$schedules" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $('.btn_dynamic_delete').on('click', function(e) {
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
                            setTimeout(() => {
                                window.location.href = data.redirect_url;
                            }, 1000);
                        },
                        error: function(xhr, status, data) {},
                    })
                }
            });
        })
    </script>
@endpush
