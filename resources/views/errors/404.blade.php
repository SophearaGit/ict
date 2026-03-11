@extends('frontend.layouts.master')
@section('page_title', isset($page_title) ? $page_title : 'Page Title Here')
@section('content')
    <section class="py-lg-4 py-5">
        <!-- container -->
        <div class="container my-lg-8">
            <!-- row -->
            <div class="row align-items-center">
                <!-- col -->
                <div class="col-lg-12 mb-6 mb-lg-0">
                    <div class="rounded-4 overflow-hidden shadow-sm">
                        <!-- Crossfade -->
                        <div class="row align-items-center justify-content-center g-0 h-lg-100 py-10">
                            <!-- Docs -->
                            <div class="offset-xl-1 col-xl-4 col-lg-6 col-md-12 col-12 text-center text-lg-start">
                                <h1 class="display-1 mb-3">
                                    404
                                </h1>
                                <p class="mb-5 lead px-4 px-md-0">
                                    Oops! The page you are looking for does not exist. It might have been moved or deleted.
                                    <a href="#" class="btn-link">Contact us</a>
                                </p>
                                <a href="{{ route('home') }}" class="btn btn-primary me-2">Back to Safety</a>
                            </div>
                            <!-- img -->
                            <div class="offset-xl-1 col-xl-6 col-lg-6 col-md-12 col-12 mt-8 mt-lg-0">
                                <img src="{{ asset('/frontend/assets/images/error/404-error-img.svg') }}" alt="error"
                                    class="w-100" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
