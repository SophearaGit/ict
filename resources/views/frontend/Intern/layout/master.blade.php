<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>
        @yield('page_title', 'ICT LMS - Intern Dashboard')
    </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
    <meta content="Coderthemes" name="author" />

    <!-- App favicon -->
    <link rel="shortcut icon" href="/frontend/intern/assets/images/favicon.ico">

    <!-- Theme Config Js -->
    <script src="/frontend/intern/assets/js/config.js"></script>

    <!-- Vendor css -->
    <link href="/frontend/intern/assets/css/vendor.min.css" rel="stylesheet" type="text/css" />

    <!-- App css -->
    <link href="/frontend/intern/assets/css/app.min.css" rel="stylesheet" type="text/css" id="app-style" />

    <!-- Icons css -->
    <link href="/frontend/intern/assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    @stack('styles')
    <style>
        .sidenav-menu {
            display: flex;
            flex-direction: column;
        }

        .sidenav-menu [data-simplebar] {
            flex: 1 1 auto;
            min-height: 0;
            /* required for simplebar to shrink properly inside flex */
        }

        .sidenav-bottom {
            flex-shrink: 0;
            border-top: 1px dashed var(--bs-border-color);
            padding: 8px 0;
        }

        .sidenav-bottom .side-nav {
            margin-bottom: 0;
        }
    </style>
</head>

<body>

    <!-- Begin page -->
    <div class="wrapper">
        @include('frontend.Intern.layout.partials.sidenav-menu')
        @include('frontend.Intern.layout.partials.app-topbar')
        @include('frontend.Intern.layout.partials.search-modal')

        <!-- ============================================================== -->

        <!-- Start Page Content here -->

        <!-- ============================================================== -->
        <div class="page-content">
            <div class="page-container">
                @yield('content')
            </div>

            <!-- container -->
            @include('frontend.Intern.layout.partials.footer')
        </div>

        <!-- ============================================================== -->

        <!-- End Page content -->

        <!-- ============================================================== -->
    </div>

    <!-- END wrapper -->
    @include('frontend.Intern.layout.partials.theme-settings-offcanvas')

    <!-- Vendor js -->
    <script src="/frontend/intern/assets/js/vendor.min.js"></script>

    <!-- App js -->
    <script src="/frontend/intern/assets/js/app.js"></script>

    <!-- Apex Chart js -->
    <script src="assets/vendor/apexcharts/apexcharts.min.js"></script>

    <!-- Projects Analytics Dashboard App js -->
    <script src="/frontend/intern/assets/js/pages/dashboard-sales.js"></script>
    @stack('scripts')
</body>

</html>
