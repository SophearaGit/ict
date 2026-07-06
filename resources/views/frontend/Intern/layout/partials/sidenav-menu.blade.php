<!-- Sidenav Menu Start -->
<div class="sidenav-menu">

    <!-- Brand Logo -->
    <a href="{{ route('intern.dashboard') }}" class="logo">
        <span class="logo-light">
            <span class="logo-lg">
                <img src="/frontend/intern/assets/images/ict-logo-all.jpg" alt="logo"
                    style="height: clamp(20px, 4vw, 32px); width: auto; max-width: 100%;">
            </span>
            <span class="logo-sm text-center">
                <img src="/frontend/intern/assets/images/ict-logo-all.jpg" alt="small logo"
                    style="height: clamp(18px, 3vw, 26px); width: auto; max-width: 100%;">
            </span>
        </span>
        <span class="logo-dark">
            <span class="logo-lg">
                <img src="/frontend/intern/assets/images/ict-logo-all.jpg" alt="dark logo"
                    style="height: clamp(20px, 4vw, 32px); width: auto; max-width: 100%;">
            </span>
            <span class="logo-sm text-center">
                <img src="/frontend/intern/assets/images/ict-logo-all.jpg" alt="small logo"
                    style="height: clamp(18px, 3vw, 26px); width: auto; max-width: 100%;">
            </span>
        </span>
    </a>

    <!-- Sidebar Hover Menu Toggle Button -->
    <button class="button-sm-hover">
        <i class="ti ti-circle align-middle"></i>
    </button>

    <!-- Full Sidebar Menu Close Button -->
    <button class="button-close-fullsidebar">
        <i class="ti ti-x align-middle"></i>
    </button>
    <div data-simplebar>

        <!--- Sidenav Menu -->
        <ul class="side-nav">
            <li class="side-nav-item {{ request()->routeIs('intern.dashboard') ? 'active' : '' }}">
                <a href="{{ route('intern.dashboard') }}"
                    class="side-nav-link {{ request()->routeIs('intern.dashboard') ? 'active' : '' }}">
                    <span class="menu-icon"><i class="ti ti-dashboard"></i></span>
                    <span class="menu-text"> Dashboard </span>
                </a>
            </li>

            <li class="side-nav-item {{ request()->routeIs('intern.report.*') ? 'active' : '' }}">
                <a href="{{ route('intern.report.index') }}"
                    class="side-nav-link {{ request()->routeIs('intern.report.*') ? 'active' : '' }}">
                    <span class="menu-icon"><i class="ti ti-report"></i></span>
                    <span class="menu-text"> Reports </span>
                </a>
            </li>
        </ul>
        <div class="clearfix"></div>
    </div>
</div>

<!-- Sidenav Menu End -->
