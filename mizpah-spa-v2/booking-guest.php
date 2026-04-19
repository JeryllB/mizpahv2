<?php
session_start();
include 'includes/db.php';

/* ================= BOOKING ================= */
if(isset($_POST['submit_booking'])){

$name    = mysqli_real_escape_string($conn,$_POST['customer_name']);
$phone   = mysqli_real_escape_string($conn,$_POST['phone']);
$service = mysqli_real_escape_string($conn,$_POST['service']);
$duration= mysqli_real_escape_string($conn,$_POST['duration']);
$price   = mysqli_real_escape_string($conn,$_POST['price']);
$date    = mysqli_real_escape_string($conn,$_POST['booking_date']);
$time    = mysqli_real_escape_string($conn,$_POST['booking_time']);
$pax     = mysqli_real_escape_string($conn,$_POST['pax']);
$notes   = mysqli_real_escape_string($conn,$_POST['notes']);

/* 6 BED LIMIT */
$check = mysqli_query($conn,"
SELECT COUNT(*) as total 
FROM bookings 
WHERE booking_date='$date' AND booking_time='$time'
");

$row = mysqli_fetch_assoc($check);

if($row['total'] >= 6){
echo "<script>alert('Fully booked (6 beds taken)!');window.history.back();</script>";
exit;
}

/* INSERT */
mysqli_query($conn,"INSERT INTO bookings
(customer_name,phone,service,duration,price,booking_date,booking_time,pax,notes,status)
VALUES
('$name','$phone','$service','$duration','$price','$date','$time','$pax','$notes','Pending')");

echo "<script>alert('Booking Successful!');window.location='booking-guest.php';</script>";
exit;
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Mizpah Wellness Spa</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&family=Playfair+Display:wght@500;700&display=swap" rel="stylesheet">

<style>

:root{
--bg:#0b0b0b;
--card:#161616;
--gold:#D6C29C;
--border:rgba(214,194,156,.2);
}

*{margin:0;padding:0;box-sizing:border-box;font-family:Poppins;}

body{background:var(--bg);color:#fff;}

header{
display:flex;
justify-content:space-between;
padding:18px 8%;
background:#111;
border-bottom:1px solid var(--border);
position:sticky;
top:0;
z-index:10;
}

.logo{
font-family:'Playfair Display';
color:var(--gold);
font-size:22px;
font-weight:700;
}

nav a{color:#fff;margin-left:15px;text-decoration:none;}
nav a:hover{color:var(--gold);}

.hero{
text-align:center;
padding:40px 8%;
}

.hero h1{
font-family:'Playfair Display';
color:var(--gold);
font-size:40px;
}

.section{padding:25px 8%;}

.title{
font-family:'Playfair Display';
color:var(--gold);
font-size:22px;
margin-bottom:15px;
}

.grid{
display:grid;
grid-template-columns:repeat(auto-fit,minmax(240px,1fr));
gap:15px;
}

.card{
background:var(--card);
padding:18px;
border-radius:12px;
border:1px solid var(--border);
cursor:pointer;
transition:.25s;
}

.card:hover{
transform:translateY(-4px);
border-color:var(--gold);
}

.desc{font-size:13px;color:#cfcfcf;margin-top:6px;}
.price{color:var(--gold);margin-top:8px;font-weight:700;}

.form-box{
background:#141414;
padding:25px;
border-radius:14px;
max-width:900px;
margin:auto;
border:1px solid var(--border);
}

label{
font-size:13px;
color:var(--gold);
display:block;
margin-top:12px;
}

input,select,textarea{
width:100%;
padding:10px;
margin-top:6px;
border-radius:8px;
border:1px solid #2a2a2a;
background:#0e0e0e;
color:#fff;
}

.row{
display:grid;
grid-template-columns:1fr 1fr;
gap:12px;
}

button{
width:100%;
padding:14px;
background:var(--gold);
border:none;
border-radius:10px;
font-weight:700;
margin-top:15px;
cursor:pointer;
}

button:hover{opacity:.9;}

</style>
</head>

<body>

<header>
<div class="logo">Mizpah Wellness Spa</div>
<nav>
<a href="index.php">Home</a>
<a href="login.php">Login</a>
</nav>
</header>

<div class="hero">
<h1>Book Your Relaxation</h1>
</div>

<div class="section">
<div class="title">Services</div>

<div class="grid">

<div class="card" onclick="setService('Swedish Massage',600,850,1150)">
Swedish Massage
<div class="desc">Relaxing full body massage</div>
<div class="price">Starts at ₱600</div>
</div>

<div class="card" onclick="setService('Mizpah Signature',750,1100,1450)">
Mizpah Signature
<div class="desc">Premium spa blend therapy</div>
<div class="price">Starts at ₱750</div>
</div>

<div class="card" onclick="setService('Thai Massage',650,950,1250)">
Thai Massage
<div class="desc">Stretch + pressure therapy</div>
<div class="price">Starts at ₱650</div>
</div>

<div class="card" onclick="setService('Shiatsu Massage',650,950,1250)">
Shiatsu Massage
<div class="desc">Energy flow therapy</div>
<div class="price">Starts at ₱650</div>
</div>

<div class="card" onclick="setService('Lymphatic Massage',850,1250,1650)">
Lymphatic Massage
<div class="desc">Detox & fluid drainage</div>
<div class="price">Starts at ₱850</div>
</div>

<div class="card" onclick="setService('Prenatal Massage',850,1250,1650)">
Prenatal / Postnatal
<div class="desc">Safe pregnancy massage</div>
<div class="price">Starts at ₱850</div>
</div>

</div>
</div>

<div class="section">
<div class="title">Mizpah Packages</div>

<div class="grid">

<div class="card" onclick="setService('Bronze Package',1600,1600,1600)">
🥉 Bronze Package
<div class="desc">Swedish + Scrub + Hot Stone</div>
<div class="price">₱1,600 • 1h45</div>
</div>

<div class="card" onclick="setService('Silver Package',1800,1800,1800)">
🥈 Silver Package
<div class="desc">Signature full spa experience</div>
<div class="price">₱1,800 • 1h45</div>
</div>

<div class="card" onclick="setService('Gold Package',2000,2000,2000)">
🥇 Gold Package
<div class="desc">Full luxury spa experience</div>
<div class="price">₱2,000 • 2hrs</div>
</div>

</div>
</div>

<div class="section">
<div class="title">Add-ons</div>

<div class="grid">

<div class="card">
<div class="desc">Hot Stone Therapy</div>
<div class="price">+₱300</div>
</div>

<div class="card">
<div class="desc">Foot Reflex Massage</div>
<div class="price">+₱350</div>
</div>

<div class="card">
<div class="desc">Head & Shoulder Relief</div>
<div class="price">+₱350</div>
</div>

<div class="card">
<div class="desc">Premium Body Scrub</div>
<div class="price">+₱750</div>
</div>

</div>
</div>

<div class="section">
<div class="title">Booking Details</div>

<div class="form-box">

<form method="POST">

<label>Service</label>
<input id="service" name="service" readonly required>

<label>Duration</label>
<select id="duration" onchange="calcPrice()" required>
<option value="">Select</option>
<option value="1 Hour">1 Hour</option>
<option value="1.5 Hours">1.5 Hours</option>
<option value="2 Hours">2 Hours</option>
</select>

<input type="hidden" name="duration" id="durationText">
<input type="hidden" name="price" id="price">

<div id="priceBox" style="color:#D6C29C;margin-top:5px;"></div>

<div class="row">
<input name="customer_name" placeholder="Name" required>
<input name="phone" placeholder="Phone" required>
</div>

<div class="row">
<input type="date" name="booking_date" required>

<select name="booking_time">
<option>3:00 PM</option>
<option>4:00 PM</option>
<option>5:00 PM</option>
<option>6:00 PM</option>
<option>7:00 PM</option>
<option>8:00 PM</option>
<option>9:00 PM</option>
</select>
</div>

<select name="pax">
<option>1</option><option>2</option>
<option>3</option><option>4</option>
<option>5</option><option>6</option>
</select>

<textarea name="notes" placeholder="Notes"></textarea>

<button name="submit_booking">Confirm Booking</button>

</form>

</div>
</div>

<script>

let prices = {};

function setService(name,p1,p2,p3){
document.getElementById("service").value = name;
prices = {
"1 Hour":p1,
"1.5 Hours":p2,
"2 Hours":p3
};
document.getElementById("priceBox").innerText="";
document.getElementById("price").value="";
document.getElementById("duration").value="";
}

function calcPrice(){
let d = document.getElementById("duration").value;
let p = prices[d] || 0;

document.getElementById("price").value = p;
document.getElementById("durationText").value = d;

document.getElementById("priceBox").innerText =
"Estimated Price: ₱" + p;
}

</script>

</body>
</html>