<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="ICT Professional Training Center offers hands-on courses in programming, web development, design, data, and networking — taught by industry professionals in Phnom Penh.">
    <title>
        @yield('page_title', 'NO TITLE') - ICT Professional Training Center
    </title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&family=Lexend:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <!-- Favicon icon-->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('/frontend/assets/ictImg/logo/ictLogo.jpg') }}" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="{{ asset('frontend/asset/css/style.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">

    <!-- In your <head> -->

    <!-- Flatpickr (date picker) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <!-- Choices.js (clean select dropdowns) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">
    @stack('styles')
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
    @stack('meta')
</head>

<body>
    <div class="container">
        @include('frontend.layouts.new.header')
        @include('frontend.layouts.new.mobile-drawer')
        @yield('content')

        <!-- ═══ AD POPUP MODAL (shows once on page load) ═══ -->
        @if (Request::is('/'))
            <div class="ad-popup-overlay" id="adPopupOverlay">
                <div class="ad-popup-box">
                    <button class="ad-popup-close" id="adPopupClose">&times;</button>
                    <div class="advertisement-slide">
                        <div id="carouselExampleInterval" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                <div class="carousel-item active" data-bs-interval="4000">
                                    <img src="frontend/asset/images/slide-cut-v24.jpg" class="d-block w-100"
                                        alt="...">
                                </div>
                                <div class="carousel-item" data-bs-interval="4000">
                                    <img src="frontend/asset/images/slide-cut-v15.jpg" class="d-block w-100"
                                        alt="...">
                                </div>
                                <div class="carousel-item">
                                    <img src="frontend/asset/images/ICT_SlideShow.jpg" class="d-block w-100"
                                        alt="...">
                                </div>
                            </div>
                            <button class="carousel-control-prev" type="button"
                                data-bs-target="#carouselExampleInterval" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button"
                                data-bs-target="#carouselExampleInterval" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @include('frontend.layouts.new.footer')
    </div>

    <!-- end .container -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <script>
        AOS.init({
            duration: 700,
            easing: 'ease-out-cubic',
            once: true,
            offset: 80
        });
    </script>

    <!-- Dark/Light Mode Script -->
    <script>
        const html = document.documentElement;

        function applyTheme(theme) {
            html.setAttribute('data-theme', theme);
            document.getElementById('themeIcon').className = theme === 'dark' ? 'fa-solid fa-sun' : 'fa-solid fa-moon';
            localStorage.setItem('theme', theme);
        }
        const saved = localStorage.getItem('theme') ||
            (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
        applyTheme(saved);
        document.getElementById('themeToggle').addEventListener('click', () => {
            applyTheme(html.getAttribute('data-theme') === 'dark' ? 'light' : 'dark');
        });
    </script>
    <script>
        /* ── Ad Popup Modal: auto-show on load, close on X or overlay click ── */
        window.addEventListener('load', () => {
            const adPopup = document.getElementById('adPopupOverlay');
            if (adPopup) {
                setTimeout(() => adPopup.classList.add('show'), 400);
            }
        });
        const adPopupClose = document.getElementById('adPopupClose');
        if (adPopupClose) {
            adPopupClose.addEventListener('click', () => {
                document.getElementById('adPopupOverlay').classList.remove('show');
            });
        }
        const adPopupOverlay = document.getElementById('adPopupOverlay');
        if (adPopupOverlay) {
            adPopupOverlay.addEventListener('click', (e) => {
                if (e.target.id === 'adPopupOverlay') {
                    e.currentTarget.classList.remove('show');
                }
            });
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
        /* ── Register buttons -> go to register.html ── */
        document.querySelectorAll('#registerbtn, .drawer-register').forEach(btn => {
            btn.addEventListener('click', () => window.location.href = 'register.html');
        });
        /* ── Hamburger Drawer ── */
        const hamburger = document.getElementById('hamburger');
        const drawer = document.getElementById('mobileDrawer');
        hamburger.addEventListener('click', () => {
            hamburger.classList.toggle('open');
            drawer.classList.toggle('open');
            document.body.style.overflow = drawer.classList.contains('open') ? 'hidden' : '';
        });
        drawer.querySelectorAll('a').forEach(a => {
            a.addEventListener('click', () => {
                hamburger.classList.remove('open');
                drawer.classList.remove('open');
                document.body.style.overflow = '';
            });
        });
        var i = 0;
        var a = setInterval(function() {
            $('#activest').text(i);
            i++;
            if (i > 2500) {
                clearInterval(a);
                $('#activest').text("2,500+");
            }
        }, 1);
        var j = 0;
        var b = setInterval(function() {
            $('#professionaltea').text(j);
            j++;
            if (j > 280) {
                clearInterval(b);
                $('#professionaltea').text("280+");
            }
        }, 20);
        var k = 0;
        var c = setInterval(function() {
            $('#languagesavail').text(k);
            k++;
            if (k > 28) {
                clearInterval(c);
                $('#languagesavail').text("28+");
            }
        }, 60);
        var l = 0;
        var intervalIid = setInterval(function() {
            $('#trainingevents').text(l);
            l++;
            if (l > 320) {
                clearInterval(intervalIid);
                $('#trainingevents').text("320+");
            }
        }, 20);
        /* Toggle a dropdown sub-menu open / closed */
        function toggleMenu(item) {
            /* Find the <ul class="sub-menu"> right after this item */
            const subMenu = item.nextElementSibling;
            /* Toggle the "open" class on both the button and the list */
            item.classList.toggle('open');
            subMenu.classList.toggle('open');
        }
    </script>
</body>

</html>
