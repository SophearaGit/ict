@extends('admin.layouts.master')
@section('page_title', 'Blogs')
@push('styles')
    <style>
        /* ── Blogs mobile card list ───────────────────────────────────────────
           Below md, the 8-column table is replaced with stacked cards so
           nothing requires horizontal scrolling to read on a phone. */
        .blog-card {
            padding: 1rem 1.25rem;
            transition: background-color .15s ease;
        }

        .blog-card:hover {
            background-color: var(--gk-list-group-action-hover-bg);
        }

        .blog-card-thumb {
            width: 56px;
            height: 56px;
            object-fit: cover;
            flex-shrink: 0;
        }
    </style>
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
                    <div class="input-group">
                        <span class="input-group-text bg-transparent">
                            <i class="fe fe-search text-muted"></i>
                        </span>
                        <input type="text" name="search" value="{{ request('search') }}"
                            class="form-control border-start-0" placeholder="Search title...">
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">All statuses</option>
                        <option value="draft" @selected(request('status') === 'draft')>Draft</option>
                        <option value="scheduled" @selected(request('status') === 'scheduled')>Scheduled</option>
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
                    <button type="submit" class="btn btn-outline-primary w-100">
                        <i class="fe fe-filter me-1"></i> Filter
                    </button>
                </div>
            </form>
        </div>

        {{-- ── Desktop / tablet (>= md): table ─────────────────────────────────── --}}
        <div class="table-responsive d-none d-md-block">
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
                                    <div class="avatar avatar-sm bg-light-primary text-primary rounded">
                                        <span class="avatar-initials rounded">{{ Str::substr($blog->title, 0, 1) }}</span>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div class="fw-semibold text-gray-800">{{ $blog->title }}</div>
                                <div class="text-body small">{{ Str::limit($blog->excerpt, 60) }}</div>
                            </td>
                            <td>
                                @switch($blog->type)
                                    @case('article')
                                        <span class="badge bg-white text-dark border">
                                            <i class="fe fe-file-text me-1"></i> Article
                                        </span>
                                    @break

                                    @case('facebook')
                                        <span class="badge bg-light-primary text-primary">
                                            <i class="fe fe-facebook me-1"></i> Facebook
                                        </span>
                                    @break

                                    @case('tiktok')
                                        <span class="badge bg-light-dark text-dark">
                                            <i class="fe fe-music me-1"></i> TikTok
                                        </span>
                                    @break

                                    @case('youtube')
                                        <span class="badge bg-light-danger text-danger">
                                            <i class="fe fe-youtube me-1"></i> YouTube
                                        </span>
                                    @break

                                    @default
                                        <span class="badge bg-light-secondary text-secondary">
                                            <i class="fe fe-globe me-1"></i> {{ ucfirst($blog->type) }}
                                        </span>
                                @endswitch
                            </td>
                            <td>
                                @if ($blog->status === 'published')
                                    <span class="badge bg-light-success text-dark-success">Published</span>
                                @elseif ($blog->status === 'scheduled')
                                    <span class="badge bg-light-info text-dark-info"
                                        title="{{ $blog->published_at?->timezone('Asia/Phnom_Penh')->format('F j, Y \a\t g:i A') }}">
                                        Scheduled
                                    </span>
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
                            <td>
                                {{ $blog->published_at?->timezone('Asia/Phnom_Penh')->format('F j, Y \a\t g:i A') ?? '—' }}
                            </td>
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
                            <td colspan="8" class="text-center py-5">
                                <div class="icon-shape icon-lg rounded-circle bg-light-secondary text-secondary mx-auto mb-3">
                                    <i class="bi bi-journal-text fs-4"></i>
                                </div>
                                <h5 class="mb-0">No blogs found.</h5>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- ── Mobile (< md): stacked cards ────────────────────────────────────── --}}
        <div class="d-md-none">
            @forelse ($blogs as $blog)
                <div class="blog-card border-bottom">
                    <div class="d-flex gap-3">
                        @if ($blog->thumbnail_url)
                            <img src="{{ $blog->thumbnail_url }}" alt="" class="rounded blog-card-thumb">
                        @else
                            <div class="avatar avatar-sm bg-light-primary text-primary rounded blog-card-thumb"
                                style="width:56px;height:56px;">
                                <span class="avatar-initials rounded">{{ Str::substr($blog->title, 0, 1) }}</span>
                            </div>
                        @endif
                        <div class="flex-grow-1 min-w-0">
                            <div class="d-flex align-items-start justify-content-between gap-2">
                                <div class="fw-semibold text-gray-800">{{ $blog->title }}</div>
                                <div class="dropdown flex-shrink-0">
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
                            </div>
                            <div class="text-body small mb-2">{{ Str::limit($blog->excerpt, 70) }}</div>
                            <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                                @switch($blog->type)
                                    @case('article')
                                        <span class="badge bg-white text-dark border">
                                            <i class="fe fe-file-text me-1"></i> Article
                                        </span>
                                    @break

                                    @case('facebook')
                                        <span class="badge bg-light-primary text-primary">
                                            <i class="fe fe-facebook me-1"></i> Facebook
                                        </span>
                                    @break

                                    @case('tiktok')
                                        <span class="badge bg-light-dark text-dark">
                                            <i class="fe fe-music me-1"></i> TikTok
                                        </span>
                                    @break

                                    @case('youtube')
                                        <span class="badge bg-light-danger text-danger">
                                            <i class="fe fe-youtube me-1"></i> YouTube
                                        </span>
                                    @break

                                    @default
                                        <span class="badge bg-light-secondary text-secondary">
                                            <i class="fe fe-globe me-1"></i> {{ ucfirst($blog->type) }}
                                        </span>
                                @endswitch

                                @if ($blog->status === 'published')
                                    <span class="badge bg-light-success text-dark-success">Published</span>
                                @elseif ($blog->status === 'scheduled')
                                    <span class="badge bg-light-info text-dark-info">Scheduled</span>
                                @else
                                    <span class="badge bg-light-warning text-dark-warning">Draft</span>
                                @endif

                                @if ($blog->is_featured)
                                    <i class="bi bi-star-fill text-warning" title="Featured"></i>
                                @endif
                            </div>
                            <div class="d-flex align-items-center justify-content-between small text-muted">
                                <span><i class="fe fe-eye me-1"></i>{{ number_format($blog->views) }} views</span>
                                <span>
                                    {{ $blog->published_at?->timezone('Asia/Phnom_Penh')->format('d M, Y') ?? '—' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center text-muted py-5">
                    <div class="icon-shape icon-lg rounded-circle bg-light-secondary text-secondary mx-auto mb-3">
                        <i class="bi bi-journal-text fs-4"></i>
                    </div>
                    <p class="mb-0 small">No blogs found.</p>
                </div>
            @endforelse
        </div>

        @if ($blogs->hasPages())
            <div class="card-footer">
                {{ $blogs->appends(request()->query())->links('components.paginate-geek') }}
            </div>
        @endif
    </div>
@endsection
@push('scripts')
@endpush
