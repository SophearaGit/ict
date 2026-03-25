@extends('admin.layouts.master')
@section('page_title', isset($page_title) ? $page_title : 'Page Title Here')
@push('styles')
    <style>
        #teacherCardView,
        #teacherTableView {
            display: none;
        }

        .pagination-wrapper {
            margin-top: 25px;
            display: flex;
            justify-content: center;
        }

        .pagination {
            display: flex;
            gap: 6px;
            list-style: none;
            padding: 0;
        }

        .pagination li {
            display: inline-block;
        }

        .pagination li a,
        .pagination li span {
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 36px;
            height: 36px;
            padding: 0 10px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 500;
            border: 1px solid #e5e7eb;
            color: #374151;
            background: #fff;
            text-decoration: none;
            transition: 0.2s;
        }

        .pagination li a:hover {
            background: #f3f4f6;
        }

        .pagination li.active span {
            background: #3d0fe8;
            color: #fff;
            border-color: #3d0fe8;
        }

        .pagination li.disabled span {
            color: #9ca3af;
            background: #f9fafb;
            cursor: not-allowed;
        }

        .content {
            flex: 1;
            padding: 24px;
            min-width: 0;
        }

        .top-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            margin-bottom: 18px;
            flex-wrap: wrap;
        }

        .title {
            font-size: 28px;
            font-weight: 700;
            color: #111827;
        }

        .search-box {
            width: 100%;
            max-width: 460px;
            background: #fff;
            border-radius: 14px;
            padding: 12px 16px;
            display: flex;
            align-items: center;
            gap: 10px;
            border: 1px solid #e5e7eb;
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.04);
        }

        .search-box i {
            color: #6b7280;
        }

        .search-box input {
            border: none;
            outline: none;
            background: transparent;
            width: 100%;
            font-size: 14px;
            color: #374151;
        }

        .top-bottom {
            font-size: 13px;
            color: #6b7280;
            margin-top: -8px;
            margin-bottom: 20px;
            font-weight: 500;
        }

        .toolbar {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            gap: 16px;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }

        .filters {
            display: flex;
            flex-wrap: wrap;
            gap: 14px;
            flex: 1;
        }

        .filter-group {
            display: flex;
            align-items: center;
            gap: 10px;
            position: relative;
        }

        .filter-label {
            font-size: 14px;
            color: #4b5563;
            white-space: nowrap;
            font-weight: 500;
        }

        .filter-box {
            min-width: 170px;
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 11px 14px;
            font-size: 14px;
            color: #374151;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.04);
            cursor: pointer;
            transition: 0.2s ease;
        }

        .filter-box:hover {
            border-color: #c7d2fe;
        }

        .dropdown {
            display: none;
            position: absolute;
            top: calc(100% + 8px);
            left: 115px;
            width: 170px;
            background: #fff;
            border-radius: 10px;
            border: 1px solid #e5e7eb;
            box-shadow: 0 12px 28px rgba(15, 23, 42, 0.10);
            overflow: hidden;
            z-index: 100;
        }

        .dropdown div {
            padding: 11px 14px;
            font-size: 14px;
            cursor: pointer;
            color: #374151;
        }

        .dropdown div:hover {
            background: #f3f4f6;
        }

        .dropdown.show {
            display: block;
        }

        .toolbar-right {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .add-teacher-btn {
            height: 42px;
            padding: 0 16px;
            border: none;
            background: #3d0fe8;
            color: #fff;
            border-radius: 10px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 8px 18px rgba(61, 15, 232, 0.18);
        }

        .add-teacher-btn:hover {
            background: #3210c7;
        }

        .view-toggle {
            display: flex;
            gap: 10px;
            flex-shrink: 0;
        }

        .view-toggle button {
            width: 42px;
            height: 42px;
            border: 1px solid #e5e7eb;
            background: #fff;
            border-radius: 10px;
            cursor: pointer;
            transition: 0.2s ease;
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.04);
        }

        .view-toggle button:hover {
            background: #f9fafb;
        }

        .view-toggle button.active {
            background: #3d0fe8;
            color: #fff;
            border-color: #3d0fe8;
        }

        .showing-text {
            font-size: 14px;
            color: #6b7280;
            margin-bottom: 16px;
        }

        .card-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 20px;
        }

        .teacher-card {
            background: #fff;
            border-radius: 16px;
            padding: 20px 16px 18px;
            position: relative;
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.06);
            border: 1px solid #eef2f7;
            min-height: 270px;
            transition: 0.2s ease;
        }

        .teacher-card:hover {
            transform: translateY(-3px);
        }

        .status-badge {
            position: absolute;
            top: 14px;
            right: 14px;
            padding: 6px 12px;
            font-size: 11px;
            font-weight: 700;
            color: #fff;
            border-radius: 999px;
        }

        .status-active {
            background: #16a34a;
        }

        .status-leave {
            background: #ef4444;
        }

        .teacher-image {
            width: 82px;
            height: 82px;
            border-radius: 50%;
            object-fit: cover;
            display: block;
            margin: 10px auto 14px;
            border: 4px solid #f3f4f6;
        }

        .teacher-name {
            text-align: center;
            font-size: 18px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 12px;
        }

        .teacher-details {
            display: block;
        }

        .info-line {
            display: flex;
            align-items: flex-start;
            gap: 8px;
            font-size: 13px;
            color: #6b7280;
            margin-bottom: 8px;
            line-height: 1.5;
            word-break: break-word;
        }

        .info-line span:first-child {
            width: 16px;
            text-align: center;
            flex-shrink: 0;
        }

        .divider {
            height: 1px;
            background: #eceff4;
            margin: 14px 0;
        }

        .subject-row,
        .rating-row {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            margin-top: 8px;
            flex-wrap: wrap;
        }

        .subject-row .label,
        .rating-row .label {
            color: #6b7280;
            min-width: 52px;
            font-weight: 500;
        }

        .subject-row .value {
            color: #374151;
            font-weight: 600;
            line-height: 1.5;
        }

        .rating-badge {
            background: red;
            color: #fff;
            font-weight: 700;
            font-size: 12px;
            padding: 4px 10px;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .table-wrapper {
            width: 100%;
            overflow-x: auto;
            background: #fff;
            border-radius: 16px;
            border: 1px solid #eef2f7;
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.06);
        }

        .teacher-table {
            width: 100%;
            min-width: 950px;
            border-collapse: collapse;
        }

        .teacher-table th,
        .teacher-table td {
            padding: 14px 16px;
            text-align: left;
            border-bottom: 1px solid #eef2f7;
            font-size: 14px;
            vertical-align: middle;
        }

        .teacher-table th {
            background: #f8fafc;
            color: #374151;
            font-weight: 700;
            white-space: nowrap;
        }

        .teacher-table td img {
            width: 46px;
            height: 46px;
            border-radius: 50%;
            object-fit: cover;
        }

        .status-tag {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 999px;
            color: #fff;
            font-size: 12px;
            font-weight: 700;
        }

        .status-tag.active {
            background: green;
        }

        .status-tag.leave {
            background: #ef4444;
        }

        @media (max-width: 1200px) {
            .card-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }

        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.55);
            display: none;
            align-items: center;
            justify-content: center;
            padding: 20px;
            z-index: 9999;
        }

        .modal-overlay.show {
            display: flex;
        }

        .teacher-modal {
            width: 100%;
            max-width: 760px;
            background: #fff;
            border-radius: 18px;
            padding: 24px;
            box-shadow: 0 20px 60px rgba(15, 23, 42, 0.25);
            animation: popupShow 0.25s ease;
        }

        @keyframes popupShow {
            from {
                opacity: 0;
                transform: translateY(20px) scale(0.96);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .modal-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .modal-header h2 {
            font-size: 22px;
            color: #111827;
        }

        .close-modal {
            width: 40px;
            height: 40px;
            border: none;
            background: #f3f4f6;
            border-radius: 10px;
            cursor: pointer;
            font-size: 16px;
            color: #374151;
        }

        .close-modal:hover {
            background: #e5e7eb;
        }

        .modal-form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 16px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .form-group label {
            font-size: 14px;
            font-weight: 600;
            color: #374151;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            height: 44px;
            border: 1px solid #d1d5db;
            border-radius: 10px;
            padding: 0 14px;
            font-size: 14px;
            outline: none;
            background: #fff;
        }

        .form-group input:focus,
        .form-group select:focus {
            border-color: #3d0fe8;
            box-shadow: 0 0 0 3px rgba(61, 15, 232, 0.10);
        }

        .modal-actions {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            margin-top: 24px;
            flex-wrap: wrap;
        }

        .cancel-btn,
        .save-btn {
            height: 44px;
            padding: 0 18px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
        }

        .cancel-btn {
            background: #e5e7eb;
            color: #374151;
        }

        .save-btn {
            background: #3d0fe8;
            color: #fff;
        }

        .save-btn:hover {
            background: #3210c7;
        }

        @media (max-width: 768px) {
            .teacher-modal {
                padding: 18px;
            }

            .modal-form-grid {
                grid-template-columns: 1fr;
            }

            .modal-actions {
                flex-direction: column;
            }

            .cancel-btn,
            .save-btn {
                width: 100%;
            }
        }

        @media (max-width: 992px) {
            .sidebar {
                width: 90px;
            }

            .content {
                padding: 20px;
            }

            .top-header {
                flex-direction: column;
                align-items: stretch;
            }

            .search-box {
                max-width: 100%;
            }

            .toolbar {
                align-items: stretch;
            }

            .filters {
                width: 100%;
            }

            .card-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                display: none;
            }

            .content {
                padding: 16px;
            }

            .title {
                font-size: 24px;
            }

            .toolbar {
                flex-direction: column;
                align-items: stretch;
            }

            .filters {
                flex-direction: column;
                gap: 12px;
                width: 100%;
            }

            .filter-group {
                flex-direction: row;
                align-items: center;
                width: 100%;
            }

            .filter-label {
                width: 120px;
                min-width: 120px;
                font-size: 13px;
            }

            .filter-box {
                flex: 1;
                min-width: 0;
                width: auto;
            }

            .dropdown {
                top: calc(100% + 8px);
                left: 130px;
                width: calc(100% - 130px);
            }

            .toolbar-right {
                width: 100%;
                justify-content: space-between;
            }

            .card-grid {
                grid-template-columns: 1fr;
            }

            .teacher-table {
                min-width: 700px;
            }

            .status-tag {
                font-size: 11px;
                padding: 4px 8px;
            }

            .status-tag {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                padding: 5px 10px;
                border-radius: 999px;
                font-size: 12px;
                font-weight: 700;
            }

            .teacher-table td {
                white-space: nowrap;
            }
        }

        @media (max-width: 576px) {
            .content {
                padding: 14px;
            }

            .top-bottom,
            .showing-text {
                font-size: 12px;
            }

            .filter-group {
                flex-direction: column;
                align-items: stretch;
                gap: 8px;
            }

            .filter-label {
                width: 100%;
                min-width: 100%;
            }

            .filter-box {
                width: 100%;
            }

            .dropdown {
                left: 0;
                width: 100%;
            }

            .toolbar-right {
                flex-direction: column;
                align-items: stretch;
            }

            .view-toggle {
                width: 100%;
                justify-content: flex-start;
            }

            .add-teacher-btn {
                width: 100%;
                justify-content: center;
            }

            .teacher-table {
                min-width: 700px;
            }

            .status-tag {
                font-size: 11px;
                padding: 4px 8px;
            }

            .status-tag {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                padding: 5px 10px;
                border-radius: 999px;
                font-size: 12px;
                font-weight: 700;
            }

            .teacher-table td {
                white-space: nowrap;
            }
        }
    </style>
@endpush
@section('content')
    <div class="row">
        <div class="col-lg-12 col-md-12 col-12">
            <div class="content">
                <div class="top-header">
                    <div class="title">Teacher</div>
                    <form method="GET">
                        <div class="search-box">
                            <span><i class="fe fe-search"></i></span>
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Search teacher..." />
                        </div>
                    </form>
                </div>

                {{-- <div class="top-bottom">Dashboard &gt; User &gt; Teacher</div> --}}

                <div class="toolbar">
                    <form method="GET" id="filterForm">
                        <input type="hidden" name="subject" id="subjectInput" value="{{ request('subject') }}">
                        <input type="hidden" name="status" id="statusInput" value="{{ request('status') }}">
                        <input type="hidden" name="gender" id="genderInput" value="{{ request('gender') }}">

                        <div class="filters">
                            <div class="filter-group">
                                <div class="filter-label">Filter By Subject:</div>
                                <div class="filter-box" onclick="toggleDropdown(this)">
                                    <span class="selected-text">
                                        {{ request('subject') ?? 'All Subject' }}
                                    </span>
                                    <span>
                                        <i class="fe fe-chevron-down"></i>
                                    </span>
                                </div>
                                <div class="dropdown">
                                    <div onclick="selectOption(this)">All Subject</div>
                                    @foreach ($subjects as $subject)
                                        <div onclick="selectOption(this)">{{ $subject }}</div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="filter-group">
                                <div class="filter-label">Status:</div>
                                <div class="filter-box" onclick="toggleDropdown(this)">
                                    <span class="selected-text">
                                        {{ request('status') ?? 'All Status' }}
                                    </span>
                                    <span> <i class="fe fe-chevron-down"></i></span>
                                </div>
                                <div class="dropdown">
                                    <div onclick="selectOption(this)">All Status</div>
                                    <div onclick="selectOption(this)">Active</div>
                                    <div onclick="selectOption(this)">On_Leave</div>
                                </div>
                            </div>

                            <div class="filter-group">
                                <div class="filter-label">Gender:</div>
                                <div class="filter-box" onclick="toggleDropdown(this)">
                                    <span class="selected-text">
                                        {{ request('gender') ?? 'All Gender' }}
                                    </span>
                                    <span> <i class="fe fe-chevron-down"></i></span>
                                </div>
                                <div class="dropdown">
                                    <div onclick="selectOption(this)">All Gender</div>
                                    <div onclick="selectOption(this)">Male</div>
                                    <div onclick="selectOption(this)">Female</div>
                                </div>
                            </div>
                        </div>
                    </form>
                    {{-- button reset filter --}}
                    @if (request('subject') || request('status') || request('gender'))
                        <button type="button" class="btn bg-danger text-white"
                            onclick="window.location.href='{{ route('admin.instructor.index') }}'">
                            <i class="fe fe-x-circle"></i>
                            Reset Filter
                        </button>
                    @endif

                    <div class="toolbar-right">
                        <button class="add-teacher-btn" type="button">
                            <i class="fe fe-plus-circle"></i>
                            Add Teacher
                        </button>

                        <div class="view-toggle">
                            <button class="active" type="button" onclick="setView('grid', this)">
                                <i class="fe fe-grid"></i>
                            </button>
                            <button type="button" onclick="setView('table', this)">
                                <i class="fe fe-list"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- <div class="showing-text" id="showingText">Showing Teachers</div> -->

                <div class="card-grid" id="teacherCardView">
                    @forelse ($instructors as $teacher)
                        @php
                            $status = $teacher->status ?? 'Active'; // adjust column name
                            $statusClass = $status === 'active' ? 'bg-success' : 'status-leave';

                            $image =
                                $teacher->image == 'no-img.jpg'
                                    ? asset('\default-images\user\both.jpg')
                                    : asset($teacher->image);
                            $subjects =
                                Str::limit($teacher->courses->pluck('title')->unique()->take(3)->implode(', '), 30) ?:
                                '';
                            $rating = $teacher->rating ?? rand(3, 5); // temp fallback
                        @endphp
                        <a href="{{ route('admin.instructor.show.detail', $teacher->id) }}">
                            <div class="teacher-card" data-name="{{ $teacher->name }}" data-email="{{ $teacher->email }}"
                                data-location="{{ $teacher->location ?? 'N/A' }}"
                                data-phone="{{ $teacher->phone ?? 'N/A' }}" data-subject="{{ $subjects }}"
                                data-rating="{{ $rating }}" data-status="{{ $status }}"
                                data-image="{{ $image }}">

                                <div class="status-badge {{ $statusClass }} text-capitalize">
                                    {{ $status }}
                                </div>

                                <img class="teacher-image" src="{{ $image }}" alt="{{ $teacher->name }}">

                                <div class="teacher-details">
                                    <div class="teacher-name text-capitalize">{{ $teacher->name }}</div>

                                    <div class="info-line">
                                        <span>✉</span>
                                        <span>{{ $teacher->email }}</span>
                                    </div>

                                    <div class="info-line">
                                        <span>📍</span>
                                        <span>{{ $teacher->location ?? 'N/A' }}</span>
                                    </div>

                                    <div class="info-line">
                                        <span>📞</span>
                                        <span>{{ $teacher->phone ?? 'N/A' }}</span>
                                    </div>

                                    <div class="divider"></div>

                                    <div class="subject-row">
                                        <span class="label">Subject:</span>
                                        <span class="value">{{ $subjects }}</span>
                                    </div>

                                    <div class="rating-row">
                                        <span class="label">Rating:</span>
                                        <span class="rating-badge">
                                            {{ number_format($rating, 1) }} ★
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div style="grid-column: span 4; text-align: center; padding: 40px 0; color: #6b7280;">
                            No teachers found.
                        </div>
                    @endforelse
                </div>

                <div class="table-wrapper" id="teacherTableView" style="display: none;">
                    <table class="teacher-table">
                        <thead>
                            <tr>
                                <th>Photo</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Location</th>
                                <th>Phone</th>
                                <th>Subject</th>
                                <th>Rating</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody id="teacherTableBody"></tbody>
                    </table>
                </div>

                @if ($instructors->hasPages())
                    <div class="pagination-wrapper">
                        <ul class="pagination">

                            {{-- Previous --}}
                            @if ($instructors->onFirstPage())
                                <li class="disabled"><span>&laquo;</span></li>
                            @else
                                <li>
                                    <a href="{{ $instructors->previousPageUrl() }}">&laquo;</a>
                                </li>
                            @endif

                            {{-- Pages --}}
                            @foreach ($instructors->getUrlRange(1, $instructors->lastPage()) as $page => $url)
                                @if ($page == $instructors->currentPage())
                                    <li class="active"><span>{{ $page }}</span></li>
                                @else
                                    <li><a href="{{ $url }}">{{ $page }}</a></li>
                                @endif
                            @endforeach

                            {{-- Next --}}
                            @if ($instructors->hasMorePages())
                                <li>
                                    <a href="{{ $instructors->nextPageUrl() }}">&raquo;</a>
                                </li>
                            @else
                                <li class="disabled"><span>&raquo;</span></li>
                            @endif

                        </ul>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Add Teacher Modal -->
    <div class="modal-overlay" id="teacherModal">
        <div class="teacher-modal">
            <div class="modal-header">
                <h2>Add Teacher</h2>
                <button type="button" class="close-modal" id="closeModalBtn">
                    <i class="fe fe-x"></i>
                </button>
            </div>

            <form id="addTeacherForm">
                <div class="modal-form-grid">
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" id="teacherName" placeholder="Enter teacher name" required>
                    </div>

                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" id="teacherEmail" placeholder="Enter email" required>
                    </div>

                    <div class="form-group">
                        <label>Location</label>
                        <input type="text" id="teacherLocation" placeholder="Enter location" required>
                    </div>

                    <div class="form-group">
                        <label>Phone</label>
                        <input type="text" id="teacherPhone" placeholder="Enter phone number" required>
                    </div>

                    <div class="form-group">
                        <label>Subject</label>
                        <input type="text" id="teacherSubject" placeholder="Enter subject" required>
                    </div>

                    <div class="form-group">
                        <label>Rating</label>
                        <input type="number" id="teacherRating" min="0" max="5" step="0.1"
                            placeholder="Enter rating" required>
                    </div>

                    <div class="form-group">
                        <label>Status</label>
                        <select id="teacherStatus" required>
                            <option value="Active">Active</option>
                            <option value="On Leave">On Leave</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Photo URL</label>
                        <input type="text" id="teacherImage" placeholder="Enter image URL">
                    </div>
                </div>

                <div class="modal-actions">
                    <button type="button" class="cancel-btn" id="cancelModalBtn">Cancel</button>
                    <button type="submit" class="save-btn">Save Teacher</button>
                </div>
            </form>
        </div>
    </div>

@endsection
@push('scripts')
    <script>
        function toggleDropdown(element) {
            const dropdown = element.nextElementSibling;
            const isOpen = dropdown.classList.contains("show");

            document.querySelectorAll(".dropdown").forEach(function(d) {
                d.classList.remove("show");
            });

            if (!isOpen) {
                dropdown.classList.add("show");
            }
        }

        function selectOption(option) {
            const dropdown = option.parentElement;
            const filterBox = dropdown.previousElementSibling;
            const label = filterBox.querySelector(".selected-text");

            const value = option.innerText;
            label.innerText = value;

            // 🔥 detect which filter
            const group = option.closest(".filter-group").querySelector(".filter-label").innerText;

            if (group.includes("Subject")) {
                document.getElementById("subjectInput").value = value === "All Subject" ? "" : value;
            }

            if (group.includes("Status")) {
                document.getElementById("statusInput").value = value === "All Status" ? "" : value;
            }

            if (group.includes("Gender")) {
                document.getElementById("genderInput").value = value === "All Gender" ? "" : value;
            }

            dropdown.classList.remove("show");

            // 🚀 AUTO SUBMIT
            document.getElementById("filterForm").submit();
        }

        document.addEventListener("click", function(e) {
            if (!e.target.closest(".filter-group")) {
                document.querySelectorAll(".dropdown").forEach(function(d) {
                    d.classList.remove("show");
                });
            }
        });

        function buildTeacherTable() {
            const teacherCards = document.querySelectorAll("#teacherCardView .teacher-card");
            const tableBody = document.getElementById("teacherTableBody");
            tableBody.innerHTML = "";

            teacherCards.forEach(function(card) {
                const name = card.dataset.name;
                const email = card.dataset.email;
                const location = card.dataset.location;
                const phone = card.dataset.phone;
                const subject = card.dataset.subject;
                const rating = card.dataset.rating;
                const status = card.dataset.status;
                const image = card.dataset.image;

                const statusClass = status === "Active" ? "active" : "leave";

                const row = `
                <tr>
                    <td><img src="${image}" alt="${name}"></td>
                    <td>${name}</td>
                    <td>${email}</td>
                    <td>${location}</td>
                    <td>${phone}</td>
                    <td>${subject}</td>
                    <td><span class="rating-badge">${rating} ★</span></td>
                    <td><span class="status-tag ${statusClass}">${status}</span></td>
                </tr>
            `;

                tableBody.insertAdjacentHTML("beforeend", row);
            });
        }

        function setView(type, btn) {
            const cardView = document.getElementById("teacherCardView");
            const tableView = document.getElementById("teacherTableView");
            const buttons = document.querySelectorAll(".view-toggle button");

            buttons.forEach(function(button) {
                button.classList.remove("active");
            });

            btn.classList.add("active");

            if (type === "table") {
                buildTeacherTable();
                cardView.style.display = "none";
                tableView.style.display = "block";
            } else {
                cardView.style.display = "grid";
                tableView.style.display = "none";
            }

            // 🔥 SAVE TO LOCAL STORAGE
            localStorage.setItem("teacherViewMode", type);
        }

        document.addEventListener("DOMContentLoaded", function() {
            const savedView = localStorage.getItem("teacherViewMode") || "grid";

            const gridBtn = document.querySelector(".view-toggle button:first-child");
            const tableBtn = document.querySelector(".view-toggle button:last-child");

            if (savedView === "table") {
                setView("table", tableBtn);
            } else {
                setView("grid", gridBtn);
            }
        });

        // buildTeacherTable();

        $(document).ready(function() {
            const modal = $("#teacherModal");

            $(".add-teacher-btn").on("click", function() {
                modal.addClass("show");
            });

            $("#closeModalBtn, #cancelModalBtn").on("click", function() {
                modal.removeClass("show");
            });

            $("#teacherModal").on("click", function(e) {
                if (e.target === this) {
                    modal.removeClass("show");
                }
            });

            $("#addTeacherForm").on("submit", function(e) {
                e.preventDefault();

                let name = $("#teacherName").val().trim();
                let email = $("#teacherEmail").val().trim();
                let location = $("#teacherLocation").val().trim();
                let phone = $("#teacherPhone").val().trim();
                let subject = $("#teacherSubject").val().trim();
                let rating = $("#teacherRating").val().trim();
                let status = $("#teacherStatus").val();
                let image = $("#teacherImage").val().trim();

                if (image === "") {
                    image = "https://i.pravatar.cc/150?img=25";
                }

                let statusBadgeClass = status === "Active" ? "status-active" : "status-leave";

                let newTeacherCard = `
                <div class="teacher-card"
                    data-name="${name}"
                    data-email="${email}"
                    data-location="${location}"
                    data-phone="${phone}"
                    data-subject="${subject}"
                    data-rating="${rating}"
                    data-status="${status}"
                    data-image="${image}">

                    <div class="status-badge ${statusBadgeClass}">${status}</div>
                    <img class="teacher-image" src="${image}" alt="${name}">

                    <div class="teacher-details">
                        <div class="teacher-name">${name}</div>
                        <div class="info-line"><span>✉</span><span>${email}</span></div>
                        <div class="info-line"><span>📍</span><span>${location}</span></div>
                        <div class="info-line"><span>📞</span><span>${phone}</span></div>
                        <div class="divider"></div>
                        <div class="subject-row">
                            <span class="label">Subject:</span>
                            <span class="value">${subject}</span>
                        </div>
                        <div class="rating-row">
                            <span class="label">Rating:</span>
                            <span class="rating-badge">${rating} ★</span>
                        </div>
                    </div>
                </div>
            `;

                $("#teacherCardView").append(newTeacherCard);

                buildTeacherTable();

                this.reset();
                modal.removeClass("show");
            });
        });
    </script>
@endpush
