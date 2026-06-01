@extends('frontend.layouts.new.master')
@section('page_title', isset($page_title) ? $page_title : 'Page Title Here')
@push('styles')
    <style>
        /* ─── User Dropdown Fix ─── */
        .user-dropdown {
            position: relative;
            display: inline-block;
        }

        .user-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            background: none;
            border: none;
            cursor: pointer;
            padding: 6px 10px;
            border-radius: 8px;
        }

        .user-btn img {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            object-fit: cover;
        }

        .user-dropdown-content {
            visibility: hidden;
            opacity: 0;
            position: absolute;
            top: 100%;
            /* sits right below the button */
            right: 0;
            min-width: 160px;
            /* ✅ KEY FIX: invisible padding bridges the gap between button and menu */
            padding-top: 8px;
            background: transparent;
            /* wrapper is transparent */
            z-index: 9999;
            transition: opacity 0.15s ease, visibility 0.15s ease;
            transition-delay: 120ms;
            /* grace period so it doesn't vanish instantly */
        }

        /* ✅ Show when hovering anywhere on the parent */
        .user-dropdown:hover .user-dropdown-content {
            visibility: visible;
            opacity: 1;
            transition-delay: 0ms;
        }

        /* The actual visible box is an inner div */
        .user-dropdown-content-inner {
            background: #1e1e1e;
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.4);
            padding: 6px 0;
            overflow: hidden;
        }

        .user-dropdown-content a,
        .user-dropdown-content .logout-btn {
            display: block;
            width: 100%;
            padding: 10px 16px;
            text-align: left;
            text-decoration: none;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 14px;
            color: #fff !important;
            /* ← add !important */
            box-sizing: border-box;
            white-space: nowrap;
        }

        .user-dropdown-content a:hover,
        .user-dropdown-content .logout-btn:hover {
            background: rgba(255, 255, 255, 0.08);
        }

        /* Light mode */
        @media (prefers-color-scheme: light) {
            .user-dropdown-content-inner {
                background: #ffffff;
                border-color: rgba(0, 0, 0, 0.1);
                box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
            }

            .user-dropdown-content a:hover,
            .user-dropdown-content .logout-btn:hover {
                background: rgba(0, 0, 0, 0.05);
            }
        }

        .acb {
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .acb.active-cat {
            /* style however fits your design, e.g: */
            border-bottom: 2px solid #3777ff;
            color: #3777ff;
        }

        .boxcard {
            transition: opacity 0.3s ease, transform 0.3s ease;
        }

        .boxcard.hidden {
            display: none;
        }

        .boxcard.fade-in {
            animation: fadeIn 0.3s ease forwards;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
@endpush
@section('content')

    <!-- ═══ CAROUSEL ═══ -->
    <div id="carouselExampleAutoplaying" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="0" class="active" aria-current="true"
                aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="1" aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="2" aria-label="Slide 3"></button>
        </div>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="{{ asset('/frontend/assets-new/images/slideshow4.jpg') }}" class="d-block w-100" alt="">
                <div class="carousel-caption d-none d-md-block">
                    <h1>Learn from the Best Teachers</h1>
                    <p>Some representative placeholder content for the first slide.</p>
                    <button>Get Started</button>
                </div>
            </div>
            <div class="carousel-item">
                <img src="{{ asset('/frontend/assets-new/images/ICT_slideShow1.jpg') }}" class="d-block w-100"
                    alt="Slide 1">
                <div class="carousel-caption d-none d-md-block">
                    <h1>Learn from the Best Teachers</h1>
                    <p>Some representative placeholder content for the first slide.</p>
                    <button>Get Started</button>
                </div>
            </div>
            <div class="carousel-item">
                <img src="{{ asset('/frontend/assets-new/images/ICT_ShildeShow2.jpg') }}" class="d-block w-100"
                    alt="Slide 2">
                <div class="carousel-caption d-none d-md-block">
                    <h1>Learn from the Best Teachers</h1>
                    <p>Some representative placeholder content for the first slide.</p>
                    <button>Get Started</button>
                </div>
            </div>
            <div class="carousel-item">
                <img src="{{ asset('/frontend/assets-new/images/ICT_Slideshow4.jpg') }}" class="d-block w-100"
                    alt="Slide 3">
                <div class="carousel-caption d-none d-md-block">
                    <h1>Learn from the Best Teachers</h1>
                    <p>Some representative placeholder content for the first slide.</p>
                    <button>Get Started</button>
                </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleAutoplaying"
            data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleAutoplaying"
            data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>

    <!-- ═══ CATEGORY ICONS ═══ -->
    <div class="catagoryicon">
        <div class="mainboxicon">
            <div class="boxcategory">
                <i class="fa-solid fa-user-graduate" style="font-size:34px;"></i>
                <p id="activest">2,500+</p>
                <p class="clp">Active Student</p>
            </div>
            <div class="boxcategory">
                <i class="fa-solid fa-person-chalkboard" style="font-size:36px;"></i>
                <p id="professionaltea">1,200+</p>
                <p class="clp">Professional Teacher</p>
            </div>
            <div class="boxcategory">
                <i class="fa-solid fa-file-code" style="font-size:34px;"></i>
                <p id="languagesavail">28+</p>
                <p class="clp">Languages Available</p>
            </div>
            <div class="boxcategory">
                <i class="fa-regular fa-calendar-days" style="font-size:34px;"></i>
                <p id="trainingevents">320+</p>
                <p class="clp">Training Event</p>
            </div>
        </div>
    </div>

    <!-- ═══ HERO ═══ -->
    <div class="showpage">
        <div class="description">
            <button id="desone">Now accepting enrollment - Batch 2026</button>
            <h1>Start Your ICT <br> Professional <br> Journey</h1>
            <p id="p">Master in-demand technology skills through structured online courses and live
                instructor-led
                classes designed for real world careers.</p>
            <div class="btndescript">
                <button id="studyon">Study Online</button>
                <button id="regis">Register Real Time Class</button>
            </div>
            <div class="studentenroll">
                <img class="st-pic" src="/frontend/assets-new/images/OIP (7).webp" alt="Student">
                <img class="st-pic" src="/frontend/assets-new/images/OIP (3).webp" alt="Student">
                <img class="st-pic" src="/frontend/assets-new/images/OIP (4).webp" alt="Student">
                <img class="st-pic" src="/frontend/assets-new/images/OIP (6).webp" alt="Student">
                <div class="aboutstudent">
                    <p id="se">+25,000 students enrolled</p>
                    <div class="star">
                        <i class="fa-solid fa-star" style="color:gold;"></i>
                        <i class="fa-solid fa-star" style="color:gold;"></i>
                        <i class="fa-solid fa-star" style="color:gold;"></i>
                        <i class="fa-solid fa-star" style="color:gold;"></i>
                        <i class="fa-solid fa-star" style="color:gold;"></i>
                        <p>4.9 average rating</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="imgdescript">
            <img src="/frontend/assets-new/images/istockphoto-1061639630-170667a.jpg" alt="Hero Image">
        </div>
    </div>

    <!-- Search section  -->
    <div class="search">
        <h2>All COURSE OF ICT</h2>
        <div class="searchbox">
            <input type="text" id="seainput" placeholder="Search your course">
            <i class="fa-solid fa-magnifying-glass"></i>
        </div>
    </div>

    <!--course slider-->
    <!--course slider-->
    <div class="allcoursebox">
        <div class="coursebox">
            <div class="boxcourse">
                {{-- "All" button --}}
                <div class="acb active-cat" data-category="all">
                    <i class="fa-solid fa-border-all"></i>
                    <p>All</p>
                </div>
                @foreach ($categories_for_frontend as $category)
                    <div class="acb" data-category="{{ $category->id }}">
                        @if ($category->icon)
                            <i class="{{ $category->icon }}"></i>
                        @elseif ($category->thumbnail)
                            <img src="{{ asset($category->thumbnail) }}" alt="{{ $category->name }}"
                                style="width:34px;height:34px;object-fit:cover;">
                        @else
                            <i class="fa-solid fa-book"></i>
                        @endif
                        <p>{{ $category->name }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="mainbox">
        @forelse ($courses as $title => $group)
            @php $course = $group->first(); @endphp
            <div class="boxcard" data-category="{{ $course->category_id }}">
                <img src="{{ $course->thumbnail ?: 'https://media1.giphy.com/media/v1.Y2lkPTc5MGI3NjExYXZhMW5qNXpyZ3M1ZG84NHBoa2QyYnBpd3hrZ2F0aDk4b3RvbnR1MiZlcD12MV9pbnRlcm5hbF9naWZfYnlfaWQmY3Q9Zw/xTiTnxpQ3ghPiB2Hp6/giphy.gif' }}"
                    alt="{{ $course->title }}"
                    onerror="this.src='https://media1.giphy.com/media/v1.Y2lkPTc5MGI3NjExYXZhMW5qNXpyZ3M1ZG84NHBoa2QyYnBpd3hrZ2F0aDk4b3RvbnR1MiZlcD12MV9pbnRlcm5hbF9naWZfYnlfaWQmY3Q9Zw/xTiTnxpQ3ghPiB2Hp6/giphy.gif'">
                <div class="teacher">
                    <img src="{{ $course->instructor->image == 'no-img.jpg' ? asset('default-images/user/both.jpg') : asset($course->instructor->image) }}"
                        alt="{{ $course->instructor->name }}">
                    <p>{{ $course->instructor->name }}</p>
                    <button>
                        {{-- category --}}
                        @if ($course->category)
                            {{ $course->category->name }}
                        @else
                            Uncategorized
                        @endif
                    </button>
                </div>
                <h2>{{ $title }}</h2>
                <div class="weekschedule">
                    <i class="fa-regular fa-calendar-days"></i>
                    <p>Weekly Schedule</p>
                    <p class="hour">{{ $course->duration }} hours</p>
                </div>
                <div class="pweekly">
                    @foreach ($group as $item)
                        @if ($item->schedule)
                            <p class="text-capitalize">. {{ $item->schedule->study_day }}
                                ({{ \Carbon\Carbon::parse($item->schedule->start_time)->format('g:i A') }} -
                                {{ \Carbon\Carbon::parse($item->schedule->end_time)->format('g:i A') }})
                            </p>
                        @endif
                    @endforeach
                </div>
                <div class="prnrate">
                    <h3>${{ number_format($course->price, 2) }}</h3>
                    <div class="starate">
                        <p>4.9</p>
                        @for ($i = 0; $i < 5; $i++)
                            <i class="fa-solid fa-star" style="color:gold;"></i>
                        @endfor
                    </div>
                </div>
            </div>
        @empty
            <p>No courses available at the moment. Please check back later.</p>
        @endforelse
    </div>

    <!-- ═══ COURSE CARDS ═══ -->
    <div class="card-areaa">
        <h3 id="blogsection">About Blog Videos <span>›</span></h3>
        <p id="blog_description">Lorem ipsum dolor sit amet consectetur adipisicing elit. Accusantium id
            repellendus
            ipsa ipsam nulla. Quibusdam sint quisquam placeat.</p>
        <div class="wrapperr">
            <div class="box-area">
                <div class="boxx" class="active">
                    <img src="/frontend/assets-new/images/whatisweb3.jpg" alt="">
                    <div class="overlay">
                        <p>Phat Sopheaktra</p>
                        <h3>What is Web Development?</h3>

                        <!-- <a href="#">Learn More</a> -->
                    </div>
                </div>
                <div class="boxx">
                    <img src="/frontend/assets-new/images/boxcourseAdvancedExcel.webp" alt="">
                    <div class="overlay">
                        <p>Phat Sopheaktra</p>
                        <h3>What is Web Development?</h3>

                        <!-- <a href="#">Learn More</a> -->
                    </div>
                </div>
                <div class="boxx">
                    <img src="/frontend/assets-new/images/Blogexcel.webp" alt="">
                    <div class="overlay">
                        <p>Phat Sopheaktra</p>
                        <h3>What is Web Development?</h3>

                        <!-- <a href="#">Learn More</a> -->
                    </div>
                </div>
                <div class="boxx">
                    <img src="/frontend/assets-new/images/whatisweb3.jpg" alt="">
                    <div class="overlay">
                        <p>Phat Sopheaktra</p>
                        <h3>What is Web Development?</h3>

                        <!-- <a href="#">Learn More</a> -->
                    </div>
                </div>
                <div class="boxx">
                    <img src="/frontend/assets-new/images/front-end-web-developer-html-css-bootstrap.png" alt="">
                    <div class="overlay">
                        <p>Phat Sopheaktra</p>
                        <h3>What is Web Development?</h3>

                        <!-- <a href="#">Learn More</a> -->
                    </div>
                </div>
                <div class="boxx">
                    <img src="/frontend/assets-new/images/whatisweb3.jpg" alt="">
                    <div class="overlay">
                        <p>Phat Sopheaktra</p>
                        <h3>What is Web Development?</h3>

                        <!-- <a href="#">Learn More</a> -->
                    </div>
                </div>
                <div class="boxx">
                    <img src="/frontend/assets-new/images/dataanalyze.webp" alt="">
                    <div class="overlay">
                        <p>Phat Sopheaktra</p>
                        <h3>What is Web Development?</h3>

                        <!-- <a href="#">Learn More</a> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="aboutschool">
        <div class="text">
            <p>WHY CHOOSE US</p>
            <h3>The Best Platform to Learn New Skills</h3>
            <p>We provide everything you need to succeed in your learning journey. Our platform is designed to make
                learning effective and enjoyable.</p>
            <div class="typecategoryaboutschool">
                <div class="typecategory">
                    <i class="fa-regular fa-clock"></i>
                    <p class="aboutdescri"><i>Learn at your pace</i><br>Access courses anytime, anywhere with
                        lifetime access to all purchased courses.</p>
                </div>
                <div class="typecategory">
                    <i class="fa-solid fa-users"></i>
                    <p class="aboutdescri"><i>Expert Instructors</i><br>Learn from industry professionals working
                        at
                        top companies like Google and Meta.</p>
                </div>
                <div class="typecategory">
                    <i class="fa-solid fa-diagram-project"></i>
                    <p class="aboutdescri"><i>Hands-on Projects</i><br>Build real-world projects and add them to
                        your portfolio with coding exercises.</p>
                </div>
                <div class="typecategory">
                    <i class="fa-solid fa-award"></i>
                    <p class="aboutdescri"><i>Certificates</i><br>Earn verified certificates upon completion. Share
                        on LinkedIn and your resume.</p>
                </div>
            </div>
        </div>
        <div class="imgaboutschool">
            <img src="/frontend/assets-new/images/photo_2026-04-30_15-36-55.jpg" alt="About Us Image">
        </div>
    </div>
    <div class="teacherictblock">
        <p class="pt" id="pt">EXPERT INSTRUCTORS</p>
        <div class="iconwithtext">
            <i class="fa-solid fa-circle-check" style="color: #3777ff;"></i>
            <h2>Learn From the Best</h2>
        </div>
        <p class="pt">Our instructors are industry professionals from top companies, passionate about sharing
            their
            knowledge.</p>

        <!--Teacher cards -->
        <div class="teacher-cards-grid">
            <div class="teacher-card">
                <div class="teacher-avatar-wrap">
                    <img src="/frontend/assets-new/images/teacherNhim.jpg" alt="Mr. Davit">
                </div>
                <h3>Nhanh Nhim</h3>
                <p class="teacher-role">Java Programming I</p>
                <div class="teacher-rating">
                    <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star-half-stroke"></i>
                    <span>4.7 (5K)</span>
                </div>
                <div class="teacher-divider"></div>
                <div class="teacher-stats">
                    <div class="stat-item"><span class="stat-value">38K</span><span class="stat-label">Students</span>
                    </div>
                    <div class="stat-item"><span class="stat-value">7</span><span class="stat-label">Courses</span>
                    </div>
                </div>
            </div>
            <div class="teacher-card">
                <div class="teacher-avatar-wrap">
                    <img src="/frontend/assets-new/images/teacherMuthManou.jpg" alt="Mr. Davit">
                </div>
                <h3>Muth Manou</h3>
                <p class="teacher-role">Web Frontend Development</p>
                <div class="teacher-rating">
                    <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star-half-stroke"></i>
                    <span>4.7 (5K)</span>
                </div>
                <div class="teacher-divider"></div>
                <div class="teacher-stats">
                    <div class="stat-item"><span class="stat-value">38K</span><span class="stat-label">Students</span>
                    </div>
                    <div class="stat-item"><span class="stat-value">7</span><span class="stat-label">Courses</span>
                    </div>
                </div>
            </div>
            <div class="teacher-card">
                <div class="teacher-avatar-wrap">
                    <img src="/frontend/assets-new/images/teachersrunBorath.jpg" alt="Mr. Davit">
                </div>
                <h3>Srun Borath</h3>
                <p class="teacher-role">Python Programming</p>
                <div class="teacher-rating">
                    <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star-half-stroke"></i>
                    <span>4.7 (5K)</span>
                </div>
                <div class="teacher-divider"></div>
                <div class="teacher-stats">
                    <div class="stat-item"><span class="stat-value">38K</span><span class="stat-label">Students</span>
                    </div>
                    <div class="stat-item"><span class="stat-value">7</span><span class="stat-label">Courses</span>
                    </div>
                </div>
            </div>
            <div class="teacher-card">
                <div class="teacher-avatar-wrap">
                    <img src="/frontend/assets-new/images/teacherRathana.jpg" alt="Mr. Davit">
                </div>
                <h3>Srin Rathana</h3>
                <p class="teacher-role">Web Frontend Development</p>
                <div class="teacher-rating">
                    <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star-half-stroke"></i>
                    <span>4.7 (5K)</span>
                </div>
                <div class="teacher-divider"></div>
                <div class="teacher-stats">
                    <div class="stat-item"><span class="stat-value">38K</span><span class="stat-label">Students</span>
                    </div>
                    <div class="stat-item"><span class="stat-value">7</span><span class="stat-label">Courses</span>
                    </div>
                </div>
            </div>
            <div class="teacher-card">
                <div class="teacher-avatar-wrap">
                    <img src="/frontend/assets-new/images/teacherHengVattey.jpg" alt="Mr. Davit">
                </div>
                <h3>Heng Vattey</h3>
                <p class="teacher-role">Devop</p>
                <div class="teacher-rating">
                    <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star-half-stroke"></i>
                    <span>4.7 (5K)</span>
                </div>
                <div class="teacher-divider"></div>
                <div class="teacher-stats">
                    <div class="stat-item"><span class="stat-value">38K</span><span class="stat-label">Students</span>
                    </div>
                    <div class="stat-item"><span class="stat-value">7</span><span class="stat-label">Courses</span>
                    </div>
                </div>
            </div>
            <div class="teacher-card">
                <div class="teacher-avatar-wrap">
                    <img src="/frontend/assets-new/images/teachernut.JPG" alt="Mr. Davit">
                </div>
                <h3>Nut Dalitongsin</h3>
                <p class="teacher-role">Networking</p>
                <div class="teacher-rating">
                    <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star-half-stroke"></i>
                    <span>4.7 (5K)</span>
                </div>
                <div class="teacher-divider"></div>
                <div class="teacher-stats">
                    <div class="stat-item"><span class="stat-value">38K</span><span class="stat-label">Students</span>
                    </div>
                    <div class="stat-item"><span class="stat-value">7</span><span class="stat-label">Courses</span>
                    </div>
                </div>
            </div>
            <div class="teacher-card">
                <div class="teacher-avatar-wrap">
                    <img src="/frontend/assets-new/images/teacherchanvimean.jpg" alt="">
                </div>
                <h3>Chan Vimean</h3>
                <p class="teacher-role">Python Languages</p>
                <div class="teacher-rating">
                    <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star-half-stroke"></i>
                    <span>4.7 (5K)</span>
                </div>
                <div class="teacher-divider"></div>
                <div class="teacher-stats">
                    <div class="stat-item"><span class="stat-value">38K</span><span class="stat-label">Students</span>
                    </div>
                    <div class="stat-item"><span class="stat-value">7</span><span class="stat-label">Courses</span>
                    </div>
                </div>
            </div>
            <div class="teacher-card">
                <div class="teacher-avatar-wrap">
                    <img src="/frontend/assets-new/images/teachervornsarn.jpg" alt="">
                </div>
                <h3>Vorn Saran</h3>
                <p class="teacher-role">Web Backend Development</p>
                <div class="teacher-rating">
                    <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star-half-stroke"></i>
                    <span>4.7 (5K)</span>
                </div>
                <div class="teacher-divider"></div>
                <div class="teacher-stats">
                    <div class="stat-item"><span class="stat-value">38K</span><span class="stat-label">Students</span>
                    </div>
                    <div class="stat-item"><span class="stat-value">7</span><span class="stat-label">Courses</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section partnership and footer -->
    <section class="partnership">
        <h2 class="partnership__title">Our <span>Partnership</span></h2>
        <p class="partnership__sub">Collaborating with Cambodia's leading tech companies and media organizations to
            deliver world-class education.</p>
        <div class="partner-track-wrap">
            <div class="partner-track">

                <!-- First set -->
                <div class="partner-card">
                    <a href="https://www.camboNCT.com" target="_blank" rel="noopener noreferrer">
                        <img src="/frontend/assets-new/images/p-camboNCT.jpg" alt="">
                    </a>
                </div>
                <div class="partner-card">
                    <a href="#" target="_blank" rel="noopener noreferrer">
                        <img src="/frontend/assets-new/images/p-cemintel.webp" alt="">
                    </a>
                </div>
                <div class="partner-card">
                    <a href="#" target="_blank" rel="noopener noreferrer">
                        <img src="/frontend/assets-new/images/p-emerald.webp" alt="">
                    </a>
                </div>
                <div class="partner-card">
                    <a href="#" target="_blank" rel="noopener noreferrer">
                        <img src="/frontend/assets-new/images/p-ezecom.webp" alt="">
                    </a>
                </div>
                <div class="partner-card">
                    <a href="#" target="_blank" rel="noopener noreferrer">
                        <img src="/frontend/assets-new/images/p-khmer24.webp" alt="">
                    </a>
                </div>
                <div class="partner-card">
                    <a href="#" target="_blank" rel="noopener noreferrer">
                        <img src="/frontend/assets-new/images/p-loma_tecc.jpg" alt="">
                    </a>
                </div>
                <div class="partner-card">
                    <a href="#" target="_blank" rel="noopener noreferrer">
                        <img src="/frontend/assets-new/images/p-sabay.png" alt="">
                    </a>
                </div>

                <!-- Duplicate set for seamless loop -->
                <div class="partner-card">
                    <a href="https://www.camboNCT.com" target="_blank" rel="noopener noreferrer">
                        <img src="/frontend/assets-new/images/p-camboNCT.jpg" alt="">
                    </a>
                </div>
                <div class="partner-card">
                    <a href="#" target="_blank" rel="noopener noreferrer">
                        <img src="/frontend/assets-new/images/p-cemintel.webp" alt="">
                    </a>
                </div>
                <div class="partner-card">
                    <a href="#" target="_blank" rel="noopener noreferrer">
                        <img src="/frontend/assets-new/images/p-emerald.webp" alt="">
                    </a>
                </div>
                <div class="partner-card">
                    <a href="#" target="_blank" rel="noopener noreferrer">
                        <img src="/frontend/assets-new/images/p-ezecom.webp" alt="">
                    </a>
                </div>
                <div class="partner-card">
                    <a href="#" target="_blank" rel="noopener noreferrer">
                        <img src="/frontend/assets-new/images/p-khmer24.webp" alt="">
                    </a>
                </div>
                <div class="partner-card">
                    <a href="#" target="_blank" rel="noopener noreferrer">
                        <img src="/frontend/assets-new/images/p-loma_tecc.jpg" alt="">
                    </a>
                </div>
                <div class="partner-card">
                    <a href="#" target="_blank" rel="noopener noreferrer">
                        <img src="/frontend/assets-new/images/p-sabay.png" alt="">
                    </a>
                </div>

                <!-- Duplicate set for seamless loop -->
                <div class="partner-card">
                    <a href="https://www.camboNCT.com" target="_blank" rel="noopener noreferrer">
                        <img src="/frontend/assets-new/images/p-camboNCT.jpg" alt="">
                    </a>
                </div>
                <div class="partner-card">
                    <a href="#" target="_blank" rel="noopener noreferrer">
                        <img src="/frontend/assets-new/images/p-cemintel.webp" alt="">
                    </a>
                </div>
                <div class="partner-card">
                    <a href="#" target="_blank" rel="noopener noreferrer">
                        <img src="/frontend/assets-new/images/p-emerald.webp" alt="">
                    </a>
                </div>
                <div class="partner-card">
                    <a href="#" target="_blank" rel="noopener noreferrer">
                        <img src="/frontend/assets-new/images/p-ezecom.webp" alt="">
                    </a>
                </div>
                <div class="partner-card">
                    <a href="#" target="_blank" rel="noopener noreferrer">
                        <img src="/frontend/assets-new/images/p-khmer24.webp" alt="">
                    </a>
                </div>
                <div class="partner-card">
                    <a href="#" target="_blank" rel="noopener noreferrer">
                        <img src="/frontend/assets-new/images/p-loma_tecc.jpg" alt="">
                    </a>
                </div>
                <div class="partner-card">
                    <a href="#" target="_blank" rel="noopener noreferrer">
                        <img src="/frontend/assets-new/images/p-sabay.png" alt="">
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection
