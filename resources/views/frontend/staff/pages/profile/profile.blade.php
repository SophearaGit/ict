@extends('frontend.staff.layout.master')
@section('page_title', isset($page_title) ? $page_title : 'Page Title Here')
@push('styles')
    <style>
        .avatar-wrapper {
            width: 140px;
            height: 140px;
            margin: auto;
            border-radius: 50%;
            overflow: hidden;
            border: 4px solid #f1f1f1;
        }

        .avatar-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
    </style>
@endpush
@section('content')
    @include('frontend.staff.pages.partials.breadcrumb')
    <div class="card">
        <ul class="nav nav-pills user-profile-tab" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <button data-tab="account"
                    class="nav-link position-relative rounded-0 d-flex align-items-center justify-content-center bg-transparent fs-3 py-4 active"
                    id="pills-account-tab" data-bs-toggle="pill" data-bs-target="#pills-account" type="button" role="tab"
                    aria-controls="pills-account" aria-selected="false" tabindex="-1">
                    <i class="ti ti-user-circle me-2 fs-6"></i>
                    <span class="d-none d-md-block">Account Settings</span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button data-tab="social"
                    class="nav-link position-relative rounded-0 d-flex align-items-center justify-content-center bg-transparent fs-3 py-4"
                    id="pills-social-tab" data-bs-toggle="pill" data-bs-target="#pills-social" type="button" role="tab"
                    aria-controls="pills-social" aria-selected="true">
                    <i class="ti ti-brand-wechat me-2 fs-6"></i>
                    <span class="d-none d-md-block">
                        Social Links
                    </span>
                </button>
            </li>
        </ul>
        <div class="card-body">
            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade active show" id="pills-account" role="tabpanel"
                    aria-labelledby="pills-account-tab" tabindex="0">
                    <div class="row">
                        <div class="col-lg-6 d-flex align-items-stretch">
                            <div class="card w-100 position-relative overflow-hidden">
                                <div class="card-body p-4">
                                    <h5 class="card-title fw-semibold">Change Profile</h5>
                                    <p class="card-subtitle mb-4">Change your profile picture from here</p>
                                    <div class="text-center">

                                        <div class="avatar-wrapper">
                                            <img id="profilePreview"
                                                src="{{ asset($user->image == 'no-img.jpg' ? '/admin/assets/dist/images/profile/user-1.jpg' : $user->image) }}"
                                                alt="Profile Image">
                                        </div>

                                        <input type="file" id="profileInput" name="image" hidden
                                            accept="image/png, image/jpeg, image/gif" form="form_profile">

                                        <div class="d-flex align-items-center justify-content-center my-4 gap-3">
                                            <button type="button" class="btn btn-primary" id="uploadBtn">Upload</button>
                                            <button type="button" class="btn btn-outline-danger"
                                                id="resetBtn">Reset</button>
                                        </div>

                                        <p class="mb-0">Allowed JPG, GIF or PNG. Max size of 800K</p>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6 d-flex align-items-stretch">
                            <div class="card w-100 position-relative overflow-hidden">
                                <div class="card-body p-4">
                                    <h5 class="card-title fw-semibold">Change Password</h5>
                                    <p class="card-subtitle mb-4">To change your password please confirm here</p>
                                    <form>
                                        <div class="mb-4">
                                            <label for="current_password" class="form-label fw-semibold">Current
                                                Password</label>
                                            <input type="password" class="form-control" id="current_password"
                                                form="form_profile" name="current_password" placeholder="Current Password">
                                        </div>
                                        <div class="mb-4">
                                            <label for="password" class="form-label fw-semibold">New
                                                Password</label>
                                            <input type="password" class="form-control" id="password"
                                                placeholder="New Password" form="form_profile" name="password">
                                        </div>
                                        <div class="">
                                            <label for="password_confirmation" class="form-label fw-semibold">Confirm
                                                Password</label>
                                            <input type="password" class="form-control" id="password_confirmation"
                                                value="" form="form_profile" name="password_confirmation"
                                                placeholder="Confirm Password">
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="card w-100 position-relative overflow-hidden mb-0">
                                <div class="card-body p-4">
                                    <h5 class="card-title fw-semibold">Personal Details</h5>
                                    <p class="card-subtitle mb-4">To change your personal detail , edit and save from here
                                    </p>
                                    <form action="{{ route('staff.profile.edit.update') }}" method="POST"
                                        enctype="multipart/form-data" id="form_profile">
                                        @csrf
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="mb-4">
                                                    <label for="name" class="form-label fw-semibold">
                                                        Name ( English ) <span class="text-danger">*</span>
                                                    </label>
                                                    <input type="text" class="form-control" name="name"
                                                        placeholder="Name" value="{{ $user->name }}">
                                                </div>
                                                <div class="mb-4">
                                                    <label for="khmer_name" class="form-label fw-semibold">
                                                        Name ( Khmer )
                                                    </label>
                                                    <input type="text" class="form-control" name="khmer_name"
                                                        placeholder="Khmer Name" value="{{ $user->khmer_name }}">
                                                </div>
                                                <div class="mb-4">
                                                    <label for="email" class="form-label fw-semibold">
                                                        Email <span class="text-danger">*</span>
                                                    </label>
                                                    <input type="email" class="form-control" name="email"
                                                        placeholder="Email" value="{{ $user->email }}">
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="mb-4">
                                                    <label for="gender" class="form-label fw-semibold">
                                                        Gender <span class="text-danger">*</span>
                                                    </label>
                                                    <select class="form-select" name="gender">
                                                        <option value="">Select Gender</option>
                                                        <option value="male" @selected($user->gender == 'male')>Male
                                                        </option>
                                                        <option value="female" @selected($user->gender == 'female')>Female
                                                        </option>
                                                    </select>
                                                </div>
                                                <div class="mb-4">
                                                    <label for="bio" class="form-label fw-semibold">
                                                        Bio
                                                    </label>
                                                    <textarea class="form-control" name="bio" placeholder="Bio" rows="5">{{ $user->bio }}</textarea>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="d-flex align-items-center justify-content-end mt-4 gap-3">
                                                    <button class="btn btn-primary">Save</button>
                                                    <button class="btn btn-light-danger text-danger">Cancel</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="pills-social" role="tabpanel" aria-labelledby="pills-social-tab"
                    tabindex="0">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h5>
                                        <span class="card-title fw-semibold">Social Links</span>
                                    </h5>
                                    <p class="card-subtitle mb-0">
                                        To change your social profile link , edit and save from here
                                    </p>
                                </div>
                                <form class="form-horizontal r-separator"
                                    action="{{ route('staff.social.profile.update') }}" method="POST">
                                    @csrf
                                    <div class="card-body">
                                        <div class="form-group mb-3 row pb-3">
                                            <label for="x" class="col-sm-3 text-end control-label col-form-label">
                                                Twitter
                                            </label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="x" name="x"
                                                    placeholder="http://" value="{{ $user->x }}">
                                            </div>
                                        </div>
                                        <div class="form-group mb-3 row pb-3">
                                            <label for="facebook"
                                                class="col-sm-3 text-end control-label col-form-label">Facebook</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="facebook"
                                                    name="facebook" placeholder="http://" value="{{ $user->facebook }}">
                                            </div>
                                        </div>
                                        {{-- linkedin --}}
                                        <div class="form-group mb-3 row pb-3">
                                            <label for="linkedin"
                                                class="col-sm-3 text-end control-label col-form-label">Linkedin</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="linkedin"
                                                    name="linkedin" placeholder="http://" value="{{ $user->linkedin }}">
                                            </div>
                                        </div>
                                        {{-- website --}}
                                        <div class="form-group mb-3 row pb-3">
                                            <label for="website"
                                                class="col-sm-3 text-end control-label col-form-label">Website</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="website" name="website"
                                                    placeholder="http://" value="{{ $user->website }}">
                                            </div>
                                        </div>
                                        {{-- github --}}
                                        <div class="form-group mb-3 row pb-3">
                                            <label for="github"
                                                class="col-sm-3 text-end control-label col-form-label">Github</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="github" name="github"
                                                    placeholder="http://" value="{{ $user->github }}">
                                            </div>
                                        </div>
                                        {{-- instagram --}}
                                        <div class="form-group mb-3 row pb-3">
                                            <label for="instagram"
                                                class="col-sm-3 text-end control-label col-form-label">Instagram</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="instagram"
                                                    name="instagram" placeholder="http://"
                                                    value="{{ $user->instagram }}">
                                            </div>
                                        </div>
                                        {{-- youtube --}}
                                        <div class="form-group mb-3 row pb-3">
                                            <label for="youtube"
                                                class="col-sm-3 text-end control-label col-form-label">Youtube</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="youtube" name="youtube"
                                                    placeholder="http://" value="{{ $user->youtube }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="p-3 border-top">
                                        <div class="form-group text-end">
                                            <button type="submit"
                                                class="btn btn-info rounded-pill px-4 waves-effect waves-light">
                                                Save
                                            </button>
                                            <button type="submit"
                                                class="btn btn-dark rounded-pill px-4 waves-effect waves-light">
                                                Cancel
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
@endsection
@push('scripts')
    <script>
        const STORAGE_KEY = "profile_active_tab";

        document.addEventListener("DOMContentLoaded", () => {

            const activeTab = localStorage.getItem(STORAGE_KEY);

            if (activeTab) {
                const tab = document.querySelector(`#${activeTab}-tab`);
                if (tab) new bootstrap.Tab(tab).show();
            }

            document.querySelectorAll('button[data-bs-toggle="pill"]').forEach(tab => {
                tab.addEventListener("shown.bs.tab", function(e) {
                    localStorage.setItem(STORAGE_KEY, e.target.id.replace('-tab', ''));
                });
            });

        });


        document.addEventListener("DOMContentLoaded", function() {

            const uploadBtn = document.getElementById("uploadBtn");
            const resetBtn = document.getElementById("resetBtn");
            const fileInput = document.getElementById("profileInput");
            const preview = document.getElementById("profilePreview");

            const defaultImage = preview.src;

            // Trigger file input
            uploadBtn.addEventListener("click", function() {
                fileInput.click();
            });

            // Preview selected image
            fileInput.addEventListener("change", function() {

                const file = this.files[0];

                if (!file) return;

                // // File size validation (900KB)
                // if (file.size > 900 * 1024) {
                //     alert("File size must be less than 800KB.");
                //     fileInput.value = "";
                //     return;
                // }

                // File type validation
                const allowedTypes = ["image/jpeg", "image/png", "image/gif"];

                if (!allowedTypes.includes(file.type)) {
                    alert("Only JPG, PNG, or GIF files are allowed.");
                    fileInput.value = "";
                    return;
                }

                // Preview image
                const reader = new FileReader();

                reader.onload = function(e) {
                    preview.src = e.target.result;
                };

                reader.readAsDataURL(file);
            });

            // Reset image
            resetBtn.addEventListener("click", function() {
                fileInput.value = "";
                preview.src = defaultImage;
            });

        });
    </script>
@endpush
