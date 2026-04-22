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

/* ================= BASE ================= */
*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:'Poppins',sans-serif;
}

body{
background:#0b0b0b;
color:#fff;
line-height:1.6;
}

/* ================= HEADER ================= */
header{
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

.logo{
font-family:'Playfair Display';
color:#D6C29C;
font-size:22px;
font-weight:700;
}

nav a{
color:#fff;
text-decoration:none;
margin-left:15px;
}

nav a:hover{
color:#D6C29C;
}

/* ================= TITLES ================= */
h1{
text-align:center;
margin:30px 0;
font-family:'Playfair Display';
color:#D6C29C;
}

.section-title{
padding:0 8%;
color:#D6C29C;
font-size:20px;
margin-top:40px;
}

/* ================= SERVICES (VERTICAL RECTANGLE) ================= */
.services-stack{
padding:20px 8%;
display:flex;
flex-direction:column;
gap:18px;
}

.service-card{
background:#161616;
padding:22px;
border-radius:14px;
border:1px solid rgba(214,194,156,.15);
transition:.3s;
}

.service-card:hover{
transform:translateY(-4px);
border-color:#D6C29C;
}

.service-card h3{
color:#D6C29C;
margin-bottom:8px;
font-family:'Playfair Display';
}

.service-card .desc{
color:#ccc;
font-size:13px;
margin-bottom:10px;
line-height:1.6;
}

.service-card .time{
color:#aaa;
font-size:12px;
margin-bottom:6px;
}

.service-card .price{
color:#D6C29C;
font-weight:700;
margin-bottom:10px;
}

.service-card .btn{
display:inline-block;
padding:8px 14px;
background:#D6C29C;
color:#111;
border-radius:8px;
text-decoration:none;
font-size:13px;
font-weight:600;
}

/* ================= PACKAGES (KEEP ORIGINAL LOOK) ================= */
.package-grid{
display:grid;
grid-template-columns:repeat(auto-fit,minmax(280px,1fr));
gap:25px;
padding:20px 8%;
}

.package-card{
background:linear-gradient(145deg,#111,#0c0c0c);
padding:28px;
border-radius:18px;
border:1px solid rgba(255,255,255,0.06);
text-align:left;
position:relative;
overflow:hidden;
}

.package-card h3{
margin-bottom:15px;
font-size:22px;
font-family:'Playfair Display';
}

.package-card .desc{
color:#ccc;
font-size:14px;
line-height:1.6;
}

.package-card .price{
display:block;
margin-top:15px;
font-size:24px;
font-weight:700;
color:#D6C29C;
}

/* colors */
.package-card.bronze{border-left:5px solid #cd7f32;}
.package-card.silver{border-left:5px solid #c0c0c0;}
.package-card.gold{border-left:5px solid #D6C29C;}

/* ================= ADD ONS (BELOW PACKAGES) ================= */
.addons-stack{
padding:20px 8%;
display:grid;
grid-template-columns:repeat(auto-fit,minmax(260px,1fr));
gap:18px;
}

.addon-card{
background:#141414;
padding:18px;
border-radius:12px;
border:1px solid rgba(255,255,255,0.08);
}

.addon-card h4{
color:#D6C29C;
margin-bottom:6px;
font-family:'Playfair Display';
}

.addon-card p{
color:#aaa;
font-size:13px;
margin-bottom:8px;
}

.addon-card span{
color:#fff;
font-weight:700;
}

</style>
</head>

<body>

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

<div class="services-stack">

<div class="service-card">
<h3>Swedish Massage</h3>
<p class="desc">A gentle, soothing massage using light to moderate pressure, designed to melt away stress and promote deep relaxation.</p>
<div class="time">1hr • 1.5hr • 2hr</div>
<div class="price">₱600 - ₱1,150</div>
<a href="booking-guest.php" class="btn">Book</a>
</div>

<div class="service-card">
<h3>Mizpah Signature</h3>
<p class="desc">Our most requested service. An exclusive blend of Swedish, Shiatsu, deep tissue, stretching, and facial massage — adaptable from light to firm pressure. Includes a specialized tool to release muscle tension. Perfect if you have specific areas of concern.</p>
<div class="time">1hr • 1.5hr • 2hr</div>
<div class="price">₱750 - ₱1,450</div>
<a href="booking-guest.php" class="btn">Book</a>
</div>

<div class="service-card">
<h3>Thai Massage</h3>
<p class="desc">Performed dry or with oil, this therapeutic massage combines firm finger pressure and guided stretching to improve flexibility and reduce muscle tightness.</p>
<div class="time">1hr • 1.5hr • 2hr</div>
<div class="price">₱650 - ₱1,250</div>
<a href="booking-guest.php" class="btn">Book</a>
</div>

<div class="service-card">
<h3>Shiatsu Dry Massage</h3>
<p class="desc">Performed dry or with oil, this therapeutic massage combines firm finger pressure and guided stretching to improve flexibility and reduce muscle tightness.</p>
<div class="time">1hr • 1.5hr • 2hr</div>
<div class="price">₱650 - ₱1,250</div>
<a href="booking-guest.php" class="btn">Book</a>
</div>

<div class="service-card">
<h3>Lymphatic Massage</h3>
<p class="desc">A firm, results-driven massage using a specialized tool and technique to stimulate lymphatic drainage, reduce bloating, and flush excess water from the body.</p>
<div class="time">1hr • 1.5hr • 2hr</div>
<div class="price">₱850 - ₱1,650</div>
<a href="booking-guest.php" class="btn">Book</a>
</div>

<div class="service-card">
<h3>Prenatal / Postpartum</h3>
<p class="desc">A safe, specialized therapy designed for expectant and new mothers to ease muscle tension, reduce stress, and support recovery during and after pregnancy.</p>
<div class="time">1hr • 1.5hr • 2hr</div>
<div class="price">₱850 - ₱1,650</div>
<a href="booking-guest.php" class="btn">Book</a>
</div>

</div>

<!-- PACKAGES (UNCHANGED LOOK AREA) -->
<div class="section-title">Mizpah Packages</div>

<div class="package-grid">

    <div class="package-card bronze">
        <h3>Bronze Package</h3>
        <div class="desc">
            Swedish Massage • Body Scrub • Hot Stone Therapy • Milk Mask • Korean Face Mask • Foot Mask
            <br><br>
            <small>
                A relaxing starter package designed to relieve muscle tension, gently exfoliate the skin,
                and refresh your face and feet for a full-body light rejuvenation experience.
            </small>
        </div>
        <span class="price">₱1,600</span>
    </div>

    <div class="package-card silver">
        <h3>Silver Package</h3>
        <div class="desc">
            Mizpah Signature Massage • Body Scrub • Hot Stone Therapy • Milk Mask • Korean Face Mask • Foot Mask
            <br><br>
            <small>
                A more enhanced spa experience combining deeper therapeutic massage techniques with full-body
                exfoliation and skin nourishment for better relaxation and glow.
            </small>
        </div>
        <span class="price">₱1,800</span>
    </div>

    <div class="package-card gold">
        <h3>Gold Package</h3>
        <div class="desc">
            Mizpah Signature Massage • Body Scrub • Hot Stone Therapy • Head or Foot Massage • Milk Mask • Korean Face Mask • Foot Mask
            <br><br>
            <small>
                Our premium full spa experience with complete body care, stress relief focus, and deep relaxation
                from head to toe. Perfect for total rejuvenation and luxury pampering.
            </small>
        </div>
        <span class="price">₱2,000</span>
    </div>

</div>

<!-- ADD ONS (BELOW PACKAGES) -->
<div class="section-title">Enhance Your Session (Add-ons)</div>

<div class="addons-stack">

<div class="addon-card">
<h4>Premium Body Scrub</h4>
<p>Milk mask + Korean mask + exfoliation</p>
<span>₱750</span>
</div>

<div class="addon-card">
<h4>Hot Stone</h4>
<p>Deep muscle heat relaxation therapy</p>
<span>₱300</span>
</div>

<div class="addon-card">
<h4>Ventosa Cupping</h4>
<p>Improves circulation & detox</p>
<span>₱350</span>
</div>

<div class="addon-card">
<h4>Foot Massage</h4>
<p>Reflexology 30 minutes</p>
<span>₱350</span>
</div>

<div class="addon-card">
<h4>Head & Shoulder</h4>
<p>Upper body tension relief</p>
<span>₱350</span>
</div>

<div class="addon-card">
<h4>Kiddie Massage</h4>
<p>Gentle massage for kids</p>
<span>₱400</span>
</div>

</div>

</body>
</html>