<nav class="navbar navbar-expand-md shadow-sm mb-4 mb-lg-0 sidenav">
    <!-- Menu -->
    <a class="d-xl-none d-lg-none d-md-none text-inherit fw-bold" href="#">Menu</a>
    <!-- Button -->
    <button class="navbar-toggler d-md-none icon-shape icon-sm rounded bg-primary text-light" type="button"
        data-bs-toggle="collapse" data-bs-target="#sidenav" aria-controls="sidenav" aria-expanded="false"
        aria-label="Toggle navigation">
        <span class="fe fe-menu"></span>
    </button>
    <!-- Collapse -->
    <div class="collapse navbar-collapse" id="sidenav">
        <div class="navbar-nav flex-column">
            <span class="navbar-header">Dashboard</span>
            <ul class="list-unstyled ms-n2 mb-4">
                <!-- Nav item -->
                <li class="nav-item {{ Route::is('instructor.dashboard') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('instructor.dashboard') }}">
                        <i class="fe fe-home nav-icon"></i>
                        My Dashboard
                    </a>
                </li>
                <!-- Nav item -->
                <li class="nav-item {{ Route::is('instructor.courses.index') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('instructor.courses.index') }}">
                        <i class="fe fe-book nav-icon"></i>
                        My Courses
                    </a>
                </li>
                {{-- <!-- Nav item -->
                <li class="nav-item">
                    <a class="nav-link" href="instructor-reviews.html">
                        <i class="fe fe-star nav-icon"></i>
                        Reviewss
                    </a>
                </li>
                <!-- Nav item -->
                <li class="nav-item">
                    <a class="nav-link" href="instructor-earning.html">
                        <i class="fe fe-pie-chart nav-icon"></i>
                        Earnings
                    </a>
                </li>
                <!-- Nav item -->
                <li class="nav-item">
                    <a class="nav-link" href="instructor-order.html">
                        <i class="fe fe-shopping-bag nav-icon"></i>
                        Orders
                    </a>
                </li>
                <!-- Nav item -->
                <li class="nav-item">
                    <a class="nav-link" href="instructor-students.html">
                        <i class="fe fe-users nav-icon"></i>
                        Students
                    </a>
                </li>
                <!-- Nav item -->
                <li class="nav-item">
                    <a class="nav-link" href="instructor-payouts.html">
                        <i class="fe fe-dollar-sign nav-icon"></i>
                        Payouts
                    </a>
                </li> --}}
            </ul>
            <!-- Navbar header -->
            <span class="navbar-header">Account Settings</span>
            <ul class="list-unstyled ms-n2 mb-0">
                <!-- Nav item -->
                <li class="nav-item {{ Route::is('instructor.profile.edit') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('instructor.profile.edit') }}">
                        <i class="fe fe-settings nav-icon"></i>
                        Edit Profile
                    </a>
                </li>
                <!-- Nav item -->
                <li class="nav-item {{ Route::is('instructor.security') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('instructor.security') }}">
                        <i class="fe fe-user nav-icon"></i>
                        Security
                    </a>
                </li>
                <!-- Nav item -->
                <li class="nav-item {{ Route::is('instructor.social.profile') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('instructor.social.profile') }}">
                        <i class="fe fe-refresh-cw nav-icon"></i>
                        Social Profiles
                    </a>
                </li>
                <!-- Nav item -->
                <li class="nav-item">
                    <a class="nav-link" href="javascript:void(0);"
                        onclick="event.preventDefault();
                                    document.getElementById('teacher_logout_form').submit();">
                        <i class="fe fe-power nav-icon"></i>
                        Sign Out
                    </a>
                    <form method="POST" action="{{ route('logout') }}" id="teacher_logout_form">
                        @csrf
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>
