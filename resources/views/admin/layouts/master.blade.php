<!DOCTYPE html>
<html lang="en">

<head>
    <!--  Title -->
    <title></title>
    <!--  Required Meta Tag -->
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="handheldfriendly" content="true" />
    <meta name="MobileOptimized" content="width" />
    <meta name="description" content="Mordenize" />
    <meta name="author" content="" />
    <meta name="keywords" content="Mordenize" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!--  Favicon -->
    <link rel="shortcut icon" type="image/png" href="" />
    <!-- Owl Carousel  -->
    <link rel="stylesheet" href="/admin/assets/dist/libs/owl.carousel/dist/assets/owl.carousel.min.css">

    <!-- Core Css -->
    <link id="themeColors" rel="stylesheet" href="/admin/assets/dist/css/style.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</head>

<body>
    {{-- @include('admin.layouts.preloader') --}}
    <!--  Body Wrapper -->
    <div class="page-wrapper" id="main-wrapper" data-theme="blue_theme" data-layout="vertical" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed">
        @include('admin.layouts.sidebar')
        <!--  Main wrapper -->
        <div class="body-wrapper">
            @include('admin.layouts.header')
            <div class="container-fluid">
                @yield('content')
            </div>
        </div>
        <div class="dark-transparent sidebartoggler"></div>
        <div class="dark-transparent sidebartoggler"></div>
    </div>
    @include('admin.layouts.mobile-navbar')
    @include('admin.layouts.customizer')
    <!--  Import Js Files -->
    <script src="/admin/assets/dist/libs/jquery/dist/jquery.min.js"></script>
    <script src="/admin/assets/dist/libs/simplebar/dist/simplebar.min.js"></script>
    <script src="/admin/assets/dist/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <!--  core files -->
    <script src="/admin/assets/dist/js/app.min.js"></script>
    <script src="/admin/assets/dist/js/app.init.js"></script>
    <script src="/admin/assets/dist/js/app-style-switcher.js"></script>
    <script src="/admin/assets/dist/js/sidebarmenu.js"></script>
    <script src="/admin/assets/dist/js/custom.js"></script>
    <!--  current page js files -->
    <script src="/admin/assets/dist/libs/owl.carousel/dist/owl.carousel.min.js"></script>
    <script src="/admin/assets/dist/libs/apexcharts/dist/apexcharts.min.js"></script>
    <script src="/admin/assets/dist/js/dashboard.js"></script>
</body>

</html>
