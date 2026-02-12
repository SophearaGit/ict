<!-- Sidebar -->
<nav class="navbar-vertical navbar">
    <div class="vh-100" data-simplebar>
        <!-- Brand logo -->
        <a class="navbar-brand" href="{{ route('admin.dashboard') }}">
            <img src="" alt="ICT-LOGO" />
        </a>
        <!-- Navbar nav -->
        <ul class="navbar-nav flex-column" id="sideNavbar">
            <li class="nav-item">
                <a class="nav-link {{ Route::is('admin.dashboard') ? 'active' : '' }} "
                    href="{{ route('admin.dashboard') }}">
                    <i class="nav-icon fe fe-home me-2"></i>
                    Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Route::is('admin.instructor-request.index') ? 'active' : '' }} "
                    href="{{ route('admin.instructor-request.index') }}">
                    <i class="nav-icon fe fe-help-circle me-2"></i>
                    Instructor Request
                </a>
            </li>
            <!-- Nav item -->
            <li class="nav-item">
                <a class="nav-link {{ Route::is('admin.instructor.index') ? '' : 'collapsed' }} " href="#"
                    data-bs-toggle="collapse" data-bs-target="#navProfile" aria-expanded="false"
                    aria-controls="navProfile">
                    <i class="nav-icon fe fe-user me-2"></i>
                    User
                </a>
                <div id="navProfile"
                    class="collapse {{ Route::is('admin.instructor.index') || Route::is('admin.student.index') ? 'show' : '' }} "
                    data-bs-parent="#sideNavbar">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ Route::is('admin.instructor.index') ? 'active' : '' }} "
                                href="{{ route('admin.instructor.index') }}">Instructor</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Route::is('admin.student.index') ? 'active' : '' }}  "
                                href="{{ route('admin.student.index') }}">Students</a>
                        </li>
                    </ul>
                </div>
            </li>
        </ul>
    </div>
</nav>
