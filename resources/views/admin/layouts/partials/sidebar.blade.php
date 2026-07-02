@php
    $isUser =
        Route::is('admin.instructor.index') ||
        Route::is('admin.instructor.show.detail') ||
        Route::is('admin.student.index') ||
        Route::is('admin.staff.index');
    $isCourse =
        Route::is('admin.courses.realtime.index') ||
        Route::is('admin.courses.realtime.show') ||
        Route::is('admin.courses.realtime.create') ||
        Route::is('admin.courses.realtime.edit') ||
        Route::is('admin.courses.student.invoice');
    $isReport = Route::is('admin.student-report.index') || Route::is('admin.staff-report.index');
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
            <li class="nav-item">
                <a class="nav-link {{ Route::is('admin.instructor-request.index') ? 'active' : '' }}"
                    href="{{ route('admin.instructor-request.index') }}">
                    <i class="nav-icon fe fe-help-circle me-2"></i> Teacher Request
                </a>
            </li>
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
                            <a class="nav-link {{ Route::is('admin.student-report.index') ? 'active' : '' }}"
                                href="{{ route('admin.student-report.index') }}">
                                Student
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Route::is('admin.staff-report.index') ? 'active' : '' }}"
                                href="{{ route('admin.staff-report.index') }}">
                                Staff
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
                        <li class="nav-item">
                            <a class="nav-link {{ Route::is('admin.instructor.index') || Route::is('admin.instructor.show.detail') ? 'active' : '' }}"
                                href="{{ route('admin.instructor.index') }}">
                                Teacher
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Route::is('admin.student.index') ? 'active' : '' }}"
                                href="{{ route('admin.student.index') }}">
                                Student
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Route::is('admin.staff.index') ? 'active' : '' }}"
                                href="{{ route('admin.staff.index') }}">
                                Staff
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
                        <li class="nav-item">
                            <a class="nav-link {{ $isCourse ? 'active' : '' }}"
                                href="{{ route('admin.courses.realtime.index') }}">
                                All
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
        </ul>
    </div>
</nav>
