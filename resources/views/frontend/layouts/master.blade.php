<!doctype html>
<html lang="en">

<head>
    <link rel="stylesheet" href="/frontend/assets/libs/glightbox/dist/css/glightbox.min.css" />
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="" />
    <meta name="keywords" content="" />
    <meta name="author" content="Codescandy" />

    <!-- Favicon icon-->
    <link rel="shortcut icon" type="image/x-icon" href="" />

    {{-- iziToast --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/css/iziToast.min.css"
        integrity="sha512-O03ntXoVqaGUTAeAmvQ2YSzkCvclZEcPQu1eqloPaHfJ5RuNGiS4l+3duaidD801P50J28EHyonCV06CUlTSag=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/js/iziToast.min.js"
        integrity="sha512-Zq9o+E00xhhR/7vJ49mxFNJ0KQw1E1TMWkPTxrWcnpfEFDEXgUiwJHIKit93EW/XxE31HSI5GEOW06G6BF1AtA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <!-- darkmode js -->
    <script src="/frontend/assets/js/vendors/darkMode.js"></script>

    <!-- Libs CSS -->
    <link href="/frontend/assets/fonts/feather/feather.css" rel="stylesheet" />
    <link href="/frontend/assets/libs/bootstrap-icons/font/bootstrap-icons.min.css" rel="stylesheet" />
    <link href="/frontend/assets/libs/simplebar/dist/simplebar.min.css" rel="stylesheet" />

    <!-- Theme CSS -->
    <link rel="stylesheet" href="/frontend/assets/css/theme.min.css">

    <link rel="canonical" href="https://geeksui.codescandy.com/geeks/pages/landings/home-academy.html" />
    <link href="/frontend/assets/libs/tiny-slider/dist/tiny-slider.css" rel="stylesheet" />
    <title>@yield('page_title')</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    @stack('styles')
</head>

<body class="bg-white">
    @include('frontend.layouts.navbar')
    <main>
        @yield('content')
    </main>
    @include('frontend.layouts.footer')
    <!-- Scroll top -->
    <div class="btn-scroll-top">
        <svg class="progress-square svg-content" width="100%" height="100%" viewBox="0 0 40 40">
            <path
                d="M8 1H32C35.866 1 39 4.13401 39 8V32C39 35.866 35.866 39 32 39H8C4.13401 39 1 35.866 1 32V8C1 4.13401 4.13401 1 8 1Z" />
        </svg>
    </div>

    <!-- Scripts -->
    <!-- Libs JS -->
    <script src="/frontend/assets/libs/@popperjs/core/dist/umd/popper.min.js"></script>
    <script src="/frontend/assets/libs/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="/frontend/assets/libs/simplebar/dist/simplebar.min.js"></script>

    <!-- Theme JS -->
    <script src="/frontend/assets/js/theme.min.js"></script>


    <script src="/frontend/assets/libs/tiny-slider/dist/min/tiny-slider.js"></script>
    <script src="/frontend/assets/js/vendors/tnsSlider.js"></script>
    <script src="/frontend/assets/libs/glightbox/dist/js/glightbox.min.js"></script>
    <script src="/frontend/assets/js/vendors/glight.js"></script>

    @stack('scripts')

    @if ($errors->any())
        <script>
            @foreach ($errors->all() as $error)
                iziToast.error({
                    title: '',
                    message: '{{ $error }}',
                    position: 'bottomRight'
                });
            @endforeach
        </script>
    @endif

    @if (session()->get('success'))
        <script>
            iziToast.success({
                title: '',
                message: '{{ session()->get('success') }}',
                position: 'bottomRight'
            });
        </script>
    @endif

</body>

</html>
