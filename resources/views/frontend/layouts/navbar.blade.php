@php
    $imgCheck =
        auth()->check() && auth()->user()->image !== 'no-img.jpg'
            ? auth()->user()->image
            : '/default-images/user/both.jpg';
@endphp
<nav class="navbar navbar-expand-lg">
    <div class="container px-0">
        <div class="d-flex">
            <a class="navbar-brand" href="{{ route('home') }}"><img style="width: 35px; height: 35px;"
                    src="{{ asset('/frontend/assets/ictImg/logo/ictLogo.jpg') }}" alt="ICT-LOGO" /></a>
            <div class="dropdown d-none d-md-block">
                <button class="btn btn-light-primary text-primary" type="button" id="dropdownMenuButton1"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fe fe-list me-2 align-middle"></i>
                    Category
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                    <li class="dropdown-submenu dropend">
                        <a class="dropdown-item dropdown-list-group-item dropdown-toggle" href="#">Web
                            Development</a>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="../../pages/course-category.html">Bootstrap</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="../../pages/course-category.html">React</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="../../pages/course-category.html">GraphQl</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="../../pages/course-category.html">Gatsby</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="../../pages/course-category.html">Grunt</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="../../pages/course-category.html">Svelte</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="../../pages/course-category.html">Meteor</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="../../pages/course-category.html">HTML5</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="../../pages/course-category.html">Angular</a>
                            </li>
                        </ul>
                    </li>
                    <li class="dropdown-submenu dropend">
                        <a class="dropdown-item dropdown-list-group-item dropdown-toggle" href="#">Design</a>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="../../pages/course-category.html">Graphic Design</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="../../pages/course-category.html">Illustrator</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="../../pages/course-category.html">UX / UI Design</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="../../pages/course-category.html">Figma Design</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="../../pages/course-category.html">Adobe XD</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="../../pages/course-category.html">Sketch</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="../../pages/course-category.html">Icon Design</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="../../pages/course-category.html">Photoshop</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="../../pages/course-category.html" class="dropdown-item">Mobile App</a>
                    </li>
                    <li>
                        <a href="../../pages/course-category.html" class="dropdown-item">IT Software</a>
                    </li>
                    <li>
                        <a href="../../pages/course-category.html" class="dropdown-item">Marketing</a>
                    </li>
                    <li>
                        <a href="../../pages/course-category.html" class="dropdown-item">Music</a>
                    </li>
                    <li>
                        <a href="../../pages/course-category.html" class="dropdown-item">Life Style</a>
                    </li>
                    <li>
                        <a href="../../pages/course-category.html" class="dropdown-item">Business</a>
                    </li>
                    <li>
                        <a href="../../pages/course-category.html" class="dropdown-item">Photography</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="order-lg-3">
            <div class="d-flex align-items-center">
                <div class="dropdown">
                    <button class="btn btn-light btn-icon rounded-circle d-flex align-items-center" type="button"
                        aria-expanded="false" data-bs-toggle="dropdown" aria-label="Toggle theme (auto)">
                        <i class="bi theme-icon-active"></i>
                        <span class="visually-hidden bs-theme-text">Toggle theme</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="bs-theme-text">
                        <li>
                            <button type="button" class="dropdown-item d-flex align-items-center"
                                data-bs-theme-value="light" aria-pressed="false">
                                <i class="bi theme-icon bi-sun-fill"></i>
                                <span class="ms-2">Light</span>
                            </button>
                        </li>
                        <li>
                            <button type="button" class="dropdown-item d-flex align-items-center"
                                data-bs-theme-value="dark" aria-pressed="false">
                                <i class="bi theme-icon bi-moon-stars-fill"></i>
                                <span class="ms-2">Dark</span>
                            </button>
                        </li>
                        <li>
                            <button type="button" class="dropdown-item d-flex align-items-center active"
                                data-bs-theme-value="auto" aria-pressed="true">
                                <i class="bi theme-icon bi-circle-half"></i>
                                <span class="ms-2">Auto</span>
                            </button>
                        </li>
                    </ul>
                </div>
                @auth
                    <ul class="navbar-nav navbar-right-wrap ms-2 d-flex nav-top-wrap">
                        @if (Auth::user()->role == 'instructor')
                            <li class="dropdown stopevent">
                                <a class="btn btn-light btn-icon rounded-circle" href="#" role="button"
                                    id="dropdownNotification" data-bs-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false">
                                    <i class="fe fe-bell"></i>
                                    @if (auth()->user()->unreadNotifications->count() > 0)
                                        <span
                                            class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                            {{ auth()->user()->unreadNotifications->count() }}
                                        </span>
                                    @endif
                                </a>
                                <div class="dropdown-menu dropdown-menu-end dropdown-menu-lg"
                                    aria-labelledby="dropdownNotification">
                                    <div>
                                        {{-- HEADER --}}
                                        <div
                                            class="border-bottom px-3 py-3 d-flex justify-content-between align-items-center">
                                            <span class="h4 mb-0">
                                                Notifications
                                            </span>
                                            @if (auth()->user()->unreadNotifications->count())
                                                <form action="{{ route('instructor.notifications.read-all') }}"
                                                    method="POST">
                                                    @csrf
                                                    <button class="btn btn-sm btn-light">
                                                        Mark all read
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                        {{-- LIST --}}
                                        <ul class="list-group list-group-flush"
                                            style="max-height: 400px; overflow-y:auto;">
                                            @forelse(auth()->user()->notifications->take(10) as $notification)
                                                <li class="list-group-item {{ $notification->read_at ? '' : 'bg-light' }}">
                                                    <div class="row">
                                                        <div class="col">
                                                            <a href="{{ $notification->data['redirect_url'] ?? '#' }}"
                                                                class="text-body text-decoration-none">
                                                                <div class="d-flex">
                                                                    {{-- AVATAR --}}
                                                                    <img src="{{ asset($notification->data['admin_image'] ?? '/default-images/user/both.jpg') }}"
                                                                        alt="" class="avatar-md rounded-circle">
                                                                    {{-- CONTENT --}}
                                                                    <div class="ms-3">
                                                                        <h5 class="fw-bold mb-1">
                                                                            {{ $notification->data['title'] ?? 'Notification' }}
                                                                        </h5>
                                                                        <p class="mb-1">
                                                                            {{ $notification->data['message'] ?? '' }}
                                                                        </p>
                                                                        <span class="fs-6 text-muted">
                                                                            {{ $notification->created_at->diffForHumans() }}
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            </a>
                                                        </div>
                                                        {{-- RIGHT ACTION --}}
                                                        <div class="col-auto text-center me-2">
                                                            @if (!$notification->read_at)
                                                                <form
                                                                    action="{{ route('instructor.notifications.read', $notification->id) }}"
                                                                    method="POST">
                                                                    @csrf
                                                                    <button type="submit"
                                                                        class="badge-dot bg-info border-0"
                                                                        title="Mark as read">
                                                                    </button>
                                                                </form>
                                                            @else
                                                                <span class="badge-dot bg-secondary"></span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </li>
                                            @empty
                                                <li class="list-group-item text-center py-4">
                                                    <div class="text-muted">
                                                        No notifications yet.
                                                    </div>
                                                </li>
                                            @endforelse
                                        </ul>
                                        {{-- FOOTER --}}
                                        <div class="border-top px-3 py-3">
                                            <a href="#" class="text-link fw-semibold">
                                                See all Notifications
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @endif
                        <li class="dropdown ms-2">
                            <a class="rounded-circle" href="javascript:;" role="button" id="dropdownUser"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <div class="avatar avatar-md avatar-indicators avatar-online">
                                    <img alt="avatar" src="{{ asset($imgCheck) }}" class="rounded-circle" />
                                </div>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownUser">
                                <div class="dropdown-item">
                                    <div class="d-flex">
                                        <div class="avatar avatar-md avatar-indicators avatar-online">
                                            <img alt="avatar" src="{{ asset($imgCheck) }}" class="rounded-circle" />
                                        </div>
                                        <div class="ms-3 lh-1">
                                            <h5 class="mb-1">
                                                {{ auth()->user()->name }}
                                            </h5>
                                            <p class="mb-0">
                                                {{ auth()->user()->email }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="dropdown-divider"></div>
                                <ul class="list-unstyled">
                                    @if (Auth::user()->role == 'student')
                                        <li>
                                            <a class="dropdown-item" href="{{ route('student.dashboard') }}">
                                                <i class="fe fe-home me-2"></i>
                                                Dashboard
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('student.profile.edit') }}">
                                                <i class="fe fe-user me-2"></i>
                                                Profile
                                            </a>
                                        </li>
                                    @elseif (Auth::user()->role == 'instructor')
                                        <li>
                                            <a class="dropdown-item" href="{{ route('instructor.dashboard') }}">
                                                <i class="fe fe-home me-2"></i>
                                                Dashboard
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('instructor.profile.edit') }}">
                                                <i class="fe fe-user me-2"></i>
                                                Profile
                                            </a>
                                        </li>
                                    @elseif (Auth::user()->role == 'staff')
                                        <li>
                                            <a class="dropdown-item" href="{{ route('staff.dashboard') }}">
                                                <i class="fe fe-home me-2"></i>
                                                Dashboard
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('staff.profile.edit') }}">
                                                <i class="fe fe-user me-2"></i>
                                                Profile
                                            </a>
                                        </li>
                                    @elseif (Auth::user()->role == 'unknown')
                                        <li>
                                            <a class="dropdown-item" href="javascript:void(0);">
                                                <i class="fe fe-x me-2"></i>
                                                Your account has been disabled. Please contact support.
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                                <div class="dropdown-divider"></div>
                                <ul class="list-unstyled">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('logout') }}"
                                            onclick="event.preventDefault();getElementById('logout').submit();">
                                            <i class="fe fe-power me-2"></i>
                                            Sign Out
                                        </a>
                                        <form method="POST" id="logout" action="{{ route('logout') }}">
                                            @csrf
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                @else
                    {{-- <a href="#" class="btn btn-icon btn-light rounded-circle d-none d-md-inline-flex ms-2"><i
                            class="fe fe-shopping-cart align-middle"></i></a> --}}
                    <a href="{{ route('login') }}" class="btn btn-outline-primary ms-2 d-none d-lg-block">
                        Login
                    </a>
                    {{-- <a href="{{ route('register') }}" class="btn btn-primary ms-2 d-none d-lg-block">Sign up</a> --}}
                @endauth
                <!-- Button -->
                <button class="navbar-toggler collapsed ms-2 ms-lg-0" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbar-default" aria-controls="navbar-default" aria-expanded="false"
                    aria-label="Toggle navigation">
                    <span class="icon-bar top-bar mt-0"></span>
                    <span class="icon-bar middle-bar"></span>
                    <span class="icon-bar bottom-bar"></span>
                </button>
            </div>
        </div>
        <!-- Collapse -->
        <div class="collapse navbar-collapse" id="navbar-default">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('home') ? 'active' : '' }}" href="{{ route('home') }}">Home</a>
                </li>
            </ul>
            {{-- Mobile Sign In / Sign Up — visible only on small screens --}}
            @guest
                <div class="d-lg-none mt-3 d-flex flex-column gap-2">
                    <a href="{{ route('login') }}" class="btn btn-outline-primary w-100">Login</a>
                    {{-- <a href="{{ route('register') }}" class="btn btn-primary w-100">Sign up</a> --}}
                </div>
            @endguest
        </div>
    </div>
</nav>
