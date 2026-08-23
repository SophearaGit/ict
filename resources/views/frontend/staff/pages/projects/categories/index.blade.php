@extends('frontend.staff.layout.master')
@section('page_title', isset($page_title) ? $page_title : 'Project Categories')
@section('content')
    @include('frontend.staff.pages.partials.breadcrumb')


    {{-- Toolbar --}}
    <div class="card card-body">
        <form method="GET" action="{{ route('staff.project-categories.index') }}" id="filter-form">
            <div class="row align-items-center g-2">
                <div class="col-md-5 col-xl-5">
                    <div class="position-relative">
                        <input type="text" name="search" class="form-control product-search ps-5"
                            placeholder="Search categories..." value="{{ request('search') }}">
                        <i class="ti ti-search position-absolute top-50 start-0 translate-middle-y fs-6 text-dark ms-3"></i>
                    </div>
                </div>
                <div class="col-md-4 col-xl-3">
                    <select name="status" class="form-select" onchange="document.getElementById('filter-form').submit();">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-md-3 col-xl-4 d-flex justify-content-end">
                    <a href="javascript:void(0)" class="btn btn-info d-flex align-items-center text-nowrap" data-bs-toggle="modal"
                        data-bs-target="#addCategoryModal">
                        <i class="ti ti-plus text-white me-1 fs-5"></i> Add Category
                    </a>
                </div>
            </div>
        </form>
    </div>

    <div class="card card-body">
        <div class="table-responsive">
            <table class="table search-table align-middle text-nowrap">
                <thead class="header-item">
                    <tr>
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Projects</th>
                        <th>Status</th>
                        <th>Sort Order</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categories as $category)
                        <tr>
                            <td>{{ $category->name }}</td>
                            <td><span class="text-muted">{{ $category->slug }}</span></td>
                            <td>{{ $category->projects_count }}</td>
                            <td>
                                <span class="badge {{ $category->is_active ? 'bg-light-success text-success' : 'bg-light-secondary text-secondary' }} rounded-pill">
                                    {{ $category->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>{{ $category->sort_order }}</td>
                            <td>
                                <div class="action-btn">
                                    <a href="javascript:void(0)" class="text-info btn-edit-category" title="Edit"
                                        data-id="{{ $category->id }}" data-name="{{ $category->name }}"
                                        data-is-active="{{ $category->is_active ? 1 : 0 }}"
                                        data-sort-order="{{ $category->sort_order }}">
                                        <i class="ti ti-edit fs-5"></i>
                                    </a>
                                    <a href="javascript:void(0)" class="text-danger ms-2 btn-delete-category"
                                        title="Delete" data-id="{{ $category->id }}" data-name="{{ $category->name }}">
                                        <i class="ti ti-trash fs-5"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                <i class="ti ti-category-off fs-6 me-1"></i>
                                No categories found{{ request('search') ? ' for "' . request('search') . '"' : '' }}.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($categories->hasPages())
            {{ $categories->links('frontend.staff.pages.pagination.custom') }}
        @endif
    </div>

    {{-- ADD MODAL --}}
    <div class="modal fade" id="addCategoryModal" tabindex="-1" aria-hidden="true" style="display:none;">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header d-flex align-items-center">
                    <h5 class="modal-title">Add Project Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('staff.project-categories.store') }}" id="addCategoryForm">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                placeholder="e.g. Web Development" value="{{ old('name') }}">
                            @error('name')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Sort Order</label>
                            <input type="number" name="sort_order" class="form-control" min="0"
                                value="{{ old('sort_order', 0) }}">
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" id="add-is-active"
                                name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="add-is-active">Active (visible on the filter tabs)</label>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success rounded-pill px-4"
                        onclick="document.getElementById('addCategoryForm').submit();">
                        <i class="ti ti-device-floppy me-1"></i> Save
                    </button>
                    <button class="btn btn-danger rounded-pill px-4" data-bs-dismiss="modal">
                        <i class="ti ti-x me-1"></i> Discard
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- EDIT MODAL --}}
    <div class="modal fade" id="editCategoryModal" tabindex="-1" aria-hidden="true" style="display:none;">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header d-flex align-items-center">
                    <h5 class="modal-title">Edit Project Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" id="editCategoryForm">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Name <span class="text-danger">*</span></label>
                            <input type="text" id="edit-name" name="name" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Sort Order</label>
                            <input type="number" id="edit-sort-order" name="sort_order" class="form-control" min="0">
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" id="edit-is-active"
                                name="is_active" value="1">
                            <label class="form-check-label" for="edit-is-active">Active (visible on the filter tabs)</label>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success rounded-pill px-4"
                        onclick="document.getElementById('editCategoryForm').submit();">
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
    <div class="modal fade" id="deleteCategoryModal" tabindex="-1" aria-hidden="true" style="display:none;">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header d-flex align-items-center">
                    <h5 class="modal-title text-danger">
                        <i class="ti ti-trash me-2"></i> Delete Category
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <i class="ti ti-alert-triangle text-warning" style="font-size: 3rem;"></i>
                    <h5 class="mt-3">Are you sure?</h5>
                    <p class="text-muted mb-0">You are about to delete <strong id="delete-category-name"></strong>. <br>
                        Projects in this category will keep their data but lose the category link.</p>
                </div>
                <div class="modal-footer justify-content-center">
                    <form id="deleteCategoryForm" method="POST">
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

    {{-- Reopen add modal on validation error --}}
    @if ($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var modal = new bootstrap.Modal(document.getElementById('addCategoryModal'));
                modal.show();
            });
        </script>
    @endif
@endsection
@push('scripts')
    <script>
        // ─── Edit ───────────────────────────────────────────────────────────────────
        document.querySelectorAll('.btn-edit-category').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const id = this.dataset.id;
                const form = document.getElementById('editCategoryForm');
                form.action = `/staff/project-categories/${id}`;
                document.getElementById('edit-name').value = this.dataset.name ?? '';
                document.getElementById('edit-sort-order').value = this.dataset.sortOrder ?? 0;
                document.getElementById('edit-is-active').checked = this.dataset.isActive === '1';
                new bootstrap.Modal(document.getElementById('editCategoryModal')).show();
            });
        });
        // ─── Delete ─────────────────────────────────────────────────────────────────
        document.querySelectorAll('.btn-delete-category').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const id = this.dataset.id;
                const name = this.dataset.name;
                document.getElementById('delete-category-name').textContent = name;
                document.getElementById('deleteCategoryForm').action = `/staff/project-categories/${id}`;
                new bootstrap.Modal(document.getElementById('deleteCategoryModal')).show();
            });
        });
    </script>
@endpush
