@extends('auth.layouts.master')
@section('page_title', isset($page_title) ? $page_title : 'ICT Center | Forgot Password')
@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tabler-icons/3.35.0/tabler-icons.min.css" />
    <style>
        body {
            background: linear-gradient(135deg, #EEF2FF 0%, #F9FAFB 100%);
        }

        .forgot-card {
            border: none;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
        }

        .forgot-left {
            background: linear-gradient(180deg, #4F46E5 0%, #7C3AED 100%);
            color: white;
            position: relative;
        }

        .forgot-left::before {
            content: '';
            position: absolute;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 50%;
            top: -120px;
            right: -120px;
        }

        .forgot-left::after {
            content: '';
            position: absolute;
            width: 220px;
            height: 220px;
            background: rgba(255, 255, 255, 0.06);
            border-radius: 50%;
            bottom: -100px;
            left: -100px;
        }

        .forgot-form-control {
            height: 52px;
            border-radius: 14px;
            border: 1px solid #E5E7EB;
            padding-left: 45px;
        }

        .forgot-form-control:focus {
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

        .forgot-btn {
            height: 52px;
            border-radius: 14px;
            background: linear-gradient(90deg, #4F46E5 0%, #7C3AED 100%);
            border: none;
            font-weight: 600;
            transition: 0.3s;
        }

        .forgot-btn:hover {
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
                <div class="card forgot-card">
                    <div class="row g-0">
                        {{-- LEFT SIDE --}}
                        <div class="col-lg-5 d-none d-lg-flex flex-column justify-content-between p-5 forgot-left">
                            <div style="position: relative; z-index: 2;">
                                <img src="{{ asset('/frontend/assets/ictImg/logo/ictLogo.jpg') }}" width="80"
                                    height="80" class="rounded-circle mb-4 object-fit-cover" alt="logo">
                                <h2 class="fw-bold mb-3 text-white">
                                    Reset Your Password
                                </h2>
                                <p class="opacity-75 fs-5">
                                    Don’t worry! Enter your email and we’ll send you a password reset link.
                                </p>
                            </div>
                            <div style="position: relative; z-index: 2;">
                                <div class="feature-box d-flex align-items-center gap-3 mb-3">
                                    <i class="ti ti-shield-lock fs-1"></i>
                                    <div>
                                        <h6 class="mb-1 fw-semibold text-white">
                                            Secure Access
                                        </h6>
                                        <small class="opacity-75">
                                            Your account security is protected
                                        </small>
                                    </div>
                                </div>
                                <div class="feature-box d-flex align-items-center gap-3 mb-3">
                                    <i class="ti ti-mail-forward fs-1"></i>
                                    <div>
                                        <h6 class="mb-1 fw-semibold text-white">
                                            Instant Reset Link
                                        </h6>
                                        <small class="opacity-75">
                                            Receive reset instructions quickly
                                        </small>
                                    </div>
                                </div>
                                <div class="feature-box d-flex align-items-center gap-3">
                                    <i class="ti ti-device-laptop fs-1"></i>
                                    <div>
                                        <h6 class="mb-1 fw-semibold text-white">
                                            Continue Learning
                                        </h6>
                                        <small class="opacity-75">
                                            Get back to your dashboard anytime
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- RIGHT SIDE --}}
                        <div class="col-lg-7 bg-white">
                            <div class="p-4 p-lg-5">
                                <div class="text-center text-lg-start mb-5">
                                    <h2 class="fw-bold mb-2">
                                        Forgot Password
                                    </h2>
                                    <p class="text-muted mb-0">
                                        Enter your email address to receive a password reset link.
                                    </p>
                                </div>
                                {{-- STATUS --}}
                                @if (session('status'))
                                    <div class="alert alert-success border-0 rounded-4">
                                        {{ session('status') }}
                                    </div>
                                @endif
                                {{-- FORM --}}
                                <form method="POST" action="{{ route('password.email') }}">
                                    @csrf
                                    {{-- EMAIL --}}
                                    <div class="mb-4">
                                        <label for="email" class="form-label fw-semibold">
                                            Email Address
                                            <span class="text-danger">*</span>
                                        </label>
                                        <div class="position-relative">
                                            <i class="ti ti-mail input-icon"></i>
                                            <input type="email" id="email" class="form-control forgot-form-control"
                                                name="email" placeholder="Enter your email" value="{{ old('email') }}"
                                                required autofocus>
                                        </div>
                                        <x-input-error :messages="$errors->get('email')" class="text-danger mt-2" />
                                    </div>
                                    {{-- BUTTON --}}
                                    <div class="d-grid mb-4">
                                        <button type="submit" class="btn forgot-btn text-white">
                                            Send Password Reset Link
                                        </button>
                                    </div>
                                    {{-- LOGIN --}}
                                    <div class="text-center">
                                        <span class="text-muted">
                                            Remember your password?
                                        </span>
                                        <a href="{{ route('login') }}" class="fw-semibold text-decoration-none">
                                            Back to Login
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
