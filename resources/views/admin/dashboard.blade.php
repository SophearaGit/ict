@extends('admin.layouts.master')
@section('content')
    <!--  Owl carousel -->
    <div class="owl-carousel counter-carousel owl-theme">
        <div class="item">
            <div class="card border-0 zoom-in bg-light-primary shadow-none">
                <div class="card-body">
                    <div class="text-center">
                        <img src="/admin/assets/dist/images/svgs/icon-user-male.svg" width="50" height="50"
                            class="mb-3" alt="" />
                        <p class="fw-semibold fs-3 text-primary mb-1"> Employees </p>
                        <h5 class="fw-semibold text-primary mb-0">96</h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="item">
            <div class="card border-0 zoom-in bg-light-warning shadow-none">
                <div class="card-body">
                    <div class="text-center">
                        <img src="/admin/assets/dist/images/svgs/icon-briefcase.svg" width="50" height="50"
                            class="mb-3" alt="" />
                        <p class="fw-semibold fs-3 text-warning mb-1">Clients</p>
                        <h5 class="fw-semibold text-warning mb-0">3,650</h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="item">
            <div class="card border-0 zoom-in bg-light-info shadow-none">
                <div class="card-body">
                    <div class="text-center">
                        <img src="/admin/assets/dist/images/svgs/icon-mailbox.svg" width="50" height="50"
                            class="mb-3" alt="" />
                        <p class="fw-semibold fs-3 text-info mb-1">Projects</p>
                        <h5 class="fw-semibold text-info mb-0">356</h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="item">
            <div class="card border-0 zoom-in bg-light-danger shadow-none">
                <div class="card-body">
                    <div class="text-center">
                        <img src="/admin/assets/dist/images/svgs/icon-favorites.svg" width="50" height="50"
                            class="mb-3" alt="" />
                        <p class="fw-semibold fs-3 text-danger mb-1">Events</p>
                        <h5 class="fw-semibold text-danger mb-0">696</h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="item">
            <div class="card border-0 zoom-in bg-light-success shadow-none">
                <div class="card-body">
                    <div class="text-center">
                        <img src="/admin/assets/dist/images/svgs/icon-speech-bubble.svg" width="50" height="50"
                            class="mb-3" alt="" />
                        <p class="fw-semibold fs-3 text-success mb-1">Payroll</p>
                        <h5 class="fw-semibold text-success mb-0">$96k</h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="item">
            <div class="card border-0 zoom-in bg-light-info shadow-none">
                <div class="card-body">
                    <div class="text-center">
                        <img src="/admin/assets/dist/images/svgs/icon-connect.svg" width="50" height="50"
                            class="mb-3" alt="" />
                        <p class="fw-semibold fs-3 text-info mb-1">Reports</p>
                        <h5 class="fw-semibold text-info mb-0">59</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
