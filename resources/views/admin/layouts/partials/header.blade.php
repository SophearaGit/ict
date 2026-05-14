@php
    $admin = auth()->guard('admin')->user();
    $adminImgCheck = $admin->image == 'no-img.jpg' ? '\default-images\user\both.jpg' : $admin->image;
@endphp
<div class="header">
    <!-- navbar -->
    <nav class="navbar-default navbar navbar-expand-lg">
        <a id="nav-toggle" href="javascript:;">
            <i class="fe fe-menu"></i>
        </a>
        <div class="ms-lg-3 d-none d-md-none d-lg-block">

            <!-- Form -->
            <form class="d-flex align-items-center">
                <span class="position-absolute ps-3 search-icon">
                    <i class="fe fe-search"></i>
                </span>
                <input type="search" class="form-control ps-6" placeholder="Search Entire Dashboard" />
            </form>
        </div>

        <!--Navbar nav -->
        <div class="ms-auto d-flex">
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
            <ul class="navbar-nav navbar-right-wrap ms-2 d-flex nav-top-wrap">
                <li class="dropdown stopevent">
                    <a class="btn btn-light btn-icon rounded-circle" href="#" role="button"
                        id="dropdownNotification" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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
                            <div class="border-bottom px-3 py-3 d-flex justify-content-between align-items-center">
                                <span class="h4 mb-0">
                                    Notifications
                                </span>
                                @if (auth()->user()->unreadNotifications->count())
                                    <form action="{{ route('admin.notifications.read-all') }}" method="POST">
                                        @csrf
                                        <button class="btn btn-sm btn-light">
                                            Mark all read
                                        </button>
                                    </form>
                                @endif
                            </div>
                            {{-- LIST --}}
                            <ul class="list-group list-group-flush" style="max-height: 400px; overflow-y:auto;">
                                @forelse(auth()->user()->notifications->take(10) as $notification)
                                    <li class="list-group-item {{ $notification->read_at ? '' : 'bg-light' }}">
                                        <div class="row">
                                            <div class="col">
                                                <a href="
                                                    @if ($notification->data['course_id']) {{ route('admin.courses.realtime.show', $notification->data['course_id']) }}
                                                    @else
                                                        '#' @endif
                                                "
                                                    class="text-body text-decoration-none">
                                                    <div class="d-flex">
                                                        {{-- AVATAR --}}
                                                        <img src="{{ $notification->data['teacher_image'] }}"
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
                                                        action="{{ route('admin.notifications.read', $notification->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        <button type="submit" class="badge-dot bg-info border-0"
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
                <li class="dropdown ms-2">
                    <a class="rounded-circle" href="javascript:;" role="button" id="dropdownUser"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="avatar avatar-md avatar-indicators avatar-online">
                            <img alt="avatar" src="{{ asset($adminImgCheck) }}" class="rounded-circle" />
                        </div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownUser">
                        <div class="dropdown-item">
                            <div class="d-flex">
                                <div class="avatar avatar-md avatar-indicators avatar-online">
                                    <img alt="avatar" src="{{ asset($adminImgCheck) }}" class="rounded-circle" />
                                </div>
                                <div class="ms-3 lh-1">
                                    <h5 class="mb-1">
                                        {{ $admin->name }}
                                    </h5>
                                    <p class="mb-0">
                                        {{ $admin->email }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="dropdown-divider"></div>
                        <ul class="list-unstyled">
                            <li class="dropdown-submenu dropstart-lg">
                                <a class="dropdown-item dropdown-list-group-item dropdown-toggle" href="javascript:;">
                                    <i class="fe fe-circle me-2"></i>
                                    Status
                                </a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item" href="javascript:;">
                                            <span class="badge-dot bg-success me-2"></span>
                                            Online
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="javascript:;">
                                            <span class="badge-dot bg-secondary me-2"></span>
                                            Offline
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="javascript:;">
                                            <span class="badge-dot bg-warning me-2"></span>
                                            Away
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="javascript:;">
                                            <span class="badge-dot bg-danger me-2"></span>
                                            Busy
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a class="dropdown-item" href="javascript:;">
                                    <i class="fe fe-user me-2"></i>
                                    Profile
                                </a>
                            </li>
                        </ul>
                        <div class="dropdown-divider"></div>
                        <ul class="list-unstyled">
                            <li>
                                <a class="dropdown-item" href="{{ route('admin.logout') }}"
                                    onclick="event.preventDefault();getElementById('logout').submit();">
                                    <i class="fe fe-power me-2"></i>
                                    Sign Out
                                </a>
                                <form method="POST" id="logout" action="{{ route('admin.logout') }}">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
</div>
