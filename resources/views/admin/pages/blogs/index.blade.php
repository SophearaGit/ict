@extends('admin.layouts.master')
@section('page_title', 'Blogs')
@push('styles')
@endpush
@section('content')
    <div class="row">
        <!-- Page Header -->
        <div class="col-lg-12 col-md-12 col-12">
            <div class="border-bottom pb-3 mb-3 d-md-flex align-items-center justify-content-between">
                <div class="mb-3 mb-md-0">
                    <h1 class="mb-1 h2 fw-bold">Blogs</h1>
                    <!-- Breadcrumb -->
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Blogs</li>
                        </ol>
                    </nav>
                </div>
                <div>
                    <a href="{{ route('admin.blogs.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-lg me-1"></i> New Blog
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <form method="GET" class="row g-2 align-items-center">
                <div class="col-md-4">
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                        placeholder="Search title...">
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">All statuses</option>
                        <option value="draft" @selected(request('status') === 'draft')>Draft</option>
                        <option value="published" @selected(request('status') === 'published')>Published</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="type" class="form-select">
                        <option value="">All types</option>
                        @foreach (['article', 'facebook', 'tiktok', 'youtube'] as $type)
                            <option value="{{ $type }}" @selected(request('type') === $type)>
                                {{ ucfirst($type) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-outline-primary w-100">Filter</button>
                </div>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Thumbnail</th>
                        <th>Title</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Featured</th>
                        <th>Views</th>
                        <th>Published</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($blogs as $blog)
                        <tr>
                            <td>
                                @if ($blog->thumbnail_url)
                                    <img src="{{ $blog->thumbnail_url }}" alt="" class="rounded" width="56"
                                        height="40" style="object-fit: cover;">
                                @else
                                    <div class="avatar avatar-sm avatar-light-primary rounded">
                                        <span class="avatar-initials rounded">{{ Str::substr($blog->title, 0, 1) }}</span>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div class="fw-semibold text-gray-800">{{ $blog->title }}</div>
                                <div class="text-body small">{{ Str::limit($blog->excerpt, 60) }}</div>
                            </td>
                            <td><span
                                    class="badge bg-light-secondary text-dark-secondary text-capitalize">{{ $blog->type }}</span>
                            </td>
                            <td>
                                @if ($blog->status === 'published')
                                    <span class="badge bg-light-success text-dark-success">Published</span>
                                @else
                                    <span class="badge bg-light-warning text-dark-warning">Draft</span>
                                @endif
                            </td>
                            <td>
                                @if ($blog->is_featured)
                                    <i class="bi bi-star-fill text-warning"></i>
                                @else
                                    <i class="bi bi-star text-gray-400"></i>
                                @endif
                            </td>
                            <td>{{ number_format($blog->views) }}</td>
                            <td>{{ $blog->published_at?->format('M d, Y') ?? '—' }}</td>
                            <td class="text-end">
                                <div class="dropdown">
                                    <button class="btn btn-icon btn-sm btn-ghost" type="button" data-bs-toggle="dropdown">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item" href="{{ route('admin.blogs.show', $blog) }}"><i
                                                    class="bi bi-eye dropdown-item-icon"></i> View</a></li>
                                        <li><a class="dropdown-item" href="{{ route('admin.blogs.edit', $blog) }}"><i
                                                    class="bi bi-pencil dropdown-item-icon"></i> Edit</a></li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li>
                                            <form action="{{ route('admin.blogs.destroy', $blog) }}" method="POST"
                                                onsubmit="return confirm('Delete this blog post?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger">
                                                    <i class="bi bi-trash dropdown-item-icon"></i> Delete
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-body">No blogs found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($blogs->hasPages())
            <div class="card-footer">
                {{ $blogs->links() }}
            </div>
        @endif
    </div>
@endsection
@push('scripts')
@endpush
