<?php
session_start();
include '../includes/db.php';

if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['name'];

/* SUBMIT BOOKING */
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

    $therapist_id = mysqli_real_escape_string($conn,$_POST['therapist'] ?? '');
    $payment_method = mysqli_real_escape_string($conn,$_POST['payment_method']);
    $addons = mysqli_real_escape_string($conn,$_POST['addons'] ?? "");

    mysqli_query($conn,"INSERT INTO bookings
    (user_id,customer_name,phone,service,duration,price,booking_date,booking_time,pax,addons,therapist_id,payment_method,notes,status)
    VALUES
    ('$user_id','$name','$phone','$service','$duration','$price','$date','$time','$pax','$addons','$therapist_id','$payment_method','$notes','Pending')
    ");

    echo "<script>alert('Booking Successful!');window.location='mybookings.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Booking</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&family=Playfair+Display:wght@600&display=swap" rel="stylesheet">

<style>
body{margin:0;font-family:Poppins;background:#0b0b0b;color:#fff;}
header{padding:15px 8%;background:#111;display:flex;justify-content:space-between;border-bottom:1px solid #333;}
.logo{font-family:'Playfair Display';color:#D6C29C;font-size:22px;}

.section{padding:25px 8%;}
.title{color:#D6C29C;font-size:20px;margin-bottom:10px;}

.grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:12px;}

.card{
background:#161616;
padding:15px;
border-radius:10px;
border:1px solid #333;
cursor:pointer;
transition:.2s;
position:relative;
}

.card:hover{transform:scale(1.02);border-color:#D6C29C;}

.card.active{
border:2px solid #D6C29C;
background:#1f1f1f;
}

.card.active::after{
content:"✔";
position:absolute;
top:10px;right:12px;
color:#D6C29C;
font-weight:bold;
}

.desc{font-size:13px;color:#bbb;margin:8px 0;}
.price{color:#D6C29C;font-weight:600;}

.summary{
background:#161616;
padding:15px;
border-radius:8px;
border:1px solid #333;
margin-top:10px;
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
</style>
</head>

<body>

<header>
<div class="logo">Mizpah Spa Booking</div>
<div><?= $user_name ?></div>
</header>

<form method="POST">

<input type="hidden" name="customer_name" value="<?= $user_name ?>">
<input type="hidden" name="service" id="service">
<input type="hidden" name="duration" id="duration">
<input type="hidden" name="price" id="price">
<input type="hidden" name="addons" id="addons">

<!-- THERAPIST -->
<div class="section">
<div class="title">Preferred Therapist</div>
<select name="therapist">
<option value="">No Preference</option>
<?php
$q = mysqli_query($conn,"SELECT id,name FROM therapists ORDER BY name ASC");
while($r = mysqli_fetch_assoc($q)){
?>
<option value="<?= $r['id'] ?>"><?= htmlspecialchars($r['name']) ?></option>
<?php } ?>
</select>
</div>

<!-- SERVICES -->
<div class="section">
<div class="title">Services</div>
<div class="grid">

<div class="card" onclick="selectService(this,'Swedish Massage',600)">
<b>Swedish Massage</b>
<div class="desc">A gentle, soothing massage using light to moderate pressure for deep relaxation.</div>
<div class="price">₱600</div>
</div>

<div class="card" onclick="selectService(this,'MIZPAH Signature',750)">
<b>MIZPAH Signature</b>
<div class="desc">Blended therapy: Swedish + Shiatsu + deep tissue + stretching + facial + tool release.</div>
<div class="price">₱750</div>
</div>

<div class="card" onclick="selectService(this,'Thai Massage',650)">
<b>Thai Massage</b>
<div class="desc">Stretching + firm pressure therapy for flexibility and muscle relief.</div>
<div class="price">₱650</div>
</div>

<div class="card" onclick="selectService(this,'Shiatsu Massage',650)">
<b>Shiatsu Massage</b>
<div class="desc">Finger pressure technique to restore energy flow and reduce tension.</div>
<div class="price">₱650</div>
</div>

<div class="card" onclick="selectService(this,'Lymphatic Massage',850)">
<b>Lymphatic Massage</b>
<div class="desc">Detox + fluid drainage to reduce bloating and improve circulation.</div>
<div class="price">₱850</div>
</div>

<div class="card" onclick="selectService(this,'Prenatal Massage',850)">
<b>Prenatal / Postpartum Massage</b>
<div class="desc">Gentle massage tailored for mothers.</div>
<div class="price">₱850</div>
</div>

</div>
</div>

<!-- DURATION -->
<div class="section">
<div class="title">Duration</div>
<div class="grid">

<div class="card" onclick="setDuration(this,'1 hr',0)">1 Hour</div>
<div class="card" onclick="setDuration(this,'1.5 hr',200)">1.5 Hours</div>
<div class="card" onclick="setDuration(this,'2 hr',400)">2 Hours</div>

</div>
</div>

<!-- PACKAGES -->
<div class="section">
<div class="title">Mizpah Packages</div>
<div class="grid">

<div class="card" onclick="selectPackage(this,'Bronze Package','1 hr 45 mins',1600)">
<b>Bronze Package</b>
<div class="desc">Swedish + scrub + hot stone + masks + foot mask</div>
<div class="price">₱1,600</div>
</div>

<div class="card" onclick="selectPackage(this,'Silver Package','1 hr 45 mins',1800)">
<b>Silver Package</b>
<div class="desc">MIZPAH Signature + full spa set</div>
<div class="price">₱1,800</div>
</div>

<div class="card" onclick="selectPackage(this,'Gold Package','2 hrs',2000)">
<b>Gold Package</b>
<div class="desc">Full luxury spa experience</div>
<div class="price">₱2,000</div>
</div>

</div>
</div>

<!-- ADDONS -->
<div class="section">
<div class="title">Add-ons</div>
<div class="grid">

<div class="card addon" onclick="toggleAddon(this,300)">Hot Stone <div class="desc">Deep muscle relaxation</div><div class="price">₱300</div></div>

<div class="card addon" onclick="toggleAddon(this,350)">Ventosa Cupping <div class="desc">Blood flow + toxin release</div><div class="price">₱350</div></div>

<div class="card addon" onclick="toggleAddon(this,350)">Foot Massage <div class="desc">Reflexology therapy</div><div class="price">₱350</div></div>

<div class="card addon" onclick="toggleAddon(this,350)">Head & Shoulder <div class="desc">Upper body relief</div><div class="price">₱350</div></div>

<div class="card addon" onclick="toggleAddon(this,400)">Kiddie Massage <div class="desc">Gentle kids massage</div><div class="price">₱400</div></div>


</div>
</div>

<!-- BOOKING -->
<div class="section">

<label>Date</label>
<input type="date" name="booking_date" id="datePick" onchange="loadTimeSlots()" required>

<label>Time</label>
<select name="booking_time" id="timeSlot" required></select>

<label>Payment</label>
<select name="payment_method">
<option>Cash</option>
<option>GCash</option>
</select>

<label>Pax</label>
<select name="pax">
<option>1</option><option>2</option><option>3</option>
<option>4</option><option>5</option><option>6</option>
</select>

<label>Phone</label>
<input type="text" name="phone" required>

<label>Notes</label>
<textarea name="notes"></textarea>

<div class="summary" id="summary">Select service</div>

<button type="submit" name="submit_booking">CONFIRM BOOKING</button>

</div>

</form>

<script>
let selected = {};
let addonTotal = 0;

function selectService(el,name,price){
document.querySelectorAll(".card").forEach(c=>c.classList.remove("active"));
el.classList.add("active");
selected = {name,price};
document.getElementById("service").value = name;
update();
}

function setDuration(el,duration,extra){
document.querySelectorAll(".section:nth-of-type(3) .card").forEach(c=>c.classList.remove("active"));
el.classList.add("active");
selected.duration = duration;
selected.price = (selected.price||0)+extra;
document.getElementById("duration").value = duration;
update();
}

function selectPackage(el,name,duration,price){
document.querySelectorAll(".card").forEach(c=>c.classList.remove("active"));
el.classList.add("active");

selected = {name,price,duration};
document.getElementById("service").value = name;
document.getElementById("duration").value = duration;

update();
}

function toggleAddon(el,price){
el.classList.toggle("active");
addonTotal += el.classList.contains("active") ? price : -price;

let arr=[...document.querySelectorAll(".addon.active")].map(a=>a.innerText);
document.getElementById("addons").value = arr.join(", ");

update();
}

function update(){
let total = (selected.price||0)+addonTotal;

document.getElementById("price").value = total;

document.getElementById("summary").innerHTML =
"<b>Service:</b> "+(selected.name||"-")+"<br>"+
"<b>Duration:</b> "+(selected.duration||"-")+"<br>"+
"<b>Addons:</b> ₱"+addonTotal+"<br>"+
"<b>Total:</b> ₱"+total;
}

/* TIME FIX */
function loadTimeSlots(){
let sel = document.getElementById("timeSlot");
sel.innerHTML="";

for(let h=15;h<=23;h++){
let hr=h>12?h-12:h;
let ampm=h>=12?"PM":"AM";
sel.innerHTML+=`<option value="${h}:00">${hr}:00 ${ampm}</option>`;
}
}

document.addEventListener("DOMContentLoaded", loadTimeSlots);
</script>

</body>
</html>