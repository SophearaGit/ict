<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        @yield('page_title')
    </title>
    <!-- Favicon icon-->
    <link href="{{ asset('https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('/frontend/assets/ictImg/logo/ictLogo.jpg') }}" />
    <link rel="stylesheet"
        href="{{ asset('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/frontend/assets-new/css/style.css') }}">
    <script src="{{ asset('https://code.jquery.com/jquery-3.6.0.min.js') }}"></script>
    @stack('styles')
</head>

<body>
    <div class="container">
        @include('frontend.layouts.new.header')
        @include('frontend.layouts.new.mobile-drawer')
        @yield('content')
        @include('frontend.layouts.new.footer')
    </div>
    <!-- end .container -->
    <script src="{{ asset('https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js') }}"></script>
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
        const track = document.querySelector('.partner-track-wrap');
        const inner = document.querySelector('.partner-track');
        let isDown = false,
            startX, scrollLeft;
        // Pause animation on hover (already in your CSS, this is just the drag-to-scroll part)
        track.addEventListener('mousedown', (e) => {
            isDown = true;
            startX = e.pageX - track.offsetLeft;
            scrollLeft = track.scrollLeft;
            inner.style.animationPlayState = 'paused';
        });
        track.addEventListener('mouseleave', () => {
            isDown = false;
            inner.style.animationPlayState = 'running';
        });
        track.addEventListener('mouseup', () => {
            isDown = false;
            inner.style.animationPlayState = 'running';
        });
        track.addEventListener('mousemove', (e) => {
            if (!isDown) return;
            e.preventDefault();
            const x = e.pageX - track.offsetLeft;
            const walk = (x - startX) * 1.5;
            track.scrollLeft = scrollLeft - walk;
        });
        // Touch support (mobile)
        let touchStartX, touchScrollLeft;
        track.addEventListener('touchstart', (e) => {
            touchStartX = e.touches[0].pageX - track.offsetLeft;
            touchScrollLeft = track.scrollLeft;
            inner.style.animationPlayState = 'paused';
        });
        track.addEventListener('touchend', () => {
            inner.style.animationPlayState = 'running';
        });
        track.addEventListener('touchmove', (e) => {
            const x = e.touches[0].pageX - track.offsetLeft;
            const walk = (x - touchStartX) * 1.5;
            track.scrollLeft = touchScrollLeft - walk;
        });
        // let f = 0;
        // var isfull = 0;
        // setInterval(function () {
        //   if (isfull == 1) {
        //     f = f - 1;
        //     $(".boxx").css("transform", `translateX(${-f * 210}px)`);
        //     if (f === 0) {
        //       isfull = 0;
        //       return;
        //     }
        //   } else {
        //     f = f + 1;
        //     $(".boxx").css("transform", `translateX(${-f * 210}px)`);
        //     if (f === 2) {
        //       isfull = 1;
        //       return;
        //     }
        //   }
        // }, 4000);
        /* Course Slider and Cards  */
        fetch("/frontend/assets-new/json/coursebox.json")
            .then(res => res.json())
            .then(data => {
                let mid = '';
                for (const cards of data) {
                    mid += `<div class="acb">
                        <i class="${cards["icon"]}"></i>
                            <p>${cards["title"]}</p>
                        </div>`;
                }
                document.querySelector(".boxcourse").innerHTML = mid;
                // fetch("/frontend/assets-new/json/course.json")
                //     .then(res => res.json())
                //     .then(data => {
                //         let cardHtml = '';
                //         for (const cards of data) {
                //             cardHtml += `
                //         <div class="boxcard">
                //             <img src="${cards["image"]}" alt="Course">
                //             <div class="teacher">
                //                 <img src="/frontend/assets-new/images/OIP (5).webp" alt="Teacher">
                //                 <p>Phat Sopheaktra</p>
                //                 <button>Development</button>
                //             </div>
                //             <h2>${cards["title"]}</h2>
                //             <div class="weekschedule">
                //                 <i class="fa-regular fa-calendar-days"></i>
                //                 <p>Weekly Schedule</p>
                //                 <p class="hour">48 hours</p>
                //             </div>
                //             <p class="pweekly">. Mon-Tue-Wed (18:00 - 20:00pm) <br>. Sat (13:00 - 16:00pm)<br>. Sun (13:00 - 16:00pm)</p>
                //             <div class="prnrate">
                //                 <h3>$${cards["price"]}.00</h3>
                //                 <div class="starate">
                //                     <p>4.9</p>
                //                     <i class="fa-solid fa-star" style="color:gold;"></i>
                //                     <i class="fa-solid fa-star" style="color:gold;"></i>
                //                     <i class="fa-solid fa-star" style="color:gold;"></i>
                //                     <i class="fa-solid fa-star" style="color:gold;"></i>
                //                     <i class="fa-solid fa-star" style="color:gold;"></i>
                //                 </div>
                //             </div>
                //         </div>`;
                //         }
                //         document.querySelector(".mainbox").innerHTML = cardHtml;
                //     });
                // const maxIndex = Math.max(0, data.length - 1);
                // let i = 0;
                // $('#iii').click(() => {
                //     i = (i >= maxIndex) ? 0 : i + 1;
                //     $('.boxcourse').css("transform", `translateX(${-i * 250}px)`);
                // });
                // $('#ii').click(() => {
                //     i = (i <= 0) ? maxIndex : i - 1;
                //     $('.boxcourse').css("transform", `translateX(${-i * 250}px)`);
                // });
            })
            .catch(err => console.error("Error:", err));
    </script>
</body>

</html>
