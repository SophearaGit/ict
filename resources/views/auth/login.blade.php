@extends('auth.layouts.master')
@section('page_title', isset($page_title) ? $page_title : 'ICT Center | Login')
@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tabler-icons/3.35.0/tabler-icons.min.css" />
    <style>
        body {
            background: linear-gradient(135deg, #EEF2FF 0%, #F9FAFB 100%);
        }

        .back-home-btn {
            transition: .3s;
            font-size: 14px;
            font-weight: 600;
        }

        .back-home-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
        }

        .login-card {
            border: none;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
        }

        .login-left {
            background: linear-gradient(180deg, #4F46E5 0%, #7C3AED 100%);
            color: white;
            position: relative;
        }

        .login-left::before {
            content: '';
            position: absolute;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 50%;
            top: -120px;
            right: -120px;
        }

        .login-left::after {
            content: '';
            position: absolute;
            width: 220px;
            height: 220px;
            background: rgba(255, 255, 255, 0.06);
            border-radius: 50%;
            bottom: -100px;
            left: -100px;
        }

        .login-form-control {
            height: 52px;
            border-radius: 14px;
            border: 1px solid #E5E7EB;
            padding-left: 45px;
        }

        .login-form-control:focus {
            border-color: #4F46E5;
            box-shadow: 0 0 0 0.15rem rgba(79, 70, 229, 0.15);
        }

        .input-icon {
            position: absolute;
            top: 50%;
            left: 16px;
            transform: translateY(-50%);
            color: #9CA3AF;
            font-size: 20px;
        }

        .toggle-password {
            position: absolute;
            top: 50%;
            right: 16px;
            transform: translateY(-50%);
            cursor: pointer;
            color: #9CA3AF;
            font-size: 20px;
        }

        .login-btn {
            height: 52px;
            border-radius: 14px;
            background: linear-gradient(90deg, #4F46E5 0%, #7C3AED 100%);
            border: none;
            font-weight: 600;
            transition: 0.3s;
        }

        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(79, 70, 229, 0.25);
        }

        .login-btn-google {
            height: 52px;
            border-radius: 14px;
            font-weight: 600;
            transition: 0.3s;
        }

        .login-btn-google:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
        }

        .feature-box {
            background: rgba(255, 255, 255, 0.08);
            border-radius: 16px;
            padding: 14px;
        }

        .object-fit-cover {
            object-fit: cover;
        }
    </style>
@endpush
@section('content')
    <section class="container min-vh-100 d-flex align-items-center py-5">
        <div class="row justify-content-center w-100">
            <div class="col-xl-10">
                <div class="card login-card">
                    <div class="row g-0">
                        {{-- LEFT SIDE --}}
                        <div class="col-lg-5 d-none d-lg-flex flex-column justify-content-between p-5 login-left">
                            <div style="position: relative; z-index: 2;">
                                <img src="{{ asset('/frontend/assets/ictImg/logo/ictLogo.jpg') }}" width="80"
                                    height="80" class="rounded-circle mb-4 object-fit-cover" alt="logo">
                                <h2 class="fw-bold mb-3 text-white">
                                    Welcome to ICT Center!
                                </h2>
                                <p class="opacity-75 fs-4">
                                    Empowering students with modern technology education and practical digital skills.
                                </p>
                            </div>
                            <div style="position: relative; z-index: 2;">
                                <div class="feature-box d-flex align-items-center gap-3 mb-3">
                                    <i class="ti ti-book fs-1"></i>
                                    <div>
                                        <h6 class="mb-1 fw-semibold text-white">
                                            Professional Courses
                                        </h6>
                                        <small class="opacity-75 ">
                                            Learn from industry experts
                                        </small>
                                    </div>
                                </div>
                                <div class="feature-box d-flex align-items-center gap-3 mb-3">
                                    <i class="ti ti-users fs-1"></i>
                                    <div>
                                        <h6 class="mb-1 fw-semibold  text-white">
                                            Student Community
                                        </h6>
                                        <small class="opacity-75">
                                            Grow with talented learners
                                        </small>
                                    </div>
                                </div>
                                <div class="feature-box d-flex align-items-center gap-3">
                                    <i class="ti ti-certificate fs-1"></i>
                                    <div>
                                        <h6 class="mb-1 fw-semibold  text-white">
                                            Certification
                                        </h6>
                                        <small class="opacity-75">
                                            Build your future career
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- RIGHT SIDE --}}
                        <div class="col-lg-7 bg-white">
                            <div class="p-4 p-lg-5">
                                <div class="d-flex justify-content-between align-items-start mb-5 flex-wrap gap-3">
                                    <div>
                                        <h2 class="fw-bold mb-2">
                                            Login
                                        </h2>
                                        <p class="text-muted mb-0">
                                            Access your courses, reports, and learning dashboard.
                                        </p>
                                    </div>
                                    {{-- <a href="{{ route('home') }}"
                class="btn btn-light border rounded-pill px-3 py-2 d-inline-flex align-items-center gap-2 back-home-btn">
                <i class="ti ti-home"></i>
                <span>
                                            Homepage
                                        </span>
                </a> --}}
                                </div>
                                <x-auth-session-status class="mb-4" :status="session('status')" />
                                <form method="POST" action="{{ route('login') }}">
                                    @csrf
                                    {{-- EMAIL --}}
                                    <div class="mb-4">
                                        <label for="email" class="form-label fw-semibold">
                                            Email Address <span class="text-danger">*</span>
                                        </label>
                                        <div class="position-relative">
                                            <i class="ti ti-mail input-icon"></i>
                                            <input type="email" id="email" class="form-control login-form-control"
                                                name="email" placeholder="Enter your email" value="{{ old('email') }}"
                                                required>
                                        </div>
                                        <x-input-error :messages="$errors->get('email')" class="text-danger mt-2" />
                                    </div>
                                    {{-- PASSWORD --}}
                                    <div class="mb-4">
                                        <label for="password" class="form-label fw-semibold">
                                            Password <span class="text-danger">*</span>
                                        </label>
                                        <div class="position-relative">
                                            <i class="ti ti-lock input-icon"></i>
                                            <input type="password" id="password"
                                                class="form-control login-form-control pe-5" name="password"
                                                placeholder="Enter your password" required>
                                            <i class="ti ti-eye toggle-password" id="togglePassword"></i>
                                        </div>
                                        <x-input-error :messages="$errors->get('password')" class="text-danger mt-2" />
                                    </div>
                                    {{-- REMEMBER --}}
                                    <div class="d-flex justify-content-between align-items-center mb-4">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="rememberme" name="remember">
                                            <label class="form-check-label" for="rememberme">
                                                Remember me
                                            </label>
                                        </div>
                                        <a href="{{ route('password.request') }}" class="text-decoration-none fw-semibold">
                                            Forgot Password?
                                        </a>
                                    </div>
                                    {{-- BUTTON --}}
                                    <div class="d-grid mb-4">
                                        <button type="submit" class="btn login-btn text-white">
                                            Sign In
                                        </button>
                                    </div>
                                </form>
                                {{-- DIVIDER --}}
                                <div class="d-flex align-items-center gap-3 mb-4">
                                    <hr class="flex-grow-1 m-0">
                                    <span class="text-muted small">or</span>
                                    <hr class="flex-grow-1 m-0">
                                </div>
                                {{-- GOOGLE — also how a new student registers; there's no
                                     separate signup form, first sign-in creates the account. --}}
                                <div class="d-grid mb-2">
                                    <a href="{{ route('auth.google.redirect') }}"
                                        class="btn btn-light border login-btn-google d-flex align-items-center justify-content-center gap-2">
                                        <svg width="18" height="18" viewBox="0 0 18 18" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                            <path fill="#4285F4" d="M17.64 9.2c0-.637-.057-1.251-.164-1.84H9v3.481h4.844c-.209 1.125-.843 2.078-1.796 2.717v2.258h2.908c1.702-1.567 2.684-3.874 2.684-6.615z" />
                                            <path fill="#34A853" d="M9 18c2.43 0 4.467-.806 5.956-2.18l-2.908-2.259c-.806.54-1.837.86-3.048.86-2.344 0-4.328-1.584-5.036-3.711H.957v2.332C2.438 15.983 5.482 18 9 18z" />
                                            <path fill="#FBBC05" d="M3.964 10.71A5.41 5.41 0 0 1 3.682 9c0-.593.102-1.17.282-1.71V4.958H.957A8.996 8.996 0 0 0 0 9c0 1.452.348 2.827.957 4.042l3.007-2.332z" />
                                            <path fill="#EA4335" d="M9 3.58c1.321 0 2.508.454 3.44 1.345l2.582-2.581C13.463.891 11.426 0 9 0 5.482 0 2.438 2.017.957 4.958L3.964 7.29C4.672 5.163 6.656 3.58 9 3.58z" />
                                        </svg>
                                        <span class="fw-semibold">Continue with Google</span>
                                    </a>
                                </div>
                                <p class="text-center mb-4">
                                    <span class="text-muted">New here? Continuing with Google above creates your account automatically, or</span>
                                    <a href="{{ route('register') }}" class="fw-semibold text-decoration-none">
                                        learn more
                                    </a>
                                </p>
                                {{-- REGISTER --}}
                                <div class="text-center">
                                    <a href="{{ route('home') }}"
                                        class="btn btn-light border rounded-pill px-3 py-2 d-inline-flex align-items-center gap-2 back-home-btn">
                                        <i class="ti ti-home"></i>
                                        <span>
                                            Homepage
                                        </span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('scripts')
    <script>
        $('#togglePassword').on('click', function() {
            let passwordInput = $('#password');
            let type = passwordInput.attr('type') === 'password' ?
                'text' :
                'password';
            passwordInput.attr('type', type);
            $(this).toggleClass('ti-eye');
            $(this).toggleClass('ti-eye-off');
        });
    </script>
@endpush
