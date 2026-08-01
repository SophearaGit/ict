@extends('frontend.staff.layout.master')
@section('page_title', isset($page_title) ? $page_title : 'Page Title Here')
@section('content')
{{-- Flatpickr (date picker) & Croppie (image cropper) --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.css">
<style>
  .image-preview-circle {
    width: 90px;
    height: 90px;
    border-radius: 50%;
    overflow: hidden;
    border: 2px solid #e9ecef;
    background: #f8f9fa;
    flex-shrink: 0;
  }
  .image-preview-circle img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }
  .form-section-title {
    font-size: .75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .04em;
    color: #6c757d;
    margin-top: .25rem;
    margin-bottom: .75rem;
  }
  .form-section-title:not(:first-child) {
    margin-top: 1.25rem;
    padding-top: 1rem;
    border-top: 1px dashed #e9ecef;
  }
  #croppie-container .cr-slider-wrap {
    margin-top: 15px;
  }
  .bio-counter {
    font-size: .75rem;
    color: #adb5bd;
  }
</style>
@include('frontend.staff.pages.partials.breadcrumb')
{{-- Toolbar --}}
<div class="card card-body">
  <form method="GET" action="{{ route('staff.student.index') }}" id="search-form">
    <div class="row g-2 align-items-center">
      <div class="col-md-4 col-xl-3">
        <div class="position-relative">
          <input type="text" name="search" id="search-input" class="form-control product-search ps-5" placeholder="Search name, email, phone..." value="{{ request('search') }}">
          <i class="ti ti-search position-absolute top-50 start-0 translate-middle-y fs-6 text-dark ms-3"></i>
          @if (request('search'))
          <button type="button" id="clear-search-btn" class="btn btn-sm p-0 position-absolute top-50 end-0 translate-middle-y me-2 text-muted" title="Clear search">
            <i class="ti ti-x"></i>
          </button>
          @endif
        </div>
      </div>
      <div class="col-6 col-md-3 col-xl-2">
        <select name="status" class="form-select filter-select" onchange="document.getElementById('search-form').submit()">
          <option value="">All Status</option>
          <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
          <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive
          </option>
        </select>
      </div>
      <div class="col-6 col-md-3 col-xl-2">
        <select name="gender" class="form-select filter-select" onchange="document.getElementById('search-form').submit()">
          <option value="">All Genders</option>
          <option value="male" {{ request('gender') === 'male' ? 'selected' : '' }}>Male</option>
          <option value="female" {{ request('gender') === 'female' ? 'selected' : '' }}>Female</option>
        </select>
      </div>
      <div class="col-6 col-md-3 col-xl-2">
        <select name="sort" class="form-select filter-select" onchange="document.getElementById('search-form').submit()">
          <option value="latest" {{ request('sort', 'latest') === 'latest' ? 'selected' : '' }}>Newest
            First</option>
          <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Oldest First
          </option>
          <option value="name_asc" {{ request('sort') === 'name_asc' ? 'selected' : '' }}>Name A–Z
          </option>
          <option value="name_desc" {{ request('sort') === 'name_desc' ? 'selected' : '' }}>Name Z–A
          </option>
        </select>
      </div>
      <div class="col-md-2 col-xl-3 d-flex justify-content-md-end justify-content-center align-items-center gap-2 mt-2 mt-md-0">
        {{-- View Toggle --}}
        <div class="btn-group" role="group" id="view-toggle">
          <button type="button" class="btn btn-outline-secondary view-btn active" data-view="list" title="List View">
            <i class="ti ti-list fs-5"></i>
          </button>
          <button type="button" class="btn btn-outline-secondary view-btn" data-view="grid" title="Grid View">
            <i class="ti ti-layout-grid fs-5"></i>
          </button>
        </div>
        <a href="javascript:void(0)" class="btn btn-info d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#addStudentModal">
          <i class="ti ti-user-plus text-white me-1 fs-5"></i> Add Student
        </a>
      </div>
    </div>
  </form>
  <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mt-3">
    <small class="text-muted">
      <i class="ti ti-users me-1"></i>
      Showing {{ $students->firstItem() ?? 0 }}–{{ $students->lastItem() ?? 0 }} of
      {{ $students->total() }} student{{ $students->total() === 1 ? '' : 's' }}
    </small>
    @if (request()->anyFilled(['search', 'status', 'gender']))
    <a href="{{ route('staff.student.index') }}" class="small text-danger text-decoration-none">
      <i class="ti ti-filter-off me-1"></i> Clear filters
    </a>
    @endif
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
          <th>Status</th>
          <th>Joined</th>
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
                                        : asset($student->image) }}" alt="avatar" class="rounded-circle object-fit-cover avatar-ring {{ $student->status === 'active' ? 'avatar-ring-active' : 'avatar-ring-inactive' }}" width="35" height="35">
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
            <span
                                    class="badge {{ $student->status === 'active' ? 'bg-light-success text-success' : 'bg-light-secondary text-secondary' }} rounded-pill">
                                    <i class="ti {{ $student->status === 'active' ? 'ti-circle-check' : 'ti-circle-x' }} me-1"></i>
                                    {{ ucfirst($student->status ?? 'active') }}
                                </span>
          </td>
          <td>
            <span title="{{ $student->created_at?->format('M d, Y g:i A') }}">
            {{ $student->created_at?->format('M d, Y') ?? '-' }}
            </span>
          </td>
          <td>
            <div class="action-btn">
              <a href="javascript:void(0)" class="text-secondary btn-view-student" title="View" data-id="{{ $student->id }}" data-name="{{ $student->name }}" data-khmer-name="{{ $student->khmer_name }}" data-email="{{ $student->email }}" data-phone="{{ $student->phone }}" data-dob="{{ $student->dob }}" data-gender="{{ $student->gender }}" data-location="{{ $student->location }}" data-nationality="{{ $student->nationality }}" data-alternate-phone="{{ $student->alternate_phone }}" data-bio="{{ $student->bio }}" data-status="{{ $student->status }}" data-joined="{{ $student->created_at?->format('M d, Y') }}" data-image-url="{{ $student->image == 'no-img.jpg'
                                            ? ($student->gender == 'male'
                                                ? asset('\admin\assets\dist\images\profile\user-1.jpg')
                                                : asset('\admin\assets\dist\images\profile\user-2.jpg'))
                                            : asset($student->image) }}">
                <i class="ti ti-eye fs-5"></i>
              </a>
              <a href="javascript:void(0)" class="text-info ms-2 btn-edit-student" title="Edit" data-id="{{ $student->id }}" data-name="{{ $student->name }}" data-khmer-name="{{ $student->khmer_name }}" data-email="{{ $student->email }}" data-phone="{{ $student->phone }}" data-dob="{{ $student->dob }}" data-gender="{{ $student->gender }}" data-location="{{ $student->location }}" data-nationality="{{ $student->nationality }}" data-alternate-phone="{{ $student->alternate_phone }}" data-bio="{{ $student->bio }}" data-image-url="{{ $student->image == 'no-img.jpg'
                                            ? ($student->gender == 'male'
                                                ? asset('\admin\assets\dist\images\profile\user-1.jpg')
                                                : asset('\admin\assets\dist\images\profile\user-2.jpg'))
                                            : asset($student->image) }}">
                <i class="ti ti-edit fs-5"></i>
              </a>
              <a href="javascript:void(0)" class="text-danger ms-2 btn-delete-student" title="Delete" data-id="{{ $student->id }}" data-name="{{ $student->name }}">
                <i class="ti ti-trash fs-5"></i>
              </a>
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="8" class="text-center py-4 text-muted">
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
      <div class="card h-100 shadow-sm border-0 student-card">
        <div class="card-body d-flex flex-column align-items-center text-center pt-4">
          <img src="{{ $student->image == 'no-img.jpg'
                                ? ($student->gender == 'male'
                                    ? asset('\admin\assets\dist\images\profile\user-1.jpg')
                                    : asset('\admin\assets\dist\images\profile\user-2.jpg'))
                                : asset($student->image) }}" alt="avatar" class="rounded-circle object-fit-cover mb-3 avatar-ring {{ $student->status === 'active' ? 'avatar-ring-active' : 'avatar-ring-inactive' }}" width="72" height="72">
          <h6 class="mb-0 fw-semibold">{{ $student->name }}</h6>
          @if ($student->khmer_name)
          <small class="text-muted">{{ $student->khmer_name }}</small>
          @endif
          <div class="d-flex gap-1 flex-wrap justify-content-center mt-2">
            <span
                                    class="badge {{ $student->gender === 'male' ? 'bg-light-primary text-primary' : 'bg-light-danger text-danger' }} rounded-pill">
            {{ ucfirst($student->gender ?? '-') }}
            </span>
            <span
                                    class="badge {{ $student->status === 'active' ? 'bg-light-success text-success' : 'bg-light-secondary text-secondary' }} rounded-pill">
            {{ ucfirst($student->status ?? 'active') }}
            </span>
          </div>
          <hr class="w-100 my-3">
          <div class="w-100 text-start small text-muted">
            <div class="mb-1 text-truncate"><i class="ti ti-mail me-1"></i> {{ $student->email }}
            </div>
            <div class="mb-1"><i class="ti ti-phone me-1"></i> {{ $student->phone ?? '-' }}</div>
            <div class="mb-1"><i class="ti ti-map-pin me-1"></i> {{ $student->location ?? '-' }}
            </div>
            @if ($student->nationality)
            <div class="mb-1"><i class="ti ti-flag me-1"></i> {{ $student->nationality }}
            </div>
            @endif
            <div class="text-muted-light"><i class="ti ti-calendar-event me-1"></i> Joined
              {{ $student->created_at?->format('M d, Y') ?? '-' }}
            </div>
          </div>
        </div>
        <div class="card-footer bg-transparent border-0 d-flex justify-content-center gap-2 pb-3">
          <a href="javascript:void(0)" class="btn btn-sm btn-outline-secondary btn-view-student" title="View" data-id="{{ $student->id }}" data-name="{{ $student->name }}" data-khmer-name="{{ $student->khmer_name }}" data-email="{{ $student->email }}" data-phone="{{ $student->phone }}" data-dob="{{ $student->dob }}" data-gender="{{ $student->gender }}" data-location="{{ $student->location }}" data-nationality="{{ $student->nationality }}" data-alternate-phone="{{ $student->alternate_phone }}" data-bio="{{ $student->bio }}" data-status="{{ $student->status }}" data-joined="{{ $student->created_at?->format('M d, Y') }}" data-image-url="{{ $student->image == 'no-img.jpg'
                                    ? ($student->gender == 'male'
                                        ? asset('\admin\assets\dist\images\profile\user-1.jpg')
                                        : asset('\admin\assets\dist\images\profile\user-2.jpg'))
                                    : asset($student->image) }}">
            <i class="ti ti-eye"></i>
          </a>
          <a href="javascript:void(0)" class="btn btn-sm btn-outline-info btn-edit-student" title="Edit" data-id="{{ $student->id }}" data-name="{{ $student->name }}" data-khmer-name="{{ $student->khmer_name }}" data-email="{{ $student->email }}" data-phone="{{ $student->phone }}" data-dob="{{ $student->dob }}" data-gender="{{ $student->gender }}" data-location="{{ $student->location }}" data-nationality="{{ $student->nationality }}" data-alternate-phone="{{ $student->alternate_phone }}" data-bio="{{ $student->bio }}" data-image-url="{{ $student->image == 'no-img.jpg'
                                    ? ($student->gender == 'male'
                                        ? asset('\admin\assets\dist\images\profile\user-1.jpg')
                                        : asset('\admin\assets\dist\images\profile\user-2.jpg'))
                                    : asset($student->image) }}">
            <i class="ti ti-edit"></i>
          </a>
          <a href="javascript:void(0)" class="btn btn-sm btn-outline-danger btn-delete-student" title="Delete" data-id="{{ $student->id }}" data-name="{{ $student->name }}">
            <i class="ti ti-trash"></i>
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
<div class="modal fade" id="addStudentModal" tabindex="-1" aria-labelledby="addStudentModalLabel" style="display: none;" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header d-flex align-items-center">
        <h5 class="modal-title" id="addStudentModalLabel">Add Student</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form method="POST" action="{{ route('staff.student.store') }}" id="addStudentForm" enctype="multipart/form-data">
          @csrf
          <div class="form-section-title"><i class="ti ti-user me-1"></i> Personal Information</div>
          <div class="row">
            <div class="col-12">
              <div class="mb-3">
                <label class="form-label fw-semibold">Profile Image</label>
                <div class="d-flex align-items-center gap-3">
                  <div class="image-preview-circle">
                    <img id="add-image-preview" src="{{ asset('admin/assets/dist/images/profile/user-1.jpg') }}" alt="Preview">
                  </div>
                  <div>
                    <button type="button" class="btn btn-sm btn-outline-primary" id="add-image-trigger">
                      <i class="ti ti-camera-plus me-1"></i> Upload & Crop
                    </button>
                    <div class="form-text mb-0">JPG, PNG or WEBP. Crop to a square.</div>
                  </div>
                  <input type="file" id="add-image-input" accept="image/*" class="d-none">
                  <input type="file" name="image" id="add-image-file" class="d-none">
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label fw-semibold">Full Name (English) <span
                                            class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="e.g. Dara Chan" value="{{ old('name') }}">
                @error('name')
                <span class="text-danger small">{{ $message }}</span>
                @enderror
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label fw-semibold">Full Name (Khmer)</label>
                <input type="text" name="khmer_name" class="form-control" placeholder="e.g. ដារ៉ា ចាន់" value="{{ old('khmer_name') }}">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="e.g. student@gmail.com" value="{{ old('email') }}">
                @error('email')
                <span class="text-danger small">{{ $message }}</span>
                @enderror
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label fw-semibold">Phone <span class="text-danger">*</span></label>
                <input type="text" name="phone" inputmode="tel" maxlength="20" class="form-control @error('phone') is-invalid @enderror" placeholder="e.g. 012000000" value="{{ old('phone') }}">
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
                <input type="text" name="dob" id="dob" class="form-control flatpickr-date" placeholder="Select date" autocomplete="off" value="{{ old('dob') }}">
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
          <div class="form-section-title"><i class="ti ti-lock me-1"></i> Account Security</div>
          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label fw-semibold">Password <span
                                            class="text-danger">*</span></label>
                <div class="input-group">
                  <input type="password" id="s-password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Min. 8 characters">
                  <button type="button" class="btn btn-outline-secondary toggle-password" data-target="#s-password"><i class="ti ti-eye"></i></button>
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
                  <input type="password" id="s-password-confirm" name="password_confirmation" class="form-control" placeholder="Re-enter password">
                  <button type="button" class="btn btn-outline-secondary toggle-password" data-target="#s-password-confirm"><i class="ti ti-eye"></i></button>
                </div>
              </div>
            </div>
          </div>
          <div class="form-section-title"><i class="ti ti-map-pin me-1"></i> Additional Information
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label fw-semibold">Location</label>
                <input type="text" name="location" class="form-control" placeholder="e.g. Phnom Penh" value="{{ old('location') }}">
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label fw-semibold">Nationality</label>
                <input type="text" name="nationality" class="form-control" placeholder="e.g. Cambodian" value="{{ old('nationality') }}">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label fw-semibold">Alternate Phone</label>
                <input type="text" name="alternate_phone" inputmode="tel" maxlength="20" class="form-control" placeholder="e.g. 098000000" value="{{ old('alternate_phone') }}">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-12">
              <div class="mb-3">
                <label class="form-label fw-semibold d-flex justify-content-between">
                  <span>Bio / Notes</span>
                  <span class="bio-counter" id="add-bio-counter">0 / 1000</span>
                </label>
                <textarea name="bio" id="add-bio" class="form-control" rows="3" maxlength="1000" placeholder="Short bio or notes about the student">{{ old('bio') }}</textarea>
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button class="btn btn-success rounded-pill px-4" onclick="document.getElementById('addStudentForm').submit();">
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
          <div class="form-section-title"><i class="ti ti-user me-1"></i> Personal Information</div>
          <div class="row">
            <div class="col-12">
              <div class="mb-3">
                <label class="form-label fw-semibold">Profile Image</label>
                <div class="d-flex align-items-center gap-3">
                  <div class="image-preview-circle">
                    <img id="edit-image-preview" src="{{ asset('admin/assets/dist/images/profile/user-1.jpg') }}" alt="Preview">
                  </div>
                  <div>
                    <button type="button" class="btn btn-sm btn-outline-primary" id="edit-image-trigger">
                      <i class="ti ti-camera-plus me-1"></i> Upload & Crop
                    </button>
                    <div class="form-text mb-0">Leave unchanged to keep current photo.</div>
                  </div>
                  <input type="file" id="edit-image-input" accept="image/*" class="d-none">
                  <input type="file" name="image" id="edit-image-file" class="d-none">
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label fw-semibold">Full Name (English) <span
                                            class="text-danger">*</span></label>
                <input type="text" name="name" id="edit-name" class="form-control" placeholder="e.g. Dara Chan">
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
                <input type="text" name="phone" id="edit-phone" inputmode="tel" maxlength="20" class="form-control">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label fw-semibold">Date of Birth</label>
                <input type="text" name="dob" id="edit-dob" class="form-control flatpickr-date" placeholder="Select date" autocomplete="off">
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
          <div class="form-section-title"><i class="ti ti-lock me-1"></i> Account Security</div>
          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label fw-semibold">New Password</label>
                <div class="input-group">
                  <input type="password" name="password" id="edit-password" class="form-control" placeholder="Leave blank to keep current">
                  <button type="button" class="btn btn-outline-secondary toggle-password" data-target="#edit-password"><i class="ti ti-eye"></i></button>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label fw-semibold">Confirm New Password</label>
                <div class="input-group">
                  <input type="password" id="edit-password-confirm" name="password_confirmation" class="form-control" placeholder="Re-enter new password">
                  <button type="button" class="btn btn-outline-secondary toggle-password" data-target="#edit-password-confirm"><i class="ti ti-eye"></i></button>
                </div>
              </div>
            </div>
          </div>
          <div class="form-section-title"><i class="ti ti-map-pin me-1"></i> Additional Information
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
                <label class="form-label fw-semibold">Nationality</label>
                <input type="text" name="nationality" id="edit-nationality" class="form-control">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label fw-semibold">Alternate Phone</label>
                <input type="text" name="alternate_phone" id="edit-alternate-phone" inputmode="tel" maxlength="20" class="form-control">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-12">
              <div class="mb-3">
                <label class="form-label fw-semibold d-flex justify-content-between">
                  <span>Bio / Notes</span>
                  <span class="bio-counter" id="edit-bio-counter">0 / 1000</span>
                </label>
                <textarea name="bio" id="edit-bio" class="form-control" rows="3" maxlength="1000" placeholder="Short bio or notes about the student"></textarea>
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button class="btn btn-success rounded-pill px-4" onclick="document.getElementById('editStudentForm').submit();">
          <i class="ti ti-device-floppy me-1"></i> Save Changes
        </button>
        <button class="btn btn-danger rounded-pill px-4" data-bs-dismiss="modal">
          <i class="ti ti-x me-1"></i> Discard
        </button>
      </div>
    </div>
  </div>
</div>
{{-- IMAGE CROP MODAL (shared by Add & Edit) --}}
<div class="modal fade" id="cropImageModal" tabindex="-1" aria-hidden="true" style="display:none;">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header d-flex align-items-center">
        <h5 class="modal-title"><i class="ti ti-crop me-2"></i> Crop Profile Image</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div id="croppie-container" style="width:100%; height:350px;"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success rounded-pill px-4" id="cropConfirmBtn">
          <i class="ti ti-check me-1"></i> Crop & Use
        </button>
        <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">
          <i class="ti ti-x me-1"></i> Cancel
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.js"></script>
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
  // ─── Date Pickers (flatpickr) ──────────────────────────────────────────────
  function createDobPicker(selector, defaultDate = null) {
    return flatpickr(selector, {
      dateFormat: 'Y-m-d',
      altInput: true,
      altFormat: 'F j, Y',
      maxDate: 'today',
      allowInput: true,
      defaultDate: defaultDate,
    });
  }
  let dobPickerAdd = createDobPicker('#dob');
  let dobPickerEdit = createDobPicker('#edit-dob');
  // ─── Bio Character Counter ─────────────────────────────────────────────────
  function bindBioCounter(textareaId, counterId) {
    const textarea = document.getElementById(textareaId);
    const counter = document.getElementById(counterId);
    if (!textarea || !counter) return;
    const update = () => counter.textContent = `${textarea.value.length} / 1000`;
    textarea.addEventListener('input', update);
    update();
  }
  bindBioCounter('add-bio', 'add-bio-counter');
  bindBioCounter('edit-bio', 'edit-bio-counter');
  // ─── Image Upload + Croppie Preview ────────────────────────────────────────
  let croppieInstance = null;
  let cropTargetPrefix = null;
  const cropModalEl = document.getElementById('cropImageModal');
  const cropModal = new bootstrap.Modal(cropModalEl);
  function initImageCropper(prefix) {
    const trigger = document.getElementById(`${prefix}-image-trigger`);
    const input = document.getElementById(`${prefix}-image-input`);
    trigger.addEventListener('click', () => input.click());
    input.addEventListener('change', function(e) {
      const file = e.target.files[0];
      if (!file) return;
      cropTargetPrefix = prefix;
      const reader = new FileReader();
      reader.onload = function(evt) {
        cropModal.show();
        cropModalEl.addEventListener('shown.bs.modal', function handler() {
          cropModalEl.removeEventListener('shown.bs.modal', handler);
          const container = document.getElementById('croppie-container');
          container.innerHTML = '';
          croppieInstance = new Croppie(container, {
            viewport: {
              width: 220,
              height: 220,
              type: 'circle'
            },
            boundary: {
              width: 300,
              height: 300
            },
            enableExif: true,
            enableOrientation: true,
          });
          croppieInstance.bind({
            url: evt.target.result
          });
        }, {
          once: true
        });
      };
      reader.readAsDataURL(file);
    });
  }
  initImageCropper('add');
  initImageCropper('edit');
  document.getElementById('cropConfirmBtn').addEventListener('click', function() {
    if (!croppieInstance || !cropTargetPrefix) return;
    croppieInstance.result({
      type: 'blob',
      size: 'viewport',
      format: 'jpeg',
      quality: 0.9
    }).then(function(blob) {
      const fileName = `profile-${Date.now()}.jpg`;
      const file = new File([blob], fileName, {
        type: 'image/jpeg'
      });
      const dt = new DataTransfer();
      dt.items.add(file);
      document.getElementById(`${cropTargetPrefix}-image-file`).files = dt.files;
      document.getElementById(`${cropTargetPrefix}-image-preview`).src = URL.createObjectURL(blob);
      document.getElementById(`${cropTargetPrefix}-image-input`).value = '';
      cropModal.hide();
    });
  });
  cropModalEl.addEventListener('hidden.bs.modal', function() {
    if (croppieInstance) {
      croppieInstance.destroy();
      croppieInstance = null;
    }
  });
  // ─── Reset Add modal each time it opens fresh ─────────────────────────────
  document.getElementById('addStudentModal').addEventListener('hidden.bs.modal', function() {
    document.getElementById('addStudentForm').reset();
    document.getElementById('add-image-preview').src =
      "{{ asset('admin/assets/dist/images/profile/user-1.jpg') }}";
    document.getElementById('add-image-file').value = '';
    dobPickerAdd.destroy();
    dobPickerAdd = createDobPicker('#dob');
    document.getElementById('add-bio-counter').textContent = '0 / 1000';
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
      dobPickerEdit.destroy();
      dobPickerEdit = createDobPicker('#edit-dob', this.dataset.dob || null);
      document.getElementById('edit-location').value = this.dataset.location ?? '';
      document.getElementById('edit-gender').value = this.dataset.gender ?? '';
      document.getElementById('edit-nationality').value = this.dataset.nationality ?? '';
      document.getElementById('edit-alternate-phone').value = this.dataset.alternatePhone ?? '';
      document.getElementById('edit-bio').value = this.dataset.bio ?? '';
      document.getElementById('edit-bio-counter').textContent =
        `${(this.dataset.bio ?? '').length} / 1000`;
      document.getElementById('edit-image-preview').src = this.dataset.imageUrl ?? '';
      document.getElementById('edit-image-file').value = '';
      new bootstrap.Modal(document.getElementById('editStudentModal')).show();
    });
  });
</script>
@endpush
