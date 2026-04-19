<?php
session_start();
include 'includes/db.php';

if(isset($_POST['submit_booking'])){

$name    = mysqli_real_escape_string($conn,$_POST['customer_name']);
$phone   = mysqli_real_escape_string($conn,$_POST['phone']);
$service = mysqli_real_escape_string($conn,$_POST['service']);
$duration= mysqli_real_escape_string($conn,$_POST['duration']);
$date    = mysqli_real_escape_string($conn,$_POST['booking_date']);
$time    = mysqli_real_escape_string($conn,$_POST['booking_time']);
$pax     = mysqli_real_escape_string($conn,$_POST['pax']);
$notes   = mysqli_real_escape_string($conn,$_POST['notes']);

/* 6 BEDS CHECK */
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
(customer_name,phone,service,booking_date,booking_time,pax,notes,status)
VALUES
('$name','$phone','$service - $duration','$date','$time','$pax','$notes','Pending')");

echo "<script>alert('Booking Successful!');window.location='booking-guest.php';</script>";
exit;
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Mizpah Booking</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

<style>
body{margin:0;font-family:Poppins;background:#0b0b0b;color:#fff;}
header{display:flex;justify-content:space-between;padding:18px 8%;background:#111;border-bottom:1px solid #333;}
.logo{color:#D6C29C;font-weight:700;font-size:22px;}
nav a{color:#fff;margin-left:15px;text-decoration:none;}
nav a:hover{color:#D6C29C;}

.hero{text-align:center;padding:40px;}
.hero h1{color:#D6C29C;}

.section{padding:20px 8%;}
.title{color:#D6C29C;font-size:22px;margin-bottom:10px;}

.grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(230px,1fr));gap:12px;}

.card{
background:#141414;
padding:15px;
border-radius:12px;
border:1px solid #222;
cursor:pointer;
}

.card:hover{border-color:#D6C29C;}

.price{color:#D6C29C;margin-top:6px;}

.desc{font-size:12px;color:#ccc;margin-top:5px;}

.form-box{
background:#141414;
padding:20px;
border-radius:12px;
max-width:900px;
margin:auto;
border:1px solid #222;
}

input,select,textarea{
width:100%;
padding:10px;
margin-top:6px;
background:#0e0e0e;
border:1px solid #333;
color:#fff;
border-radius:8px;
}

label{color:#D6C29C;font-size:13px;}

.row{display:grid;grid-template-columns:1fr 1fr;gap:10px;}

button{
width:100%;
padding:14px;
background:#D6C29C;
border:none;
font-weight:700;
border-radius:10px;
margin-top:10px;
}

.addons label{display:block;margin:5px 0;font-size:13px;}
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

<!-- SERVICES -->
<!-- SERVICES -->
<div class="section">
<div class="title">Choose Your Service</div>

<div class="grid">

<div class="card" onclick="setService('Swedish Massage')">
Swedish Massage
<div class="desc">Gentle, soothing massage for stress relief and relaxation.</div>
</div>

<div class="card" onclick="setService('Mizpah Signature')">
Mizpah Signature
<div class="desc">Blend of Swedish, Shiatsu, deep tissue & stretching.</div>
</div>

<div class="card" onclick="setService('Thai Massage')">
Thai Massage
<div class="desc">Stretching + pressure therapy for flexibility.</div>
</div>

<div class="card" onclick="setService('Shiatsu Dry Massage')">
Shiatsu Dry Massage
<div class="desc">Rhythmic finger pressure for energy flow & relief.</div>
</div>

<div class="card" onclick="setService('Lymphatic Massage')">
Lymphatic Massage
<div class="desc">Detox massage for swelling & fluid retention.</div>
</div>

<div class="card" onclick="setService('Prenatal / Postnatal Massage')">
Prenatal / Postnatal
<div class="desc">Safe massage for pregnancy & recovery.</div>
</div>

</div>
</div>

<!-- PACKAGES (RESTORED) -->
<div class="section">
<div class="title">MIZPAH Packages</div>

<div class="grid">

<div class="card" onclick="setService('Bronze Package')">
🥉 Bronze Package
<div class="desc">Swedish + Scrub + Hot Stone + Masks</div>
<div class="price">₱1,600</div>
</div>

<div class="card" onclick="setService('Silver Package')">
🥈 Silver Package
<div class="desc">Signature + Scrub + Hot Stone + Masks</div>
<div class="price">₱1,800</div>
</div>

<div class="card" onclick="setService('Gold Package')">
🥇 Gold Package
<div class="desc">Full premium experience</div>
<div class="price">₱2,000</div>
</div>

</div>
</div>

<!-- DURATION -->
<div class="section">
<div class="title">Choose Duration</div>

<div class="grid">

<div class="card" onclick="setDuration('1 Hour',600)">1 Hour</div>
<div class="card" onclick="setDuration('1.5 Hours',850)">1.5 Hours</div>
<div class="card" onclick="setDuration('2 Hours',1150)">2 Hours</div>

</div>
</div>

<!-- ADD ONS -->
<div class="section">
<div class="title">Add-ons</div>

<div class="addons">
<label><input type="checkbox" value="300" class="addon"> Hot Stone (+₱300)</label>
<label><input type="checkbox" value="350" class="addon"> Foot Massage (+₱350)</label>
<label><input type="checkbox" value="350" class="addon"> Head & Shoulder (+₱350)</label>
<label><input type="checkbox" value="750" class="addon"> Body Scrub (+₱750)</label>
</div>
</div>

<!-- FORM -->
<div class="section">
<div class="title">Booking Form</div>

<div class="form-box">

<form method="POST">

<label>Service</label>
<input name="service" id="service" readonly required>

<label>Duration</label>
<input name="duration" id="duration" readonly required>

<div class="row">
<div>
<label>Name</label>
<input name="customer_name" required>
</div>
<div>
<label>Phone</label>
<input name="phone" required>
</div>
</div>

<div class="row">
<div>
<label>Date</label>
<input type="date" name="booking_date" required>
</div>

<div>
<label>Time</label>
<select name="booking_time" required>
<option>3:00 PM</option>
<option>4:00 PM</option>
<option>5:00 PM</option>
<option>6:00 PM</option>
<option>7:00 PM</option>
<option>8:00 PM</option>
<option>9:00 PM</option>
<option>10:00 PM</option>
<option>11:00 PM</option>
<option>12:00 AM</option>
<option>1:00 AM</option>
<option>2:00 AM</option>
</select>
</div>
</div>

<label>Pax</label>
<select name="pax">
<option>1</option><option>2</option><option>3</option>
<option>4</option><option>5</option><option>6</option>
</select>

<label>Notes</label>
<textarea name="notes"></textarea>

<button name="submit_booking">Confirm Booking</button>

</form>

</div>
</div>

<script>
function setService(s){
document.getElementById("service").value = s;
}

function setDuration(d){
document.getElementById("duration").value = d;
}
</script>

</body>
</html>