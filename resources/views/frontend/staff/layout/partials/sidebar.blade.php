<aside class="left-sidebar">
    <!-- Sidebar scroll-->
    <div>
        <div class="brand-logo d-flex align-items-center justify-content-between">
            <a href="{{ route('home') }}" class="text-nowrap logo-img">
                <img src="{{ asset('/frontend/assets/ictImg/logo/ictLogo.jpg') }}" class="dark-logo" width="38"
                    height="38" alt="ict_dark_logo" />
                <img src="{{ asset('/frontend/assets/ictImg/logo/ictLogo.jpg') }}" class="light-logo" width="38"
                    height="38" alt="ict_light_logo" />
            </a>
            <div class="close-btn d-lg-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
                <i class="ti ti-x fs-8 text-muted"></i>
            </div>
        </div>

        <!-- Sidebar navigation-->
        <nav class="sidebar-nav scroll-sidebar" data-simplebar>
            <ul id="sidebarnav">

                {{-- Home Section --}}
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">Home</span>
                </li>

                {{-- Dashboard --}}
                <li class="sidebar-item">
                    <a class="sidebar-link {{ request()->routeIs('staff.dashboard') ? 'active' : '' }}"
                        href="{{ route('staff.dashboard') }}" aria-expanded="false">
                        <span>
                            <i class="ti ti-layout-dashboard"></i>
                        </span>
                        <span class="hide-menu">Dashboard</span>
                    </a>
                </li>

                {{-- Users Dropdown (Teachers & Students) --}}
                <li class="sidebar-item {{ request()->routeIs('staff.teacher.*') ? 'selected' : '' }}">
                    <a class="sidebar-link has-arrow {{ request()->routeIs('staff.teacher.*') ? 'active' : '' }}"
                        href="javascript:;" aria-expanded="false">
                        <span>
                            <i class="ti ti-users"></i>
                        </span>
                        <span class="hide-menu">Users</span>
                    </a>
                    <ul aria-expanded="false"
                        class="collapse first-level {{ request()->routeIs('staff.teacher.*') ? 'in' : '' }}">

                        {{-- Teachers --}}
                        <li class="sidebar-item {{ request()->routeIs('staff.teacher.*') ? 'active' : '' }}">
                            <a href="{{ route('staff.teacher.index') }}"
                                class="sidebar-link {{ request()->routeIs('staff.teacher.*') ? 'active' : '' }}">
                                <div class="round-16 d-flex align-items-center justify-content-center">
                                    <i class="ti ti-chalkboard fs-3"></i>
                                </div>
                                <span class="hide-menu">Teachers</span>
                            </a>
                        </li>

                        {{-- Students --}}
                        <li class="sidebar-item ">
                            <a href="#" class="sidebar-link ">
                                <div class="round-16 d-flex align-items-center justify-content-center">
                                    <i class="ti ti-user-plus fs-3"></i>
                                </div>
                                <span class="hide-menu">Students</span>
                            </a>
                        </li>

                    </ul>
                </li>

                {{-- Invoice --}}
                <li class="sidebar-item {{ request()->routeIs('staff.invoices') ? 'selected' : '' }}">
                    <a class="sidebar-link {{ request()->routeIs('staff.invoices') ? 'active' : '' }}"
                        href="{{ route('staff.invoices') }}" aria-expanded="false">
                        <span>
                            <i class="ti ti-file-invoice"></i>
                        </span>
                        <span class="hide-menu">Invoice</span>
                    </a>
                </li>

                {{-- Reports --}}
                <li class="sidebar-item {{ request()->routeIs('staff.reports.*') ? 'selected' : '' }}">
                    <a class="sidebar-link {{ request()->routeIs('staff.reports.*') ? 'active' : '' }}"
                        href="{{ route('staff.reports.index') }}" aria-expanded="false">
                        <span>
                            <i class="ti ti-chart-bar"></i>
                        </span>
                        <span class="hide-menu">Reports</span>
                    </a>
                </li>
                <li class="sidebar-item {{ request()->is('staff/student-registration') ? 'selected' : '' }}">
                    <a class="sidebar-link has-arrow {{ request()->is('staff/student-registration') ? 'active' : '' }} "
                        href="javascript:;" aria-expanded="false">
                        <span class="d-flex">
                            <i class="ti ti-chart-donut-3"></i>
                        </span>
                        <span class="hide-menu">
                            Student Management
                        </span>
                    </a>
                    <ul aria-expanded="false"
                        class="collapse first-level
                    {{ request()->is('staff/student-registration') ? 'in' : '' }}
                    ">
                        <li class="sidebar-item {{ request()->is('staff/student-registration') ? 'active' : '' }}">
                            <a href="{{ route('staff.student.registration') }}"
                                class="sidebar-link
                            {{ request()->is('staff/student-registration') ? 'active' : '' }}
                            ">
                                <div class="round-16 d-flex align-items-center justify-content-center">
                                    <i class="ti ti-user-plus fs-3"></i>
                                </div>
                                <span class="hide-menu">
                                    Registration
                                </span>
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- Course Management Dropdown --}}
                <li
                    class="sidebar-item {{ request()->routeIs('staff.courses.*', 'staff.schedules.*') ? 'selected' : '' }}">
                    <a class="sidebar-link has-arrow {{ request()->routeIs('staff.courses.*', 'staff.schedules.*') ? 'active' : '' }}"
                        href="javascript:;" aria-expanded="false">
                        <span class="d-flex">
                            <i class="ti ti-book"></i>
                        </span>
                        <span class="hide-menu">Course Management</span>
                    </a>
                    <ul aria-expanded="false"
                        class="collapse first-level {{ request()->routeIs('staff.courses.*', 'staff.schedules.*') ? 'in' : '' }}">

                        {{-- Courses --}}
                        <li class="sidebar-item {{ request()->routeIs('staff.courses.*') ? 'active' : '' }}">
                            <a href="{{ route('staff.courses.index') }}"
                                class="sidebar-link {{ request()->routeIs('staff.courses.*') ? 'active' : '' }}">
                                <div class="round-16 d-flex align-items-center justify-content-center">
                                    <i class="ti ti-bookmark fs-3"></i>
                                </div>
                                <span class="hide-menu">Courses</span>
                            </a>
                        </li>

                        {{-- Schedules --}}
                        <li class="sidebar-item {{ request()->routeIs('staff.schedules.*') ? 'active' : '' }}">
                            <a href="{{ route('staff.schedules.index') }}"
                                class="sidebar-link {{ request()->routeIs('staff.schedules.*') ? 'active' : '' }}">
                                <div class="round-16 d-flex align-items-center justify-content-center">
                                    <i class="ti ti-calendar fs-3"></i>
                                </div>
                                <span class="hide-menu">Schedules</span>
                            </a>
                        </li>

                    </ul>
                </li>

            </ul>
        </nav>

        <div class="fixed-profile p-3 bg-light-secondary rounded sidebar-ad mt-3">
            <div class="hstack gap-3">
                <div class="john-img">
                    <img src="/admin/assets/dist/images/profile/user-1.jpg" class="rounded-circle" width="40"
                        height="40" alt="">
                </div>
                <div class="john-title">
                    <h6 class="mb-0 fs-4 fw-semibold">Mathew</h6>
                    <span class="fs-2 text-dark">Designer</span>
                </div>
                <button class="border-0 bg-transparent text-primary ms-auto" tabindex="0" type="button"
                    aria-label="logout" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="logout">
                    <i class="ti ti-power fs-6"></i>
                </button>
            </div>
        </div>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
</aside>
