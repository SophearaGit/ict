@extends('frontend.staff.layout.master')
@section('page_title', isset($page_title) ? $page_title : 'Page Title Here')
@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.css">
    <style>
        .avatar-wrapper {
            position: relative;
            width: 150px;
            height: 150px;
            margin: auto;
            border-radius: 50%;
            overflow: hidden;
            border: 4px solid #f1f1f1;
            box-shadow: 0 2px 10px rgba(0, 0, 0, .08);
            cursor: pointer;
        }

        .avatar-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: filter .2s ease;
        }

        .avatar-wrapper .avatar-overlay {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(0, 0, 0, .45);
            color: #fff;
            font-size: 22px;
            opacity: 0;
            transition: opacity .2s ease;
        }

        .avatar-wrapper:hover .avatar-overlay {
            opacity: 1;
        }

        .avatar-wrapper:hover img {
            filter: brightness(.9);
        }

        .cropper-modal-preview {
            max-height: 420px;
            width: 100%;
            display: block;
        }

        .section-heading {
            display: flex;
            align-items: center;
            gap: .5rem;
            font-size: .95rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .03em;
            color: #7c8fac;
            margin-bottom: 1rem;
            padding-bottom: .5rem;
            border-bottom: 1px dashed #e5eaef;
        }

        .section-heading i {
            font-size: 1.1rem;
        }

        .field-block {
            margin-bottom: 1.75rem;
        }

        /* Tag / chip input for "expertise" */
        .tag-input {
            display: flex;
            flex-wrap: wrap;
            gap: .4rem;
            padding: .5rem;
            border: 1px solid #dfe5ef;
            border-radius: .5rem;
            min-height: 46px;
            align-items: center;
        }

        .tag-input:focus-within {
            border-color: #5d87ff;
            box-shadow: 0 0 0 .15rem rgba(93, 135, 255, .15);
        }

        .tag-chip {
            display: inline-flex;
            align-items: center;
            gap: .35rem;
            background: #ecf2ff;
            color: #5d87ff;
            border-radius: 999px;
            padding: .2rem .3rem .2rem .7rem;
            font-size: .82rem;
            font-weight: 500;
        }

        .tag-chip button {
            border: none;
            background: transparent;
            color: #5d87ff;
            line-height: 1;
            padding: 0 .3rem;
            font-size: 1rem;
        }

        .tag-input input {
            border: none;
            outline: none;
            flex: 1;
            min-width: 120px;
            padding: .3rem;
            font-size: .875rem;
        }

        .doc-drop {
            border: 1.5px dashed #dfe5ef;
            border-radius: .6rem;
            padding: 1.25rem;
            text-align: center;
            cursor: pointer;
            transition: border-color .2s ease, background-color .2s ease;
        }

        .doc-drop:hover {
            border-color: #5d87ff;
            background-color: #f8faff;
        }

        .doc-current {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #f8f9fa;
            border-radius: .5rem;
            padding: .6rem .9rem;
            margin-top: .75rem;
            font-size: .85rem;
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
                    id="pills-account-tab" data-bs-toggle="pill" data-bs-target="#pills-account" type="button"
                    role="tab" aria-controls="pills-account" aria-selected="false" tabindex="-1">
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
                                        <div class="avatar-wrapper" id="avatarWrapper">
                                            <img id="profilePreview"
                                                src="{{ asset($user->image == 'no-img.jpg' ? '/admin/assets/dist/images/profile/user-1.jpg' : $user->image) }}"
                                                alt="Profile Image">
                                            <div class="avatar-overlay"><i class="ti ti-camera"></i></div>
                                        </div>
                                        <input type="file" id="profileInput" name="image" hidden
                                            accept="image/png, image/jpeg, image/gif" form="form_profile">
                                        <div class="d-flex align-items-center justify-content-center my-4 gap-3">
                                            <button type="button" class="btn btn-primary" id="uploadBtn">
                                                <i class="ti ti-upload me-1"></i> Upload
                                            </button>
                                            <button type="button" class="btn btn-outline-danger" id="resetBtn">
                                                <i class="ti ti-refresh me-1"></i> Reset
                                            </button>
                                        </div>
                                        <p class="mb-0 text-muted fs-2">Allowed JPG, GIF or PNG. Max size of 800K</p>
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
                                        <div class="section-heading"><i class="ti ti-id-badge"></i> Basic Information
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="field-block">
                                                    <label for="name" class="form-label fw-semibold">
                                                        Name ( English ) <span class="text-danger">*</span>
                                                    </label>
                                                    <input type="text" class="form-control" name="name"
                                                        id="name" placeholder="Name" value="{{ $user->name }}">
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="field-block">
                                                    <label for="khmer_name" class="form-label fw-semibold">
                                                        Name ( Khmer )
                                                    </label>
                                                    <input type="text" class="form-control" name="khmer_name"
                                                        id="khmer_name" placeholder="Khmer Name"
                                                        value="{{ $user->khmer_name }}">
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="field-block">
                                                    <label for="email" class="form-label fw-semibold">
                                                        Email <span class="text-danger">*</span>
                                                    </label>
                                                    <input type="email" class="form-control" name="email"
                                                        id="email" placeholder="Email" value="{{ $user->email }}">
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="field-block">
                                                    <label for="gender" class="form-label fw-semibold">
                                                        Gender <span class="text-danger">*</span>
                                                    </label>
                                                    <select class="form-select" name="gender" id="gender">
                                                        <option value="">Select Gender</option>
                                                        <option value="male" @selected($user->gender == 'male')>Male
                                                        </option>
                                                        <option value="female" @selected($user->gender == 'female')>Female
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="field-block">
                                                    <label for="dob" class="form-label fw-semibold">
                                                        Date of Birth
                                                    </label>
                                                    <input type="date" class="form-control" name="dob"
                                                        id="dob" value="{{ $user->dob }}">
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="field-block">
                                                    <label for="nationality" class="form-label fw-semibold">
                                                        Nationality
                                                    </label>
                                                    <input type="text" class="form-control" name="nationality"
                                                        id="nationality" placeholder="e.g. Cambodian"
                                                        value="{{ $user->nationality }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="section-heading"><i class="ti ti-briefcase"></i> Professional
                                            Information</div>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="field-block">
                                                    <label for="designation" class="form-label fw-semibold">
                                                        Designation
                                                    </label>
                                                    <input type="text" class="form-control" name="designation"
                                                        id="designation" placeholder="e.g. Senior Instructor"
                                                        value="{{ $user->designation }}">
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="field-block">
                                                    <label for="headline" class="form-label fw-semibold">
                                                        Headline
                                                    </label>
                                                    <input type="text" class="form-control" name="headline"
                                                        id="headline" placeholder="A short professional tagline"
                                                        value="{{ $user->headline }}">
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="field-block">
                                                    <label for="expertiseInput" class="form-label fw-semibold">
                                                        Areas of Expertise
                                                    </label>
                                                    <div class="tag-input" id="expertiseTagInput">
                                                        <input type="text" id="expertiseInput"
                                                            placeholder="Type a skill and press Enter">
                                                    </div>
                                                    <div id="expertiseHiddenInputs"></div>
                                                    <div class="form-text">Press Enter or comma to add a tag.</div>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="field-block mb-0">
                                                    <label for="bio" class="form-label fw-semibold">
                                                        Bio
                                                    </label>
                                                    <textarea class="form-control" name="bio" id="bio" placeholder="Bio" rows="4">{{ $user->bio }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="section-heading"><i class="ti ti-phone"></i> Contact Information
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="field-block">
                                                    <label for="phone" class="form-label fw-semibold">
                                                        Phone
                                                    </label>
                                                    <input type="tel" class="form-control" name="phone"
                                                        id="phone" placeholder="Phone Number"
                                                        value="{{ $user->phone }}">
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="field-block">
                                                    <label for="alternate_phone" class="form-label fw-semibold">
                                                        Alternate Phone
                                                    </label>
                                                    <input type="tel" class="form-control" name="alternate_phone"
                                                        id="alternate_phone" placeholder="Alternate Phone Number"
                                                        value="{{ $user->alternate_phone }}">
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="field-block mb-0">
                                                    <label for="location" class="form-label fw-semibold">
                                                        Location
                                                    </label>
                                                    <input type="text" class="form-control" name="location"
                                                        id="location" placeholder="City, Country"
                                                        value="{{ $user->location }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="section-heading"><i class="ti ti-file-certificate"></i> Verification
                                            Document</div>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="field-block mb-0">
                                                    <label class="form-label fw-semibold d-block">
                                                        ID Card / Certificate
                                                    </label>
                                                    <div class="doc-drop" id="docDrop">
                                                        <i class="ti ti-cloud-upload fs-4 mb-1 d-block"></i>
                                                        <span class="fw-semibold">Click to upload a document</span>
                                                        <div class="text-muted fs-2">PDF, JPG or PNG up to 5MB</div>
                                                    </div>
                                                    <input type="file" id="documentInput" name="document" hidden
                                                        accept="image/png, image/jpeg, application/pdf">
                                                    @if ($user->document)
                                                        <div class="doc-current" id="currentDocRow">
                                                            <span><i class="ti ti-file-text me-1"></i>
                                                                Current file on record</span>
                                                            <a href="{{ asset($user->document) }}" target="_blank"
                                                                class="btn btn-sm btn-light-primary">View</a>
                                                        </div>
                                                    @endif
                                                    <div class="doc-current d-none" id="newDocRow">
                                                        <span id="newDocName"><i class="ti ti-file-text me-1"></i></span>
                                                        <button type="button" class="btn btn-sm btn-light-danger"
                                                            id="removeDocBtn">Remove</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
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
                                                <i class="ti ti-brand-x fs-5 me-1 align-middle"></i> Twitter / X
                                            </label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="x" name="x"
                                                    placeholder="http://" value="{{ $user->x }}">
                                            </div>
                                        </div>
                                        <div class="form-group mb-3 row pb-3">
                                            <label for="facebook" class="col-sm-3 text-end control-label col-form-label">
                                                <i class="ti ti-brand-facebook fs-5 me-1 align-middle"></i> Facebook
                                            </label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="facebook"
                                                    name="facebook" placeholder="http://" value="{{ $user->facebook }}">
                                            </div>
                                        </div>
                                        {{-- linkedin --}}
                                        <div class="form-group mb-3 row pb-3">
                                            <label for="linkedin" class="col-sm-3 text-end control-label col-form-label">
                                                <i class="ti ti-brand-linkedin fs-5 me-1 align-middle"></i> Linkedin
                                            </label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="linkedin"
                                                    name="linkedin" placeholder="http://" value="{{ $user->linkedin }}">
                                            </div>
                                        </div>
                                        {{-- website --}}
                                        <div class="form-group mb-3 row pb-3">
                                            <label for="website" class="col-sm-3 text-end control-label col-form-label">
                                                <i class="ti ti-world fs-5 me-1 align-middle"></i> Website
                                            </label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="website" name="website"
                                                    placeholder="http://" value="{{ $user->website }}">
                                            </div>
                                        </div>
                                        {{-- github --}}
                                        <div class="form-group mb-3 row pb-3">
                                            <label for="github" class="col-sm-3 text-end control-label col-form-label">
                                                <i class="ti ti-brand-github fs-5 me-1 align-middle"></i> Github
                                            </label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="github" name="github"
                                                    placeholder="http://" value="{{ $user->github }}">
                                            </div>
                                        </div>
                                        {{-- instagram --}}
                                        <div class="form-group mb-3 row pb-3">
                                            <label for="instagram" class="col-sm-3 text-end control-label col-form-label">
                                                <i class="ti ti-brand-instagram fs-5 me-1 align-middle"></i> Instagram
                                            </label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="instagram"
                                                    name="instagram" placeholder="http://"
                                                    value="{{ $user->instagram }}">
                                            </div>
                                        </div>
                                        {{-- telegram --}}
                                        <div class="form-group mb-3 row pb-3">
                                            <label for="telegram" class="col-sm-3 text-end control-label col-form-label">
                                                <i class="ti ti-brand-telegram fs-5 me-1 align-middle"></i> Telegram
                                            </label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="telegram"
                                                    name="telegram" placeholder="http://" value="{{ $user->telegram }}">
                                            </div>
                                        </div>
                                        {{-- tiktok --}}
                                        <div class="form-group mb-3 row pb-3">
                                            <label for="tiktok" class="col-sm-3 text-end control-label col-form-label">
                                                <i class="ti ti-brand-tiktok fs-5 me-1 align-middle"></i> TikTok
                                            </label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="tiktok" name="tiktok"
                                                    placeholder="http://" value="{{ $user->tiktok }}">
                                            </div>
                                        </div>
                                        {{-- youtube --}}
                                        <div class="form-group mb-3 row pb-3">
                                            <label for="youtube" class="col-sm-3 text-end control-label col-form-label">
                                                <i class="ti ti-brand-youtube fs-5 me-1 align-middle"></i> Youtube
                                            </label>
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
    {{-- Cropper Modal --}}
    <div class="modal fade" id="cropModal" tabindex="-1" aria-labelledby="cropModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cropModalLabel">Crop your photo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="cropper-modal-preview">
                        <img id="cropperTarget" src="" alt="Crop preview" style="max-width: 100%;">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-danger" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="cropSaveBtn">
                        <i class="ti ti-crop me-1"></i> Crop & Save
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.js"></script>
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
            const avatarWrapper = document.getElementById("avatarWrapper");
            const resetBtn = document.getElementById("resetBtn");
            const fileInput = document.getElementById("profileInput");
            const preview = document.getElementById("profilePreview");
            const defaultImage = preview.src;
            const cropperTarget = document.getElementById("cropperTarget");
            const cropModalEl = document.getElementById("cropModal");
            const cropModal = new bootstrap.Modal(cropModalEl);
            const cropSaveBtn = document.getElementById("cropSaveBtn");
            let cropper = null;
            const allowedTypes = ["image/jpeg", "image/png", "image/gif"];

            function openFilePicker() {
                fileInput.click();
            }
            uploadBtn.addEventListener("click", openFilePicker);
            avatarWrapper.addEventListener("click", openFilePicker);
            fileInput.addEventListener("change", function() {
                const file = this.files[0];
                if (!file) return;
                if (!allowedTypes.includes(file.type)) {
                    alert("Only JPG, PNG, or GIF files are allowed.");
                    fileInput.value = "";
                    return;
                }
                if (file.size > 800 * 1024) {
                    alert("File size must be less than 800KB.");
                    fileInput.value = "";
                    return;
                }
                const reader = new FileReader();
                reader.onload = function(e) {
                    cropperTarget.src = e.target.result;
                    cropModal.show();
                };
                reader.readAsDataURL(file);
            });
            cropModalEl.addEventListener("shown.bs.modal", function() {
                if (cropper) cropper.destroy();
                cropper = new Cropper(cropperTarget, {
                    aspectRatio: 1,
                    viewMode: 1,
                    autoCropArea: 1,
                    responsive: true,
                    background: false,
                });
            });
            cropModalEl.addEventListener("hidden.bs.modal", function() {
                if (cropper) {
                    cropper.destroy();
                    cropper = null;
                }
                // If the user dismissed without saving, clear the raw file input
                if (!preview.dataset.cropped) {
                    fileInput.value = "";
                }
            });
            cropSaveBtn.addEventListener("click", function() {
                if (!cropper) return;
                const canvas = cropper.getCroppedCanvas({
                    width: 400,
                    height: 400,
                    imageSmoothingQuality: "high",
                });
                canvas.toBlob(function(blob) {
                    const croppedFile = new File([blob], "avatar.png", {
                        type: "image/png"
                    });
                    // Replace the file input's contents with the cropped image
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(croppedFile);
                    fileInput.files = dataTransfer.files;
                    preview.src = canvas.toDataURL("image/png");
                    preview.dataset.cropped = "1";
                    cropModal.hide();
                }, "image/png");
            });
            resetBtn.addEventListener("click", function() {
                fileInput.value = "";
                delete preview.dataset.cropped;
                preview.src = defaultImage;
            });
        });
        // Expertise tag input
        document.addEventListener("DOMContentLoaded", function() {
            const wrapper = document.getElementById("expertiseTagInput");
            const input = document.getElementById("expertiseInput");
            const hiddenWrap = document.getElementById("expertiseHiddenInputs");
            const initial = @json($user->expertise ?? []);
            let tags = Array.isArray(initial) ? initial.filter(Boolean) : [];

            function render() {
                wrapper.querySelectorAll(".tag-chip").forEach(el => el.remove());
                hiddenWrap.innerHTML = "";
                tags.forEach((tag, idx) => {
                    const chip = document.createElement("span");
                    chip.className = "tag-chip";
                    chip.innerHTML =
                        `${tag} <button type="button" data-idx="${idx}" aria-label="Remove">&times;</button>`;
                    wrapper.insertBefore(chip, input);
                    const hidden = document.createElement("input");
                    hidden.type = "hidden";
                    hidden.name = "expertise[]";
                    hidden.value = tag;
                    hidden.setAttribute("form", "form_profile");
                    hiddenWrap.appendChild(hidden);
                });
            }

            function addTag(value) {
                const clean = value.trim().replace(/,+$/, "");
                if (clean && !tags.includes(clean)) {
                    tags.push(clean);
                    render();
                }
                input.value = "";
            }
            input.addEventListener("keydown", function(e) {
                if (e.key === "Enter" || e.key === ",") {
                    e.preventDefault();
                    addTag(input.value);
                } else if (e.key === "Backspace" && input.value === "" && tags.length) {
                    tags.pop();
                    render();
                }
            });
            wrapper.addEventListener("click", function(e) {
                if (e.target.matches("button[data-idx]")) {
                    const idx = parseInt(e.target.getAttribute("data-idx"), 10);
                    tags.splice(idx, 1);
                    render();
                } else if (e.target === wrapper) {
                    input.focus();
                }
            });
            render();
        });
        // Document upload
        document.addEventListener("DOMContentLoaded", function() {
            const docDrop = document.getElementById("docDrop");
            const documentInput = document.getElementById("documentInput");
            const newDocRow = document.getElementById("newDocRow");
            const newDocName = document.getElementById("newDocName");
            const currentDocRow = document.getElementById("currentDocRow");
            const removeDocBtn = document.getElementById("removeDocBtn");
            docDrop.addEventListener("click", () => documentInput.click());
            documentInput.addEventListener("change", function() {
                const file = this.files[0];
                if (!file) return;
                const allowed = ["image/jpeg", "image/png", "application/pdf"];
                if (!allowed.includes(file.type)) {
                    alert("Only PDF, JPG, or PNG files are allowed.");
                    documentInput.value = "";
                    return;
                }
                if (file.size > 5 * 1024 * 1024) {
                    alert("File size must be less than 5MB.");
                    documentInput.value = "";
                    return;
                }
                newDocName.innerHTML = `<i class="ti ti-file-text me-1"></i> ${file.name}`;
                newDocRow.classList.remove("d-none");
                if (currentDocRow) currentDocRow.classList.add("d-none");
            });
            removeDocBtn.addEventListener("click", function() {
                documentInput.value = "";
                newDocRow.classList.add("d-none");
                if (currentDocRow) currentDocRow.classList.remove("d-none");
            });
        });
    </script>
@endpush
