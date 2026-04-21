<?php
session_start();
include 'includes/db.php';

/* =========================
   SUBMIT BOOKING
========================= */
if(isset($_POST['submit_booking'])){

$name     = mysqli_real_escape_string($conn,$_POST['customer_name']);
$phone    = mysqli_real_escape_string($conn,$_POST['phone']);
$service  = mysqli_real_escape_string($conn,$_POST['service']);
$duration = mysqli_real_escape_string($conn,$_POST['duration']);
$price    = mysqli_real_escape_string($conn,$_POST['price']);
$date     = mysqli_real_escape_string($conn,$_POST['booking_date']);
$time     = mysqli_real_escape_string($conn,$_POST['booking_time']);
$pax      = mysqli_real_escape_string($conn,$_POST['pax']);
$notes    = mysqli_real_escape_string($conn,$_POST['notes']);

$addons = isset($_POST['addons']) ? $_POST['addons'] : "";
if(is_array($addons)){
    $addons = implode(", ", $addons);
}

/* SLOT LIMIT */
$check = mysqli_query($conn,"
SELECT COUNT(*) as total 
FROM bookings 
WHERE booking_date='$date' AND booking_time='$time'
");

$row = mysqli_fetch_assoc($check);

if($row['total'] >= 6){
    echo "<script>alert('Fully booked na ang slot. Pili ka ibang time.');history.back();</script>";
    exit;
}

/* INSERT BOOKING */
mysqli_query($conn,"INSERT INTO bookings
(customer_name,phone,service,duration,price,booking_date,booking_time,pax,addons,notes,status)
VALUES
('$name','$phone','$service','$duration','$price','$date','$time','$pax','$addons','$notes','Pending')
");

/* SAVE SESSION FOR THANK YOU PAGE */
$_SESSION['last_booking'] = [
    'name' => $name,
    'service' => $service,
    'date' => $date,
    'time' => $time,
    'price' => $price
];

/* REDIRECT */
header("Location: thankyou.php");
exit;
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Guest Booking</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&family=Playfair+Display:wght@600&display=swap" rel="stylesheet">

<style>
body{margin:0;font-family:Poppins;background:#0b0b0b;color:#fff;}

header{
padding:18px 8%;
background:#111;
display:flex;
justify-content:space-between;
border-bottom:1px solid #333;
}

.logo{
font-family:'Playfair Display';
color:#D6C29C;
font-size:22px;
}

.section{padding:25px 8%;}
.title{color:#D6C29C;font-size:20px;margin-bottom:10px;}

.grid{
display:grid;
grid-template-columns:repeat(auto-fit,minmax(260px,1fr));
gap:12px;
}

.card{
background:#161616;
padding:15px;
border-radius:10px;
border:1px solid #333;
cursor:pointer;
transition:.2s;
}

.card:hover,.card.active{
border-color:#D6C29C;
transform:scale(1.02);
}

.desc{font-size:13px;color:#bbb;margin:6px 0;}
.price{color:#D6C29C;font-weight:600;}

.summary{
background:#161616;
padding:15px;
border-radius:8px;
border:1px solid #333;
margin-top:10px;
font-size:13px;
}

input,select,textarea{
width:100%;
padding:10px;
margin-top:5px;
background:#0d0d0d;
border:1px solid #333;
color:#fff;
border-radius:6px;
}

button{
background:#D6C29C;
border:none;
padding:12px;
width:100%;
margin-top:10px;
font-weight:600;
cursor:pointer;
}

.duration-btn{
display:inline-block;
margin:5px;
padding:6px 10px;
border:1px solid #444;
cursor:pointer;
font-size:12px;
}

.duration-btn.active{
background:#D6C29C;
color:#000;
}
</style>
</head>

<body>

<header>
<div class="logo">Mizpah Guest Booking</div>
</header>

<form method="POST">

<!-- SERVICES -->
<div class="section">
<div class="title">Services (Select Duration)</div>

<div class="grid">

<div class="card" onclick="selectService('Swedish Massage',600,850,1150)">
<b>Swedish Massage</b>
<div class="desc">Gentle, soothing massage using light pressure to relieve stress</div>
<div class="price">₱600 - ₱1150</div>
</div>

<div class="card" onclick="selectService('Mizpah Signature',750,1100,1450)">
<b>Mizpah Signature</b>
<div class="desc">Most requested premium therapeutic blend</div>
<div class="price">₱750 - ₱1450</div>
</div>

<div class="card" onclick="selectService('Thai Massage',650,950,1250)">
<b>Thai Massage</b>
<div class="desc">Stretching + pressure therapy</div>
<div class="price">₱650 - ₱1250</div>
</div>

<div class="card" onclick="selectService('Shiatsu Massage',650,950,1250)">
<b>Shiatsu Massage</b>
<div class="desc">Energy balancing pressure massage</div>
<div class="price">₱650 - ₱1250</div>
</div>

<div class="card" onclick="selectService('Lymphatic Massage',850,1250,1650)">
<b>Lymphatic Massage</b>
<div class="desc">Detox + circulation improvement</div>
<div class="price">₱850 - ₱1650</div>
</div>

</div>

<div id="durationBox"></div>
</div>

<!-- PACKAGES -->
<div class="section">
<div class="title">Mizpah Packages</div>

<div class="grid">

<div class="card">
<b>Bronze Package</b>
<div class="desc">Swedish + scrub + hot stone + masks + foot care</div>
<div class="price">₱1,600</div>
</div>

<div class="card">
<b>Silver Package</b>
<div class="desc">Signature + scrub + hot stone + masks</div>
<div class="price">₱1,800</div>
</div>

<div class="card">
<b>Gold Package</b>
<div class="desc">Full luxury spa experience</div>
<div class="price">₱2,000</div>
</div>

</div>
</div>

<!-- ADD ONS -->
<div class="section">
<div class="title">Add-ons</div>

<div class="grid">

<div class="card addon" onclick="toggleAddon(this,'Premium Body Scrub +500')">
<b>Premium Body Scrub</b>
<div class="desc">45 mins skin renewal</div>
<div class="price">₱500</div>
</div>

<div class="card addon" onclick="toggleAddon(this,'Hot Stone +300')">
<b>Hot Stone</b>
<div class="desc">Muscle relaxation therapy</div>
<div class="price">₱300</div>
</div>

<div class="card addon" onclick="toggleAddon(this,'Ventosa Cupping +350')">
<b>Ventosa Cupping</b>
<div class="desc">Improves blood flow</div>
<div class="price">₱350</div>
</div>

<div class="card addon" onclick="toggleAddon(this,'Foot Massage +300')">
<b>Foot Massage</b>
<div class="desc">Reflexology session</div>
<div class="price">₱300</div>
</div>

<div class="card addon" onclick="toggleAddon(this,'Head & Shoulder +300')">
<b>Head & Shoulder</b>
<div class="desc">Upper body relief</div>
<div class="price">₱300</div>
</div>

</div>

<input type="hidden" name="addons" id="addons">
</div>

<!-- BOOKING -->
<div class="section">
<div class="title">Booking Details</div>

<input type="hidden" name="service" id="service">
<input type="hidden" name="duration" id="duration">
<input type="hidden" name="price" id="price">

<label>Full Name</label>
<input name="customer_name" required>

<label>Phone</label>
<input name="phone" required>

<label>Date</label>
<input type="date" name="booking_date" required>

<label>Time</label>
<select name="booking_time" required>
<option>1:00 PM</option><option>2:00 PM</option>
<option>3:00 PM</option><option>4:00 PM</option>
<option>5:00 PM</option><option>6:00 PM</option>
<option>7:00 PM</option><option>8:00 PM</option>
<option>9:00 PM</option><option>10:00 PM</option>
<option>11:00 PM</option><option>12:00 AM</option>
</select>

<label>Pax</label>
<select name="pax">
<option>1</option><option>2</option>
<option>3</option><option>4</option>
<option>5</option><option>6</option>
</select>

<label>Notes</label>
<textarea name="notes"></textarea>

<div class="summary" id="summary">
Select service + duration
</div>

<button type="submit" name="submit_booking">CONFIRM BOOKING</button>

</div>

</form>

<script>
let selectedService="";

function selectService(name,p1,p2,p3){
selectedService=name;
document.getElementById("service").value=name;

document.getElementById("durationBox").innerHTML=`
<div class="title">Select Duration</div>
<div class="duration-btn" onclick="pick('1hr',${p1},this)">1 hr</div>
<div class="duration-btn" onclick="pick('1.5hr',${p2},this)">1.5 hr</div>
<div class="duration-btn" onclick="pick('2hr',${p3},this)">2 hr</div>
`;
}

function pick(d,p,el){
document.getElementById("duration").value=d;
document.getElementById("price").value=p;

document.querySelectorAll(".duration-btn").forEach(b=>b.classList.remove("active"));
el.classList.add("active");

document.getElementById("summary").innerHTML=
`<b>Service:</b> ${selectedService}<br>
<b>Duration:</b> ${d}<br>
<b>Price:</b> ₱${p}`;
}

let addons=[];
function toggleAddon(el,name){
el.classList.toggle("active");

if(addons.includes(name)){
addons=addons.filter(a=>a!=name);
}else{
addons.push(name);
}

document.getElementById("addons").value=addons.join(", ");
}
</script>

</body>
</html>