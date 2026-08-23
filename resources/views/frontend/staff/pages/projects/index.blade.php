@extends('frontend.staff.layout.master')
@section('page_title', isset($page_title) ? $page_title : 'Projects')
@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css">
@endpush
@section('content')
    @include('frontend.staff.pages.partials.breadcrumb')

    {{-- Toolbar --}}
    <div class="card card-body">
        <form method="GET" action="{{ route('staff.projects.index') }}" id="filter-form">
            <div class="row align-items-center g-2">
                <div class="col-md-4 col-xl-4">
                    <div class="position-relative">
                        <input type="text" name="search" class="form-control product-search ps-5"
                            placeholder="Search projects..." value="{{ request('search') }}">
                        <i class="ti ti-search position-absolute top-50 start-0 translate-middle-y fs-6 text-dark ms-3"></i>
                    </div>
                </div>
                <div class="col-md-4 col-xl-3">
                    <select name="category_id" class="form-select select2" data-placeholder="All Categories"
                        onchange="document.getElementById('filter-form').submit();">
                        <option></option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ (string) request('category_id') === (string) $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 col-xl-2">
                    <select name="status" class="form-select" onchange="document.getElementById('filter-form').submit();">
                        <option value="">All Status</option>
                        <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published</option>
                        <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                    </select>
                </div>
                <div class="col-md-12 col-xl-3 d-flex justify-content-xl-end justify-content-center align-items-center gap-2 mt-3 mt-xl-0">
                    {{-- View Toggle --}}
                    <div class="btn-group" role="group" id="view-toggle">
                        <button type="button" class="btn btn-outline-secondary view-btn active" data-view="list"
                            title="List View">
                            <i class="ti ti-list fs-5"></i>
                        </button>
                        <button type="button" class="btn btn-outline-secondary view-btn" data-view="grid"
                            title="Grid View">
                            <i class="ti ti-layout-grid fs-5"></i>
                        </button>
                    </div>
                    <a href="{{ route('staff.projects.create') }}" class="btn btn-info d-flex align-items-center text-nowrap">
                        <i class="ti ti-plus text-white me-1 fs-5"></i> Add Project
                    </a>
                </div>
            </div>
        </form>
    </div>

    {{-- LIST VIEW --}}
    <div id="view-list" class="card card-body">
        <div class="table-responsive">
            <table class="table search-table align-middle text-nowrap">
                <thead class="header-item">
                    <tr>
                        <th>Project</th>
                        <th>Category</th>
                        <th>Student</th>
                        <th>Batch</th>
                        <th>Views / Likes</th>
                        <th>Status</th>
                        <th>Featured</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($projects as $project)
                        <tr class="search-items">
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="{{ $project->thumbnail_url ?? asset('admin/assets/dist/images/profile/user-1.jpg') }}"
                                        alt="{{ $project->title }}" class="rounded object-fit-cover" width="48"
                                        height="48">
                                    <div class="ms-3">
                                        <h6 class="mb-0">{{ $project->title }}</h6>
                                        <span class="fs-3 text-muted">{{ $project->excerpt ?? '-' }}</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if ($project->category)
                                    <span class="badge bg-light-info text-info rounded-pill">{{ $project->category->name }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>{{ $project->student->name ?? '-' }}</td>
                            <td>{{ $project->batch_label ?? '-' }}</td>
                            <td>
                                <span title="Views"><i class="ti ti-eye fs-5 text-muted"></i> {{ $project->short_views }}</span>
                                <span class="ms-2" title="Likes"><i class="ti ti-heart fs-5 text-muted"></i> {{ $project->likes }}</span>
                            </td>
                            <td>
                                <span class="badge {{ $project->status === 'published' ? 'bg-light-success text-success' : 'bg-light-secondary text-secondary' }} rounded-pill">
                                    {{ ucfirst($project->status) }}
                                </span>
                            </td>
                            <td>
                                <form method="POST" action="{{ route('staff.projects.toggle-featured', $project) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-link p-0 border-0"
                                        title="{{ $project->is_featured ? 'Remove from featured' : 'Mark as featured' }}">
                                        <i class="ti {{ $project->is_featured ? 'ti-star-filled text-warning' : 'ti-star text-muted' }} fs-6"></i>
                                    </button>
                                </form>
                            </td>
                            <td>
                                <div class="action-btn">
                                    <a href="{{ route('staff.projects.edit', $project) }}" class="text-info" title="Edit">
                                        <i class="ti ti-edit fs-5"></i>
                                    </a>
                                    <a href="javascript:void(0)" class="text-danger ms-2 btn-delete-project"
                                        title="Delete" data-id="{{ $project->id }}" data-name="{{ $project->title }}">
                                        <i class="ti ti-trash fs-5"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">
                                <i class="ti ti-apps-off fs-6 me-1"></i>
                                No projects found{{ request('search') ? ' for "' . request('search') . '"' : '' }}.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($projects->hasPages())
            {{ $projects->links('frontend.staff.pages.pagination.custom') }}
        @endif
    </div>

    {{-- GRID VIEW --}}
    <div id="view-grid" style="display:none;">
        <div class="row g-3">
            @forelse ($projects as $project)
                <div class="col-sm-6 col-md-4 col-xl-3">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="position-relative">
                            <img src="{{ $project->thumbnail_url ?? asset('admin/assets/dist/images/profile/user-1.jpg') }}"
                                alt="{{ $project->title }}" class="card-img-top object-fit-cover" style="height:150px;">
                            @if ($project->is_featured)
                                <span class="badge bg-warning text-dark position-absolute top-0 end-0 m-2">
                                    <i class="ti ti-star-filled fs-5"></i> Featured
                                </span>
                            @endif
                        </div>
                        <div class="card-body d-flex flex-column">
                            @if ($project->category)
                                <span class="badge bg-light-info text-info rounded-pill mb-2 align-self-start">{{ $project->category->name }}</span>
                            @endif
                            <h6 class="mb-1 fw-semibold">{{ $project->title }}</h6>
                            <span class="fs-3 text-muted mb-2">{{ $project->student->name ?? '-' }} @if($project->batch_label) &middot; {{ $project->batch_label }} @endif</span>
                            <div class="mt-auto d-flex align-items-center justify-content-between">
                                <span class="badge {{ $project->status === 'published' ? 'bg-light-success text-success' : 'bg-light-secondary text-secondary' }} rounded-pill">
                                    {{ ucfirst($project->status) }}
                                </span>
                                <span class="fs-3 text-muted">
                                    <i class="ti ti-eye fs-5"></i> {{ $project->short_views }}
                                    <i class="ti ti-heart fs-5 ms-2"></i> {{ $project->likes }}
                                </span>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-0 d-flex justify-content-center gap-2 pb-3">
                            <a href="{{ route('staff.projects.edit', $project) }}" class="btn btn-sm btn-outline-info">
                                <i class="ti ti-edit me-1"></i> Edit
                            </a>
                            <a href="javascript:void(0)" class="btn btn-sm btn-outline-danger btn-delete-project"
                                data-id="{{ $project->id }}" data-name="{{ $project->title }}">
                                <i class="ti ti-trash me-1"></i> Delete
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5 text-muted">
                    <i class="ti ti-apps-off fs-1 d-block mb-2"></i>
                    No projects found{{ request('search') ? ' for "' . request('search') . '"' : '' }}.
                </div>
            @endforelse
        </div>
        @if ($projects->hasPages())
            <div class="mt-3">
                {{ $projects->links('frontend.staff.pages.pagination.custom') }}
            </div>
        @endif
    </div>

    {{-- DELETE MODAL --}}
    <div class="modal fade" id="deleteProjectModal" tabindex="-1" aria-hidden="true" style="display:none;">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header d-flex align-items-center">
                    <h5 class="modal-title text-danger">
                        <i class="ti ti-trash me-2"></i> Delete Project
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <i class="ti ti-alert-triangle text-warning" style="font-size: 3rem;"></i>
                    <h5 class="mt-3">Are you sure?</h5>
                    <p class="text-muted mb-0">You are about to delete <strong id="delete-project-name"></strong>. <br>
                        This action cannot be undone.</p>
                </div>
                <div class="modal-footer justify-content-center">
                    <form id="deleteProjectForm" method="POST">
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
@endsection
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                theme: 'bootstrap-5',
                width: '100%',
                allowClear: true,
                placeholder: function() {
                    return $(this).data('placeholder') || 'Select an option';
                }
            });
        });

        // ─── View Toggle (persisted in localStorage) ───────────────────────────────
        const VIEW_KEY = 'staff_projects_view_preference';
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

        // ─── Delete ─────────────────────────────────────────────────────────────────
        document.querySelectorAll('.btn-delete-project').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const id = this.dataset.id;
                const name = this.dataset.name;
                document.getElementById('delete-project-name').textContent = name;
                document.getElementById('deleteProjectForm').action = `/staff/projects/${id}`;
                new bootstrap.Modal(document.getElementById('deleteProjectModal')).show();
            });
        });
    </script>
@endpush
