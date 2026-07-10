@extends('frontend.layouts.new.master')
@section('page_title', isset($page_title) ? $page_title : 'Page Title Here')
@push('styles')
    <style>
        .acb {
            cursor: pointer;
            pointer-events: all;
            user-select: none;
            transition: background-color 0.2s;
        }

        .acb:hover {
            background-color: rgb(230, 230, 230);
        }

        .acb.active {
            background-color: #3777ff;
            color: white;
        }

        .acb.active p,
        .acb.active .fa-solid {
            color: white !important;
        }

        .category-icon,
        .acb i {
            width: 20px;
            height: 20px;
            font-size: 20px;
            object-fit: contain;
        }

        .empty-course-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            width: 100%;
            min-height: 300px;
            padding: 60px 20px;
        }

        #no-course-found {
            width: 100%;
            display: none;
        }

        .empty-course-state {
            width: 100%;
            min-height: 350px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .empty-course-state i {
            font-size: 50px;
            color: #999;
            margin-bottom: 15px;
        }

        .empty-course-state h3 {
            margin-bottom: 10px;
        }

        .empty-course-state p {
            color: #777;
        }
    </style>
@endpush
@section('content')

    <!-- ═══ CAROUSEL ═══ -->
    <div class="video-carousel">
        <video class="vc-video" src="{{ asset('frontend/asset/images/video_hero.mp4') }}" autoplay muted playsinline
            loop></video>
        <div class="vc-overlay"></div>
        <div class="vc-caption">
            <h2>Learn from the <em> Best Teachers</em></h2>
            <p>We combine expert instructors, hands-on projects, and flexible schedules to help you build a real
                career in tech — whether you are a complete beginner or looking to level up your skills.</p>
            <div class="vc-btns">
                <button class="vc-btn-primary">Get Started</button>
            </div>
        </div>
    </div>

    <!-- ═══ CATEGORY ICONS ═══ -->
    <div class="catagoryicon">
        <h1>Empowering Learnings, Creating Opportunities</h1>
        <p id="category-title-block">We are committed to providing high-quality education and training to empower learners
            and create meaningful opportunities in the field of Information and Communication Technology.</p>
        <div class="mainboxicon">
            <div class="boxcategory">
                <i class="fa-solid fa-user-graduate"></i>
                <p id="activest">2,500+</p>
                <p class="clp">Active Student</p>
                <span>Learners from diverse backgrounds growing together with us.</span>
            </div>
            <div class="boxcategory">
                <i class="fa-solid fa-person-chalkboard"></i>
                <p id="professionaltea">1,200+</p>
                <p class="clp">Professional Teacher</p>
                <span>Experienced educations dedicated to guiding your success.</span>
            </div>
            <div class="boxcategory">
                <i class="fa-solid fa-file-code"></i>
                <p id="languagesavail">28+</p>
                <p class="clp">Languages Available</p>
                <span>A wide range of proggramming languages and technologies to learn.</span>
            </div>
            <div class="boxcategory">
                <i class="fa-regular fa-calendar-days"></i>
                <p id="trainingevents">320+</p>
                <p class="clp">Training Event</p>
                <span>Workshop, webinars and bootcamps to boost your skills.</span>
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
                @foreach ($instructors as $instructor)
                    <img class="st-pic"
                        src="{{ asset(
                            empty($instructor->image) || $instructor->image === 'no-img.jpg'
                                ? asset('default-images/user/both.jpg')
                                : ltrim($instructor->image, '/'),
                        ) }}"
                        alt="{{ $instructor->name }}"
                        title="{{ $instructor->name }} ({{ number_format($instructor->students_count) }} students)">
                @endforeach
                <div class="aboutstudent">
                    <p id="se">
                        +{{ number_format($total_students) }}
                        students enrolled
                    </p>
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
            <img src="{{ asset('./frontend/asset/images/istockphoto-1061639630-170667a.jpg') }}" alt="Hero Image">
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
    <div class="allcoursebox">
        <div class="coursebox">
            <div class="boxcourse">
                <div class="acb active" data-category="all">
                    <i class="fa-solid fa-grip"></i>
                    <p>All</p>
                </div>
                @foreach ($categories_for_frontend as $category)
                    <div class="acb" data-category="{{ $category->id }}">
                        @if (!empty($category->thumbnail))
                            <img src="{{ asset(ltrim($category->thumbnail, '/')) }}" alt="{{ $category->name }}"
                                class="category-icon">
                        @elseif (!empty($category->icon))
                            <i class="{{ $category->icon }}"></i>
                        @endif
                        <p>{{ $category->name }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="mainbox">
        @foreach ($courses->take(16) as $title => $group)
            @php
                $course = $group->first();
            @endphp
            <a href="{{ route('course.details', $course->slug) }}" class="boxcard"
                data-category="{{ $course->category_id }}" data-title="{{ strtolower($title) }}">
                <img src="{{ asset(empty($course->thumbnail) ? 'default-images/ict-courses/loading.gif' : ltrim($course->thumbnail, '/')) }}"
                    alt="{{ $course->title }}">
                <div class="teacher">
                    <img src="{{ asset(
                        empty($course->instructor?->image) || $course->instructor?->image === 'no-img.jpg'
                            ? 'default-images/user/both.jpg'
                            : ltrim($course->instructor->image, '/'),
                    ) }}"
                        alt="{{ $course->instructor?->name }}">
                    <p>{{ $course->instructor?->name }}</p>
                    <button>{{ $course->category?->name }}</button>
                </div>
                <h2>{{ $title }}</h2>
                <div class="weekschedule">
                    <i class="fa-regular fa-calendar-days"></i>
                    <p>{{ $group->count() }} Weekly Schedule{{ $group->count() > 1 ? 's' : '' }}</p>
                    <p class="hour">{{ $course->duration }} hrs</p>
                </div>
                <p class="pweekly">
                    @foreach ($group->unique('schedule_id')->take(3) as $scheduleCourse)
                        • {{ $scheduleCourse->schedule?->short_days }}
                        ({{ $scheduleCourse->schedule?->formatted_time }})
                        @if (!$loop->last)
                            <br>
                        @endif
                    @endforeach
                    @if ($group->unique('schedule_id')->count() > 3)
                        <br>• +{{ $group->unique('schedule_id')->count() - 3 }} more batches
                    @endif
                </p>
                <div class="prnrate">
                    <h3>${{ number_format($course->price, 2) }}</h3>
                    <div class="starate">
                        <p>4.9</p>
                        @for ($i = 0; $i < 5; $i++)
                            <i class="fa-solid fa-star" style="color:gold;"></i>
                        @endfor
                    </div>
                </div>
            </a>
        @endforeach
    </div>
    <div id="no-course-found" class="empty-course-state" style="display:none;">
        <i class="fa-solid fa-magnifying-glass"></i>
        <h3>No Courses Found</h3>
        <p>Try searching with another keyword.</p>
    </div>

    <!-- ═══ COURSE CARDS ═══ -->
    <div class="card-areaa">
        <h3 id="blogsection">About Blog Videos <span>›</span></h3>
        <p id="blog_description">Lorem ipsum dolor sit amet consectetur adipisicing elit. Accusantium id
            repellendus ipsa ipsam nulla. Quibusdam sint quisquam placeat.</p>
        <div class="wrapperr">
            <div class="box-area">
                <div class="boxx" class="active">
                    <img src="./frontend/asset/images/whatisweb3.jpg" alt="">
                    <div class="overlay">
                        <p>Phat Sopheaktra</p>
                        <h3>What is Web Development?</h3>

                        <!-- <a href="#">Learn More</a> -->
                    </div>
                </div>
                <div class="boxx">
                    <img src="./frontend/asset/images/boxcourseAdvancedExcel.webp" alt="">
                    <div class="overlay">
                        <p>Phat Sopheaktra</p>
                        <h3>What is Web Development?</h3>

                        <!-- <a href="#">Learn More</a> -->
                    </div>
                </div>
                <div class="boxx">
                    <img src="./frontend/asset/images/Blogexcel.webp" alt="">
                    <div class="overlay">
                        <p>Phat Sopheaktra</p>
                        <h3>What is Web Development?</h3>

                        <!-- <a href="#">Learn More</a> -->
                    </div>
                </div>
                <div class="boxx">
                    <img src="./frontend/asset/images/whatisweb3.jpg" alt="">
                    <div class="overlay">
                        <p>Phat Sopheaktra</p>
                        <h3>What is Web Development?</h3>

                        <!-- <a href="#">Learn More</a> -->
                    </div>
                </div>
                <div class="boxx">
                    <img src="./frontend/asset/images/front-end-web-developer-html-css-bootstrap.png" alt="">
                    <div class="overlay">
                        <p>Phat Sopheaktra</p>
                        <h3>What is Web Development?</h3>

                        <!-- <a href="#">Learn More</a> -->
                    </div>
                </div>
                <div class="boxx">
                    <img src="./frontend/asset/images/whatisweb3.jpg" alt="">
                    <div class="overlay">
                        <p>Phat Sopheaktra</p>
                        <h3>What is Web Development?</h3>

                        <!-- <a href="#">Learn More</a> -->
                    </div>
                </div>
                <div class="boxx">
                    <img src="./frontend/asset/images/dataanalyze.webp" alt="">
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
            <img src="./frontend/asset/images/photo_2026-04-30_15-36-55.jpg" alt="About Us Image">
        </div>
    </div>

    <!-- CEO founder of ICT Solutions Co,Ltd -->
    <div class="teacher-img-with-description">
        <img src="./frontend/asset/images/teacherNhim.jpg" alt="">
        <div class="teacher-nhim-text-descript">
            <p>Mr.NHANH NHIM</p>
            <span>CEO & Founder of ICT Solutions,Co,Ltd.</span>
            <h3>"Our mission is to empower the next generation of digital innovators with the tools they need to
                change the world."</h3>
        </div>
    </div>
    <div class="teacherictblock">
        <p class="pt" id="pt">EXPERT INSTRUCTORS</p>
        <div class="iconwithtext">
            <i class="fa-solid fa-circle-check" style="color: #3777ff;"></i>
            <h2>Learn From the Best</h2>
        </div>
        <p class="pt">Our instructors are industry professionals from top companies, passionate about sharing
            their knowledge.</p>

        <!--Teacher cards -->
        <div class="teacher-cards-grid">
            @foreach ($featured_instructors as $instructor)
                <div class="teacher-card">
                    <div class="teacher-avatar-wrap">
                        <img src="{{ asset(
                            empty($instructor->image) || $instructor->image === 'no-img.jpg'
                                ? 'default-images/user/both.jpg'
                                : ltrim($instructor->image, '/'),
                        ) }}"
                            alt="{{ $instructor->name }}">
                    </div>
                    <h3>{{ $instructor->name }}</h3>
                    <p class="teacher-role">
                        {{ $instructor->courses->first()?->title ?? 'ICT Instructor' }}
                    </p>
                    <div class="teacher-rating">
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <span>4.9</span>
                    </div>
                    <div class="teacher-divider"></div>
                    <div class="teacher-stats">
                        <div class="stat-item">
                            <span class="stat-value">
                                {{ number_format($instructor->students_count) }}
                            </span>
                            <span class="stat-label">
                                Students
                            </span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-value">
                                {{ $instructor->courses_count }}
                            </span>
                            <span class="stat-label">
                                Courses
                            </span>
                        </div>
                    </div>
                </div>
            @endforeach
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
                        <img src="{{ asset('frontend/asset/images/p-camboNCT.jpg') }}" alt="">
                    </a>
                </div>
                <div class="partner-card">
                    <a href="#" target="_blank" rel="noopener noreferrer">
                        <img src="{{ asset('frontend/asset/images/p-cemintel.webp') }}" alt="">
                    </a>
                </div>
                <div class="partner-card">
                    <a href="#" target="_blank" rel="noopener noreferrer">
                        <img src="{{ asset('frontend/asset/images/p-emerald.webp') }}" alt="">
                    </a>
                </div>
                <div class="partner-card">
                    <a href="#" target="_blank" rel="noopener noreferrer">
                        <img src="{{ asset('frontend/asset/images/p-ezecom.webp') }}" alt="">
                    </a>
                </div>
                <div class="partner-card">
                    <a href="#" target="_blank" rel="noopener noreferrer">
                        <img src="{{ asset('frontend/asset/images/p-khmer24.webp') }}" alt="">
                    </a>
                </div>
                <div class="partner-card">
                    <a href="#" target="_blank" rel="noopener noreferrer">
                        <img src="{{ asset('frontend/asset/images/p-loma_tecc.jpg') }}" alt="">
                    </a>
                </div>
                <div class="partner-card">
                    <a href="#" target="_blank" rel="noopener noreferrer">
                        <img src="{{ asset('frontend/asset/images/p-sabay.png') }}" alt="">
                    </a>
                </div>

                <!-- Duplicate set for seamless loop -->
                <div class="partner-card">
                    <a href="https://www.camboNCT.com" target="_blank" rel="noopener noreferrer">
                        <img src="{{ asset('frontend/asset/images/p-camboNCT.jpg') }}" alt="">
                    </a>
                </div>
                <div class="partner-card">
                    <a href="#" target="_blank" rel="noopener noreferrer">
                        <img src="{{ asset('frontend/asset/images/p-cemintel.webp') }}" alt="">
                    </a>
                </div>
                <div class="partner-card">
                    <a href="#" target="_blank" rel="noopener noreferrer">
                        <img src="{{ asset('frontend/asset/images/p-emerald.webp') }}" alt="">
                    </a>
                </div>
                <div class="partner-card">
                    <a href="#" target="_blank" rel="noopener noreferrer">
                        <img src="{{ asset('frontend/asset/images/p-ezecom.webp') }}" alt="">
                    </a>
                </div>
                <div class="partner-card">
                    <a href="#" target="_blank" rel="noopener noreferrer">
                        <img src="./frontend/asset/images/p-khmer24.webp" alt="">
                    </a>
                </div>
                <div class="partner-card">
                    <a href="#" target="_blank" rel="noopener noreferrer">
                        <img src="./frontend/asset/images/p-loma_tecc.jpg" alt="">
                    </a>
                </div>
                <div class="partner-card">
                    <a href="#" target="_blank" rel="noopener noreferrer">
                        <img src="./frontend/asset/images/p-sabay.png" alt="">
                    </a>
                </div>

                <!-- Duplicate set for seamless loop -->
                <div class="partner-card">
                    <a href="https://www.camboNCT.com" target="_blank" rel="noopener noreferrer">
                        <img src="./frontend/asset/images/p-camboNCT.jpg" alt="">
                    </a>
                </div>
                <div class="partner-card">
                    <a href="#" target="_blank" rel="noopener noreferrer">
                        <img src="./frontend/asset/images/p-cemintel.webp" alt="">
                    </a>
                </div>
                <div class="partner-card">
                    <a href="#" target="_blank" rel="noopener noreferrer">
                        <img src="./frontend/asset/images/p-emerald.webp" alt="">
                    </a>
                </div>
                <div class="partner-card">
                    <a href="#" target="_blank" rel="noopener noreferrer">
                        <img src="./frontend/asset/images/p-ezecom.webp" alt="">
                    </a>
                </div>
                <div class="partner-card">
                    <a href="#" target="_blank" rel="noopener noreferrer">
                        <img src="./frontend/asset/images/p-khmer24.webp" alt="">
                    </a>
                </div>
                <div class="partner-card">
                    <a href="#" target="_blank" rel="noopener noreferrer">
                        <img src="./frontend/asset/images/p-loma_tecc.jpg" alt="">
                    </a>
                </div>
                <div class="partner-card">
                    <a href="#" target="_blank" rel="noopener noreferrer">
                        <img src="./frontend/asset/images/p-sabay.png" alt="">
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const categories = document.querySelectorAll('.acb');
            const cards = document.querySelectorAll('.boxcard');
            const searchInput = document.getElementById('seainput');
            const emptyState = document.getElementById('no-course-found');

            function applyFilters() {
                const keyword = searchInput.value.toLowerCase();
                const activeCategory =
                    document.querySelector('.acb.active')
                    ?.dataset.category ?? 'all';
                let visibleCount = 0;
                cards.forEach(card => {
                    const title = card.dataset.title;
                    const category = card.dataset.category;
                    const matchSearch =
                        title.includes(keyword);
                    const matchCategory =
                        activeCategory === 'all' ||
                        category === activeCategory;
                    if (matchSearch && matchCategory) {
                        card.style.display = '';
                        visibleCount++;
                    } else {
                        card.style.display = 'none';
                    }
                });
                const mainbox = document.querySelector('.mainbox');
                if (visibleCount === 0) {
                    mainbox.style.display = 'none';
                    emptyState.style.display = 'block';
                } else {
                    mainbox.style.display = '';
                    emptyState.style.display = 'none';
                }
            }
            categories.forEach(category => {
                category.addEventListener('click', e => {
                    categories.forEach(c =>
                        c.classList.remove('active')
                    );
                    e.currentTarget.classList.add('active');
                    applyFilters();
                });
            });
            searchInput.addEventListener(
                'keyup',
                applyFilters
            );
            applyFilters();
        });
    </script>
@endpush
