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
                    class="collapse {{ Route::is('admin.instructor.index') || Route::is('admin.student.index') || Route::is('admin.staff.index')
                        ? 'show'
                        : '' }} "
                    data-bs-parent="#sideNavbar">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ Route::is('admin.instructor.index') ? 'active' : '' }} "
                                href="{{ route('admin.instructor.index') }}">Instructor</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Route::is('admin.student.index') ? 'active' : '' }}  "
                                href="{{ route('admin.student.index') }}">Student</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Route::is('admin.staff.index') ? 'active' : '' }}  "
                                href="{{ route('admin.staff.index') }}">Staff</a>
                        </li>
                    </ul>
                </div>
            </li>
            {{-- COURSE MANAGEMENT LI DROPDONW SHOWING COURSE language --}}
            <li class="nav-item">
                <a class="nav-link {{ (Route::is('admin.course-language.index') || Route::is('admin.course-level.index')
                        ? ''
                        : 'collapsed' || Route::is('admin.course-category.index'))
                    ? ''
                    : 'collapsed' }}
                "
                    href="#" data-bs-toggle="collapse" data-bs-target="#navCourseManagement" aria-expanded="false"
                    aria-controls="navCourseManagement">
                    <i class="nav-icon fe fe-book me-2"></i>
                    Course Management
                </a>
                <div id="navCourseManagement"
                    class="collapse {{ Route::is('admin.course-language.index') ||
                    Route::is('admin.course-level.index') ||
                    Route::is('admin.course-category.index') ||
                    Route::is('admin.courses.index')
                        ? 'show'
                        : '' }} "
                    data-bs-parent="#sideNavbar">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ Route::is('admin.courses.index') ? 'active' : '' }} "
                                href="{{ route('admin.courses.index') }}">Course</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Route::is('admin.course-language.index') ? 'active' : '' }} "
                                href="{{ route('admin.course-language.index') }}">Course Language</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Route::is('admin.course-level.index') ? 'active' : '' }} "
                                href="{{ route('admin.course-level.index') }}">Course Level</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Route::is('admin.course-category.index') ? 'active' : '' }} "
                                href="{{ route('admin.course-category.index') }}">Course Category</a>
                        </li>
                    </ul>
                </div>
            </li>




        </ul>
    </div>
</nav>
