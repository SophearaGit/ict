@extends('frontend.layouts.new.master')
@section('page_title', isset($page_title) ? $page_title : 'Page Title Here')
@push('styles')
@endpush
@section('content')

    <!-- ═══ CAROUSEL ═══ -->
    <div class="video-carousel">
        <video class="vc-video" src="{{ asset('frontend/asset/images/vid4web.mp4') }}" autoplay muted playsinline
            loop></video>
        <div class="vc-overlay"></div>
        <div class="vc-caption" data-aos="fade-up">
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
        <h1 data-aos="fade-up">Empowering Learnings, Creating Opportunities</h1>
        <p id="category-title-block" data-aos="fade-up" data-aos-delay="100">We are committed to providing
            high-quality education and training to empower learners and create meaningful opportunities in the field
            of Information and Communication Technology.</p>
        <div class="mainboxicon">
            <div class="boxcategory" data-aos="fade-up" data-aos-delay="0">
                <i class="ti ti-school fa-user-graduate"></i>
                <p id="activest">{{ number_format($total_students ?? 0) }}+</p>
                <p class="clp">Active Student</p>
            </div>
            <div class="boxcategory" data-aos="fade-up" data-aos-delay="100">
                <i class="ti ti-brand-teams fa-person-chalkboard"></i>
                <p id="professionaltea">1,200+</p>
                <p class="clp">Professional Teacher</p>
            </div>
            <div class="boxcategory" data-aos="fade-up" data-aos-delay="200">
                <i class="ti ti-code fa-file-code"></i>
                <p id="languagesavail">28+</p>
                <p class="clp">Languages Available</p>
            </div>
            <div class="boxcategory" data-aos="fade-up" data-aos-delay="300">
                <i class="ti ti-calendar-event fa-calendar-days"></i>
                <p id="trainingevents">320+</p>
                <p class="clp">Training Event</p>
            </div>
        </div>
    </div>

    <!-- ═══ HERO ═══ -->
    <div class="showpage">
        <div class="description" data-aos="fade-right">
            <button id="desone">Now accepting enrollment - Batch 2026</button>
            <h1>Start Your ICT Professional Journey</h1>
            <p id="p">Master in-demand technology skills through structured online courses and live instructor-led
                classes designed for real world careers.</p>
            <div class="btndescript">
                <button id="studyon">Study Online</button>
                <button id="regis">Register Real Time Class</button>
            </div>
            <div class="studentenroll">
                <img class="st-pic" src="frontend/asset/images/Teacher/teacherchanvimean.jpg" alt="Student">
                <img class="st-pic" src="frontend/asset/images/Teacher/teacherMuthManou.jpg" alt="Student">
                <img class="st-pic" src="frontend/asset/images/Teacher/teacherHengVattey.jpg" alt="Student">
                <img class="st-pic" src="frontend/asset/images/Teacher/teacherRathana.jpg" alt="Student">
                <div class="aboutstudent">
                    <p id="se">+25,000 students enrolled</p>
                    <div class="star">
                        <i class="fa-solid fa-star" style="color:gold;"></i>
                        <i class="fa-solid fa-star" style="color:gold;"></i>
                        <i class="fa-solid fa-star" style="color:gold;"></i>
                        <i class="fa-solid fa-star" style="color:gold;"></i>
                        <i class="fa-solid fa-star" style="color:gold;"></i>
                        <p>4.9 rating</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="imgdescript" data-aos="fade-left" data-aos-delay="150">
            <img src="frontend/asset/images/slide-cut-v1.jpg" alt="Hero Image">
        </div>
    </div>

    <!-- Search section  -->
    <div class="search" data-aos="fade-up">
        <h2>All COURSE OF ICT</h2>
        <div class="searchbox">
            <input type="text" id="seainput" placeholder="Search your course" autocomplete="off">
            <i class="fa-solid fa-magnifying-glass"></i>
        </div>
    </div>

    <!--course slider-->
    <div class="allcoursebox">
        <div class="coursebox">
            <div class="boxcourse" id="categoryTabs">
                <div class="acb active" data-category="all">
                    <i class="fa-solid fa-border-all"></i>
                    <p>All</p>
                </div>
                @php
                    // Adjust 'category.name' below to match your ICTCourse -> category relation's actual column
$categoryList = $courses->flatten(1)->pluck('category.name')->filter()->unique();
// Maps a category name to an icon class. Add/adjust entries to match your real category names;
// anything not listed falls back to the default icon.
$categoryIcons = [
    'programming' => 'fa-solid fa-code',
    'website' => 'ti ti-world-www',
    'web development' => 'ti ti-world-www',
    'data' => 'ti ti-database',
    'computer office' => 'ti ti-brand-windows',
    'networking' => 'fa-solid fa-network-wired',
    'design' => 'fa-solid fa-palette',
                    ];
                @endphp
                @foreach ($categoryList as $categoryName)
                    @php
                        $iconClass = $categoryIcons[strtolower($categoryName)] ?? 'fa-solid fa-layer-group';
                    @endphp
                    <div class="acb" data-category="{{ \Illuminate\Support\Str::slug($categoryName) }}">
                        <i class="{{ $iconClass }}"></i>
                        <p>{{ $categoryName }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="mainbox" id="courseGrid">
        @forelse ($courses as $courseTitle => $courseGroup)
            @php
                // Each group holds every ICTCourse sharing this title (e.g. different schedules/instructors).
                // We show the most recent one as the representative card.
                $course = $courseGroup->first();
            @endphp
            <a href="{{ route('course.details', $course->slug ?? $course->id) }}" class="boxcard"
                data-category="{{ \Illuminate\Support\Str::slug($course->category->name ?? '') }}"
                data-title="{{ strtolower($course->title) }}">
                <img src="{{ $course->thumbnail ? asset($course->thumbnail) : 'frontend/asset/images/Course-Language/default.jpg' }}"
                    alt="{{ $course->title }}">
                <div class="detail-boxcard">
                    <button>{{ $course->category->name ?? 'Development' }}</button>
                    <h2>{{ $course->title }}</h2>
                    <div class="weekschedule">
                        <i class="fa-regular fa-calendar-days"></i>
                        <p>Weekly Schedule</p>
                        <p class="hour">{{ $course->duration_hours ?? '48' }} hours</p>
                    </div>
                    <p class="pweekly">
                        @forelse ($courseGroup as $groupedCourse)
                            @if ($groupedCourse->schedule)
                                . {{ ucfirst($groupedCourse->schedule->study_day) }}
                                ({{ \Carbon\Carbon::parse($groupedCourse->schedule->start_time)->format('g:iA') }} -
                                {{ \Carbon\Carbon::parse($groupedCourse->schedule->end_time)->format('g:iA') }})
                                <br>
                            @endif
                        @empty
                            Schedule to be announced
                        @endforelse
                    </p>
                </div>
                <div class="prnrate">
                    <h3>${{ number_format($course->price ?? 0, 2) }}</h3>
                    <div class="starate">
                        <p>{{ $course->rating ?? '4.9' }}</p>
                        @for ($i = 0; $i < 5; $i++)
                            <i class="fa-solid fa-star" style="color:gold;"></i>
                        @endfor
                    </div>
                </div>
            </a>
        @empty
            <p class="no-courses">No courses available right now. Please check back soon.</p>
        @endforelse
    </div>
    <p id="noCourseResults" class="no-courses" style="display:none;">No courses match your search or filter.</p>

    <!-- ═══ COURSE CARDS ═══ -->
    <div class="card-areaa">
        <a href="blog.html" style="text-decoration: none; color: inherit;">
            <h3 id="blogsection" data-aos="fade-up">About Blog Videos <span>›</span></h3>
        </a>
        <p id="blog_description" data-aos="fade-up" data-aos-delay="100">Explore our video blog to discover expert
            tips, inspiring stories, and practical ideas designed to help you grow and stay informed.</p>
        <div class="wrapperr">
            <div class="box-area">
                @forelse ($latest_blogs as $index => $blog)
                    <a href="{{ route('blog.details', $blog->slug) }}" class="boxx {{ $index === 0 ? 'active' : '' }}">
                        <img src="{{ $blog->thumbnail ? asset($blog->thumbnail) : 'frontend/asset/images/Blog/default.png' }}"
                            alt="{{ $blog->title }}">
                        <div class="overlay">
                            <p>{{ $blog->admin->name ?? ($blog->staff->name ?? 'ICT Team') }}</p>
                            <h3>{{ $blog->title }}</h3>
                        </div>
                    </a>
                @empty
                    <p class="no-blogs">No blog posts published yet.</p>
                @endforelse
            </div>
        </div>
    </div>
    </div>
    <div class="aboutschool">
        <div class="text" data-aos="fade-right">
            <p id="WCU">WHY CHOOSE US</p>
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
                    <p class="aboutdescri"><i>Expert Instructors</i><br>Learn from industry professionals working at
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
        <div class="imgaboutschool" data-aos="fade-left" data-aos-delay="150">
            <img src="frontend/asset/images/photo_2026-04-30_15-36-55.jpg" alt="About Us Image">
        </div>
    </div>

    <!-- CEO founder of ICT Solutions Co,Ltd -->
    <div class="message-teacher-block" data-aos="fade-up">
        <div class="message-by-kru-nhim">
            <img id="message-by-kru-nhim-img" src="frontend/asset/images/kru-nhim2.JPG" alt="">
            <div class="message-text">
                <h3>Message from the Director & Founder</h3>
                <p>
                    Welcome to ICT Professional Training Center. <br><br>
                    It is my great pleasure to welcome you to our training center. Since our establishmen in
                    2019, ICT Professional Training Center has been committed to providing high-quality IT
                    education and practical skills that meet the demands of today's digital world. <br><br>
                    Technology continues to transform the way we live, work, and communicate. Therefore, our
                    mission is not only to teach technical knowledge but also to empower students with
                    problem-solving abilities, creativity, professionalism, and confidence to succeed in their
                    careers. <br><br>
                    At ICT Professional Training Center, we offer industry-relevant short course including C++,
                    Java Programming, Web Development, Cybersecurity, Data Analysis, Digital Marketing, Power
                    BI, Graphic Design, and many other technology-focused programs. Our courses are designed to
                    combine theoretical knowledge with hands-on practical experience, ensuring that students are
                    ready for real-world challenges. <br><br>
                    We believe that education is the foundation of personal and professional growth Whether you
                    are a student, job seeker, working professional, or business owner, our training programs
                    are designed to help you achieve your goals and unlock new opportunities. <br><br>
                    Thank you for choosing ICT Professional Training Center as your learning partner. We look
                    forward to supporting your journey toward sucess in the ever-evolving world of technology.
                </p>
                <span>Mr. Nhanh Nhim</span>
                <p id="color-p">Director & Founder of ICT Professional Training Center</p>
            </div>
        </div>
    </div>
    <div class="teacherictblock">
        <p class="expert-instructor" id="pt" data-aos="fade-up">EXPERT INSTRUCTORS</p>
        <div class="iconwithtext" data-aos="fade-up">
            <i class="fa-solid fa-circle-check" style="color: #3777ff;"></i>
            <h2>Learn From the Best</h2>
        </div>
        <p class="pt">Our instructors are industry professionals from top companies, passionate about sharing their
            knowledge.</p>

        <!--Teacher cards -->
        <div class="teacher-cards-grid">
            @forelse ($featured_instructors as $index => $instructor)
                <div class="teacher-card" data-aos="fade-up" data-aos-delay="{{ ($index % 4) * 80 }}">
                    <div class="teacher-avatar-wrap">
                        <img src="{{ $instructor->image ? asset($instructor->image) : 'frontend/asset/images/Teacher/default.jpg' }}"
                            alt="{{ $instructor->name }}">
                    </div>
                    <h3>{{ $instructor->name }}</h3>
                    <p class="teacher-role">{{ $instructor->courses->first()->title ?? 'Instructor' }}</p>
                    <div class="teacher-rating">
                        @for ($i = 0; $i < 4; $i++)
                            <i class="fa-solid fa-star"></i>
                        @endfor
                        <i class="fa-solid fa-star-half-stroke"></i>
                        <span>4.7 ({{ number_format($instructor->students_count ?? 0) }})</span>
                    </div>
                    <div class="teacher-divider"></div>
                    <div class="teacher-stats">
                        <div class="stat-item">
                            <span class="stat-value">{{ number_format($instructor->students_count ?? 0) }}</span>
                            <span class="stat-label">Students</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-value">{{ $instructor->courses_count ?? 0 }}</span>
                            <span class="stat-label">Courses</span>
                        </div>
                    </div>
                </div>
            @empty
                <p class="no-instructors">No instructors to show yet.</p>
            @endforelse
        </div>
    </div>

    <!-- Section partnership and footer -->
    <section class="partnership">
        <h2 class="partnership__title" data-aos="fade-up">Our <span>Partnership</span></h2>
        <p class="partnership__sub" data-aos="fade-up" data-aos-delay="100">Collaborating with Cambodia's leading
            tech companies and media organizations to
            deliver world-class education.</p>
        <div class="partner-track-wrap">
            <div class="partner-track">

                <!-- First set -->
                <div class="partner-card">
                    <a href="https://www.camboNCT.com" target="_blank" rel="noopener noreferrer">
                        <img src="frontend/asset/images/Partnership/p-camboNCT.jpg" alt="">
                    </a>
                </div>
                <div class="partner-card">
                    <a href="#" target="_blank" rel="noopener noreferrer">
                        <img src="frontend/asset/images/Partnership/p-cemintel.webp" alt="">
                    </a>
                </div>
                <div class="partner-card">
                    <a href="#" target="_blank" rel="noopener noreferrer">
                        <img src="frontend/asset/images/Partnership/p-emerald.webp" alt="">
                    </a>
                </div>
                <div class="partner-card">
                    <a href="#" target="_blank" rel="noopener noreferrer">
                        <img src="frontend/asset/images/Partnership/p-ezecom.webp" alt="">
                    </a>
                </div>
                <div class="partner-card">
                    <a href="#" target="_blank" rel="noopener noreferrer">
                        <img src="frontend/asset/images/Partnership/p-khmer24.webp" alt="">
                    </a>
                </div>
                <div class="partner-card">
                    <a href="#" target="_blank" rel="noopener noreferrer">
                        <img src="frontend/asset/images/Partnership/p-loma_tecc.jpg" alt="">
                    </a>
                </div>
                <div class="partner-card">
                    <a href="#" target="_blank" rel="noopener noreferrer">
                        <img src="frontend/asset/images/Partnership/p-sabay.png" alt="">
                    </a>
                </div>

                <!-- Duplicate set for seamless loop -->
                <div class="partner-card">
                    <a href="https://www.camboNCT.com" target="_blank" rel="noopener noreferrer">
                        <img src="frontend/asset/images/Partnership/p-camboNCT.jpg" alt="">
                    </a>
                </div>
                <div class="partner-card">
                    <a href="#" target="_blank" rel="noopener noreferrer">
                        <img src="frontend/asset/images/Partnership/p-cemintel.webp" alt="">
                    </a>
                </div>
                <div class="partner-card">
                    <a href="#" target="_blank" rel="noopener noreferrer">
                        <img src="frontend/asset/images/Partnership/p-emerald.webp" alt="">
                    </a>
                </div>
                <div class="partner-card">
                    <a href="#" target="_blank" rel="noopener noreferrer">
                        <img src="frontend/asset/images/Partnership/p-ezecom.webp" alt="">
                    </a>
                </div>
                <div class="partner-card">
                    <a href="#" target="_blank" rel="noopener noreferrer">
                        <img src="frontend/asset/images/Partnership/p-khmer24.webp" alt="">
                    </a>
                </div>
                <div class="partner-card">
                    <a href="#" target="_blank" rel="noopener noreferrer">
                        <img src="frontend/asset/images/Partnership/p-loma_tecc.jpg" alt="">
                    </a>
                </div>
                <div class="partner-card">
                    <a href="#" target="_blank" rel="noopener noreferrer">
                        <img src="frontend/asset/images/Partnership/p-sabay.png" alt="">
                    </a>
                </div>

                <!-- Duplicate set for seamless loop -->
                <div class="partner-card">
                    <a href="https://www.camboNCT.com" target="_blank" rel="noopener noreferrer">
                        <img src="frontend/asset/images/Partnership/p-camboNCT.jpg" alt="">
                    </a>
                </div>
                <div class="partner-card">
                    <a href="#" target="_blank" rel="noopener noreferrer">
                        <img src="frontend/asset/images/Partnership/p-cemintel.webp" alt="">
                    </a>
                </div>
                <div class="partner-card">
                    <a href="#" target="_blank" rel="noopener noreferrer">
                        <img src="frontend/asset/images/Partnership/p-emerald.webp" alt="">
                    </a>
                </div>
                <div class="partner-card">
                    <a href="#" target="_blank" rel="noopener noreferrer">
                        <img src="frontend/asset/images/Partnership/p-ezecom.webp" alt="">
                    </a>
                </div>
                <div class="partner-card">
                    <a href="#" target="_blank" rel="noopener noreferrer">
                        <img src="frontend/asset/images/Partnership/p-khmer24.webp" alt="">
                    </a>
                </div>
                <div class="partner-card">
                    <a href="#" target="_blank" rel="noopener noreferrer">
                        <img src="frontend/asset/images/Partnership/p-loma_tecc.jpg" alt="">
                    </a>
                </div>
                <div class="partner-card">
                    <a href="#" target="_blank" rel="noopener noreferrer">
                        <img src="frontend/asset/images/Partnership/p-sabay.png" alt="">
                    </a>
                </div>
            </div>
        </div>
    </section>
    {{-- Show AD popup only on the index/home page --}}
    @if (request()->routeIs('index'))
        <!-- ═══ AD POPUP MODAL (shows once on page load) ═══ -->
        <div class="ad-popup-overlay" id="adPopupOverlay">
            <div class="ad-popup-box">
                <button class="ad-popup-close" id="adPopupClose">&times;</button>
                <div class="advertisement-slide">
                    <div id="carouselExampleInterval" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            <div class="carousel-item active" data-bs-interval="4000">
                                <img src="frontend/asset/images/slide-cut-v24.jpg" class="d-block w-100"
                                    alt="Advertisement">
                            </div>
                            <div class="carousel-item" data-bs-interval="4000">
                                <img src="frontend/asset/images/slide-cut-v15.jpg" class="d-block w-100"
                                    alt="Advertisement">
                            </div>
                            <div class="carousel-item" data-bs-interval="4000">
                                <img src="frontend/asset/images/ICT_SlideShow.jpg" class="d-block w-100"
                                    alt="Advertisement">
                            </div>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleInterval"
                            data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleInterval"
                            data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <script>
        (function() {
            function initCourseFilters() {
                var tabs = document.querySelectorAll('#categoryTabs .acb');
                var searchInput = document.getElementById('seainput');
                var searchIcon = document.querySelector('.searchbox .fa-magnifying-glass');
                var cards = document.querySelectorAll('#courseGrid .boxcard');
                var noResultsMsg = document.getElementById('noCourseResults');
                var activeCategory = 'all';
                if (!cards.length) {
                    return;
                }

                function normalize(str) {
                    return (str || '').toString().trim().toLowerCase();
                }

                function applyFilters() {
                    var query = normalize(searchInput ? searchInput.value : '');
                    var visibleCount = 0;
                    cards.forEach(function(card) {
                        var cardCategory = card.getAttribute('data-category') || '';
                        var cardTitle = normalize(card.getAttribute('data-title'));
                        var matchesCategory = (activeCategory === 'all') || (cardCategory === activeCategory);
                        var matchesSearch = (query === '') || (cardTitle.indexOf(query) !== -1);
                        var show = matchesCategory && matchesSearch;
                        card.style.display = show ? '' : 'none';
                        if (show) {
                            visibleCount++;
                        }
                    });
                    if (noResultsMsg) {
                        noResultsMsg.style.display = (visibleCount === 0) ? 'block' : 'none';
                    }
                }
                tabs.forEach(function(tab) {
                    tab.addEventListener('click', function() {
                        tabs.forEach(function(t) {
                            t.classList.remove('active');
                        });
                        tab.classList.add('active');
                        activeCategory = tab.getAttribute('data-category') || 'all';
                        applyFilters();
                    });
                });
                if (searchInput) {
                    searchInput.addEventListener('input', applyFilters);
                    searchInput.addEventListener('keyup', applyFilters);
                    searchInput.addEventListener('search', applyFilters);
                }
                if (searchIcon) {
                    searchIcon.style.cursor = 'pointer';
                    searchIcon.addEventListener('click', applyFilters);
                }
                // Run once on load in case the search box is pre-filled (e.g. back/forward navigation).
                applyFilters();
            }
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initCourseFilters);
            } else {
                initCourseFilters();
            }
        })();
    </script>
@endsection
