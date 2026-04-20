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

        <h1 class="hero-title-white">Exquisite Comfort</h1>
        <h2 class="hero-title-gold">Exceptional Care</h2>

        <p class="hero-text">
            Kawit's premier wellness sanctuary — where skilled hands and serene surroundings restore your body, mind, and spirit.
        </p>

        <a href="booking-guest.php" class="btn-primary">Book Now</a>

        <div class="hero-info">
            <div class="info-box">☎ 0936-995-0038</div>
            <div class="info-box">🕒 1PM – 3AM</div>
            <div class="info-box">📍 Kawit, Cavite</div>
        </div>

    </div>
</section>

<!-- SIGNATURE / SERVICES CARDS -->
<section class="section">

    <h2>Mizpah Signature Services</h2>

    <div class="service-grid">

        <div class="service-card">
            <h3>Swedish Massage</h3>
            <p class="time">1–2 hrs</p>
            <p class="price">From ₱600</p>
        </div>

        <div class="service-card featured">
            <div class="badge">Popular</div>
            <h3>MIZPAH Signature</h3>
            <p class="time">1–2 hrs</p>
            <p class="price">From ₱750</p>
        </div>

        <div class="service-card">
            <h3>Lymphatic Massage</h3>
            <p class="time">1–2 hrs</p>
            <p class="price">From ₱850</p>
        </div>

    </div>

</section>

<!-- PACKAGES -->
<section class="section">

    <h2>Mizpah Packages</h2>

    <div class="package-grid">

        <div class="package-card bronze">
            <h3>Bronze Package</h3>
            <p class="duration">1 Hour Massage</p>
            <p>Perfect for quick relaxation and stress relief.</p>
            <strong>₱1,600</strong>
        </div>

        <div class="package-card silver">
            <h3>Silver Package</h3>
            <p class="duration">1.5 Hours Relaxation</p>
            <p>Balanced massage for full body comfort.</p>
            <strong>₱1,800</strong>
        </div>

        <div class="package-card gold">
            <h3>Gold Package</h3>
            <p class="duration">2 Hours Premium Care</p>
            <p>Premium deep relaxation with luxury care.</p>
            <strong>₱2,000</strong>
        </div>

    </div>

</section>

<!-- WHY CHOOSE -->
<section class="section why-choose">

    <h2>Why Choose Us</h2>

    <p class="subtitle">A Different Kind of Wellness</p>
    <p>Every detail crafted with your comfort in mind.</p>

    <div class="why-grid">

        <div class="why-box">
            <h3>Extended Hours</h3>
            <p>Mon–Fri 3PM–3AM, Sat–Sun 1PM–3AM.</p>
        </div>

        <div class="why-box">
            <h3>Licensed Therapists</h3>
            <p>Professionally certified with experience.</p>
        </div>

        <div class="why-box">
            <h3>Clean & Safe</h3>
            <p>Strict sanitization protocols.</p>
        </div>

        <div class="why-box">
            <h3>Affordable Luxury</h3>
            <p>Premium spa at accessible prices.</p>
        </div>

    </div>

    <div class="promise">

        <h3>Our Promise</h3>
        <p class="promise-title">Your Comfort is Our Priority</p>

        <div class="promise-grid">

            <ul class="promise-list">
                <li>Every therapist is professionally trained and licensed.</li>
                <li>We maintain strict hygiene protocols.</li>
                <li>Luxury spa experience at affordable rates.</li>
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

<!-- TESTIMONIALS -->
<section class="section">

    <h2>What Clients Say</h2>

    <div class="card-container">

        <?php
        $testi = mysqli_query($conn,"SELECT * FROM testimonials WHERE featured=1 LIMIT 3");

        while($row = mysqli_fetch_assoc($testi)){
        ?>

        <div class="card">
            <p><?= str_repeat("⭐",$row['rating']) ?></p>
            <p>"<?= $row['message'] ?>"</p>
            <h4><?= $row['name'] ?></h4>
        </div>

        <?php } ?>

    </div>

</section>

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
            <p>Your sanctuary for relaxation and healing.</p>
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

<!-- SCROLL HEADER SCRIPT -->
<script>
window.addEventListener("scroll", function () {
    const header = document.querySelector(".site-header");

    if (window.scrollY > 50) {
        header.classList.add("scrolled");
    } else {
        header.classList.remove("scrolled");
    }
});
</script>

</body>
</html>