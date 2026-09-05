@extends('auth.layouts.master')
@section('page_title', isset($page_title) ? $page_title : 'ICT Center | Register')
@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tabler-icons/3.35.0/tabler-icons.min.css" />
    <style>
        body {
            background: linear-gradient(135deg, #EEF2FF 0%, #F9FAFB 100%);
        }

        .register-card {
            border: none;
            border-radius: 22px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
        }

        .register-left {
            background: linear-gradient(180deg, #4F46E5 0%, #7C3AED 100%);
            color: white;
            position: relative;
            padding: 2.5rem !important;
        }

        .register-left::before {
            content: '';
            position: absolute;
            width: 240px;
            height: 240px;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 50%;
            top: -100px;
            right: -100px;
        }

        .register-left::after {
            content: '';
            position: absolute;
            width: 180px;
            height: 180px;
            background: rgba(255, 255, 255, 0.06);
            border-radius: 50%;
            bottom: -80px;
            left: -80px;
        }

        .feature-box {
            background: rgba(255, 255, 255, 0.08);
            border-radius: 16px;
            padding: 14px;
            backdrop-filter: blur(10px);
        }

        .feature-box i {
            font-size: 32px !important;
        }

        .feature-box h6 {
            font-size: 15px;
        }

        .feature-box small {
            font-size: 13px;
        }

        .object-fit-cover {
            object-fit: cover;
        }

        .col-lg-7.bg-white {
            display: flex;
            align-items: center;
        }

        .col-lg-7.bg-white .p-lg-5 {
            width: 100%;
        }

        .register-form-control {
            height: 50px;
            border-radius: 14px;
            border: 1px solid #E5E7EB;
            padding-left: 46px;
            font-size: 14px;
            transition: .3s;
        }

        .register-form-control::placeholder {
            color: #94A3B8;
        }

        .register-form-control:focus {
            border-color: #7C3AED;
            box-shadow: 0 0 0 0.15rem rgba(124, 58, 237, 0.15);
        }

        .input-icon {
            position: absolute;
            top: 50%;
            left: 16px;
            transform: translateY(-50%);
            color: #9CA3AF;
            font-size: 18px;
        }

        .toggle-password {
            position: absolute;
            top: 50%;
            right: 16px;
            transform: translateY(-50%);
            cursor: pointer;
            color: #9CA3AF;
            font-size: 18px;
        }

        .register-btn {
            height: 50px;
            border-radius: 14px;
            background: linear-gradient(90deg, #4F46E5 0%, #7C3AED 100%);
            border: none;
            font-weight: 600;
            font-size: 15px;
            transition: 0.3s;
        }

        .register-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(79, 70, 229, 0.25);
        }

        .register-btn-google {
            height: 50px;
            border-radius: 14px;
            font-weight: 600;
            font-size: 15px;
            transition: 0.3s;
        }

        .register-btn-google:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
        }

        .nav-pills {
            background: #F3F4F6;
            padding: 5px;
            border-radius: 14px;
            width: fit-content;
        }

        .nav-pills .nav-link {
            border-radius: 10px;
            color: #6B7280;
            font-weight: 600;
            padding: 10px 22px;
            font-size: 14px;
            border: none;
        }

        .nav-pills .nav-link.active {
            background: linear-gradient(90deg, #4F46E5 0%, #7C3AED 100%);
            color: white;
        }

        .custom-file-upload {
            border: 1px dashed #C7D2FE;
            border-radius: 14px;
            padding: 14px 16px;
            transition: .3s;
            background: #F8FAFC;
            cursor: pointer;
        }

        .custom-file-upload:hover {
            border-color: #7C3AED;
            background: #F5F3FF;
        }

        .custom-file-upload input {
            display: none;
        }

        .custom-file-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }

        .custom-file-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .custom-file-left h6 {
            font-size: 14px;
        }

        .custom-file-left small {
            font-size: 12px;
        }

        .custom-file-icon {
            width: 46px;
            height: 46px;
            border-radius: 12px;
            background: rgba(124, 58, 237, 0.1);
            color: #7C3AED;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }

        .browse-btn {
            padding: 8px 14px;
            border-radius: 10px;
            background: linear-gradient(90deg, #4F46E5 0%, #7C3AED 100%);
            color: white;
            font-weight: 600;
            font-size: 13px;
            white-space: nowrap;
        }

        .tab-content {
            min-height: auto;
        }

        .p-lg-5 {
            padding: 2.5rem !important;
        }

        @media(max-width: 991px) {
            .custom-file-content {
                flex-direction: column;
                align-items: flex-start;
            }

            .browse-btn {
                width: 100%;
                text-align: center;
            }
        }
    </style>
@endpush
@section('content')
    <section class="container min-vh-100 d-flex align-items-center py-4">
        <div class="row justify-content-center w-100">
            <div class="col-xl-10">
                <div class="card register-card">
                    <div class="row g-0">
                        {{-- LEFT --}}
                        <div class="col-lg-5 d-none d-lg-flex flex-column justify-content-between register-left">
                            <div style="position: relative; z-index: 2;">
                                <img src="{{ asset('/frontend/assets/ictImg/logo/ictLogo.jpg') }}" width="75"
                                    height="75" class="rounded-circle mb-4 object-fit-cover" alt="logo">
                                <h2 class="fw-bold mb-3 text-white">
                                    Join ICT Center!
                                </h2>
                                <p class="opacity-75 fs-5">
                                    Start your journey in modern technology education and practical digital skills.
                                </p>
                            </div>
                            <div style="position: relative; z-index: 2;">
                                <div class="feature-box d-flex align-items-center gap-3 mb-3">
                                    <i class="ti ti-book"></i>
                                    <div>
                                        <h6 class="mb-1 fw-semibold text-white">
                                            Learn Modern Skills
                                        </h6>
                                        <small class="opacity-75">
                                            Industry-focused practical courses
                                        </small>
                                    </div>
                                </div>
                                <div class="feature-box d-flex align-items-center gap-3 mb-3">
                                    <i class="ti ti-users"></i>
                                    <div>
                                        <h6 class="mb-1 fw-semibold text-white">
                                            Community Learning
                                        </h6>
                                        <small class="opacity-75">
                                            Collaborate with students & teachers
                                        </small>
                                    </div>
                                </div>
                                <div class="feature-box d-flex align-items-center gap-3">
                                    <i class="ti ti-certificate"></i>
                                    <div>
                                        <h6 class="mb-1 fw-semibold text-white">
                                            Certification
                                        </h6>
                                        <small class="opacity-75">
                                            Build your future career path
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- RIGHT --}}
                        <div class="col-lg-7 bg-white">
                            <div class="p-4 p-lg-5">
                                {{-- HEADER --}}
                                <div class="mb-4">
                                    <h2 class="fw-bold mb-2">
                                        Create Account
                                    </h2>
                                    <p class="text-muted mb-0">
                                        Register with your Google account to access the ICT Center platform.
                                    </p>
                                </div>
                                <x-auth-session-status class="mb-4" :status="session('status')" />
                                {{-- Self-entered name/email/password registration is
                                     intentionally removed — it let anyone create an
                                     account with made-up info, no proof it's a real
                                     person or a real, owned email. Google verifies both,
                                     so it's now the only way a student account gets
                                     created. (Teacher self-registration is separately on
                                     hold — staff/admin still create instructor accounts
                                     directly. That old form is preserved in git history /
                                     RegisteredUserController::store() if it's needed again.) --}}
                                {{-- GOOGLE --}}
                                <div class="d-grid mb-4">
                                    <a href="{{ route('auth.google.redirect') }}"
                                        class="btn btn-light border register-btn-google d-flex align-items-center justify-content-center gap-2">
                                        <svg width="18" height="18" viewBox="0 0 18 18" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                            <path fill="#4285F4" d="M17.64 9.2c0-.637-.057-1.251-.164-1.84H9v3.481h4.844c-.209 1.125-.843 2.078-1.796 2.717v2.258h2.908c1.702-1.567 2.684-3.874 2.684-6.615z" />
                                            <path fill="#34A853" d="M9 18c2.43 0 4.467-.806 5.956-2.18l-2.908-2.259c-.806.54-1.837.86-3.048.86-2.344 0-4.328-1.584-5.036-3.711H.957v2.332C2.438 15.983 5.482 18 9 18z" />
                                            <path fill="#FBBC05" d="M3.964 10.71A5.41 5.41 0 0 1 3.682 9c0-.593.102-1.17.282-1.71V4.958H.957A8.996 8.996 0 0 0 0 9c0 1.452.348 2.827.957 4.042l3.007-2.332z" />
                                            <path fill="#EA4335" d="M9 3.58c1.321 0 2.508.454 3.44 1.345l2.582-2.581C13.463.891 11.426 0 9 0 5.482 0 2.438 2.017.957 4.958L3.964 7.29C4.672 5.163 6.656 3.58 9 3.58z" />
                                        </svg>
                                        <span class="fw-semibold">Register with Google</span>
                                    </a>
                                </div>
                                {{-- LOGIN --}}
                                <div class="text-center mt-2">
                                    <span class="text-muted">
                                        Already have an account?
                                    </span>
                                    <a href="{{ route('login') }}" class="fw-semibold text-decoration-none">
                                        Login
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
