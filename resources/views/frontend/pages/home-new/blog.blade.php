@extends('frontend.layouts.new.master')
@section('page_title', isset($page_title) ? $page_title : 'Blog')
@push('styles')
    <style>
        .blog-header {
            width: 100%;
            height: 180px;
            background-color: #012142;
            color: #ffffff;
            padding-top: 30px;
        }

        .blog-header h1 {
            font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;
            color: white;
            font-size: 43px;
            font-weight: 800;
            margin: auto;
            text-align: center;
        }

        .blog-header p {
            color: white;
            margin: auto;
            text-align: center;
            font-weight: 600;
            padding-top: 10px;
            width: 55%;
        }

        .blog-wrapper {
            padding-top: 20px;
            width: 97%;
            margin: 0 auto;
        }

        /* ===== Hero / Feature Card ===== */
        .hero-cardd {
            position: relative;
            border-radius: 20px;
            overflow: hidden;
            height: 360px;
            margin-bottom: 40px;
            border: none;
            display: block;
            color: inherit;
            text-decoration: none;
        }

        .hero-cardd img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s ease;
        }

        .hero-cardd:hover img {
            transform: scale(1.08);
        }

        .hero-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg, rgba(0, 0, 0, 0.75) 0%, rgba(0, 0, 0, 0.35) 55%, rgba(0, 0, 0, 0.05) 100%);
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 30px 40px;
        }

        .badge-feature {
            background-color: dodgerblue;
            color: #fff;
            font-size: 12px;
            font-weight: 600;
            padding: 5px 14px;
            border-radius: 20px;
            width: fit-content;
            margin-bottom: 14px;
        }

        .hero-overlay h1 {
            color: #fff;
            font-size: 32px;
            line-height: 1.25;
            margin-bottom: 12px;
            max-width: 600px;
        }

        .hero-overlay p {
            color: #e5e7eb;
            font-size: 15px;
            max-width: 500px;
            margin-bottom: 20px;
        }

        .hero-meta {
            color: #fff;
            font-size: 14px;
        }

        .hero-meta span {
            font-weight: 700;
        }

        /* ===== Filter Pills + Search ===== */
        .filter-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 40px;
        }

        .filter-pills {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .pill {
            padding: 10px 22px;
            border: 1px solid grey;
            border-radius: 30px;
            background: #fff;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: 0.2s ease;
            text-decoration: none;
            color: inherit;
            display: inline-block;
        }

        .pill.active {
            background-color: dodgerblue;
            color: #fff;
            border-color: dodgerblue;
        }

        .search-box-blogg {
            background-color: #fff;
            display: flex;
            align-items: center;
            border: 1px solid grey;
            border-radius: 30px;
            width: 330px;
            height: 40px;
            padding: 10px;
        }

        .search-box-blogg input {
            border: none;
            outline: none;
            font-size: 15px;
            width: 90%;
            height: 34px;
            margin: 8px;
            background: transparent;
        }

        .search-box-blogg button {
            border: none;
            background: transparent;
            padding: 0;
        }

        .search-box-blogg i {
            color: grey;
        }

        /* ===== Card Grid ===== */
        .card-grid {
            width: 99%;
            margin: auto;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 48px;
        }

        .blog-card-section {
            border: 1px solid rgb(225, 223, 223);
            border-radius: 16px;
            overflow: hidden;
            transition: box-shadow 0.2s ease, transform 0.2s ease;
            display: flex;
            flex-direction: column;
            color: inherit;
            text-decoration: none;
            background: #fff;
        }

        .blog-card-section:hover {
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
            transform: translateY(-4px);
            color: inherit;
        }

        .card-img-blog {
            position: relative;
            height: 200px;
            background-color: #0f172a;
        }

        .card-img-blog img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .badge-category {
            position: absolute;
            top: 12px;
            left: 12px;
            background: #fff;
            color: blue;
            font-size: 12px;
            font-weight: 600;
            padding: 4px 12px;
            border-radius: 20px;
            text-transform: capitalize;
        }

        .card-body-blog {
            padding: 18px 20px 16px;
            display: flex;
            flex-direction: column;
            flex: 1;
        }

        .card-body-blog h3 {
            font-size: 17px;
            margin-bottom: 8px;
            line-height: 1.3;
        }

        .card-body-blog p {
            font-size: 14px;
            color: grey;
            line-height: 1.5;
            margin-bottom: 16px;
            flex: 1;
        }

        .card-footer-blog {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 1px solid rgb(221, 219, 219);
            padding-top: 12px;
            font-size: 13px;
            color: var(--text-gray);
        }

        .card-footer-blog a {
            color: blue;
            text-decoration: none;
            font-weight: 600;
        }

        .card-footer-blog a i {
            margin-left: 4px;
        }

        .more-blog {
            display: flex;
            gap: 8px;
            margin-top: 40px;
            justify-content: center;
            align-items: center;
        }

        .more-blog nav {
            margin: 0;
        }

        .more-blog .pagination {
            margin: 0;
        }

        .empty-blog-state {
            text-align: center;
            padding: 60px 20px;
            color: grey;
        }

        /* ============================================= */
        /* ===== Responsive: Blog Page ===== */
        /* ============================================= */
        @media (max-width: 1024px) {
            .blog-header {
                height: 160px;
            }

            .blog-header h1 {
                font-size: 34px;
            }

            .blog-header p {
                width: 75%;
            }

            .hero-cardd {
                height: 300px;
            }

            .hero-overlay h1 {
                font-size: 26px;
            }

            .hero-overlay p {
                font-size: 14px;
                max-width: 400px;
            }

            .card-grid {
                width: 88%;
                margin: auto;
                grid-template-columns: repeat(2, 1fr);
                gap: 20px;
            }
        }

        @media (max-width: 768px) {
            .blog-header {
                height: auto;
                padding: 25px 15px;
            }

            .blog-header h1 {
                font-size: 28px;
            }

            .blog-header p {
                width: 90%;
                font-size: 14px;
            }

            .hero-cardd {
                height: 260px;
                margin-bottom: 30px;
            }

            .hero-overlay {
                padding: 20px;
            }

            .hero-overlay h1 {
                font-size: 22px;
            }

            .hero-overlay p {
                display: none;
            }

            .filter-row {
                flex-direction: column;
                align-items: stretch;
                gap: 15px;
                margin-bottom: 30px;
            }

            .filter-pills {
                flex-wrap: wrap;
                overflow-x: visible;
                gap: 8px;
            }

            .filter-pills .pill {
                flex: 0 1 auto;
            }

            .search-box-blogg {
                width: 60%;
            }

            .card-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 18px;
            }
        }

        @media (max-width: 690px) {
            .filter-pills {
                flex-wrap: wrap;
                overflow-x: visible;
                gap: 8px;
            }

            .filter-pills .pill {
                flex: 0 1 auto;
                padding: 7px 16px;
                font-size: 13px;
            }

            .badge-feature {
                font-size: 11px;
                padding: 4px 10px;
            }

            .hero-meta {
                font-size: 12px;
            }

            .card-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 11px;
                width: 79%;
            }

            .card-grid .blog-card-section {
                width: 100%;
            }

            .blog-card-section .card-body-blog h3 {
                font-size: 10px;
                font-weight: 600;
            }

            .blog-card-section .card-body-blog p {
                font-size: 9px;
            }

            .card-img-blog {
                height: 150px;
            }

            .card-img-blog .badge-category {
                top: 12px;
                left: 12px;
                font-size: 10px;
                padding: 4px 11px;
            }

            .card-body-blog h3 {
                font-size: 17px;
            }

            .card-footer-blog {
                font-size: 11px;
            }

            .card-footer-blog i {
                font-size: 10px;
            }

            .card-footer-blog a {
                font-size: 10px;
            }
        }

        @media (max-width: 560px) {
            .blog-wrapper {
                width: 92%;
                padding-top: 15px;
            }

            .blog-header h1 {
                font-size: 22px;
            }

            .blog-header p {
                width: 95%;
                font-size: 13px;
            }

            .hero-cardd {
                height: 220px;
                border-radius: 14px;
                margin-bottom: 25px;
            }

            .hero-overlay {
                padding: 16px;
            }

            .hero-overlay h1 {
                font-size: 18px;
                margin-bottom: 8px;
            }

            .filter-pills {
                flex-wrap: wrap;
                overflow-x: visible;
                gap: 8px;
            }

            .filter-pills .pill {
                flex: 0 1 auto;
                padding: 7px 16px;
                font-size: 13px;
            }

            .badge-feature {
                font-size: 11px;
                padding: 4px 10px;
            }

            .hero-meta {
                font-size: 12px;
            }

            .card-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 11px;
                width: 99%;
            }

            .card-grid .blog-card-section {
                width: 100%;
            }

            .blog-card-section .card-body-blog h3 {
                font-size: 10px;
                font-weight: 600;
            }

            .blog-card-section .card-body-blog p {
                font-size: 9px;
            }

            .card-img-blog {
                height: 120px;
            }

            .card-img-blog .badge-category {
                top: 12px;
                left: 12px;
                font-size: 9px;
                padding: 3px 8px;
            }

            .card-body-blog h3 {
                font-size: 11px;
            }

            .card-footer-blog {
                font-size: 9px;
            }

            .card-footer-blog i {
                font-size: 8px;
            }

            .card-footer-blog a {
                font-size: 8px;
            }

            .more-blog {
                gap: 12px;
            }
        }
    </style>
@endpush
@section('content')
    <div class="blog-container">
        <div class="blog-header">
            <h1>Our Blog</h1>
            <p>Insights, tutorials, and news from the ICT Center team and industry experts.</p>
        </div>
        <div class="blog-wrapper">
            {{-- ===== Hero / Feature Card ===== --}}
            @if ($featured)
                <a href="{{ route('blog.details', $featured->slug) }}" class="hero-cardd">
                    <img src="{{ $featured->thumbnail ?? asset('frontend/asset/images/blog-slide.avif') }}"
                        alt="{{ $featured->title }}">
                    <div class="hero-overlay">
                        <span class="badge-feature">Feature</span>
                        <h1>{{ $featured->title }}</h1>
                        @if ($featured->excerpt)
                            <p>{{ Str::limit($featured->excerpt, 140) }}</p>
                        @endif
                        <div class="hero-meta">
                            <span>{{ $featured->admin?->name ?? 'ICT Team' }}</span>
                            &nbsp;•&nbsp;
                            {{ $featured->published_at?->format('M d, Y') }}
                        </div>
                    </div>
                </a>
            @endif
            {{-- ===== Filter Pills + Search ===== --}}
            <form method="GET" action="{{ route('blog') }}" class="filter-row" id="blog-filter-form">
                <div class="filter-pills">
                    <a href="{{ route('blog', array_filter(['search' => request('search')])) }}"
                        class="pill {{ request('type') ? '' : 'active' }}">All</a>
                    @foreach (['article', 'facebook', 'tiktok', 'youtube'] as $type)
                        <a href="{{ route('blog', array_filter(['type' => $type, 'search' => request('search')])) }}"
                            class="pill {{ request('type') === $type ? 'active' : '' }}">
                            {{ ucfirst($type) }}
                        </a>
                    @endforeach
                </div>
                <div class="search-box-blogg">
                    <button type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search articles...">
                    @if (request('type'))
                        <input type="hidden" name="type" value="{{ request('type') }}">
                    @endif
                </div>
            </form>
            {{-- ===== Card Grid ===== --}}
            @if ($blogs->count())
                <div class="card-grid">
                    @foreach ($blogs as $blog)
                        {{-- Card grid --}}
                        <a href="{{ route('blog.details', $blog->slug) }}" class="blog-card-section">
                            <div class="card-img-blog">
                                <span class="badge-category">{{ $blog->type }}</span>
                                <img src="{{ $blog->thumbnail ?? asset('frontend/asset/images/blog-slide.avif') }}"
                                    alt="{{ $blog->title }}">
                            </div>
                            <div class="card-body-blog">
                                <h3>{{ $blog->title }}</h3>
                                <p>{{ Str::limit($blog->excerpt, 100) }}</p>
                                <div class="card-footer-blog">
                                    <span><i class="fa-regular fa-clock"></i>
                                        {{ $blog->published_at?->format('M d, Y') }}</span>
                                    <span>Read More <i class="fa-solid fa-arrow-right"></i></span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
                <div class="more-blog">
                    {{ $blogs->onEachSide(1)->links() }}
                </div>
            @else
                <div class="empty-blog-state">
                    <i class="fa-regular fa-newspaper fa-2x mb-3"></i>
                    <p>No blog posts found{{ request('search') ? ' for "' . request('search') . '"' : '' }}.</p>
                </div>
            @endif
        </div>
    </div>
@endsection
@push('scripts')
@endpush
