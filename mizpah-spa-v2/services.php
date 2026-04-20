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

body{
margin:0;
font-family:Poppins;
background:#0b0b0b;
color:#fff;
}

/* HEADER */
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

nav a:hover{color:#D6C29C;}

/* TITLE */
h1{
text-align:center;
margin:35px 0 10px;
font-family:'Playfair Display';
color:#D6C29C;
}

/* GRID */
.grid{
display:grid;
grid-template-columns:repeat(auto-fit,minmax(260px,1fr));
gap:18px;
padding:30px 8%;
}

.card{
background:#161616;
padding:18px;
border-radius:14px;
border:1px solid rgba(214,194,156,.15);
cursor:pointer;
transition:.25s;
}

.card:hover{
transform:translateY(-5px);
border-color:#D6C29C;
}

.card h3{
margin:0;
color:#D6C29C;
}

.card p{
font-size:13px;
color:#ccc;
line-height:1.5;
}

.price{
margin-top:10px;
font-weight:700;
color:#D6C29C;
}

/* BUTTON */
.btn{
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

/* MODAL */
.modal{
display:none;
position:fixed;
inset:0;
background:rgba(0,0,0,.75);
justify-content:center;
align-items:center;
z-index:999;
}

.modal-content{
background:#161616;
padding:25px;
width:420px;
border-radius:14px;
border:1px solid rgba(214,194,156,.2);
position:relative;
animation:fade .2s ease;
}

@keyframes fade{
from{transform:scale(.9);opacity:0}
to{transform:scale(1);opacity:1}
}

.close{
position:absolute;
top:10px;
right:15px;
cursor:pointer;
font-size:18px;
color:#D6C29C;
}

.modal h2{
font-family:'Playfair Display';
color:#D6C29C;
margin-bottom:10px;
}

.modal p{
font-size:13px;
color:#ccc;
line-height:1.6;
}

.badge{
display:inline-block;
margin-top:8px;
padding:4px 10px;
font-size:12px;
border-radius:20px;
background:rgba(214,194,156,.15);
color:#D6C29C;
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

<div class="grid">

<!-- SERVICE CARDS -->
<div class="card" onclick="openModal('Swedish Massage','Relaxing full body massage designed to reduce stress and improve circulation.','₱600 - ₱1,150')">
<h3>Swedish Massage</h3>
<p>Gentle relaxation therapy for stress relief.</p>
<div class="price">Starts ₱600</div>
<a class="btn">View Details</a>
</div>

<div class="card" onclick="openModal('Mizpah Signature','Premium blend of Swedish, Shiatsu, stretching and deep therapy for full body restoration.','₱750 - ₱1,450')">
<h3>Mizpah Signature</h3>
<p>Our best-selling luxury treatment.</p>
<div class="price">Starts ₱750</div>
<a class="btn">View Details</a>
</div>

<div class="card" onclick="openModal('Thai Massage','Traditional Thai stretching + pressure points for flexibility and pain relief.','₱650 - ₱1,250')">
<h3>Thai Massage</h3>
<p>Stretch & energy balance therapy.</p>
<div class="price">Starts ₱650</div>
<a class="btn">View Details</a>
</div>

<div class="card" onclick="openModal('Shiatsu Massage','Japanese pressure point therapy that improves energy flow and reduces tension.','₱650 - ₱1,250')">
<h3>Shiatsu Massage</h3>
<p>Deep pressure energy healing.</p>
<div class="price">Starts ₱650</div>
<a class="btn">View Details</a>
</div>

<div class="card" onclick="openModal('Lymphatic Massage','Detox massage that helps reduce swelling and improve circulation.','₱850 - ₱1,650')">
<h3>Lymphatic Massage</h3>
<p>Body detox & fluid drainage.</p>
<div class="price">Starts ₱850</div>
<a class="btn">View Details</a>
</div>

<div class="card" onclick="openModal('Prenatal / Postnatal','Safe massage for expecting and new mothers for relaxation and recovery.','₱850 - ₱1,650')">
<h3>Prenatal / Postnatal</h3>
<p>Safe mother wellness care.</p>
<div class="price">Starts ₱850</div>
<a class="btn">View Details</a>
</div>

<!-- PACKAGES -->
<div class="card" onclick="openModal('Bronze Package','Swedish + Scrub + Hot Stone + Face Mask for quick luxury relaxation.','₱1,600 (1h45)')">
<h3>🥉 Bronze Package</h3>
<p>Quick luxury experience.</p>
<div class="price">₱1,600</div>
<a class="btn">View Details</a>
</div>

<div class="card" onclick="openModal('Silver Package','Signature full spa experience with complete relaxation therapy set.','₱1,800 (1h45)')">
<h3>🥈 Silver Package</h3>
<p>Best value premium set.</p>
<div class="price">₱1,800</div>
<a class="btn">View Details</a>
</div>

<div class="card" onclick="openModal('Gold Package','Full luxury spa treatment with extended pampering session.','₱2,000 (2hrs)')">
<h3>🥇 Gold Package</h3>
<p>Ultimate spa experience.</p>
<div class="price">₱2,000</div>
<a class="btn">View Details</a>
</div>

</div>

<!-- MODAL -->
<div class="modal" id="modal">

<div class="modal-content">

<span class="close" onclick="closeModal()">&times;</span>

<h2 id="mTitle"></h2>

<span class="badge">Mizpah Wellness Spa</span>

<p id="mDesc"></p>

<p><strong style="color:#D6C29C;">Price:</strong> <span id="mPrice"></span></p>

<a href="booking-guest.php" class="btn">Book This</a>

</div>

</div>

<script>

function openModal(title,desc,price){
document.getElementById('modal').style.display='flex';
document.getElementById('mTitle').innerText=title;
document.getElementById('mDesc').innerText=desc;
document.getElementById('mPrice').innerText=price;
}

function closeModal(){
document.getElementById('modal').style.display='none';
}

window.onclick=function(e){
if(e.target.id==='modal'){
closeModal();
}
}

</script>

</body>
</html>