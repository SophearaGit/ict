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
                                </form>
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
