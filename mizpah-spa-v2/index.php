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

    <div class="header-left logo">
        Mizpah Wellness Spa
    </div>

    <nav class="header-center">
        <a href="#signature">Services</a>
        <a href="#therapists">Therapists</a>
        <a href="#testimonials">Reviews</a>
        <a href="#">Virtual Tour</a>
    </nav>

    <div class="header-right">
        <a href="login.php" class="btn-primary">Login</a>
    </div>

</header>

<!-- HERO -->
<section class="hero">

    <div class="hero-content">

        <h1 class="hero-title-white">Exquisite Comfort</h1>
        <h2 class="hero-title-gold">Exceptional Care</h2>

        <p class="hero-text">
            Kawit's premier wellness sanctuary — where skilled hands and serene surroundings
            restore your body, mind, and spirit.
        </p>

        <div style="margin-top:25px;">
            <a href="booking.php" class="btn-primary">Get Started</a>
        </div>

    </div>

</section>

<!-- SERVICES -->
<section id="signature">

<h2>Mizpah Signature Treatments</h2>

<div class="card-container">

<?php
$sig = mysqli_query($conn,"SELECT * FROM services WHERE service_name='Mizpah Signature'");

while($row = mysqli_fetch_assoc($sig)){
?>

<div class="card">
<h3><?= $row['service_name'] ?></h3>
<p><?= $row['duration'] ?></p>
<strong>₱<?= number_format($row['price'],2) ?></strong>
</div>

<?php } ?>

</div>

</section>

<!-- THERAPISTS -->
<section id="therapists">

<h2>Our Therapists</h2>

<div class="card-container">

<div class="card">
<h3>Licensed Therapist</h3>
<p>Relaxation & wellness expert</p>
</div>

<div class="card">
<h3>Senior Therapist</h3>
<p>Thai & deep tissue specialist</p>
</div>

</div>

</section>

<!-- TESTIMONIALS -->
<section id="testimonials">

<h2>What Clients Say</h2>

<div class="card-container">

<?php
$t = mysqli_query($conn,"SELECT * FROM testimonials WHERE featured=1 LIMIT 3");

while($row = mysqli_fetch_assoc($t)){
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
<section class="cta">

<h2>Ready to Relax?</h2>
<p>Book your appointment today</p>

<a href="booking.php" class="btn-primary">Get Started</a>

</section>

<!-- FOOTER -->
<footer>

<div class="footer-grid">

<div class="footer-col left">
<h3>Mizpah Wellness Spa</h3>
<p>Your sanctuary for relaxation and healing.</p>
</div>

<div class="footer-col center">
<h4>Quick Links</h4>
<p><a href="#signature">Services</a></p>
<p><a href="#therapists">Therapists</a></p>
<p><a href="#">Virtual Tour</a></p>
</div>

<div class="footer-col right">
<h4>Contact</h4>
<p>Kawit, Cavite</p>
<p>0936-995-0038</p>
</div>

</div>

<div class="footer-bottom">
<div>© 2026 Mizpah Wellness Spa</div>
<div>
<a href="#">Privacy Policy</a> | <a href="#">Terms of Service</a>
</div>
</div>

</footer>

</body>
</html>