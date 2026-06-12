@extends('frontend.layouts.new.master')
@section('page_title', isset($page_title) ? $page_title : 'Page Title Here')
@section('content')
    <h2 id="contacth2">Get in Touch</h2>
    <p id="contactp">Have questions? We'd love to hear from you. Send us a message and we'll respond a soon as possible.</p>
    <div class="messagenhotline">
        <div class="message-block">
            <h3>Sent us to a message</h3>
            <label for="">Your Name:</label>
            <input type="text" placeholder="Uy Sotheary">
            <label for="">Phone Number:</label>
            <input type="text">
            <label for="">Your Message:</label>
            <textarea name="" id="" cols="30" rows="10" placeholder="Write your message here..."></textarea>
            <button>Send Message</button>
        </div>
        <div class="hotline">
            <div class="location">
                <i class="fas fa-map-marker-alt"></i>
                <p>Our Office <br><span>Phnom Penh</span></p>
            </div><br>
            <div class="location">
                <i class="fas fa-phone"></i>
                <p>Phone Number <br><span>+855 12 383 773</span></p>
            </div><br>
            <div class="location">
                <i class="fas fa-clock"></i>
                <p>Office Hour <br><span>Mon-Fri 9:00AM - 6:00PM</span></p>
            </div>
            <br>
        </div>
    </div>

    <iframe
        src="https://www.google.com/maps/embed?pb=!1m13!1m8!1m3!1d3908.811541490112!2d104.8916802!3d11.565364!3m2!1i1024!2i768!4f13.1!3m2!1m1!2zMTHCsDMzJzU1LjciTiAxMDTCsDUzJzMwLjQiRQ!5e0!3m2!1sen!2skh!4v1779854389608!5m2!1sen!2skh"
        width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"
        referrerpolicy="no-referrer-when-downgrade"></iframe>

    <div class="question-block">
        <h1>Frequently Asked Questions</h1>
        <p>Find quick answers to common questions.</p>
        <div class="questionselect">
            <select name="" id="">
                <option class="option" value="">How can I enroll in a course?</option>
                <option class="option" value="">Answer 1</option>
                <option class="option" value="">Answer 2</option>
                <option class="option" value="">Answer 3</option>
                <option class="option" value="">Answer 4</option>
            </select> <br>
            <select name="" id="">
                <option class="option" value="">What payment methods do you accept?</option>
                <option class="option" value="">Answer 1</option>
                <option class="option" value="">Answer 2</option>
                <option class="option" value="">Answer 3</option>
                <option class="option" value="">Answer 4</option>
            </select> <br>
            <select name="" id="">
                <option class="option" value="">Can I get a refund if I am not satisfied?</option>
                <option class="option" value="">Answer 1</option>
                <option class="option" value="">Answer 2</option>
                <option class="option" value="">Answer 3</option>
                <option class="option" value="">Answer 4</option>
            </select> <br>
            <select name="" id="">
                <option class="option" value="">Do you offer certificates upon completion?</option>
                <option class="option" value="">Answer 1</option>
                <option class="option" value="">Answer 2</option>
                <option class="option" value="">Answer 3</option>
                <option class="option" value="">Answer 4</option>
            </select> <br>
            <select name="" id="">
                <option class="option" value="">Do you offer certificates upon completion?</option>
                <option class="option" value="">Answer 1</option>
                <option class="option" value="">Answer 2</option>
                <option class="option" value="">Answer 3</option>
                <option class="option" value="">Answer 4</option>
            </select>
        </div>

    </div>

@endsection
