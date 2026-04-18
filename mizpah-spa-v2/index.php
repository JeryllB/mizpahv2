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

<style>

/* ================= HEADER ================= */
.site-header{
position:fixed;
top:0;
left:0;
width:100%;
z-index:999;
padding:22px 8%;
display:flex;
justify-content:space-between;
align-items:center;
background:transparent;
transition:.35s;
border:none;
box-shadow:none;
}

.site-header.scrolled{
background:#111111;
box-shadow:0 8px 25px rgba(0,0,0,.35);
}

.site-header nav{
display:flex;
gap:28px;
align-items:center;
}

.site-header nav a{
color:#fff;
text-decoration:none;
font-weight:500;
font-size:15px;
}

/* ================= HERO ================= */
.hero{
min-height:100vh;
padding:150px 8% 80px;
display:flex;
align-items:center;
justify-content:center;
text-align:center;

/* BIGGER IMAGE */
background:url('assets/images/hero.jpg') center center / cover no-repeat;
}

.hero-content{
max-width:950px;
}

.hero-title-white{
font-size:58px;
color:#fff;
font-family:'Playfair Display', serif;
margin-bottom:8px;
}

.hero-title-gold{
font-size:48px;
color:#D6C29C;
font-family:'Playfair Display', serif;
margin-bottom:18px;
}

.hero-text{
color:#ffffff;
font-size:17px;
line-height:1.8;
max-width:780px;
margin:auto;
margin-bottom:28px;
}

.hero-buttons{
margin-bottom:28px;
}

/* ================= INLINE DETAILS ================= */
.hero-info{
display:grid;
grid-template-columns:1fr 1fr 1fr;
gap:30px;
align-items:center;
margin-top:10px;
}

.info-box{
text-align:center;
}

.info-box span{
display:block;
font-size:18px;
margin-bottom:6px;
color:#D6C29C;
}

.info-box p{
margin:0;
font-size:15px;
color:#fff;
white-space:nowrap;
}

/* ================= BUTTON ================= */
.btn-primary{
background:#D6C29C;
color:#111111;
padding:12px 22px;
border-radius:8px;
text-decoration:none;
font-weight:600;
display:inline-block;
}

/* ================= FOOTER ================= */
footer{
background:#0b0b0b;
padding:60px 8% 25px;
color:#fff;
}

.footer-grid{
display:grid;
grid-template-columns:1fr 1fr 1fr;
gap:30px;
margin-bottom:35px;
}

.footer-col.left{text-align:left;}
.footer-col.center{text-align:center;}
.footer-col.right{text-align:right;}

.footer-col h3,
.footer-col h4{
color:#D6C29C;
margin-bottom:12px;
font-family:'Playfair Display', serif;
}

.footer-col p,
.footer-col a{
color:#fff;
font-size:14px;
line-height:1.8;
text-decoration:none;
}

.footer-col a:hover{
color:#D6C29C;
}

.footer-bottom{
border-top:1px solid rgba(255,255,255,.08);
padding-top:20px;
display:flex;
justify-content:space-between;
flex-wrap:wrap;
gap:20px;
font-size:14px;
}

/* ================= MOBILE ================= */
@media(max-width:900px){

.site-header{
flex-direction:column;
gap:15px;
}

.site-header nav{
flex-wrap:wrap;
justify-content:center;
gap:14px;
}

.hero-title-white{
font-size:42px;
}

.hero-title-gold{
font-size:34px;
}

.hero-info{
grid-template-columns:1fr;
gap:18px;
}

.footer-grid{
grid-template-columns:1fr;
text-align:center;
}

.footer-col.left,
.footer-col.center,
.footer-col.right{
text-align:center;
}

.footer-bottom{
flex-direction:column;
text-align:center;
}

}

</style>

</head>

<body>

<!-- HEADER -->
<header class="site-header" id="header">

<div class="logo">Mizpah Wellness Spa</div>

<nav>
<a href="index.php">Home</a>
<a href="#promo">Promos</a>
<a href="#signature">Services</a>
<a href="#">Therapists</a>
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

<div class="hero-buttons">
<a href="login.php" class="btn-primary">Book Now</a>
</div>

<div class="hero-info">

<div class="info-box">
<span>☎</span>
<p>0936-995-0038</p>
</div>

<div class="info-box">
<span>◷</span>
<p>Mon–Fri 3PM–3AM · Sat–Sun 1PM–3AM</p>
</div>

<div class="info-box">
<span>⌂</span>
<p>Kawit, Cavite</p>
</div>

</div>

</div>

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
<p><a href="#">Therapists</a></p>
<p><a href="#">Virtual Tour</a></p>
</div>

<div class="footer-col right">
<h4>Contact Info</h4>
<p>0936-995-0038</p>
<p>Kawit, Cavite</p>
<p>Mon–Fri 3PM – 3AM</p>
<p>Sat–Sun 1PM – 3AM</p>
</div>

</div>

<div class="footer-bottom">

<div>
© 2026 Mizpah Wellness Spa. All rights reserved.
</div>

<div>
Privacy Policy &nbsp; | &nbsp; Terms of Service
</div>

</div>

</footer>


<script>
window.addEventListener("scroll",function(){

let header=document.getElementById("header");

if(window.scrollY > 80){
header.classList.add("scrolled");
}else{
header.classList.remove("scrolled");
}

});
</script>

</body>
</html>