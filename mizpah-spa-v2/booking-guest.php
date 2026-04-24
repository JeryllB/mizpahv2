<?php
session_start();
include 'includes/db.php';

/* ================= BOOKING INSERT ================= */
if(isset($_POST['submit_booking'])){

$service_id = $_POST['service_id'];
$service = mysqli_real_escape_string($conn,$_POST['service']);
$duration = mysqli_real_escape_string($conn,$_POST['duration']);
$price = $_POST['price'];

$name = mysqli_real_escape_string($conn,$_POST['customer_name']);
$phone = mysqli_real_escape_string($conn,$_POST['phone']);
$date = $_POST['booking_date'];
$time = $_POST['booking_time'];
$pax = $_POST['pax'];
$payment = $_POST['payment_method'];
$notes = mysqli_real_escape_string($conn,$_POST['notes']);

/* VALIDATION */
if(empty($service_id) || empty($duration) || empty($date) || empty($time)){
    echo "<script>alert('Please complete service, duration, date and time');</script>";
}else{

mysqli_query($conn,"INSERT INTO bookings
(service_id,service,duration,price,customer_name,phone,booking_date,booking_time,pax,payment_method,notes,status)
VALUES
('$service_id','$service','$duration','$price','$name','$phone','$date','$time','$pax','$payment','$notes','Pending')
");

header("Location: thankyou.php");
exit;

}

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

.header{
padding:20px;
text-align:center;
background:#111;
color:#D6C29C;
font-weight:600;
border-bottom:1px solid #222;
}

.container{
max-width:950px;
margin:auto;
padding:25px;
}

.section{
margin-bottom:35px;
}

.section h3{
text-align:center;
color:#D6C29C;
margin-bottom:15px;
}

.grid{
display:grid;
grid-template-columns:repeat(auto-fit,minmax(240px,1fr));
gap:15px;
}

.card{
background:#161616;
padding:16px;
border-radius:14px;
border:1px solid #222;
cursor:pointer;
transition:.2s;
}

.card:hover{
border-color:#D6C29C;
transform:translateY(-3px);
}

.card.active{
border:2px solid #D6C29C;
}

.card b{
color:#D6C29C;
display:block;
margin-bottom:6px;
}

.small{
font-size:12px;
color:#aaa;
line-height:1.5;
}

/* TIME */
.time-grid{
display:grid;
grid-template-columns:repeat(4,1fr);
gap:10px;
}

.time{
background:#111;
padding:12px;
border-radius:10px;
text-align:center;
border:1px solid #333;
cursor:pointer;
font-size:13px;
}

.time.active{
background:#D6C29C;
color:#000;
font-weight:600;
}

/* INPUTS */
input,select,textarea{
width:100%;
padding:12px;
margin-top:8px;
background:#0d0d0d;
border:1px solid #333;
color:#fff;
border-radius:8px;
}

/* BUTTON */
button{
width:100%;
padding:14px;
background:#D6C29C;
border:none;
border-radius:10px;
font-weight:700;
cursor:pointer;
margin-top:20px;
}

button:hover{
opacity:.9;
}
</style>
</head>

<body>

<div class="header">Mizpah Wellness Spa Booking</div>

<div class="container">

<form method="POST">

<!-- SERVICE -->
<div class="section">
<h3>Choose Service</h3>

<div class="grid">

<?php
$services = mysqli_query($conn,"SELECT * FROM services");
while($s=mysqli_fetch_assoc($services)):
?>

<div class="card"
onclick="selectService(this,<?= $s['id'] ?>,'<?= $s['service_name'] ?>','<?= htmlspecialchars($s['description']) ?>')">

<b><?= $s['service_name'] ?></b>
<div class="small"><?= $s['description'] ?></div>

</div>

<?php endwhile; ?>

</div>
</div>

<!-- HIDDEN -->
<input type="hidden" name="service_id" id="service_id">
<input type="hidden" name="service" id="service">
<input type="hidden" name="duration" id="duration">
<input type="hidden" name="price" id="price">
<input type="hidden" name="booking_time" id="booking_time">

<!-- DESCRIPTION -->
<div class="section">
<h3>Service Details</h3>
<div class="card">
<div class="small" id="descBox">Select a service to see details</div>
</div>
</div>

<!-- DURATION -->
<div class="section">
<h3>Choose Duration</h3>
<div id="durationBox" class="grid">Select service first</div>
</div>

<!-- DATE -->
<div class="section">
<h3>Date</h3>
<input type="date" name="booking_date" required>
</div>

<!-- TIME -->
<div class="section">
<h3>Time</h3>

<div class="time-grid">

<div class="time" onclick="setTime(this,'3:00 PM')">3:00 PM</div>
<div class="time" onclick="setTime(this,'4:00 PM')">4:00 PM</div>
<div class="time" onclick="setTime(this,'5:00 PM')">5:00 PM</div>
<div class="time" onclick="setTime(this,'6:00 PM')">6:00 PM</div>
<div class="time" onclick="setTime(this,'7:00 PM')">7:00 PM</div>
<div class="time" onclick="setTime(this,'8:00 PM')">8:00 PM</div>
<div class="time" onclick="setTime(this,'9:00 PM')">9:00 PM</div>
<div class="time" onclick="setTime(this,'10:00 PM')">10:00 PM</div>
<div class="time" onclick="setTime(this,'11:00 PM')">11:00 PM</div>
<div class="time" onclick="setTime(this,'12:00 AM')">12:00 AM</div>

</div>

</div>

<!-- DETAILS -->
<div class="section">
<h3>Customer Details</h3>

<input name="customer_name" placeholder="Full Name" required>
<input name="phone" placeholder="Phone Number" required>

<select name="payment_method">
<option>Cash</option>
<option>GCash</option>
</select>

<input name="pax" type="number" value="1">

<textarea name="notes" placeholder="Notes (optional)"></textarea>

</div>

<button name="submit_booking">CONFIRM BOOKING</button>

</form>

</div>

<script>

function selectService(el,id,name,desc){

document.querySelectorAll('.card').forEach(c=>c.classList.remove('active'));
el.classList.add('active');

document.getElementById('service_id').value = id;
document.getElementById('service').value = name;
document.getElementById('descBox').innerText = desc;

/* LOAD DURATION */
fetch("get_duration.php?id="+id)
.then(res=>res.json())
.then(data=>{

let html="";

data.forEach(d=>{
html += `
<div class="card"
onclick="selectDuration('${d.duration}',${d.price},this)">
<b>${d.duration}</b>
<div class="small">₱${d.price}</div>
</div>`;
});

document.getElementById("durationBox").innerHTML = html;

});

}

function selectDuration(duration,price,el){

document.querySelectorAll('#durationBox .card')
.forEach(c=>c.classList.remove('active'));

el.classList.add('active');

document.getElementById('duration').value = duration;
document.getElementById('price').value = price;
}

function setTime(el,time){

document.querySelectorAll('.time').forEach(t=>t.classList.remove('active'));
el.classList.add('active');

document.getElementById('booking_time').value = time;
}

</script>

</body>
</html>