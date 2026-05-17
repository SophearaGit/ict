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
                                        Register as a student or teacher to access the ICT Center platform.
                                    </p>
                                </div>
                                {{-- TABS --}}
                                <ul class="nav nav-pills gap-2 mb-4" role="tablist">
                                    <li class="nav-item">
                                        <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#student"
                                            type="button">
                                            Student
                                        </button>
                                    </li>
                                    <li class="nav-item">
                                        <button class="nav-link" data-bs-toggle="pill" data-bs-target="#teacher"
                                            type="button">
                                            Teacher
                                        </button>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    {{-- STUDENT --}}
                                    <div class="tab-pane fade show active" id="student">
                                        <form method="POST" action="{{ route('register', ['type' => 'student']) }}">
                                            @csrf
                                            {{-- NAME --}}
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold mb-2">
                                                    Full Name
                                                </label>
                                                <div class="position-relative">
                                                    <i class="ti ti-user input-icon"></i>
                                                    <input type="text" name="name"
                                                        class="form-control register-form-control"
                                                        placeholder="Enter your full name" value="{{ old('name') }}"
                                                        required>
                                                </div>
                                            </div>
                                            {{-- EMAIL --}}
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold mb-2">
                                                    Email Address
                                                </label>
                                                <div class="position-relative">
                                                    <i class="ti ti-mail input-icon"></i>
                                                    <input type="email" name="email"
                                                        class="form-control register-form-control"
                                                        placeholder="Enter your email" value="{{ old('email') }}" required>
                                                </div>
                                            </div>
                                            {{-- PASSWORD --}}
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold mb-2">
                                                    Password
                                                </label>
                                                <div class="position-relative">
                                                    <i class="ti ti-lock input-icon"></i>
                                                    <input type="password" id="studentPassword" name="password"
                                                        class="form-control register-form-control pe-5"
                                                        placeholder="Create password" required>
                                                    <i class="ti ti-eye toggle-password" data-target="#studentPassword"></i>
                                                </div>
                                            </div>
                                            {{-- CONFIRM --}}
                                            <div class="mb-4">
                                                <label class="form-label fw-semibold mb-2">
                                                    Confirm Password
                                                </label>
                                                <div class="position-relative">
                                                    <i class="ti ti-lock-check input-icon"></i>
                                                    <input type="password" id="studentConfirmPassword"
                                                        name="password_confirmation"
                                                        class="form-control register-form-control pe-5"
                                                        placeholder="Confirm password" required>
                                                    <i class="ti ti-eye toggle-password"
                                                        data-target="#studentConfirmPassword"></i>
                                                </div>
                                            </div>
                                            {{-- BUTTON --}}
                                            <div class="d-grid mb-3 mt-4">
                                                <button type="submit" class="btn register-btn text-white">
                                                    Create Student Account
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                    {{-- TEACHER --}}
                                    <div class="tab-pane fade" id="teacher">
                                        <form method="POST" action="{{ route('register', ['type' => 'instructor']) }}"
                                            enctype="multipart/form-data">
                                            @csrf
                                            {{-- NAME --}}
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold mb-2">
                                                    Full Name
                                                </label>
                                                <div class="position-relative">
                                                    <i class="ti ti-user input-icon"></i>
                                                    <input type="text" name="name"
                                                        class="form-control register-form-control"
                                                        placeholder="Enter your full name" required>
                                                </div>
                                            </div>
                                            {{-- EMAIL --}}
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold mb-2">
                                                    Email Address
                                                </label>
                                                <div class="position-relative">
                                                    <i class="ti ti-mail input-icon"></i>
                                                    <input type="email" name="email"
                                                        class="form-control register-form-control"
                                                        placeholder="Enter your email" required>
                                                </div>
                                            </div>
                                            {{-- RESUME --}}
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold mb-2">
                                                    Resume / CV
                                                </label>
                                                <label class="custom-file-upload w-100">
                                                    <input type="file" name="document" id="document" required>
                                                    <div class="custom-file-content">
                                                        <div class="custom-file-left">
                                                            <div class="custom-file-icon">
                                                                <i class="ti ti-file-text"></i>
                                                            </div>
                                                            <div>
                                                                <h6 class="mb-1 fw-semibold">
                                                                    Upload Resume
                                                                </h6>
                                                                <small class="text-muted">
                                                                    PDF, DOC, DOCX
                                                                </small>
                                                            </div>
                                                        </div>
                                                        <div class="browse-btn">
                                                            Browse
                                                        </div>
                                                    </div>
                                                </label>
                                                <small id="fileName" class="text-muted mt-2 d-block"></small>
                                            </div>
                                            {{-- PASSWORD --}}
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold mb-2">
                                                    Password
                                                </label>
                                                <div class="position-relative">
                                                    <i class="ti ti-lock input-icon"></i>
                                                    <input type="password" id="teacherPassword" name="password"
                                                        class="form-control register-form-control pe-5"
                                                        placeholder="Create password" required>
                                                    <i class="ti ti-eye toggle-password"
                                                        data-target="#teacherPassword"></i>
                                                </div>
                                            </div>
                                            {{-- CONFIRM --}}
                                            <div class="mb-4">
                                                <label class="form-label fw-semibold mb-2">
                                                    Confirm Password
                                                </label>
                                                <div class="position-relative">
                                                    <i class="ti ti-lock-check input-icon"></i>
                                                    <input type="password" id="teacherConfirmPassword"
                                                        name="password_confirmation"
                                                        class="form-control register-form-control pe-5"
                                                        placeholder="Confirm password" required>
                                                    <i class="ti ti-eye toggle-password"
                                                        data-target="#teacherConfirmPassword"></i>
                                                </div>
                                            </div>
                                            {{-- BUTTON --}}
                                            <div class="d-grid mb-3 mt-4">
                                                <button type="submit" class="btn register-btn text-white">
                                                    Create Teacher Account
                                                </button>
                                            </div>
                                        </form>
                                    </div>
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
@push('scripts')
    <script>
        $('.toggle-password').on('click', function() {
            let target = $(this).data('target');
            let input = $(target);
            let type = input.attr('type') === 'password' ?
                'text' :
                'password';
            input.attr('type', type);
            $(this).toggleClass('ti-eye');
            $(this).toggleClass('ti-eye-off');
        });
        $('#document').on('change', function() {
            let fileName = this.files[0]?.name;
            if (fileName) {
                $('#fileName').text(fileName);
            }
        });
    </script>
@endpush
