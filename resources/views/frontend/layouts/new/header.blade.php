<div class="header">
    <div class="schoollogo">

        <!-- a tag with logo and tittle -->
        <a href="index.html">
            <img src="{{ asset('/frontend/assets-new/images/ICTLogo9.jpg') }}" alt="navimg">
        </a>
        <h2>ICT PROFESSIONAL<br><span> TRAINING CENTER</span></h2>
    </div>
    <ul>
        <li class="dropdown">
            <a href="javascript:void(0)" class="dropbtn">Course</a>
            <div class="dropdown-content">
                <div class="dropdown-column">
                    @php
                        $displayedTitles = [];
                    @endphp

                    @foreach ($categories_for_frontend as $category)
                        <div class="has-submenu">
                            <a href="#">{{ $category->name }}<span>›</span></a>

                            <div class="submenu">
                                @foreach ($category->courses as $course)
                                    @continue(in_array($course->title, $displayedTitles))

                                    <a href="#">{{ $course->title }}</a>

                                    @php
                                        $displayedTitles[] = $course->title;
                                    @endphp
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </li>
        <li><a href="about.html">About</a></li>
        <li><a href="blog.html">Blog</a></li>
        <li><a href="index.html">Contact</a></li>
    </ul>

    <button class="theme-toggle-btn" id="themeToggle" title="Toggle theme">
        <i class="fa-solid fa-moon" id="themeIcon"></i>
    </button>
    <div class="navbtn">

        @auth

            @php
                $imgCheck =
                    auth()->user()->image !== 'no-img.jpg' ? auth()->user()->image : '/default-images/user/both.jpg';
            @endphp

            <div class="user-dropdown">
                <button class="user-btn">
                    <img src="{{ asset($imgCheck) }}" alt="User">
                    <span>{{ auth()->user()->name }}</span>
                </button>

                <div class="user-dropdown-content">
                    <div class="user-dropdown-content-inner"> {{-- ← add this wrapper --}}

                        @if (Auth::user()->role == 'student')
                            <a href="{{ route('student.dashboard') }}">Dashboard</a>
                            <a href="{{ route('student.profile.edit') }}">Profile</a>
                        @elseif (Auth::user()->role == 'instructor')
                            <a href="{{ route('instructor.dashboard') }}">Dashboard</a>
                            <a href="{{ route('instructor.profile.edit') }}">Profile</a>
                        @elseif (Auth::user()->role == 'staff')
                            <a href="{{ route('staff.dashboard') }}">Dashboard</a>
                            <a href="{{ route('staff.profile.edit') }}">Profile</a>
                        @elseif (Auth::user()->role == 'unknown')
                            <a href="javascript:void(0)">Account Disabled</a>
                        @endif

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="logout-btn">Logout</button>
                        </form>

                    </div> {{-- ← close wrapper --}}
                </div>
            </div>
        @else
            <button id="loginbtn" onclick="window.location.href='{{ route('login') }}'">
                Login
            </button>

            {{-- <button id="registerbtn" onclick="window.location.href='{{ route('register') }}'">
                Register
            </button> --}}

        @endauth

    </div>

    <!-- Hamburger (mobile only) -->
    <button class="hamburger" id="hamburger" aria-label="Toggle menu">
        <span></span>
        <span></span>
        <span></span>
    </button>
</div>
