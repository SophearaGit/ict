@extends('frontend.layouts.new.master')
@section('page_title', $blog->meta_title ?: $blog->title)
@push('styles')
    <style>
        /* ===== Hero Image ===== */
        .blog-detail-hero {
            position: relative;
            width: 100%;
            height: 320px;
            overflow: hidden;
            background-color: #0f172a;
        }

        .blog-detail-hero img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .back-to-blog {
            position: absolute;
            top: 20px;
            left: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
            background: rgba(0, 0, 0, 0.5);
            color: #fff;
            font-size: 14px;
            font-weight: 600;
            padding: 10px 18px;
            border-radius: 30px;
            text-decoration: none;
            backdrop-filter: blur(4px);
        }

        .back-to-blog:hover {
            background: rgba(0, 0, 0, 0.7);
            color: #fff;
        }

        /* ===== Overlapping Content Card ===== */
        .Blog-detail-card {
            background: #fff;
            max-width: 990px;
            margin: -100px auto 0;
            padding: 40px 50px;
            border-radius: 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            position: relative;
            text-align: center;
            animation: fadeSlideUp 0.7s ease forwards;
            opacity: 0;
        }

        @keyframes fadeSlideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .blog-detail-category {
            display: inline-block;
            background: #e6efff;
            color: blue;
            font-size: 14px;
            font-weight: 600;
            padding: 6px 18px;
            border-radius: 20px;
            margin-bottom: 13px;
            text-transform: capitalize;
        }

        .Blog-detail-card h1 {
            font-size: 33px;
            font-weight: 700;
            line-height: 1.3;
            color: #111;
            margin-bottom: 25px;
        }

        /* ===== Author / Date / Read time row ===== */
        .detail-meta {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
            opacity: 0;
            animation: fadeSlideUp 0.6s ease forwards;
            animation-delay: 0.25s;
        }

        .meta-author {
            display: flex;
            align-items: center;
            gap: 10px;
            text-align: left;
        }

        .meta-author div {
            line-height: 0.6;
        }

        .meta-author img {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            border: 1px solid rgb(230, 228, 228);
            padding: 1px;
            object-fit: cover;
        }

        .meta-author .avatar-fallback {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            border: 1px solid rgb(230, 228, 228);
            background: #0d6efd;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 20px;
        }

        .detail-meta .meta-date {
            line-height: 0.6;
        }

        .meta-author p,
        .meta-date p {
            display: block;
            font-size: 15px;
            font-weight: 600;
            color: #111;
        }

        .meta-author span,
        .meta-date span {
            font-size: 13px;
            color: grey;
        }

        .meta-divider {
            width: 1px;
            height: 45px;
            background: #c2c3c4;
        }

        .meta-read {
            font-size: 14px;
            color: #333;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .blog-description-detail {
            margin: auto;
            width: 65%;
            padding: 35px;
            text-align: left;
        }

        .blog-description-detail p {
            line-height: 1.7;
            font-size: 15.5px;
        }

        .blog-media-wrap {
            position: relative;
            width: 100%;
            overflow: hidden;
            border-radius: var(--gk-border-radius-lg, 16px);
            background-color: #0f172a;
            margin-bottom: 20px;
        }

        .blog-media-wrap.ratio-landscape {
            padding-top: 56.25%;
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

        .external-embed-card {
            text-align: center;
            padding: 40px 20px;
            background: #f8fafc;
            border-radius: 16px;
            margin-bottom: 20px;
        }

        /* ===== iPhone Frame (for portrait embeds) ===== */
        .phone-frame-wrap {
            display: flex;
            justify-content: center;
            padding: 30px 0;
            background: linear-gradient(180deg, #f8fafc 0%, #eef2f7 100%);
            border-radius: 20px;
            margin-bottom: 20px;
        }

        .phone-frame {
            position: relative;
            width: 320px;
            max-height: 700px;
            background: #111827;
            border-radius: 42px;
            padding: 14px;
            box-shadow:
                0 25px 50px -12px rgba(0, 0, 0, 0.35),
                0 0 0 2px rgba(0, 0, 0, 0.15);
        }

        /* notch */
        .phone-frame::before {
            content: "";
            position: absolute;
            top: 14px;
            left: 50%;
            transform: translateX(-50%);
            width: 110px;
            height: 22px;
            background: #111827;
            border-radius: 0 0 16px 16px;
            z-index: 5;
        }

        /* side buttons */
        .phone-frame::after {
            content: "";
            position: absolute;
            top: 100px;
            left: -3px;
            width: 3px;
            height: 60px;
            background: #0b0f19;
            border-radius: 2px 0 0 2px;
            box-shadow: 0 90px 0 0 #0b0f19;
        }

        .phone-screen {
            position: relative;
            width: 100%;
            max-height: 672px;
            background: #000;
            border-radius: 30px;
            overflow-y: auto;
            overflow-x: hidden;
            -webkit-overflow-scrolling: touch;
        }

        /* hide scrollbar visually but keep it scrollable */
        .phone-screen::-webkit-scrollbar {
            width: 4px;
        }

        .phone-screen::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 4px;
        }

        /* Facebook / YouTube iframe portrait: fill the screen area (single canvas incl. caption) */
        .phone-screen>iframe {
            display: block;
            width: 100% !important;
            height: 672px !important;
            max-width: none !important;
            border: 0;
            border-radius: 30px;
        }

        /* TikTok blockquote: let it size NATURALLY (video + caption stacked),
                   only constrain the width so it fits the phone, never force height */
        .phone-screen blockquote.tiktok-embed {
            width: 100% !important;
            max-width: 100% !important;
            min-width: 0 !important;
            margin: 0 !important;
        }

        .phone-screen blockquote.tiktok-embed iframe {
            width: 100% !important;
            max-width: 100% !important;
            border-radius: 20px;
        }

        @media (max-width: 480px) {
            .phone-frame {
                width: 260px;
                max-height: 560px;
                border-radius: 34px;
                padding: 10px;
            }

            .phone-frame::before {
                width: 90px;
                height: 18px;
                top: 10px;
            }

            .phone-screen {
                max-height: 536px;
                border-radius: 24px;
            }

            .phone-screen>iframe {
                height: 536px !important;
                border-radius: 24px;
            }
        }

        /* ===== Tags + Share Row ===== */
        .tags-share-row {
            width: 62%;
            margin: auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
            padding: 25px 0;
            border-top: 1px solid #d5d5d6;
            border-bottom: 1px solid #d5d5d6;
        }

        .tags-group {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .share-group {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .tags-label,
        .share-label {
            font-weight: 700;
            font-size: 15px;
            margin-right: 5px;
        }

        .tags-group .pills {
            padding: 6px 16px;
            font-size: 15px;
            border-radius: 30px;
            font-weight: 600;
            border: none;
            cursor: default;
            background: #e6efff;
            color: blue;
        }

        .share-icon {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #f3f4f6;
            color: #333;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            font-size: 14px;
            transition: background 0.2s ease;
        }

        .share-icon:hover {
            background: #e5e7eb;
            color: #333;
        }

        /* ===== Author Bio Card ===== */
        .author-card {
            display: flex;
            align-items: flex-start;
            gap: 30px;
            border: 1px solid #d7d7d9;
            border-radius: 20px;
            padding: 20px 35px;
            max-width: 800px;
            margin: 50px auto 20px;
        }

        .author-card img,
        .author-card .avatar-fallback-lg {
            width: 130px;
            height: 150px;
            border-radius: 20px;
            object-fit: cover;
            border: 1px solid rgb(233, 232, 232);
            flex-shrink: 0;
        }

        .author-card .avatar-fallback-lg {
            background: #0d6efd;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 42px;
        }

        .author-info h3 {
            font-size: 18px;
            margin-top: 10px;
        }

        .author-info h3 span {
            font-weight: 700;
        }

        .author-info p {
            font-size: 14.5px;
            line-height: 1.4;
            margin-bottom: 12px;
        }

        .author-link {
            color: blue;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
        }

        .author-link:hover {
            text-decoration: underline;
        }

        /* ===== Related Posts ===== */
        .related-posts {
            max-width: 990px;
            margin: 40px auto 60px;
            padding: 0 15px;
        }

        .related-posts h4 {
            font-weight: 700;
            margin-bottom: 20px;
        }

        .related-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
        }

        .related-card {
            border: 1px solid rgb(225, 223, 223);
            border-radius: 14px;
            overflow: hidden;
            text-decoration: none;
            color: inherit;
            display: block;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .related-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.08);
            color: inherit;
        }

        .related-card img {
            width: 100%;
            height: 140px;
            object-fit: cover;
        }

        .related-card .related-body {
            padding: 14px 16px;
        }

        .related-card h6 {
            font-size: 14px;
            font-weight: 700;
            margin-bottom: 6px;
            line-height: 1.3;
        }

        .related-card span {
            font-size: 12px;
            color: grey;
        }

        /* ============================================= */
        /* ===== Responsive: Blog Detail Page ===== */
        /* ============================================= */
        @media (max-width: 1024px) {
            .blog-detail-hero {
                height: 280px;
            }

            .Blog-detail-card {
                max-width: 90%;
                padding: 35px 40px;
                margin-top: -80px;
            }

            .Blog-detail-card h1 {
                font-size: 28px;
            }

            .blog-description-detail {
                width: 80%;
                padding: 25px;
            }

            .tags-share-row {
                width: 80%;
            }

            .author-card {
                max-width: 90%;
            }

            .related-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .blog-detail-hero {
                height: 220px;
            }

            .back-to-blog {
                top: 14px;
                left: 14px;
                font-size: 13px;
                padding: 8px 14px;
            }

            .Blog-detail-card {
                max-width: 92%;
                padding: 30px 25px;
                margin-top: -60px;
                border-radius: 16px;
            }

            .Blog-detail-card h1 {
                font-size: 22px;
            }

            .blog-detail-category {
                font-size: 13px;
                padding: 5px 14px;
            }

            .detail-meta {
                flex-direction: column;
                gap: 12px;
            }

            .meta-divider {
                width: 60px;
                height: 1px;
            }

            .blog-description-detail {
                width: 90%;
                padding: 20px 15px;
            }

            .tags-share-row {
                width: 90%;
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }

            .tags-group,
            .share-group {
                width: 100%;
            }

            .author-card {
                max-width: 92%;
                flex-direction: column;
                align-items: center;
                text-align: center;
                padding: 25px;
            }

            .author-card img,
            .author-card .avatar-fallback-lg {
                width: 100px;
                height: 100px;
            }

            .related-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 480px) {
            .blog-detail-hero {
                height: 180px;
            }

            .back-to-blog {
                font-size: 12px;
                padding: 7px 12px;
                gap: 6px;
            }

            .Blog-detail-card {
                padding: 22px 18px;
                margin-top: -40px;
                border-radius: 14px;
            }

            .Blog-detail-card h1 {
                font-size: 18px;
                margin-bottom: 15px;
            }

            .blog-detail-category {
                font-size: 12px;
                padding: 4px 12px;
            }

            .meta-author img,
            .meta-author .avatar-fallback {
                width: 45px;
                height: 45px;
            }

            .meta-author p,
            .meta-date p {
                font-size: 13px;
            }

            .meta-author span,
            .meta-date span {
                font-size: 11px;
            }

            .meta-read {
                font-size: 12px;
            }

            .share-group {
                margin-top: 10px;
            }

            .blog-description-detail {
                width: 100%;
                padding: 15px 10px;
            }

            .blog-description-detail p {
                font-size: 14px;
            }

            .tags-share-row {
                width: 93%;
                padding: 18px 0;
            }

            .tags-group .pills {
                padding: 5px 12px;
                font-size: 13px;
            }

            .share-icon {
                width: 30px;
                height: 30px;
                font-size: 12px;
            }

            .author-card {
                max-width: 100%;
                padding: 20px 15px;
                margin: 30px auto 15px;
            }

            .author-card img,
            .author-card .avatar-fallback-lg {
                width: 80px;
                height: 90px;
            }

            .author-info h3 {
                font-size: 16px;
            }

            .author-info p {
                font-size: 13px;
            }
        }
    </style>
@endpush
@section('content')
    <div class="blog-detail-contianer">
        {{-- ===== Blog Detail Header ===== --}}
        <div class="blog-detail-hero">
            <img src="{{ $blog->thumbnail_url ?? asset('frontend/asset/images/blod-detail-slide-img.jpg') }}"
                alt="{{ $blog->title }}">
            <a href="{{ route('blog') }}" class="back-to-blog">
                <i class="fa-solid fa-arrow-left"></i> Back to Blog
            </a>
        </div>
        <div class="Blog-detail-card">
            <span class="blog-detail-category">{{ $blog->type }}</span>
            <h1>{{ $blog->title }}</h1>
            <div class="detail-meta">
                <div class="meta-author">
                    @if ($blog->admin?->avatar ?? false)
                        <img src="{{ asset($blog->admin->avatar) }}" alt="{{ $blog->admin->name }}">
                    @else
                        <div class="avatar-fallback">{{ Str::substr($blog->admin?->name ?? 'A', 0, 1) }}</div>
                    @endif
                    <div>
                        <p>{{ $blog->admin?->name ?? 'ICT Team' }}</p>
                        <span>Author</span>
                    </div>
                </div>
                <div class="meta-divider"></div>
                <div class="meta-date">
                    <p>{{ $blog->published_at?->format('M d, Y') ?? $blog->created_at->format('M d, Y') }}</p>
                    <span>Published</span>
                </div>
                @if ($blog->type === 'article' && $blog->content)
                    <div class="meta-divider"></div>
                    <div class="meta-read">
                        <i class="fa-regular fa-clock"></i>
                        {{ max(1, (int) ceil(str_word_count(strip_tags($blog->content)) / 200)) }} min read
                    </div>
                @endif
            </div>
        </div>
        <div class="blog-description-detail">
            @if ($blog->type !== 'article' && $blog->is_embed_html)
                @if ($blog->is_portrait_embed)
                    <div class="phone-frame-wrap">
                        <div class="phone-frame">
                            <div class="phone-screen">
                                {!! $blog->embed_url_for_display !!}
                            </div>
                        </div>
                    </div>
                @else
                    <div class="blog-media-wrap ratio-landscape">
                        {!! $blog->embed_url_for_display !!}
                    </div>
                @endif
            @elseif ($blog->type !== 'article' && $blog->embed_url)
                <div class="external-embed-card">
                    @if ($blog->thumbnail_url)
                        <img src="{{ $blog->thumbnail_url }}" alt="" class="img-fluid rounded mb-3"
                            style="max-height: 320px;">
                    @endif
                    <p class="text-body mb-3">This {{ $blog->type }} post links to external content.</p>
                    <a href="{{ $blog->embed_url }}" target="_blank" class="btn btn-primary">
                        <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> Open on {{ ucfirst($blog->type) }}
                    </a>
                </div>
            @endif
            @if ($blog->excerpt)
                <p class="lead">{{ $blog->excerpt }}</p>
            @endif
            @if ($blog->type === 'article' && $blog->content)
                <div>{!! $blog->content !!}</div>
            @endif
        </div>
        {{-- ===== Share Row ===== --}}
        <div class="tags-share-row">
            <div class="tags-group">
                <i class="fa-solid fa-tag"></i>
                <span class="tags-label">Category</span>
                <button class="pills">{{ ucfirst($blog->type) }}</button>
            </div>
            <div class="share-group">
                <i class="fa-solid fa-share-nodes"></i>
                <span class="share-label">Share</span>
                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank"
                    class="share-icon"><i class="fa-brands fa-facebook-f"></i></a>
                <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(url()->current()) }}"
                    target="_blank" class="share-icon"><i class="fa-brands fa-linkedin-in"></i></a>
                <a href="https://t.me/share/url?url={{ urlencode(url()->current()) }}&text={{ urlencode($blog->title) }}"
                    target="_blank" class="share-icon"><i class="fa-solid fa-paper-plane"></i></a>
                <a href="#" class="share-icon"
                    onclick="navigator.clipboard.writeText('{{ url()->current() }}'); this.querySelector('i').classList.replace('fa-link','fa-check'); return false;">
                    <i class="fa-solid fa-link"></i>
                </a>
            </div>
        </div>
        {{-- ===== Author Bio Card ===== --}}
        @if ($blog->admin)
            <div class="author-card">
                @if ($blog->admin->avatar ?? false)
                    <img src="{{ asset($blog->admin->avatar) }}" alt="{{ $blog->admin->name }}">
                @else
                    <div class="avatar-fallback-lg">{{ Str::substr($blog->admin->name, 0, 1) }}</div>
                @endif
                <div class="author-info">
                    <h3>About <span>{{ $blog->admin->name }}</span></h3>
                    <p>Part of the ICT Professional Training Center team, sharing insights and expertise to help others
                        learn and grow.</p>
                </div>
            </div>
        @endif
        {{-- ===== Related Posts ===== --}}
        @if ($related->count())
            <div class="related-posts">
                <h4>You might also like</h4>
                <div class="related-grid">
                    @foreach ($related as $item)
                        <a href="{{ route('blog.details', $item->slug) }}" class="related-card">
                            <img src="{{ $item->thumbnail_url ?? asset('frontend/asset/images/blog-slide.avif') }}"
                                alt="{{ $item->title }}">
                            <div class="related-body">
                                <h6>{{ Str::limit($item->title, 60) }}</h6>
                                <span>{{ $item->published_at?->format('M d, Y') }}</span>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
@endsection
@push('scripts')
@endpush
