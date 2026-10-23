@extends('frontend.staff.layout.master')
@section('page_title', $blog->title)
@push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Syne:wght@600;700;800&display=swap');

        .blog-media-wrap {
            position: relative;
            width: 100%;
            overflow: hidden;
            border-radius: 16px;
            background-color: var(--gk-gray-900, #16151f);
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

        /* ── TOP BAR: back link + badges + actions ── */
        .blog-show-topbar {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 14px;
        }

        .blog-back-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-family: 'DM Sans', sans-serif;
            font-size: 13px;
            font-weight: 600;
            color: #6b6b7a;
            text-decoration: none;
            transition: color 0.15s;
        }

        .blog-back-link:hover {
            color: #4f46e5;
        }

        .blog-show-actions {
            display: flex;
            gap: 8px;
        }

        .blog-btn-edit {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 10px 18px;
            border-radius: 11px;
            font-family: 'DM Sans', sans-serif;
            font-size: 13.5px;
            font-weight: 600;
            text-decoration: none;
            background: #0f0e17;
            color: #fff;
            border: 1px solid #0f0e17;
            transition: all 0.15s;
        }

        .blog-btn-edit:hover {
            background: #2749c4;
            border-color: #2749c4;
            color: #fff;
        }

        .blog-btn-more {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            padding: 0;
            border-radius: 11px;
            border: 1px solid #e5e5ec;
            background: #fff;
            color: #6b6b7a;
            font-size: 16px;
            line-height: 1;
        }

        .blog-btn-more:hover {
            background: #f4f4f8;
            color: #22212e;
        }

        /* ── TITLE + BYLINE ── */
        .blog-show-header {
            margin-bottom: 20px;
        }

        .blog-show-badges {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 8px;
            margin-bottom: 12px;
        }

        .blog-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 5px 12px;
            border-radius: 20px;
            font-family: 'DM Sans', sans-serif;
            font-size: 12px;
            font-weight: 600;
            white-space: nowrap;
        }

        .blog-show-title {
            font-family: 'DM Sans', sans-serif;
            font-weight: 700;
            font-size: 26px;
            line-height: 1.3;
            letter-spacing: -0.01em;
            color: #16151f;
            margin-bottom: 14px;
        }

        .blog-show-byline {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 14px;
            font-family: 'DM Sans', sans-serif;
            font-size: 13px;
            color: #8a8a9a;
        }

        .blog-show-byline span {
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .blog-show-byline i {
            font-size: 15px;
        }

        .blog-show-byline .blog-byline-author {
            color: #22212e;
            font-weight: 600;
        }

        /* ── CONTENT CARD ── */
        .blog-content-card {
            background: #fff;
            border: 1px solid #f0f1f5;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 2px 10px rgba(20, 20, 43, 0.04);
        }

        .blog-excerpt-lead {
            font-family: 'DM Sans', sans-serif;
            font-size: 15.5px;
            line-height: 1.6;
            color: #4b4b58;
            border-left: 3px solid #4f46e5;
            padding-left: 16px;
            margin-bottom: 20px;
        }

        .blog-article-body {
            font-family: 'DM Sans', sans-serif;
            font-size: 15px;
            line-height: 1.8;
            color: #3a3a46;
        }

        .blog-article-body img {
            max-width: 100%;
            border-radius: 12px;
        }

        .blog-embed-fallback {
            background: #fff;
            border: 1px solid #f0f1f5;
            border-radius: 16px;
            padding: 48px 24px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(20, 20, 43, 0.04);
        }

        .blog-embed-btn {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 10px 18px;
            border-radius: 11px;
            border: 1px solid #e5e5ec;
            color: #22212e;
            text-decoration: none;
            font-family: 'DM Sans', sans-serif;
            font-size: 13.5px;
            font-weight: 600;
            transition: all 0.15s;
        }

        .blog-embed-btn:hover {
            background: #f4f4f8;
            color: #22212e;
        }

        /* ── SIDEBAR CARDS ── */
        .blog-side-card {
            background: #fff;
            border: 1px solid #f0f1f5;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(20, 20, 43, 0.04);
            margin-bottom: 18px;
        }

        .blog-side-card-header {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 14px 18px;
            border-bottom: 1px solid #f0f1f5;
            font-family: 'DM Sans', sans-serif;
            font-weight: 700;
            font-size: 13.5px;
            color: #16151f;
        }

        .blog-side-card-header i {
            color: #4f46e5;
        }

        .blog-detail-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 18px;
            border-bottom: 1px solid #f7f7fa;
            font-family: 'DM Sans', sans-serif;
        }

        .blog-detail-row:last-child {
            border-bottom: none;
        }

        .blog-detail-label {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: #8a8a9a;
        }

        .blog-detail-label i {
            width: 26px;
            height: 26px;
            border-radius: 8px;
            background: #f4f4f8;
            color: #6b6b7a;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
        }

        .blog-detail-value {
            font-size: 13px;
            font-weight: 600;
            color: #22212e;
            text-align: right;
        }

        .blog-detail-slug {
            padding: 12px 18px;
            font-family: 'DM Sans', sans-serif;
        }

        .blog-detail-slug code {
            display: block;
            margin-top: 6px;
            font-size: 12px;
            background: #f8f8fb;
            padding: 6px 10px;
            border-radius: 7px;
            word-break: break-all;
            color: #4f46e5;
        }

        .blog-source-link {
            display: block;
            padding: 16px 18px;
            font-family: 'DM Sans', sans-serif;
            font-size: 13px;
            color: #4b4b58;
            text-decoration: none;
            word-break: break-all;
        }

        .blog-source-link:hover {
            color: #4f46e5;
        }

        .blog-seo-card {
            padding: 18px;
        }

        .blog-seo-title {
            color: #1a0dab;
            font-size: 16px;
            font-family: arial, sans-serif;
            text-overflow: ellipsis;
            overflow: hidden;
            white-space: nowrap;
        }

        .blog-seo-url {
            color: #006621;
            font-size: 13px;
            margin-bottom: 2px;
        }

        .blog-seo-desc {
            font-family: 'DM Sans', sans-serif;
            font-size: 13px;
            color: #6b6b7a;
        }

        @media (max-width: 575.98px) {
            .blog-show-title {
                font-size: 21px;
            }

            .blog-content-card {
                padding: 18px;
            }
        }
    </style>
@endpush
@section('content')
    @include('frontend.staff.pages.partials.breadcrumb')

    @php
        $wordCount = $blog->type === 'article' && $blog->content ? str_word_count(strip_tags($blog->content)) : 0;
        $readingMinutes = $wordCount > 0 ? max(1, (int) ceil($wordCount / 200)) : null;
    @endphp

    <div class="blog-show-topbar">
        <a href="{{ route('staff.blogs.index') }}" class="blog-back-link">
            <i class="ti ti-arrow-left"></i> Back to Blogs
        </a>
        <div class="blog-show-actions">
            <a href="{{ route('staff.blogs.edit', $blog) }}" class="blog-btn-edit">
                <i class="ti ti-pencil"></i> Edit
            </a>
            <div class="dropdown">
                <button class="blog-btn-more" type="button" data-bs-toggle="dropdown">
                    <i class="ti ti-dots-vertical"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <form action="{{ route('staff.blogs.destroy', $blog) }}" method="POST"
                            onsubmit="return confirm('Delete this blog post?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="ti ti-trash dropdown-item-icon"></i> Delete
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="blog-show-header">
        <div class="blog-show-badges">
            <span class="blog-badge" style="background:#f4f4f8;color:#4b4b58;">
                <i
                    class="ti ti-{{ match ($blog->type) {
                        'facebook' => 'brand-facebook',
                        'tiktok' => 'brand-tiktok',
                        'youtube' => 'brand-youtube',
                        default => 'file-text',
                    } }}"></i>{{ ucfirst($blog->type) }}
            </span>
            @if ($blog->status === 'published')
                <span class="blog-badge" style="background:#dcfce7;color:#16a34a;">
                    <i class="ti ti-circle-check"></i> Published
                </span>
            @elseif ($blog->status === 'scheduled')
                <span class="blog-badge" style="background:#e0f2fe;color:#0ea5e9;">
                    <i class="ti ti-clock-hour-4"></i> Scheduled
                </span>
            @else
                <span class="blog-badge" style="background:#fef3c7;color:#d97706;">
                    <i class="ti ti-pencil"></i> Draft
                </span>
            @endif
            @if ($blog->is_featured)
                <span class="blog-badge" style="background:#eef2ff;color:#4f46e5;">
                    <i class="ti ti-star-filled"></i> Featured
                </span>
            @endif
            @if ($readingMinutes)
                <span class="blog-badge" style="background:#f4f4f8;color:#6b6b7a;">
                    <i class="ti ti-clock"></i> {{ $readingMinutes }} min read
                </span>
            @endif
        </div>

        <h1 class="blog-show-title">{{ $blog->title }}</h1>

        <div class="blog-show-byline">
            <span><i class="ti ti-user-circle"></i> <span
                    class="blog-byline-author">{{ $blog->author()?->name ?? '—' }}</span></span>
            <span><i class="ti ti-calendar-check"></i>
                {{ $blog->published_at?->timezone('Asia/Phnom_Penh')->format('M d, Y') ?? 'Not published' }}</span>
            <span><i class="ti ti-eye"></i> {{ number_format($blog->views) }} views</span>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            {{-- Media: embed takes priority (autoplay-ready), thumbnail as fallback --}}
            @if ($blog->type !== 'article' && $blog->is_embed_html)
                <div
                    class="blog-media-wrap {{ $blog->is_portrait_embed ? 'ratio-portrait' : 'ratio-landscape' }} mb-4">
                    {!! $blog->embed_url !!}
                </div>
            @elseif ($blog->type !== 'article' && $blog->embed_url)
                <div class="blog-embed-fallback mb-4">
                    @if ($blog->thumbnail_url)
                        <img src="{{ $blog->thumbnail_url }}" alt="" class="img-fluid rounded mb-3"
                            style="max-height: 320px;">
                    @endif
                    <p class="text-body mb-3">This {{ $blog->type }} post links to external content.</p>
                    <a href="{{ $blog->embed_url }}" target="_blank" class="blog-embed-btn">
                        <i class="ti ti-external-link"></i> Open on {{ ucfirst($blog->type) }}
                    </a>
                </div>
            @elseif ($blog->thumbnail_url)
                <div class="mb-4">
                    <img src="{{ $blog->thumbnail_url }}" alt="{{ $blog->title }}"
                        class="img-fluid rounded w-100" style="max-height: 420px; object-fit: cover;">
                </div>
            @endif

            <div class="blog-content-card">
                @if ($blog->excerpt)
                    <p class="blog-excerpt-lead">{{ $blog->excerpt }}</p>
                @endif
                @if ($blog->type === 'article')
                    <div class="blog-article-body">{!! $blog->content !!}</div>
                @endif
            </div>

            @if ($blog->meta_title || $blog->meta_description)
                <div class="blog-side-card mt-4">
                    <div class="blog-side-card-header"><i class="ti ti-search"></i> SEO Preview</div>
                    <div class="blog-seo-card">
                        <div class="blog-seo-title">{{ $blog->meta_title ?: $blog->title }}</div>
                        <div class="blog-seo-url">{{ url('/blog/' . $blog->slug) }}</div>
                        <div class="blog-seo-desc">{{ $blog->meta_description ?: $blog->excerpt }}</div>
                    </div>
                </div>
            @endif
        </div>

        <div class="col-lg-4">
            <div class="blog-side-card">
                <div class="blog-side-card-header"><i class="ti ti-info-circle"></i> Details</div>
                <div class="blog-detail-row">
                    <span class="blog-detail-label"><i class="ti ti-user"></i> Author</span>
                    <span class="blog-detail-value">{{ $blog->author()?->name ?? '—' }}</span>
                </div>
                <div class="blog-detail-row">
                    <span class="blog-detail-label"><i class="ti ti-eye"></i> Views</span>
                    <span class="blog-detail-value">{{ number_format($blog->views) }}</span>
                </div>
                <div class="blog-detail-row">
                    <span class="blog-detail-label"><i class="ti ti-calendar-check"></i> Published</span>
                    <span class="blog-detail-value">{{ $blog->published_at?->format('M d, Y') ?? '—' }}</span>
                </div>
                <div class="blog-detail-row">
                    <span class="blog-detail-label"><i class="ti ti-clock"></i> Last Updated</span>
                    <span class="blog-detail-value">{{ $blog->updated_at->format('M d, Y') }}</span>
                </div>
                <div class="blog-detail-slug">
                    <span class="blog-detail-label"><i class="ti ti-link"></i> Slug</span>
                    <code>{{ $blog->slug }}</code>
                </div>
            </div>

            @if ($blog->type !== 'article' && $blog->embed_url)
                <div class="blog-side-card">
                    <div class="blog-side-card-header"><i class="ti ti-external-link"></i> Source</div>
                    <a href="{{ $blog->is_embed_html ? '#' : $blog->embed_url }}"
                        @unless ($blog->is_embed_html) target="_blank" @endunless class="blog-source-link">
                        <i class="ti ti-brand-{{ $blog->type }} me-1"></i>
                        {{ $blog->is_embed_html ? 'Embedded ' . ucfirst($blog->type) . ' content' : $blog->embed_url }}
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection
@push('scripts')
@endpush
