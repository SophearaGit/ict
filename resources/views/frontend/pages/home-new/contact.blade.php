@extends('frontend.layouts.new.master')
@section('page_title', isset($page_title) ? $page_title : 'Page Title Here')
@section('content')
    <div class="contact-top-banner">
        <h2 id="contacth2" data-aos="fade-up">Get in Touch</h2>
        <p id="contactp" data-aos="fade-up" data-aos-delay="100">Have questions? We'd love to hear from you. Send us a
            message and we'll respond a soon as
            possible.</p>
    </div>
    <div class="messagenhotline">
        <form class="message-block" data-aos="fade-right">
            <h3>Sent us to a message</h3>
            <label for="contact-name">Your Name:</label>
            <input type="text" id="contact-name" name="name" placeholder="Enter your name...">
            <label for="contact-phone">Phone Number:</label>
            <input type="text" id="contact-phone" name="phone" placeholder="Enter your phone number...">
            <label for="contact-message">Your Message:</label>
            <textarea id="contact-message" name="message" cols="30" rows="10" placeholder="Write your message here..."></textarea>
            <button type="submit">Send Message</button>
        </form>
        <div class="contact-information-with-map" data-aos="fade-left">
            <div class="hotline">
                <div class="location">
                    <i class="fas fa-map-marker-alt"></i>
                    <p>Our Office <br><span>ផ្លូវលេខ240B ផ្លូវ132 ភូមិ06 សង្កាត់ទឹក ល្អក់ទី01 ខណ្ឌទួលគោក
                            រាជធានីភ្នំពេញ</span></p>
                </div><br>
                <div class="location">
                    <i class="fas fa-phone"></i>
                    <p>Phone Number <br><span>092 702 175 / 096 287 5270</span></p>
                </div><br>
                <div class="location">
                    <i class="fas fa-clock"></i>
                    <p>Office Hour <br><span>Mon-Fri 8:00AM - 8:00PM / Sat-Sun 8:00AM - 6:00PM</span></p>
                </div>
                <br>
            </div>
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m13!1m8!1m3!1d3908.811541490112!2d104.8916802!3d11.565364!3m2!1i1024!2i768!4f13.1!3m2!1m1!2zMTHCsDMzJzU1LjciTiAxMDTCsDUzJzMwLjQiRQ!5e0!3m2!1sen!2skh!4v1779854389608!5m2!1sen!2skh"
                width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
    </div>
    <div class="question-block" data-aos="fade-up">
        <h1>Frequently Asked Questions</h1>
        <p>Find quick answers to common questions.</p>
        <div class="accordion-menu">
            <ul>
                <li>
                    <input type="checkbox" title="click to get answer" checked>
                    <i class="arrow"></i>
                    <h2>Languages Used</h2>
                    <p>This UI was written in HTML and CSS.</p>
                </li>
                <li>
                    <input type="checkbox" title="click to get answer" checked>
                    <i class="arrow"></i>
                    <h2>How can I enroll in a course?</h2>
                    <p>Using the sibling and checked selectors, we can determine
                        the styling of sibling elements based on the checked state
                        of the checkbox input element.
                    </p>
                </li>
                <li>
                    <input type="checkbox" title="click to get answer" checked>
                    <i class="arrow"></i>
                    <h2>Points of Interest</h2>
                    <p>By making the open state default for when :checked isn't
                        detected, we can make this system accessable for browsers
                        that don't recognize :checked.
                    </p>
                </li>
                <li>
                    <input type="checkbox" title="click to get answer" checked>
                    <i class="arrow"></i>
                    <h2>Points of Interest</h2>
                    <p>By making the open state default for when :checked isn't
                        detected, we can make this system accessable for browsers
                        that don't recognize :checked.
                    </p>
                </li>
                <li>
                    <input type="checkbox" title="click to get answer" checked>
                    <i class="arrow"></i>
                    <h2>Points of Interest</h2>
                    <p>By making the open state default for when :checked isn't
                        detected, we can make this system accessable for browsers
                        that don't recognize :checked.
                    </p>
                </li>
            </ul>
        </div>
    </div>
@endsection
