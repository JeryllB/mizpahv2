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

$addons = "";
if(isset($_POST['addons'])){
    $addons = implode(", ", $_POST['addons']);
}

/* max 6 beds */
$check = mysqli_query($conn,"
SELECT COUNT(*) total
FROM bookings
WHERE booking_date='$date'
AND booking_time='$time'
");

$row = mysqli_fetch_assoc($check);

if($row['total'] >= 6){
    echo "<script>alert('Selected slot is fully booked. Please choose another time.');history.back();</script>";
    exit;
}

/* insert */
mysqli_query($conn,"INSERT INTO bookings
(customer_name,phone,service,duration,price,booking_date,booking_time,pax,addons,notes,status)
VALUES
('$name','$phone','$service','$duration','$price','$date','$time','$pax','$addons','$notes','Pending')
");

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

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">

<style>
:root{
--bg:#0b0b0b;
--card:#161616;
--gold:#D6C29C;
--line:rgba(214,194,156,.18);
}

*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:Poppins;
}

body{
background:var(--bg);
color:#fff;
}

header{
display:flex;
justify-content:space-between;
align-items:center;
padding:18px 8%;
background:#111;
border-bottom:1px solid var(--line);
position:sticky;
top:0;
z-index:999;
}

.logo{
font-family:'Playfair Display';
font-size:24px;
color:var(--gold);
font-weight:700;
}

nav a{
color:#fff;
text-decoration:none;
margin-left:15px;
font-size:14px;
}

.hero{
text-align:center;
padding:45px 8%;
}

.hero h1{
font-family:'Playfair Display';
font-size:42px;
color:var(--gold);
}

.hero p{
margin-top:8px;
color:#bbb;
}

.section{
padding:24px 8%;
}

.title{
font-family:'Playfair Display';
font-size:25px;
color:var(--gold);
margin-bottom:16px;
}

.sub{
font-size:13px;
color:#aaa;
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
border-radius:14px;
border:1px solid var(--line);
cursor:pointer;
transition:.25s;
}

.card:hover{
transform:translateY(-4px);
border-color:var(--gold);
}

.active{
border:1px solid var(--gold);
box-shadow:0 0 0 1px var(--gold);
}

.card h3{
font-size:18px;
margin-bottom:7px;
}

.desc{
font-size:13px;
color:#ccc;
line-height:1.6;
min-height:40px;
}

.price{
margin-top:10px;
font-weight:700;
color:var(--gold);
}

.form-box{
max-width:950px;
margin:auto;
background:#141414;
padding:25px;
border-radius:16px;
border:1px solid var(--line);
}

label{
display:block;
font-size:13px;
color:var(--gold);
margin-top:12px;
margin-bottom:6px;
}

input,select,textarea{
width:100%;
padding:12px;
border-radius:10px;
border:1px solid #2a2a2a;
background:#0d0d0d;
color:#fff;
}

textarea{
height:110px;
resize:none;
}

.row{
display:grid;
grid-template-columns:1fr 1fr;
gap:15px;
}

.summary{
margin-top:16px;
padding:18px;
background:#161616;
border-radius:14px;
border:1px solid var(--line);
line-height:1.8;
}

button{
width:100%;
padding:15px;
margin-top:16px;
border:none;
border-radius:12px;
background:var(--gold);
font-weight:700;
cursor:pointer;
}

button:hover{
opacity:.92;
}

@media(max-width:768px){
.row{
grid-template-columns:1fr;
}
.hero h1{
font-size:30px;
}
}
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
<p>Mon–Fri 3PM–3AM • Sat–Sun 1PM–3AM</p>
</div>

<form method="POST">

<!-- SERVICES -->
<div class="section">
<div class="title">1. Choose Service</div>
<div class="sub">Tap one service first.</div>

<div class="grid">

<div class="card" onclick="chooseService('Swedish Massage',600,850,1150)">
<h3>Swedish Massage</h3>
<div class="desc">Gentle relaxing massage for full body stress relief.</div>
<div class="price">Starts ₱600</div>
</div>

<div class="card" onclick="chooseService('Mizpah Signature',750,1100,1450)">
<h3>Mizpah Signature</h3>
<div class="desc">Premium signature therapy blend.</div>
<div class="price">Starts ₱750</div>
</div>

<div class="card" onclick="chooseService('Thai Massage',650,950,1250)">
<h3>Thai Massage</h3>
<div class="desc">Stretching and pressure therapy.</div>
<div class="price">Starts ₱650</div>
</div>

<div class="card" onclick="chooseService('Shiatsu Massage',650,950,1250)">
<h3>Shiatsu Massage</h3>
<div class="desc">Traditional finger pressure massage.</div>
<div class="price">Starts ₱650</div>
</div>

<div class="card" onclick="chooseService('Lymphatic Massage',850,1250,1650)">
<h3>Lymphatic Massage</h3>
<div class="desc">Helps circulation and detox flow.</div>
<div class="price">Starts ₱850</div>
</div>

<div class="card" onclick="chooseService('Prenatal Massage',850,1250,1650)">
<h3>Prenatal / Postnatal</h3>
<div class="desc">Comfort care for mothers.</div>
<div class="price">Starts ₱850</div>
</div>

</div>
</div>

<!-- PACKAGES -->
<div class="section">
<div class="title">2. Mizpah Packages</div>
<div class="sub">Packages already include fixed duration.</div>

<div class="grid">

<div class="card" onclick="choosePackage('Bronze Package','1 Hour 45 Minutes',1600)">
<h3>🥉 Bronze Package</h3>
<div class="desc">Swedish + Scrub + Hot Stone</div>
<div class="price">₱1600</div>
</div>

<div class="card" onclick="choosePackage('Silver Package','1 Hour 45 Minutes',1800)">
<h3>🥈 Silver Package</h3>
<div class="desc">Signature + Scrub + Hot Stone</div>
<div class="price">₱1800</div>
</div>

<div class="card" onclick="choosePackage('Gold Package','2 Hours',2000)">
<h3>🥇 Gold Package</h3>
<div class="desc">Luxury full spa session</div>
<div class="price">₱2000</div>
</div>

</div>
</div>

<!-- DURATION -->
<div class="section">
<div class="title">3. Choose Duration</div>
<div class="sub">For regular services only.</div>

<div class="grid">

<div class="card" onclick="pickDuration('1 Hour')">
<h3>1 Hour</h3>
<div class="price" id="d1">Select service first</div>
</div>

<div class="card" onclick="pickDuration('1.5 Hours')">
<h3>1.5 Hours</h3>
<div class="price" id="d2">Select service first</div>
</div>

<div class="card" onclick="pickDuration('2 Hours')">
<h3>2 Hours</h3>
<div class="price" id="d3">Select service first</div>
</div>

</div>
</div>

<!-- ADDONS -->
<div class="section">
<div class="title">4. Add-ons (Optional)</div>
<div class="sub">Tap cards you want to include.</div>

<div class="grid">

<div class="card addon" onclick="toggleAddon(this,'Hot Stone +300')">
<h3>Hot Stone</h3>
<div class="desc">Warm stones for deeper relaxation.</div>
<div class="price">+₱300</div>
</div>

<div class="card addon" onclick="toggleAddon(this,'Foot Massage +350')">
<h3>Foot Massage</h3>
<div class="desc">30 mins reflexology treatment.</div>
<div class="price">+₱350</div>
</div>

<div class="card addon" onclick="toggleAddon(this,'Head & Shoulder +350')">
<h3>Head & Shoulder</h3>
<div class="desc">Relieves headache and tension.</div>
<div class="price">+₱350</div>
</div>

<div class="card addon" onclick="toggleAddon(this,'Body Scrub +750')">
<h3>Premium Body Scrub</h3>
<div class="desc">Skin renewal and glow treatment.</div>
<div class="price">+₱750</div>
</div>

</div>

<input type="hidden" name="addons[]" id="addonData">
</div>

<!-- BOOKING FORM -->
<div class="section">
<div class="title">5. Booking Details</div>

<div class="form-box">

<input type="hidden" name="service" id="service" required>
<input type="hidden" name="duration" id="duration" required>
<input type="hidden" name="price" id="price" required>

<label>Selected Service</label>
<input id="serviceView" readonly>

<label>Selected Duration</label>
<input id="durationView" readonly>

<label>Estimated Price</label>
<input id="priceView" readonly>

<div class="row">

<div>
<label>Full Name</label>
<input name="customer_name" required>
</div>

<div>
<label>Phone Number</label>
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
<option>1:00 PM</option>
<option>2:00 PM</option>
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
<option>3:00 AM</option>
</select>
</div>

</div>

<label>Pax</label>
<select name="pax">
<option>1</option>
<option>2</option>
<option>3</option>
<option>4</option>
<option>5</option>
<option>6</option>
</select>

<label>Notes</label>
<textarea name="notes"></textarea>

<div class="summary" id="summary">
Choose your service first.
</div>

<button type="submit" name="submit_booking">Confirm Booking</button>

</div>
</div>

</form>

<script>
let prices = {};
let service = "";
let duration = "";
let total = 0;
let fixed = false;
let addons = [];

function chooseService(name,p1,p2,p3){
service = name;
fixed = false;
duration = "";
total = 0;

prices = {
"1 Hour":p1,
"1.5 Hours":p2,
"2 Hours":p3
};

document.getElementById("d1").innerHTML="₱"+p1;
document.getElementById("d2").innerHTML="₱"+p2;
document.getElementById("d3").innerHTML="₱"+p3;

updateSummary("Now choose duration.");
}

function choosePackage(name,dur,price){
service = name;
duration = dur;
total = price;
fixed = true;
updateFields();
}

function pickDuration(d){
if(service==""){
alert("Please select service first.");
return;
}
if(fixed) return;

duration = d;
total = prices[d];
updateFields();
}

function toggleAddon(el,name){

if(addons.includes(name)){
addons = addons.filter(a => a != name);
el.classList.remove("active");
}else{
addons.push(name);
el.classList.add("active");
}

document.getElementById("addonData").value = addons.join(", ");
updateFields();
}

function addonTotal(){
let sum = 0;

addons.forEach(function(a){
if(a.includes("300")) sum += 300;
if(a.includes("350")) sum += 350;
if(a.includes("750")) sum += 750;
});

return sum;
}

function updateFields(){

if(service=="") return;

let finalTotal = parseInt(total) + addonTotal();

document.getElementById("service").value = service;
document.getElementById("duration").value = duration;
document.getElementById("price").value = finalTotal;

document.getElementById("serviceView").value = service;
document.getElementById("durationView").value = duration;
document.getElementById("priceView").value = "₱"+finalTotal;

updateSummary();
}

function updateSummary(msg=""){

if(msg!=""){
document.getElementById("summary").innerHTML = msg;
return;
}

document.getElementById("summary").innerHTML =
"<b>Service:</b> "+service+"<br>"+
"<b>Duration:</b> "+duration+"<br>"+
"<b>Add-ons:</b> "+(addons.length ? addons.join(", ") : "None")+"<br>"+
"<b>Total:</b> ₱"+document.getElementById("price").value;
}
</script>

</body>
</html>
