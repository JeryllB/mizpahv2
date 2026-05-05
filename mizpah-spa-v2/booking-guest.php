<?php
session_start();
include __DIR__ . '/includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = (int)$_SESSION['user_id'];

if(isset($_POST['submit_booking'])){

$name = $_POST['customer_name'] ?? '';
$phone = $_POST['phone'] ?? '';

$service_id = $_POST['service_id'] ?? '';
$service = $_POST['service'] ?? '';
$duration = $_POST['duration'] ?? '';
$price = $_POST['price'] ?? 0;

$date = $_POST['booking_date'] ?? '';
$time = $_POST['booking_time'] ?? '';
$pax = (int)($_POST['pax'] ?? 1);

$payment = $_POST['payment_method'] ?? 'Cash';
$notes = $_POST['notes'] ?? '';

$therapist = $_POST['therapist'] ?? '';
$addons = $_POST['addons'] ?? '';

$room_type = $_POST['room_type'] ?? '';
$beds = (int)($_POST['beds'] ?? 1);

if($room_type === "Couple Room"){
    $beds = 2;
    $pax = 2;
}

mysqli_query($conn,"INSERT INTO bookings
(user_id,service_id,service,duration,price,customer_name,phone,booking_date,booking_time,pax,payment_method,notes,addons,therapist_id,room_type,beds,status)
VALUES
($user_id,'$service_id','$service','$duration','$price','$name','$phone','$date','$time','$pax','$payment','$notes','$addons','$therapist','$room_type','$beds','Pending')");

header("Location: thankyou.php?id=" . mysqli_insert_id($conn));
exit;
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Booking</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">

<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:Poppins;}
body{background:#0b0b0b;color:#fff;}

.header{padding:18px;text-align:center;color:#D6C29C;border-bottom:1px solid #222;}

.container{max-width:900px;margin:auto;padding:20px;}

.box{
background:#141414;
border:1px solid #222;
border-radius:14px;
padding:18px;
margin-bottom:12px;
}

h3{font-size:12px;color:#D6C29C;margin-bottom:10px;}

.grid{
display:grid;
grid-template-columns:repeat(auto-fit,minmax(140px,1fr));
gap:10px;
}

.card{
background:#111;
border:1px solid #222;
border-radius:12px;
padding:12px;
text-align:center;
cursor:pointer;
font-size:12px;
transition:.2s;
}

.card.active{border:2px solid #D6C29C;}
.card.dim{opacity:.3;pointer-events:none;}

.desc{font-size:11px;color:#aaa;margin-top:4px;}

input,select,textarea{
width:100%;
padding:10px;
margin-top:6px;
background:#0f0f0f;
color:#fff;
border:1px solid #333;
border-radius:10px;
}

.btn{
width:100%;
padding:14px;
background:#D6C29C;
border:none;
border-radius:12px;
font-weight:bold;
cursor:pointer;
}

/* SUMMARY */
.summary{
background:#111;
border:1px solid #333;
border-radius:12px;
padding:12px;
font-size:12px;
margin-bottom:10px;
position:sticky;
top:10px;
}

.summary h4{color:#D6C29C;margin-bottom:6px;}

.time-card{
background:#111;
border:1px solid #222;
border-radius:12px;
padding:12px;
text-align:center;
cursor:pointer;
}

.time-card.active{border:2px solid #D6C29C;}
.time-card.dim{opacity:.3;pointer-events:none;}

.small{font-size:11px;color:#aaa;}
</style>
</head>

<body>

<div class="header">CUSTOMER BOOKING</div>

<div class="container">

<form method="POST">

<!-- CATEGORY -->
<div class="box">
<h3>CATEGORY</h3>
<div class="grid">
<div class="card category" data-cat="Massage">Massage</div>
<div class="card category" data-cat="Package">Package</div>
<div class="card category" data-cat="Promo">Promo</div>
</div>
</div>

<!-- SERVICE -->
<div class="box">
<h3>SERVICE</h3>
<div class="grid" id="serviceBox"></div>
<div id="serviceDesc" class="small"></div>
</div>

<!-- DURATION -->
<div class="box">
<h3>DURATION</h3>
<div class="grid" id="durationBox"></div>
</div>

<!-- ADD-ONS -->
<div class="box">
<h3>ADD-ONS</h3>
<div class="grid" id="addonBox"></div>
<input type="hidden" name="addons" id="addons">
</div>

<!-- ROOM -->
<div class="box">
<h3>ROOM</h3>
<div class="grid">
<div class="card room" data-room="Single Room">Single</div>
<div class="card room" data-room="Couple Room">Couple</div>
</div>
<input type="hidden" name="room_type" id="room_type">
</div>

<!-- BEDS -->
<div class="box">
<h3>BEDS</h3>
<div class="grid">
<div class="card bed" data-bed="1">1</div>
<div class="card bed" data-bed="2">2</div>
<div class="card bed" data-bed="3">3</div>
<div class="card bed" data-bed="4">4</div>
</div>
<input type="hidden" name="beds" id="beds" value="1">
</div>

<!-- DATE -->
<div class="box">
<h3>DATE</h3>
<input type="date" id="booking_date" name="booking_date" required>
</div>

<!-- TIME -->
<div class="box">
<h3>TIME</h3>
<div class="grid" id="timeBox"></div>
<input type="hidden" name="booking_time" id="booking_time">
</div>

<!-- THERAPIST -->
<div class="box">
<h3>THERAPIST</h3>
<div class="grid" id="therapistBox"></div>
<input type="hidden" name="therapist" id="therapist">
</div>

<!-- CUSTOMER -->
<div class="box">
<h3>CUSTOMER</h3>

<input name="customer_name" placeholder="Full Name" required>
<input name="phone" placeholder="Phone Number" required>
<input type="number" name="pax" value="1">
<select name="payment_method">
<option>Cash</option>
<option>GCash</option>
</select>
<textarea name="notes" placeholder="Notes"></textarea>
</div>

<!-- SUMMARY (FIXED ALWAYS WORKING) -->
<div class="summary" id="summaryBox">
<h4>SUMMARY</h4>
Service: -<br>
Duration: -<br>
Room: -<br>
Time: -<br>
Therapist: -
</div>

<button class="btn" name="submit_booking">BOOK NOW</button>

<input type="hidden" name="service_id" id="service_id">
<input type="hidden" name="service" id="service">
<input type="hidden" name="duration" id="duration">
<input type="hidden" name="price" id="price">

</form>

</div>

<script>

let summary = {
service:'-',
duration:'-',
room:'-',
time:'-',
therapist:'-'
};

function renderSummary(){
summaryBox.innerHTML=`
<h4>SUMMARY</h4>
Service: ${summary.service}<br>
Duration: ${summary.duration}<br>
Room: ${summary.room}<br>
Time: ${summary.time}<br>
Therapist: ${summary.therapist}
`;
}

const serviceDesc=document.getElementById('serviceDesc');

/* CATEGORY */
document.addEventListener('click',e=>{
let c=e.target.closest('.category');
if(!c) return;

document.querySelectorAll('.category').forEach(x=>x.classList.remove('active'));
c.classList.add('active');

fetch('get_services_by_category.php?cat='+c.dataset.cat)
.then(r=>r.json())
.then(d=>{
serviceBox.innerHTML='';
d.forEach(s=>{
serviceBox.innerHTML+=`
<div class="card service"
data-id="${s.id}"
data-name="${s.service_name}"
data-desc="${s.description}">
${s.service_name}
</div>`;
});
});
});

/* SERVICE */
document.addEventListener('click',e=>{
let s=e.target.closest('.service');
if(!s) return;

service_id.value=s.dataset.id;
service.value=s.dataset.name;

serviceDesc.innerText=s.dataset.desc||'';

summary.service=s.dataset.name;
renderSummary();

/* LOAD DURATION */
fetch('get_duration.php?id='+s.dataset.id)
.then(r=>r.json())
.then(d=>{
durationBox.innerHTML='';
d.forEach(x=>{
durationBox.innerHTML+=`
<div class="card duration"
data-d="${x.duration}"
data-p="${x.price}">
${x.duration}<br>₱${x.price}
</div>`;
});
});

/* LOAD ADDONS */
fetch('get_addons.php')
.then(r=>r.json())
.then(d=>{
addonBox.innerHTML='';
d.forEach(a=>{
addonBox.innerHTML+=`
<div class="card addon"
data-name="${a.service_name}"
data-price="${a.price}">
${a.service_name}<br>₱${a.price}
<div class="desc">${a.description||''}</div>
</div>`;
});
});
});

/* DURATION FIX */
document.addEventListener('click',e=>{
let d=e.target.closest('.duration');
if(!d) return;

document.querySelectorAll('.duration').forEach(x=>x.classList.remove('active'));
d.classList.add('active');

duration.value=d.dataset.d;
price.value=d.dataset.p;

summary.duration=d.dataset.d;
renderSummary();
});

/* ADDONS FIX */
document.addEventListener('click',e=>{
let a=e.target.closest('.addon');
if(!a) return;

a.classList.toggle('active');

let arr=[];
document.querySelectorAll('.addon.active').forEach(x=>{
arr.push(x.dataset.name);
});

addons.value=arr.join(', ');
});

/* ROOM FIX */
document.addEventListener('click',e=>{
let r=e.target.closest('.room');
if(!r) return;

document.querySelectorAll('.room').forEach(x=>x.classList.remove('active'));
r.classList.add('active');

room_type.value=r.dataset.room;
summary.room=r.dataset.room;

renderSummary();
});

/* ================= ROOM + BED FIX (FINAL) ================= */

document.addEventListener('click',e=>{
let r=e.target.closest('.room');
if(!r) return;

document.querySelectorAll('.room').forEach(x=>x.classList.remove('active'));
r.classList.add('active');

room_type.value = r.dataset.room;

/* RESET BEDS FIRST */
document.querySelectorAll('.bed').forEach(b=>{
b.classList.remove('active','dim');
b.style.pointerEvents = "auto";
});

if(r.dataset.room === "Couple Room"){

/* AUTO FIX */
beds.value = 2;
document.querySelector('[name="pax"]').value = 2;

/* LOCK ALL BEDS */
document.querySelectorAll('.bed').forEach(b=>{
b.classList.add('dim');
b.style.pointerEvents = "none";
});

/* FORCE BED = 2 SELECTED UI */
let bed2 = document.querySelector('.bed[data-bed="2"]');
if(bed2){
bed2.classList.add('active');
}

}else{

/* SINGLE ROOM RESET */
beds.value = 1;
document.querySelector('[name="pax"]').value = 1;

}
});

/* BED CLICK (ONLY IF NOT LOCKED) */
document.addEventListener('click',e=>{
let b=e.target.closest('.bed');
if(!b) return;

/* BLOCK IF COUPLE */
if(room_type.value === "Couple Room") return;

document.querySelectorAll('.bed').forEach(x=>x.classList.remove('active'));
b.classList.add('active');

beds.value = b.dataset.bed;
document.querySelector('[name="pax"]').value = b.dataset.bed;
});

/* TIME + THERAPIST FIX */
booking_date.onchange=async ()=>{
timeBox.innerHTML='';
therapistBox.innerHTML='';

for(let h=10;h<=22;h++){
let res=await fetch('check_slot.php?date='+booking_date.value+'&time='+h+':00');
let data=await res.json();

let div=document.createElement('div');
div.className='time-card';

div.innerHTML=(h%12||12)+':00 '+(h>=12?'PM':'AM')+
`<div class="small">${data.remaining} slot</div>`;

if(!data.available) div.classList.add('dim');

div.onclick=()=>{
booking_time.value=h+':00';
summary.time=h+':00';
renderSummary();

document.querySelectorAll('.time-card').forEach(x=>x.classList.remove('active'));
div.classList.add('active');

/* THERAPIST FIXED PER SLOT */
fetch('get_available_therapists.php?date='+booking_date.value+'&time='+h+':00')
.then(r=>r.json())
.then(d=>{
therapistBox.innerHTML='';
d.forEach(t=>{
therapistBox.innerHTML+=`<div class="card therapist">${t}</div>`;
});
});
};

timeBox.appendChild(div);
}
};

/* THERAPIST */
document.addEventListener('click',e=>{
let t=e.target.closest('.therapist');
if(!t) return;

document.querySelectorAll('.therapist').forEach(x=>x.classList.remove('active'));
t.classList.add('active');

therapist.value=t.innerText;
summary.therapist=t.innerText;
renderSummary();
});

</script>

</body>
</html> 