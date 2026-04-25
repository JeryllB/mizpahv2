<?php
session_start();

/* FIXED PATH (IMPORTANT) */
include __DIR__ . '/../includes/db.php';

/* CHECK LOGIN */
if(!isset($_SESSION['user_id'])){
header("Location: ../login.php");
exit;
}

/* GET USER */
$user_id = $_SESSION['user_id'];

$user = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT * FROM users WHERE id='$user_id'
"));

/* SUBMIT BOOKING */
if(isset($_POST['submit_booking'])){

$name = $user['name'];

$service_id = $_POST['service_id'];
$service = $_POST['service'];
$duration = $_POST['duration'];
$price = $_POST['price'];

$date = $_POST['booking_date'];
$time = $_POST['booking_time'];
$pax = (int)$_POST['pax'];
$payment = $_POST['payment_method'];
$notes = $_POST['notes'];
$therapist = $_POST['therapist'] ?? '';

if($pax > 6){
die("Max 6 pax only");
}

/* SLOT CHECK */
$check = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COALESCE(SUM(pax),0) as total
FROM bookings
WHERE booking_date='$date'
AND booking_time='$time'
AND status!='Cancelled'
"));

if($check['total'] + $pax > 6){
die("Slot full");
}

/* INSERT BOOKING */
mysqli_query($conn,"INSERT INTO bookings
(user_id,service_id,service,duration,price,customer_name,booking_date,booking_time,pax,payment_method,notes,status)
VALUES
('$user_id','$service_id','$service','$duration','$price','$name','$date','$time','$pax','$payment','$notes','Pending')
");

$booking_id = mysqli_insert_id($conn);

/* SAVE THERAPIST */
if($therapist != ""){

$t = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT id FROM therapists WHERE name='$therapist'
"));

$tid = $t['id'];

mysqli_query($conn,"
INSERT INTO booking_therapists
(booking_id,therapist_id,booking_date,booking_time)
VALUES
('$booking_id','$tid','$date','$time')
");
}

header("Location: thankyou.php?id=$booking_id");
exit;
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Customer Booking</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

<style>
body{margin:0;background:#0b0b0b;color:#fff;font-family:Poppins;}
.header{text-align:center;padding:20px;color:#D6C29C;font-weight:600;border-bottom:1px solid #222;}
.container{max-width:1000px;margin:auto;padding:20px;}
.section{margin-bottom:25px;}
h3{text-align:center;color:#D6C29C;}
.grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:12px;}
.card{background:#141414;padding:14px;border-radius:12px;border:1px solid #222;cursor:pointer;}
.card.active{border:2px solid #D6C29C;}
.small{font-size:12px;color:#aaa;}
input,select,textarea{width:100%;padding:12px;margin-top:8px;background:#111;border:1px solid #333;color:#fff;border-radius:8px;}
button{width:100%;padding:14px;background:#D6C29C;border:none;font-weight:700;margin-top:15px;border-radius:10px;}
.infoBox{padding:10px;background:#111;border-radius:10px;border:1px solid #333;text-align:center;}
</style>
</head>

<body>

<div class="header">My Booking</div>

<div class="container">

<form method="POST">

<!-- USER -->
<div class="section">
<h3>Customer</h3>
<div class="infoBox">
<b><?= $user['name'] ?></b>
</div>
</div>

<!-- SERVICE -->
<div class="section">
<h3>Service</h3>

<div class="grid">
<?php
$s = mysqli_query($conn,"SELECT * FROM services");
while($r=mysqli_fetch_assoc($s)):
?>
<div class="card"
onclick="selectService(this,'<?= $r['id'] ?>','<?= $r['service_name'] ?>','<?= $r['description'] ?>')">

<b><?= $r['service_name'] ?></b>
<div class="small"><?= $r['description'] ?></div>

</div>
<?php endwhile; ?>
</div>
</div>

<input type="hidden" name="service_id" id="service_id">
<input type="hidden" name="service" id="service">
<input type="hidden" name="duration" id="duration">
<input type="hidden" name="price" id="price">

<!-- DETAILS -->
<div class="section">
<h3>Details</h3>
<div class="card"><div id="descBox" class="small">Select service</div></div>
</div>

<!-- DURATION -->
<div class="section">
<h3>Duration</h3>
<div class="grid" id="durationBox">Select service first</div>
</div>

<!-- DATE -->
<div class="section">
<h3>Date</h3>
<input type="date" id="booking_date" name="booking_date" required>
</div>

<!-- TIME -->
<div class="section">
<h3>Time</h3>

<div class="grid">
<div class="card time-slot" data-time="15:00"><b>3:00 PM</b></div>
<div class="card time-slot" data-time="16:00"><b>4:00 PM</b></div>
<div class="card time-slot" data-time="17:00"><b>5:00 PM</b></div>
<div class="card time-slot" data-time="18:00"><b>6:00 PM</b></div>
<div class="card time-slot" data-time="19:00"><b>7:00 PM</b></div>
</div>

</div>

<input type="hidden" id="booking_time" name="booking_time">

<!-- THERAPIST -->
<div class="section">
<h3>Therapist (Optional)</h3>
<select name="therapist" id="therapistBox" disabled>
<option value="">Select date & time first</option>
</select>
</div>

<!-- INFO -->
<div class="section">
<h3>Booking Info</h3>

<input type="number" name="pax" value="1" max="6">

<select name="payment_method">
<option>Cash</option>
<option>GCash</option>
</select>

<textarea name="notes" placeholder="Notes"></textarea>

<button name="submit_booking">BOOK NOW</button>

</div>

</form>

</div>

<script>

/* SERVICE */
function selectService(el,id,name,desc){

document.querySelectorAll('.card').forEach(c=>c.classList.remove('active'));
el.classList.add('active');

service_id.value=id;
service.value=name;
descBox.innerText=desc;

fetch('../get_duration.php?id='+id)
.then(r=>r.json())
.then(data=>{

let html='';

data.forEach(d=>{
html += `<div class="card" onclick="selectDuration(this,'${d.duration}','${d.price}')">
<b>${d.duration}</b><div class="small">₱${d.price}</div></div>`;
});

durationBox.innerHTML=html;

});
}

/* DURATION */
function selectDuration(el,d,p){

document.querySelectorAll('#durationBox .card')
.forEach(c=>c.classList.remove('active'));

el.classList.add('active');

duration.value=d;
price.value=p;
}

/* TIME */
document.querySelectorAll('.time-slot').forEach(el=>{
el.onclick=()=>{

document.querySelectorAll('.time-slot').forEach(c=>c.classList.remove('active'));
el.classList.add('active');

booking_time.value = el.dataset.time;

loadTherapists();

};
});

/* DATE */
document.getElementById('booking_date').addEventListener('change',function(){
loadTherapists();
});

/* THERAPIST LOAD */
function loadTherapists(){

let date = document.getElementById('booking_date').value;
let time = document.getElementById('booking_time').value;

if(!date || !time) return;

fetch('../get_available_therapists.php?date='+date+'&time='+time)
.then(res=>res.json())
.then(data=>{

let select = document.getElementById('therapistBox');

select.innerHTML = "";

let def = document.createElement("option");
def.value = "";
def.text = "No Preference (Admin will assign)";
select.appendChild(def);

data.forEach(name=>{
let opt = document.createElement("option");
opt.value = name;
opt.text = name;
select.appendChild(opt);
});

select.disabled = false;

});

}

</script>

</body>
</html>