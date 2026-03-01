@php
    $imgCheck = auth()->user()->image == 'no-img.jpg' ? '\default-images\user\both.jpg' : auth()->user()->image;
@endphp
<header class="app-header">
    <nav class="navbar navbar-expand-lg navbar-light">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link sidebartoggler nav-icon-hover ms-n3" id="headerCollapse" href="javascript:void(0)">
                    <i class="ti ti-menu-2"></i>
                </a>
            </li>
            {{-- <li class="nav-item d-none d-lg-block">
                <a class="nav-link nav-icon-hover" href="javascript:void(0)" data-bs-toggle="modal"
                    data-bs-target="#exampleModal">
                    <i class="ti ti-search"></i>
                </a>
            </li> --}}
        </ul>
        <div class="d-block d-lg-none">
            <img src="/admin/assets/dist/images/logos/dark-logo.svg" class="dark-logo" width="180" alt="" />
            <img src="/admin/assets/dist/images/logos/light-logo.svg" class="light-logo" width="180"
                alt="" />
        </div>
        <button class="navbar-toggler p-0 border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="p-2">
                <i class="ti ti-dots fs-7"></i>
            </span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <div class="d-flex align-items-center justify-content-between">
                <a href="javascript:void(0)" class="nav-link d-flex d-lg-none align-items-center justify-content-center"
                    type="button" data-bs-toggle="offcanvas" data-bs-target="#mobilenavbar"
                    aria-controls="offcanvasWithBothOptions">
                    <i class="ti ti-align-justified fs-7"></i>
                </a>
                <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-center">

                    <li class="nav-item dropdown">
                        <a class="nav-link pe-0" href="javascript:void(0)" id="drop1" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <div class="d-flex align-items-center">
                                <div class="user-profile-img">
                                    <img src="{{ $imgCheck ?? '' }}" class="rounded-circle" width="35"
                                        height="35" alt="{{ Auth::user()?->name ?? 'User Image' }}" />
                                </div>
                            </div>
                        </a>
                        <div class="dropdown-menu content-dd dropdown-menu-end dropdown-menu-animate-up"
                            aria-labelledby="drop1">
                            <div class="profile-dropdown position-relative" data-simplebar>
                                {{-- <div class="py-3 px-7 pb-0">
                                    <h5 class="mb-0 fs-5 fw-semibold">User Profile</h5>
                                </div> --}}
                                <div class="d-flex align-items-center py-9 mx-7 border-bottom">
                                    <img src="{{ $imgCheck ?? '' }}" class="rounded-circle" width="80"
                                        height="80" alt="{{ Auth::user()?->name ?? 'User Image' }}" />
                                    <div class="ms-3">
                                        <h5 class="mb-1 fs-3">
                                            {{ Auth::user()?->name ?? 'User' }}
                                        </h5>
                                        <span class="mb-1 d-block text-dark">
                                            {{ Auth::user()?->role ?? 'Role' }}
                                        </span>
                                        <p class="mb-0 d-flex text-dark align-items-center gap-2">
                                            <i class="ti ti-mail fs-4"></i> {{ Auth::user()?->email ?? 'email' }}
                                        </p>
                                    </div>
                                </div>
                                <div class="message-body">
                                    <a href="./page-user-profile.html" class="py-8 px-7 mt-8 d-flex align-items-center">
                                        <span
                                            class="d-flex align-items-center justify-content-center bg-light rounded-1 p-6">
                                            <img src="/admin/assets/dist/images/svgs/icon-account.svg" alt=""
                                                width="24" height="24">
                                        </span>
                                        <div class="w-75 d-inline-block v-middle ps-3">
                                            <h6 class="mb-1 bg-hover-primary fw-semibold"> My Profile
                                            </h6>
                                            <span class="d-block text-dark">Account Settings</span>
                                        </div>
                                    </a>

                                </div>
                                <div class="d-grid py-4 px-7 pt-8">
                                    <a class="btn btn-outline-primary" href="javascript:void(0);"
                                        onclick="event.preventDefault();
                                    document.getElementById('staff_logout_form').submit();">
                                        Sign Out
                                    </a>
                                    <form method="POST" action="{{ route('logout') }}" id="staff_logout_form">
                                        @csrf
                                    </form>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>
