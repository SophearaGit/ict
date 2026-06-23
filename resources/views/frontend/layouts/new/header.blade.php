<style>
    .user-dropdown {
        position: relative;
    }

    .user-dropdown::after {
        content: '';
        position: absolute;
        top: 100%;
        right: 0;
        width: 220px;
        height: 15px;
    }

    .user-btn {
        display: flex;
        align-items: center;
        gap: 10px;
        border: none;
        background: transparent;
        cursor: pointer;
        padding: 8px 12px;
        border-radius: 12px;
        font-size: 16px;
        font-weight: 600;
        color: inherit;
    }

    .user-avatar {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #eee;
    }

    .user-menu {
        position: absolute;
        top: 100%;
        right: 0;
        min-width: 220px;
        background: #fff;
        border-radius: 14px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, .15);
        overflow: hidden;
        display: none;
        z-index: 9999;
    }

    .user-dropdown:hover .user-menu {
        display: block;
    }

    .user-menu a,
    .user-menu button {
        width: 100%;
        display: block;
        padding: 14px 18px;
        border: none;
        background: transparent;
        text-align: left;
        text-decoration: none;
        color: #333;
        cursor: pointer;
        font-size: 15px;
    }

    .user-menu a:hover,
    .user-menu button:hover {
        background: #f5f5f5;
    }

    .user-menu form {
        margin: 0;
    }
</style>
<div class="header">
    <div class="schoollogo">

        <!-- a tag with logo and tittle -->
        <a href="{{ route('home') }}" class="logo">
            <img src="{{ asset('./frontend/asset/images/ICTLogo9.jpg') }}" alt="navimg">
        </a>
        <h2>ICT PROFESSIONAL<br><span> TRAINING CENTER</span></h2>
    </div>

    <!-- Desktop nav -->
    <ul>
        <li class="dropdown">
            <a href="{{ route('course') }}" class="dropbtn">Course</a>
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
        <li><a href="#">Blog</a></li>
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
