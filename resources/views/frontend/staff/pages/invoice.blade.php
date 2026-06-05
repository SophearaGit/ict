@extends('frontend.staff.layout.master')
@section('page_title', isset($page_title) ? $page_title : 'Page Title Here')
@push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&family=Syne:wght@600;700;800&display=swap');

        .invoice-application {
            border: none;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.07);
        }

        /* ── LEFT PANEL ── */
        .inv-sidebar {
            width: 300px;
            min-width: 300px;
            background: #0f0e17;
            display: flex;
            flex-direction: column;
            height: calc(100vh - 180px);
        }

        .inv-sidebar-header {
            padding: 20px 18px 16px;
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
            background: rgba(21, 2, 166, 0.4);
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

        .inv-list-date {
            font-size: 11px;
            color: rgba(255, 255, 255, 0.3);
            white-space: nowrap;
            flex-shrink: 0;
        }

        .inv-status-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .inv-status-dot.paid {
            background: #22c55e;
        }

        .inv-status-dot.half {
            background: #eab308;
        }

        .inv-status-dot.unpaid {
            background: #ef4444;
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
        .inv-main {
            flex: 1;
            background: #f8f8fb;
            overflow-y: auto;
            height: calc(100vh - 180px);
        }

        .inv-empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100%;
            color: #aaa;
            gap: 12px;
        }

        .inv-empty-state i {
            font-size: 48px;
            opacity: 0.3;
        }

        .inv-empty-state p {
            font-family: 'DM Sans', sans-serif;
            font-size: 14px;
        }

        .inv-loader {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
        }

        /* Mobile toggle */
        .inv-mobile-bar {
            display: none;
            padding: 12px 16px;
            background: #0f0e17;
            gap: 12px;
            align-items: center;
        }

        @media (max-width: 991px) {
            .inv-mobile-bar {
                display: flex;
            }

            .inv-sidebar {
                display: none;
            }

            .inv-main {
                height: calc(100vh - 200px);
            }
        }
    </style>
@endpush
@section('content')
    @include('frontend.staff.pages.partials.breadcrumb')
    <div class="card invoice-application" style="border-radius:16px;">
        {{-- Mobile bar --}}
        <div class="inv-mobile-bar">
            <button class="btn btn-sm btn-outline-light" type="button" data-bs-toggle="offcanvas"
                data-bs-target="#inv-offcanvas">
                <i class="ti ti-menu-2"></i>
            </button>
            <span style="color:rgba(255,255,255,0.5);font-size:13px;font-family:'DM Sans',sans-serif;">Invoices</span>
        </div>
        <div class="d-flex" style="height:calc(100vh - 180px);">
            {{-- ── LEFT SIDEBAR ── --}}
            <div class="inv-sidebar d-none d-lg-flex flex-column">
                <div class="inv-sidebar-header">
                    <form class="inv-search-wrap" method="GET" action="{{ route('staff.invoices') }}">
                        <i class="ti ti-search"></i>
                        <input type="search" name="search" value="{{ request('search') }}"
                            placeholder="Search student or code…">
                    </form>
                </div>
                <ul class="inv-list">
                    @forelse ($invoices as $invoice)
                        @php
                            $statusClass = match ($invoice->payment_status) {
                                'paid' => 'paid',
                                'half_paid' => 'half',
                                default => 'unpaid',
                            };
                        @endphp
                        <li>
                            <a href="javascript:void(0)" class="inv-list-item btn_view_invoice_detail"
                                id="invoice-{{ $invoice->id }}" data-invoice-id="{{ $invoice->id }}">
                                <div class="inv-list-avatar {{ $statusClass }}">
                                    <i class="ti ti-user"></i>
                                </div>
                                <div class="inv-list-info">
                                    <div class="inv-list-name">{{ $invoice->student->name }}</div>
                                    <div class="inv-list-code">{{ $invoice->invoice_code }}</div>
                                </div>
                                <div style="display:flex;flex-direction:column;align-items:flex-end;gap:5px;">
                                    <div class="inv-list-date">{{ $invoice->created_at->format('d M') }}</div>
                                    <div class="inv-status-dot {{ $statusClass }}"></div>
                                </div>
                            </a>
                        </li>
                    @empty
                        <li
                            style="padding:40px 18px;text-align:center;color:rgba(255,255,255,0.3);font-family:'DM Sans',sans-serif;font-size:13px;">
                            No invoices found
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
                <div class="invoiceing-box h-100">
                    <div class="inv-empty-state h-100">
                        <i class="ti ti-file-invoice"></i>
                        <p>Select an invoice to view details</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Mobile offcanvas --}}
    <div class="offcanvas offcanvas-start" tabindex="-1" id="inv-offcanvas" style="background:#0f0e17;width:300px;">
        <div class="offcanvas-header" style="border-bottom:1px solid rgba(255,255,255,0.07);">
            <span style="color:#fff;font-family:'Syne',sans-serif;font-weight:700;font-size:16px;">Invoices</span>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="p-3" style="border-bottom:1px solid rgba(255,255,255,0.07);">
            <form class="inv-search-wrap" method="GET" action="{{ route('staff.invoices') }}">
                <i class="ti ti-search"></i>
                <input type="search" name="search" value="{{ request('search') }}" placeholder="Search…">
            </form>
        </div>
        <ul class="inv-list">
            @forelse ($invoices as $invoice)
                @php
                    $statusClass = match ($invoice->payment_status) {
                        'paid' => 'paid',
                        'half_paid' => 'half',
                        default => 'unpaid',
                    };
                @endphp
                <li>
                    <a href="javascript:void(0)" class="inv-list-item btn_view_invoice_detail"
                        id="invoice-mob-{{ $invoice->id }}" data-invoice-id="{{ $invoice->id }}"
                        data-bs-dismiss="offcanvas">
                        <div class="inv-list-avatar {{ $statusClass }}">
                            <i class="ti ti-user"></i>
                        </div>
                        <div class="inv-list-info">
                            <div class="inv-list-name">{{ $invoice->student->name }}</div>
                            <div class="inv-list-code">{{ $invoice->invoice_code }}</div>
                        </div>
                        <div class="inv-status-dot {{ $statusClass }}"></div>
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
        let loader = `
            <div style="display:flex;align-items:center;justify-content:center;height:100%;">
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
                        <div style="display:flex;align-items:center;justify-content:center;height:100%;color:#aaa;font-family:'DM Sans',sans-serif;">
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
