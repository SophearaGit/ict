@extends('frontend.layouts.master')
@section('page_title', isset($page_title) ? $page_title : 'Page Title Here')
@push('styles')
    <link rel="stylesheet" href="/admin/assets/dist/libs/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
    <style>
        .avatar-wrapper {
            width: 100px;
            height: 100px;
            cursor: pointer;
        }

        .avatar-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .avatar-overlay {
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.55);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            opacity: 0;
            transition: opacity 0.3s ease;
            font-size: 14px;
            font-weight: 500;
        }

        .avatar-wrapper:hover .avatar-overlay {
            opacity: 1;
        }
    </style>
@endpush
@section('content')
    @php
        $imgCheck = auth()->user()->image == 'no-img.jpg' ? '\default-images\user\both.jpg' : auth()->user()->image;
    @endphp
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
                            <div class="d-lg-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center mb-4 mb-lg-0">
                                    <div class="avatar-wrapper position-relative">
                                        <img src="{{ $imgCheck }}" id="img-uploaded" class="avatar-xl rounded-circle"
                                            alt="avatar">
                                        <div class="avatar-overlay"
                                            onclick="
                                        event.preventDefault();
                                        $('#avatar-input').click();
                                        ">
                                            <span>
                                                <i class="fe fe-camera fs-4"></i></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="ms-3">
                                        <h4 class="mb-0">Your avatar</h4>
                                        <p class="mb-2">PNG or JPG no bigger than 800px wide and tall.</p>
                                    </div>
                                </div>
                                <div>
                                </div>
                            </div>
                            <hr class="my-5">
                            <div>
                                <h4 class="mb-0">Personal Details</h4>
                                <p class="mb-4">
                                    All of your personal details will be included in your certificate, so please provide
                                    accurate information.
                                </p>
                                <!-- Form -->
                                <form action="{{ route('student.profile.edit.submit') }}" method="POST"
                                    class="row gx-3 needs-validation" novalidate="" enctype="multipart/form-data">
                                    @csrf
                                    <input class="form-control" type="file" id="avatar-input" name="avatar"
                                        accept="image/png, image/jpeg" hidden
                                        onchange="
                                            const file = this.files[0];
                                            if (!file) return;
                                            const reader = new FileReader();
                                            reader.onload = e => $('#img-uploaded').attr('src', e.target.result);
                                            reader.readAsDataURL(file);
                                            reader.readAsDataURL(file);
                                        ">
                                    <!-- name -->
                                    <div class="mb-3 col-12 col-md-6">
                                        <label class="form-label" for="name">Name ( English )</label>
                                        <input type="text" id="name" name="name" class="form-control"
                                            placeholder="Enter khmer name here." value="{{ auth()->user()->name }}"
                                            required="">
                                        <div class="invalid-feedback">Please enter first name.</div>
                                        <x-input-error :messages="$errors->get('name')" class="mt-2 text-danger" />
                                    </div>
                                    {{-- khmer_name --}}
                                    <div class="mb-3 col-12 col-md-6">
                                        <label class="form-label" for="khmer_name">Name ( Khmer )</label>
                                        <input type="text" id="khmer_name" name="khmer_name" class="form-control"
                                            placeholder="" value="{{ auth()->user()->khmer_name }}" required="">
                                        <div class="invalid-feedback">Please entername.</div>
                                        <x-input-error :messages="$errors->get('khmer_name')" class="mt-2 text-danger" />
                                    </div>
                                    {{-- email --}}
                                    <div class="mb-3 col-12 col-md-6">
                                        <label class="form-label" for="email">Email</label>
                                        <input type="email" id="email" name="email" class="form-control"
                                            placeholder="" value="{{ auth()->user()->email }}" required="">
                                        <div class="invalid-feedback">Please enter email.</div>
                                        <x-input-error :messages="$errors->get('email')" class="mt-2 text-danger" />
                                    </div>
                                    {{-- headline --}}
                                    <div class="mb-3 col-12 col-md-6">
                                        <label class="form-label" for="headline">Headline</label>
                                        <input type="text" id="headline" name="headline" class="form-control"
                                            placeholder="" value="{{ auth()->user()->headline }}" required="">
                                        <div class="invalid-feedback">Please enter headline.</div>
                                        <x-input-error :messages="$errors->get('headline')" class="mt-2 text-danger" />
                                    </div>
                                    {{-- bio --}}
                                    <div class="mb-3 col-12 col-md-6">
                                        <label class="form-label" for="bio">Bio</label>
                                        <input type="text" id="bio" name="bio" class="form-control"
                                            placeholder="" value="{{ auth()->user()->bio }}" required="">
                                        <div class="invalid-feedback">Please enter bio.</div>
                                        <x-input-error :messages="$errors->get('bio')" class="mt-2 text-danger" />
                                    </div>
                                    {{--  --}}
                                    {{-- gender --}}
                                    <div class="mb-3 col-12 col-md-6">
                                        <label class="form-label" for="gender">Gender</label>
                                        <select id="gender" name="gender" class="form-select" required="">
                                            <option value="">Select gender</option>
                                            <option value="male" @selected(auth()->user()->gender === 'male')>Male</option>
                                            <option value="female" @selected(auth()->user()->gender === 'female')>Female</option>
                                        </select>
                                        <div class="invalid-feedback">Please select gender.</div>
                                        <x-input-error :messages="$errors->get('gender')" class="mt-2 text-danger" />
                                    </div>
                                    {{-- dob --}}
                                    <div class="mb-3 col-12 col-md-6">
                                        <label class="form-label" for="dob">Date of Birth</label>
                                        <input type="date" id="dob" name="dob" class="form-control"
                                            placeholder="" value="{{ auth()->user()->dob }}" required="">
                                        <div class="invalid-feedback">Please enter date of birth.</div>
                                        <x-input-error :messages="$errors->get('dob')" class="mt-2 text-danger" />
                                    </div>
                                    {{-- nationality --}}
                                    <div class="mb-3 col-12 col-md-6">
                                        <label class="form-label" for="nationality">Nationality</label>
                                        <input type="text" id="nationality" name="nationality" class="form-control"
                                            placeholder="" value="{{ auth()->user()->nationality }}" required="">
                                        <div class="invalid-feedback">Please enter nationality.</div>
                                        <x-input-error :messages="$errors->get('nationality')" class="mt-2 text-danger" />
                                    </div>

                                    <div class="col-12">
                                        <!-- Button -->
                                        <button class="btn btn-primary" type="submit">Update Profile</button>
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
    <script src="/admin/assets/dist/libs/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
    <script>
        $('#dob').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true,
        });
    </script>
@endpush
