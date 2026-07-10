<footer>
    <div class="footer-grid">

        <!-- Brand -->
        <div class="footer-brand">
            <div class="brand-row">
                <img class="footerimg" src="frontend/asset/images/ICTLogo9.jpg" alt="">
                <span class="brand-name">ICT Center</span>
            </div>
            <p class="brand-tagline">Empowering professsionals through world-class ICT education since 2020.</p>
            <div class="socials">
                <a href="https://web.facebook.com/profile.php?id=61551254861573#" class="social-btn"><i
                        class="fab fa-facebook-f"></i></a>
                <a href="https://t.me/ICTprofessionlcenter" class="social-btn"><i class="fab fa-telegram-plane"></i></a>
                <a href="https://www.tiktok.com/@ict.center" class="social-btn"><i class="fab fa-tiktok"></i></a>
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
                <a href="https://maps.app.goo.gl/3568UAgzxBwvwL9S7" target="_blank"
                    style="text-decoration: none; color: inherit;">
                    <span class="contact-text">
                        ផ្ទះលេខ 240B ផ្លូវ 132 ភូមិ06 សង្កាត់ទឹកល្អក់ទី01 ខណ្ឌទួលគោក រាជធានីភ្នំពេញ
                    </span>
                </a>
            </div>
            <div class="contact-item">
                <div class="contact-icon"><i class="fas fa-phone"></i></div>
                <span class="contact-text">0123837733</span>
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
            <div class="mb-2">
                <img src="frontend/asset/images/footer/appstore.svg" alt="">
            </div>
            <div>
                <img src="frontend/asset/images/footer/playstore.svg" alt="">
            </div>
        </div>
    </div>
</footer>
