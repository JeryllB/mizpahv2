<?php
session_start();
include 'includes/db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Mizpah Wellness Spa</title>

<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

<!-- HEADER -->
<header class="site-header">
    <div class="logo">Mizpah Wellness Spa</div>

    <nav>
        <a href="index.php">Home</a>
        <a href="services.php">Services</a>
        <a href="therapist.php">Therapists</a>
        <a href="#">Virtual Tour</a>
    </nav>

    <a href="login.php" class="btn-primary">Login</a>
</header>

<!-- HERO -->
<section class="hero">
    <div class="hero-content">

    <img src="assets/images/logo.png" alt="Mizpah Logo" class="hero-logo">

    <h1 class="hero-title-white">Exquisite Comfort</h1>
    <h2 class="hero-title-gold">Exceptional Care</h2>

    <p class="hero-text">
        Kawit's premier wellness sanctuary — where relaxation meets luxury experience.
    </p>

    <a href="booking-guest.php" class="btn-primary">Book Now</a>

    <div class="hero-info">
        <div class="info-box">☎ 0936-995-0038</div>
        <div class="info-box">🕒 1PM – 3AM</div>
        <div class="info-box">📍 Kawit, Cavite</div>
    </div>

</div>
</section>

<!-- SIGNATURE SERVICES (NO POPUP, DIRECT DETAILS) -->
<section class="section">

<h2>Mizpah Signature Services</h2>

<div class="service-grid">

    <div class="service-card">
        <h3>Swedish Massage</h3>
        <p class="desc">Relaxing full body massage using light to medium pressure.</p>
        <p class="time">1–2 hrs</p>
        <p class="price">₱600</p>
        <a href="booking-guest.php" class="btn-small">Book Now</a>
    </div>

    <div class="service-card featured">
        <div class="badge">Recommended</div>
        <h3>Mizpah Signature</h3>
        <p class="desc">Combination of Swedish, Shiatsu & deep tissue massage.</p>
        <p class="time">1–2 hrs</p>
        <p class="price">₱750</p>
        <a href="booking-guest.php" class="btn-small">Book Now</a>
    </div>

    <div class="service-card">
        <h3>Lymphatic Massage</h3>
        <p class="desc">Detox massage that improves circulation & reduces swelling.</p>
        <p class="time">1–2 hrs</p>
        <p class="price">₱850</p>
        <a href="booking-guest.php" class="btn-small">Book Now</a>
    </div>

</div>

</section>

<!-- PACKAGES -->
<section class="section">

<h2>Mizpah Packages</h2>

<div class="package-grid">

    <div class="package-card bronze">
        <h3>Bronze Package</h3>
        <ul class="package-list">
            <li>Swedish Massage</li>
            <li>Body Scrub</li>
            <li>Hot Stone</li>
            <li>Face Mask</li>
        </ul>
        <strong>₱1,600</strong>
    </div>

    <div class="package-card silver">
        <h3>Silver Package</h3>
        <ul class="package-list">
            <li>Mizpah Signature Massage</li>
            <li>Body Scrub</li>
            <li>Hot Stone</li>
            <li>Face Mask</li>
        </ul>
        <strong>₱1,800</strong>
    </div>

    <div class="package-card gold">
        <h3>Gold Package</h3>
        <ul class="package-list">
            <li>Mizpah Signature Massage</li>
            <li>Body Scrub</li>
            <li>Hot Stone</li>
            <li>Full Body Care</li>
        </ul>
        <strong>₱2,000</strong>
    </div>

</div>

</section>

<!-- POPULAR CHOICES -->
<section class="section">

<h2>Popular Choices</h2>
<p class="subtitle">Our Guests' Favourites</p>

<div class="popular-grid">

    <div class="popular-card">
        <span class="tag">Signature</span>
        <img src="assets/images/popular/signature.jpg" alt="Mizpah Signature">

        <h3>Mizpah Signature</h3>
        <p>Our exclusive blend for ultimate relaxation</p>

        <strong>₱750</strong>

        <a href="booking-guest.php" class="btn-small">Book Now</a>
    </div>

    <div class="popular-card">
        <span class="tag">Popular</span>
        <img src="assets/images/popular/hotstone.jpg" alt="Hot Stone Combo">

        <h3>Hot Stone Combo</h3>
        <p>Melt away tension with heated basalt stones</p>

        <strong>₱1,000</strong>

        <a href="booking-guest.php" class="btn-small">Book Now</a>
    </div>

    <div class="popular-card">
        <span class="tag">Add-On</span>
        <img src="assets/images/popular/quick.jpg" alt="Quick Escape">

        <h3>Quick Escape</h3>
        <p>30-min relief for busy schedules</p>

        <strong>₱350</strong>

        <a href="booking-guest.php" class="btn-small">Book Now</a>
    </div>

</div>

</section>

<!-- WHY CHOOSE -->
<section class="section why-choose">

    <h2>Why Choose Us</h2>

    <p class="subtitle">A Different Kind of Wellness</p>

    <p style="max-width:800px;margin:0 auto 30px;color:#aaa;">
        Every detail crafted with your comfort in mind.
    </p>

    <div class="why-grid">

        <div class="why-box">
            <h3>Extended Hours</h3>
            <p>Open late every day — Mon–Fri 3PM–3AM, Sat–Sun 1PM–3AM. We fit your schedule.</p>
        </div>

        <div class="why-box">
            <h3>Licensed Therapists</h3>
            <p>Every therapist is professionally certified with years of hands-on experience.</p>
        </div>

        <div class="why-box">
            <h3>Clean & Safe</h3>
            <p>Pristine facilities with strict sanitization protocols for your peace of mind.</p>
        </div>

        <div class="why-box">
            <h3>Affordable Luxury</h3>
            <p>Premium wellness experiences at prices that make self-care accessible to everyone.</p>
        </div>

    </div>

    <!-- PROMISE -->
    <div class="promise">

        <h3 style="color:#D6C29C;margin-bottom:5px;">Our Promise</h3>
        <p style="color:#aaa;margin-bottom:25px;">Your Comfort is Our Priority</p>

        <div class="promise-grid">

            <ul class="promise-list">
                <li>1. Every therapist is professionally trained and licensed.</li>
                <li>2. We maintain pristine facilities with strict hygiene protocols.</li>
                <li>3. Luxury spa experiences at prices that make self-care accessible.</li>
            </ul>

            <div class="stats">

                <div class="stat-box">
                    <strong>6+</strong>
                    <span>Massage types</span>
                </div>

                <div class="stat-box">
                    <strong>100%</strong>
                    <span>Licensed therapists</span>
                </div>

                <div class="stat-box">
                    <strong>5★</strong>
                    <span>Customer rating</span>
                </div>

                <div class="stat-box">
                    <strong>7</strong>
                    <span>Days open</span>
                </div>

            </div>

        </div>

    </div>

</section>

<!-- RATINGS / TESTIMONIALS -->
<section class="section ratings-section">

    <h2>Guest Ratings</h2>
    <p class="subtitle">What Our Clients Say</p>

    <div class="rating-summary">
        <div class="big-rating">4.9★</div>
        <p>Based on 250+ happy guests</p>
    </div>

    <div class="ratings-grid">

        <div class="rating-chat">
            <div class="chat-top">
                <div class="avatar">M</div>
                <div>
                    <h4>Maria G.</h4>
                    <small>2 days ago</small>
                </div>
            </div>

            <div class="stars">★★★★★</div>

            <p>
                Very relaxing ambiance and professional therapists.
                Highly recommended!
            </p>
        </div>

        <div class="rating-chat">
            <div class="chat-top">
                <div class="avatar">J</div>
                <div>
                    <h4>John R.</h4>
                    <small>Last week</small>
                </div>
            </div>

            <div class="stars">★★★★★</div>

            <p>
                The Mizpah Signature massage was amazing.
                Will definitely come back.
            </p>
        </div>

        <div class="rating-chat">
            <div class="chat-top">
                <div class="avatar">A</div>
                <div>
                    <h4>Angela T.</h4>
                    <small>This month</small>
                </div>
            </div>

            <div class="stars">★★★★★</div>

            <p>
                Clean place, friendly staff,
                and premium experience.
            </p>
        </div>

    </div>

</section>

<div class="ratings-section">

    <h2>Customer Reviews</h2>

    <div class="rating-summary">
        <div class="big-rating">4.8</div>
        <p>Based on customer feedback</p>
    </div>

    <div class="ratings-grid" id="ratingsBox">
        Loading reviews...
    </div>

    <hr style="margin:40px 0; border:1px solid #222">

    <!-- WRITE REVIEW FORM -->
    <div class="rating-form">
        <h3>Leave a Review</h3>

        <form action="submit_rating.php" method="POST">

            <input type="text" name="name" placeholder="Your Name" required>

            <select name="rating" required>
                <option value="">Rating</option>
                <option value="5">★★★★★</option>
                <option value="4">★★★★</option>
                <option value="3">★★★</option>
                <option value="2">★★</option>
                <option value="1">★</option>
            </select>

            <textarea name="message" placeholder="Your review..." required></textarea>

            <button type="submit">Submit Review</button>

        </form>
    </div>

</div>

<!-- CTA -->
<section class="section">
    <h2>Ready to Relax?</h2>
    <a href="booking-guest.php" class="btn-primary">Book Now</a>
</section>

<!-- FOOTER -->
<footer class="footer">
    <div class="footer-grid">

        <div>
            <h3>Mizpah Wellness Spa</h3>
            <p>Your sanctuary for relaxation.</p>
        </div>

        <div>
            <h4>Quick Links</h4>
            <p>Services</p>
            <p>Therapists</p>
            <p>Virtual Tour</p>
        </div>

        <div>
            <h4>Contact</h4>
            <p>0936-995-0038</p>
            <p>Kawit, Cavite</p>
        </div>

    </div>

    <div class="footer-bottom">
        <p>© 2026 Mizpah Wellness Spa</p>
    </div>
</footer>

<script>
window.addEventListener("scroll", function () {
    document.querySelector(".site-header")
    .classList.toggle("scrolled", window.scrollY > 50);
});
</script>

<script>
function loadRatings(){
    fetch("fetch_ratings.php")
    .then(res => res.text())
    .then(data => {
        document.getElementById("ratingsBox").innerHTML = data;
    });
}

loadRatings(); // initial load
setInterval(loadRatings, 3000); // refresh every 3 seconds
</script>

</body>
</html>