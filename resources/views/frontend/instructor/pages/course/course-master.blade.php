<!doctype html>
<html lang="en">

<head>
    <link rel="stylesheet" href="/frontend/assets/libs/quill/dist/quill.snow.css ">
    <link rel="stylesheet" href="/frontend/assets/libs/glightbox/dist/css/glightbox.min.css ">
    <link rel="stylesheet" href="/frontend/assets/libs/bs-stepper/dist/css/bs-stepper.min.css ">
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="" />
    <meta name="keywords" content="" />
    <meta name="author" content="Codescandy" />
    <meta name="base_url" content="{{ url('/') }}">
    <meta name="csrf_token" content="{{ csrf_token() }}">

    {{-- jQuery --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    {{-- jQueryUI --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.14.1/jquery-ui.min.js"
        integrity="sha512-MSOo1aY+3pXCOCdGAYoBZ6YGI0aragoQsg1mKKBHXCYPIWxamwOE7Drh+N5CPgGI5SA9IEKJiPjdfqWFWmZtRA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.14.1/themes/base/jquery-ui.min.css"
        integrity="sha512-TFee0335YRJoyiqz8hA8KV3P0tXa5CpRBSoM0Wnkn7JoJx1kaq1yXL/rb8YFpWXkMOjRcv5txv+C6UluttluCQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    {{-- sweetAlert --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.26.19/dist/sweetalert2.all.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.26.19/dist/sweetalert2.min.css"rel="stylesheet">

    {{-- iziToast --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/css/iziToast.min.css"
        integrity="sha512-O03ntXoVqaGUTAeAmvQ2YSzkCvclZEcPQu1eqloPaHfJ5RuNGiS4l+3duaidD801P50J28EHyonCV06CUlTSag=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/js/iziToast.min.js"
        integrity="sha512-Zq9o+E00xhhR/7vJ49mxFNJ0KQw1E1TMWkPTxrWcnpfEFDEXgUiwJHIKit93EW/XxE31HSI5GEOW06G6BF1AtA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    {{-- lfm --}}
    <script src="/vendor/laravel-filemanager/js/stand-alone-button.js"></script>

    {{-- base_url --}}
    <script>
        const base_url = $('meta[name="base_url"]').attr('content');
        const csrf_token = $('meta[name="csrf_token"]').attr('content');
    </script>

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


    <link rel="canonical" href="https://geeksui.codescandy.com/geeks/pages/add-course.html ">
    <link href="/frontend/assets/libs/@yaireo/tagify/dist/tagify.css" rel="stylesheet ">
    <link href="/frontend/assets/libs/dragula/dist/dragula.min.css" rel="stylesheet ">
    <title>
        @yield('page_title')
    </title>
    @stack('styles')
</head>

<body>
    <!-- Navbar -->
    @include('frontend.layouts.navbar')
    <!-- Page header-->
    <main>
        <section class="py-4 py-lg-6 bg-primary">
            <div class="container">
                <div class="row">
                    <div class="offset-lg-1 col-lg-10 col-md-12 col-12">
                        <div class="d-lg-flex align-items-center justify-content-between">
                            <!-- Content -->
                            <div class="mb-4 mb-lg-0">
                                <h1 class="text-white mb-1">
                                    {{ isset($breadcrumb_title) ? $breadcrumb_title : 'Breadcrumb Title' }}
                                </h1>
                                <p class="mb-0 text-white lead">
                                    {{ isset($breadcrumb_sub_title) ? $breadcrumb_sub_title : 'Page description goes here' }}
                                </p>
                            </div>
                            <div>
                                <a href="{{ route('instructor.courses.index') }}" class="btn btn-white">Back to
                                    Course</a>
                                <a href="#" class="btn btn-dark step-trigger"
                                    data-step="{{ request()->step }}">Save</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Page Content -->
        <section class="pb-8">
            <div class="container">
                <div id="courseForm" class="bs-stepper">
                    <div class="row">
                        <div class="offset-lg-1 col-lg-10 col-md-12 col-12">
                            <!-- Stepper Button -->
                            <div class="bs-stepper-header shadow-sm" role="tablist">
                                <a href=""
                                    class="step-trigger {{ request()?->step == '1' || Route::is('instructor.courses.create') ? 'active' : '' }} "
                                    data-step="1">
                                    <span class="bs-stepper-circle">1</span>
                                    <span class="bs-stepper-label ">Basic Information</span>
                                </a>
                                <div class="bs-stepper-line"></div>
                                <a href="" class="step-trigger {{ request()?->step == '2' ? 'active' : '' }} "
                                    data-step="2">
                                    <span class="bs-stepper-circle">2</span>
                                    <span class="bs-stepper-label ">More Information</span>
                                </a>
                                <div class="bs-stepper-line"></div>
                                <a href="" class="step-trigger {{ request()?->step == '3' ? 'active' : '' }} "
                                    data-step="3">
                                    <span class="bs-stepper-circle">3</span>
                                    <span class="bs-stepper-label ">Course Content</span>
                                </a>
                                <div class="bs-stepper-line"></div>
                                <a href="" class="step-trigger {{ request()?->step == '4' ? 'active' : '' }} "
                                    data-step="4">
                                    <span class="bs-stepper-circle">4</span>
                                    <span class="bs-stepper-label ">Finish</span>
                                </a>
                            </div>
                            <!-- Stepper content -->
                            <div class="bs-stepper-content mt-5">
                                @yield('course-content')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    @include('frontend.layouts.footer')

    <!-- Scroll top -->
    <div class="btn-scroll-top">
        <svg class="progress-square svg-content" width="100%" height="100%" viewBox="0 0 40 40">
            <path
                d="M8 1H32C35.866 1 39 4.13401 39 8V32C39 35.866 35.866 39 32 39H8C4.13401 39 1 35.866 1 32V8C1 4.13401 4.13401 1 8 1Z" />
        </svg>
    </div>

    <!-- Scripts -->

    <script src="/frontend/assets/libs/file-upload-with-preview/dist/file-upload-with-preview.iife.js"></script>
    <script src="/frontend/assets/libs/@yaireo/tagify/dist/tagify.min.js"></script>

    <!-- Scripts -->
    <!-- Libs JS -->
    <script src="/frontend/assets/libs/@popperjs/core/dist/umd/popper.min.js"></script>
    <script src="/frontend/assets/libs/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="/frontend/assets/libs/simplebar/dist/simplebar.min.js"></script>

    <!-- Theme JS -->
    <script src="/frontend/assets/js/theme.min.js"></script>


    <script src="/frontend/assets/libs/quill/dist/quill.min.js"></script>
    <script src="/frontend/assets/libs/dragula/dist/dragula.min.js"></script>

    <script src="/frontend/assets/libs/bs-stepper/dist/js/bs-stepper.min.js"></script>
    <script src="/frontend/assets/js/vendors/beStepper.js"></script>
    <script src="/frontend/assets/js/vendors/customDragula.js"></script>
    <script src="/frontend/assets/js/vendors/editor.js"></script>

    <script src="/frontend/assets/js/vendors/file-upload.js"></script>
    <script src="/frontend/assets/libs/glightbox/dist/js/glightbox.min.js"></script>
    <script src="/frontend/assets/js/vendors/glight.js"></script>


    @stack('scripts')

    <script>
        $('#lfm').filemanager('file');

        // working on step tab trigger
        $('.step-trigger').on('click', function(e) {
            e.preventDefault();
            let step = $(this).data('step');
            $('.course_form').find('input[name="next_step"]').val(step);
            $('.course_form').trigger('submit');
        });

        //  working on previous button
        $('.previous_btn').on('click', function(e) {
            e.preventDefault();
            let step = $(this).data('step') - 1;
            $('.course_form').find('input[name="next_step"]').val(step);
            $('.course_form').trigger('submit');
        });
    </script>

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
