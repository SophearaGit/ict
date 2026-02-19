<!-- User info -->
@php
    $imgCheck = auth()->user()->image == 'no-img.jpg' ? '\default-images\user\both.jpg' : auth()->user()->image;
    $verifiedCheck =
        auth()->user()->approval_status == 'approved' && auth()->user()->isNot(null)
            ? '/frontend/assets/images/svg/checked-mark.svg'
            : '';
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
                        <img src="{{ $imgCheck }}"
                            class="avatar-xl rounded-circle border border-4 border-white position-relative"
                            alt="avatar">
                        <a href="#" class="position-absolute top-0 end-0" data-bs-toggle="tooltip"
                            data-placement="top" title="Verified">
                            <img src="{{ $verifiedCheck }}" alt="checked" height="30" width="30">
                        </a>
                    </div>
                    <div class="lh-1">
                        <h2 class="mb-0 text-capitalize">{{ auth()->user()->name }}</h2>
                        <p class="mb-0 d-block">{{ auth()->user()->email }}</p>
                    </div>
                </div>
                <div>
                    <a href="{{ route('instructor.courses.create') }}" class="btn btn-primary d-none d-md-block">Create
                        New Course</a>
                </div>
            </div>
        </div>
    </div>
</div>
