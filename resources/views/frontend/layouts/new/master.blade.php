<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        @yield('page_title', 'NO TITLE') - ICT Professional Training Center
    </title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tabler-icons/3.35.0/tabler-icons.min.css"
        integrity="sha512-gzw5zNP2TRq+DKyAqZfDclaTG4dOrGJrwob2Fc8xwcJPDPVij0HowLIMZ8c1NefFM0OZZYUUUNoPfcoI5jqudw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Favicon icon-->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('frontend/assets/ictImg/logo/ictLogo.jpg') }}" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="{{ asset('frontend/asset/css/style.css') }}">
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
        @include('frontend.layouts.new.footer')
    </div>

    <!-- end .container -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

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
    @stack('scripts')
</body>

</html>
