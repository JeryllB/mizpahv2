<?php
session_start();
include 'includes/db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Mizpah Services</title>

<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

<style>

/* ================= SAFE SCOPE ================= */
.services-page{
margin:0;
font-family:Poppins;
background:#0b0b0b;
color:#fff;
}

/* HEADER */
.services-page header{
padding:18px 8%;
display:flex;
justify-content:space-between;
align-items:center;
background:#111;
border-bottom:1px solid rgba(214,194,156,.2);
position:sticky;
top:0;
z-index:10;
}

.services-page .logo{
font-family:'Playfair Display';
color:#D6C29C;
font-size:22px;
font-weight:700;
}

.services-page nav a{
color:#fff;
text-decoration:none;
margin-left:15px;
}

.services-page nav a:hover{
color:#D6C29C;
}

.services-page h1{
text-align:center;
margin:30px 0;
font-family:'Playfair Display';
color:#D6C29C;
}

.services-page .section-title{
padding:0 8%;
color:#D6C29C;
font-size:20px;
margin-top:40px;
}

.services-page .grid{
display:grid;
grid-template-columns:repeat(auto-fit,minmax(280px,1fr));
gap:18px;
padding:20px 8%;
}

/* ================= SERVICE CARD ================= */
.services-page .card{
background:#161616;
padding:18px;
border-radius:14px;
border:1px solid rgba(214,194,156,.15);
transition:.3s;
}

.services-page .card:hover{
transform:translateY(-5px);
border-color:#D6C29C;
}

.services-page .card h3{
margin:0;
color:#D6C29C;
}

.services-page .desc{
font-size:13px;
color:#ccc;
line-height:1.6;
margin:10px 0;
}

.services-page .durations{
margin:10px 0;
font-size:13px;
color:#bbb;
}

.services-page .price{
color:#D6C29C;
font-weight:700;
margin-top:8px;
}

.services-page .btn{
display:inline-block;
margin-top:12px;
padding:10px 15px;
background:#D6C29C;
color:#111;
border-radius:8px;
text-decoration:none;
font-weight:600;
font-size:13px;
}

/* ================= ADD ONS ================= */
.services-page .add-on .desc{
font-size:12px;
}

/* ================= PACKAGE (SIDE ACCENT ONLY) ================= */
.services-page .package-card{
background:#161616;
padding:18px;
border-radius:14px;
border:1px solid rgba(255,255,255,0.08);
transition:.3s;
position:relative;
}

.services-page .package-card:hover{
transform:translateY(-5px);
border-color:#D6C29C;
}

/* LEFT SIDE COLOR ONLY */
.services-page .package-bronze{
border-left:5px solid #cd7f32;
}

.services-page .package-silver{
border-left:5px solid #c0c0c0;
}

.services-page .package-gold{
border-left:5px solid #D6C29C;
}

.services-page .package-card h3{
margin:0;
font-family:'Playfair Display';
}

.services-page .package-bronze h3{color:#cd7f32;}
.services-page .package-silver h3{color:#c0c0c0;}
.services-page .package-gold h3{color:#D6C29C;}

</style>
</head>

<body class="services-page">

<header>
<div class="logo">Mizpah Services</div>
<nav>
<a href="index.php">Home</a>
<a href="booking-guest.php">Book</a>
</nav>
</header>

<h1>Our Spa Services</h1>

<!-- SERVICES -->
<div class="section-title">Full Body Massage</div>

<div class="grid">

<div class="card">
<h3>Swedish Massage</h3>
<div class="desc">
Experience gentle to moderate pressure with long flowing strokes that relax the body, improve circulation, and reduce stress.
</div>
<div class="durations">1hr • 1.5hr • 2hr</div>
<div class="price">₱600 - ₱1,150</div>
<a href="booking-guest.php" class="btn">Book</a>
</div>

<div class="card">
<h3>Mizpah Signature</h3>
<div class="desc">
A premium blend of Swedish, Shiatsu, and aromatherapy designed for full body and mind rejuvenation.
</div>
<div class="durations">1hr • 1.5hr • 2hr</div>
<div class="price">₱750 - ₱1,450</div>
<a href="booking-guest.php" class="btn">Book</a>
</div>

<div class="card">
<h3>Thai Massage</h3>
<div class="desc">
Ancient healing technique combining stretching, acupressure, and assisted yoga for flexibility and pain relief.
</div>
<div class="durations">1hr • 1.5hr • 2hr</div>
<div class="price">₱650 - ₱1,250</div>
<a href="booking-guest.php" class="btn">Book</a>
</div>

<div class="card">
<h3>Shiatsu Dry Massage</h3>
<div class="desc">
Japanese pressure-point therapy performed without oil to restore energy balance and relieve deep muscle tension.
</div>
<div class="durations">1hr • 1.5hr • 2hr</div>
<div class="price">₱650 - ₱1,250</div>
<a href="booking-guest.php" class="btn">Book</a>
</div>

<div class="card">
<h3>Lymphatic Massage</h3>
<div class="desc">
Gentle detox massage that reduces swelling, improves circulation, and supports immune system health.
</div>
<div class="durations">1hr • 1.5hr • 2hr</div>
<div class="price">₱850 - ₱1,650</div>
<a href="booking-guest.php" class="btn">Book</a>
</div>

<div class="card">
<h3>Prenatal / Postpartum</h3>
<div class="desc">
Safe and gentle massage for pregnant and postnatal mothers to relieve body pain and promote recovery.
</div>
<div class="durations">1hr • 1.5hr • 2hr</div>
<div class="price">₱850 - ₱1,650</div>
<a href="booking-guest.php" class="btn">Book</a>
</div>

</div>

<!-- ADD ONS -->
<div class="section-title">Enhance Your Session (Add-ons)</div>

<div class="grid">

<div class="card">
<h3>Premium Body Scrub</h3>
<div class="desc">45 mins scrub + milk mask + Korean mask</div>
<div class="price">₱750</div>
</div>

<div class="card">
<h3>Hot Stone</h3>
<div class="desc">Heated stones for deep muscle relaxation</div>
<div class="price">₱300</div>
</div>

<div class="card">
<h3>Ventosa Cupping</h3>
<div class="desc">Improves blood flow & toxin release</div>
<div class="price">₱350</div>
</div>

<div class="card">
<h3>Foot Massage</h3>
<div class="desc">30 mins reflexology therapy</div>
<div class="price">₱350</div>
</div>

<div class="card">
<h3>Head & Shoulder</h3>
<div class="desc">Upper body tension relief</div>
<div class="price">₱350</div>
</div>

<div class="card">
<h3>Kiddie Massage</h3>
<div class="desc">Gentle massage for children</div>
<div class="price">₱400</div>
</div>

</div>

<!-- PACKAGES -->
<div class="section-title">Mizpah Packages</div>

<div class="grid">

<div class="package-card package-bronze">
<h3>Bronze Package</h3>
<div class="desc">
Swedish massage<br>
Body scrub<br>
Hot stone<br>
Milk mask<br>
Korean mask<br>
Foot mask
</div>
<div class="durations">1 hr 45 mins</div>
<div class="price">₱1,600</div>
</div>

<div class="package-card package-silver">
<h3>Silver Package</h3>
<div class="desc">
Mizpah Signature massage<br>
Body scrub<br>
Hot stone<br>
Milk mask<br>
Korean mask<br>
Foot mask
</div>
<div class="durations">1 hr 45 mins</div>
<div class="price">₱1,800</div>
</div>

<div class="package-card package-gold">
<h3>Gold Package</h3>
<div class="desc">
Signature massage<br>
Body scrub<br>
Hot stone<br>
Head or Foot massage<br>
Milk mask<br>
Korean mask<br>
Foot mask
</div>
<div class="durations">2 hrs</div>
<div class="price">₱2,000</div>
</div>

</div>

</body>
</html>