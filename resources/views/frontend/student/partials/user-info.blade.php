<!-- User info -->
@php
    $imgCheck = auth()->user()->image == 'no-img.jpg' ? '\default-images\user\both.jpg' : auth()->user()->image;
@endphp
<div class="row align-items-center">
    <div class="col-xl-12 col-lg-12 col-md-12 col-12">
        <!-- Bg -->
        <div class="rounded-top"
            style="background: url(/frontend/assets/images/background/profile-bg.jpg) no-repeat; background-size: cover; height: 100px">
        </div>
        <div class="card px-4 pt-2 pb-4 shadow-sm rounded-top-0 rounded-bottom-0 rounded-bottom-md-2">
            <div class="d-flex align-items-end justify-content-between">
                <div class="d-flex align-items-center">
                    <div class="me-2 position-relative d-flex justify-content-end align-items-end mt-n5">
                        <img src="{{ $imgCheck }}" class="avatar-xl rounded-circle border border-4 border-white"
                            alt="avatar">
                    </div>
                    <div class="lh-1">
                        <h2 class="mb-0 text-capitalize">
                            {{ auth()->user()->name }}
                            <a href="#" data-bs-toggle="tooltip" data-placement="top" aria-label="Beginner"
                                data-bs-original-title="Beginner">
                                <i class="fas fa-check-circle text-primary ms-1"></i>
                            </a>
                        </h2>
                        <p class="mb-0 d-block">
                            {{ auth()->user()->email }}
                        </p>
                    </div>
                </div>
                <div>
                    @if (Route::is('student.profile.edit'))
                        <a href="{{ route('student.dashboard') }}" class="btn btn-primary btn-sm d-none d-md-block">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('student.profile.edit') }}" class="btn btn-primary btn-sm d-none d-md-block">
                            Account Setting
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
