@extends('admin.layouts.master')
@section('page_title', $blog->title)
@push('styles')
    <style>
        .blog-media-wrap {
            position: relative;
            width: 100%;
            overflow: hidden;
            border-radius: var(--gk-border-radius-lg);
            background-color: var(--gk-gray-900);
        }

        /* Landscape videos (normal FB posts, YouTube, TikTok landscape) */
        .blog-media-wrap.ratio-landscape {
            padding-top: 56.25%;
            /* 16:9 */
        }

        /* Portrait / Reels — capped height, centered, no stretch */
        .blog-media-wrap.ratio-portrait {
            max-width: 420px;
            margin-left: auto;
            margin-right: auto;
            aspect-ratio: 9 / 16;
        }

        .blog-media-wrap.ratio-landscape iframe,
        .blog-media-wrap.ratio-landscape video,
        .blog-media-wrap.ratio-landscape>div {
            position: absolute !important;
            top: 0;
            left: 0;
            width: 100% !important;
            height: 100% !important;
            max-width: none !important;
            border: 0;
        }

        .blog-media-wrap.ratio-portrait iframe,
        .blog-media-wrap.ratio-portrait video,
        .blog-media-wrap.ratio-portrait>div {
            width: 100% !important;
            height: 100% !important;
            max-width: none !important;
            border: 0;
            display: block;
        }

        .blog-type-icon {
            width: 3rem;
            height: 3rem;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }
    </style>
@endpush
@section('content')
    <div class="row">
        <!-- Page Header -->
        <div class="col-lg-12 col-md-12 col-12">
            <div class="border-bottom pb-3 mb-3 d-md-flex align-items-center justify-content-between">
                <div class="mb-3 mb-md-0">
                    <h1 class="mb-1 h2 fw-bold">{{ $blog->title }}</h1>
                    <!-- Breadcrumb -->
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.blogs.index') }}">Blogs</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">{{ $blog->title }}</li>
                        </ol>
                    </nav>
                    <div class="d-flex flex-wrap align-items-center gap-2 mt-2">
                        <span class="badge bg-light-secondary text-dark-secondary text-capitalize">
                            <i
                                class="bi bi-{{ match ($blog->type) {
                                    'facebook' => 'facebook',
                                    'tiktok' => 'tiktok',
                                    'youtube' => 'youtube',
                                    default => 'file-text',
                                } }} me-1"></i>{{ $blog->type }}
                        </span>
                        <span
                            class="badge {{ $blog->status === 'published' ? 'bg-light-success text-dark-success' : 'bg-light-warning text-dark-warning' }}">
                            <i
                                class="bi bi-{{ $blog->status === 'published' ? 'check-circle' : 'pencil' }} me-1"></i>{{ ucfirst($blog->status) }}
                        </span>
                        @if ($blog->is_featured)
                            <span class="badge bg-light-primary text-dark-primary">
                                <i class="bi bi-star-fill me-1"></i>Featured
                            </span>
                        @endif
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.blogs.edit', $blog) }}" class="btn btn-primary">
                        <i class="bi bi-pencil me-1"></i> Edit
                    </a>
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary btn-icon" type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
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
            </div>
        </div>
    </div>
    <div class="row g-4">
        <div class="col-lg-8">
            {{-- Media: embed takes priority (autoplay-ready), thumbnail as fallback --}}
            @if ($blog->type !== 'article' && $blog->is_embed_html)
                <div
                    class="blog-media-wrap {{ $blog->is_portrait_embed ? 'ratio-portrait' : 'ratio-landscape' }} mb-4 shadow-sm">
                    {!! $blog->embed_url !!}
                </div>
            @elseif ($blog->type !== 'article' && $blog->embed_url)
                <div class="card mb-4">
                    <div class="card-body text-center py-5">
                        @if ($blog->thumbnail_url)
                            <img src="{{ $blog->thumbnail_url }}" alt="" class="img-fluid rounded mb-3"
                                style="max-height: 320px;">
                        @endif
                        <p class="text-body mb-3">This {{ $blog->type }} post links to external content.</p>
                        <a href="{{ $blog->embed_url }}" target="_blank" class="btn btn-outline-primary">
                            <i class="bi bi-box-arrow-up-right me-1"></i> Open on {{ ucfirst($blog->type) }}
                        </a>
                    </div>
                </div>
            @elseif ($blog->thumbnail_url)
                <div class="mb-4">
                    <img src="{{ $blog->thumbnail_url }}" alt="{{ $blog->title }}"
                        class="img-fluid rounded shadow-sm w-100" style="max-height: 420px; object-fit: cover;">
                </div>
            @endif
            <div class="card">
                <div class="card-body">
                    @if ($blog->excerpt)
                        <p class="lead border-start border-3 border-primary ps-3 text-gray-700">{{ $blog->excerpt }}</p>
                    @endif
                    @if ($blog->type === 'article')
                        @if ($blog->thumbnail_url)
                            <img src="{{ $blog->thumbnail_url }}" alt="" class="img-fluid rounded mb-4">
                        @endif
                        <div class="fs-6 lh-lg">{!! $blog->content !!}</div>
                    @endif
                </div>
            </div>
            @if ($blog->meta_title || $blog->meta_description)
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-search me-1"></i> SEO Preview</h5>
                    </div>
                    <div class="card-body">
                        <div class="text-primary fs-6 text-truncate">{{ $blog->meta_title ?: $blog->title }}</div>
                        <div class="text-success small mb-1">{{ url('/blog/' . $blog->slug) }}</div>
                        <div class="text-body small">{{ $blog->meta_description ?: $blog->excerpt }}</div>
                    </div>
                </div>
            @endif
        </div>
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Details</h5>
                </div>
                <div class="list-group list-group-flush">
                    <div class="list-group-item d-flex align-items-center justify-content-between">
                        <span class="text-body"><i class="bi bi-person me-2"></i>Author</span>
                        <span class="fw-medium">{{ $blog->admin?->name ?? '—' }}</span>
                    </div>
                    <div class="list-group-item d-flex align-items-center justify-content-between">
                        <span class="text-body"><i class="bi bi-eye me-2"></i>Views</span>
                        <span class="fw-medium">{{ number_format($blog->views) }}</span>
                    </div>
                    <div class="list-group-item d-flex align-items-center justify-content-between">
                        <span class="text-body"><i class="bi bi-calendar-check me-2"></i>Published</span>
                        <span class="fw-medium">{{ $blog->published_at?->format('M d, Y') ?? '—' }}</span>
                    </div>
                    <div class="list-group-item d-flex align-items-center justify-content-between">
                        <span class="text-body"><i class="bi bi-clock-history me-2"></i>Last Updated</span>
                        <span class="fw-medium">{{ $blog->updated_at->format('M d, Y') }}</span>
                    </div>
                    <div class="list-group-item">
                        <span class="text-body d-block mb-1"><i class="bi bi-link-45deg me-2"></i>Slug</span>
                        <code class="small">{{ $blog->slug }}</code>
                    </div>
                </div>
            </div>
            @if ($blog->type !== 'article' && $blog->embed_url)
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Source</h5>
                    </div>
                    <div class="card-body">
                        <a href="{{ $blog->is_embed_html ? '#' : $blog->embed_url }}"
                            @unless ($blog->is_embed_html) target="_blank" @endunless
                            class="text-break small text-body">
                            <i class="bi bi-{{ $blog->type }} me-1"></i>
                            {{ $blog->is_embed_html ? 'Embedded ' . ucfirst($blog->type) . ' content' : $blog->embed_url }}
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
@push('scripts')
@endpush
