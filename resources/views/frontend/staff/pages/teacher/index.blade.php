@extends('frontend.staff.layout.master')
@section('page_title', isset($page_title) ? $page_title : 'Page Title Here')
@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css">
    <style>
        /* ============ Toolbar / action row ============ */
        .teacher-action-row {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 16px;
            align-items: center;
            margin-bottom: 24px;
        }

        .teacher-toolbar-left {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .teacher-search-form {
            flex: 1;
            min-width: 220px;
        }

        .teacher-search-wrap {
            position: relative;
            display: flex;
            align-items: center;
            background: #fff;
            border: 1px solid #e7e8f0;
            border-radius: 12px;
            padding: 0 14px;
            height: 46px;
            transition: border-color .15s, box-shadow .15s;
        }

        .teacher-search-wrap:focus-within {
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, .12);
        }

        .teacher-search-wrap i {
            color: #9397ab;
            font-size: 18px;
            margin-right: 10px;
        }

        .teacher-search-input.form-control {
            border: none;
            box-shadow: none;
            padding: 0;
            height: auto;
            font-family: 'DM Sans', sans-serif;
            font-size: 14px;
            background: transparent;
        }

        .teacher-search-input.form-control:focus {
            box-shadow: none;
        }

        .teacher-view-toggle {
            display: flex;
            background: #f6f7fb;
            border-radius: 12px;
            padding: 4px;
            border: none;
            gap: 2px;
        }

        .teacher-view-toggle .view-btn {
            border: none;
            background: transparent;
            color: #6b7280;
            width: 38px;
            height: 38px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 9px;
            transition: background .15s, color .15s;
        }

        .teacher-view-toggle .view-btn.active {
            background: #fff;
            color: #1a1a2e;
            box-shadow: 0 1px 4px rgba(20, 20, 43, .08);
        }

        .teacher-view-toggle .view-btn:hover:not(.active) {
            color: #1a1a2e;
        }

        .teacher-add-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #0f0e17;
            color: #fff;
            border: none;
            border-radius: 999px;
            padding: 12px 22px;
            font-family: 'DM Sans', sans-serif;
            font-weight: 700;
            font-size: 14px;
            white-space: nowrap;
            transition: background .15s;
            text-decoration: none;
        }

        .teacher-add-btn:hover {
            background: #2749c4;
            color: #fff;
        }

        .teacher-add-btn i {
            font-size: 17px;
        }

        @media (max-width: 991.98px) {
            .teacher-action-row {
                grid-template-columns: 1fr;
            }

            .teacher-toolbar-left {
                width: 100%;
            }

            .teacher-search-form {
                width: 100%;
            }

            .teacher-add-btn {
                width: 100%;
                justify-content: center;
            }
        }

        @media (max-width: 575.98px) {
            .teacher-toolbar-left {
                flex-direction: column;
                align-items: stretch;
            }

            .teacher-view-toggle {
                align-self: flex-start;
            }
        }

        /* ============ List view: card + table ============ */
        .teacher-list-card {
            background: #fff;
            border: 1px solid #f0f1f5;
            border-radius: 16px;
            box-shadow: 0 2px 10px rgba(20, 20, 43, .04);
            padding: 8px;
            overflow: hidden;
        }

        .teacher-table-scroll {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        table.teacher-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            font-family: 'DM Sans', sans-serif;
        }

        table.teacher-table thead th {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: .04em;
            color: #8b8fa3;
            font-weight: 700;
            border: none;
            padding: 14px 16px;
            background: transparent;
            white-space: nowrap;
        }

        table.teacher-table tbody tr {
            border-top: 1px solid #f3f4f8;
            transition: background .12s;
        }

        table.teacher-table tbody tr:hover {
            background: #fafafe;
        }

        table.teacher-table tbody td {
            padding: 14px 16px;
            vertical-align: middle;
            border: none;
            font-size: 14px;
            color: #2b2d3d;
        }

        table.teacher-table .user-name {
            font-family: 'DM Sans', sans-serif;
            font-weight: 700;
            font-size: 14px;
            color: #1a1a2e;
        }

        table.teacher-table .user-work {
            font-size: 12.5px;
        }

        table.teacher-table img.rounded-circle {
            border: 2px solid #f0f1f5;
        }

        table.teacher-table .badge.rounded-pill {
            font-family: 'DM Sans', sans-serif;
            font-weight: 600;
            font-size: 12px;
            padding: 5px 12px;
        }

        table.teacher-table .bg-light-primary {
            background: #eef0fd !important;
            color: #4f46e5 !important;
        }

        table.teacher-table .bg-light-danger {
            background: #fee2e2 !important;
            color: #dc2626 !important;
        }

        table.teacher-table .action-btn {
            display: flex;
            align-items: center;
            gap: 4px;
        }

        table.teacher-table .action-btn a {
            width: 32px;
            height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 9px;
            color: #6b7280 !important;
            transition: background .15s, color .15s;
        }

        table.teacher-table .action-btn a.btn-edit-teacher:hover {
            background: #eef0fd;
            color: #4f46e5 !important;
        }

        table.teacher-table .action-btn a.btn-delete-teacher:hover {
            background: #fee2e2;
            color: #dc2626 !important;
        }

        #view-list .text-center.text-muted {
            padding: 56px 20px !important;
        }

        #view-list .ti-users-off {
            font-size: 40px;
            color: #c7cbe0;
            display: block;
            margin: 0 auto 10px;
        }

        /* ============ Grid view cards ============ */
        #view-grid .card {
            border: 1px solid #f0f1f5 !important;
            border-radius: 16px !important;
            box-shadow: 0 2px 10px rgba(20, 20, 43, .04) !important;
            transition: box-shadow .15s, transform .15s;
            font-family: 'DM Sans', sans-serif;
        }

        #view-grid .card:hover {
            box-shadow: 0 8px 24px rgba(20, 20, 43, .08) !important;
            transform: translateY(-2px);
        }

        #view-grid .card img.rounded-circle {
            border: 3px solid #f6f7fb !important;
            box-shadow: none !important;
        }

        #view-grid .card h6 {
            font-weight: 700;
            color: #1a1a2e;
        }

        #view-grid .card .badge.rounded-pill {
            font-weight: 600;
            font-size: 12px;
        }

        #view-grid .card .bg-light-primary {
            background: #eef0fd !important;
            color: #4f46e5 !important;
        }

        #view-grid .card .bg-light-danger {
            background: #fee2e2 !important;
            color: #dc2626 !important;
        }

        #view-grid .card .card-footer {
            border-top: 1px solid #f3f4f8 !important;
        }

        #view-grid .card .card-footer .btn {
            border-radius: 999px;
            font-weight: 600;
            font-size: 12.5px;
        }

        #view-grid .card .card-footer .btn-outline-info {
            border-color: #d7dafa;
            color: #4f46e5;
        }

        #view-grid .card .card-footer .btn-outline-info:hover {
            background: #4f46e5;
            border-color: #4f46e5;
            color: #fff;
        }

        #view-grid .card .card-footer .btn-outline-danger {
            border-color: #fbd0d0;
            color: #dc2626;
        }

        #view-grid .card .card-footer .btn-outline-danger:hover {
            background: #dc2626;
            border-color: #dc2626;
            color: #fff;
        }

        #view-grid .text-center.text-muted .ti-users-off {
            color: #c7cbe0;
        }

        /* ============ Modals: Add / Edit / Crop / Delete ============ */
        #addContactModal .modal-content,
        #editContactModal .modal-content,
        #cropModal .modal-content,
        #deleteTeacherModal .modal-content {
            border: none;
            border-radius: 18px;
            overflow: hidden;
            font-family: 'DM Sans', sans-serif;
        }

        #addContactModal .modal-header,
        #editContactModal .modal-header,
        #cropModal .modal-header,
        #deleteTeacherModal .modal-header {
            border-bottom: 1px solid #f0f1f5;
            padding: 20px 24px;
        }

        #addContactModal .modal-title,
        #editContactModal .modal-title,
        #cropModal .modal-title,
        #deleteTeacherModal .modal-title {
            font-family: 'DM Sans', sans-serif;
            font-weight: 700;
            font-size: 18px;
            color: #1a1a2e;
        }

        #addContactModal .modal-body,
        #editContactModal .modal-body {
            padding: 24px;
        }

        #addContactModal .modal-footer,
        #editContactModal .modal-footer,
        #cropModal .modal-footer,
        #deleteTeacherModal .modal-footer {
            border-top: 1px solid #f0f1f5;
            padding: 16px 24px;
        }

        #addContactModal .form-label,
        #editContactModal .form-label {
            font-family: 'DM Sans', sans-serif;
            font-weight: 600;
            font-size: 13px;
            color: #3d3f52;
            margin-bottom: 6px;
        }

        #addContactModal .form-control,
        #addContactModal .form-select,
        #editContactModal .form-control,
        #editContactModal .form-select {
            border: 1px solid #e7e8f0;
            border-radius: 10px;
            padding: 10px 14px;
            font-family: 'DM Sans', sans-serif;
            font-size: 14px;
            color: #1a1a2e;
            transition: border-color .15s, box-shadow .15s;
        }

        #addContactModal .form-control:focus,
        #addContactModal .form-select:focus,
        #editContactModal .form-control:focus,
        #editContactModal .form-select:focus {
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, .12);
        }

        #addContactModal textarea.form-control,
        #editContactModal textarea.form-control {
            resize: vertical;
        }

        /* Footer buttons */
        #addContactModal .modal-footer .btn,
        #editContactModal .modal-footer .btn,
        #cropModal .modal-footer .btn,
        #deleteTeacherModal .modal-footer .btn {
            border-radius: 999px;
            font-family: 'DM Sans', sans-serif;
            font-weight: 700;
            font-size: 14px;
            padding: 10px 22px;
            border: none;
        }

        #addContactModal .modal-footer .btn-success,
        #editContactModal .modal-footer .btn-success {
            background: #0f0e17;
            color: #fff;
        }

        #addContactModal .modal-footer .btn-success:hover,
        #editContactModal .modal-footer .btn-success:hover {
            background: #2749c4;
        }

        #addContactModal .modal-footer .btn-danger,
        #editContactModal .modal-footer .btn-danger,
        #deleteTeacherModal .modal-footer .btn-danger {
            background: #dc2626;
            color: #fff;
        }

        #addContactModal .modal-footer .btn-danger:hover,
        #editContactModal .modal-footer .btn-danger:hover,
        #deleteTeacherModal .modal-footer .btn-danger:hover {
            background: #b91c1c;
        }

        .teacher-btn-ghost-danger.btn-danger {
            background: #fff !important;
            color: #dc2626 !important;
            border: 1px solid #fbd0d0 !important;
        }

        .teacher-btn-ghost-danger.btn-danger:hover {
            background: #fee2e2 !important;
        }

        #deleteTeacherModal .modal-footer .btn-secondary,
        #cropModal .modal-footer .btn-secondary {
            background: #f6f7fb;
            color: #3d3f52;
        }

        #deleteTeacherModal .modal-footer .btn-secondary:hover,
        #cropModal .modal-footer .btn-secondary:hover {
            background: #eceefa;
        }

        #cropModal .modal-footer .btn-primary {
            background: #4f46e5;
            color: #fff;
        }

        #cropModal .modal-footer .btn-primary:hover {
            background: #3c35b5;
        }

        /* Password toggle + input-group */
        #addContactModal .input-group .form-control,
        #editContactModal .input-group .form-control {
            border-right: none;
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
        }

        #addContactModal .toggle-password,
        #editContactModal .toggle-password {
            border: 1px solid #e7e8f0;
            border-left: none;
            background: #fff;
            color: #8b8fa3;
            border-top-right-radius: 10px;
            border-bottom-right-radius: 10px;
        }

        #addContactModal .toggle-password:hover,
        #editContactModal .toggle-password:hover {
            color: #4f46e5;
        }

        /* Edit tabs — segmented pill bar */
        .edit-tabs {
            border-bottom: none;
            background: #f6f7fb;
            border-radius: 12px;
            padding: 4px;
            display: inline-flex;
            gap: 2px;
            flex-wrap: wrap;
        }

        .edit-tabs .nav-item {
            margin: 0;
        }

        .edit-tabs .nav-link {
            border: none;
            border-radius: 9px;
            padding: 8px 16px;
            font-family: 'DM Sans', sans-serif;
            color: #6b7280;
            font-weight: 600;
            font-size: 13.5px;
        }

        .edit-tabs .nav-link.active {
            background: #fff;
            color: #1a1a2e;
            box-shadow: 0 1px 4px rgba(20, 20, 43, .08);
        }

        .edit-tabs .nav-link:hover:not(.active) {
            color: #1a1a2e;
        }

        @media (max-width: 575.98px) {
            .edit-tabs {
                display: flex;
                width: 100%;
            }

            .edit-tabs .nav-item {
                flex: 1;
            }

            .edit-tabs .nav-link {
                width: 100%;
                text-align: center;
                padding: 8px 8px;
                font-size: 12.5px;
            }
        }

        /* Expertise chip list */
        #edit-expertise-list {
            display: flex;
            flex-direction: row;
            flex-wrap: wrap;
            gap: 8px;
            list-style: none;
            padding: 0;
            margin: 0 0 8px;
            border: none;
        }

        #edit-expertise-list .list-group-item {
            display: flex;
            flex: 0 0 auto;
            align-items: center;
            gap: 8px;
            width: auto;
            max-width: 100%;
            border: 1px solid #e0e3f3;
            border-radius: 20px;
            background: #f6f7fd;
            padding: 6px 8px 6px 14px;
            font-size: 13px;
            font-family: 'DM Sans', sans-serif;
            color: #1a1a2e;
        }

        #edit-expertise-list .btn-remove-expertise {
            border: none;
            background: #fff;
            color: #8b8fa3;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            padding: 0;
        }

        #edit-expertise-list .btn-remove-expertise:hover {
            background: #fee2e2;
            color: #dc2626;
        }

        #btn-add-expertise {
            border-radius: 0 10px 10px 0;
            border: 1px solid #d7dafa;
            border-left: none;
            color: #4f46e5;
            background: #fff;
            font-weight: 600;
        }

        #btn-add-expertise:hover {
            background: #4f46e5;
            color: #fff;
        }

        #edit-expertise-input {
            border-radius: 10px 0 0 10px;
        }

        /* Image preview + crop */
        #edit-image-preview-wrap {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            overflow: hidden;
            flex-shrink: 0;
            border: 2px solid #f0f1f5;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f6f7fb;
        }

        #edit-image-preview-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        #crop-target-wrap {
            max-height: 420px;
            overflow: hidden;
            border-radius: 12px;
        }

        #crop-target-wrap img {
            max-width: 100%;
            display: block;
        }

        /* Delete modal */
        #deleteTeacherModal .ti-alert-triangle {
            color: #f59e0b !important;
        }
    </style>
@endpush
@section('content')
    @include('frontend.staff.pages.partials.breadcrumb')
    {{-- Toolbar --}}
    <div class="teacher-action-row">
        <div class="teacher-toolbar-left">
            <form method="GET" action="{{ route('staff.teacher.index') }}" id="search-form"
                class="teacher-search-form">
                <div class="teacher-search-wrap">
                    <i class="ti ti-search"></i>
                    <input type="text" name="search" class="form-control teacher-search-input"
                        placeholder="Search Teachers..." value="{{ request('search') }}">
                </div>
            </form>
            {{-- View Toggle --}}
            <div class="btn-group teacher-view-toggle" role="group" id="view-toggle">
                <button type="button" class="btn view-btn active" data-view="list" title="List View">
                    <i class="ti ti-list fs-5"></i>
                </button>
                <button type="button" class="btn view-btn" data-view="grid" title="Grid View">
                    <i class="ti ti-layout-grid fs-5"></i>
                </button>
            </div>
        </div>
        <a href="javascript:void(0)" id="btn-add-contact" class="teacher-add-btn" data-bs-toggle="modal"
            data-bs-target="#addContactModal">
            <i class="ti ti-user-plus"></i> Add Teacher
        </a>
    </div>
    {{-- LIST VIEW --}}
    <div id="view-list" class="teacher-list-card">
        <div class="teacher-table-scroll">
            <table class="table teacher-table align-middle">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Gender</th>
                        <th>Location</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($instructors as $instructor)
                        <tr>
                            <td>{{ $instructors->firstItem() + $loop->index }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="{{ $instructor->image == 'no-img.jpg'
                                        ? ($instructor->gender == 'male'
                                            ? asset('\admin\assets\dist\images\profile\user-1.jpg')
                                            : asset('\admin\assets\dist\images\profile\user-2.jpg'))
                                        : asset($instructor->image) }}"
                                        alt="avatar" class="rounded-circle object-fit-cover" width="35"
                                        height="35">
                                    <div class="ms-3">
                                        <div class="user-meta-info">
                                            <h6 class="user-name mb-0">{{ $instructor->name }}</h6>
                                            <span
                                                class="user-work fs-3 text-muted">{{ $instructor->khmer_name ?? '-' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $instructor->email }}</td>
                            <td>{{ $instructor->phone ?? '-' }}</td>
                            <td>
                                <span
                                    class="badge {{ $instructor->gender === 'male' ? 'bg-light-primary text-primary' : 'bg-light-danger text-danger' }} rounded-pill">
                                    {{ ucfirst($instructor->gender ?? '-') }}
                                </span>
                            </td>
                            <td>{{ $instructor->location ?? '-' }}</td>
                            <td>
                                <div class="action-btn">
                                    <a href="javascript:void(0)" class="text-info btn-edit-teacher" title="Edit"
                                        data-id="{{ $instructor->id }}" data-name="{{ $instructor->name }}"
                                        data-khmer-name="{{ $instructor->khmer_name }}"
                                        data-email="{{ $instructor->email }}" data-phone="{{ $instructor->phone }}"
                                        data-alternate-phone="{{ $instructor->alternate_phone }}"
                                        data-dob="{{ $instructor->dob }}" data-gender="{{ $instructor->gender }}"
                                        data-nationality="{{ $instructor->nationality }}"
                                        data-location="{{ $instructor->location }}"
                                        data-headline="{{ $instructor->headline }}"
                                        data-designation="{{ $instructor->designation }}"
                                        data-bio="{{ $instructor->bio }}"
                                        data-expertise="{{ $instructor->expertise ? json_encode($instructor->expertise) : '[]' }}"
                                        data-facebook="{{ $instructor->facebook }}" data-x="{{ $instructor->x }}"
                                        data-linkedin="{{ $instructor->linkedin }}"
                                        data-website="{{ $instructor->website }}" data-github="{{ $instructor->github }}"
                                        data-instagram="{{ $instructor->instagram }}"
                                        data-telegram="{{ $instructor->telegram }}"
                                        data-tiktok="{{ $instructor->tiktok }}" data-youtube="{{ $instructor->youtube }}"
                                        data-image="{{ $instructor->image && $instructor->image !== 'no-img.jpg' ? asset($instructor->image) : '' }}">
                                        <i class="ti ti-edit fs-5"></i>
                                    </a>
                                    <a href="javascript:void(0)" class="text-danger ms-2 btn-delete-teacher" title="Delete"
                                        data-id="{{ $instructor->id }}" data-name="{{ $instructor->name }}">
                                        <i class="ti ti-trash fs-5"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                <i class="ti ti-users-off fs-6 me-1"></i>
                                No teachers found{{ request('search') ? ' for "' . request('search') . '"' : '' }}.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($instructors->hasPages())
            {{ $instructors->links('frontend.staff.pages.pagination.custom') }}
        @endif
    </div>
    {{-- GRID VIEW --}}
    <div id="view-grid" style="display:none;">
        <div class="row g-3">
            @forelse($instructors as $instructor)
                <div class="col-sm-6 col-md-4 col-xl-3">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-body d-flex flex-column align-items-center text-center pt-4">
                            <img src="{{ $instructor->image == 'no-img.jpg'
                                ? ($instructor->gender == 'male'
                                    ? asset('\admin\assets\dist\images\profile\user-1.jpg')
                                    : asset('\admin\assets\dist\images\profile\user-2.jpg'))
                                : asset($instructor->image) }}"
                                alt="avatar"
                                class="rounded-circle object-fit-cover mb-3 border border-3 border-light shadow-sm"
                                width="72" height="72">
                            <h6 class="mb-0 fw-semibold">{{ $instructor->name }}</h6>
                            @if ($instructor->khmer_name)
                                <small class="text-muted">{{ $instructor->khmer_name }}</small>
                            @endif
                            <span
                                class="badge {{ $instructor->gender === 'male' ? 'bg-light-primary text-primary' : 'bg-light-danger text-danger' }} rounded-pill mt-2">
                                {{ ucfirst($instructor->gender ?? '-') }}
                            </span>
                            <hr class="w-100 my-3">
                            <div class="w-100 text-start small text-muted">
                                <div class="mb-1"><i class="ti ti-mail me-1"></i> {{ $instructor->email }}</div>
                                <div class="mb-1"><i class="ti ti-phone me-1"></i> {{ $instructor->phone ?? '-' }}
                                </div>
                                <div><i class="ti ti-map-pin me-1"></i> {{ $instructor->location ?? '-' }}</div>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-0 d-flex justify-content-center gap-2 pb-3">
                            <a href="javascript:void(0)" class="btn btn-sm btn-outline-info btn-edit-teacher"
                                data-id="{{ $instructor->id }}" data-name="{{ $instructor->name }}"
                                data-khmer-name="{{ $instructor->khmer_name }}" data-email="{{ $instructor->email }}"
                                data-phone="{{ $instructor->phone }}"
                                data-alternate-phone="{{ $instructor->alternate_phone }}"
                                data-dob="{{ $instructor->dob }}" data-gender="{{ $instructor->gender }}"
                                data-nationality="{{ $instructor->nationality }}"
                                data-location="{{ $instructor->location }}" data-headline="{{ $instructor->headline }}"
                                data-designation="{{ $instructor->designation }}" data-bio="{{ $instructor->bio }}"
                                data-expertise="{{ $instructor->expertise ? json_encode($instructor->expertise) : '[]' }}"
                                data-facebook="{{ $instructor->facebook }}" data-x="{{ $instructor->x }}"
                                data-linkedin="{{ $instructor->linkedin }}" data-website="{{ $instructor->website }}"
                                data-github="{{ $instructor->github }}" data-instagram="{{ $instructor->instagram }}"
                                data-telegram="{{ $instructor->telegram }}" data-tiktok="{{ $instructor->tiktok }}"
                                data-youtube="{{ $instructor->youtube }}"
                                data-image="{{ $instructor->image && $instructor->image !== 'no-img.jpg' ? asset($instructor->image) : '' }}">
                                <i class="ti ti-edit me-1"></i> Edit
                            </a>
                            <a href="javascript:void(0)" class="btn btn-sm btn-outline-danger btn-delete-teacher"
                                data-id="{{ $instructor->id }}" data-name="{{ $instructor->name }}">
                                <i class="ti ti-trash me-1"></i> Delete
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5 text-muted">
                    <i class="ti ti-users-off fs-1 d-block mb-2"></i>
                    No teachers found{{ request('search') ? ' for "' . request('search') . '"' : '' }}.
                </div>
            @endforelse
        </div>
        @if ($instructors->hasPages())
            <div class="mt-3">
                {{ $instructors->links('frontend.staff.pages.pagination.custom') }}
            </div>
        @endif
    </div>
    {{-- CREATE MODAL (unchanged) --}}
    <div class="modal fade" id="addContactModal" tabindex="-1" aria-labelledby="addContactModalTitle"
        style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header d-flex align-items-center">
                    <h5 class="modal-title">Add Teacher</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="add-contact-box">
                        <div class="add-contact-content">
                            <form method="POST" action="{{ route('staff.teacher.store') }}" id="addContactModalTitle"
                                enctype="multipart/form-data">
                                @csrf
                                {{-- Lets the reopen-on-error script (bottom of page) know it was THIS
                                     form, not the Edit form, that failed validation. --}}
                                <input type="hidden" name="_modal" value="add">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="c-name" class="form-label fw-semibold">Full Name (English) <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" id="c-name" name="name"
                                                class="form-control @error('name') is-invalid @enderror"
                                                placeholder="e.g. John Doe" value="{{ old('name') }}"
                                                pattern="[A-Za-z'.\-\s]+"
                                                title="English letters only (spaces, apostrophes, hyphens and periods are OK)">
                                            @error('name')
                                                <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="c-khmer-name" class="form-label fw-semibold">Full Name
                                                (Khmer)</label>
                                            <input type="text" id="c-khmer-name" name="khmer_name"
                                                class="form-control @error('khmer_name') is-invalid @enderror"
                                                placeholder="e.g. គ្រូបង្រៀន"
                                                value="{{ old('khmer_name') }}"
                                                pattern="[ក-៿\s]+"
                                                title="Khmer script only">
                                            @error('khmer_name')
                                                <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="c-email" class="form-label fw-semibold">Email <span
                                                    class="text-danger">*</span></label>
                                            <input type="email" id="c-email" name="email"
                                                class="form-control @error('email') is-invalid @enderror"
                                                placeholder="e.g. teacher@gmail.com" value="{{ old('email') }}">
                                            @error('email')
                                                <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="c-phone" class="form-label fw-semibold">Phone <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" id="c-phone" name="phone" inputmode="tel"
                                                maxlength="20" required pattern="[0-9+\-\s]{8,20}"
                                                title="Phone number: digits only, optionally starting with +, 8–20 characters"
                                                class="form-control @error('phone') is-invalid @enderror"
                                                placeholder="e.g. 012000000" value="{{ old('phone') }}">
                                            @error('phone')
                                                <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="c-dob" class="form-label fw-semibold">Date of Birth</label>
                                            <input type="date" id="c-dob" name="dob" class="form-control"
                                                max="{{ date('Y-m-d') }}" value="{{ old('dob') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="c-gender" class="form-label fw-semibold">Gender <span
                                                    class="text-danger">*</span></label>
                                            <select id="c-gender" name="gender"
                                                class="form-select @error('gender') is-invalid @enderror">
                                                <option value="" disabled selected>Select Gender</option>
                                                <option value="male" {{ old('gender') === 'male' ? 'selected' : '' }}>
                                                    Male</option>
                                                <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>
                                                    Female</option>
                                            </select>
                                            @error('gender')
                                                <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="c-password" class="form-label fw-semibold">Password <span
                                                    class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="password" id="c-password" name="password"
                                                    class="form-control @error('password') is-invalid @enderror"
                                                    placeholder="Min. 8 characters">
                                                <button type="button" class="btn btn-outline-secondary toggle-password"
                                                    data-target="#c-password">
                                                    <i class="ti ti-eye"></i>
                                                </button>
                                            </div>
                                            @error('password')
                                                <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="c-password-confirm" class="form-label fw-semibold">Confirm
                                                Password <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="password" id="c-password-confirm"
                                                    name="password_confirmation" class="form-control"
                                                    placeholder="Re-enter password">
                                                <button type="button" class="btn btn-outline-secondary toggle-password"
                                                    data-target="#c-password-confirm">
                                                    <i class="ti ti-eye"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="c-location" class="form-label fw-semibold">Location</label>
                                            <input type="text" id="c-location" name="location" class="form-control"
                                                placeholder="e.g. Phnom Penh" value="{{ old('location') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="c-image" class="form-label fw-semibold">Profile Image</label>
                                            <input type="file" id="c-image" name="image" class="form-control"
                                                accept="image/*">
                                            <small class="text-muted">Accepted: JPG, PNG, WEBP</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="c-document" class="form-label fw-semibold">Document</label>
                                            <input type="file" id="c-document" name="document" class="form-control"
                                                accept="image/*,.pdf">
                                            <small class="text-muted">Accepted: JPG, PNG, PDF</small>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="btn-add" class="btn btn-success rounded-pill px-4"
                        onclick="document.getElementById('addContactModalTitle').submit();">
                        <i class="ti ti-user-plus me-1"></i> Add Teacher
                    </button>
                    <button class="btn btn-danger teacher-btn-ghost-danger rounded-pill px-4" data-bs-dismiss="modal">
                        <i class="ti ti-x me-1"></i> Discard
                    </button>
                </div>
            </div>
        </div>
    </div>
    {{-- EDIT MODAL (upgraded) --}}
    <div class="modal fade" id="editContactModal" tabindex="-1" aria-hidden="true" style="display:none;">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header d-flex align-items-center">
                    <h5 class="modal-title">Edit Teacher</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul class="nav nav-tabs edit-tabs mb-3" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-basic"
                                type="button" role="tab">Basic Info</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-profile" type="button"
                                role="tab">Profile &amp; Bio</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-social" type="button"
                                role="tab">Social Links</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-media" type="button"
                                role="tab">Media &amp; Security</button>
                        </li>
                    </ul>

                    <form method="POST" id="editTeacherForm" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        {{-- Same purpose as the Add form's "_modal" field: tells the
                             reopen-on-error script this is the Edit form, and carries the
                             instructor id across the redirect so the form's action/data
                             can be rebuilt (the modal is normally populated from JS
                             dataset attributes on click, which is lost on a full reload). --}}
                        <input type="hidden" name="_modal" value="edit">
                        <input type="hidden" name="_teacher_id" id="edit-teacher-id" value="{{ old('_teacher_id') }}">
                        <div class="tab-content">
                            {{-- Basic Info --}}
                            <div class="tab-pane fade show active" id="tab-basic" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Full Name (English) <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="name" id="edit-name"
                                                class="form-control @error('name') is-invalid @enderror"
                                                placeholder="e.g. John Doe"
                                                pattern="[A-Za-z'.\-\s]+"
                                                title="English letters only (spaces, apostrophes, hyphens and periods are OK)"
                                                value="{{ old('name') }}">
                                            @error('name')
                                                <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Full Name (Khmer)</label>
                                            <input type="text" name="khmer_name" id="edit-khmer-name"
                                                class="form-control @error('khmer_name') is-invalid @enderror"
                                                placeholder="e.g. គ្រូបង្រៀន"
                                                pattern="[ក-៿\s]+"
                                                title="Khmer script only"
                                                value="{{ old('khmer_name') }}">
                                            @error('khmer_name')
                                                <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Email <span
                                                    class="text-danger">*</span></label>
                                            <input type="email" name="email" id="edit-email"
                                                class="form-control @error('email') is-invalid @enderror"
                                                placeholder="e.g. teacher@gmail.com" value="{{ old('email') }}">
                                            @error('email')
                                                <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Phone <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="phone" id="edit-phone" inputmode="tel"
                                                maxlength="20" required pattern="[0-9+\-\s]{8,20}"
                                                title="Phone number: digits only, optionally starting with +, 8–20 characters"
                                                class="form-control @error('phone') is-invalid @enderror"
                                                placeholder="e.g. 012000000" value="{{ old('phone') }}">
                                            @error('phone')
                                                <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Alternate Phone</label>
                                            <input type="text" name="alternate_phone" id="edit-alternate-phone"
                                                class="form-control" placeholder="e.g. 097000000"
                                                value="{{ old('alternate_phone') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Date of Birth</label>
                                            <input type="date" name="dob" id="edit-dob" class="form-control"
                                                max="{{ date('Y-m-d') }}" value="{{ old('dob') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Gender <span
                                                    class="text-danger">*</span></label>
                                            <select name="gender" id="edit-gender"
                                                class="form-select @error('gender') is-invalid @enderror">
                                                <option value="" disabled>Select Gender</option>
                                                <option value="male" {{ old('gender') === 'male' ? 'selected' : '' }}>Male</option>
                                                <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>Female</option>
                                            </select>
                                            @error('gender')
                                                <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Nationality</label>
                                            <input type="text" name="nationality" id="edit-nationality"
                                                class="form-control" placeholder="e.g. Khmer"
                                                value="{{ old('nationality') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Location</label>
                                            <input type="text" name="location" id="edit-location"
                                                class="form-control" placeholder="e.g. Phnom Penh"
                                                value="{{ old('location') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Profile & Bio --}}
                            <div class="tab-pane fade" id="tab-profile" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Headline</label>
                                            <input type="text" name="headline" id="edit-headline"
                                                class="form-control" placeholder="e.g. Senior Frontend Developer">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Designation</label>
                                            <input type="text" name="designation" id="edit-designation"
                                                class="form-control" placeholder="e.g. UI/UX Specialist">
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Bio</label>
                                    <textarea name="bio" id="edit-bio" rows="4" class="form-control"
                                        placeholder="A short professional background..."></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Expertise</label>
                                    <div class="input-group mb-2">
                                        <input type="text" id="edit-expertise-input" class="form-control"
                                            placeholder="e.g. React.js — press Enter or click Add">
                                        <button type="button" class="btn btn-outline-primary" id="btn-add-expertise">
                                            <i class="ti ti-plus"></i> Add
                                        </button>
                                    </div>
                                    <ul class="list-group" id="edit-expertise-list"></ul>
                                    <div id="edit-expertise-hidden-inputs"></div>
                                    <small class="text-muted">Type a skill and press Enter or click Add. Click the
                                        <i class="ti ti-x"></i> on an item to remove it.</small>
                                </div>
                            </div>

                            {{-- Social Links --}}
                            <div class="tab-pane fade" id="tab-social" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold"><i
                                                    class="ti ti-brand-facebook me-1"></i> Facebook</label>
                                            <input type="url" name="facebook" id="edit-facebook"
                                                class="form-control" placeholder="https://facebook.com/username">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold"><i class="ti ti-brand-x me-1"></i> X
                                                (Twitter)</label>
                                            <input type="url" name="x" id="edit-x" class="form-control"
                                                placeholder="https://x.com/username">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold"><i
                                                    class="ti ti-brand-linkedin me-1"></i> LinkedIn</label>
                                            <input type="url" name="linkedin" id="edit-linkedin"
                                                class="form-control" placeholder="https://linkedin.com/in/username">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold"><i class="ti ti-world me-1"></i>
                                                Website</label>
                                            <input type="url" name="website" id="edit-website" class="form-control"
                                                placeholder="https://example.com">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold"><i class="ti ti-brand-github me-1"></i>
                                                GitHub</label>
                                            <input type="url" name="github" id="edit-github" class="form-control"
                                                placeholder="https://github.com/username">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold"><i
                                                    class="ti ti-brand-instagram me-1"></i> Instagram</label>
                                            <input type="url" name="instagram" id="edit-instagram"
                                                class="form-control" placeholder="https://instagram.com/username">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold"><i
                                                    class="ti ti-brand-telegram me-1"></i> Telegram</label>
                                            <input type="text" name="telegram" id="edit-telegram"
                                                class="form-control" placeholder="@username">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold"><i class="ti ti-brand-tiktok me-1"></i>
                                                TikTok</label>
                                            <input type="text" name="tiktok" id="edit-tiktok" class="form-control"
                                                placeholder="@username">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold"><i class="ti ti-brand-youtube me-1"></i>
                                                YouTube</label>
                                            <input type="text" name="youtube" id="edit-youtube" class="form-control"
                                                placeholder="Channel URL">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Media & Security --}}
                            <div class="tab-pane fade" id="tab-media" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Profile Image</label>
                                            <div class="d-flex align-items-center gap-3 mb-2">
                                                <div id="edit-image-preview-wrap">
                                                    <img id="edit-image-preview" src="" alt="Preview"
                                                        style="display:none;">
                                                    <i class="ti ti-user fs-3 text-muted" id="edit-image-placeholder"></i>
                                                </div>
                                                <input type="file" id="edit-image-input" name="image"
                                                    class="form-control" accept="image/*">
                                            </div>
                                            <small class="text-muted">Leave blank to keep current image. Selecting a
                                                new one opens the cropper.</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Document</label>
                                            <input type="file" name="document" class="form-control"
                                                accept="image/*,.pdf">
                                            <small class="text-muted">Leave blank to keep current document</small>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">New Password</label>
                                            <div class="input-group">
                                                <input type="password" name="password" id="edit-password"
                                                    class="form-control" placeholder="Leave blank to keep current">
                                                <button type="button" class="btn btn-outline-secondary toggle-password"
                                                    data-target="#edit-password"><i class="ti ti-eye"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Confirm New Password</label>
                                            <div class="input-group">
                                                <input type="password" id="edit-password-confirm"
                                                    name="password_confirmation" class="form-control"
                                                    placeholder="Re-enter new password">
                                                <button type="button" class="btn btn-outline-secondary toggle-password"
                                                    data-target="#edit-password-confirm"><i
                                                        class="ti ti-eye"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success rounded-pill px-4"
                        onclick="document.getElementById('editTeacherForm').submit();">
                        <i class="ti ti-device-floppy me-1"></i> Save Changes
                    </button>
                    <button class="btn btn-danger teacher-btn-ghost-danger rounded-pill px-4" data-bs-dismiss="modal">
                        <i class="ti ti-x me-1"></i> Discard
                    </button>
                </div>
            </div>
        </div>
    </div>
    {{-- CROP MODAL --}}
    <div class="modal fade" id="cropModal" tabindex="-1" aria-hidden="true" style="display:none;">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Crop Profile Image</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="crop-target-wrap">
                        <img id="crop-target" src="" alt="Crop target">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="btn-apply-crop">
                        <i class="ti ti-crop me-1"></i> Apply Crop
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
    {{-- DELETE MODAL --}}
    <div class="modal fade" id="deleteTeacherModal" tabindex="-1" aria-hidden="true" style="display:none;">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header d-flex align-items-center">
                    <h5 class="modal-title text-danger">
                        <i class="ti ti-trash me-2"></i> Delete Teacher
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <i class="ti ti-alert-triangle text-warning" style="font-size: 3rem;"></i>
                    <h5 class="mt-3">Are you sure?</h5>
                    <p class="text-muted mb-0">You are about to delete <strong id="delete-teacher-name"></strong>. <br>
                        This action cannot be undone.</p>
                </div>
                <div class="modal-footer justify-content-center">
                    <form id="deleteTeacherForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger rounded-pill px-4">
                            <i class="ti ti-trash me-1"></i> Yes, Delete
                        </button>
                    </form>
                    <button class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">
                        <i class="ti ti-x me-1"></i> Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
    {{-- Reopen modal on validation error --}}
    @if ($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Was hardcoded to always reopen the Add modal, so a failed Edit
                // submission (e.g. an invalid phone/name) silently popped up an
                // empty "Add Teacher" modal instead of showing the Edit modal
                // with the error and the teacher's data still in place.
                @if (old('_modal') === 'edit')
                    var editId = @json(old('_teacher_id'));
                    if (editId) {
                        document.getElementById('editTeacherForm').action = `/staff/teacher/${editId}`;
                    }
                    var modal = new bootstrap.Modal(document.getElementById('editContactModal'));
                    modal.show();
                @else
                    var modal = new bootstrap.Modal(document.getElementById('addContactModal'));
                    modal.show();
                @endif
            });
        </script>
    @endif
@endsection
@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>
    <script>
        // ─── View Toggle (persisted in localStorage) ───────────────────────────────
        const VIEW_KEY = 'teacher_view_preference';
        const listView = document.getElementById('view-list');
        const gridView = document.getElementById('view-grid');
        const viewBtns = document.querySelectorAll('.view-btn');

        function setView(view) {
            if (view === 'grid') {
                listView.style.display = 'none';
                gridView.style.display = 'block';
            } else {
                listView.style.display = 'block';
                gridView.style.display = 'none';
            }
            viewBtns.forEach(btn => {
                btn.classList.toggle('active', btn.dataset.view === view);
            });
            localStorage.setItem(VIEW_KEY, view);
        }
        // Restore saved preference
        const savedView = localStorage.getItem(VIEW_KEY) || 'list';
        setView(savedView);
        viewBtns.forEach(btn => {
            btn.addEventListener('click', () => setView(btn.dataset.view));
        });
        // ─── Password Toggle ────────────────────────────────────────────────────────
        $(document).on('click', '.toggle-password', function() {
            const target = $($(this).data('target'));
            const isPassword = target.attr('type') === 'password';
            target.attr('type', isPassword ? 'text' : 'password');
            $(this).find('i').toggleClass('ti-eye ti-eye-off');
        });
        // ─── Name Script Restriction ───────────────────────────────────────────────
        // Full Name (English) and Full Name (Khmer) had no restriction at all on
        // either the Add or Edit form, so either field silently accepted either
        // script. Strip disallowed characters live as the user types.
        function restrictToPattern(inputId, allowedCharsRegex) {
            const input = document.getElementById(inputId);
            if (!input) return;
            input.addEventListener('input', function() {
                const cursor = this.selectionStart;
                const before = this.value;
                this.value = this.value.split('').filter(ch => allowedCharsRegex.test(ch)).join('');
                const removed = before.length - this.value.length;
                if (removed > 0 && cursor !== null) {
                    this.setSelectionRange(cursor - removed, cursor - removed);
                }
            });
        }
        const ENGLISH_NAME_CHAR = /[A-Za-z'.\-\s]/;
        const KHMER_NAME_CHAR = /[ក-៿\s]/;
        restrictToPattern('c-name', ENGLISH_NAME_CHAR);
        restrictToPattern('c-khmer-name', KHMER_NAME_CHAR);
        restrictToPattern('edit-name', ENGLISH_NAME_CHAR);
        restrictToPattern('edit-khmer-name', KHMER_NAME_CHAR);
        // Email had no script restriction either, so it silently accepted
        // Khmer characters (e.g. pasted from the Khmer name field) and the
        // form would submit looking "filled in" even though it was never a
        // real, deliverable address. Restrict it live to plain ASCII,
        // mirroring the two restrictions above.
        const EMAIL_CHAR = /[\x00-\x7F]/;
        restrictToPattern('c-email', EMAIL_CHAR);
        restrictToPattern('edit-email', EMAIL_CHAR);
        // ─── Delete ─────────────────────────────────────────────────────────────────
        document.querySelectorAll('.btn-delete-teacher').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const id = this.dataset.id;
                const name = this.dataset.name;
                document.getElementById('delete-teacher-name').textContent = name;
                document.getElementById('deleteTeacherForm').action = `/staff/teacher/${id}`;
                new bootstrap.Modal(document.getElementById('deleteTeacherModal')).show();
            });
        });

        // ─── Expertise "add task" list ──────────────────────────────────────────────
        const expertiseList = document.getElementById('edit-expertise-list');
        const expertiseHidden = document.getElementById('edit-expertise-hidden-inputs');
        const expertiseInput = document.getElementById('edit-expertise-input');

        function renderExpertiseHidden() {
            expertiseHidden.innerHTML = '';
            expertiseList.querySelectorAll('li').forEach(li => {
                const hidden = document.createElement('input');
                hidden.type = 'hidden';
                hidden.name = 'expertise[]';
                hidden.value = li.dataset.value;
                expertiseHidden.appendChild(hidden);
            });
        }

        function addExpertiseItem(text) {
            text = text.trim();
            if (!text) return;
            const li = document.createElement('li');
            li.className = 'list-group-item d-flex justify-content-between align-items-center';
            li.dataset.value = text;

            const span = document.createElement('span');
            span.textContent = text;

            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'btn btn-sm btn-outline-danger btn-remove-expertise';
            btn.innerHTML = '<i class="ti ti-x"></i>';
            btn.addEventListener('click', function() {
                li.remove();
                renderExpertiseHidden();
            });

            li.appendChild(span);
            li.appendChild(btn);
            expertiseList.appendChild(li);
            renderExpertiseHidden();
        }

        document.getElementById('btn-add-expertise').addEventListener('click', function() {
            addExpertiseItem(expertiseInput.value);
            expertiseInput.value = '';
            expertiseInput.focus();
        });
        expertiseInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                addExpertiseItem(expertiseInput.value);
                expertiseInput.value = '';
            }
        });

        // ─── Cropper.js — square (1:1) profile image crop ──────────────────────────
        let cropper = null;
        let cropModalInstance = null;
        const editImageInput = document.getElementById('edit-image-input');
        const cropTarget = document.getElementById('crop-target');
        const editImagePreview = document.getElementById('edit-image-preview');
        const editImagePlaceholder = document.getElementById('edit-image-placeholder');
        const cropModalEl = document.getElementById('cropModal');

        editImageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = function(evt) {
                cropTarget.src = evt.target.result;
                cropModalInstance = new bootstrap.Modal(cropModalEl);
                cropModalInstance.show();
            };
            reader.readAsDataURL(file);
        });

        cropModalEl.addEventListener('shown.bs.modal', function() {
            if (cropper) {
                cropper.destroy();
                cropper = null;
            }
            cropper = new Cropper(cropTarget, {
                aspectRatio: 1,
                viewMode: 1,
                autoCropArea: 1,
                responsive: true,
            });
        });

        cropModalEl.addEventListener('hidden.bs.modal', function() {
            if (cropper) {
                cropper.destroy();
                cropper = null;
            }
        });

        document.getElementById('btn-apply-crop').addEventListener('click', function() {
            if (!cropper) return;
            cropper.getCroppedCanvas({
                width: 400,
                height: 400
            }).toBlob(function(blob) {
                const croppedFile = new File([blob], 'profile.jpg', {
                    type: 'image/jpeg'
                });
                const dt = new DataTransfer();
                dt.items.add(croppedFile);
                editImageInput.files = dt.files;

                editImagePreview.src = URL.createObjectURL(blob);
                editImagePreview.style.display = 'block';
                editImagePlaceholder.style.display = 'none';

                cropModalInstance.hide();
            }, 'image/jpeg', 0.9);
        });

        // ─── Edit — populate every field from data-* attributes ────────────────────
        document.querySelectorAll('.btn-edit-teacher').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const id = this.dataset.id;
                const form = document.getElementById('editTeacherForm');
                form.action = `/staff/teacher/${id}`;
                document.getElementById('edit-teacher-id').value = id ?? '';

                // Basic Info
                document.getElementById('edit-name').value = this.dataset.name ?? '';
                document.getElementById('edit-khmer-name').value = this.dataset.khmerName ?? '';
                document.getElementById('edit-email').value = this.dataset.email ?? '';
                document.getElementById('edit-phone').value = this.dataset.phone ?? '';
                document.getElementById('edit-alternate-phone').value = this.dataset.alternatePhone ?? '';
                document.getElementById('edit-dob').value = this.dataset.dob ?? '';
                document.getElementById('edit-gender').value = this.dataset.gender ?? '';
                document.getElementById('edit-nationality').value = this.dataset.nationality ?? '';
                document.getElementById('edit-location').value = this.dataset.location ?? '';

                // Profile & Bio
                document.getElementById('edit-headline').value = this.dataset.headline ?? '';
                document.getElementById('edit-designation').value = this.dataset.designation ?? '';
                document.getElementById('edit-bio').value = this.dataset.bio ?? '';

                expertiseList.innerHTML = '';
                let expertiseArr = [];
                try {
                    expertiseArr = JSON.parse(this.dataset.expertise || '[]');
                } catch (e) {
                    expertiseArr = [];
                }
                expertiseArr.forEach(skill => addExpertiseItem(skill));

                // Social Links
                document.getElementById('edit-facebook').value = this.dataset.facebook ?? '';
                document.getElementById('edit-x').value = this.dataset.x ?? '';
                document.getElementById('edit-linkedin').value = this.dataset.linkedin ?? '';
                document.getElementById('edit-website').value = this.dataset.website ?? '';
                document.getElementById('edit-github').value = this.dataset.github ?? '';
                document.getElementById('edit-instagram').value = this.dataset.instagram ?? '';
                document.getElementById('edit-telegram').value = this.dataset.telegram ?? '';
                document.getElementById('edit-tiktok').value = this.dataset.tiktok ?? '';
                document.getElementById('edit-youtube').value = this.dataset.youtube ?? '';

                // Media — reset file input, show current image (if any) as preview
                editImageInput.value = '';
                if (this.dataset.image) {
                    editImagePreview.src = this.dataset.image;
                    editImagePreview.style.display = 'block';
                    editImagePlaceholder.style.display = 'none';
                } else {
                    editImagePreview.src = '';
                    editImagePreview.style.display = 'none';
                    editImagePlaceholder.style.display = 'block';
                }

                // Password fields always start blank
                document.getElementById('edit-password').value = '';
                document.getElementById('edit-password-confirm').value = '';

                // Always land back on the first tab
                const firstTabBtn = document.querySelector('.edit-tabs .nav-link');
                if (firstTabBtn) new bootstrap.Tab(firstTabBtn).show();

                new bootstrap.Modal(document.getElementById('editContactModal')).show();
            });
        });
    </script>
@endpush
