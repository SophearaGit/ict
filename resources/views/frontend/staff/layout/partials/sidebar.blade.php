<aside class="left-sidebar">
    <!-- Sidebar scroll-->
    <div>
        <div class="brand-logo d-flex align-items-center justify-content-between">
            <a href="./index.html" class="text-nowrap logo-img">
                <img src="" class="dark-logo" width="180" alt="ict_dark_logo" />
                <img src="" class="light-logo" width="180" alt="ict_light_logo" />
            </a>
            <div class="close-btn d-lg-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
                <i class="ti ti-x fs-8 text-muted"></i>
            </div>
        </div>
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav scroll-sidebar" data-simplebar>
            <ul id="sidebarnav">
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">Home</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link {{ request()->is('staff/dashboard') ? 'active' : '' }}"
                        href="{{ route('staff.dashboard') }}" aria-expanded="false">
                        <span>
                            <i class="ti ti-layout-dashboard"></i>
                        </span>
                        <span class="hide-menu">
                            Dashboard
                        </span>
                    </a>
                </li>
                {{-- invoice --}}
                <li class="sidebar-item {{ request()->is('staff/invoices') ? 'selected' : '' }}">
                    <a class="sidebar-link {{ request()->is('staff/invoices') ? 'active' : '' }}"
                        href="{{ route('staff.invoices') }}" aria-expanded="false">
                        <span>
                            <i class="ti ti-file-invoice"></i>
                        </span>
                        <span class="hide-menu">
                            Invoice
                        </span>
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

                <li
                    class="sidebar-item {{ request()->is('staff/courses') ||
                    request()->is('staff/courses/create') ||
                    request()->is('staff/schedules') ||
                    request()->is('staff/schedules/create')
                        ? 'selected'
                        : '' }}">
                    <a class="sidebar-link has-arrow {{ request()->is('staff/courses') ||
                    request()->is('staff/courses/create') ||
                    request()->is('staff/schedules') ||
                    request()->is('staff/schedules/create')
                        ? 'active'
                        : '' }}"
                        href="javascript:;" aria-expanded="false">
                        <span class="d-flex">
                            <i class="ti ti-book"></i>
                        </span>
                        <span class="hide-menu">
                            Course Management
                        </span>
                    </a>
                    <ul aria-expanded="false"
                        class="collapse first-level
                    {{ request()->is('staff/courses') ||
                    request()->is('staff/courses/create') ||
                    request()->is('staff/schedules') ||
                    request()->is('staff/schedules/create')
                        ? 'in'
                        : '' }}
                    ">
                        <li
                            class="sidebar-item
                            {{ request()->is('staff/courses') || request()->is('staff/courses/create') ? 'active' : '' }}
                        ">
                            <a href="{{ route('staff.courses.index') }}"
                                class="sidebar-link {{ request()->is('staff/courses') || request()->is('staff/courses/create') ? 'active' : '' }}">
                                <div class="round-16 d-flex align-items-center justify-content-center">
                                    <i class="ti ti-bookmark fs-3"></i>
                                </div>
                                <span class="hide-menu">
                                    Courses
                                </span>
                            </a>
                        </li>

                        <li
                            class="sidebar-item
                            {{ request()->is('staff/schedules') || request()->is('staff/schedules/create') ? 'active' : '' }}
                        ">
                            <a href="{{ route('staff.schedules.index') }}"
                                class="sidebar-link
                            {{ request()->is('staff/schedules') || request()->is('staff/schedules/create') ? 'active' : '' }}

                            ">
                                <div class="round-16 d-flex align-items-center justify-content-center">
                                    <i class="ti ti-calendar fs-3"></i>
                                </div>
                                <span class="hide-menu">
                                    Schedules
                                </span>
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
