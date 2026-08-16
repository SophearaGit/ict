@extends('frontend.layouts.new.master')
@section('page_title', isset($page_title) ? $page_title : 'Blog')
@push('styles')
    <style>
        .blog-header {
            width: 100%;
            height: 240px;
            background-color: #000203;
            background-image:
                linear-gradient(135deg, rgba(1, 22, 39, 0.92), rgba(1, 12, 24, 0.7)),
                url(frontend/asset/images/advertisement/advertisement-slideshow\(2\).webp);
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            color: #ffffff;
            padding-top: 66px;
        }

        .blog-header h1 {
            font-family: var(--font-heading);
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
            /* max-width: 1150px; */
            margin: 0 auto;
        }

        /* ===== Hero / Feature Card ===== */
        .hero-cardd {
            position: relative;
            border-radius: 20px;
            overflow: hidden;
            height: 500px;
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
            /* smooth zoom animation */
        }

        .hero-cardd:hover img {
            transform: scale(1.08);
            /* zoom in on hover */
        }

        /* dark gradient so text stays readable over the photo */
        .hero-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg, rgba(0, 0, 0, 0.75) 0%, rgba(0, 0, 0, 0.35) 55%, rgba(0, 0, 0, 0.05) 100%);
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 40px 50px;
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
            color: #000;
            text-decoration: none;
            display: inline-block;
        }

        .pill.active {
            background-color: dodgerblue;
            color: #fff;
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
            /* min-width: 260px; */
        }

        .search-box-blogg input {
            border: none;
            outline: none;
            font-size: 15px;
            width: 90%;
            height: 34px;
            margin: 8px;
        }

        .search-box-blogg i {
            color: grey;
        }

        /* ===== Card Grid ===== */
        .card-grid {
            width: 99%;
            margin: auto;
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 18px;
        }

        .blog-card-section {
            /* border: 1px solid rgb(225, 223, 223); */
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 0 7px 0 rgb(202, 202, 202);
            transition: box-shadow 0.2s ease, transform 0.2s ease;
            display: block;
            color: inherit;
            text-decoration: none;
        }

        .blog-card-section:hover {
            transform: translateY(-4px);
            /* card lifts slightly */
        }

        .card-img-blog {
            position: relative;
            height: auto;
        }

        .card-img-blog img {
            width: 100%;
            height: auto;
            max-width: 100%;
            aspect-ratio: 4 / 2.6;
            /*new important*/
            /* object-fit: cover; */
        }

        .badge-category {
            position: absolute;
            top: 12px;
            left: 12px;
            background: #fff;
            color: #000;
            text-decoration: none;
            font-size: 12px;
            font-weight: 600;
            padding: 4px 12px;
            border-radius: 20px;
        }

        .card-body-blog {
            padding: 18px 20px 16px;
        }

        .card-body-blog h3 {
            font-size: 15px;
            margin-bottom: 8px;
            line-height: 1.3;
            height: 50px;
        }

        .card-body-blog p {
            /* font-size: 14px; */
            font-size: 13px;
            color: grey;
            line-height: 1.5;
            margin-bottom: 16px;
            height: 80px;
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
            gap: 20px;
            margin-top: 40px;
            justify-content: center;
            align-items: center;
        }

        .avertisement-slide {
            width: 95%;
            margin: auto;
            margin-top: 30px;
            padding: 40px;
            border-radius: 120px;
        }

        .avertisement-slide .carousel-inner {
            border-radius: 30px;
        }

        /* ============================================= */
        /* ===== Responsive: Blog Page ===== */
        /* ============================================= */
        /* ----- 1024px: Tablet / small laptop ----- */
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
                grid-template-columns: repeat(3, 1fr);
                gap: 20px;
            }

            .card-body-blog p {
                height: 84px;
            }
        }

        /* ----- 768px: Tablet portrait ----- */
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
                /* hide long description on smaller screens */
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
                /* allow wrapping instead of forcing single line */
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
                /* let pills drop to a new line instead of scrolling */
                overflow-x: visible;
                /* cancel the scroll behavior inherited from 768px */
                gap: 8px;
            }

            .filter-pills .pill {
                flex: 0 1 auto;
                /* allow wrapping, no forced single line */
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
                font-size: 12px;
                font-weight: 600;
                height: 40px;
            }

            .blog-card-section .card-body-blog p {
                font-size: 10px;
                height: 46px;
                /* line-height: 1.4; */
            }

            /* .card-img-blog{
                            height: 150px;
                        } */
            .card-img-blog .badge-category {
                position: absolute;
                top: 12px;
                left: 12px;
                background: #fff;
                color: #000;
                text-decoration: none;
                font-size: 10px;
                font-weight: 600;
                padding: 4px 11px;
                border-radius: 20px;
            }

            /* .card-body-blog p{
                            font-size: 9px;
                        } */
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

        /* ----- 480px: Mobile ----- */
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
                /* let pills drop to a new line instead of scrolling */
                overflow-x: visible;
                /* cancel the scroll behavior inherited from 768px */
                gap: 8px;
            }

            .filter-pills .pill {
                flex: 0 1 auto;
                /* allow wrapping, no forced single line */
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
                height: 30px;
            }

            .blog-card-section .card-body-blog p {
                font-size: 9px;
                /* line-height: 1.4; */
            }

            /* .card-img-blog{
                            height: 120px;
                        } */
            .card-img-blog .badge-category {
                position: absolute;
                top: 12px;
                left: 12px;
                background: #fff;
                color: #000;
                text-decoration: none;
                font-size: 9px;
                font-weight: 600;
                padding: 3px 8px;
                border-radius: 20px;
            }

            /* .card-body-blog p{
                            font-size: 9px;
                        } */
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

            .more-blog .blog-number-page {
                width: 32px;
                height: 32px;
                font-size: 15px;
            }
        }
    </style>
@endpush
@section('content')

    <!-- =======================================blog-code-start========================================== -->
    <div class="blog-header">
        <h1>Our Blog</h1>
        <p>Insights, tutorials, and news from the ICT Center team and industry experts.</p>
    </div>
    <div class="blog-wrapper">

        <!-- ===== Hero / Feature Card ===== -->
        @if ($featured)
            <a href="{{ route('blog.details', $featured->slug) }}" class="hero-cardd" data-aos="fade-up">
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

        <!-- ===== Filter Pills + Search ===== -->
        <form method="GET" action="{{ route('blog') }}" class="filter-row" id="blog-filter-form">
            <div class="filter-pills">
                <a href="{{ route('blog', array_filter(['search' => request('search')])) }}"
                    class="pill {{ request('type') ? '' : 'active' }}">All</a>
                @foreach (['article' => 'Article', 'facebook' => 'Facebook', 'youtube' => 'Youtube', 'tiktok' => 'Tiktok'] as $typeValue => $typeLabel)
                    <a href="{{ route('blog', array_filter(['type' => $typeValue, 'search' => request('search')])) }}"
                        class="pill {{ request('type') === $typeValue ? 'active' : '' }}">
                        {{ $typeLabel }}
                    </a>
                @endforeach
            </div>
            <div class="search-box-blogg">
                <button type="submit" style="border:none;background:transparent;padding:0;">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search articles...">
                @if (request('type'))
                    <input type="hidden" name="type" value="{{ request('type') }}">
                @endif
            </div>
        </form>

        <!-- ===== Card Grid (CSS Grid) ===== -->
        @if ($blogs->count())
            <div class="card-grid">
                @foreach ($blogs as $index => $blog)
                    <a href="{{ route('blog.details', $blog->slug) }}" class="blog-card-section" data-aos="fade-up"
                        data-aos-delay="{{ ($index % 3) * 60 }}">
                        <div class="card-img-blog">
                            <span class="badge-category">{{ ucfirst($blog->type) }}</span>
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

            @if ($blogs->hasPages())
                <div class="pagination">
                    @if ($blogs->onFirstPage())
                        <div class="page-btn disabled">&#10094;</div>
                    @else
                        <a href="{{ $blogs->previousPageUrl() }}" class="page-btn">&#10094;</a>
                    @endif

                    @for ($page = 1; $page <= $blogs->lastPage(); $page++)
                        @if ($page === $blogs->currentPage())
                            <div class="page-btn active">{{ $page }}</div>
                        @else
                            <a href="{{ $blogs->url($page) }}" class="page-btn">{{ $page }}</a>
                        @endif
                    @endfor

                    @if ($blogs->hasMorePages())
                        <a href="{{ $blogs->nextPageUrl() }}" class="page-btn">&#10095;</a>
                    @else
                        <div class="page-btn disabled">&#10095;</div>
                    @endif
                </div>
            @endif
        @else
            <p style="text-align:center;color:grey;padding:60px 20px;">
                No blog posts found{{ request('search') ? ' for "' . request('search') . '"' : '' }}.
            </p>
        @endif
        <div class="avertisement-slide">
            <div id="carouselExampleInterval" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active" data-bs-interval="4000">
                        <img src="frontend/asset/images/slide-cut-v1.jpg" class="d-block w-100" alt="...">
                    </div>
                    <div class="carousel-item" data-bs-interval="4000">
                        <img src="frontend/asset/images/slide-cut-v7.jpg" class="d-block w-100" alt="...">
                    </div>
                    <div class="carousel-item">
                        <img src="frontend/asset/images/ICT_SlideShow.jpg" class="d-block w-100" alt="...">
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleInterval"
                    data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleInterval"
                    data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </div>
    </div>
@endsection
