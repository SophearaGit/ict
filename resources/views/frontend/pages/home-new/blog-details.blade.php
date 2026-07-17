@extends('frontend.layouts.new.master')
@section('page_title', $blog->meta_title ?: $blog->title)
@push('meta')
    @php
        $ogImage = $blog->thumbnail_url
            ? (str_starts_with($blog->thumbnail_url, 'http')
                ? $blog->thumbnail_url
                : asset($blog->thumbnail_url))
            : asset('frontend/asset/images/blod-detail-slide-img.jpg');
        $ogDescription = $blog->meta_description ?: $blog->excerpt ?: Str::limit(strip_tags($blog->content), 160);
    @endphp
    <meta property="og:type" content="article">
    <meta property="og:title" content="{{ $blog->meta_title ?: $blog->title }}">
    <meta property="og:description" content="{{ $ogDescription }}">
    {{-- <meta property="og:image" content="{{ $ogImage }}"> --}}
    <meta property="og:image" content="http://ict-lms.info/uploads/blog/thumbnails/ict_6a59a899ed900.jpg">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:site_name" content="{{ config('app.name') }}">
    {{-- Twitter/X card (also used by some other apps) --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $blog->meta_title ?: $blog->title }}">
    <meta name="twitter:description" content="{{ $ogDescription }}">
    <meta name="twitter:image" content="{{ $ogImage }}">
    <meta name="description" content="{{ $ogDescription }}">
@endpush
@push('styles')
    @include('frontend.pages.home-new.css.blog-details-style')
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
                <button class="pills">
                    {{ $blog->type }}
                </button>
            </div>
            @php
                $shareUrl = urlencode(url()->current());
                $shareTitle = urlencode($blog->title);
                $shareLinks = [
                    [
                        'name' => 'facebook',
                        'icon' => 'fa-brands fa-facebook-f',
                        'url' => "https://www.facebook.com/sharer/sharer.php?u={$shareUrl}",
                    ],
                    [
                        'name' => 'linkedin',
                        'icon' => 'fa-brands fa-linkedin-in',
                        'url' => "https://www.linkedin.com/sharing/share-offsite/?url={$shareUrl}",
                    ],
                    [
                        'name' => 'telegram',
                        'icon' => 'fa-solid fa-paper-plane',
                        'url' => "https://t.me/share/url?url={$shareUrl}&text={$shareTitle}",
                    ],
                ];
            @endphp
            <div class="share-group">
                <i class="fa-solid fa-share-nodes"></i>
                <span class="share-label">Share</span>
                @foreach ($shareLinks as $link)
                    <a href="{{ $link['url'] }}" class="share-icon" data-share="{{ $link['name'] }}"
                        aria-label="Share on {{ ucfirst($link['name']) }}" rel="noopener noreferrer">
                        <i class="{{ $link['icon'] }}"></i>
                    </a>
                @endforeach
                <a href="#" class="share-icon" id="copy-link-btn" data-url="{{ url()->current() }}"
                    aria-label="Copy link">
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Open share links in a centered popup instead of a full new tab
            document.querySelectorAll('.share-icon[data-share]').forEach(function(link) {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const url = this.getAttribute('href');
                    const width = 600,
                        height = 500;
                    const left = (window.innerWidth - width) / 2 + window.screenX;
                    const top = (window.innerHeight - height) / 2 + window.screenY;
                    window.open(
                        url,
                        'share-' + this.dataset.share,
                        `width=${width},height=${height},left=${left},top=${top},noopener,noreferrer`
                    );
                });
            });
            // Copy link button with icon + tooltip feedback
            const copyBtn = document.getElementById('copy-link-btn');
            if (copyBtn) {
                copyBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const icon = this.querySelector('i');
                    navigator.clipboard.writeText(this.dataset.url).then(() => {
                        icon.classList.replace('fa-link', 'fa-check');
                        this.classList.add('copied');
                        setTimeout(() => {
                            icon.classList.replace('fa-check', 'fa-link');
                            this.classList.remove('copied');
                        }, 1500);
                    });
                });
            }
        });
    </script>
@endpush
