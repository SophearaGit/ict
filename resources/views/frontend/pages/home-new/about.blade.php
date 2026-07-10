@extends('frontend.layouts.new.master')
@section('page_title', isset($page_title) ? $page_title : 'Page Title Here')
@section('content')
    <!-- about body section start -->
    <div class="about-container">
        <div class="text-about-description">
            <h1>Our Story</h1>
            <p>We are dedicated to empowering individuals with the skills needed to thrive in the digital economy.
            </p>
        </div>
        <div class="message-teacher-block">
            <div class="message-by-kru-nhim">
                <img id="message-by-kru-nhim-img" src="frontend/asset/images/kru-nhim2.JPG" alt="">
                <div class="message-text">
                    <h3>Message from the director & Founder</h3>
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
        <!-- ═══ CATEGORY ICONS ═══ -->
        <div class="catagoryicon">
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
        <div class="mision-vision">
            <div class="our-mision-vision-content">
                <i class="fa-solid fa-earth-europe"></i>
                <h5>Our Mision</h5>
                <p>
                <ul>
                    <li>To provide high-quality and affordable ICT education that meets industry standards.</li>
                    <li>To equip students with practical skills and hands-on experience for real-world careers.</li>
                    <li>To promote innovation, creativity, and lifelong learning in the field of information
                        technology.</li>
                    <li>To develop competent IT professionals who can contribute to local and global digital
                        transformation.</li>
                    <li>To build strong partnerships with industries, organizations, and communities to enhance
                        career opportunities for our students.</li>
                </ul>
                </p>
            </div>
            <div class="our-mision-vision-content">
                <i class="fa-solid fa-medal"></i>
                <h5>Our Vision</h5>
                <p>To become a leading professional ICT training center recognized for excellence in technology
                    education, innovation, and workforce development, empowering individuals to succeed in the
                    digital economy.</p>
            </div>
        </div>
        <div class="journey-title">
            <h2>Our Journey</h2>
            <p>A decade of excellence in tech education.</p>
        </div>
        <div class="timeline">

            <!-- 2019 - right side -->
            <div class="timeline-item right">
                <div class="timeline-spacer"></div>
                <div class="timeline-icon"><i class="fa-solid fa-flag"></i></div>
                <div class="timeline-card">
                    <div class="card-img"><img src="frontend/asset/images/slide-cut-v19.jpg" alt=""></div>
                    <div class="card-text">
                        <span class="year-badge">2019</span>
                        <h3>Our Beginning</h3>
                        <p>ICT Professional Training Center was established in 2019 with a vision to provide
                            practical and professional ICT education for students, graduates, and working
                            professionals.</p>
                    </div>
                </div>
            </div>

            <!-- 2020 - left side -->
            <div class="timeline-item left">
                <div class="timeline-card">
                    <div class="card-img"> <img src="frontend/asset/images/slide-cut-v15.jpg" alt=""></div>
                    <div class="card-text">
                        <span class="year-badge">2020</span>
                        <h3>Bridging the Skills Gap</h3>
                        <p>Recognizing the growing demand for digital skills and technology expertise, the center
                            was founded to bridge the gap between academic knowledge and industry requirements.</p>
                    </div>
                </div>
                <div class="timeline-icon"><i class="fa-solid fa-award"></i></div>
                <div class="timeline-spacer"></div>
            </div>

            <!-- 2021 - right side -->
            <div class="timeline-item right">
                <div class="timeline-spacer"></div>
                <div class="timeline-icon"><i class="fa-solid fa-flag"></i></div>
                <div class="timeline-card">
                    <div class="card-img"> <img src="frontend/asset/images/slide-cut-v6.jpg" alt=""></div>
                    <div class="card-text">
                        <span class="year-badge">2021</span>
                        <h3>Expanding Training Programs</h3>
                        <p>The center continuously expanded its training programs to meet industry needs, offering
                            courses in Programming, Web Development, Mobile Development, Cybersecurity, and Data
                            Analysis.</p>
                    </div>
                </div>
            </div>

            <!-- 2021 - left side -->
            <div class="timeline-item left">
                <div class="timeline-card">
                    <div class="card-img"><img src="frontend/asset/images/cybersecurity.webp" alt=""></div>
                    <div class="card-text">
                        <span class="year-badge">2023</span>
                        <h3>Empowering Future Professionals</h3>
                        <p>Through experienced instructors and practical learning approaches, ICT Professional
                            Training Center became a trusted destination for individuals seeking to enhance their
                            technical skills and career opportunities.</p>
                    </div>
                </div>
                <div class="timeline-icon"><i class="fa-solid fa-microchip"></i></div>
                <div class="timeline-spacer"></div>
            </div>

            <!-- 2026 - right side (repeat) -->
            <div class="timeline-item right">
                <div class="timeline-spacer"></div>
                <div class="timeline-icon"><i class="fa-solid fa-building-columns"></i></div>
                <div class="timeline-card">
                    <div class="card-img"><img src="frontend/asset/images/slide-cut-v17.jpg" alt=""></div>
                    <div class="card-text">
                        <span class="year-badge">2026</span>
                        <h3>Continuing Our Mission</h3>
                        <p>We continue empowering learners with the knowledge, skills, and confidence needed to
                            thrive in the modern digital world while preparing the next generation of technology
                            professionals.</p>
                    </div>
                </div>
            </div>

            <!-- 2021 - left side (repeat) -->
            <div class="timeline-item left">
                <div class="timeline-card">
                    <div class="card-img"><img src="frontend/asset/images/istockphoto-1061639630-170667a.jpg"
                            alt=""></div>
                    <div class="card-text">
                        <span class="year-badge">2021</span>
                        <h3>Continuing Our Mission</h3>
                        <p>We continue empowering learners with the knowledge, skills, and confidence needed to
                            thrive in the modern digital world while preparing the next generation of technology
                            professionals.</p>
                    </div>
                </div>
                <div class="timeline-icon"><i class="fa-solid fa-rocket"></i></div>
                <div class="timeline-spacer"></div>
            </div>
        </div>
        <div class="why-choose-ict">
            <h2>Why Choose ICT Center</h2>
            <p id="detail-ict-about">We provide a comprehensive learning ecosystem designed to take you from
                beginner to industry-ready professional.</p>
            <div class="aboutus-detail-block">
                <div class="box-category">
                    <i class="fa-solid fa-desktop"></i>
                    <h5>Pratical Learning</h5>
                    <p>learn by doing with hand-on labs, interactive coding environments, and real-world scenarios.
                    </p>
                </div>
                <div class="box-category">
                    <i class="fa-solid fa-book-open"></i>
                    <h5>Inductry Curriculum</h5>
                    <p>syllabuses design in collaboration with top tech companies to ensure relevance.</p>
                </div>
                <div class="box-category">
                    <i class="fa-solid fa-user-group"></i>
                    <h5>Experienced Instructors</h5>
                    <p>Learn from senior developers and Inductry veterans who bring real experience.</p>
                </div>
                <div class="box-category">
                    <i class="fa-solid fa-earth-europe"></i>
                    <h5>Recognized Certifications</h5>
                    <p>Earn verified certificates that are respected bt employers globally.</p>
                </div>
                <div class="box-category">
                    <i class="fa-solid fa-briefcase"></i>
                    <h5>Career Support</h5>
                    <p>Get 1-on-1 cereer coaching, resume reviews, and mock interview.</p>
                </div>
                <div class="box-category">
                    <i class="fa-solid fa-rocket"></i>
                    <h5>Real-world Projects</h5>
                    <p>Build a professional portfolio with projects that solve actual business problems.</p>
                </div>
            </div>
        </div>
    </div>


    <div class="section-title">
        <h2>Organizational Structure</h2>
        <p>Meet the leadership team driving our vision forward.</p>
    </div>
    <div class="top-card">
        <img src="frontend/asset/images/teacherNhim.jpg" alt="Mr.Nhanh Nhim">
        <h3>Mr.Nhanh Nhim</h3>
        <p class="role">CEO &amp; Founder</p>
        <div class="social-icons">
            <a href="#"><i class="fa-brands fa-facebook-f"></i></a>
            <a href="#"><i class="fa-brands fa-linkedin-in"></i></a>
            <a href="#"><i class="fa-solid fa-envelope"></i></a>
        </div>
    </div>
    <div class="line-down"></div>

    <!-- ── Level 2: Manager ── -->
    <div class="mid-card">
        <img src="frontend/asset/images/staff/vasotheavy-Deputy-director.jpg" alt="Mrs.So Theavy">
        <h3>Mrs.So Theavy</h3>
        <p class="role">Deputy Director of ICT</p>
    </div>
    <div class="line-down"></div>

    <div class="line-controll">
        <!-- Horizontal connector -->
        <div class="line-horizontal"></div>

        <!-- ── Level 3: Departments ── -->
        <div class="bottom-row">

            <!-- Column 1 -->
            <div class="dept-col">
                <div class="branch"></div>
                <div class="dept-card">
                    <p class="dept-name">Human Resourses</p>
                    <div class="person">
                        <img src="frontend/asset/images/staff/Channy.jpg" alt="">
                        <div>
                            <h4>Tha Channy</h4>
                            <span>Human Resourse</span>
                            <div class="social-icons">
                                <a href="#"><i class="fa-brands fa-linkedin-in"></i></a>
                                <a href="#"><i class="fa-brands fa-twitter"></i></a>
                            </div>
                        </div>
                    </div>
                    <button class="view-team-btn" onclick="toggleTeam(this)">
                        View Team (2) <i class="fa-solid fa-chevron-down"></i>
                    </button>
                </div>
                <div class="team-dropdown">
                    <div class="team-member-card">
                        <img src="frontend/asset/images/staff/Lin.jpg" alt="">
                        <h4>Choeun Dalin</h4>
                        <p class="role">Human Resourse</p>
                        <div class="social-icons">
                            <a href="#"><i class="fa-brands fa-linkedin-in"></i></a>
                            <a href="#"><i class="fa-brands fa-twitter"></i></a>
                        </div>
                    </div>
                    <div class="team-member-card">
                        <img src="frontend/asset/images/staff/Soriya.jpg" alt="">
                        <h4>Hout Soriya<h4>
                                <p class="role">Human Resourse</p>
                                <div class="social-icons">
                                    <a href="#"><i class="fa-brands fa-linkedin-in"></i></a>
                                    <a href="#"><i class="fa-brands fa-twitter"></i></a>
                                </div>
                    </div>
                </div>
            </div>

            <!-- end Column 1 -->

            <!-- Column 2 -->
            <div class="dept-col">
                <div class="branch"></div>
                <div class="dept-card">
                    <p class="dept-name">Digital Marketing</p>
                    <div class="person">
                        <img src="frontend/asset/images/staff/Kongkea.jpg" alt="">
                        <div>
                            <h4>Vatha Chetkongkea</h4>
                            <span>Digital Marketing</span>
                            <div class="social-icons">
                                <a href="#"><i class="fa-brands fa-linkedin-in"></i></a>
                                <a href="#"><i class="fa-brands fa-twitter"></i></a>
                            </div>
                        </div>
                    </div>
                    <button class="view-team-btn" onclick="toggleTeam(this)">
                        View Team (1) <i class="fa-solid fa-chevron-down"></i>
                    </button>
                </div>
                <div class="team-dropdown">
                    <div class="team-member-card">
                        <img src="frontend/asset/images/staff/Lyna.jpg" alt="">
                        <h4>Reth annalina</h4>
                        <p class="role">Digital Marketing</p>
                        <div class="social-icons">
                            <a href="#"><i class="fa-brands fa-linkedin-in"></i></a>
                            <a href="#"><i class="fa-brands fa-twitter"></i></a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- end Column 2 -->

            <!-- Column 3 -->
            <div class="dept-col">
                <div class="branch"></div>
                <div class="dept-card">
                    <p class="dept-name">Video Editing</p>
                    <div class="person">
                        <img src="frontend/asset/images/staff/Chan.jpg" alt="">
                        <div>
                            <h4>Than Chantheary</h4>
                            <span>Video editor</span>
                            <div class="social-icons">
                                <a href="#"><i class="fa-brands fa-linkedin-in"></i></a>
                                <a href="#"><i class="fa-brands fa-twitter"></i></a>
                            </div>
                        </div>
                    </div>
                    <button class="view-team-btn" onclick="toggleTeam(this)">
                        View Team (0) <i class="fa-solid fa-chevron-down"></i>
                    </button>
                </div>
                <!-- <div class="team-dropdown">
                                    <div class="team-member-card">
                                        <img src="frontend/asset/images/teachervornsarn.jpg" alt="">
                                        <h4>Staff Name 1</h4>
                                        <p class="role">Campaign Manager</p>
                                        <div class="social-icons">
                                            <a href="#"><i class="fa-brands fa-linkedin-in"></i></a>
                                            <a href="#"><i class="fa-brands fa-twitter"></i></a>
                                        </div>
                                    </div>
                                    <div class="team-member-card">
                                        <img src="frontend/asset/images/teachervornsarn.jpg" alt="">
                                        <h4>Staff Name 2</h4>
                                        <p class="role">Content Strategist</p>
                                        <div class="social-icons">
                                            <a href="#"><i class="fa-brands fa-linkedin-in"></i></a>
                                            <a href="#"><i class="fa-brands fa-twitter"></i></a>
                                        </div>
                                    </div>
                                    <div class="team-member-card">
                                        <img src="frontend/asset/images/teachervornsarn.jpg" alt="">
                                        <h4>Staff Name 3</h4>
                                        <p class="role">Social Media</p>
                                        <div class="social-icons">
                                            <a href="#"><i class="fa-brands fa-linkedin-in"></i></a>
                                            <a href="#"><i class="fa-brands fa-twitter"></i></a>
                                        </div>
                                    </div>
                                </div> -->
            </div>

            <!-- end Column 3 -->

            <!-- Column 4 -->
            <div class="dept-col">
                <div class="branch"></div>
                <div class="dept-card">
                    <p class="dept-name">App Development</p>
                    <div class="person">
                        <img src="frontend/asset/images/staff/Rasmey.jpg" alt="">
                        <div>
                            <h4>Savean Raksmey</h4>
                            <span>Mobile App developer</span>
                            <div class="social-icons">
                                <a href="#"><i class="fa-brands fa-linkedin-in"></i></a>
                                <a href="#"><i class="fa-brands fa-twitter"></i></a>
                            </div>
                        </div>
                    </div>
                    <button class="view-team-btn" onclick="toggleTeam(this)">
                        View Team (0) <i class="fa-solid fa-chevron-down"></i>
                    </button>
                </div>
                <!-- <div class="team-dropdown">
                                    <div class="team-member-card">
                                        <img src="frontend/asset/images/teacherSinMany.jpg" alt="">
                                        <h4>Staff Name 1</h4>
                                        <p class="role">Career Counselor</p>
                                        <div class="social-icons">
                                            <a href="#"><i class="fa-brands fa-linkedin-in"></i></a>
                                            <a href="#"><i class="fa-brands fa-twitter"></i></a>
                                        </div>
                                    </div>
                                    <div class="team-member-card">
                                        <img src="frontend/asset/images/teacherSinMany.jpg" alt="">
                                        <h4>Staff Name 2</h4>
                                        <p class="role">Alumni Coordinator</p>
                                        <div class="social-icons">
                                            <a href="#"><i class="fa-brands fa-linkedin-in"></i></a>
                                            <a href="#"><i class="fa-brands fa-twitter"></i></a>
                                        </div>
                                    </div>
                                    <div class="team-member-card">
                                        <img src="frontend/asset/images/teacherSinMany.jpg" alt="">
                                        <h4>Staff Name 3</h4>
                                        <p class="role">Student Support</p>
                                        <div class="social-icons">
                                            <a href="#"><i class="fa-brands fa-linkedin-in"></i></a>
                                            <a href="#"><i class="fa-brands fa-twitter"></i></a>
                                        </div>
                                    </div>
                                </div> -->
            </div>

            <!-- end Column 4 -->

            <!-- Column 5 -->
            <div class="dept-col">
                <div class="branch"></div>
                <div class="dept-card">
                    <p class="dept-name">UX/UI Design</p>
                    <div class="person">
                        <img src="frontend/asset/images/staff/Tra.jpg" alt="">
                        <div>
                            <h4>Phat Sopheaktra</h4>
                            <span>UX/UI Designer</span>
                            <div class="social-icons">
                                <a href="#"><i class="fa-brands fa-linkedin-in"></i></a>
                                <a href="#"><i class="fa-brands fa-twitter"></i></a>
                            </div>
                        </div>
                    </div>
                    <button class="view-team-btn" onclick="toggleTeam(this)">
                        View Team (2) <i class="fa-solid fa-chevron-down"></i>
                    </button>
                </div>
                <div class="team-dropdown">
                    <div class="team-member-card">
                        <img src="frontend/asset/images/staff/Limey.jpg" alt="">
                        <h4>Liv Limey</h4>
                        <p class="role">UX/UI Designer</p>
                        <div class="social-icons">
                            <a href="#"><i class="fa-brands fa-linkedin-in"></i></a>
                            <a href="#"><i class="fa-brands fa-twitter"></i></a>
                        </div>
                    </div>
                    <div class="team-member-card">
                        <img src="frontend/asset/images/staff/Serey.jpg" alt="">
                        <h4>Yin Prasethy Serey</h4>
                        <p class="role">UX/UI Designer</p>
                        <div class="social-icons">
                            <a href="#"><i class="fa-brands fa-linkedin-in"></i></a>
                            <a href="#"><i class="fa-brands fa-twitter"></i></a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- end Column 5 -->

            <!-- Column 6 -->
            <div class="dept-col">
                <div class="branch"></div>
                <div class="dept-card">
                    <p class="dept-name">Web Development</p>
                    <div class="person">
                        <img src="frontend/asset/images/staff/Ra.jpg" alt="">
                        <div>
                            <h4>Seth Sopheara</h4>
                            <span>Full-Stack Developer</span>
                            <div class="social-icons">
                                <a href="#"><i class="fa-brands fa-linkedin-in"></i></a>
                                <a href="#"><i class="fa-brands fa-twitter"></i></a>
                            </div>
                        </div>
                    </div>
                    <button class="view-team-btn" onclick="toggleTeam(this)">
                        View Team (7) <i class="fa-solid fa-chevron-down"></i>
                    </button>
                </div>
                <div class="team-dropdown">
                    <div class="team-member-card">
                        <img src="frontend/asset/images/staff/Theary.jpg" alt="">
                        <h4>Uy Sotheary</h4>
                        <p class="role">Frontend Developer</p>
                        <div class="social-icons">
                            <a href="#"><i class="fa-brands fa-linkedin-in"></i></a>
                            <a href="#"><i class="fa-brands fa-twitter"></i></a>
                        </div>
                    </div>
                    <div class="team-member-card">
                        <img src="frontend/asset/images/staff/Lymeng.jpg" alt="">
                        <h4>Chhay Lymeng</h4>
                        <p class="role">Tester</p>
                        <div class="social-icons">
                            <a href="#"><i class="fa-brands fa-linkedin-in"></i></a>
                            <a href="#"><i class="fa-brands fa-twitter"></i></a>
                        </div>
                    </div>
                    <div class="team-member-card">
                        <img src="frontend/asset/images/staff/rathana.jpg" alt="">
                        <h4>Srin Rathana</h4>
                        <p class="role">Backend Developer</p>
                        <div class="social-icons">
                            <a href="#"><i class="fa-brands fa-linkedin-in"></i></a>
                            <a href="#"><i class="fa-brands fa-twitter"></i></a>
                        </div>
                    </div>
                    <div class="team-member-card">
                        <img src="frontend/asset/images/staff/Sak.jpg" alt="">
                        <h4>Tep Panhahsak</h4>
                        <p class="role">Backend Developer</p>
                        <div class="social-icons">
                            <a href="#"><i class="fa-brands fa-linkedin-in"></i></a>
                            <a href="#"><i class="fa-brands fa-twitter"></i></a>
                        </div>
                    </div>
                    <div class="team-member-card">
                        <img src="frontend/asset/images/staff/Vathana.jpg" alt="">
                        <h4>Roeun Vathana</h4>
                        <p class="role">Backend Developer</p>
                        <div class="social-icons">
                            <a href="#"><i class="fa-brands fa-linkedin-in"></i></a>
                            <a href="#"><i class="fa-brands fa-twitter"></i></a>
                        </div>
                    </div>
                    <div class="team-member-card">
                        <img src="frontend/asset/images/staff/Sothon.jpg" alt="">
                        <h4>Yeong Vachekasy sothon</h4>
                        <p class="role">Backend Developer</p>
                        <div class="social-icons">
                            <a href="#"><i class="fa-brands fa-linkedin-in"></i></a>
                            <a href="#"><i class="fa-brands fa-twitter"></i></a>
                        </div>
                    </div>
                    <div class="team-member-card">
                        <img src="frontend/asset/images/staff/Sothy.jpg" alt="">
                        <h4>Keurn Sothy</h4>
                        <p class="role">Backend Developer</p>
                        <div class="social-icons">
                            <a href="#"><i class="fa-brands fa-linkedin-in"></i></a>
                            <a href="#"><i class="fa-brands fa-twitter"></i></a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- end Column 6 -->
        </div>

    </div>


    <!-- end .bottom-row -->
    <div class="website-dev-team-block">
        <h2>Website Development Team</h2>
        <p>The Technical core behind ICT Center's digital presence and platform architecture.</p>
        <div class="developer-team-box">
            <div class="project-manager-web">
                <div class="img-name-role">
                    <img id="img" src="frontend/asset/images/staff/backend(bong pheara).jpg" alt="">
                    <div class="name-role">
                        <h5>Dev.Seth Sopheara</h5>
                        <p>Project Manager <br> <span>Full-Stack</span></p>
                    </div>
                </div>
                <p id="web-manager-quote">Leads the development and maintenance of all ICT Center digital platforms,
                    overseeing architecture design, code reviews, and deployment pipelines.</p>
                <div class="technicial-skill">
                    <button>Full-Stack</button>
                    <button>Frontend</button>
                    <button>Backend</button>
                    <button>System Design</button>
                </div>
            </div>
            <div class="member-team-block">
                <div class="member-team-dev">
                    <div class="img-name-role">
                        <img src="frontend/asset/images/staff/frontend(theary).jpg" alt="">
                        <div class="name-role">
                            <h5>Dev.Uy sotheary</h5>
                            <p>Frontend Developer</p>
                        </div>
                    </div>
                    <div class="technicial-skill">
                        <button>Frontend</button>
                        <button>React</button>
                        <button>UI Development</button>
                    </div>
                </div>
                <div class="member-team-dev">
                    <div class="img-name-role">
                        <img src="frontend/asset/images/staff/backend(bong pheara).jpg" alt="">
                        <div class="name-role">
                            <h5>Dev.Seth Sopheara</h5>
                            <p>Backend Developer</p>
                        </div>
                    </div>
                    <div class="technicial-skill">
                        <button>Backend</button>
                        <button>API</button>
                        <button>Database</button>
                    </div>
                </div>
                <div class="member-team-dev">
                    <div class="img-name-role">
                        <img src="frontend/asset/images/staff/QAT(bong pheaktra).JPG" alt="">
                        <div class="name-role">
                            <h5>Dev.Phat Sopheaktra</h5>
                            <p>UX/UI & QAT</p>
                        </div>
                    </div>
                    <div class="technicial-skill">
                        <button>UI/UX</button>
                        <button>Figma</button>
                        <button>QAT</button>
                    </div>
                </div>
                <div class="member-team-dev">
                    <div class="img-name-role">
                        <img src="frontend/asset/images/staff/chay-lymeng-web-dev-team.JPG" alt="">
                        <div class="name-role">
                            <h5>Dev.Chay Lymeng</h5>
                            <p>QAT</p>
                        </div>
                    </div>
                    <div class="technicial-skill">
                        <button>Tester</button>
                        <button>QAT</button>
                        <button>Bug Report</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="our-gallery-block">
        <h2>Our Gallary</h2>
        <p>Glimpses of Life at ICT Center.</p>
        <div class="our-gallery-category">
            <div class="tab">
                <button class="tablinks active" onclick="openCity(event, 'All')">All</button>
                <button class="tablinks" onclick="openCity(event, 'Classes')">Classes</button>
                <button class="tablinks" onclick="openCity(event, 'Competition')">Competition</button>
                <button class="tablinks" onclick="openCity(event, 'Graduation')">Graduation</button>
            </div>
            <div id="All" class="tabcontent active" style="display: block;">
                <div class="about-gallery">
                    <img src="frontend/asset/images/slide-cut-v8.jpg" alt="2">
                    <img src="frontend/asset/images/slide-cut-v11.jpg" alt="2">
                    <img src="frontend/asset/images/slide-cut-v13.jpg" alt="2">
                    <img src="frontend/asset/images/ICT_Slideshow4.jpg" alt="2">
                    <img src="frontend/asset/images/slide-cut-v14.jpg" alt="2">
                    <img src="frontend/asset/images/slide-cut-v15.jpg" alt="2">
                    <img src="frontend/asset/images/ICT_ShildeShow2.jpg" alt="2">
                    <img src="frontend/asset/images/slide-cut-v17.jpg" alt="2">
                    <img src="frontend/asset/images/slide-cut-v18.jpg" alt="2">
                    <img src="frontend/asset/images/slide-cut-v19.jpg" alt="2">
                    <img src="frontend/asset/images/slide-cut-v21.jpg" alt="2">
                    <img src="frontend/asset/images/slide-cut-v5.jpg" alt="2">
                    <img src="frontend/asset/images/slide-cut-v6.jpg" alt="2">
                    <img src="frontend/asset/images/slide-cut-v7.jpg" alt="2">
                    <img src="frontend/asset/images/slide-cut-v9.jpg" alt="2">
                </div>
            </div>
            <div id="Classes" class="tabcontent" style="display:none">
                <div class="about-gallery">

                    <img src="frontend/asset/images/slide-cut-v11.jpg" alt="2">
                    <img src="frontend/asset/images/ICT_Slideshow4.jpg" alt="2">
                    <img src="frontend/asset/images/slide-cut-v22.JPG" alt="2">
                    <img src="frontend/asset/images/slide-cut-v23.JPG" alt="2">
                    <img src="frontend/asset/images/slide-cut-v21.jpg" alt="2">
                    <img src="frontend/asset/images/slide-cut-v5.jpg" alt="2">

                </div>
            </div>
            <div id="Competition" class="tabcontent" style="display:none">
                <div class="about-gallery">
                    <img src="frontend/asset/images/slide-cut-v8.jpg" alt="2">
                    <img src="frontend/asset/images/slide-cut-v21.jpg" alt="2">
                    <img src="frontend/asset/images/slide-cut-v14.jpg" alt="2">
                    <img src="frontend/asset/images/slide-cut-v15.jpg" alt="2">
                    <img src="frontend/asset/images/ICT_SlideShow.jpg" alt="2">
                    <img src="frontend/asset/images/slide-cut-v17.jpg" alt="2">
                    <img src="frontend/asset/images/slide-cut-v18.jpg" alt="2">
                    <img src="frontend/asset/images/slide-cut-v19.jpg" alt="2">
                    <img src="frontend/asset/images/slide-cut-v13.jpg" alt="2">
                    <img src="frontend/asset/images/slide-cut-v7.jpg" alt="2">
                    <img src="frontend/asset/images/slide-cut-v9.jpg" alt="2">
                </div>
            </div>
            <div id="Graduation" class="tabcontent" style="display:none">
                <div class="about-gallery">
                    <img src="frontend/asset/images/slide-cut-v9.jpg" alt="2">
                    <img src="frontend/asset/images/slide-cut-v19.jpg" alt="2">
                    <img src="frontend/asset/images/slide-cut-v13.jpg" alt="2">
                    <img src="frontend/asset/images/slideshow4.jpg" alt="2">
                    <img src="frontend/asset/images/slide-cut-v14.jpg" alt="2">
                    <img src="frontend/asset/images/slide-cut-v15.jpg" alt="2">
                    <img src="frontend/asset/images/ICT_ShildeShow2.jpg" alt="2">
                    <img src="frontend/asset/images/slide-cut-v17.jpg" alt="2">
                    <img src="frontend/asset/images/slide-cut-v18.jpg" alt="2">
                    <img src="frontend/asset/images/slide-cut-v11.jpg" alt="2">
                    <img src="frontend/asset/images/slide-cut-v21.jpg" alt="2">
                    <img src="frontend/asset/images/slide-cut-v5.jpg" alt="2">
                    <img src="frontend/asset/images/slide-cut-v6.jpg" alt="2">
                    <img src="frontend/asset/images/slide-cut-v7.jpg" alt="2">
                    <img src="frontend/asset/images/slide-cut-v8.jpg" alt="2">
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        /* Toggle a dropdown sub-menu open / closed */
        function toggleMenu(item) {
            /* Find the <ul class="sub-menu"> right after this item */
            const subMenu = item.nextElementSibling;
            /* Toggle the "open" class on both the button and the list */
            item.classList.toggle('open');
            subMenu.classList.toggle('open');
        }
        // Scroll-triggered animation for message block
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.15
        });
        document.querySelectorAll('.message-by-kru-nhim').forEach(el => observer.observe(el));
        //OUR-GALLERY-CATEGORY-BLOCK
        function openCity(evt, cityName) {
            var i, tabcontent, tablinks;
            tabcontent = document.getElementsByClassName("tabcontent");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }
            tablinks = document.getElementsByClassName("tablinks");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(" active", "");
            }
            const cityTab = document.getElementById(cityName);
            if (cityTab) {
                cityTab.style.display = "block";
            }
            evt.currentTarget.className += " active";
        }
        //------script-tree-binary-dropdown--------
        function toggleTeam(btn) {
            // team-dropdown is the next sibling after dept-card
            const deptCol = btn.closest('.dept-col');
            const teamDropdown = deptCol.querySelector('.team-dropdown');
            const isOpen = teamDropdown.classList.contains('show');
            const count = teamDropdown.querySelectorAll('.team-member-card').length;
            teamDropdown.classList.toggle('show');
            btn.innerHTML = isOpen ?
                `View Team (${count}) <i class="fa-solid fa-chevron-down"></i>` :
                `Hide Team <i class="fa-solid fa-chevron-up"></i>`;
        }
    </script>
@endpush
