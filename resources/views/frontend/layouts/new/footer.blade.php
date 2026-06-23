<!-- footer section -->
<footer>
    <div class="footer-grid">

        <!-- Brand -->
        <div class="footer-brand">
            <div class="brand-row">
                <img class="footerimg" src="{{ asset('frontend/asset/images/ICTLogo9.jpg') }}" alt="">
                <span class="brand-name">ICT Center</span>
            </div>
            <p class="brand-tagline">Empowering professionals through world-class ICT education since 2020.</p>
            <div class="socials">
                <a href="https://www.facebook.com/share/1EbvE5fes5/" class="social-btn" target="_blank"><i
                        class="fab fa-facebook-f"></i></a>
                <a href="https://t.me/ictinfo1" class="social-btn" target="_blank"><i
                        class="fab fa-telegram-plane"></i></a>
                <a href="https://www.tiktok.com/@ict.center?_r=1&_t=ZS-978aXUeqBMo" class="social-btn"
                    target="_blank"><i class="fab fa-tiktok"></i></a>
                <a href="#" class="social-btn"><i class="fab fa-linkedin-in"></i></a>
            </div>
        </div>

        <!-- About -->
        <div class="footer-col">
            <h4>About ICT</h4>
            <ul>
                <li><a href="{{ url('/') }}">Home</a></li>
                <li><a href="{{ route('course') }}">All Course</a></li>
                <li><a href="#">About</a></li>
                <li><a href="#">Blog</a></li>
                <li><a href="{{ route('contact') }}">Contact Us</a></li>
            </ul>
        </div>

        <!-- Popular Course -->
        <div class="footer-col">
            <h4>Popular Course</h4>
            <ul>
                @foreach ($popularCourses as $course)
                    <li>
                        <a href="{{ route('course', ['search' => $course->title]) }}">
                            {{ $course->title }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>

        <!-- Contact -->
        <div class="footer-col">
            <h4>Contact Info</h4>
            <div class="contact-item">
                <div class="contact-icon"><i class="fas fa-map-marker-alt"></i></div>
                {{-- link --}}
                <a href="https://maps.app.goo.gl/3568UAgzxBwvwL9S7" target="_blank">
                    <span class="contact-text">
                        ផ្ទះលេខ 240B ផ្លូវ 132 ភូមិ06 សង្កាត់ទឹកល្អក់ទី01 ខណ្ឌទួលគោក រាជធានីភ្នំពេញ
                    </span>
                </a>
            </div>
            <div class="contact-item">
                <div class="contact-icon"><i class="fas fa-phone"></i></div>
                <span class="contact-text">092 702 175 <br> 096 287 5270</span>
            </div>
            <div class="contact-item">
                <div class="contact-icon"><i class="fas fa-envelope"></i></div>
                <span class="contact-text">ICTCenter@gmail.com</span>
            </div>
        </div>

        <!-- Download App -->
        <div class="footer-col app-col">
            <h4>Download App</h4>
            <p class="app-desc">Download our app from the App Store and Google Play Store.</p>
            <div class="playstore">
                <img src="{{ asset('frontend/asset/images/palystore.jpg') }}" alt="" class="rounded">
                <div class="textplaystore">
                    <p>Download on <span><b>Play Store</b></span></p>
                </div>
            </div>
            <div class="appstore">
                <img src="{{ asset('frontend/asset/images/App_Store_(iOS).svg') }}" alt="">
                <div class="textappstore">
                    <p>Download on <span><b>App Store</b></span></p>
                </div>
            </div>
        </div>
    </div>
</footer>
