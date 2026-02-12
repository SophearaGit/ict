<!doctype html>
<html lang="en">

<head>
    <link rel="stylesheet" href="/frontend/assets/libs/flatpickr/dist/flatpickr.min.css">
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="" />
    <meta name="keywords" content="" />
    <meta name="author" content="Codescandy" />

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <!-- Favicon icon-->
    <link rel="shortcut icon" type="image/x-icon" href="/frontend/assets/images/favicon/favicon.ico" />

    <!-- darkmode js -->
    <script src="/frontend/assets/js/vendors/darkMode.js"></script>

    <!-- Libs CSS -->
    <link href="/frontend/assets/fonts/feather/feather.css" rel="stylesheet" />
    <link href="/frontend/assets/libs/bootstrap-icons/font/bootstrap-icons.min.css" rel="stylesheet" />
    <link href="/frontend/assets/libs/simplebar/dist/simplebar.min.css" rel="stylesheet" />

    <!-- Theme CSS -->
    <link rel="stylesheet" href="/frontend/assets/css/theme.min.css">

    <link rel="canonical" href="https://geeksui.codescandy.com/geeks/pages/dashboard/admin-dashboard.html">

    <title>
        @yield('page_title')
    </title>
    @stack('scripts')
</head>

<body>
    <!-- Wrapper -->
    <div id="db-wrapper">
        <!-- navbar vertical -->
        @include('admin.layouts.partials.sidebar')
        <!-- Page Content -->
        <main id="page-content">
            @include('admin.layouts.partials.header')
            <!-- Page Header -->
            <!-- Container fluid -->
            <section class="container-fluid p-4">
                @yield('content')
            </section>
        </main>
    </div>

    <!-- Script -->

    <!-- Libs JS -->
    <script src="/frontend/assets/libs/@popperjs/core/dist/umd/popper.min.js"></script>
    <script src="/frontend/assets/libs/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="/frontend/assets/libs/simplebar/dist/simplebar.min.js"></script>

    <!-- Theme JS -->
    <script src="/frontend/assets/js/theme.min.js"></script>

    <script src="/frontend/assets/libs/apexcharts/dist/apexcharts.min.js"></script>
    <script src="/frontend/assets/js/vendors/chart.js"></script>
    <script src="/frontend/assets/libs/flatpickr/dist/flatpickr.min.js"></script>
    <script src="/frontend/assets/js/vendors/flatpickr.js"></script>
    @stack('scripts')
</body>

</html>
