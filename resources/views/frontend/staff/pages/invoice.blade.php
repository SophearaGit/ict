@extends('frontend.staff.layout.master')
@section('page_title', isset($page_title) ? $page_title : 'Page Title Here')
@section('content')
    @include('frontend.staff.pages.partials.breadcrumb')
    <div class="card overflow-hidden invoice-application">

        <div class="d-flex align-items-center justify-content-between gap-3 m-3 d-lg-none">
            <button class="btn btn-primary d-flex" type="button" data-bs-toggle="offcanvas" data-bs-target="#chat-sidebar"
                aria-controls="chat-sidebar">
                <i class="ti ti-menu-2 fs-5"></i>
            </button>
            <form class="position-relative w-100">
                <input type="text" class="form-control search-chat py-2 ps-5" id="text-srh" placeholder="Search Contact">
                <i class="ti ti-search position-absolute top-50 start-0 translate-middle-y fs-6 text-dark ms-3"></i>
            </form>
        </div>

        <div class="d-flex">
            <div class="w-25 d-none d-lg-block border-end user-chat-box">
                <div class="p-3 border-bottom">
                    <form class="position-relative">
                        <input type="search" class="form-control search-invoice ps-5" id="text-srh"
                            placeholder="Search Invoice">
                        <i class="ti ti-search position-absolute top-50 start-0 translate-middle-y fs-6 text-dark ms-3"></i>
                    </form>
                </div>
                <div class="app-invoice">
                    <ul class="overflow-auto invoice-users" style="height: calc(100vh - 262px)" data-simplebar="init">
                        <div class="simplebar-wrapper" style="margin: 0px;">
                            <div class="simplebar-height-auto-observer-wrapper">
                                <div class="simplebar-height-auto-observer"></div>
                            </div>
                            <div class="simplebar-mask">
                                <div class="simplebar-offset" style="right: 0px; bottom: 0px;">
                                    <div class="simplebar-content-wrapper" tabindex="0" role="region"
                                        aria-label="scrollable content" style="height: 100%; overflow: hidden;">
                                        <div class="simplebar-content" style="padding: 0px;">
                                            @forelse ($invoices as $invoice)
                                                <li>
                                                    <a href="javascript:void(0)"
                                                        class="p-3 bg-hover-light-black border-bottom d-flex align-items-start invoice-user listing-user btn_view_invoice_detail"
                                                        id="invoice-{{ $invoice->id }}"
                                                        data-invoice-id="{{ $invoice->id }}">
                                                        <div
                                                            class="btn
                                                            {{ $invoice->payment_status == 'paid' ? 'btn-success' : ($invoice->payment_status == 'partial' ? 'btn-warning' : 'btn-danger') }}
                                                            round rounded-circle d-flex align-items-center justify-content-center">
                                                            <i class="ti ti-user fs-6"></i>
                                                        </div>
                                                        <div class="ms-3 d-inline-block w-75">
                                                            <h6 class="mb-0 invoice-customer">{{ $invoice->student->name }}
                                                            </h6>
                                                            <span
                                                                class="fs-3 invoice-id text-truncate text-body-color d-block w-85">Code:
                                                                {{ $invoice->invoice_code }}</span>
                                                            <span
                                                                class="fs-3 invoice-date text-nowrap text-body-color d-block">
                                                                {{ $invoice->created_at->format('d M Y') }}</span>
                                                            </span>
                                                        </div>
                                                    </a>
                                                </li>
                                            @empty
                                            @endforelse
                                            <li></li>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="simplebar-placeholder" style="width: auto; height: 457px;"></div>
                        </div>
                        <div class="simplebar-track simplebar-horizontal" style="visibility: hidden;">
                            <div class="simplebar-scrollbar" style="width: 0px; display: none;"></div>
                        </div>
                        <div class="simplebar-track simplebar-vertical" style="visibility: hidden;">
                            <div class="simplebar-scrollbar" style="height: 0px; display: none;"></div>
                        </div>
                    </ul>
                </div>
            </div>

            <div class="w-75 w-xs-100 chat-container">
                <div class="invoice-inner-part h-100">
                    <div class="invoiceing-box">

                    </div>
                </div>
            </div>

            <div class="offcanvas offcanvas-start user-chat-box" tabindex="-1" id="chat-sidebar"
                aria-labelledby="offcanvasExampleLabel">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="offcanvasExampleLabel">
                        Invoice
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="p-3 border-bottom">
                    <form class="position-relative">
                        <input type="search" class="form-control search-invoice ps-5" id="text-srh"
                            placeholder="Search Invoice">
                        <i class="ti ti-search position-absolute top-50 start-0 translate-middle-y fs-6 text-dark ms-3"></i>
                    </form>
                </div>
                <div class="app-invoice overflow-auto">
                    <ul class="invoice-users">
                        <li>
                            <a href="javascript:void(0)"
                                class="p-3 bg-hover-light-black border-bottom d-flex align-items-start invoice-user listing-user bg-light"
                                id="invoice-123" data-invoice-id="123">
                                <div
                                    class="btn btn-primary round rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="ti ti-user fs-6"></i>
                                </div>
                                <div class="ms-3 d-inline-block w-75">
                                    <h6 class="mb-0 invoice-customer">James Anderson</h6>

                                    <span class="fs-3 invoice-id text-truncate text-body-color d-block w-85">Id:
                                        #123</span>
                                    <span class="fs-3 invoice-date text-nowrap text-body-color d-block">9 Fab 2020</span>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)"
                                class="p-3 bg-hover-light-black border-bottom d-flex align-items-start invoice-user listing-user"
                                id="invoice-124" data-invoice-id="124">
                                <div
                                    class="btn btn-danger round rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="ti ti-user fs-6"></i>
                                </div>
                                <div class="ms-3 d-inline-block w-75">
                                    <h6 class="mb-0 invoice-customer">Bianca Doe</h6>
                                    <span class="fs-3 invoice-id text-truncate text-body-color d-block w-85">#124</span>
                                    <span class="fs-3 invoice-date text-nowrap text-body-color d-block">9 Fab 2020</span>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)"
                                class="p-3 bg-hover-light-black border-bottom d-flex align-items-start invoice-user listing-user"
                                id="invoice-125" data-invoice-id="125">
                                <div
                                    class="btn btn-info round rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="ti ti-user fs-6"></i>
                                </div>
                                <div class="ms-3 d-inline-block w-75">
                                    <h6 class="mb-0 invoice-customer">Angelina Rhodes</h6>
                                    <span class="fs-3 invoice-id text-truncate text-body-color d-block w-85">#125</span>
                                    <span class="fs-3 invoice-date text-nowrap text-body-color d-block">9 Fab 2020</span>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)"
                                class="p-3 bg-hover-light-black border-bottom d-flex align-items-start invoice-user listing-user"
                                id="invoice-126" data-invoice-id="126">
                                <div
                                    class="btn btn-warning round rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="ti ti-user fs-6"></i>
                                </div>
                                <div class="ms-3 d-inline-block w-75">
                                    <h6 class="mb-0 invoice-customer">Samuel Smith</h6>
                                    <span class="fs-3 invoice-id text-truncate text-body-color d-block w-85">#126</span>
                                    <span class="fs-3 invoice-date text-nowrap text-body-color d-block">9 Fab 2020</span>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)"
                                class="p-3 bg-hover-light-black border-bottom d-flex align-items-start invoice-user listing-user"
                                id="invoice-127" data-invoice-id="127">
                                <div
                                    class="btn btn-primary round rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="ti ti-user fs-6"></i>
                                </div>
                                <div class="ms-3 d-inline-block w-75">
                                    <h6 class="mb-0 invoice-customer">Gabriel Jobs</h6>
                                    <span class="fs-3 invoice-id text-truncate text-body-color d-block w-85">#127</span>
                                    <span class="fs-3 invoice-date text-nowrap text-body-color d-block">9 Fab 2020</span>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('scripts')
    <!-- ---------------------------------------------- -->
    <!-- current page js files -->
    <!-- ---------------------------------------------- -->
    <script src="/admin/assets/dist/js/apps/jquery.PrintArea.js"></script>
    {{-- <script src="/admin/assets/dist/js/apps/invoice.js"></script> --}}

    <script>
        $(function() {

            // Search invoice
            $(".search-invoice").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $(".invoice-users li").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });
            });

            // Invoice switch logic
            var $btns = $(".listing-user").click(function() {

                var getDataInvoiceAttr = $(this).attr("data-invoice-id");
                var $el = $("." + this.id).show();

                $("#custom-invoice > div").not($el).hide();

                $(".invoice-number").text("#" + getDataInvoiceAttr);

                $btns.removeClass("bg-light");
                $(this).addClass("bg-light");

            });

            // ✅ Trigger first listing-user only if it exists
            if ($(".listing-user").length > 0) {
                $(".listing-user:first").trigger("click");
            }

        });

        // ✅ Dynamic Print
        $(document).on("click", ".print-page", function() {

            var options = {
                mode: "iframe",
                popClose: false
            };

            var visibleInvoice = $("#custom-invoice > div:visible");

            if (visibleInvoice.length) {
                visibleInvoice.printArea(options);
            } else {
                alert("No invoice selected to print.");
            }
        });
    </script>


    <script>
        let loader = `
            <div class="invoice-header d-flex align-items-center border-bottom p-3">
                <h4 class="font-medium text-uppercase mb-0">
                     <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </h4>
                <div class="ms-auto">

                </div>
            </div>
            <div class="p-3">
                <div class="invoice-123" id="printableArea" style="display: block;">
                    <div class="row pt-3">
                        <div class="d-flex align-items-center justify-content-center w-100 py-5">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;

        $('.btn_view_invoice_detail').on('click', function(e) {
            e.preventDefault();

            let invoice_id = $(this).data('invoice-id');

            $.ajax({
                method: 'GET',
                url: base_url + `/staff/invoice-detail/${invoice_id}`,
                data: {},
                beforeSend: function() {
                    $('.invoiceing-box').html(loader);
                },
                success: function(data) {
                    $('.invoiceing-box').html(data);
                },
                error: function(xhr, status, error) {

                },
            })
        })
    </script>
@endpush
