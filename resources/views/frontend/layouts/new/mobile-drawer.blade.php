<!-- ═══ MOBILE DRAWER ═══ -->
<div class="mobile-drawer" id="mobileDrawer">
    @auth
        <div class="acc-img">
            <img src="{{ asset(
                empty(Auth::user()->image) || Auth::user()->image === 'no-img.jpg'
                    ? 'default-images/user/both.jpg'
                    : ltrim(Auth::user()->image, '/'),
            ) }}"
                alt="account-img-mobile">
            <div class="name-userr">
                <p>{{ Auth::user()->name }}</p>
                <span>{{ ucfirst(Auth::user()->role) }}</span>
            </div>
        </div>
    @endauth
    <nav>
        <div class="course-mobile-dropdown">
            <h4><a href="{{ route('course') }}" style="text-decoration: none;">Course</a></h4>
            <ul>
                @foreach ($categories_for_frontend as $category)
                    <li>
                        <div class="menu-item" onclick="toggleMenu(this)">
                            {{ $category->name }}
                            <span class="arrow-one">▼</span>
                        </div>
                        <ul class="sub-menu">
                            @foreach ($category->courses->unique('title') as $course)
                                <li>
                                    <a href="{{ route('course', ['search' => $course->title]) }}">
                                        {{ $course->title }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                @endforeach
            </ul>
        </div>
        <ul>
            <li><a href="{{ route('about') }}">About</a></li>
            <li><a href="{{ route('blog') }}">Blog</a></li>
            <li><a href="{{ route('contact') }}">Contact</a></li>
            @auth
                <li>
                    <a href="{{ route(Auth::user()->role . '.dashboard') }}">
                        <i class="fa-solid fa-gauge-high"></i> Dashboard
                    </a>
                </li>
            @endauth
        </ul>
    </nav>
    <div class="drawer-btns">
        @guest
            <button class="drawer-login" onclick="window.location.href='{{ route('login') }}'">Login</button>
            <button class="drawer-register" disabled>Register</button>
        @else
            <form action="{{ route('logout') }}" method="POST" style="width:100%;">
                @csrf
                <button type="submit" class="drawer-login" style="width:100%;">
                    <i class="fa-solid fa-right-from-bracket"></i> Logout
                </button>
            </form>
        @endguest
    </div>
</div>
