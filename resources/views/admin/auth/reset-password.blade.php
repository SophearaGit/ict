{{-- <x-guest-layout>
    <form method="POST" action="{{ route('admin.password.store') }}">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
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
            <x-primary-button>
                {{ __('Reset Password') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout> --}}


<!DOCTYPE html>
<html lang="en">

<head>
    <!--  Title -->
    <title></title>
    <!--  Required Meta Tag -->
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="handheldfriendly" content="true" />
    <meta name="MobileOptimized" content="width" />
    <meta name="description" content="Mordenize" />
    <meta name="author" content="" />
    <meta name="keywords" content="Mordenize" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!--  Favicon -->
    <link rel="shortcut icon" type="image/png" href="" />

    <!-- Core Css -->
    <link id="themeColors" rel="stylesheet" href="/admin/assets/dist/css/style.min.css" />
</head>

<body>
    {{-- <!-- Preloader -->
    <div class="preloader">
        <img src="/admin/assets/dist/images/logos/favicon.ico" alt="loader" class="lds-ripple img-fluid" />
    </div>
    <!-- Preloader -->
    <div class="preloader">
        <img src="/admin/assets/dist/images/logos/favicon.ico" alt="loader" class="lds-ripple img-fluid" />
    </div> --}}
    <!--  Body Wrapper -->
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed">
        <div class="position-relative overflow-hidden radial-gradient min-vh-100">
            <div class="position-relative z-index-5">
                <div class="row">
                    <div class="col-lg-6 col-xl-8 col-xxl-9">
                        {{-- <a href="./index.html" class="text-nowrap logo-img d-block px-4 py-9 w-100">
                            <img src="/admin/assets/dist/images/logos/dark-logo.svg" width="180" alt="">
                        </a> --}}
                        <div class="d-none d-lg-flex align-items-center justify-content-center"
                            style="height: calc(100vh - 80px);">
                            <img src="/admin/assets/dist/images/backgrounds/login-security.svg" alt=""
                                class="img-fluid" width="500">
                        </div>
                    </div>
                    <div class="col-lg-6 col-xl-4 col-xxl-3">
                        <div class="card mb-0 shadow-none rounded-0 min-vh-100 h-100">
                            <div class="d-flex align-items-center w-100 h-100">
                                <div class="card-body">
                                    <div class="mb-5">
                                        <h2 class="fw-bolder fs-7 mb-3">Forgot your password?</h2>
                                        <p class="mb-0 ">
                                            Please enter the email address associated with your account and We will
                                            email you a link to reset your password.
                                        </p>
                                    </div>
                                    <form method="POST" action="{{ route('admin.password.store') }}">
                                        @csrf
                                        <!-- Password Reset Token -->
                                        <input type="hidden" name="token" value="{{ $request->route('token') }}">

                                        <div class="mb-3">
                                            <label for="email" class="form-label">Email address</label>
                                            <input required autofocus type="email" class="form-control" id="email"
                                                name="email" value="{{ old('email') }}" aria-describedby="emailHelp">
                                            <x-input-error :messages="$errors->get('email')" class="mt-2 text-danger" />

                                        </div>
                                        {{-- Password --}}
                                        <div class="mb-3">
                                            <label for="password" class="form-label">Password</label>
                                            <input required type="password" class="form-control" id="password"
                                                name="password" aria-describedby="passwordHelp">
                                            <x-input-error :messages="$errors->get('password')" class="mt-2 text-danger" />
                                        </div>
                                        {{-- Confirm Password --}}
                                        <div class="mb-3">
                                            <label for="password_confirmation" class="form-label">Confirm
                                                Password</label>
                                            <input required type="password" class="form-control"
                                                id="password_confirmation" name="password_confirmation"
                                                aria-describedby="passwordHelp">
                                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-danger" />
                                        </div>
                                        <button type="submit" class="btn btn-primary w-100 py-8 mb-3">Reset
                                            Password</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!--  Import Js Files -->
    <script src="/admin/assets/dist/libs/jquery/dist/jquery.min.js"></script>
    <script src="/admin/assets/dist/libs/simplebar/dist/simplebar.min.js"></script>
    <script src="/admin/assets/dist/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <!--  core files -->
    <script src="/admin/assets/dist/js/app.min.js"></script>
    <script src="/admin/assets/dist/js/app.init.js"></script>
    <script src="/admin/assets/dist/js/app-style-switcher.js"></script>
    <script src="/admin/assets/dist/js/sidebarmenu.js"></script>

    <script src="/admin/assets/dist/js/custom.js"></script>
</body>

</html>
