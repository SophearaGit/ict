@extends('frontend.layouts.master')
@section('page_title', isset($page_title) ? $page_title : 'Page Title Here')
@section('content')
    <section class="pt-5 pb-5">
        <div class="container">
            @include('frontend.student.partials.user-info')
            <!-- Content -->
            <div class="row mt-0 mt-md-4">
                <div class="col-lg-3 col-md-4 col-12">
                    @include('frontend.student.partials.side-navbar')
                </div>
                <div class="col-lg-9 col-md-8 col-12">
                    <!-- Card -->
                    <div class="card">
                        <!-- Card header -->
                        @include('frontend.student.partials.card-header')
                        <!-- Card body -->
                        <div class="card-body">
                            <div>
                                <h4 class="mb-0">Change Password</h4>
                                <p>
                                    You have full control to manage your own account setting. If you feel that your
                                    password is compromised, change it immediately.
                                </p>
                                <!-- Form -->
                                <form class="row needs-validation" novalidate=""
                                    action="{{ route('student.security.update') }}" method="POST">
                                    @csrf
                                    <div class="col-lg-6 col-md-12 col-12">
                                        <!-- Current password -->
                                        <div class="mb-3">
                                            <label class="form-label" for="current_password">Current password</label>
                                            <input id="current_password" type="password" name="current_password"
                                                class="form-control" placeholder="" required="">
                                            <div class="invalid-feedback">Please enter current password.</div>
                                            <x-input-error :messages="$errors->get('current_password')" class="mt-2 text-danger" />
                                        </div>
                                        <!-- New password -->
                                        <div class="mb-3 password-field">
                                            <label class="form-label" for="password">New password</label>
                                            <input id="password" type="password" name="password" class="form-control mb-2"
                                                placeholder="" required="">
                                            <div class="invalid-feedback">Please enter new password.</div>
                                            <x-input-error :messages="$errors->get('password')" class="mt-2 text-danger" />
                                            <div class="row align-items-center g-0">
                                                <div class="col-6">
                                                    <span data-bs-toggle="tooltip" data-placement="right"
                                                        data-bs-original-title="Test it by typing a password in the field below. To reach full strength, use at least 6 characters, a capital letter and a digit, e.g. 'Test01'">
                                                        Password strength
                                                        <i class="fe fe-help-circle ms-1"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <!-- Confirm new password -->
                                            <label class="form-label" for="password_confirmation">Confirm New
                                                Password</label>
                                            <input id="password_confirmation" type="password" name="password_confirmation"
                                                class="form-control mb-2" placeholder="" required="">
                                            <div class="invalid-feedback">Please enter confirm new password.</div>
                                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-danger" />
                                        </div>
                                        <!-- Button -->
                                        <button type="submit" class="btn btn-primary">Save Password</button>
                                        <div class="col-6"></div>
                                    </div>
                                    {{-- <div class="col-12 mt-4">
                                        <p class="mb-0">
                                            Can't remember your current password?
                                            <a href="{{ route('') }}">Reset your password via email</a>
                                        </p>
                                    </div> --}}
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
