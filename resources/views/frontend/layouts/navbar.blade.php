@php
    $imgCheck =
        auth()->check() && auth()->user()->image !== 'no-img.jpg'
            ? auth()->user()->image
            : '/default-images/user/both.jpg';
@endphp
<nav class="navbar navbar-expand-lg">
    <div class="container-fluid px-0">
        <div class="d-flex">
            <a class="navbar-brand" href="{{ route('home') }}"><img style="width: 35px; height: 35px;"
                    src="{{ asset('/frontend/assets/ictImg/logo/ictLogo.jpg') }}" alt="ICT-LOGO" /></a>
            <div class="dropdown d-none d-md-block">
                <button class="btn btn-light-primary text-primary" type="button" id="dropdownMenuButton1"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fe fe-list me-2 align-middle"></i>
                    Category
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                    <li class="dropdown-submenu dropend">
                        <a class="dropdown-item dropdown-list-group-item dropdown-toggle" href="#">Web
                            Development</a>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="../../pages/course-category.html">Bootstrap</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="../../pages/course-category.html">React</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="../../pages/course-category.html">GraphQl</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="../../pages/course-category.html">Gatsby</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="../../pages/course-category.html">Grunt</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="../../pages/course-category.html">Svelte</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="../../pages/course-category.html">Meteor</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="../../pages/course-category.html">HTML5</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="../../pages/course-category.html">Angular</a>
                            </li>
                        </ul>
                    </li>
                    <li class="dropdown-submenu dropend">
                        <a class="dropdown-item dropdown-list-group-item dropdown-toggle" href="#">Design</a>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="../../pages/course-category.html">Graphic Design</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="../../pages/course-category.html">Illustrator</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="../../pages/course-category.html">UX / UI Design</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="../../pages/course-category.html">Figma Design</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="../../pages/course-category.html">Adobe XD</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="../../pages/course-category.html">Sketch</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="../../pages/course-category.html">Icon Design</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="../../pages/course-category.html">Photoshop</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="../../pages/course-category.html" class="dropdown-item">Mobile App</a>
                    </li>
                    <li>
                        <a href="../../pages/course-category.html" class="dropdown-item">IT Software</a>
                    </li>
                    <li>
                        <a href="../../pages/course-category.html" class="dropdown-item">Marketing</a>
                    </li>
                    <li>
                        <a href="../../pages/course-category.html" class="dropdown-item">Music</a>
                    </li>
                    <li>
                        <a href="../../pages/course-category.html" class="dropdown-item">Life Style</a>
                    </li>
                    <li>
                        <a href="../../pages/course-category.html" class="dropdown-item">Business</a>
                    </li>
                    <li>
                        <a href="../../pages/course-category.html" class="dropdown-item">Photography</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="order-lg-3">
            <div class="d-flex align-items-center">
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
                @auth
                    <ul class="navbar-nav navbar-right-wrap ms-2 flex-row d-none d-md-block">
                        <li class="dropdown d-inline-block stopevent position-static">
                            {{-- <a class="btn btn-light btn-icon rounded-circle indicator indicator-primary" href="#"
                                role="button" id="dropdownNotificationSecond" data-bs-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                                <i class="fe fe-bell"></i>
                            </a> --}}
                            <div class="dropdown-menu dropdown-menu-end dropdown-menu-lg position-absolute mx-3 my-5"
                                aria-labelledby="dropdownNotificationSecond">
                                <div>
                                    <div class="border-bottom px-3 pb-3 d-flex align-items-center">
                                        <span class="h5 mb-0">Notifications</span>
                                        <a href="# ">
                                            <span class="align-middle"><i class="fe fe-settings me-1"></i></span>
                                        </a>
                                    </div>
                                    <ul class="list-group list-group-flush" style="height: 300px" data-simplebar="init">
                                        <div class="simplebar-wrapper" style="margin: 0px;">
                                            <div class="simplebar-height-auto-observer-wrapper">
                                                <div class="simplebar-height-auto-observer"></div>
                                            </div>
                                            <div class="simplebar-mask">
                                                <div class="simplebar-offset" style="right: 0px; bottom: 0px;">
                                                    <div class="simplebar-content-wrapper" tabindex="0" role="region"
                                                        aria-label="scrollable content"
                                                        style="height: auto; overflow: hidden;">
                                                        <div class="simplebar-content" style="padding: 0px;">
                                                            <li class="list-group-item bg-light">
                                                                <div class="row">
                                                                    <div class="col">
                                                                        <a class="text-body" href="#">
                                                                            <div class="d-flex">
                                                                                <img src="{{ $imgCheck }}"
                                                                                    alt=""
                                                                                    class="avatar-md rounded-circle">
                                                                                <div class="ms-3">
                                                                                    <h5 class="fw-bold mb-1">Kristin
                                                                                        Watson:</h5>
                                                                                    <p class="mb-3 text-body">Krisitn
                                                                                        Watsan like your comment on
                                                                                        course Javascript Introduction!
                                                                                    </p>
                                                                                    <span class="fs-6">
                                                                                        <span>
                                                                                            <span
                                                                                                class="fe fe-thumbs-up text-success me-1"></span>
                                                                                            2 hours ago,
                                                                                        </span>
                                                                                        <span class="ms-1">2:19
                                                                                            PM</span>
                                                                                    </span>
                                                                                </div>
                                                                            </div>
                                                                        </a>
                                                                    </div>
                                                                    <div class="col-auto text-center me-2">
                                                                        <a href="#" class="badge-dot bg-info"
                                                                            data-bs-toggle="tooltip"
                                                                            data-bs-placement="top"
                                                                            aria-label="Mark as read"
                                                                            data-bs-original-title="Mark as read"></a>
                                                                        <div>
                                                                            <a href="#" class="bg-transparent"
                                                                                data-bs-toggle="tooltip"
                                                                                data-bs-placement="top"
                                                                                aria-label="Remove"
                                                                                data-bs-original-title="Remove">
                                                                                <i class="fe fe-x"></i>
                                                                            </a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                            <li class="list-group-item">
                                                                <div class="row">
                                                                    <div class="col">
                                                                        <a class="text-body" href="#">
                                                                            <div class="d-flex">
                                                                                <img src="../assets/images/avatar/avatar-2.jpg"
                                                                                    alt=""
                                                                                    class="avatar-md rounded-circle">
                                                                                <div class="ms-3">
                                                                                    <h5 class="fw-bold mb-1">Brooklyn
                                                                                        Simmons</h5>
                                                                                    <p class="mb-3 text-body">Just
                                                                                        launched a new Courses React for
                                                                                        Beginner.</p>
                                                                                    <span class="fs-6">
                                                                                        <span>
                                                                                            <span
                                                                                                class="fe fe-thumbs-up text-success me-1"></span>
                                                                                            Oct 9,
                                                                                        </span>
                                                                                        <span class="ms-1">1:20
                                                                                            PM</span>
                                                                                    </span>
                                                                                </div>
                                                                            </div>
                                                                        </a>
                                                                    </div>
                                                                    <div class="col-auto text-center me-2">
                                                                        <a href="#" class="badge-dot bg-secondary"
                                                                            data-bs-toggle="tooltip"
                                                                            data-bs-placement="top"
                                                                            aria-label="Mark as unread"
                                                                            data-bs-original-title="Mark as unread"></a>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                            <li class="list-group-item">
                                                                <div class="row">
                                                                    <div class="col">
                                                                        <a class="text-body" href="#">
                                                                            <div class="d-flex">
                                                                                <img src="../assets/images/avatar/avatar-3.jpg"
                                                                                    alt=""
                                                                                    class="avatar-md rounded-circle">
                                                                                <div class="ms-3">
                                                                                    <h5 class="fw-bold mb-1">Jenny
                                                                                        Wilson</h5>
                                                                                    <p class="mb-3 text-body">Krisitn
                                                                                        Watsan like your comment on
                                                                                        course Javascript Introduction!
                                                                                    </p>
                                                                                    <span class="fs-6">
                                                                                        <span>
                                                                                            <span
                                                                                                class="fe fe-thumbs-up text-info me-1"></span>
                                                                                            Oct 9,
                                                                                        </span>
                                                                                        <span class="ms-1">1:56
                                                                                            PM</span>
                                                                                    </span>
                                                                                </div>
                                                                            </div>
                                                                        </a>
                                                                    </div>
                                                                    <div class="col-auto text-center me-2">
                                                                        <a href="#" class="badge-dot bg-secondary"
                                                                            data-bs-toggle="tooltip"
                                                                            data-bs-placement="top"
                                                                            aria-label="Mark as unread"
                                                                            data-bs-original-title="Mark as unread"></a>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                            <li class="list-group-item">
                                                                <div class="row">
                                                                    <div class="col">
                                                                        <a class="text-body" href="#">
                                                                            <div class="d-flex">
                                                                                <img src="../assets/images/avatar/avatar-4.jpg"
                                                                                    alt=""
                                                                                    class="avatar-md rounded-circle">
                                                                                <div class="ms-3">
                                                                                    <h5 class="fw-bold mb-1">Sina Ray
                                                                                    </h5>
                                                                                    <p class="mb-3 text-body">You earn
                                                                                        new certificate for complete the
                                                                                        Javascript Beginner course.</p>
                                                                                    <span class="fs-6">
                                                                                        <span>
                                                                                            <span
                                                                                                class="fe fe-award text-warning me-1"></span>
                                                                                            Oct 9,
                                                                                        </span>
                                                                                        <span class="ms-1">1:56
                                                                                            PM</span>
                                                                                    </span>
                                                                                </div>
                                                                            </div>
                                                                        </a>
                                                                    </div>
                                                                    <div class="col-auto text-center me-2">
                                                                        <a href="#" class="badge-dot bg-secondary"
                                                                            data-bs-toggle="tooltip"
                                                                            data-bs-placement="top"
                                                                            aria-label="Mark as unread"
                                                                            data-bs-original-title="Mark as unread"></a>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="simplebar-placeholder" style="width: 0px; height: 0px;"></div>
                                        </div>
                                        <div class="simplebar-track simplebar-horizontal" style="visibility: hidden;">
                                            <div class="simplebar-scrollbar" style="width: 0px; display: none;"></div>
                                        </div>
                                        <div class="simplebar-track simplebar-vertical" style="visibility: hidden;">
                                            <div class="simplebar-scrollbar"
                                                style="height: 0px; transform: translate3d(0px, 0px, 0px); display: none;">
                                            </div>
                                        </div>
                                    </ul>
                                    <div class="border-top px-3 pt-3 pb-0">
                                        <a href="../pages/notification-history.html" class="text-link fw-semibold">See
                                            all Notifications</a>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="dropdown ms-2 d-inline-block position-static">
                            <a class="rounded-circle" href="#" data-bs-toggle="dropdown" data-bs-display="static"
                                aria-expanded="false">
                                <div class="avatar avatar-md avatar-indicators avatar-online">
                                    <img alt="avatar" src="{{ $imgCheck }}" class="rounded-circle">
                                </div>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end position-absolute mx-3 my-5">
                                <div class="dropdown-item">
                                    <div class="d-flex">
                                        <div class="avatar avatar-md avatar-indicators avatar-online">
                                            <img alt="avatar" src="{{ $imgCheck }}" class="rounded-circle">
                                        </div>
                                        <div class="ms-3 lh-1">
                                            <h5 class="mb-1">{{ auth()->user()->name }}</h5>
                                            <p class="mb-0">
                                                {{ auth()->user()->email }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="dropdown-divider"></div>
                                <ul class="list-unstyled">
                                    @if (Auth::user()->role == 'student')
                                        <li>
                                            <a class="dropdown-item" href="{{ route('student.dashboard') }}">
                                                <i class="fe fe-home me-2"></i>
                                                Dashboard
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('student.profile.edit') }}">
                                                <i class="fe fe-user me-2"></i>
                                                Profile
                                            </a>
                                        </li>
                                    @elseif (Auth::user()->role == 'instructor')
                                        <li>
                                            <a class="dropdown-item" href="{{ route('instructor.dashboard') }}">
                                                <i class="fe fe-home me-2"></i>
                                                Dashboard
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('instructor.profile.edit') }}">
                                                <i class="fe fe-user me-2"></i>
                                                Profile
                                            </a>
                                        </li>
                                    @elseif (Auth::user()->role == 'staff')
                                        <li>
                                            <a class="dropdown-item" href="{{ route('staff.dashboard') }}">
                                                <i class="fe fe-home me-2"></i>
                                                Dashboard
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="javascript:void(0);">
                                                <i class="fe fe-user me-2"></i>
                                                Profile
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                                <div class="dropdown-divider"></div>
                                <ul class="list-unstyled">
                                    <li>
                                        <a class="dropdown-item" href="javascript:void(0);"
                                            onclick="event.preventDefault();
                                        document.getElementById('all_role_logout_form').submit();">
                                            <i class="fe fe-power me-2"></i>
                                            Sign Out
                                        </a>
                                        <form method="POST" action="{{ route('logout') }}" id="all_role_logout_form">
                                            @csrf
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                @else
                    <a href="#" class="btn btn-icon btn-light rounded-circle d-none d-md-inline-flex ms-2"><i
                            class="fe fe-shopping-cart align-middle"></i></a>
                    <a href="{{ route('login') }}" class="btn btn-outline-primary ms-2 d-none d-lg-block">Sign
                        in</a>
                    <a href="{{ route('register') }}" class="btn btn-primary ms-2 d-none d-lg-block">Sign up</a>
                @endauth
                <!-- Button -->
                <button class="navbar-toggler collapsed ms-2 ms-lg-0" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbar-default" aria-controls="navbar-default" aria-expanded="false"
                    aria-label="Toggle navigation">
                    <span class="icon-bar top-bar mt-0"></span>
                    <span class="icon-bar middle-bar"></span>
                    <span class="icon-bar bottom-bar"></span>
                </button>
            </div>
        </div>

        <!-- Collapse -->
        <div class="collapse navbar-collapse" id="navbar-default">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarLanding" data-bs-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">Landings</a>
                    <ul class="dropdown-menu" aria-labelledby="navbarLanding">
                        <li>
                            <h4 class="dropdown-header">Landings</h4>
                        </li>
                        <li>
                            <a href="../../mentor/mentor.html" class="dropdown-item">
                                <span>Mentor</span>
                                <span class="badge bg-primary ms-2">New</span>
                            </a>
                        </li>
                        <li>
                            <a href="../../pages/landings/landing-education.html" class="dropdown-item">Education</a>
                        </li>
                        <li>
                            <a href="../../pages/landings/home-academy.html" class="dropdown-item">Home
                                Academy</a>
                        </li>
                        <li>
                            <a href="../../pages/landings/landing-courses.html" class="dropdown-item">Courses</a>
                        </li>
                        <li>
                            <a href="../../pages/landings/course-lead.html" class="dropdown-item">Lead Course</a>
                        </li>
                        <li>
                            <a href="../../pages/landings/request-access.html" class="dropdown-item">Request
                                Access</a>
                        </li>
                        <li>
                            <a href="../../pages/landings/landing-sass.html" class="dropdown-item">SaaS</a>
                        </li>

                        <li>
                            <a href="../../pages/landings/landing-job.html" class="dropdown-item">Job Listing</a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarPages" data-bs-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">Pages</a>
                    <ul class="dropdown-menu dropdown-menu-arrow" aria-labelledby="navbarPages">
                        <li class="dropdown-submenu dropend">
                            <a class="dropdown-item dropdown-list-group-item dropdown-toggle"
                                href="#">Courses</a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="../../pages/course-single.html">Course
                                        Single</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="../../pages/course-single-v2.html">Course
                                        Single v2</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="../../pages/course-resume.html">Course
                                        Resume</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="../../pages/course-category.html">Course
                                        Category</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="../../pages/course-checkout.html">Course
                                        Checkout</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="../../pages/course-filter-list.html">Course
                                        List/Grid</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="../../pages/add-course.html">Add New Course</a>
                                </li>
                            </ul>
                        </li>
                        <li class="dropdown-submenu dropend">
                            <a class="dropdown-item dropdown-list-group-item dropdown-toggle" href="#">Paths</a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="../../pages/course-path.html" class="dropdown-item">Browse Path</a>
                                </li>
                                <li>
                                    <a href="../../pages/course-path-single.html" class="dropdown-item">Path
                                        Single</a>
                                </li>
                            </ul>
                        </li>
                        <li class="dropdown-submenu dropend">
                            <a class="dropdown-item dropdown-list-group-item dropdown-toggle" href="#">Blog</a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="../../pages/blog.html">Listing</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="../../pages/blog-single.html">Article</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="../../pages/blog-category.html">Category</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="../../pages/blog-sidebar.html">Sidebar</a>
                                </li>
                            </ul>
                        </li>

                        <li class="dropdown-submenu dropend">
                            <a class="dropdown-item dropdown-list-group-item dropdown-toggle"
                                href="#">Career</a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="../../pages/career.html">Overview</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="../../pages/career-list.html">Listing</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="../../pages/career-single.html">Opening</a>
                                </li>
                            </ul>
                        </li>
                        <li class="dropdown-submenu dropend">
                            <a class="dropdown-item dropdown-list-group-item dropdown-toggle"
                                href="#">Portfolio</a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="../../pages/portfolio.html">List</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="../../pages/portfolio-single.html">Single</a>
                                </li>
                            </ul>
                        </li>
                        <li class="dropdown-submenu dropend">
                            <a class="dropdown-item dropdown-list-group-item dropdown-toggle"
                                href="#">Mentor</a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="../../mentor/mentor.html">Home</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="../../mentor/mentor-list.html">List</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="../../mentor/mentor-single.html">Single</a>
                                </li>
                            </ul>
                        </li>
                        <li class="dropdown-submenu dropend">
                            <a class="dropdown-item dropdown-list-group-item dropdown-toggle" href="#">Job</a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="../../pages/landings/landing-job.html">Home</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="../../pages/jobs/job-listing.html">List</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="../../pages/jobs/job-grid.html">Grid</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="../../pages/jobs/job-single.html">Single</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="../../pages/jobs/company-list.html">Company
                                        List</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="../../pages/jobs/company-about.html">Company
                                        Single</a>
                                </li>
                            </ul>
                        </li>
                        <li class="dropdown-submenu dropend">
                            <a class="dropdown-item dropdown-list-group-item dropdown-toggle"
                                href="#">Specialty</a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="../../pages/coming-soon.html">Coming Soon</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="../../pages/404-error.html">Error 404</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="../../pages/maintenance-mode.html">Maintenance
                                        Mode</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="../../pages/terms-condition-page.html">Terms &
                                        Conditions</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <hr class="mx-3" />
                        </li>

                        <li>
                            <a class="dropdown-item" href="../../pages/about.html">About</a>
                        </li>
                        <li class="dropdown-submenu dropend">
                            <a class="dropdown-item dropdown-list-group-item dropdown-toggle" href="#">Help
                                Center</a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="../../pages/help-center.html">Help Center</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="../../pages/help-center-faq.html">FAQ's</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="../../pages/help-center-guide.html">Guide</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="../../pages/help-center-guide-single.html">Guide
                                        Single</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="../../pages/help-center-support.html">Support</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a class="dropdown-item" href="../../pages/pricing.html">Pricing</a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="../../pages/compare-plan.html">Compare Plan</a>
                        </li>

                        <li>
                            <a class="dropdown-item" href="../../pages/contact.html">Contact</a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">Accounts</a>
                    <ul class="dropdown-menu dropdown-menu-arrow">
                        <li>
                            <h4 class="dropdown-header">Accounts</h4>
                        </li>
                        <li class="dropdown-submenu dropend">
                            <a class="dropdown-item dropdown-list-group-item dropdown-toggle"
                                href="#">Instructor</a>
                            <ul class="dropdown-menu">
                                <li class="text-wrap">
                                    <h5 class="dropdown-header text-dark">Instructor</h5>
                                    <p class="dropdown-text mb-0">Instructor dashboard for manage courses and
                                        earning.</p>
                                </li>
                                <li>
                                    <hr class="mx-3" />
                                </li>
                                <li>
                                    <a class="dropdown-item"
                                        href="../../pages/dashboard-instructor.html">Dashboard</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="../../pages/instructor-profile.html">Profile</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="../../pages/instructor-courses.html">My
                                        Courses</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="../../pages/instructor-order.html">Orders</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="../../pages/instructor-reviews.html">Reviews</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="../../pages/instructor-students.html">Students</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="../../pages/instructor-payouts.html">Payouts</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="../../pages/instructor-earning.html">Earning</a>
                                </li>
                                <li class="dropdown-submenu dropend">
                                    <a class="dropdown-item dropdown-list-group-item dropdown-toggle"
                                        href="#">Quiz</a>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a class="dropdown-item" href="../../pages/instructor-quiz.html">Quiz</a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item"
                                                href="../../pages/instructor-quiz-details.html">Single</a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item"
                                                href="../../pages/instructor-quiz-result.html">Result</a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                        <li class="dropdown-submenu dropend">
                            <a class="dropdown-item dropdown-list-group-item dropdown-toggle"
                                href="#">Students</a>
                            <ul class="dropdown-menu">
                                <li class="text-wrap">
                                    <h5 class="dropdown-header text-dark">Students</h5>
                                    <p class="dropdown-text mb-0">Students dashboard to manage your courses and
                                        subscriptions.</p>
                                </li>
                                <li>
                                    <hr class="mx-3" />
                                </li>
                                <li>
                                    <a class="dropdown-item" href="../../pages/dashboard-student.html">Dashboard</a>
                                </li>
                                <li>
                                    <a class="dropdown-item"
                                        href="../../pages/student-subscriptions.html">Subscriptions</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="../../pages/payment-method.html">Payments</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="../../pages/billing-info.html">Billing Info</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="../../pages/invoice.html">Invoice</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="../../pages/invoice-details.html">Invoice
                                        Details</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="../../pages/dashboard-student.html">Bookmarked</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="../../pages/dashboard-student.html">My Path</a>
                                </li>
                                <li class="dropdown-submenu dropend">
                                    <a class="dropdown-item dropdown-list-group-item dropdown-toggle"
                                        href="#">Quiz</a>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a class="dropdown-item" href="../../pages/student-quiz.html">Quiz</a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item"
                                                href="../../pages/student-quiz-attempt.html">Attempt</a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item"
                                                href="../../pages/student-quiz-start.html">Start</a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item"
                                                href="../../pages/student-quiz-result.html">Result</a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                        <li class="dropdown-submenu dropend">
                            <a class="dropdown-item dropdown-list-group-item dropdown-toggle" href="#">Admin</a>
                            <ul class="dropdown-menu">
                                <li class="text-wrap">
                                    <h5 class="dropdown-header text-dark">Master Admin</h5>
                                    <p class="dropdown-text mb-0">Master admin dashboard to manage courses, user,
                                        site setting , and work with amazing apps.</p>
                                </li>
                                <li>
                                    <hr class="mx-3" />
                                </li>
                                <li class="px-3 d-grid">
                                    <a href="../../pages/dashboard/admin-dashboard.html"
                                        class="btn btn-sm btn-primary">Go to Dashboard</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <hr class="mx-3" />
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('login') }}">Sign In</a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('register') }}">Sign Up</a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="../../pages/forget-password.html">Forgot Password</a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="../../pages/profile-edit.html">Edit Profile</a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="../../pages/security.html">Security</a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="../../pages/social-profile.html">Social Profiles</a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="../../pages/notifications.html">Notifications</a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="../../pages/profile-privacy.html">Privacy Settings</a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="../../pages/delete-profile.html">Delete Profile</a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="../../pages/linked-accounts.html">Linked Accounts</a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item dropdown dropdown-fullwidth">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">Mega Menu</a>
                    <div class="dropdown-menu dropdown-menu-md">
                        <div class="px-4 pt-2 pb-2">
                            <div class="row">
                                <div class="col-12">
                                    <div class="lh-1 mb-5">
                                        <h3 class="mb-1">Earn a Degree</h3>
                                        <p>Breakthrough pricing on 100% online degrees designed to fit into your
                                            life.</p>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-12">
                                    <div class="border-bottom pb-2 mb-3">
                                        <h5 class="mb-0">Degrees</h5>
                                    </div>
                                    <div>
                                        <a href="#">
                                            <div class="d-flex mb-3">
                                                <img src="/frontend/assets/images/png/degree-1.png" alt="" />
                                                <div class="ms-2">
                                                    <small class="text-body">University of Michigan</small>
                                                    <h6 class="mb-0">Master of Applied Data Science</h6>
                                                </div>
                                            </div>
                                        </a>
                                        <a href="#">
                                            <div class="d-flex mb-3">
                                                <img src="/frontend/assets/images/png/degree-2.png" alt="" />
                                                <div class="ms-2">
                                                    <small class="text-body">A&B College 1980</small>
                                                    <h6 class="mb-0">MBA in Business Analytics</h6>
                                                </div>
                                            </div>
                                        </a>
                                        <a href="#">
                                            <div class="d-flex mb-3">
                                                <img src="/frontend/assets/images/png/degree-3.png" alt="" />
                                                <div class="ms-2">
                                                    <small class="text-body">Imperial College London</small>
                                                    <h6 class="mb-0">Master of Science in Machine</h6>
                                                </div>
                                            </div>
                                        </a>
                                        <a href="#">
                                            <div class="d-flex mb-3">
                                                <img src="/frontend/assets/images/png/degree-4.png" alt="" />
                                                <div class="ms-2">
                                                    <small class="text-body">University of Colorado</small>
                                                    <h6 class="mb-0">Master of Computer Science</h6>
                                                </div>
                                            </div>
                                        </a>
                                        <div class="mt-4">
                                            <a href="#" class="btn btn-outline-primary btn-sm">View all
                                                degree</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-12 mt-4 mt-lg-0">
                                    <div class="border-bottom pb-2 mb-3">
                                        <h5 class="mb-0">Certificate Programs</h5>
                                    </div>
                                    <div>
                                        <a href="#">
                                            <div class="d-flex mb-3">
                                                <img src="/frontend/assets/images/png/google.png" alt="" />
                                                <div class="ms-2">
                                                    <small class="text-body">No Prerequisites</small>
                                                    <h6 class="mb-0">Google Data Analytics</h6>
                                                </div>
                                            </div>
                                        </a>
                                        <a href="#">
                                            <div class="d-flex mb-3">
                                                <img src="/frontend/assets/images/png/IBM.png" alt="" />
                                                <div class="ms-2">
                                                    <small class="text-body">No Prerequisites</small>
                                                    <h6 class="mb-0">IBM Data Science</h6>
                                                </div>
                                            </div>
                                        </a>
                                        <a href="#">
                                            <div class="d-flex mb-3">
                                                <img src="/frontend/assets/images/png/microsoft.png" alt="" />
                                                <div class="ms-2">
                                                    <small class="text-body">Expert Feedback</small>
                                                    <h6 class="mb-0">Machine Leaning for Analytics</h6>
                                                </div>
                                            </div>
                                        </a>
                                        <a href="#">
                                            <div class="d-flex mb-3">
                                                <img src="/frontend/assets/images/png/tensorflow.png"
                                                    alt="" />
                                                <div class="ms-2">
                                                    <small class="text-body">Certification Prerequisites</small>
                                                    <h6 class="mb-0">TensorFlow Developer Certificate</h6>
                                                </div>
                                            </div>
                                        </a>
                                        <a href="#">
                                            <div class="d-flex mb-3">
                                                <img src="/frontend/assets/images/png/meta.png" alt="" />
                                                <div class="ms-2">
                                                    <small class="text-body">University of Colorado</small>
                                                    <h6 class="mb-0">Meta Marketing Analytics</h6>
                                                </div>
                                            </div>
                                        </a>
                                        <div class="mt-4">
                                            <a href="#" class="btn btn-outline-primary btn-sm">View all
                                                Certificates</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-12 mt-4 mt-lg-0">
                                    <div class="border-bottom pb-2 mb-3">
                                        <h5 class="mb-0">Popular Skills</h5>
                                    </div>
                                    <div>
                                        <ul class="nav flex-column">
                                            <li class="nav-item">
                                                <a href="#" class="nav-link">Python</a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="#" class="nav-link">SQL</a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="#" class="nav-link">Microsoft Excel</a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="#" class="nav-link">Machine Learning</a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="#" class="nav-link">Data Science</a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="#" class="nav-link">Data Analytics</a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="#" class="nav-link">Power BI</a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="#" class="nav-link">Artificial Intelligence</a>
                                            </li>
                                        </ul>

                                        <div class="mt-4">
                                            <a href="#" class="btn btn-outline-primary btn-sm">View all
                                                Skills</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        <i class="fe fe-more-horizontal"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-md" aria-labelledby="navbarDropdown">
                        <div class="list-group">
                            <a class="list-group-item list-group-item-action border-0" href="../../docs/index.html">
                                <div class="d-flex align-items-center">
                                    <i class="fe fe-file-text fs-3 text-primary"></i>
                                    <div class="ms-3">
                                        <h5 class="mb-0">Documentations</h5>
                                        <p class="mb-0 fs-6">Browse the all documentation</p>
                                    </div>
                                </div>
                            </a>
                            <a class="list-group-item list-group-item-action border-0"
                                href="../../docs/bootstrap-5-snippets.html">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-files fs-3 text-primary"></i>
                                    <div class="ms-3">
                                        <h5 class="mb-0">Snippet</h5>
                                        <p class="mb-0 fs-6">Bunch of Snippet</p>
                                    </div>
                                </div>
                            </a>
                            <a class="list-group-item list-group-item-action border-0"
                                href="../../docs/changelog.html">
                                <div class="d-flex align-items-center">
                                    <i class="fe fe-layers fs-3 text-primary"></i>
                                    <div class="ms-3">
                                        <h5 class="mb-0">
                                            Changelog
                                            <span class="text-primary ms-1" id="changelog"></span>
                                        </h5>
                                        <p class="mb-0 fs-6">See what's new</p>
                                    </div>
                                </div>
                            </a>
                            <a class="list-group-item list-group-item-action border-0"
                                href="https://geeksui.codescandy.com/geeks-rtl/" target="_blank">
                                <div class="d-flex align-items-center">
                                    <i class="fe fe-toggle-right fs-3 text-primary"></i>
                                    <div class="ms-3">
                                        <h5 class="mb-0">RTL demo</h5>
                                        <p class="mb-0 fs-6">RTL Pages</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>
