@extends('frontend.staff.layout.master')
@section('page_title', isset($page_title) ? $page_title : 'Course Categories')
@section('content')
    @include('frontend.staff.pages.partials.breadcrumb')
    {{-- Toolbar --}}
    <div class="card card-body">
        <div class="row align-items-center">
            <div class="col-md-4 col-xl-3">
                <form method="GET" action="{{ route('staff.course-categories.index') }}" id="search-form">
                    <div class="position-relative">
                        <input type="text" name="search" class="form-control product-search ps-5"
                            placeholder="Search Categories..." value="{{ request('search') }}">
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
                    data-bs-target="#addCategoryModal">
                    <i class="ti ti-category-plus text-white me-1 fs-5"></i> Add Category
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
                        <th>Category</th>
                        <th>Parent</th>
                        <th>Status</th>
                        <th>Featured</th>
                        <th>Sort Order</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                        <tr class="search-items">
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    @if ($category->thumbnail)
                                        <img src="{{ asset($category->thumbnail) }}" alt="thumb"
                                            class="rounded object-fit-cover" width="38" height="38">
                                    @else
                                        <div class="rounded bg-light-primary d-flex align-items-center justify-content-center"
                                            style="width:38px;height:38px;">
                                            <i class="{{ $category->icon ?? 'ti ti-category' }} text-primary fs-5"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <h6 class="mb-0">{{ $category->name }}</h6>
                                        <small class="text-muted">{{ $category->slug }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $category->parent?->name ?? '<span class="text-muted">—</span>' }}</td>
                            <td>
                                <span
                                    class="badge {{ $category->is_active ? 'bg-light-success text-success' : 'bg-light-danger text-danger' }} rounded-pill">
                                    {{ $category->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                @if ($category->is_featured)
                                    <span class="badge bg-light-warning text-warning rounded-pill">
                                        <i class="ti ti-star-filled me-1"></i>Featured
                                    </span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>{{ $category->sort_order ?? 0 }}</td>
                            <td>
                                <div class="action-btn">
                                    <a href="javascript:void(0)" class="text-info btn-edit-category" title="Edit"
                                        data-id="{{ $category->id }}" data-name="{{ $category->name }}"
                                        data-slug="{{ $category->slug }}" data-description="{{ $category->description }}"
                                        data-icon="{{ $category->icon }}" data-parent-id="{{ $category->parent_id }}"
                                        data-is-active="{{ $category->is_active ? '1' : '0' }}"
                                        data-is-featured="{{ $category->is_featured ? '1' : '0' }}"
                                        data-sort-order="{{ $category->sort_order }}"
                                        data-meta-title="{{ $category->meta_title }}"
                                        data-meta-description="{{ $category->meta_description }}">
                                        <i class="ti ti-edit fs-5"></i>
                                    </a>
                                    <a href="javascript:void(0)" class="text-danger ms-2 btn-delete-category" title="Delete"
                                        data-id="{{ $category->id }}" data-name="{{ $category->name }}">
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

    {{-- GRID VIEW --}}
    <div id="view-grid" style="display:none;">
        <div class="row g-3">
            @forelse($categories as $category)
                <div class="col-sm-6 col-md-4 col-xl-3">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-body d-flex flex-column align-items-center text-center pt-4">
                            @if ($category->thumbnail)
                                <img src="{{ asset($category->thumbnail) }}" alt="thumb"
                                    class="rounded object-fit-cover mb-3 border border-3 border-light shadow-sm"
                                    width="72" height="72">
                            @else
                                <div class="rounded-circle bg-light-primary d-flex align-items-center justify-content-center mb-3 border border-3 border-light shadow-sm"
                                    style="width:72px;height:72px;">
                                    <i class="{{ $category->icon ?? 'ti ti-category' }} text-primary"
                                        style="font-size:2rem;"></i>
                                </div>
                            @endif
                            <h6 class="mb-0 fw-semibold">{{ $category->name }}</h6>
                            <small class="text-muted">{{ $category->slug }}</small>
                            <div class="d-flex gap-1 flex-wrap justify-content-center mt-2">
                                <span
                                    class="badge {{ $category->is_active ? 'bg-light-success text-success' : 'bg-light-danger text-danger' }} rounded-pill">
                                    {{ $category->is_active ? 'Active' : 'Inactive' }}
                                </span>
                                @if ($category->is_featured)
                                    <span class="badge bg-light-warning text-warning rounded-pill">
                                        <i class="ti ti-star-filled me-1"></i>Featured
                                    </span>
                                @endif
                            </div>
                            @if ($category->description)
                                <p class="text-muted small mt-2 mb-0"
                                    style="display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
                                    {{ $category->description }}
                                </p>
                            @endif
                            <hr class="w-100 my-3">
                            <div class="w-100 text-start small text-muted">
                                <div class="mb-1">
                                    <i class="ti ti-sitemap me-1"></i>
                                    Parent: {{ $category->parent?->name ?? '—' }}
                                </div>
                                <div>
                                    <i class="ti ti-sort-ascending me-1"></i>
                                    Order: {{ $category->sort_order ?? 0 }}
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-0 d-flex justify-content-center gap-2 pb-3">
                            <a href="javascript:void(0)" class="btn btn-sm btn-outline-info btn-edit-category"
                                data-id="{{ $category->id }}" data-name="{{ $category->name }}"
                                data-slug="{{ $category->slug }}" data-description="{{ $category->description }}"
                                data-icon="{{ $category->icon }}" data-parent-id="{{ $category->parent_id }}"
                                data-is-active="{{ $category->is_active ? '1' : '0' }}"
                                data-is-featured="{{ $category->is_featured ? '1' : '0' }}"
                                data-sort-order="{{ $category->sort_order }}"
                                data-meta-title="{{ $category->meta_title }}"
                                data-meta-description="{{ $category->meta_description }}">
                                <i class="ti ti-edit me-1"></i> Edit
                            </a>
                            <a href="javascript:void(0)" class="btn btn-sm btn-outline-danger btn-delete-category"
                                data-id="{{ $category->id }}" data-name="{{ $category->name }}">
                                <i class="ti ti-trash me-1"></i> Delete
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5 text-muted">
                    <i class="ti ti-category-off fs-1 d-block mb-2"></i>
                    No categories found{{ request('search') ? ' for "' . request('search') . '"' : '' }}.
                </div>
            @endforelse
        </div>
        @if ($categories->hasPages())
            <div class="mt-3">
                {{ $categories->links('frontend.staff.pages.pagination.custom') }}
            </div>
        @endif
    </div>

    {{-- CREATE MODAL --}}
    <div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel"
        style="display:none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header d-flex align-items-center">
                    <h5 class="modal-title" id="addCategoryModalLabel">Add Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('staff.course-categories.store') }}" id="addCategoryForm"
                        enctype="multipart/form-data">
                        @csrf
                        {{-- Basic Info --}}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name"
                                        class="form-control @error('name') is-invalid @enderror"
                                        placeholder="e.g. Web Development" value="{{ old('name') }}">
                                    @error('name')
                                        <span class="text-danger small">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Slug</label>
                                    <input type="text" name="slug" id="add-slug"
                                        class="form-control @error('slug') is-invalid @enderror"
                                        placeholder="Auto-generated from name" value="{{ old('slug') }}">
                                    @error('slug')
                                        <span class="text-danger small">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Parent Category</label>
                                    <select name="parent_id" class="form-select">
                                        <option value="">— None (Top Level) —</option>
                                        @foreach ($parentCategories as $parent)
                                            <option value="{{ $parent->id }}"
                                                {{ old('parent_id') == $parent->id ? 'selected' : '' }}>
                                                {{ $parent->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Icon Class</label>
                                    <input type="text" name="icon" class="form-control"
                                        placeholder="e.g. ti ti-code" value="{{ old('icon') }}">
                                    <small class="text-muted">Tabler Icons class (used when no thumbnail)</small>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Description</label>
                            <textarea name="description" class="form-control" rows="3"
                                placeholder="Short description of this category...">{{ old('description') }}</textarea>
                        </div>
                        {{-- Media --}}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Thumbnail</label>
                                    <input type="file" name="thumbnail" class="form-control" accept="image/*">
                                    <small class="text-muted">Accepted: JPG, PNG, WEBP</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Sort Order</label>
                                    <input type="number" name="sort_order" class="form-control" placeholder="0"
                                        value="{{ old('sort_order', 0) }}" min="0">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold d-block">Flags</label>
                                    <div class="form-check form-switch mt-1">
                                        <input class="form-check-input" type="checkbox" name="is_active"
                                            id="add-is-active" value="1"
                                            {{ old('is_active', '1') == '1' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="add-is-active">Active</label>
                                    </div>
                                    <div class="form-check form-switch mt-1">
                                        <input class="form-check-input" type="checkbox" name="is_featured"
                                            id="add-is-featured" value="1"
                                            {{ old('is_featured') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="add-is-featured">Featured</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- SEO --}}
                        <hr class="my-3">
                        <p class="fw-semibold text-muted small mb-2"><i class="ti ti-world me-1"></i>SEO (Optional)</p>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Meta Title</label>
                                    <input type="text" name="meta_title" class="form-control"
                                        placeholder="SEO page title" value="{{ old('meta_title') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Meta Description</label>
                                    <input type="text" name="meta_description" class="form-control"
                                        placeholder="SEO description" value="{{ old('meta_description') }}">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success rounded-pill px-4"
                        onclick="document.getElementById('addCategoryForm').submit();">
                        <i class="ti ti-category-plus me-1"></i> Add Category
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
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header d-flex align-items-center">
                    <h5 class="modal-title">Edit Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" id="editCategoryForm" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        {{-- Basic Info --}}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" id="edit-name" class="form-control"
                                        placeholder="e.g. Web Development">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Slug</label>
                                    <input type="text" name="slug" id="edit-slug" class="form-control"
                                        placeholder="Auto-generated from name">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Parent Category</label>
                                    <select name="parent_id" id="edit-parent-id" class="form-select">
                                        <option value="">— None (Top Level) —</option>
                                        @foreach ($parentCategories as $parent)
                                            <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Icon Class</label>
                                    <input type="text" name="icon" id="edit-icon" class="form-control"
                                        placeholder="e.g. ti ti-code">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Description</label>
                            <textarea name="description" id="edit-description" class="form-control" rows="3"
                                placeholder="Short description..."></textarea>
                        </div>
                        {{-- Media --}}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Thumbnail</label>
                                    <input type="file" name="thumbnail" class="form-control" accept="image/*">
                                    <small class="text-muted">Leave blank to keep current image</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Sort Order</label>
                                    <input type="number" name="sort_order" id="edit-sort-order" class="form-control"
                                        min="0">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold d-block">Flags</label>
                                    <div class="form-check form-switch mt-1">
                                        <input class="form-check-input" type="checkbox" name="is_active"
                                            id="edit-is-active" value="1">
                                        <label class="form-check-label" for="edit-is-active">Active</label>
                                    </div>
                                    <div class="form-check form-switch mt-1">
                                        <input class="form-check-input" type="checkbox" name="is_featured"
                                            id="edit-is-featured" value="1">
                                        <label class="form-check-label" for="edit-is-featured">Featured</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- SEO --}}
                        <hr class="my-3">
                        <p class="fw-semibold text-muted small mb-2"><i class="ti ti-world me-1"></i>SEO (Optional)</p>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Meta Title</label>
                                    <input type="text" name="meta_title" id="edit-meta-title" class="form-control"
                                        placeholder="SEO page title">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Meta Description</label>
                                    <input type="text" name="meta_description" id="edit-meta-description"
                                        class="form-control" placeholder="SEO description">
                                </div>
                            </div>
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
                    <p class="text-muted mb-0">You are about to delete <strong id="delete-category-name"></strong>.<br>
                        This action cannot be undone.</p>
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

    {{-- Reopen modal on validation error --}}
    @if ($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                new bootstrap.Modal(document.getElementById('addCategoryModal')).show();
            });
        </script>
    @endif
@endsection

@push('scripts')
    <script>
        // ─── View Toggle (persisted in localStorage) ─────────────────────────────
        const VIEW_KEY = 'category_view_preference';
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

        const savedView = localStorage.getItem(VIEW_KEY) || 'list';
        setView(savedView);
        viewBtns.forEach(btn => {
            btn.addEventListener('click', () => setView(btn.dataset.view));
        });

        // ─── Auto-generate slug from name (Add form only) ────────────────────────
        const addNameInput = document.querySelector('#addCategoryForm input[name="name"]');
        const addSlugInput = document.getElementById('add-slug');
        if (addNameInput && addSlugInput) {
            addNameInput.addEventListener('input', function() {
                if (!addSlugInput.dataset.manually) {
                    addSlugInput.value = this.value
                        .toLowerCase()
                        .replace(/[^a-z0-9\s-]/g, '')
                        .trim()
                        .replace(/\s+/g, '-');
                }
            });
            addSlugInput.addEventListener('input', function() {
                this.dataset.manually = this.value ? '1' : '';
            });
        }

        // ─── Delete ──────────────────────────────────────────────────────────────
        document.querySelectorAll('.btn-delete-category').forEach(function(btn) {
            btn.addEventListener('click', function() {
                document.getElementById('delete-category-name').textContent = this.dataset.name;
                document.getElementById('deleteCategoryForm').action =
                    `/staff/course-categories/${this.dataset.id}`;
                new bootstrap.Modal(document.getElementById('deleteCategoryModal')).show();
            });
        });

        // ─── Edit ────────────────────────────────────────────────────────────────
        document.querySelectorAll('.btn-edit-category').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const form = document.getElementById('editCategoryForm');
                form.action = `/staff/course-categories/${this.dataset.id}`;

                document.getElementById('edit-name').value = this.dataset.name ?? '';
                document.getElementById('edit-slug').value = this.dataset.slug ?? '';
                document.getElementById('edit-description').value = this.dataset.description ?? '';
                document.getElementById('edit-icon').value = this.dataset.icon ?? '';
                document.getElementById('edit-sort-order').value = this.dataset.sortOrder ?? 0;
                document.getElementById('edit-meta-title').value = this.dataset.metaTitle ?? '';
                document.getElementById('edit-meta-description').value = this.dataset.metaDescription ?? '';

                // Parent select
                const parentSelect = document.getElementById('edit-parent-id');
                parentSelect.value = this.dataset.parentId ?? '';

                // Toggles
                document.getElementById('edit-is-active').checked = this.dataset.isActive === '1';
                document.getElementById('edit-is-featured').checked = this.dataset.isFeatured === '1';

                new bootstrap.Modal(document.getElementById('editCategoryModal')).show();
            });
        });
    </script>
@endpush
