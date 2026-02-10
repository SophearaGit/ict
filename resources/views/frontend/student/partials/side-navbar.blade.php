<!-- Side navbar -->
<nav class="navbar navbar-expand-md shadow-sm mb-4 mb-lg-0 sidenav">
    <!-- Menu -->
    <a class="d-xl-none d-lg-none d-md-none text-inherit fw-bold" href="#">Menu</a>
    <!-- Button -->
    <button class="navbar-toggler d-md-none icon-shape icon-sm rounded bg-primary text-light" type="button"
        data-bs-toggle="collapse" data-bs-target="#sidenav" aria-controls="sidenav" aria-expanded="false"
        aria-label="Toggle navigation">
        <span class="fe fe-menu"></span>
    </button>
    <!-- Collapse navbar -->
    <div class="collapse navbar-collapse" id="sidenav">
        <div class="navbar-nav flex-column">
            <span class="navbar-header">Account Settings</span>
            <!-- List -->
            <ul class="list-unstyled ms-n2 mb-4">
                <!-- Nav item -->
                <li class="nav-item {{ Route::is('student.profile.edit') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('student.profile.edit') }}">
                        <i class="fe fe-settings nav-icon"></i>
                        Edit Profile
                    </a>
                </li>
                <!-- Nav item -->
                <li class="nav-item {{ Route::is('student.security') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('student.security') }}">
                        <i class="fe fe-user nav-icon"></i>
                        Security
                    </a>
                </li>
                <!-- Nav item -->
                <li class="nav-item {{ Route::is('student.social.profile') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('student.social.profile') }}">
                        <i class="fe fe-refresh-cw nav-icon"></i>
                        Social Profiles
                    </a>
                </li>
                <!-- Nav item -->
                <li class="nav-item">
                    <a class="nav-link" href="javascript:void(0);"
                        onclick="event.preventDefault();
                                        document.getElementById('student_logout_form').submit();">
                        <i class="fe fe-power nav-icon"></i>
                        Sign Out
                    </a>
                    <form method="POST" action="{{ route('logout') }}" id="student_logout_form">
                        @csrf
                    </form>
                </li>
            </ul>
            <span class="navbar-header">Optional Settings</span>
            <ul class="list-unstyled ms-n2 mb-4">
                <!-- Nav item -->
                <li class="nav-item {{ Route::is('student.become.instructor') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('student.become.instructor') }}">
                        <i class="fe fe-settings nav-icon"></i>
                        Become an Instructor
                    </a>
                </li>
            </ul>
            @if (auth()->user()->approval_status == 'pending')
                <span class="navbar-header text-warning">
                    Note: Your instructor account will be activated after admin approval, for now you are a student.
                </span>
            @endif
        </div>
    </div>
</nav>
