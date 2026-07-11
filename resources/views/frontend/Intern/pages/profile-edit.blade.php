@extends('frontend.Intern.layout.master')
@section('page_title', isset($page_title) ? $page_title : 'Profile Settings')
@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.css">
    <style>
        .profile-avatar-wrap {
            position: relative;
            width: 120px;
            height: 120px;
            margin: 0 auto;
        }

        .profile-avatar-wrap img {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid var(--bs-border-color);
        }

        .social-input-group .input-group-text {
            width: 42px;
            justify-content: center;
        }

        #cropperImageWrap {
            max-height: 420px;
            overflow: hidden;
        }

        #cropperImage {
            display: block;
            max-width: 100%;
        }
    </style>
@endpush
@section('content')
    @php
        // Which tab should be active on load: hidden form_tab field wins on
        // a validation-failed redirect, otherwise whatever the controller
        // flashed after a successful save, otherwise default to profile.
        $activeTab = old('form_tab', session('active_tab', 'profile'));
        $avatarSrc = auth()->user()->image == '' ? '/default-images/user/both.jpg' : auth()->user()->image;
    @endphp
    <div class="page-container">
        <div class="row">
            <div class="col-12">
                <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column">
                    <div class="flex-grow-1">
                        <h4 class="fs-18 text-uppercase fw-bold m-0">Profile Settings</h4>
                    </div>
                </div>
            </div>
        </div>
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <div class="row">

            <!-- Sidebar summary -->
            <div class="col-xl-3">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="profile-avatar-wrap mb-3">
                            <img src="{{ $avatarSrc }}" id="avatarPreview" alt="{{ $user->name }}">
                        </div>
                        <h5 class="mb-1">{{ $user->name }}</h5>
                        <p class="text-muted fs-13 mb-2">{{ $user->khmer_name }}</p>
                        <span class="badge badge-soft-primary text-uppercase">{{ $user->role }}</span>
                        <hr>
                        <ul class="list-unstyled text-start fs-13 mb-0">
                            <li class="d-flex align-items-center mb-2">
                                <i class="ti ti-mail me-2 text-muted"></i> {{ $user->email }}
                            </li>
                            <li class="d-flex align-items-center mb-2">
                                <i class="ti ti-phone me-2 text-muted"></i> {{ $user->phone ?? '—' }}
                            </li>
                            <li class="d-flex align-items-center">
                                <i class="ti ti-map-pin me-2 text-muted"></i> {{ $user->location ?? '—' }}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Main settings -->
            <div class="col-xl-9">
                <div class="card">
                    <div class="card-body">
                        <ul class="nav nav-tabs mb-3" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a href="#tab-profile" data-bs-toggle="tab"
                                    aria-expanded="{{ $activeTab === 'profile' ? 'true' : 'false' }}"
                                    class="nav-link {{ $activeTab === 'profile' ? 'active' : '' }}"
                                    aria-selected="{{ $activeTab === 'profile' ? 'true' : 'false' }}" role="tab"
                                    {{ $activeTab !== 'profile' ? 'tabindex=-1' : '' }}>
                                    <i class="ti ti-user me-1"></i> Profile Info
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a href="#tab-social" data-bs-toggle="tab"
                                    aria-expanded="{{ $activeTab === 'social' ? 'true' : 'false' }}"
                                    class="nav-link {{ $activeTab === 'social' ? 'active' : '' }}"
                                    aria-selected="{{ $activeTab === 'social' ? 'true' : 'false' }}" role="tab"
                                    {{ $activeTab !== 'social' ? 'tabindex=-1' : '' }}>
                                    <i class="ti ti-link me-1"></i> Social Links
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a href="#tab-password" data-bs-toggle="tab"
                                    aria-expanded="{{ $activeTab === 'password' ? 'true' : 'false' }}"
                                    class="nav-link {{ $activeTab === 'password' ? 'active' : '' }}"
                                    aria-selected="{{ $activeTab === 'password' ? 'true' : 'false' }}" role="tab"
                                    {{ $activeTab !== 'password' ? 'tabindex=-1' : '' }}>
                                    <i class="ti ti-lock me-1"></i> Change Password
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content">

                            <!-- PROFILE INFO -->
                            <div class="tab-pane {{ $activeTab === 'profile' ? 'active show' : '' }}" id="tab-profile"
                                role="tabpanel">
                                <form action="{{ route('intern.profile.update') }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="form_tab" value="profile">
                                    <div class="mb-4">
                                        <label class="form-label d-block">Profile Photo</label>
                                        <div class="d-flex align-items-center gap-3">
                                            <img src="{{ $avatarSrc }}" id="photoThumb" class="rounded-circle"
                                                style="width:64px;height:64px;object-fit:cover;">
                                            <div>
                                                <input type="file" id="imagePicker" accept="image/*"
                                                    class="form-control @error('image') is-invalid @enderror">
                                                {{-- The real submitted field: filled by Cropper.js output --}}
                                                <input type="file" name="image" id="image" class="d-none">
                                                @error('image')
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                                <small class="text-muted">JPG or PNG, max 2MB. You'll be able to
                                                    crop it.</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Full Name</label>
                                            <input type="text" name="name" value="{{ old('name', $user->name) }}"
                                                class="form-control @error('name') is-invalid @enderror">
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Khmer Name</label>
                                            <input type="text" name="khmer_name"
                                                value="{{ old('khmer_name', $user->khmer_name) }}"
                                                class="form-control @error('khmer_name') is-invalid @enderror">
                                            @error('khmer_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Email</label>
                                            <input type="email" name="email"
                                                value="{{ old('email', $user->email) }}"
                                                class="form-control @error('email') is-invalid @enderror">
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Phone</label>
                                            <input type="text" name="phone"
                                                value="{{ old('phone', $user->phone) }}"
                                                class="form-control @error('phone') is-invalid @enderror">
                                            @error('phone')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Alternate Phone</label>
                                            <input type="text" name="alternate_phone"
                                                value="{{ old('alternate_phone', $user->alternate_phone) }}"
                                                class="form-control @error('alternate_phone') is-invalid @enderror">
                                            @error('alternate_phone')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Location</label>
                                            <input type="text" name="location"
                                                value="{{ old('location', $user->location) }}"
                                                class="form-control @error('location') is-invalid @enderror">
                                            @error('location')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Gender</label>
                                            <select name="gender"
                                                class="form-select @error('gender') is-invalid @enderror">
                                                <option value="">Select</option>
                                                @foreach (['male' => 'Male', 'female' => 'Female', 'other' => 'Other'] as $val => $label)
                                                    <option value="{{ $val }}"
                                                        {{ old('gender', $user->gender) == $val ? 'selected' : '' }}>
                                                        {{ $label }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('gender')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Date of Birth</label>
                                            <input type="text" name="dob" id="dob"
                                                data-provider="flatpickr" data-date-format="d M Y"
                                                value="{{ old('dob', $user->dob ? \Carbon\Carbon::parse($user->dob)->format('d M Y') : '') }}"
                                                class="form-control @error('dob') is-invalid @enderror">
                                            @error('dob')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Nationality</label>
                                            <input type="text" name="nationality"
                                                value="{{ old('nationality', $user->nationality) }}"
                                                class="form-control @error('nationality') is-invalid @enderror">
                                            @error('nationality')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Headline</label>
                                            <input type="text" name="headline"
                                                value="{{ old('headline', $user->headline) }}"
                                                placeholder="e.g. Web Development Instructor"
                                                class="form-control @error('headline') is-invalid @enderror">
                                            @error('headline')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Document</label>
                                            <input type="file" name="document" id="document"
                                                class="form-control @error('document') is-invalid @enderror">
                                            @error('document')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            @if ($user->document)
                                                <small class="text-muted">
                                                    Current: <a href="{{ $user->document }}" target="_blank">view
                                                        file</a>
                                                </small>
                                            @endif
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">Bio</label>
                                            <textarea name="bio" rows="4" class="form-control @error('bio') is-invalid @enderror"
                                                placeholder="Tell us a bit about yourself...">{{ old('bio', $user->bio) }}</textarea>
                                            @error('bio')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-end mt-4">
                                        <button type="submit" class="btn btn-primary px-4">
                                            <i class="ti ti-device-floppy me-1"></i> Save Changes
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <!-- SOCIAL LINKS -->
                            <div class="tab-pane {{ $activeTab === 'social' ? 'active show' : '' }}" id="tab-social"
                                role="tabpanel">
                                <form action="{{ route('intern.profile.social.update') }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="form_tab" value="social">
                                    <div class="row g-3">
                                        @php
                                            $socials = [
                                                'website' => ['ti-world', 'Website URL'],
                                                'facebook' => ['ti-brand-facebook', 'Facebook URL'],
                                                'x' => ['ti-brand-x', 'X (Twitter) URL'],
                                                'linkedin' => ['ti-brand-linkedin', 'LinkedIn URL'],
                                                'github' => ['ti-brand-github', 'GitHub URL'],
                                                'instagram' => ['ti-brand-instagram', 'Instagram URL'],
                                                'telegram' => ['ti-brand-telegram', 'Telegram Username or URL'],
                                                'tiktok' => ['ti-brand-tiktok', 'TikTok Profile URL'],
                                                'youtube' => ['ti-brand-youtube', 'YouTube URL'],
                                            ];
                                        @endphp
                                        @foreach ($socials as $field => [$icon, $placeholder])
                                            <div class="col-md-6">
                                                <label class="form-label text-capitalize">{{ $field }}</label>
                                                <div class="input-group social-input-group">
                                                    <span class="input-group-text"><i
                                                            class="ti {{ $icon }}"></i></span>
                                                    <input type="url" name="{{ $field }}"
                                                        value="{{ old($field, $user->$field) }}"
                                                        placeholder="{{ $placeholder }}"
                                                        class="form-control @error($field) is-invalid @enderror">
                                                    @error($field)
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="d-flex justify-content-end mt-4">
                                        <button type="submit" class="btn btn-primary px-4">
                                            <i class="ti ti-device-floppy me-1"></i> Save Links
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <!-- CHANGE PASSWORD -->
                            <div class="tab-pane {{ $activeTab === 'password' ? 'active show' : '' }}" id="tab-password"
                                role="tabpanel">
                                <form action="{{ route('intern.profile.password.update') }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="form_tab" value="password">
                                    <div class="row g-3">
                                        <div class="col-md-12">
                                            <label class="form-label">Current Password</label>
                                            <input type="password" name="current_password"
                                                class="form-control @error('current_password') is-invalid @enderror">
                                            @error('current_password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">New Password</label>
                                            <input type="password" name="password"
                                                class="form-control @error('password') is-invalid @enderror">
                                            @error('password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Confirm New Password</label>
                                            <input type="password" name="password_confirmation" class="form-control">
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-end mt-4">
                                        <button type="submit" class="btn btn-primary px-4">
                                            <i class="ti ti-lock me-1"></i> Update Password
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Cropper Modal -->
    <div class="modal fade" id="cropperModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Crop Photo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="cropperImageWrap">
                        <img id="cropperImage" src="">
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <div class="btn-group">
                        <button type="button" class="btn btn-light btn-icon" id="cropRotateLeft">
                            <i class="ti ti-rotate-2"></i>
                        </button>
                        <button type="button" class="btn btn-light btn-icon" id="cropRotateRight">
                            <i class="ti ti-rotate-clockwise-2"></i>
                        </button>
                        <button type="button" class="btn btn-light btn-icon" id="cropZoomIn">
                            <i class="ti ti-zoom-in"></i>
                        </button>
                        <button type="button" class="btn btn-light btn-icon" id="cropZoomOut">
                            <i class="ti ti-zoom-out"></i>
                        </button>
                        <button type="button" class="btn btn-light btn-icon" id="cropReset">
                            <i class="ti ti-refresh"></i>
                        </button>
                    </div>
                    <div>
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" id="cropSaveBtn">
                            <i class="ti ti-check me-1"></i> Save Crop
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // ---- Dynamic tab activation ----
            // Server-rendered active tab already has the correct classes;
            // this just keeps the URL hash in sync so refreshes/deep-links
            // land on the right tab too, and updates it as the user clicks.
            const activeTabFromServer = @json($activeTab);
            if (window.location.hash) {
                const hashTabTrigger = document.querySelector(
                    `.nav-tabs a[href="${window.location.hash}"]`);
                if (hashTabTrigger) {
                    bootstrap.Tab.getOrCreateInstance(hashTabTrigger).show();
                }
            } else if (activeTabFromServer && activeTabFromServer !== 'profile') {
                const el = document.querySelector(`.nav-tabs a[href="#tab-${activeTabFromServer}"]`);
                if (el) bootstrap.Tab.getOrCreateInstance(el).show();
            }
            document.querySelectorAll('.nav-tabs a[data-bs-toggle="tab"]').forEach(tabLink => {
                tabLink.addEventListener('shown.bs.tab', function(e) {
                    history.replaceState(null, '', e.target.getAttribute('href'));
                });
            });
            // ---- Cropper.js wiring ----
            const imagePicker = document.getElementById('imagePicker');
            const hiddenImageInput = document.getElementById('image');
            const cropperImage = document.getElementById('cropperImage');
            const cropperModalEl = document.getElementById('cropperModal');
            const cropperModal = new bootstrap.Modal(cropperModalEl);
            let cropper = null;
            let pickedFileName = 'avatar.jpg';
            imagePicker.addEventListener('change', function() {
                const file = this.files[0];
                if (!file) return;
                pickedFileName = file.name;
                const reader = new FileReader();
                reader.onload = e => {
                    cropperImage.src = e.target.result;
                    cropperModal.show();
                };
                reader.readAsDataURL(file);
            });
            cropperModalEl.addEventListener('shown.bs.modal', function() {
                if (cropper) cropper.destroy();
                cropper = new Cropper(cropperImage, {
                    aspectRatio: 1,
                    viewMode: 1,
                    autoCropArea: 1,
                    background: false,
                });
            });
            cropperModalEl.addEventListener('hidden.bs.modal', function() {
                if (cropper) {
                    cropper.destroy();
                    cropper = null;
                }
                // If the user cancels without saving, clear the picker
                // so we don't leave a half-picked, uncropped file behind.
                if (!hiddenImageInput.files.length) {
                    imagePicker.value = '';
                }
            });
            document.getElementById('cropRotateLeft').addEventListener('click', () => cropper.rotate(-90));
            document.getElementById('cropRotateRight').addEventListener('click', () => cropper.rotate(90));
            document.getElementById('cropZoomIn').addEventListener('click', () => cropper.zoom(0.1));
            document.getElementById('cropZoomOut').addEventListener('click', () => cropper.zoom(-0.1));
            document.getElementById('cropReset').addEventListener('click', () => cropper.reset());
            document.getElementById('cropSaveBtn').addEventListener('click', function() {
                if (!cropper) return;
                cropper.getCroppedCanvas({
                    width: 400,
                    height: 400,
                    imageSmoothingQuality: 'high',
                }).toBlob(blob => {
                    const croppedFile = new File([blob], pickedFileName, {
                        type: blob.type
                    });
                    // Push the cropped image into the real form field via
                    // DataTransfer, since you can't assign to input.files directly.
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(croppedFile);
                    hiddenImageInput.files = dataTransfer.files;
                    const previewUrl = URL.createObjectURL(blob);
                    document.getElementById('avatarPreview').src = previewUrl;
                    document.getElementById('photoThumb').src = previewUrl;
                    cropperModal.hide();
                }, 'image/jpeg', 0.9);
            });
        });
    </script>
@endpush
