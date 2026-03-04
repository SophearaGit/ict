{{-- <x-guest-layout>
    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('Email Password Reset Link') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout> --}}
@extends('auth.layouts.master')
@section('page_title', isset($page_title) ? $page_title : 'Page Title Here')
@section('content')
    <section class="container d-flex flex-column vh-100">
        <div class="row align-items-center justify-content-center g-0 h-lg-100 py-8">
            <div class="col-lg-5 col-md-8 py-8 py-xl-0">
                <!-- Card -->
                <div class="card shadow">
                    <!-- Card body -->
                    <div class="card-body p-6">
                        <div class="mb-4 text-center">
                            <a href="{{ route('home') }}"><img style="width: 100px; height: 100px;"
                                    src=" {{ asset('/frontend/assets/ictImg/logo/ictLogo.jpg') }} " class="mb-4"
                                    alt="ict-logo-icon"></a>
                            <h1 class="mb-1 fw-bold">Forgot Password</h1>
                            @if (session('status'))
                                <x-auth-session-status class="mb-4 text-success" :status="session('status')" />
                            @else
                                <p>Fill the form to reset your password.</p>
                            @endif
                        </div>


                        <!-- Form -->
                        <form method="POST" action="{{ route('password.email') }}">
                            @csrf
                            <!-- Email -->
                            <div class="mb-3">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" id="email" class="form-control" name="email"
                                    placeholder="Enter Your Email " value="{{ old('email') }}" required autofocus>
                                <x-input-error :messages="$errors->get('email')" class="text-danger mt-2" />
                            </div>
                            <!-- Button -->
                            <div class="mb-3 d-grid">
                                <button type="submit" class="btn btn-primary">Send Resest Link</button>
                            </div>
                            <span>
                                Return to
                                <a href="{{ route('login') }}">Login</a>
                            </span>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
