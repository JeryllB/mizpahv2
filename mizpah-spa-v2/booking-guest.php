<?php
session_start();
include 'includes/db.php';

if(isset($_POST['submit_booking'])){

$name = mysqli_real_escape_string($conn,$_POST['customer_name']);
$phone = mysqli_real_escape_string($conn,$_POST['phone']);
$service = mysqli_real_escape_string($conn,$_POST['service']);
$duration = mysqli_real_escape_string($conn,$_POST['duration']);
$price = mysqli_real_escape_string($conn,$_POST['price']);
$date = mysqli_real_escape_string($conn,$_POST['booking_date']);
$time = mysqli_real_escape_string($conn,$_POST['booking_time']);
$pax = mysqli_real_escape_string($conn,$_POST['pax']);
$notes = mysqli_real_escape_string($conn,$_POST['notes']);
$payment_method = mysqli_real_escape_string($conn,$_POST['payment_method']);

$addons = $_POST['addons'] ?? "";
if(is_array($addons)) $addons = implode(", ",$addons);

mysqli_query($conn,"INSERT INTO bookings
(customer_name,phone,service,duration,price,booking_date,booking_time,pax,addons,payment_method,notes,status)
VALUES
('$name','$phone','$service','$duration','$price','$date','$time','$pax','$addons','$payment_method','$notes','Pending')
");

header("Location: thankyou.php");
exit;
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Mizpah Booking</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

<style>
body{
margin:0;
font-family:Poppins;
background:#0b0b0b;
color:#fff;
}

/* HEADER */
.topbar{
position:sticky;
top:0;
z-index:999;
background:#111;
padding:18px;
text-align:center;
color:#D6C29C;
font-weight:600;
border-bottom:1px solid #333;
}

/* CONTAINER */
.wrapper{
max-width:900px;
margin:auto;
padding:20px;
}

/* SECTION */
section{
margin-bottom:25px;
}

h3{
color:#D6C29C;
text-align:center;
margin-bottom:10px;
}

/* SERVICE LIST (CLEAN) */
.list{
display:flex;
flex-direction:column;
gap:10px;
}

.item{
background:#111;
border:1px solid #333;
padding:14px;
border-radius:10px;
cursor:pointer;
transition:.2s;
}

.item:hover{
border-color:#D6C29C;
}

.item.active{
border:2px solid #D6C29C;
}

.item small{
display:block;
font-size:12px;
color:#aaa;
margin-top:5px;
line-height:1.4;
}

/* DURATION */
.duration-box{
display:flex;
gap:10px;
justify-content:center;
}

.duration{
flex:1;
background:#111;
border:1px solid #333;
padding:12px;
text-align:center;
border-radius:10px;
cursor:pointer;
}

.duration.active{
border:2px solid #D6C29C;
}

/* ADDONS */
.addon-box{
display:none;
margin-top:10px;
flex-direction:column;
gap:10px;
}

.addon{
background:#111;
border:1px solid #333;
padding:12px;
border-radius:10px;
cursor:pointer;
text-align:center;
}

.addon.active{
border:2px solid #D6C29C;
}

/* FORM */
input,select,textarea{
width:100%;
padding:10px;
margin-top:6px;
background:#0d0d0d;
border:1px solid #333;
color:#fff;
border-radius:6px;
}

/* TIME */
.time-grid{
display:grid;
grid-template-columns:repeat(4,1fr);
gap:8px;
}

.time{
padding:10px;
text-align:center;
background:#111;
border:1px solid #333;
border-radius:8px;
cursor:pointer;
font-size:12px;
}

.time.active{
background:#D6C29C;
color:#000;
}

/* SUMMARY */
.summary{
background:#111;
border:1px solid #333;
padding:15px;
border-radius:10px;
margin-top:20px;
}

/* BUTTON */
button{
width:100%;
padding:12px;
margin-top:15px;
background:#D6C29C;
border:none;
font-weight:600;
border-radius:8px;
cursor:pointer;
}
</style>
</head>

<body>

<div class="topbar">Mizpah Wellness Spa Booking</div>

<div class="wrapper">

<form method="POST">

<input type="hidden" name="service" id="service">
<input type="hidden" name="duration" id="duration">
<input type="hidden" name="price" id="price">
<input type="hidden" name="addons" id="addons">
<input type="hidden" name="booking_time" id="booking_time">

<!-- SERVICE -->
<section>
<h3>Services</h3>

<div class="list">

<div class="item" onclick="selectService(this,'Swedish Massage',600)">
<b>Swedish Massage</b>
<small>Gentle relaxation massage using light pressure to melt stress.</small>
</div>

<div class="item" onclick="selectService(this,'MIZPAH Signature',750)">
<b>MIZPAH Signature</b>
<small>Swedish + Shiatsu + deep tissue + tool therapy blend.</small>
</div>

<div class="item" onclick="selectService(this,'Thai Massage',650)">
<b>Thai Massage</b>
<small>Stretching + pressure therapy for flexibility.</small>
</div>

<div class="item" onclick="selectService(this,'Shiatsu Massage',650)">
<b>Shiatsu Massage</b>
<small>Energy flow pressure massage technique.</small>
</div>

<div class="item" onclick="selectService(this,'Lymphatic Massage',850)">
<b>Lymphatic Massage</b>
<small>Detox + bloating reduction therapy.</small>
</div>

<div class="item" onclick="selectService(this,'Prenatal Massage',850)">
<b>Prenatal Massage</b>
<small>Safe massage for expecting mothers.</small>
</div>

</div>
</section>

<!-- DURATION -->
<section>
<h3>Duration</h3>

<div class="duration-box">

<div class="duration" onclick="setDuration(this,'1 hr',0)">1 Hour</div>
<div class="duration" onclick="setDuration(this,'1.5 hr',200)">1.5 Hour</div>
<div class="duration" onclick="setDuration(this,'2 hr',400)">2 Hour</div>

</div>
</section>

<!-- ADDONS -->
<section>
<h3 onclick="toggleAddons()" style="cursor:pointer;">
Add-ons ▼
</h3>

<div class="addon-box" id="addonBox">

<div class="addon" onclick="addon(this,'Hot Stone',300)">Hot Stone</div>
<div class="addon" onclick="addon(this,'Ventosa',350)">Ventosa</div>
<div class="addon" onclick="addon(this,'Foot Massage',350)">Foot Massage</div>
<div class="addon" onclick="addon(this,'Head & Shoulder',350)">Head & Shoulder</div>
<div class="addon" onclick="addon(this,'Kiddie Massage',400)">Kiddie Massage</div>

</div>
</section>

<!-- SCHEDULE -->
<section>
<h3>Schedule</h3>

<input type="date" name="booking_date" required>

<div class="time-grid">

<div class="time" onclick="setTime(this,'15:00')">3 PM</div>
<div class="time" onclick="setTime(this,'16:00')">4 PM</div>
<div class="time" onclick="setTime(this,'17:00')">5 PM</div>
<div class="time" onclick="setTime(this,'18:00')">6 PM</div>
<div class="time" onclick="setTime(this,'19:00')">7 PM</div>
<div class="time" onclick="setTime(this,'20:00')">8 PM</div>
<div class="time" onclick="setTime(this,'21:00')">9 PM</div>
<div class="time" onclick="setTime(this,'22:00')">10 PM</div>
<div class="time" onclick="setTime(this,'23:00')">11 PM</div>
<div class="time" onclick="setTime(this,'00:00')">12 AM</div>
<div class="time" onclick="setTime(this,'01:00')">1 AM</div>
<div class="time" onclick="setTime(this,'02:00')">2 AM</div>

</div>
</section>

<!-- DETAILS -->
<section>
<h3>Details</h3>

<input name="customer_name" placeholder="Name" required>
<input name="phone" placeholder="Phone" required>

<select name="payment_method">
<option>Cash</option>
<option>GCash</option>
</select>

<select name="pax">
<option>1</option><option>2</option><option>3</option>
<option>4</option><option>5</option><option>6</option>
</select>

<textarea name="notes" placeholder="Notes"></textarea>

<button type="submit" name="submit_booking">CONFIRM BOOKING</button>

</section>

</form>

<!-- SUMMARY -->
<div class="summary">
<h3>Booking Summary</h3>
<p>Service: <span id="s">-</span></p>
<p>Duration: <span id="d">-</span></p>
<p>Add-ons: <span id="a">-</span></p>
<p>Time: <span id="t">-</span></p>
<p>Total: ₱<span id="p">0</span></p>
</div>

</div>

<script>

let data={
service:"",
duration:"",
price:0,
addons:[],
time:""
};

function selectService(el,name,price){
document.querySelectorAll(".item").forEach(c=>c.classList.remove("active"));
el.classList.add("active");

data.service=name;
data.price=price;

document.getElementById("service").value=name;
document.getElementById("price").value=price;
update();
}

function setDuration(el,dur,extra){
document.querySelectorAll(".duration").forEach(c=>c.classList.remove("active"));
el.classList.add("active");

data.duration=dur;
data.price+=extra;

document.getElementById("duration").value=dur;
document.getElementById("price").value=data.price;
update();
}

function addon(el,name,price){
el.classList.toggle("active");

if(el.classList.contains("active")){
data.addons.push(name);
data.price+=price;
}else{
data.addons=data.addons.filter(a=>a!==name);
data.price-=price;
}

document.getElementById("addons").value=data.addons.join(", ");
document.getElementById("price").value=data.price;
update();
}

function setTime(el,time){
document.querySelectorAll(".time").forEach(t=>t.classList.remove("active"));
el.classList.add("active");

data.time=time;
document.getElementById("booking_time").value=time;
update();
}

function toggleAddons(){
let box=document.getElementById("addonBox");
box.style.display = box.style.display==="flex"?"none":"flex";
}

function update(){
document.getElementById("s").innerText=data.service||"-";
document.getElementById("d").innerText=data.duration||"-";
document.getElementById("a").innerText=data.addons.join(", ")||"-";
document.getElementById("t").innerText=data.time||"-";
document.getElementById("p").innerText=data.price||0;
}

</script>

</body>
</html>