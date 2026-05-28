<div class="header">
    <div class="schoollogo">

        <!-- a tag with logo and tittle -->
        <a href="index.html">
            <img src="{{ asset('/frontend/assets-new/images/ICTLogo9.jpg') }}" alt="navimg">
        </a>
        <h2>ICT PROFESSIONAL<br><span> TRAINING CENTER</span></h2>
    </div>

    <!-- Desktop nav -->
    <ul>
        <li class="dropdown">
            <a href="javascript:void(0)" class="dropbtn">Course</a>
            <div class="dropdown-content">
                <div class="dropdown-column">
                    <div class="has-submenu">
                        <a href="#">Programming<span>›</span></a>
                        <div class="submenu">
                            <a href="#">C++</a>
                            <a href="#">C#</a>
                            <a href="#">Java I</a>
                            <a href="#">Java II</a>
                            <a href="#">Python</a>
                            <a href="#">DevOps</a>
                            <a href="#">Project Managerment</a>
                            <a href="#">Software Development with Python</a>
                            <a href="#">Software Development with Java</a>
                            <a href="#">Software Development with Python</a>
                        </div>
                    </div>
                    <div class="has-submenu">
                        <a href="#">Website <span>›</span></a>
                        <div class="submenu">
                            <a href="#">Web Frontend</a>
                            <a href="#">Web Backend</a>
                            <a href="#">Full Stack Development</a>
                            <a href="#">Web Backend(Node js)</a>
                        </div>
                    </div>
                    <div class="has-submenu">
                        <a href="#">Design <span>›</span></a>
                        <div class="submenu">
                            <a href="#">UI / UX</a>
                            <a href="#">Graphic Design</a>
                            <a href="#">Video Editor</a>
                        </div>
                    </div>
                    <div class="has-submenu">
                        <a href="#">Data <span>›</span></a>
                        <div class="submenu">
                            <a href="#">Data Managerment System</a>
                            <a href="#">Data Analytic</a>
                            <a href="#">Machine Learning</a>
                            <a href="#">Deep Learning</a>
                            <a href="#">Power BI</a>
                        </div>
                    </div>
                    <div class="has-submenu">
                        <a href="#">Computer Office<span>›</span></a>
                        <div class="submenu">
                            <a href="#">Microsoft Office</a>
                            <a href="#">Advance Excel</a>
                            <a href="#">Excel VBA</a>
                        </div>
                    </div>
                    <div class="has-submenu">
                        <a href="#">Networking<span>›</span></a>
                        <div class="submenu">
                            <a href="#">Networking CCNA</a>
                            <a href="#">Advance Networking</a>
                            <a href="#">Cyber Security</a>
                            <a href="#">Cloud Computing</a>
                        </div>
                    </div>
                </div>
            </div>
        </li>
        <li><a href="about.html">About</a></li>
        <li><a href="blog.html">Blog</a></li>
        <li><a href="index.html">Contact</a></li>
    </ul>

    <!-- <ul>
                <li class="dropdown">
                    <a href="javascript:void(0)" class="dropbtn">Course</a>
                    <div class="dropdown-content">
                        <a href="#">Website</a>
                        <a href="#">Design</a>
                        <a href="#">Programming</a>
                        <a href="#">Data</a>
                        <a href="#">Computer Office</a>
                        <a href="#">Network</a>
                    </div>
                </li>
                <li><a href="about.html">About</a></li>
                <li><a href="blog.html">Blog</a></li>
                <li><a href="index.html">Contact</a></li>
            </ul> -->
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
