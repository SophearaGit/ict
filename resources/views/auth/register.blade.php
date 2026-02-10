{{-- <x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout> --}}

@extends('auth.layouts.master')
@section('content')
    <section class="container d-flex flex-column vh-100">
        <div class="row align-items-center justify-content-center g-0 h-lg-100 py-8">
            <div class="col-lg-5 col-md-8 py-8 py-xl-0">
                <!-- Card -->
                <div class="card rounded-3">
                    <!-- Card Header -->
                    <div class="card-header p-0">
                        <ul class="nav nav-lb-tab border-bottom-0" id="tab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" id="student-sign-up-form" data-bs-toggle="pill"
                                    href="#student_form" role="tab" aria-controls="student_form"
                                    aria-selected="true">Student</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="instructor-sign-up-form" data-bs-toggle="pill" href="#teacher_form"
                                    role="tab" aria-controls="teacher_form" aria-selected="false"
                                    tabindex="-1">Teacher</a>
                            </li>
                        </ul>
                    </div>

                    <div>
                        <div class="tab-content" id="tabContent">
                            <!-- Tab -->
                            <div class="tab-pane fade active show" id="student_form" role="tabpanel"
                                aria-labelledby="student-sign-up-form">
                                <!-- Card -->
                                <div class="card shadow">
                                    <!-- Card body -->
                                    <div class="card-body p-6">
                                        <div class="mb-4">
                                            <a href="{{ route('home') }}"><img src="" class="mb-4"
                                                    alt="ict-logo"></a>
                                            <h1 class="mb-1 fw-bold">Sign up</h1>
                                            <span>
                                                Already have an account?
                                                <a href="{{ route('login') }}" class="ms-1">Sign in</a>
                                            </span>
                                        </div>
                                        <!-- Student Form -->
                                        <form method="POST" action="{{ route('register', ['type' => 'student']) }}">
                                            @csrf
                                            <!-- Username -->
                                            <div class="mb-3">
                                                <label for="name" class="form-label">Name</label>
                                                <input value="{{ old('name') }}" type="text" id="name"
                                                    class="form-control" name="name" placeholder="Name here"
                                                    autocomplete="name" required autofocus>
                                                <x-input-error :messages="$errors->get('name')" class="text-danger mt-2" />
                                            </div>
                                            <!-- Email -->
                                            <div class="mb-3">
                                                <label for="email" class="form-label">Email</label>
                                                <input type="email" id="email" class="form-control" name="email"
                                                    placeholder="Email address here" value="{{ old('email') }}" required>
                                                <x-input-error :messages="$errors->get('email')" class="text-danger mt-2" />
                                            </div>
                                            <!-- Password -->
                                            <div class="mb-3">
                                                <label for="password" class="form-label">Password</label>
                                                <input type="password" id="password" class="form-control" name="password"
                                                    placeholder="Password here" autocomplete="new-password" required>
                                                <x-input-error :messages="$errors->get('password')" class="text-danger mt-2" />
                                            </div>
                                            <!-- Confirm Password -->
                                            <div class="mb-6">
                                                <label for="password_confirmation" class="form-label">Confirm
                                                    Password</label>
                                                <input type="password" id="password_confirmation" class="form-control"
                                                    name="password_confirmation" placeholder="Password confirmation here"
                                                    autocomplete="new-password" required>
                                                <x-input-error :messages="$errors->get('password_confirmation')" class="text-danger mt-2" />
                                            </div>
                                            <div>
                                                <!-- Button -->
                                                <div class="d-grid">
                                                    <button type="submit" class="btn btn-primary">Create My
                                                        Account</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="teacher_form" role="tabpanel"
                                aria-labelledby="instructor-sign-up-form">
                                <!-- Card -->
                                <div class="card shadow">
                                    <!-- Card body -->
                                    <div class="card-body p-6">
                                        <div class="mb-4">
                                            <a href="{{ route('home') }}"><img src="" class="mb-4"
                                                    alt="ict-logo"></a>
                                            <h1 class="mb-1 fw-bold">Sign up</h1>
                                            <span>
                                                Already have an account?
                                                <a href="{{ route('login') }}" class="ms-1">Sign in</a>
                                            </span>
                                        </div>
                                        <!-- Teacher Form -->
                                        <form method="POST" action="{{ route('register', ['type' => 'instructor']) }}"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <!-- Username -->
                                            <div class="mb-3">
                                                <label for="name" class="form-label">Name</label>
                                                <input value="{{ old('name') }}" type="text" id="name"
                                                    class="form-control" name="name" placeholder="Name here"
                                                    autocomplete="name" required autofocus>
                                                <x-input-error :messages="$errors->get('name')" class="text-danger mt-2" />
                                            </div>
                                            <!-- Email -->
                                            <div class="mb-3">
                                                <label for="email" class="form-label">Email</label>
                                                <input type="email" id="email" class="form-control" name="email"
                                                    placeholder="Email address here" value="{{ old('email') }}" required>
                                                <x-input-error :messages="$errors->get('email')" class="text-danger mt-2" />
                                            </div>
                                            {{-- Document --}}
                                            <div class="mb-3">
                                                <label for="document" class="form-label text-capitalize">curriculum
                                                    vitae</label>
                                                <input type="file" id="document" class="form-control"
                                                    name="document" placeholder="Upload your document here" required>
                                                <x-input-error :messages="$errors->get('document')" class="text-danger mt-2" />
                                            </div>
                                            <!-- Password -->
                                            <div class="mb-3">
                                                <label for="password" class="form-label">Password</label>
                                                <input type="password" id="password" class="form-control"
                                                    name="password" placeholder="Password here"
                                                    autocomplete="new-password" required>
                                                <x-input-error :messages="$errors->get('password')" class="text-danger mt-2" />
                                            </div>
                                            <!-- Confirm Password -->
                                            <div class="mb-6">
                                                <label for="password_confirmation" class="form-label">Confirm
                                                    Password</label>
                                                <input type="password" id="password_confirmation" class="form-control"
                                                    name="password_confirmation" placeholder="Password confirmation here"
                                                    autocomplete="new-password" required>
                                                <x-input-error :messages="$errors->get('password_confirmation')" class="text-danger mt-2" />
                                            </div>
                                            <div>
                                                <!-- Button -->
                                                <div class="d-grid">
                                                    <button type="submit" class="btn btn-primary">
                                                        Create My Account
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
