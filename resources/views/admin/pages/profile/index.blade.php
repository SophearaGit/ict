@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.css" />
    <style>
        /* The theme's global `img { max-width:100%; height:auto }` breaks Croppie's
           internal image positioning/zoom math. Reset it inside the crop widget only. */
        #croppie-container img {
            max-width: none !important;
            height: auto !important;
        }

        #croppie-container .cr-viewport {
            border-color: rgba(255, 255, 255, .85);
        }

        /* Lighter placeholder text on this page's form fields.
           Opacity-based (rather than a fixed gray) so it lightens correctly
           against both the light theme and the dark-mode background. */
        #profile-details .form-control::placeholder,
        #security .form-control::placeholder,
        #social-profile .form-control::placeholder {
            opacity: .45;
        }

        /* Avatar hover-to-upload */
        #avatar-upload-trigger {
            position: relative;
            display: inline-block;
            cursor: pointer;
            border-radius: 50%;
        }

        #avatar-upload-trigger .avatar-hover-overlay {
            position: absolute;
            inset: 0;
            border-radius: 50%;
            background: rgba(0, 0, 0, .55);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity .15s ease-in-out;
            font-size: 1.1rem;
        }

        #avatar-upload-trigger:hover .avatar-hover-overlay {
            opacity: 1;
        }
    </style>
@endpush

@extends('admin.layouts.master')

@section('title', 'My Profile')

@section('content')
    <main>
        <section class="pt-4 pb-5">
            <div class="container-fluid">

                {{-- User info / hero --}}
                <div class="row align-items-center">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-12">
                        <div class="rounded-top"
                            style="background: linear-gradient(135deg, var(--gk-primary), var(--gk-info)); height: 100px">
                        </div>
                        <div class="card px-4 pt-2 pb-4 shadow-sm rounded-top-0 rounded-bottom-0 rounded-bottom-md-2">
                            <div class="d-flex align-items-end justify-content-between flex-wrap">
                                <div class="d-flex align-items-center">
                                    <div class="me-2 position-relative d-flex justify-content-end align-items-end mt-n5">
                                        <img src="{{ $personalDetails->image ? asset($personalDetails->image) : asset('admin/assets/dist/images/admin/default-avatar.jpg') }}"
                                            id="img-uploaded" class="avatar-xl rounded-circle border border-4 border-white"
                                            alt="avatar" />
                                    </div>
                                    <div class="lh-1">
                                        <h2 class="mb-0">{{ $personalDetails->name }}</h2>
                                        <p class="mb-0 d-block">{{ $personalDetails->email }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Content --}}
                <div class="row mt-0 mt-md-4">
                    <div class="col-lg-3 col-md-4 col-12">
                        {{-- Side navbar / tab triggers --}}
                        <nav class="navbar navbar-expand-md shadow-sm mb-4 mb-lg-0 sidenav">
                            <a class="d-xl-none d-lg-none d-md-none text-inherit fw-bold" href="#">Menu</a>
                            <button class="navbar-toggler d-md-none icon-shape icon-sm rounded bg-primary text-light"
                                type="button" data-bs-toggle="collapse" data-bs-target="#sidenav" aria-controls="sidenav"
                                aria-expanded="false" aria-label="Toggle navigation">
                                <span class="fe fe-menu"></span>
                            </button>
                            <div class="collapse navbar-collapse" id="sidenav">
                                <div class="navbar-nav flex-column">
                                    <span class="navbar-header">Account Settings</span>
                                    <ul class="nav flex-column list-unstyled ms-n2 mb-0" id="profileTabNav" role="tablist">
                                        <li
                                            class="nav-item {{ $errors->has('current_password') || $errors->has('password') ? '' : 'active' }}">
                                            <a class="nav-link {{ $errors->has('current_password') || $errors->has('password') ? '' : 'active' }}"
                                                href="#profile-details" data-bs-toggle="pill"
                                                data-bs-target="#profile-details" role="tab"
                                                aria-controls="profile-details"
                                                aria-selected="{{ $errors->has('current_password') || $errors->has('password') ? 'false' : 'true' }}">
                                                <i class="fe fe-settings nav-icon"></i>
                                                Edit Profile
                                            </a>
                                        </li>
                                        <li
                                            class="nav-item {{ $errors->has('current_password') || $errors->has('password') ? 'active' : '' }}">
                                            <a class="nav-link {{ $errors->has('current_password') || $errors->has('password') ? 'active' : '' }}"
                                                href="#security" data-bs-toggle="pill" data-bs-target="#security"
                                                role="tab" aria-controls="security"
                                                aria-selected="{{ $errors->has('current_password') || $errors->has('password') ? 'true' : 'false' }}">
                                                <i class="fe fe-lock nav-icon"></i>
                                                Security
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="#social-profile" data-bs-toggle="pill"
                                                data-bs-target="#social-profile" role="tab"
                                                aria-controls="social-profile" aria-selected="false">
                                                <i class="fe fe-share-2 nav-icon"></i>
                                                Social Profiles
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <hr class="my-2">
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="#"
                                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                                <i class="fe fe-power nav-icon"></i>
                                                Sign Out
                                            </a>
                                            <form id="logout-form" action="{{ route('admin.logout') }}" method="POST"
                                                class="d-none">
                                                @csrf
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </nav>
                    </div>

                    <div class="col-lg-9 col-md-8 col-12">
                        <div class="tab-content" id="profileTabContent">

                            {{-- ============ PROFILE DETAILS TAB ============ --}}
                            <div class="tab-pane fade {{ $errors->has('current_password') || $errors->has('password') ? '' : 'show active' }}"
                                id="profile-details" role="tabpanel">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="mb-0">Profile Details</h3>
                                        <p class="mb-0">You have full control to manage your own account setting.</p>
                                    </div>
                                    <div class="card-body">
                                        <form action="{{ route('admin.profile.update') }}" method="POST"
                                            enctype="multipart/form-data" novalidate>
                                            @csrf
                                            <input type="hidden" name="old_image" value="{{ $personalDetails->image }}">

                                            <div class="d-lg-flex align-items-center justify-content-between">
                                                <div class="d-flex align-items-center mb-4 mb-lg-0">
                                                    <div id="avatar-upload-trigger" title="Click to change photo">
                                                        <img src="{{ $personalDetails->image ? asset($personalDetails->image) : asset('admin/assets/dist/images/admin/default-avatar.jpg') }}"
                                                            id="img-preview" class="avatar-xl rounded-circle"
                                                            alt="avatar" />
                                                        <div class="avatar-hover-overlay">
                                                            <i class="fe fe-camera"></i>
                                                        </div>
                                                    </div>
                                                    <div class="ms-3">
                                                        <h4 class="mb-0">Your avatar</h4>
                                                        <p class="mb-0">PNG or JPG, max 50MB. Hover and click to change.
                                                        </p>
                                                        @error('image')
                                                            <p class="mb-0 text-danger small">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <input type="file" id="avatarSource" class="d-none"
                                                    accept="image/png,image/jpeg,image/jpg">
                                                {{-- The real submitted file input. Croppie writes the cropped image into this one. --}}
                                                <input type="file" name="image" id="image" class="d-none"
                                                    accept="image/png,image/jpeg,image/jpg">
                                            </div>

                                            <hr class="my-5" />

                                            <div>
                                                <h4 class="mb-0">Personal Details</h4>
                                                <p class="mb-4">Edit your personal information.</p>

                                                <div class="row gx-3">
                                                    <div class="mb-3 col-12 col-md-6">
                                                        <label class="form-label" for="name">Full Name</label>
                                                        <input type="text" id="name" name="name"
                                                            class="form-control @error('name') is-invalid @enderror"
                                                            placeholder="Full Name"
                                                            value="{{ old('name', $personalDetails->name) }}" required />
                                                        @error('name')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @else
                                                            <div class="invalid-feedback">Please enter your name.</div>
                                                        @enderror
                                                    </div>

                                                    <div class="mb-3 col-12 col-md-6">
                                                        <label class="form-label" for="email">Email</label>
                                                        <input type="email" id="email" name="email"
                                                            class="form-control @error('email') is-invalid @enderror"
                                                            placeholder="Email"
                                                            value="{{ old('email', $personalDetails->email) }}"
                                                            required />
                                                        @error('email')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @else
                                                            <div class="invalid-feedback">Please enter a valid email.</div>
                                                        @enderror
                                                    </div>

                                                    <div class="mb-3 col-12">
                                                        <label class="form-label" for="bio">Bio</label>
                                                        <textarea id="bio" name="bio" rows="4" maxlength="500"
                                                            class="form-control @error('bio') is-invalid @enderror" placeholder="A short description about yourself">{{ old('bio', $personalDetails->bio) }}</textarea>
                                                        @error('bio')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    <div class="col-12">
                                                        <button class="btn btn-primary" type="submit">Update
                                                            Profile</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            {{-- ============ SECURITY TAB ============ --}}
                            <div class="tab-pane fade {{ $errors->has('current_password') || $errors->has('password') ? 'show active' : '' }}"
                                id="security" role="tabpanel">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="mb-0">Security</h3>
                                        <p class="mb-0">Update your password to keep your account secure.</p>
                                    </div>
                                    <div class="card-body">
                                        <form action="{{ route('admin.profile.password.update') }}" method="POST"
                                            novalidate>
                                            @csrf

                                            <div class="row gx-3">
                                                <div class="mb-3 col-12">
                                                    <label class="form-label" for="current_password">Current
                                                        Password</label>
                                                    <input type="password" id="current_password" name="current_password"
                                                        class="form-control @error('current_password') is-invalid @enderror"
                                                        placeholder="Current Password" required />
                                                    @error('current_password')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @else
                                                        <div class="invalid-feedback">Please enter your current password.</div>
                                                    @enderror
                                                </div>

                                                <div class="mb-3 col-12 col-md-6">
                                                    <label class="form-label" for="password">New Password</label>
                                                    <input type="password" id="password" name="password"
                                                        class="form-control @error('password') is-invalid @enderror"
                                                        placeholder="New Password" required />
                                                    @error('password')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @else
                                                        <div class="invalid-feedback">Please enter a new password (min 8
                                                            characters).</div>
                                                    @enderror
                                                </div>

                                                <div class="mb-3 col-12 col-md-6">
                                                    <label class="form-label" for="password_confirmation">Confirm New
                                                        Password</label>
                                                    <input type="password" id="password_confirmation"
                                                        name="password_confirmation" class="form-control"
                                                        placeholder="Confirm New Password" required />
                                                    <div class="invalid-feedback">Please confirm your new password.</div>
                                                </div>

                                                <div class="col-12">
                                                    <button class="btn btn-primary" type="submit">Update
                                                        Password</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            {{-- ============ SOCIAL PROFILES TAB ============ --}}
                            <div class="tab-pane fade" id="social-profile" role="tabpanel">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="mb-0">Social Profiles</h3>
                                        <p class="mb-0">Add links to your social accounts.</p>
                                    </div>
                                    <div class="card-body">
                                        {{--
                                    Assumption: admins table has nullable string columns
                                    facebook, twitter, linkedin, instagram, youtube, website.
                                    Route admin.profile.social.update needs to be added — see notes below.
                                --}}
                                        <form action="{{ route('admin.profile.social.update') }}" method="POST"
                                            novalidate>
                                            @csrf

                                            <div class="row gx-3">
                                                <div class="mb-3 col-12 col-md-6">
                                                    <label class="form-label" for="facebook"><i
                                                            class="fe fe-facebook me-1"></i> Facebook</label>
                                                    <input type="url" id="facebook" name="facebook"
                                                        class="form-control @error('facebook') is-invalid @enderror"
                                                        placeholder="https://facebook.com/yourname"
                                                        value="{{ old('facebook', $personalDetails->facebook) }}" />
                                                    @error('facebook')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="mb-3 col-12 col-md-6">
                                                    <label class="form-label" for="twitter"><i
                                                            class="fe fe-twitter me-1"></i> Twitter / X</label>
                                                    <input type="url" id="twitter" name="twitter"
                                                        class="form-control @error('twitter') is-invalid @enderror"
                                                        placeholder="https://x.com/yourname"
                                                        value="{{ old('twitter', $personalDetails->twitter) }}" />
                                                    @error('twitter')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="mb-3 col-12 col-md-6">
                                                    <label class="form-label" for="linkedin"><i
                                                            class="fe fe-linkedin me-1"></i> LinkedIn</label>
                                                    <input type="url" id="linkedin" name="linkedin"
                                                        class="form-control @error('linkedin') is-invalid @enderror"
                                                        placeholder="https://linkedin.com/in/yourname"
                                                        value="{{ old('linkedin', $personalDetails->linkedin) }}" />
                                                    @error('linkedin')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="mb-3 col-12 col-md-6">
                                                    <label class="form-label" for="instagram"><i
                                                            class="fe fe-instagram me-1"></i> Instagram</label>
                                                    <input type="url" id="instagram" name="instagram"
                                                        class="form-control @error('instagram') is-invalid @enderror"
                                                        placeholder="https://instagram.com/yourname"
                                                        value="{{ old('instagram', $personalDetails->instagram) }}" />
                                                    @error('instagram')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="mb-3 col-12 col-md-6">
                                                    <label class="form-label" for="youtube"><i
                                                            class="fe fe-youtube me-1"></i> YouTube</label>
                                                    <input type="url" id="youtube" name="youtube"
                                                        class="form-control @error('youtube') is-invalid @enderror"
                                                        placeholder="https://youtube.com/@yourname"
                                                        value="{{ old('youtube', $personalDetails->youtube) }}" />
                                                    @error('youtube')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="mb-3 col-12 col-md-6">
                                                    <label class="form-label" for="website"><i
                                                            class="fe fe-globe me-1"></i> Website</label>
                                                    <input type="url" id="website" name="website"
                                                        class="form-control @error('website') is-invalid @enderror"
                                                        placeholder="https://yourwebsite.com"
                                                        value="{{ old('website', $personalDetails->website) }}" />
                                                    @error('website')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="col-12">
                                                    <button class="btn btn-primary" type="submit">Save Social
                                                        Links</button>
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
        </section>
    </main>

    {{-- ============ AVATAR CROP MODAL ============ --}}
    <div class="modal fade" id="cropModal" tabindex="-1" aria-labelledby="cropModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cropModalLabel">Crop your photo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="croppie-container" style="width: 100%; height: 350px;"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" id="btn-save-crop" class="btn btn-primary">Save photo</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const TAB_STORAGE_KEY = 'admin.profile.activeTab';

            function syncActiveLi(triggerEl) {
                document.querySelectorAll('#profileTabNav .nav-item').forEach(function(li) {
                    li.classList.remove('active');
                });
                var activeLi = triggerEl.closest('.nav-item');
                if (activeLi) activeLi.classList.add('active');
            }

            // Keep the parent <li class="nav-item"> in sync with whichever tab
            // Bootstrap actually activates. Bootstrap's Tab component only toggles
            // the "active" class on the trigger <a> itself, not its parent <li> —
            // but this theme's highlight styling is keyed off .nav-item.active.
            // Also persist the active tab so it survives the next page load.
            // IMPORTANT: these listeners must be attached before we restore the
            // saved tab below, otherwise the very first (restored) tab switch fires
            // with nothing listening yet and the sidebar highlight is left stuck.
            document.querySelectorAll('#profileTabNav [data-bs-toggle="pill"]').forEach(function(triggerEl) {
                triggerEl.addEventListener('shown.bs.tab', function(event) {
                    syncActiveLi(event.target);
                    localStorage.setItem(TAB_STORAGE_KEY, event.target.getAttribute(
                        'data-bs-target'));
                });
            });

            // Restore whichever tab was open before this page load. Form submits on
            // this page redirect (full navigation, no URL hash survives), so we use
            // localStorage instead of a hash to remember the tab across that reload.
            // A failed password validation always wins over the stored tab, since
            // the user just submitted that form and needs to see the errors.
            const hasPasswordErrors = @json($errors->has('current_password') || $errors->has('password'));
            const restoreTarget = hasPasswordErrors ? '#security' : localStorage.getItem(TAB_STORAGE_KEY);

            if (restoreTarget) {
                const triggerEl = document.querySelector('#profileTabNav [data-bs-target="' + restoreTarget + '"]');
                if (triggerEl) {
                    bootstrap.Tab.getOrCreateInstance(triggerEl).show();
                    // Belt-and-braces: sync immediately too, in case shown.bs.tab
                    // doesn't fire synchronously in some browsers/Bootstrap builds.
                    syncActiveLi(triggerEl);
                }
            }

            const chooseTrigger = document.getElementById('avatar-upload-trigger');
            const sourceInput = document.getElementById('avatarSource');
            const realInput = document.getElementById('image');
            const saveCropBtn = document.getElementById('btn-save-crop');
            const cropModalEl = document.getElementById('cropModal');
            const cropModal = new bootstrap.Modal(cropModalEl);

            let croppieInstance = null;

            // Step 1: click the avatar (hover overlay signals it's clickable) -> open native file picker
            chooseTrigger.addEventListener('click', function() {
                sourceInput.click();
            });

            const container = document.getElementById('croppie-container');
            let pendingImageUrl = null;

            // Step 2: file chosen -> read it, then open the modal
            sourceInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (!file) return;

                const reader = new FileReader();
                reader.onload = function(ev) {
                    pendingImageUrl = ev.target.result;
                    cropModal.show();
                };
                reader.readAsDataURL(file);
            });

            // Step 2b: only build Croppie once the modal has finished its show
            // transition — the container has 0 width/height before that, which is
            // why the image wouldn't render (just the empty circle boundary).
            cropModalEl.addEventListener('shown.bs.modal', function() {
                if (!pendingImageUrl) return;

                if (croppieInstance) {
                    croppieInstance.destroy();
                    croppieInstance = null;
                }

                croppieInstance = new Croppie(container, {
                    viewport: {
                        width: 200,
                        height: 200,
                        type: 'circle'
                    },
                    boundary: {
                        width: 300,
                        height: 300
                    },
                    enableExif: true
                });
                croppieInstance.bind({
                    url: pendingImageUrl
                });
            });

            // Clean up when the modal closes without saving, and let the user
            // reselect the same file again (change event won't fire on identical value otherwise)
            cropModalEl.addEventListener('hidden.bs.modal', function() {
                if (croppieInstance) {
                    croppieInstance.destroy();
                    croppieInstance = null;
                }
                pendingImageUrl = null;
                sourceInput.value = '';
            });

            // Step 3: confirm crop -> get blob, inject into the real file input, update previews
            saveCropBtn.addEventListener('click', function() {
                if (!croppieInstance) return;

                croppieInstance.result({
                    type: 'blob',
                    size: 'viewport',
                    format: 'jpeg',
                    quality: 0.9
                }).then(function(blob) {
                    const croppedFile = new File([blob], 'avatar.jpg', {
                        type: 'image/jpeg'
                    });

                    // Inject the cropped file into the real <input type="file" name="image">
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(croppedFile);
                    realInput.files = dataTransfer.files;

                    // Update previews
                    const url = URL.createObjectURL(blob);
                    document.getElementById('img-uploaded').src = url;
                    document.getElementById('img-preview').src = url;

                    cropModal.hide();
                });
            });
        });
    </script>
@endpush
