@extends('frontend.staff.layout.master')
@section('page_title', isset($page_title) ? $page_title : 'Page Title Here')
@push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&family=Syne:wght@600;700;800&display=swap');

        /* ── TOOLBAR ── */
        .inv-toolbar {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-bottom: 14px;
        }

        .inv-toolbar-btn {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 9px 16px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
            font-family: 'DM Sans', sans-serif;
            text-decoration: none;
            transition: all 0.15s;
        }

        .inv-toolbar-btn-outline {
            background: #fff;
            color: #3a3a46;
            border: 1px solid #e2e2e8;
        }

        .inv-toolbar-btn-outline:hover {
            background: #f4f4f8;
            color: #3a3a46;
        }

        .inv-toolbar-btn-solid {
            background: #0f0e17;
            color: #fff;
            border: 1px solid #0f0e17;
        }

        .inv-toolbar-btn-solid:hover {
            background: #2749c4;
            border-color: #2749c4;
            color: #fff;
        }

        @media (max-width: 575.98px) {
            .inv-toolbar {
                margin-bottom: 10px;
            }

            .inv-toolbar-btn {
                padding: 8px 12px;
                font-size: 12px;
            }
        }

        /* ── SUMMARY STATS ── */
        .inv-stat-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin-bottom: 20px;
        }

        .inv-stat-card {
            background: #fff;
            border: 1px solid #f0f1f5;
            border-radius: 16px;
            padding: 18px 20px;
            display: flex;
            align-items: center;
            gap: 14px;
            box-shadow: 0 2px 10px rgba(20, 20, 43, 0.04);
            text-decoration: none;
            position: relative;
            overflow: hidden;
            transition: transform 0.15s, box-shadow 0.15s, border-color 0.15s;
        }

        a.inv-stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 24px rgba(20, 20, 43, 0.1);
            border-color: #e3e5f0;
        }

        .inv-stat-card::before {
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

        .inv-stat-card.active::before,
        a.inv-stat-card:hover::before {
            opacity: 1;
        }

        .inv-stat-card.active {
            border-color: var(--accent, #e3e5f0);
            background: var(--accent-tint, #fff);
        }

        .inv-stat-icon {
            width: 46px;
            height: 46px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
        }

        .inv-stat-info .inv-stat-value {
            /* Syne (used for the headline/labels elsewhere on this page)
               draws a thin, open-stroke "0" at display sizes that reads
               as a hollow circle rather than a zero — fine in a heading,
               misleading as the actual number on a stat card. DM Sans'
               digits are unambiguous at any weight, so the number itself
               uses that instead; Syne stays for the card label/headings. */
            font-family: 'DM Sans', sans-serif;
            font-weight: 800;
            font-size: 25px;
            color: #16151f;
            line-height: 1.2;
            font-variant-numeric: tabular-nums;
        }

        .inv-stat-info .inv-stat-label {
            font-family: 'DM Sans', sans-serif;
            font-size: 12.5px;
            color: #8a8a9a;
            font-weight: 500;
            margin-top: 2px;
        }

        @media (max-width: 1199.98px) {
            .inv-stat-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 575.98px) {
            .inv-stat-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 10px;
                margin-bottom: 14px;
            }

            .inv-stat-card {
                padding: 13px 14px;
                border-radius: 13px;
                gap: 10px;
            }

            .inv-stat-icon {
                width: 38px;
                height: 38px;
                font-size: 17px;
                border-radius: 10px;
            }

            .inv-stat-info .inv-stat-value {
                font-size: 17px;
            }

            .inv-stat-info .inv-stat-label {
                font-size: 11px;
            }
        }

        .invoice-application {
            border: none;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.07);
        }

        /* ── LEFT PANEL ── */
        /* No fixed height here — it stretches to match .inv-main's actual
           height (the row's other flex item, via the parent's default
           align-items: stretch), so the dark sidebar always runs the full
           height of the invoice next to it instead of stopping short and
           leaving blank space below it. min-height keeps it from looking
           too short when an invoice is small/empty. .inv-list's flex:1
           then simply fills whatever extra height that gives it, still
           scrolling internally if there are more students than fit. */
        .inv-sidebar {
            width: 320px;
            min-width: 320px;
            min-height: calc(100vh - 180px);
            background: #0f0e17;
            display: flex;
            flex-direction: column;
        }

        .inv-sidebar-header {
            padding: 20px 18px 14px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.07);
        }

        .inv-sidebar-header .inv-search-wrap {
            position: relative;
        }

        .inv-sidebar-header .inv-search-wrap input {
            width: 100%;
            background: rgba(255, 255, 255, 0.07);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            padding: 9px 14px 9px 38px;
            color: #fff;
            font-size: 13px;
            font-family: 'DM Sans', sans-serif;
            outline: none;
            transition: border-color 0.2s;
        }

        .inv-sidebar-header .inv-search-wrap input::placeholder {
            color: rgba(255, 255, 255, 0.35);
        }

        .inv-sidebar-header .inv-search-wrap input:focus {
            border-color: rgba(255, 255, 255, 0.3);
        }

        .inv-sidebar-header .inv-search-wrap i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255, 255, 255, 0.35);
            font-size: 15px;
        }

        /* ── FILTER CHIPS ── */
        /* flex-wrap (not a horizontal scroller) — at the sidebar's 320px
           width, "All/Unpaid/Half Paid/Paid" plus gaps don't fit on one
           line, and a scroll-only row left "Paid" permanently half-cut
           off at the edge with no visible hint that more was there.
           Wrapping to a second line keeps every chip fully visible and
           tappable without a scroll gesture. */
        .inv-chip-row {
            display: flex;
            flex-wrap: wrap;
            gap: 7px;
            margin-top: 12px;
        }

        .inv-chip {
            flex-shrink: 0;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 11.5px;
            font-weight: 600;
            font-family: 'DM Sans', sans-serif;
            text-decoration: none;
            background: rgba(255, 255, 255, 0.06);
            color: rgba(255, 255, 255, 0.55);
            border: 1px solid rgba(255, 255, 255, 0.08);
            transition: all 0.15s;
            white-space: nowrap;
        }

        .inv-chip:hover {
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
        }

        .inv-chip .inv-chip-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .inv-chip.active {
            background: var(--chip-color, #4f46e5);
            color: #fff;
            border-color: transparent;
        }

        .inv-chip.active .inv-chip-dot {
            background: #fff;
        }

        /* ── SORT ── */
        .inv-sort-row {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 12px;
        }

        .inv-sort-row label {
            font-size: 11px;
            color: rgba(255, 255, 255, 0.35);
            font-family: 'DM Sans', sans-serif;
            flex-shrink: 0;
        }

        .inv-sort-select {
            flex: 1;
            background: rgba(255, 255, 255, 0.07);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            padding: 6px 10px;
            color: #fff;
            font-size: 11.5px;
            font-family: 'DM Sans', sans-serif;
            outline: none;
        }

        .inv-sort-select option {
            background: #0f0e17;
            color: #fff;
        }

        .inv-list {
            list-style: none;
            margin: 0;
            padding: 0;
            overflow-y: auto;
            flex: 1;
        }

        .inv-list::-webkit-scrollbar {
            width: 4px;
        }

        .inv-list::-webkit-scrollbar-track {
            background: transparent;
        }

        .inv-list::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 4px;
        }

        .inv-list-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 18px;
            cursor: pointer;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            transition: background 0.15s;
            text-decoration: none;
        }

        .inv-list-item:hover {
            background: rgba(255, 255, 255, 0.05);
        }

        .inv-list-item.active {
            background: rgba(79, 70, 229, 0.16);
            border-left: 3px solid #4f46e5;
        }

        .inv-list-avatar {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 15px;
        }

        .inv-list-avatar.paid {
            background: rgba(34, 197, 94, 0.15);
            color: #22c55e;
        }

        .inv-list-avatar.half {
            background: rgba(234, 179, 8, 0.15);
            color: #eab308;
        }

        .inv-list-avatar.unpaid {
            background: rgba(239, 68, 68, 0.15);
            color: #ef4444;
        }

        .inv-list-avatar.free {
            background: rgba(16, 185, 129, 0.15);
            color: #10b981;
        }

        .inv-list-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 10px;
        }

        .inv-list-avatar.has-photo {
            padding: 0;
            overflow: hidden;
            box-shadow: inset 0 0 0 2px rgba(255, 255, 255, 0.08);
        }

        .inv-list-info {
            flex: 1;
            min-width: 0;
        }

        .inv-list-name {
            font-family: 'DM Sans', sans-serif;
            font-weight: 600;
            font-size: 13px;
            color: #fff;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            margin-bottom: 2px;
        }

        .inv-list-code {
            font-size: 11px;
            color: rgba(255, 255, 255, 0.4);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .inv-list-course {
            font-size: 11px;
            color: rgba(255, 255, 255, 0.3);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            margin-top: 1px;
        }

        .inv-list-meta {
            flex-shrink: 0;
            text-align: right;
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 4px;
        }

        .inv-list-amount {
            font-family: 'DM Sans', sans-serif;
            font-weight: 700;
            font-size: 13px;
            white-space: nowrap;
        }

        .inv-list-amount.paid,
        .inv-list-amount.free {
            color: #22c55e;
        }

        .inv-list-amount.half {
            color: #eab308;
        }

        .inv-list-amount.unpaid {
            color: #f87171;
        }

        .inv-list-date {
            font-size: 10.5px;
            color: rgba(255, 255, 255, 0.3);
            white-space: nowrap;
            flex-shrink: 0;
        }

        /* Pagination */
        .inv-pagination {
            padding: 12px 18px;
            border-top: 1px solid rgba(255, 255, 255, 0.07);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .inv-pagination span {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.4);
            font-family: 'DM Sans', sans-serif;
        }

        .inv-pagination-btns {
            display: flex;
            gap: 6px;
        }

        .inv-pagination-btns a,
        .inv-pagination-btns span {
            width: 28px;
            height: 28px;
            border-radius: 7px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            color: rgba(255, 255, 255, 0.5);
            background: rgba(255, 255, 255, 0.06);
            text-decoration: none;
            transition: all 0.15s;
        }

        .inv-pagination-btns a:hover {
            background: rgba(255, 255, 255, 0.12);
            color: #fff;
        }

        .inv-pagination-btns span.disabled {
            opacity: 0.3;
            pointer-events: none;
        }

        /* ── RIGHT PANEL ── */
        /* No fixed height / overflow here on purpose: a full invoice
           (course table + payment history + totals) is often taller than
           one screen, and clipping it into a small internally-scrolling
           box made it feel cramped. min-height keeps it from collapsing
           when empty; beyond that it just grows with its content and the
           page scrolls normally, same as the sidebar list next to it. */
        .inv-main {
            flex: 1;
            background: #f8f8fb;
            min-height: calc(100vh - 180px);
            min-width: 0;
        }

        .inv-empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: calc(100vh - 280px);
            color: #aaa;
            gap: 10px;
            padding: 24px;
            text-align: center;
        }

        .inv-empty-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background: rgba(39, 73, 196, 0.08);
            margin-bottom: 4px;
        }

        .inv-empty-state i {
            font-size: 26px;
            color: #2749c4;
        }

        .inv-empty-state p {
            font-family: 'DM Sans', sans-serif;
            font-size: 14px;
            color: #9a9aa8;
            margin: 0;
        }

        .inv-empty-state .inv-empty-title {
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            font-size: 17px;
            color: #3a3a46;
        }

        .inv-empty-cta {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            margin-top: 6px;
            padding: 9px 18px;
            border-radius: 10px;
            background: #0f0e17;
            color: #fff;
            font-family: 'DM Sans', sans-serif;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            transition: background 0.15s;
        }

        .inv-empty-cta:hover {
            background: #2749c4;
            color: #fff;
        }

        .inv-loader {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: calc(100vh - 280px);
        }

        /* Mobile bar */
        .inv-mobile-bar {
            display: none;
            padding: 14px 16px;
            background: #0f0e17;
            gap: 12px;
            align-items: center;
            justify-content: space-between;
        }

        .inv-mobile-bar .inv-mobile-bar-left {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .inv-mobile-bar .inv-mobile-title {
            color: #fff;
            font-size: 14px;
            font-family: 'Syne', sans-serif;
            font-weight: 700;
        }

        .inv-mobile-bar .inv-mobile-count {
            color: rgba(255, 255, 255, 0.4);
            font-size: 12px;
            font-family: 'DM Sans', sans-serif;
        }

        @media (max-width: 991px) {
            .inv-mobile-bar {
                display: flex;
            }

            .inv-sidebar {
                display: none;
            }

            .inv-main {
                min-height: calc(100vh - 200px);
            }
        }
    </style>
@endpush
@section('content')
    @include('frontend.staff.pages.partials.breadcrumb')

    @php
        $invChips = [
            ['label' => 'All', 'status' => null, 'count' => $total_invoices_count ?? 0, 'color' => '#4f46e5'],
            ['label' => 'Unpaid', 'status' => 'unpaid', 'count' => $unpaid_count ?? 0, 'color' => '#ef4444'],
            [
                'label' => 'Half Paid',
                'status' => 'half_paid',
                'count' => $half_paid_count ?? 0,
                'color' => '#eab308',
            ],
            ['label' => 'Paid', 'status' => 'paid', 'count' => $paid_count ?? 0, 'color' => '#22c55e'],
        ];
        $curStatus = $current_status ?? null;
        $exportParams = array_filter([
            'search' => $current_search ?? null,
            'status' => $curStatus,
            'sort' => $current_sort ?? null,
        ]);
    @endphp

    {{-- ── TOOLBAR ── --}}
    <div class="inv-toolbar">
        <a href="{{ route('staff.invoices.export', $exportParams) }}" class="inv-toolbar-btn inv-toolbar-btn-outline">
            <i class="ti ti-download"></i> <span class="d-none d-sm-inline">Export CSV</span>
        </a>
        <a href="{{ route('staff.student.registration') }}" class="inv-toolbar-btn inv-toolbar-btn-solid">
            <i class="ti ti-user-plus"></i> <span class="d-none d-sm-inline">Register Student</span>
        </a>
    </div>

    {{-- ── SUMMARY STATS ── --}}
    {{-- Each card is a link into the same filters the chips below use, so
         staff can jump straight from "12 awaiting payment" to that exact
         list without re-finding it manually. --}}
    <div class="inv-stat-grid">
        <a href="{{ route('staff.invoices') }}"
            class="inv-stat-card {{ is_null($curStatus) ? 'active' : '' }}"
            style="--accent:#4f46e5;--accent-tint:#f5f4ff;">
            <div class="inv-stat-icon" style="background:#eef2ff;color:#4f46e5;">
                <i class="ti ti-file-invoice"></i>
            </div>
            <div class="inv-stat-info">
                <div class="inv-stat-value">{{ $total_invoices_count ?? 0 }}</div>
                <div class="inv-stat-label">Total Invoices</div>
            </div>
        </a>
        <a href="{{ route('staff.invoices', ['status' => 'paid']) }}"
            class="inv-stat-card {{ $curStatus === 'paid' ? 'active' : '' }}"
            style="--accent:#16a34a;--accent-tint:#f3fdf6;">
            <div class="inv-stat-icon" style="background:#dcfce7;color:#16a34a;">
                <i class="ti ti-circle-check"></i>
            </div>
            <div class="inv-stat-info">
                <div class="inv-stat-value">{{ $paid_count ?? 0 }}</div>
                <div class="inv-stat-label">Fully Paid</div>
            </div>
        </a>
        <a href="{{ route('staff.invoices', ['status' => 'awaiting']) }}"
            class="inv-stat-card {{ $curStatus === 'awaiting' ? 'active' : '' }}"
            style="--accent:#d97706;--accent-tint:#fffaf0;">
            <div class="inv-stat-icon" style="background:#fef3c7;color:#d97706;">
                <i class="ti ti-clock-exclamation"></i>
            </div>
            <div class="inv-stat-info">
                <div class="inv-stat-value">{{ $awaiting_count ?? 0 }}</div>
                <div class="inv-stat-label">Awaiting Payment</div>
            </div>
        </a>
        <a href="{{ route('staff.invoices', ['status' => 'awaiting']) }}"
            class="inv-stat-card {{ $curStatus === 'awaiting' ? 'active' : '' }}"
            style="--accent:#dc2626;--accent-tint:#fff5f5;">
            <div class="inv-stat-icon" style="background:#fee2e2;color:#dc2626;">
                <i class="ti ti-cash-banknote"></i>
            </div>
            <div class="inv-stat-info">
                <div class="inv-stat-value">${{ number_format($outstanding_total ?? 0, 2) }}</div>
                <div class="inv-stat-label">Outstanding Balance</div>
            </div>
        </a>
    </div>

    <div class="card invoice-application" style="border-radius:16px;">
        {{-- Mobile bar --}}
        <div class="inv-mobile-bar">
            <div class="inv-mobile-bar-left">
                <button class="btn btn-sm btn-outline-light" type="button" data-bs-toggle="offcanvas"
                    data-bs-target="#inv-offcanvas">
                    <i class="ti ti-menu-2"></i>
                </button>
                <span class="inv-mobile-title">Invoices</span>
            </div>
            <span class="inv-mobile-count">{{ $total_invoices_count ?? 0 }} total</span>
        </div>
        <div class="d-flex" style="min-height:calc(100vh - 180px);">
            {{-- ── LEFT SIDEBAR ── --}}
            <div class="inv-sidebar d-none d-lg-flex flex-column">
                <div class="inv-sidebar-header">
                    <form class="inv-search-wrap" method="GET" action="{{ route('staff.invoices') }}">
                        <i class="ti ti-search"></i>
                        <input type="search" name="search" value="{{ request('search') }}"
                            placeholder="Search student or code…">
                        @if ($current_status ?? null)
                            <input type="hidden" name="status" value="{{ $current_status }}">
                        @endif
                        @if ($current_sort ?? null)
                            <input type="hidden" name="sort" value="{{ $current_sort }}">
                        @endif
                    </form>
                    <div class="inv-chip-row">
                        @foreach ($invChips as $chip)
                            @php
                                $chipActive =
                                    ($current_status ?? null) === $chip['status'] ||
                                    (($current_status ?? null) === 'awaiting' &&
                                        in_array($chip['status'], ['unpaid', 'half_paid']));
                            @endphp
                            <a href="{{ route('staff.invoices', array_filter(['status' => $chip['status'], 'search' => $current_search ?? null, 'sort' => $current_sort ?? null])) }}"
                                class="inv-chip {{ $chipActive ? 'active' : '' }}"
                                style="--chip-color: {{ $chip['color'] }};">
                                <span class="inv-chip-dot" style="background: {{ $chip['color'] }};"></span>
                                {{ $chip['label'] }} ({{ $chip['count'] }})
                            </a>
                        @endforeach
                    </div>
                    <div class="inv-sort-row">
                        <label for="inv-sort-desktop">Sort</label>
                        <select id="inv-sort-desktop" class="inv-sort-select" onchange="invApplySort(this.value)">
                            <option value="newest" {{ ($current_sort ?? 'newest') === 'newest' ? 'selected' : '' }}>
                                Newest First</option>
                            <option value="oldest" {{ ($current_sort ?? '') === 'oldest' ? 'selected' : '' }}>Oldest
                                First</option>
                            <option value="amount_desc"
                                {{ ($current_sort ?? '') === 'amount_desc' ? 'selected' : '' }}>Amount: High to Low
                            </option>
                            <option value="amount_asc" {{ ($current_sort ?? '') === 'amount_asc' ? 'selected' : '' }}>
                                Amount: Low to High</option>
                        </select>
                    </div>
                </div>
                <ul class="inv-list">
                    @forelse ($invoices as $invoice)
                        @php
                            $statusClass = match ($invoice->payment_status) {
                                'paid' => 'paid',
                                'half_paid' => 'half',
                                'free' => 'free',
                                default => 'unpaid',
                            };
                            $displayAmount = in_array($invoice->payment_status, ['paid', 'free'])
                                ? $invoice->total_amount
                                : $invoice->remaining_amount;
                            $hasPhoto = $invoice->student->image && $invoice->student->image !== 'no-img.jpg';
                        @endphp
                        <li>
                            <a href="javascript:void(0)" class="inv-list-item btn_view_invoice_detail"
                                id="invoice-{{ $invoice->id }}" data-invoice-id="{{ $invoice->id }}">
                                <div class="inv-list-avatar {{ $statusClass }} {{ $hasPhoto ? 'has-photo' : '' }}">
                                    @if ($hasPhoto)
                                        <img src="{{ asset($invoice->student->image) }}"
                                            alt="{{ $invoice->student->name }}">
                                    @else
                                        <i class="ti ti-user"></i>
                                    @endif
                                </div>
                                <div class="inv-list-info">
                                    <div class="inv-list-name">{{ $invoice->student->name }}</div>
                                    <div class="inv-list-code">{{ $invoice->invoice_code }}</div>
                                    @if ($invoice->course)
                                        <div class="inv-list-course">{{ Str::limit($invoice->course->title, 26) }}
                                        </div>
                                    @endif
                                </div>
                                <div class="inv-list-meta">
                                    <div class="inv-list-amount {{ $statusClass }}">
                                        {{ $invoice->payment_status === 'free' ? 'Free' : '$' . number_format($displayAmount, 0) }}
                                    </div>
                                    <div class="inv-list-date">{{ $invoice->created_at->format('d M') }}</div>
                                </div>
                            </a>
                        </li>
                    @empty
                        <li style="padding:50px 18px;text-align:center;">
                            <i class="ti ti-file-off"
                                style="font-size:30px;color:rgba(255,255,255,0.15);display:block;margin-bottom:10px;"></i>
                            <span
                                style="color:rgba(255,255,255,0.3);font-family:'DM Sans',sans-serif;font-size:13px;">
                                @if (($current_search ?? null) || ($current_status ?? null))
                                    No invoices match this filter
                                @else
                                    No invoices found
                                @endif
                            </span>
                        </li>
                    @endforelse
                </ul>
                @if ($invoices->hasPages())
                    <div class="inv-pagination">
                        <span>{{ $invoices->currentPage() }} / {{ $invoices->lastPage() }}</span>
                        <div class="inv-pagination-btns">
                            @if ($invoices->onFirstPage())
                                <span class="disabled">‹</span>
                            @else
                                <a href="{{ $invoices->previousPageUrl() }}">‹</a>
                            @endif
                            @if ($invoices->hasMorePages())
                                <a href="{{ $invoices->nextPageUrl() }}">›</a>
                            @else
                                <span class="disabled">›</span>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
            {{-- ── RIGHT MAIN ── --}}
            <div class="inv-main">
                <div class="invoiceing-box">
                    <div class="inv-empty-state">
                        <span class="inv-empty-icon"><i class="ti ti-file-invoice"></i></span>
                        @if (($invoices->total() ?? 0) === 0 && !($current_search ?? null) && !($current_status ?? null))
                            <p class="inv-empty-title">No invoices yet</p>
                            <p>Invoices appear here automatically once you register a student for a paid course.</p>
                            <a href="{{ route('staff.student.registration') }}" class="inv-empty-cta">
                                <i class="ti ti-user-plus"></i> Register a Student
                            </a>
                        @else
                            <p>Select an invoice to view details</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Mobile offcanvas --}}
    <div class="offcanvas offcanvas-start" tabindex="-1" id="inv-offcanvas" style="background:#0f0e17;width:320px;">
        <div class="offcanvas-header" style="border-bottom:1px solid rgba(255,255,255,0.07);">
            <span style="color:#fff;font-family:'Syne',sans-serif;font-weight:700;font-size:16px;">Invoices</span>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="p-3" style="border-bottom:1px solid rgba(255,255,255,0.07);">
            <form class="inv-search-wrap" method="GET" action="{{ route('staff.invoices') }}">
                <i class="ti ti-search"></i>
                <input type="search" name="search" value="{{ request('search') }}" placeholder="Search…">
                @if ($current_status ?? null)
                    <input type="hidden" name="status" value="{{ $current_status }}">
                @endif
                @if ($current_sort ?? null)
                    <input type="hidden" name="sort" value="{{ $current_sort }}">
                @endif
            </form>
            <div class="inv-chip-row">
                @foreach ($invChips as $chip)
                    @php
                        $chipActiveMob =
                            ($current_status ?? null) === $chip['status'] ||
                            (($current_status ?? null) === 'awaiting' &&
                                in_array($chip['status'], ['unpaid', 'half_paid']));
                    @endphp
                    <a href="{{ route('staff.invoices', array_filter(['status' => $chip['status'], 'search' => $current_search ?? null, 'sort' => $current_sort ?? null])) }}"
                        class="inv-chip {{ $chipActiveMob ? 'active' : '' }}"
                        style="--chip-color: {{ $chip['color'] }};">
                        <span class="inv-chip-dot" style="background: {{ $chip['color'] }};"></span>
                        {{ $chip['label'] }} ({{ $chip['count'] }})
                    </a>
                @endforeach
            </div>
            <div class="inv-sort-row">
                <label for="inv-sort-mobile">Sort</label>
                <select id="inv-sort-mobile" class="inv-sort-select" onchange="invApplySort(this.value)">
                    <option value="newest" {{ ($current_sort ?? 'newest') === 'newest' ? 'selected' : '' }}>Newest
                        First</option>
                    <option value="oldest" {{ ($current_sort ?? '') === 'oldest' ? 'selected' : '' }}>Oldest First
                    </option>
                    <option value="amount_desc" {{ ($current_sort ?? '') === 'amount_desc' ? 'selected' : '' }}>
                        Amount: High to Low</option>
                    <option value="amount_asc" {{ ($current_sort ?? '') === 'amount_asc' ? 'selected' : '' }}>Amount:
                        Low to High</option>
                </select>
            </div>
        </div>
        <ul class="inv-list">
            @forelse ($invoices as $invoice)
                @php
                    $statusClass = match ($invoice->payment_status) {
                        'paid' => 'paid',
                        'half_paid' => 'half',
                        'free' => 'free',
                        default => 'unpaid',
                    };
                    $displayAmount = in_array($invoice->payment_status, ['paid', 'free'])
                        ? $invoice->total_amount
                        : $invoice->remaining_amount;
                    $hasPhotoMob = $invoice->student->image && $invoice->student->image !== 'no-img.jpg';
                @endphp
                <li>
                    <a href="javascript:void(0)" class="inv-list-item btn_view_invoice_detail"
                        id="invoice-mob-{{ $invoice->id }}" data-invoice-id="{{ $invoice->id }}"
                        data-bs-dismiss="offcanvas">
                        <div class="inv-list-avatar {{ $statusClass }} {{ $hasPhotoMob ? 'has-photo' : '' }}">
                            @if ($hasPhotoMob)
                                <img src="{{ asset($invoice->student->image) }}" alt="{{ $invoice->student->name }}">
                            @else
                                <i class="ti ti-user"></i>
                            @endif
                        </div>
                        <div class="inv-list-info">
                            <div class="inv-list-name">{{ $invoice->student->name }}</div>
                            <div class="inv-list-code">{{ $invoice->invoice_code }}</div>
                        </div>
                        <div class="inv-list-meta">
                            <div class="inv-list-amount {{ $statusClass }}">
                                {{ $invoice->payment_status === 'free' ? 'Free' : '$' . number_format($displayAmount, 0) }}
                            </div>
                        </div>
                    </a>
                </li>
            @empty
                <li style="padding:40px 18px;text-align:center;color:rgba(255,255,255,0.3);font-size:13px;">
                    No invoices found
                </li>
            @endforelse
        </ul>
    </div>
@endsection
@push('scripts')
    <script src="/admin/assets/dist/js/apps/jquery.PrintArea.js"></script>
    <script>
        // Sort dropdown (desktop sidebar + mobile offcanvas) — reload with
        // the chosen sort applied on top of whatever search/status filter
        // is already active, rather than resetting them.
        function invApplySort(value) {
            const url = new URL(window.location.href);
            url.searchParams.set('sort', value);
            window.location.href = url.toString();
        }
        let loader = `
            <div style="display:flex;align-items:center;justify-content:center;min-height:calc(100vh - 280px);">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        `;
        $(document).on('click', '.btn_view_invoice_detail', function(e) {
            e.preventDefault();
            // Active state
            $('.inv-list-item').removeClass('active');
            $(this).addClass('active');
            let invoice_id = $(this).data('invoice-id');
            // If full screen mode moved the previous invoice's markup out
            // to a direct child of <body> (see inv-body.blade.php), it's
            // no longer inside .invoiceing-box and won't be cleared by the
            // .html() call below on its own — drop it explicitly so
            // switching invoices while full screen is open doesn't leave
            // a stale overlay stuck on the page.
            $('body > .inv-detail-wrap').remove();
            $('body').removeClass('inv-fullscreen-open');
            $.ajax({
                method: 'GET',
                url: base_url + `/staff/invoice-detail/${invoice_id}`,
                beforeSend: function() {
                    $('.invoiceing-box').html(loader);
                },
                success: function(data) {
                    $('.invoiceing-box').html(data);
                },
                error: function() {
                    $('.invoiceing-box').html(`
                        <div style="display:flex;align-items:center;justify-content:center;min-height:calc(100vh - 280px);color:#aaa;font-family:'DM Sans',sans-serif;">
                            Failed to load invoice.
                        </div>
                    `);
                }
            });
        });
        // Print
        $(document).on('click', '.print-page', function() {
            $('#printableArea').printArea({
                mode: 'iframe',
                popClose: false
            });
        });
        // Auto-load first
        if ($('.btn_view_invoice_detail').length > 0) {
            $('.btn_view_invoice_detail:first').trigger('click');
        }
    </script>
@endpush
