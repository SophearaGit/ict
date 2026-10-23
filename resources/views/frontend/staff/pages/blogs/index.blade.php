@extends('frontend.staff.layout.master')
@section('page_title', isset($page_title) ? $page_title : 'Blogs')
@push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Syne:wght@600;700;800&display=swap');

        /* ── ACTION ROW: stat cards + toolbar, side by side ── */
        .blog-action-row {
            display: grid;
            grid-template-columns: 1fr auto;
            align-items: stretch;
            gap: 14px;
            margin-bottom: 18px;
        }

        .blog-stat-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 14px;
        }

        .blog-stat-card {
            background: #fff;
            border: 1px solid #f0f1f5;
            border-radius: 16px;
            padding: 16px 18px;
            display: flex;
            align-items: center;
            gap: 12px;
            box-shadow: 0 2px 10px rgba(20, 20, 43, 0.04);
            text-decoration: none;
            position: relative;
            overflow: hidden;
            transition: transform 0.15s, box-shadow 0.15s, border-color 0.15s;
        }

        a.blog-stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 24px rgba(20, 20, 43, 0.1);
            border-color: #e3e5f0;
            color: inherit;
        }

        .blog-stat-card::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: var(--accent, transparent);
            opacity: 0;
            transition: opacity 0.15s;
        }

        .blog-stat-card.active::before,
        a.blog-stat-card:hover::before {
            opacity: 1;
        }

        .blog-stat-card.active {
            border-color: var(--accent, #e3e5f0);
            background: var(--accent-tint, #fff);
        }

        .blog-stat-icon {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
        }

        .blog-stat-info .blog-stat-value {
            font-family: 'DM Sans', sans-serif;
            font-weight: 800;
            font-size: 22px;
            color: #16151f;
            line-height: 1.2;
            font-variant-numeric: tabular-nums;
        }

        .blog-stat-info .blog-stat-label {
            font-family: 'DM Sans', sans-serif;
            font-size: 12px;
            color: #8a8a9a;
            font-weight: 500;
            margin-top: 2px;
        }

        .blog-toolbar {
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .blog-toolbar-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            padding: 12px 20px;
            border-radius: 12px;
            font-size: 13.5px;
            font-weight: 600;
            font-family: 'DM Sans', sans-serif;
            text-decoration: none;
            white-space: nowrap;
            background: #0f0e17;
            color: #fff;
            border: 1px solid #0f0e17;
            transition: all 0.15s;
            height: 100%;
        }

        .blog-toolbar-btn:hover {
            background: #2749c4;
            border-color: #2749c4;
            color: #fff;
        }

        @media (max-width: 991.98px) {
            .blog-action-row {
                grid-template-columns: 1fr;
            }

            .blog-toolbar-btn {
                height: auto;
            }
        }

        @media (max-width: 767.98px) {
            .blog-stat-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 10px;
            }

            .blog-stat-card {
                padding: 12px 14px;
                border-radius: 13px;
                gap: 10px;
            }

            .blog-stat-icon {
                width: 36px;
                height: 36px;
                font-size: 16px;
            }

            .blog-stat-info .blog-stat-value {
                font-size: 18px;
            }
        }

        /* ── FILTER BAR ── */
        .blog-filter-card {
            background: #fff;
            border: 1px solid #f0f1f5;
            border-radius: 16px;
            padding: 16px 18px;
            margin-bottom: 18px;
            box-shadow: 0 2px 10px rgba(20, 20, 43, 0.04);
        }

        .blog-filter-form {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .blog-filter-search {
            position: relative;
            flex: 1 1 240px;
        }

        .blog-filter-search i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #a3a3b0;
            font-size: 16px;
        }

        .blog-filter-search input {
            width: 100%;
            padding: 10px 14px 10px 38px;
            border-radius: 10px;
            border: 1px solid #e5e5ec;
            background: #f8f8fb;
            font-family: 'DM Sans', sans-serif;
            font-size: 13.5px;
            color: #22212e;
            transition: border-color 0.15s, background 0.15s;
        }

        .blog-filter-search input:focus {
            outline: none;
            border-color: #4f46e5;
            background: #fff;
        }

        .blog-filter-select {
            flex: 0 1 180px;
            padding: 10px 14px;
            border-radius: 10px;
            border: 1px solid #e5e5ec;
            background: #f8f8fb;
            font-family: 'DM Sans', sans-serif;
            font-size: 13.5px;
            color: #22212e;
        }

        .blog-filter-submit {
            flex: 0 0 auto;
            padding: 10px 20px;
            border-radius: 10px;
            border: 1px solid #0f0e17;
            background: #0f0e17;
            color: #fff;
            font-family: 'DM Sans', sans-serif;
            font-size: 13.5px;
            font-weight: 600;
            transition: background 0.15s;
        }

        .blog-filter-submit:hover {
            background: #2749c4;
            border-color: #2749c4;
        }

        .blog-filter-clear {
            flex: 0 0 auto;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 10px 14px;
            border-radius: 10px;
            border: 1px solid #e5e5ec;
            background: #fff;
            color: #6b6b7a;
            font-family: 'DM Sans', sans-serif;
            font-size: 13.5px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.15s;
        }

        .blog-filter-clear:hover {
            background: #f4f4f8;
            color: #22212e;
        }

        @media (max-width: 575.98px) {
            .blog-filter-select {
                flex: 1 1 140px;
            }

            .blog-filter-submit,
            .blog-filter-clear {
                flex: 1 1 auto;
                text-align: center;
                justify-content: center;
            }
        }

        /* ── LIST CARD WRAPPER ── */
        .blog-list-card {
            background: #fff;
            border: 1px solid #f0f1f5;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(20, 20, 43, 0.04);
        }

        /* ── DESKTOP TABLE ── */
        .blog-table {
            width: 100%;
            border-collapse: collapse;
        }

        .blog-table thead th {
            font-family: 'DM Sans', sans-serif;
            font-size: 11.5px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: #9a9aa8;
            padding: 14px 16px;
            border-bottom: 1px solid #f0f1f5;
            text-align: left;
            white-space: nowrap;
        }

        .blog-table tbody tr {
            border-bottom: 1px solid #f5f5f9;
            transition: background 0.12s;
        }

        .blog-table tbody tr:last-child {
            border-bottom: none;
        }

        .blog-table tbody tr:hover {
            background: #fafafd;
        }

        .blog-table td {
            padding: 12px 16px;
            vertical-align: middle;
            font-family: 'DM Sans', sans-serif;
        }

        .blog-post-cell {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .blog-thumb {
            width: 56px;
            height: 42px;
            border-radius: 9px;
            object-fit: cover;
            display: block;
            background: #eef0f6;
            flex-shrink: 0;
        }

        .blog-thumb-fallback {
            width: 56px;
            height: 42px;
            border-radius: 9px;
            background: linear-gradient(135deg, #eef2ff, #e0e7ff);
            color: #4f46e5;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 15px;
            flex-shrink: 0;
        }

        .blog-title-cell .blog-title {
            font-weight: 600;
            font-size: 13.5px;
            color: #16151f;
            margin-bottom: 2px;
        }

        .blog-title-cell .blog-excerpt {
            font-size: 12px;
            color: #9a9aa8;
            max-width: 320px;
        }

        .blog-author {
            font-size: 13px;
            color: #4b4b58;
        }

        .blog-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11.5px;
            font-weight: 600;
            white-space: nowrap;
        }

        .blog-star {
            font-size: 16px;
        }

        .blog-views {
            font-family: 'DM Sans', sans-serif;
            font-size: 13px;
            color: #4b4b58;
            font-variant-numeric: tabular-nums;
        }

        .blog-date {
            font-size: 12.5px;
            color: #9a9aa8;
            white-space: nowrap;
        }

        .blog-row-actions .btn-ghost {
            border: none;
            background: transparent;
            color: #9a9aa8;
            width: 32px;
            height: 32px;
            border-radius: 9px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.15s;
        }

        .blog-row-actions .btn-ghost:hover {
            background: #f4f4f8;
            color: #22212e;
        }

        /* ── MOBILE CARD LIST ── */
        .blog-card-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
            padding: 14px;
        }

        .blog-item-card {
            border: 1px solid #f0f1f5;
            border-radius: 14px;
            padding: 12px;
            display: flex;
            gap: 12px;
        }

        .blog-item-card .blog-thumb,
        .blog-item-card .blog-thumb-fallback {
            width: 72px;
            height: 72px;
            border-radius: 11px;
            flex-shrink: 0;
        }

        .blog-item-main {
            min-width: 0;
            flex: 1;
        }

        .blog-item-main .blog-title {
            font-weight: 600;
            font-size: 14px;
            color: #16151f;
            margin-bottom: 3px;
        }

        .blog-item-main .blog-excerpt {
            font-size: 12.5px;
            color: #9a9aa8;
            margin-bottom: 8px;
        }

        .blog-item-badges {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 6px;
            margin-bottom: 6px;
        }

        .blog-item-meta {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 12px;
            color: #9a9aa8;
        }

        .blog-item-meta .blog-row-actions .btn-ghost {
            width: 28px;
            height: 28px;
        }

        /* ── EMPTY STATE ── */
        .blog-empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 64px 24px;
            text-align: center;
            gap: 6px;
        }

        .blog-empty-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background: rgba(79, 70, 229, 0.08);
            margin-bottom: 10px;
        }

        .blog-empty-icon i {
            font-size: 26px;
            color: #4f46e5;
        }

        .blog-empty-state .blog-empty-title {
            font-family: 'DM Sans', sans-serif;
            font-weight: 700;
            font-size: 16px;
            color: #16151f;
        }

        .blog-empty-state p {
            font-family: 'DM Sans', sans-serif;
            font-size: 13.5px;
            color: #9a9aa8;
            margin: 0;
            max-width: 340px;
        }

        .blog-empty-cta {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            margin-top: 10px;
            padding: 10px 18px;
            border-radius: 10px;
            background: #0f0e17;
            color: #fff;
            font-family: 'DM Sans', sans-serif;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            transition: background 0.15s;
        }

        .blog-empty-cta:hover {
            background: #2749c4;
            color: #fff;
        }

        .blog-card-footer {
            padding: 14px 16px;
            border-top: 1px solid #f0f1f5;
        }
    </style>
@endpush
@section('content')
    @include('frontend.staff.pages.partials.breadcrumb')

    @php
        $curStatus = $current_status ?? null;
        $blogStats = [
            [
                'status' => null,
                'label' => 'Total Posts',
                'value' => $total_count ?? 0,
                'icon' => 'ti-news',
                'accent' => '#4f46e5',
                'tint' => '#f5f4ff',
                'bg' => '#eef2ff',
            ],
            [
                'status' => 'published',
                'label' => 'Published',
                'value' => $published_count ?? 0,
                'icon' => 'ti-circle-check',
                'accent' => '#16a34a',
                'tint' => '#f3fdf6',
                'bg' => '#dcfce7',
            ],
            [
                'status' => 'draft',
                'label' => 'Draft',
                'value' => $draft_count ?? 0,
                'icon' => 'ti-pencil',
                'accent' => '#d97706',
                'tint' => '#fffaf0',
                'bg' => '#fef3c7',
            ],
            [
                'status' => 'scheduled',
                'label' => 'Scheduled',
                'value' => $scheduled_count ?? 0,
                'icon' => 'ti-clock-hour-4',
                'accent' => '#0ea5e9',
                'tint' => '#f0f9ff',
                'bg' => '#e0f2fe',
            ],
        ];
    @endphp

    {{-- ── SUMMARY STATS + TOOLBAR ── --}}
    <div class="blog-action-row">
        <div class="blog-stat-grid">
            @foreach ($blogStats as $stat)
                <a href="{{ route('staff.blogs.index', array_filter(['status' => $stat['status'], 'search' => $current_search ?? null, 'type' => $current_type ?? null])) }}"
                    class="blog-stat-card {{ $curStatus === $stat['status'] ? 'active' : '' }}"
                    style="--accent:{{ $stat['accent'] }};--accent-tint:{{ $stat['tint'] }};">
                    <div class="blog-stat-icon" style="background:{{ $stat['bg'] }};color:{{ $stat['accent'] }};">
                        <i class="ti {{ $stat['icon'] }}"></i>
                    </div>
                    <div class="blog-stat-info">
                        <div class="blog-stat-value">{{ $stat['value'] }}</div>
                        <div class="blog-stat-label">{{ $stat['label'] }}</div>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="blog-toolbar">
            <a href="{{ route('staff.blogs.create') }}" class="blog-toolbar-btn">
                <i class="ti ti-plus"></i> New Blog
            </a>
        </div>
    </div>

    {{-- ── FILTER BAR ── --}}
    <div class="blog-filter-card">
        <form method="GET" class="blog-filter-form">
            @if ($curStatus)
                <input type="hidden" name="status" value="{{ $curStatus }}">
            @endif
            <div class="blog-filter-search">
                <i class="ti ti-search"></i>
                <input type="text" name="search" value="{{ $current_search ?? '' }}" placeholder="Search title…">
            </div>
            <select name="type" class="blog-filter-select">
                <option value="">All types</option>
                @foreach (['article', 'facebook', 'tiktok', 'youtube'] as $type)
                    <option value="{{ $type }}" @selected(($current_type ?? null) === $type)>
                        {{ ucfirst($type) }}
                    </option>
                @endforeach
            </select>
            <button type="submit" class="blog-filter-submit">
                <i class="ti ti-filter"></i> Filter
            </button>
            @if (($current_search ?? null) || ($current_type ?? null) || $curStatus)
                <a href="{{ route('staff.blogs.index') }}" class="blog-filter-clear">
                    <i class="ti ti-x"></i> Clear
                </a>
            @endif
        </form>
    </div>

    {{-- ── LIST ── --}}
    <div class="blog-list-card">
        @if ($blogs->isEmpty())
            <div class="blog-empty-state">
                <div class="blog-empty-icon"><i class="ti ti-news"></i></div>
                @if (($current_search ?? null) || ($current_type ?? null) || $curStatus)
                    <div class="blog-empty-title">No blogs match your filters</div>
                    <p>Try a different search term, type, or status.</p>
                    <a href="{{ route('staff.blogs.index') }}" class="blog-empty-cta">
                        <i class="ti ti-x"></i> Clear filters
                    </a>
                @else
                    <div class="blog-empty-title">No blog posts yet</div>
                    <p>Published articles, Facebook, TikTok and YouTube posts you add will show up here.</p>
                    <a href="{{ route('staff.blogs.create') }}" class="blog-empty-cta">
                        <i class="ti ti-plus"></i> Write your first post
                    </a>
                @endif
            </div>
        @else
            {{-- Desktop / tablet table --}}
            <div class="table-responsive d-none d-lg-block">
                <table class="blog-table">
                    <thead>
                        <tr>
                            <th>Post</th>
                            <th>Author</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Featured</th>
                            <th>Views</th>
                            <th>Published</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($blogs as $blog)
                            <tr>
                                <td class="blog-title-cell">
                                    <div class="blog-post-cell">
                                        @if ($blog->thumbnail_url)
                                            <img src="{{ $blog->thumbnail_url }}" alt="" class="blog-thumb">
                                        @else
                                            <div class="blog-thumb-fallback">{{ Str::substr($blog->title, 0, 1) }}</div>
                                        @endif
                                        <div>
                                            <div class="blog-title">{{ Str::limit($blog->title, 48) }}</div>
                                            @if ($blog->excerpt)
                                                <div class="blog-excerpt">{{ Str::limit($blog->excerpt, 60) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="blog-author">{{ $blog->author()?->name ?? '—' }}</td>
                                <td>
                                    @switch($blog->type)
                                        @case('article')
                                            <span class="blog-badge" style="background:#f4f4f8;color:#4b4b58;">
                                                <i class="ti ti-file-text"></i> Article
                                            </span>
                                        @break

                                        @case('facebook')
                                            <span class="blog-badge" style="background:#eef2ff;color:#4f46e5;">
                                                <i class="ti ti-brand-facebook"></i> Facebook
                                            </span>
                                        @break

                                        @case('tiktok')
                                            <span class="blog-badge" style="background:#f4f4f8;color:#16151f;">
                                                <i class="ti ti-brand-tiktok"></i> TikTok
                                            </span>
                                        @break

                                        @case('youtube')
                                            <span class="blog-badge" style="background:#fee2e2;color:#dc2626;">
                                                <i class="ti ti-brand-youtube"></i> YouTube
                                            </span>
                                        @break

                                        @default
                                            <span class="blog-badge" style="background:#f4f4f8;color:#6b6b7a;">
                                                <i class="ti ti-world"></i> {{ ucfirst($blog->type) }}
                                            </span>
                                    @endswitch
                                </td>
                                <td>
                                    @if ($blog->status === 'published')
                                        <span class="blog-badge" style="background:#dcfce7;color:#16a34a;">
                                            <i class="ti ti-circle-check"></i> Published
                                        </span>
                                    @elseif ($blog->status === 'scheduled')
                                        <span class="blog-badge" style="background:#e0f2fe;color:#0ea5e9;"
                                            title="{{ $blog->published_at?->timezone('Asia/Phnom_Penh')->format('F j, Y \a\t g:i A') }}">
                                            <i class="ti ti-clock-hour-4"></i> Scheduled
                                        </span>
                                    @else
                                        <span class="blog-badge" style="background:#fef3c7;color:#d97706;">
                                            <i class="ti ti-pencil"></i> Draft
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if ($blog->is_featured)
                                        <i class="ti ti-star-filled blog-star" style="color:#eab308;"></i>
                                    @else
                                        <i class="ti ti-star blog-star" style="color:#d4d4dc;"></i>
                                    @endif
                                </td>
                                <td class="blog-views">{{ number_format($blog->views) }}</td>
                                <td class="blog-date">
                                    {{ $blog->published_at?->timezone('Asia/Phnom_Penh')->format('M d, Y') ?? '—' }}
                                </td>
                                <td class="text-end blog-row-actions">
                                    <div class="dropdown">
                                        <button class="btn-ghost" type="button" data-bs-toggle="dropdown">
                                            <i class="ti ti-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li><a class="dropdown-item" href="{{ route('staff.blogs.show', $blog) }}"><i
                                                        class="ti ti-eye dropdown-item-icon"></i> View</a></li>
                                            <li><a class="dropdown-item" href="{{ route('staff.blogs.edit', $blog) }}"><i
                                                        class="ti ti-pencil dropdown-item-icon"></i> Edit</a></li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
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
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Mobile card list --}}
            <div class="blog-card-list d-lg-none">
                @foreach ($blogs as $blog)
                    <div class="blog-item-card">
                        @if ($blog->thumbnail_url)
                            <img src="{{ $blog->thumbnail_url }}" alt="" class="blog-thumb">
                        @else
                            <div class="blog-thumb-fallback">{{ Str::substr($blog->title, 0, 1) }}</div>
                        @endif
                        <div class="blog-item-main">
                            <div class="blog-title">{{ Str::limit($blog->title, 46) }}</div>
                            @if ($blog->excerpt)
                                <div class="blog-excerpt">{{ Str::limit($blog->excerpt, 54) }}</div>
                            @endif
                            <div class="blog-item-badges">
                                @switch($blog->type)
                                    @case('facebook')
                                        <span class="blog-badge" style="background:#eef2ff;color:#4f46e5;">
                                            <i class="ti ti-brand-facebook"></i> Facebook
                                        </span>
                                    @break

                                    @case('tiktok')
                                        <span class="blog-badge" style="background:#f4f4f8;color:#16151f;">
                                            <i class="ti ti-brand-tiktok"></i> TikTok
                                        </span>
                                    @break

                                    @case('youtube')
                                        <span class="blog-badge" style="background:#fee2e2;color:#dc2626;">
                                            <i class="ti ti-brand-youtube"></i> YouTube
                                        </span>
                                    @break

                                    @default
                                        <span class="blog-badge" style="background:#f4f4f8;color:#4b4b58;">
                                            <i class="ti ti-file-text"></i> Article
                                        </span>
                                @endswitch

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
                                    <i class="ti ti-star-filled blog-star" style="color:#eab308;"></i>
                                @endif
                            </div>
                            <div class="blog-item-meta">
                                <span><i class="ti ti-eye"></i> {{ number_format($blog->views) }} ·
                                    {{ $blog->published_at?->timezone('Asia/Phnom_Penh')->format('M d, Y') ?? '—' }}</span>
                                <div class="blog-row-actions">
                                    <div class="dropdown">
                                        <button class="btn-ghost" type="button" data-bs-toggle="dropdown">
                                            <i class="ti ti-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li><a class="dropdown-item" href="{{ route('staff.blogs.show', $blog) }}"><i
                                                        class="ti ti-eye dropdown-item-icon"></i> View</a></li>
                                            <li><a class="dropdown-item" href="{{ route('staff.blogs.edit', $blog) }}"><i
                                                        class="ti ti-pencil dropdown-item-icon"></i> Edit</a></li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
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
                        </div>
                    </div>
                @endforeach
            </div>

            @if ($blogs->hasPages())
                <div class="blog-card-footer">
                    {{ $blogs->appends(request()->query())->links('components.paginate-geek') }}
                </div>
            @endif
        @endif
    </div>
@endsection
@push('scripts')
@endpush
