<div class="header">
    <div class="schoollogo">

        <!-- a tag with logo and tittle -->
        <a href="{{ route('home') }}">
            <img src="{{ asset('frontend/asset/images/ICTLogo9.jpg') }}" alt="navimg" title="Go to Home page">
        </a>
        <h2>ICT PROFESSIONAL<br><span> TRAINING CENTER</span></h2>
    </div>

    <!-- Desktop nav -->
    <ul>
        <li class="dropdown">
            <a href="{{ route('course') }}" class="dropbtn" title="Click Me">Course</a>
            <div class="dropdown-content">
                <div class="dropdown-column">
                    @foreach ($categories_for_frontend as $category)
                        <div class="has-submenu">
                            <a href="#">
                                {{ $category->name }}
                                <span>›</span>
                            </a>
                            <div class="submenu">
                                @foreach ($category->courses->unique('title') as $course)
                                    <a href="{{ route('course', ['search' => $course->title]) }}">
                                        {{ $course->title }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </li>
        <li><a href="{{ route('about') }}">About</a></li>
        <li><a href="{{ route('blog') }}">Blog</a></li>
        <li><a href="{{ route('contact') }}">Contact</a></li>
    </ul>
    <button class="theme-toggle-btn" id="themeToggle">
        <i class="fa-solid fa-moon" id="themeIcon"></i>
    </button>
    <div class="navbtn">
        @guest
            <button id="loginbtn" onclick="window.location.href='{{ route('login') }}'">
                Login
            </button>
            <div class="">
                <button id="registerbtn" onclick="#" disabled>
                    Register
                </button>
            </div>
        @else
            <div class="user-dropdown">
                <button class="user-btn">
                    <img src="{{ asset(
                        empty(Auth::user()->image) || Auth::user()->image === 'no-img.jpg'
                            ? 'default-images/user/both.jpg'
                            : ltrim(Auth::user()->image, '/'),
                    ) }}"
                        alt="{{ Auth::user()->name }}" class="user-avatar">
                    <span>{{ Auth::user()->name }}</span>
                    <i class="fa-solid fa-chevron-down"></i>
                </button>
                <div class="user-menu">
                    <a href="{{ route(Auth::user()->role . '.dashboard') }}">
                        <i class="fa-solid fa-gauge-high"></i>
                        Dashboard
                    </a>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit">
                            <i class="fa-solid fa-right-from-bracket"></i>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        @endguest
    </div>

    <!-- Hamburger (mobile only) -->
    <button class="hamburger" id="hamburger" aria-label="Toggle menu">
        <span></span>
        <span></span>
        <span></span>
    </button>
</div>
