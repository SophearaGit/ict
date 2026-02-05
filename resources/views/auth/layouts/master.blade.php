<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="" />
    <meta name="keywords" content="" />
    <meta name="author" content="Codescandy" />


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

    <link rel="canonical" href="https://geeksui.codescandy.com/geeks/pages/sign-in.html">
    <title>ICT | Sign in</title>
</head>

<body>
    <!-- Page content -->
    <main>
        @yield('content')
        @include('auth.layouts.theme')
    </main>
    <!-- Scripts -->
    <!-- Libs JS -->
    <script src="/frontend/assets/libs/@popperjs/core/dist/umd/popper.min.js"></script>
    <script src="/frontend/assets/libs/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="/frontend/assets/libs/simplebar/dist/simplebar.min.js"></script>

    <!-- Theme JS -->
    <script src="/frontend/assets/js/theme.min.js"></script>

    <script src="/frontend/assets/js/vendors/validation.js"></script>
</body>

</html>
