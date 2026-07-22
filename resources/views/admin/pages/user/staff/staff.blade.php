@extends('admin.layouts.master')
@section('page_title', isset($page_title) ? $page_title : 'Page Title Here')
@section('content')
    <div class="row">

        <!-- Page Header -->
        <div class="col-lg-12 col-md-12 col-12">
            <div class="border-bottom pb-3 mb-3 d-flex justify-content-between align-items-center">
                <div class="mb-2 mb-lg-0">
                    <h1 class="mb-1 h2 fw-bold">
                        Staff
                        <span class="fs-5">( {{ $staffs->total() }} )</span>
                    </h1>

                    <!-- Breadcrumb  -->
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">User</li>
                            <li class="breadcrumb-item active" aria-current="page">Staff</li>
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
                    <button class="btn btn-primary add_staff_btn">
                        <span class="fe fe-plus"></span>
                        Add Staff
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
                        <form action="{{ route('admin.staff.index') }}" method="GET">
                            <input type="search" class="form-control" placeholder="Search Instructor" name="search"
                                value="{{ request()->search ?? '' }}">
                        </form>
                    </div>
                    <div class="row">
                        @forelse ($staffs as $staff)
                            @php
                                $isImgChecked =
                                    $staff->image == 'no-img.jpg' ? '/default-images/user/both.jpg' : $staff->image;
                            @endphp
                            <div class="col-xl-3 col-lg-6 col-md-6 col-12">
                                <div class="card mb-4">
                                    <div class="card-body">
                                        <div class="text-center">
                                            <img src="{{ $isImgChecked }}" class="rounded-circle avatar-xl mb-3"
                                                alt="">
                                            <h4 class="mb-0">{{ $staff->name }}</h4>
                                            <p class="mb-0 small text-truncate">{{ $staff->email }}</p>
                                            <p class="mb-0 text-muted small">{{ $staff->designation ?? 'N/A' }}</p>
                                        </div>
                                        <div class="d-flex justify-content-between border-bottom py-2 mt-4">
                                            <span>Phone</span>
                                            <span class="text-dark">{{ $staff->phone ?? 'N/A' }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between border-bottom py-2">
                                            <span>Reports</span>
                                            <span class="text-dark">{{ $staff->reports_count }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between border-bottom py-2">
                                            <span>Account Status</span>
                                            <span class="text-dark">
                                                @if ($staff->role == 'unknown')
                                                    <span class="badge bg-danger">Disabled</span>
                                                @else
                                                    <span class="badge bg-success">Enabled</span>
                                                @endif
                                            </span>
                                        </div>
                                        <div class="d-flex justify-content-between border-bottom py-2">
                                            <span>Manage Staff Access</span>
                                            <span class="text-dark">
                                                @if ($staff->admin_approval_edit_staff)
                                                    <span class="badge bg-success">Granted</span>
                                                @else
                                                    <span class="badge bg-secondary">Not Granted</span>
                                                @endif
                                            </span>
                                        </div>
                                        <div class="d-flex justify-content-between border-bottom py-2">
                                            <span>Joined</span>
                                            <span class="text-dark">{{ $staff->created_at->format('d M, Y') }}</span>
                                        </div>
                                        @if ($staff->document)
                                            <div class="mt-3 d-grid">
                                                <button type="button"
                                                    class="btn btn-outline-secondary btn-sm view_document_btn"
                                                    data-url="{{ asset($staff->document) }}"
                                                    data-ext="{{ strtolower(pathinfo($staff->document, PATHINFO_EXTENSION)) }}"
                                                    data-name="{{ $staff->name }}">
                                                    <i class="fe fe-file-text me-1"></i> View Document
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body text-center">
                                        <h4 class="mb-0">No staffs found.</h4>
                                        <p class="mb-0">There are currently no approved staffs with uploaded
                                            documents.</p>
                                    </div>
                                </div>
                            </div>
                        @endforelse

                        <!-- Pagination Below -->
                        <div class="mt-4">
                            {{ $staffs->appends(request()->query())->links('components.paginate-geek') }}
                        </div>
                    </div>
                </div>

                <!-- tab pane -->
                <div class="tab-pane fade" id="tabPaneList" role="tabpanel" aria-labelledby="tabPaneList">

                    <!-- card -->
                    <div class="card">

                        <!-- card header -->
                        <div class="card-header">
                            <form action="{{ route('admin.staff.index') }}" method="GET">
                                <input type="search" class="form-control" placeholder="Search Instructor" name="search"
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
                                        <th>Phone</th>
                                        <th>Designation</th>
                                        <th>Reports</th>
                                        <th>Account Status</th>
                                        <th>Manage Access</th>
                                        <th>Joined</th>
                                        <th>Document</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($staffs as $staff)
                                        @php
                                            $isImgChecked =
                                                $staff->image == 'no-img.jpg'
                                                    ? '/default-images/user/both.jpg'
                                                    : $staff->image;
                                        @endphp
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ $isImgChecked }}" alt=""
                                                        class="rounded-circle avatar-md me-2">
                                                    <h5 class="mb-0">{{ $staff->name }}</h5>
                                                </div>
                                            </td>
                                            <td>{{ $staff->email }}</td>
                                            <td>{{ $staff->phone ?? 'N/A' }}</td>
                                            <td>{{ $staff->designation ?? 'N/A' }}</td>
                                            <td>{{ $staff->reports_count }}</td>
                                            <td>
                                                @if ($staff->role == 'unknown')
                                                    <span class="badge bg-danger">Disabled</span>
                                                @else
                                                    <span class="badge bg-success">Enabled</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($staff->admin_approval_edit_staff)
                                                    <span class="badge bg-success">Granted</span>
                                                @else
                                                    <span class="badge bg-secondary">Not Granted</span>
                                                @endif
                                            </td>
                                            <td>{{ $staff->created_at->format('d M, Y') }}</td>
                                            <td>
                                                @if ($staff->document)
                                                    <button type="button"
                                                        class="btn btn-outline-secondary btn-sm view_document_btn"
                                                        data-url="{{ asset($staff->document) }}"
                                                        data-ext="{{ strtolower(pathinfo($staff->document, PATHINFO_EXTENSION)) }}"
                                                        data-name="{{ $staff->name }}">
                                                        <i class="fe fe-file-text"></i>
                                                    </button>
                                                @else
                                                    <span class="text-muted small">None</span>
                                                @endif
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
                                                            {{-- account status: enable/disable --}}
                                                            @if ($staff->role == 'unknown')
                                                                <form
                                                                    action="{{ route('admin.staff.toggle', $staff->id) }}"
                                                                    method="POST">
                                                                    @csrf
                                                                    @method('PATCH')
                                                                    <button type="submit" class="dropdown-item">
                                                                        <i class="fe fe-check dropdown-item-icon"></i>
                                                                        Enable
                                                                    </button>
                                                                </form>
                                                            @else
                                                                <form
                                                                    action="{{ route('admin.staff.toggle', $staff->id) }}"
                                                                    method="POST">
                                                                    @csrf
                                                                    @method('PATCH')
                                                                    <button type="submit" class="dropdown-item">
                                                                        <i class="fe fe-x dropdown-item-icon"></i>
                                                                        Disable
                                                                    </button>
                                                                </form>
                                                            @endif
                                                            {{-- manage-other-staff permission: separate action --}}
                                                            @if ($staff->admin_approval_edit_staff)
                                                                <form
                                                                    action="{{ route('admin.staff.toggle-access', $staff->id) }}"
                                                                    method="POST">
                                                                    @csrf
                                                                    @method('PATCH')
                                                                    <button type="submit" class="dropdown-item">
                                                                        <i class="fe fe-shield-off dropdown-item-icon"></i>
                                                                        Revoke Staff Access
                                                                    </button>
                                                                </form>
                                                            @else
                                                                <form
                                                                    action="{{ route('admin.staff.toggle-access', $staff->id) }}"
                                                                    method="POST">
                                                                    @csrf
                                                                    @method('PATCH')
                                                                    <button type="submit" class="dropdown-item">
                                                                        <i class="fe fe-shield dropdown-item-icon"></i>
                                                                        Grant Staff Access
                                                                    </button>
                                                                </form>
                                                            @endif
                                                            <a class="dropdown-item edit_staff_btn"
                                                                href="javascript:void(0)" data-id="{{ $staff->id }}">
                                                                <i class="fe fe-edit dropdown-item-icon"></i>
                                                                Edit
                                                            </a>
                                                            <a class="dropdown-item del_staff_btn"
                                                                href="javascript:void(0)"
                                                                data-url="{{ route('admin.staff.destroy', $staff->id) }}">
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
                                                <h5 class="mb-0">No staffs found.</h5>
                                                <p class="mb-0">There are currently no approved staffs with uploaded
                                                    documents.</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>

                            <!-- Pagination Below -->
                            <div class="mb-3">
                                {{ $staffs->appends(request()->query())->links('components.paginate-geek') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="dynamic_staff_modal" tabindex="-1" aria-labelledby="addStaffModalLabel"
        aria-hidden="true">
        <div class="modal-dialog dynamic_staff_modal_content">
        </div>
    </div>
    <div class="modal fade" id="documentViewModal" tabindex="-1" aria-labelledby="documentViewModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="documentViewModalLabel">Document</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0" id="documentViewBody" style="min-height: 400px;">
                    {{-- filled by JS --}}
                </div>
                <div class="modal-footer">
                    <a href="#" id="documentViewDownload" target="_blank"
                        class="btn btn-outline-secondary btn-sm">
                        <i class="fe fe-download me-1"></i> Open in new tab
                    </a>
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $('.view_document_btn').on('click', function() {
            const url = $(this).data('url');
            const ext = $(this).data('ext');
            const name = $(this).data('name');
            $('#documentViewModalLabel').text(name + '\'s Document');
            $('#documentViewDownload').attr('href', url);

            const imageExts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            let body;

            if (ext === 'pdf') {
                body = `<iframe src="${url}" style="width:100%; height:70vh; border:0;"></iframe>`;
            } else if (imageExts.includes(ext)) {
                body =
                    `<div class="text-center p-3"><img src="${url}" alt="${name}" class="img-fluid rounded"></div>`;
            } else {
                // doc/docx and anything else can't be embedded reliably in-browser
                body = `
            <div class="text-center p-5">
                <i class="fe fe-file fs-1 text-muted d-block mb-3"></i>
                <p class="mb-1">This file type (.${ext}) can't be previewed inline.</p>
                <a href="${url}" target="_blank" class="btn btn-primary btn-sm mt-2">
                    <i class="fe fe-download me-1"></i> Download / Open
                </a>
            </div>`;
            }

            $('#documentViewBody').html(body);
            $('#documentViewModal').modal('show');
        });
        $('.add_staff_btn').on('click', function(e) {
            e.preventDefault();
            $('#dynamic_staff_modal').modal('show');
            $.ajax({
                method: 'GET',
                url: base_url + `/staff/create`,
                data: {},
                beforeSend: function() {
                    $('.dynamic_staff_modal_content').html(
                        `<div class="d-flex justify-content-center align-items-center" style="height: 200px;">
                            <div class="spinner-border" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>`
                    );
                },
                success: function(data) {
                    $('.dynamic_staff_modal_content').html(data);
                },
                error: function(xhr, status, error) {},
            })
        })
        $('.edit_staff_btn').on('click', function(e) {
            e.preventDefault();
            $('#dynamic_staff_modal').modal('show');
            let staff_id = $(this).data('id');
            $.ajax({
                method: 'GET',
                url: base_url + `/staff/edit/${staff_id}`,
                data: {},
                beforeSend: function() {
                    $('.dynamic_staff_modal_content').html(
                        `<div class="d-flex justify-content-center align-items-center" style="height: 200px;">
                            <div class="spinner-border" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>`
                    );
                },
                success: function(data) {
                    $('.dynamic_staff_modal_content').html(data);
                },
                error: function(xhr, status, error) {},
            })
        })
        $('.del_staff_btn').on('click', function(e) {
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
                        error: function(xhr, status, data) {},
                    })
                }
            });
        })
        document.addEventListener('DOMContentLoaded', function() {
            const activeTab = localStorage.getItem('staff_active_tab') || 'grid';
            const trigger = document.querySelector(`[data-tab="${activeTab}"]`);
            if (trigger) {
                new bootstrap.Tab(trigger).show();
            }
        });
        document.querySelectorAll('[data-tab]').forEach(btn => {
            btn.addEventListener('click', function() {
                localStorage.setItem('staff_active_tab', this.dataset.tab);
            });
        });
    </script>
@endpush
