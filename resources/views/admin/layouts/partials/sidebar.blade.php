@php
    $isUser = Route::is(['admin.instructor.*', 'admin.student.*', 'admin.staff.*', 'admin.intern.*']);
    $isCourse = Route::is(['admin.courses.realtime.*', 'admin.courses.student.invoice']);
    $isReport = Route::is(['admin.student-report.*', 'admin.staff-report.*', 'admin.intern-report.*']);
    $isIntern = Route::is(['admin.intern.*', 'admin.intern-report.*']);
@endphp
<nav class="navbar-vertical navbar">
    <div class="vh-100" data-simplebar>
        {{-- Brand --}}
        <a class="navbar-brand" href="{{ route('admin.dashboard') }}">
            <img src="{{ asset('/frontend/assets/ictImg/logo/ictLogo.jpg') }}" style="width:40px;height:40px;"
                alt="ICT Logo">
        </a>
        <ul class="navbar-nav flex-column" id="sideNavbar">
            {{-- ── Dashboard ── --}}
            <li class="nav-item">
                <a class="nav-link {{ Route::is('admin.dashboard') ? 'active' : '' }}"
                    href="{{ route('admin.dashboard') }}">
                    <i class="nav-icon fe fe-home me-2"></i> Dashboard
                </a>
            </li>
            {{-- ── Instructor Request ── --}}
            {{-- <li class="nav-item">
                <a class="nav-link {{ Route::is('admin.instructor-request.index') ? 'active' : '' }}"
                    href="{{ route('admin.instructor-request.index') }}">
                    <i class="nav-icon fe fe-help-circle me-2"></i> Teacher Request
                </a>
            </li> --}}
            {{-- ── Report (collapsible) ── --}}
            <li class="nav-item">
                <a class="nav-link {{ $isReport ? '' : 'collapsed' }}" href="#" data-bs-toggle="collapse"
                    data-bs-target="#navReport" aria-expanded="{{ $isReport ? 'true' : 'false' }}"
                    aria-controls="navReport">
                    <i class="nav-icon fe fe-flag me-2"></i> Report
                </a>
                <div id="navReport" class="collapse {{ $isReport ? 'show' : '' }}" data-bs-parent="#sideNavbar">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ Route::is('admin.student-report.*') ? 'active' : '' }}"
                                href="{{ route('admin.student-report.index') }}">
                                <i class="fe fe-file-text me-2"></i>
                                Student
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Route::is('admin.staff-report.*') ? 'active' : '' }}"
                                href="{{ route('admin.staff-report.index') }}">
                                <i class="fe fe-clipboard me-2"></i>
                                Staff
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Route::is('admin.intern-report.*') ? 'active' : '' }}"
                                href="{{ route('admin.intern-report.index') }}">
                                <i class="fe fe-briefcase me-2"></i>
                                Intern
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            {{-- ── Users (collapsible) ── --}}
            <li class="nav-item">
                <a class="nav-link {{ $isUser ? '' : 'collapsed' }}" href="#" data-bs-toggle="collapse"
                    data-bs-target="#navUser" aria-expanded="{{ $isUser ? 'true' : 'false' }}" aria-controls="navUser">
                    <i class="nav-icon fe fe-users me-2"></i> Users
                </a>
                <div id="navUser" class="collapse {{ $isUser ? 'show' : '' }}" data-bs-parent="#sideNavbar">
                    <ul class="nav flex-column">
                        {{-- Users --}}
                        <li class="nav-item">
                            <a class="nav-link {{ Route::is('admin.instructor.*') ? 'active' : '' }}"
                                href="{{ route('admin.instructor.index') }}">
                                <i class="fe fe-user-check me-2"></i>
                                Teacher
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Route::is('admin.student.*') ? 'active' : '' }}"
                                href="{{ route('admin.student.index') }}">
                                <i class="fe fe-user me-2"></i>
                                Student
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Route::is('admin.staff.*') ? 'active' : '' }}"
                                href="{{ route('admin.staff.index') }}">
                                <i class="fe fe-users me-2"></i>
                                Staff
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Route::is('admin.intern.*') ? 'active' : '' }}"
                                href="{{ route('admin.intern.index') }}">
                                <i class="fe fe-briefcase me-2"></i>
                                Intern
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            {{-- ── Course (collapsible) ── --}}
            <li class="nav-item">
                <a class="nav-link {{ $isCourse ? '' : 'collapsed' }}" href="#" data-bs-toggle="collapse"
                    data-bs-target="#navCourse" aria-expanded="{{ $isCourse ? 'true' : 'false' }}"
                    aria-controls="navCourse">
                    <i class="nav-icon fe fe-book me-2"></i> Course
                </a>
                <div id="navCourse" class="collapse {{ $isCourse ? 'show' : '' }}" data-bs-parent="#sideNavbar">
                    <ul class="nav flex-column">
                        {{-- Course --}}
                        <li class="nav-item">
                            <a class="nav-link {{ $isCourse ? 'active' : '' }}"
                                href="{{ route('admin.courses.realtime.index') }}">
                                <i class="fe fe-layers me-2"></i>
                                All
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
        </ul>
    </div>
</nav>
