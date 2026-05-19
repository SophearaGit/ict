@extends('frontend.staff.layout.master')
@section('page_title', isset($page_title) ? $page_title : 'Page Title Here')
@section('content')
    @include('frontend.staff.pages.partials.breadcrumb')
    {{-- Toolbar --}}
    <div class="card card-body">
        <div class="row align-items-center">
            <div class="col-md-4 col-xl-3">
                <form method="GET" action="{{ route('staff.student.index') }}" id="search-form">
                    <div class="position-relative">
                        <input type="text" name="search" class="form-control product-search ps-5"
                            placeholder="Search Students..." value="{{ request('search') }}">
                        <i class="ti ti-search position-absolute top-50 start-0 translate-middle-y fs-6 text-dark ms-3"></i>
                    </div>
                </form>
            </div>
            <div
                class="col-md-8 col-xl-9 d-flex justify-content-md-end justify-content-center align-items-center gap-2 mt-3 mt-md-0">
                {{-- View Toggle --}}
                <div class="btn-group" role="group" id="view-toggle">
                    <button type="button" class="btn btn-outline-secondary view-btn active" data-view="list"
                        title="List View">
                        <i class="ti ti-list fs-5"></i>
                    </button>
                    <button type="button" class="btn btn-outline-secondary view-btn" data-view="grid" title="Grid View">
                        <i class="ti ti-layout-grid fs-5"></i>
                    </button>
                </div>
                <a href="javascript:void(0)" class="btn btn-info d-flex align-items-center" data-bs-toggle="modal"
                    data-bs-target="#addStudentModal">
                    <i class="ti ti-user-plus text-white me-1 fs-5"></i> Add Student
                </a>
            </div>
        </div>
    </div>
    {{-- LIST VIEW --}}
    <div id="view-list" class="card card-body">
        <div class="table-responsive">
            <table class="table search-table align-middle text-nowrap">
                <thead class="header-item">
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Gender</th>
                        <th>Location</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $student)
                        <tr class="search-items">
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="{{ $student->image == 'no-img.jpg'
                                        ? ($student->gender == 'male'
                                            ? asset('\admin\assets\dist\images\profile\user-1.jpg')
                                            : asset('\admin\assets\dist\images\profile\user-2.jpg'))
                                        : asset($student->image) }}"
                                        alt="avatar" class="rounded-circle object-fit-cover" width="35"
                                        height="35">
                                    <div class="ms-3">
                                        <h6 class="user-name mb-0">{{ $student->name }}</h6>
                                        <span class="fs-3 text-muted">{{ $student->khmer_name ?? '-' }}</span>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $student->email }}</td>
                            <td>{{ $student->phone ?? '-' }}</td>
                            <td>
                                <span
                                    class="badge {{ $student->gender === 'male' ? 'bg-light-primary text-primary' : 'bg-light-danger text-danger' }} rounded-pill">
                                    {{ ucfirst($student->gender ?? '-') }}
                                </span>
                            </td>
                            <td>{{ $student->location ?? '-' }}</td>
                            <td>
                                <div class="action-btn">
                                    <a href="javascript:void(0)" class="text-info btn-edit-student" title="Edit"
                                        data-id="{{ $student->id }}" data-name="{{ $student->name }}"
                                        data-khmer-name="{{ $student->khmer_name }}" data-email="{{ $student->email }}"
                                        data-phone="{{ $student->phone }}" data-dob="{{ $student->dob }}"
                                        data-gender="{{ $student->gender }}" data-location="{{ $student->location }}">
                                        <i class="ti ti-edit fs-5"></i>
                                    </a>
                                    <a href="javascript:void(0)" class="text-danger ms-2 btn-delete-student" title="Delete"
                                        data-id="{{ $student->id }}" data-name="{{ $student->name }}">
                                        <i class="ti ti-trash fs-5"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                <i class="ti ti-users-off fs-6 me-1"></i>
                                No students found{{ request('search') ? ' for "' . request('search') . '"' : '' }}.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($students->hasPages())
            {{ $students->links('frontend.staff.pages.pagination.custom') }}
        @endif
    </div>
    {{-- GRID VIEW --}}
    <div id="view-grid" style="display:none;">
        @forelse($students as $student)
            <div class="col" style="display:contents;">
            </div>
        @empty
        @endforelse
        <div class="row g-3">
            @forelse($students as $student)
                <div class="col-sm-6 col-md-4 col-xl-3">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-body d-flex flex-column align-items-center text-center pt-4">
                            <img src="{{ $student->image == 'no-img.jpg'
                                ? ($student->gender == 'male'
                                    ? asset('\admin\assets\dist\images\profile\user-1.jpg')
                                    : asset('\admin\assets\dist\images\profile\user-2.jpg'))
                                : asset($student->image) }}"
                                alt="avatar"
                                class="rounded-circle object-fit-cover mb-3 border border-3 border-light shadow-sm"
                                width="72" height="72">
                            <h6 class="mb-0 fw-semibold">{{ $student->name }}</h6>
                            @if ($student->khmer_name)
                                <small class="text-muted">{{ $student->khmer_name }}</small>
                            @endif
                            <span
                                class="badge {{ $student->gender === 'male' ? 'bg-light-primary text-primary' : 'bg-light-danger text-danger' }} rounded-pill mt-2">
                                {{ ucfirst($student->gender ?? '-') }}
                            </span>
                            <hr class="w-100 my-3">
                            <div class="w-100 text-start small text-muted">
                                <div class="mb-1"><i class="ti ti-mail me-1"></i> {{ $student->email }}</div>
                                <div class="mb-1"><i class="ti ti-phone me-1"></i> {{ $student->phone ?? '-' }}</div>
                                <div><i class="ti ti-map-pin me-1"></i> {{ $student->location ?? '-' }}</div>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-0 d-flex justify-content-center gap-2 pb-3">
                            <a href="javascript:void(0)" class="btn btn-sm btn-outline-info btn-edit-student"
                                data-id="{{ $student->id }}" data-name="{{ $student->name }}"
                                data-khmer-name="{{ $student->khmer_name }}" data-email="{{ $student->email }}"
                                data-phone="{{ $student->phone }}" data-dob="{{ $student->dob }}"
                                data-gender="{{ $student->gender }}" data-location="{{ $student->location }}">
                                <i class="ti ti-edit me-1"></i> Edit
                            </a>
                            <a href="javascript:void(0)" class="btn btn-sm btn-outline-danger btn-delete-student"
                                data-id="{{ $student->id }}" data-name="{{ $student->name }}">
                                <i class="ti ti-trash me-1"></i> Delete
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5 text-muted">
                    <i class="ti ti-users-off fs-1 d-block mb-2"></i>
                    No students found{{ request('search') ? ' for "' . request('search') . '"' : '' }}.
                </div>
            @endforelse
        </div>
        {{-- Pagination for grid --}}
        @if ($students->hasPages())
            <div class="mt-3">
                {{ $students->links('frontend.staff.pages.pagination.custom') }}
            </div>
        @endif
    </div>
    {{-- CREATE MODAL --}}
    <div class="modal fade" id="addStudentModal" tabindex="-1" aria-labelledby="addStudentModalLabel"
        style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header d-flex align-items-center">
                    <h5 class="modal-title" id="addStudentModalLabel">Add Student</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('staff.student.store') }}" id="addStudentForm"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Full Name (English) <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="name"
                                        class="form-control @error('name') is-invalid @enderror"
                                        placeholder="e.g. Dara Chan" value="{{ old('name') }}">
                                    @error('name')
                                        <span class="text-danger small">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Full Name (Khmer)</label>
                                    <input type="text" name="khmer_name" class="form-control"
                                        placeholder="e.g. ដារ៉ា ចាន់" value="{{ old('khmer_name') }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                                    <input type="email" name="email"
                                        class="form-control @error('email') is-invalid @enderror"
                                        placeholder="e.g. student@gmail.com" value="{{ old('email') }}">
                                    @error('email')
                                        <span class="text-danger small">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Phone <span class="text-danger">*</span></label>
                                    <input type="text" name="phone"
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
                                    <label class="form-label fw-semibold">Date of Birth</label>
                                    <input type="date" name="dob" class="form-control"
                                        value="{{ old('dob') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Gender <span
                                            class="text-danger">*</span></label>
                                    <select name="gender" class="form-select @error('gender') is-invalid @enderror">
                                        <option value="" disabled selected>Select Gender</option>
                                        <option value="male" {{ old('gender') === 'male' ? 'selected' : '' }}>Male
                                        </option>
                                        <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>Female
                                        </option>
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
                                    <label class="form-label fw-semibold">Password <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="password" id="s-password" name="password"
                                            class="form-control @error('password') is-invalid @enderror"
                                            placeholder="Min. 8 characters">
                                        <button type="button" class="btn btn-outline-secondary toggle-password"
                                            data-target="#s-password"><i class="ti ti-eye"></i></button>
                                    </div>
                                    @error('password')
                                        <span class="text-danger small">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Confirm Password <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="password" id="s-password-confirm" name="password_confirmation"
                                            class="form-control" placeholder="Re-enter password">
                                        <button type="button" class="btn btn-outline-secondary toggle-password"
                                            data-target="#s-password-confirm"><i class="ti ti-eye"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Location</label>
                                    <input type="text" name="location" class="form-control"
                                        placeholder="e.g. Phnom Penh" value="{{ old('location') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Profile Image</label>
                                    <input type="file" name="image" class="form-control" accept="image/*">
                                    <small class="text-muted">Accepted: JPG, PNG, WEBP</small>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success rounded-pill px-4"
                        onclick="document.getElementById('addStudentForm').submit();">
                        <i class="ti ti-user-plus me-1"></i> Add Student
                    </button>
                    <button class="btn btn-danger rounded-pill px-4" data-bs-dismiss="modal">
                        <i class="ti ti-x me-1"></i> Discard
                    </button>
                </div>
            </div>
        </div>
    </div>
    {{-- EDIT MODAL --}}
    <div class="modal fade" id="editStudentModal" tabindex="-1" aria-hidden="true" style="display:none;">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header d-flex align-items-center">
                    <h5 class="modal-title">Edit Student</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" id="editStudentForm" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Full Name (English) <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="name" id="edit-name" class="form-control"
                                        placeholder="e.g. Dara Chan">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Full Name (Khmer)</label>
                                    <input type="text" name="khmer_name" id="edit-khmer-name" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                                    <input type="email" name="email" id="edit-email" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Phone <span class="text-danger">*</span></label>
                                    <input type="text" name="phone" id="edit-phone" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Date of Birth</label>
                                    <input type="date" name="dob" id="edit-dob" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Gender <span
                                            class="text-danger">*</span></label>
                                    <select name="gender" id="edit-gender" class="form-select">
                                        <option value="" disabled>Select Gender</option>
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">New Password</label>
                                    <div class="input-group">
                                        <input type="password" name="password" id="edit-password" class="form-control"
                                            placeholder="Leave blank to keep current">
                                        <button type="button" class="btn btn-outline-secondary toggle-password"
                                            data-target="#edit-password"><i class="ti ti-eye"></i></button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Confirm New Password</label>
                                    <div class="input-group">
                                        <input type="password" id="edit-password-confirm" name="password_confirmation"
                                            class="form-control" placeholder="Re-enter new password">
                                        <button type="button" class="btn btn-outline-secondary toggle-password"
                                            data-target="#edit-password-confirm"><i class="ti ti-eye"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Location</label>
                                    <input type="text" name="location" id="edit-location" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Profile Image</label>
                                    <input type="file" name="image" class="form-control" accept="image/*">
                                    <small class="text-muted">Leave blank to keep current image</small>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success rounded-pill px-4"
                        onclick="document.getElementById('editStudentForm').submit();">
                        <i class="ti ti-device-floppy me-1"></i> Save Changes
                    </button>
                    <button class="btn btn-danger rounded-pill px-4" data-bs-dismiss="modal">
                        <i class="ti ti-x me-1"></i> Discard
                    </button>
                </div>
            </div>
        </div>
    </div>
    {{-- DELETE MODAL --}}
    <div class="modal fade" id="deleteStudentModal" tabindex="-1" aria-hidden="true" style="display:none;">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header d-flex align-items-center">
                    <h5 class="modal-title text-danger">
                        <i class="ti ti-trash me-2"></i> Delete Student
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <i class="ti ti-alert-triangle text-warning" style="font-size: 3rem;"></i>
                    <h5 class="mt-3">Are you sure?</h5>
                    <p class="text-muted mb-0">You are about to delete <strong id="delete-student-name"></strong>.<br>
                        This action cannot be undone.</p>
                </div>
                <div class="modal-footer justify-content-center">
                    <form id="deleteStudentForm" method="POST">
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
                new bootstrap.Modal(document.getElementById('addStudentModal')).show();
            });
        </script>
    @endif
@endsection
@push('scripts')
    <script>
        // ─── View Toggle (persisted in localStorage) ───────────────────────────────
        const VIEW_KEY = 'student_view_preference';
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
        // ─── Delete ─────────────────────────────────────────────────────────────────
        document.querySelectorAll('.btn-delete-student').forEach(function(btn) {
            btn.addEventListener('click', function() {
                document.getElementById('delete-student-name').textContent = this.dataset.name;
                document.getElementById('deleteStudentForm').action = `/staff/student/${this.dataset.id}`;
                new bootstrap.Modal(document.getElementById('deleteStudentModal')).show();
            });
        });
        // ─── Edit ───────────────────────────────────────────────────────────────────
        document.querySelectorAll('.btn-edit-student').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const form = document.getElementById('editStudentForm');
                form.action = `/staff/student/${this.dataset.id}`;
                document.getElementById('edit-name').value = this.dataset.name ?? '';
                document.getElementById('edit-khmer-name').value = this.dataset.khmerName ?? '';
                document.getElementById('edit-email').value = this.dataset.email ?? '';
                document.getElementById('edit-phone').value = this.dataset.phone ?? '';
                document.getElementById('edit-dob').value = this.dataset.dob ?? '';
                document.getElementById('edit-location').value = this.dataset.location ?? '';
                document.getElementById('edit-gender').value = this.dataset.gender ?? '';
                new bootstrap.Modal(document.getElementById('editStudentModal')).show();
            });
        });
    </script>
@endpush
