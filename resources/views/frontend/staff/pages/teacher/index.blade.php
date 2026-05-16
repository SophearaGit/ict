@extends('frontend.staff.layout.master')
@section('page_title', isset($page_title) ? $page_title : 'Page Title Here')
@section('content')
    @include('frontend.staff.pages.partials.breadcrumb')
    <div class="card card-body">
        <div class="row">
            <div class="col-md-4 col-xl-3">
                <form method="GET" action="{{ route('staff.teacher.index') }}" id="search-form">
                    <div class="position-relative">
                        <input type="text" name="search" class="form-control product-search ps-5"
                            placeholder="Search Teachers..." value="{{ request('search') }}">
                        <i class="ti ti-search position-absolute top-50 start-0 translate-middle-y fs-6 text-dark ms-3"></i>
                    </div>
                </form>
            </div>
            <div class="col-md-8 col-xl-9 text-end d-flex justify-content-md-end justify-content-center mt-3 mt-md-0">
                <a href="javascript:void(0)" id="btn-add-contact" class="btn btn-info d-flex align-items-center"
                    data-bs-toggle="modal" data-bs-target="#addContactModal">
                    <i class="ti ti-users text-white me-1 fs-5"></i> Add Teacher
                </a>
            </div>
        </div>
    </div>

    <div class="card card-body">
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
                    @forelse($instructors as $instructor)
                        <tr class="search-items">
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
                                        data-dob="{{ $instructor->dob }}" data-gender="{{ $instructor->gender }}"
                                        data-location="{{ $instructor->location }}">
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
                            <td colspan="6" class="text-center py-4 text-muted">
                                <i class="ti ti-users-off fs-6 me-1"></i>
                                No teachers found{{ request('search') ? ' for "' . request('search') . '"' : '' }}.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($instructors->hasPages())
            {{-- <div class="d-flex justify-content-end mt-3"> --}}
            {{ $instructors->links('frontend.staff.pages.pagination.custom') }}
            {{-- </div> --}}
        @endif
    </div>

    {{-- CREATE MODAL --}}
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
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="c-name" class="form-label fw-semibold">Full Name (English) <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" id="c-name" name="name"
                                                class="form-control @error('name') is-invalid @enderror"
                                                placeholder="e.g. John Doe" value="{{ old('name') }}">
                                            @error('name')
                                                <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="c-khmer-name" class="form-label fw-semibold">Full Name
                                                (Khmer)</label>
                                            <input type="text" id="c-khmer-name" name="khmer_name" class="form-control"
                                                placeholder="e.g. គ្រូបង្រៀន" value="{{ old('khmer_name') }}">
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
                                            <input type="text" id="c-phone" name="phone"
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
                                                value="{{ old('dob') }}">
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
                                            <input type="password" id="c-password" name="password"
                                                class="form-control @error('password') is-invalid @enderror"
                                                placeholder="Min. 8 characters">
                                            @error('password')
                                                <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="c-password-confirm" class="form-label fw-semibold">Confirm
                                                Password <span class="text-danger">*</span></label>
                                            <input type="password" id="c-password-confirm" name="password_confirmation"
                                                class="form-control" placeholder="Re-enter password">
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
                    <button class="btn btn-danger rounded-pill px-4" data-bs-dismiss="modal">
                        <i class="ti ti-x me-1"></i> Discard
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- EDIT MODAL --}}
    <div class="modal fade" id="editContactModal" tabindex="-1" aria-hidden="true" style="display:none;">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header d-flex align-items-center">
                    <h5 class="modal-title">Edit Teacher</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" id="editTeacherForm" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Full Name (English) <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="name" id="edit-name" class="form-control"
                                        placeholder="e.g. John Doe">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Full Name (Khmer)</label>
                                    <input type="text" name="khmer_name" id="edit-khmer-name" class="form-control"
                                        placeholder="e.g. គ្រូបង្រៀន">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                                    <input type="email" name="email" id="edit-email" class="form-control"
                                        placeholder="e.g. teacher@gmail.com">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Phone <span class="text-danger">*</span></label>
                                    <input type="text" name="phone" id="edit-phone" class="form-control"
                                        placeholder="e.g. 012000000">
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
                                    <input type="password" name="password" id="edit-password" class="form-control"
                                        placeholder="Leave blank to keep current">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Confirm New Password</label>
                                    <input type="password" name="password_confirmation" class="form-control"
                                        placeholder="Re-enter new password">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Location</label>
                                    <input type="text" name="location" id="edit-location" class="form-control"
                                        placeholder="e.g. Phnom Penh">
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
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Document</label>
                                    <input type="file" name="document" class="form-control" accept="image/*,.pdf">
                                    <small class="text-muted">Leave blank to keep current document</small>
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
                    <button class="btn btn-danger rounded-pill px-4" data-bs-dismiss="modal">
                        <i class="ti ti-x me-1"></i> Discard
                    </button>
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
                var modal = new bootstrap.Modal(document.getElementById('addContactModal'));
                modal.show();
            });
        </script>
    @endif

@endsection
@push('scripts')
    <script>
        // Delete button click handler
        document.querySelectorAll('.btn-delete-teacher').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const id = this.dataset.id;
                const name = this.dataset.name;

                document.getElementById('delete-teacher-name').textContent = name;
                document.getElementById('deleteTeacherForm').action = `/staff/teacher/${id}`;

                new bootstrap.Modal(document.getElementById('deleteTeacherModal')).show();
            });
        });


        // Edit button click handler
        document.querySelectorAll('.btn-edit-teacher').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const id = this.dataset.id;
                const form = document.getElementById('editTeacherForm');

                // Set form action dynamically
                form.action = `/staff/teacher/${id}`;

                // Populate fields
                document.getElementById('edit-name').value = this.dataset.name ?? '';
                document.getElementById('edit-khmer-name').value = this.dataset.khmerName ?? '';
                document.getElementById('edit-email').value = this.dataset.email ?? '';
                document.getElementById('edit-phone').value = this.dataset.phone ?? '';
                document.getElementById('edit-dob').value = this.dataset.dob ?? '';
                document.getElementById('edit-location').value = this.dataset.location ?? '';

                // Set gender dropdown
                const genderSelect = document.getElementById('edit-gender');
                genderSelect.value = this.dataset.gender ?? '';

                // Open modal
                new bootstrap.Modal(document.getElementById('editContactModal')).show();
            });
        });
    </script>
@endpush
