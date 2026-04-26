<?php
session_start();
include '../includes/db.php';

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
if($pax < 1) $pax = 1;
if($pax > 6) $pax = 6;

$payment = $_POST['payment_method'] ?? 'Cash';
$notes = $_POST['notes'] ?? '';

$addons = $_POST['addons'] ?? '';
$therapist = $_POST['therapist'] ?? '';

$therapist_id = 0;

if(!empty($therapist)){
$q = mysqli_query($conn,"SELECT id FROM therapists WHERE name='$therapist' LIMIT 1");
if($r = mysqli_fetch_assoc($q)){
$therapist_id = $r['id'];
}
}

mysqli_query($conn,"INSERT INTO bookings
(service_id,service,duration,price,customer_name,phone,booking_date,booking_time,pax,payment_method,notes,addons,therapist_id,status)
VALUES
('$service_id','$service','$duration','$price','$name','$phone','$date','$time','$pax','$payment','$notes','$addons','$therapist_id','Pending')
");

$id = mysqli_insert_id($conn);

header("Location: thankyou.php?id=".$id);
exit;
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Guest Booking</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

<style>

body{
margin:0;
background:#0b0b0b;
color:#fff;
font-family:Poppins;
}

.header{
text-align:center;
padding:18px;
color:#D6C29C;
border-bottom:1px solid #222;
font-weight:600;
}

.container{
max-width:1000px;
margin:auto;
padding:20px;
display:flex;
flex-direction:column;
gap:16px;
}

/* FIX OVERFLOW */
.box{
background:#141414;
border:1px solid #222;
padding:18px;
border-radius:14px;
overflow:hidden;
}

h3{
font-size:12px;
color:#D6C29C;
margin-bottom:12px;
}

.grid{
display:grid;
grid-template-columns:repeat(auto-fit,minmax(160px,1fr));
gap:12px;
}

.card{
background:#111;
border:1px solid #222;
border-radius:12px;
padding:12px;
cursor:pointer;
text-align:center;
font-size:12px;
word-break:break-word;
}

.card:hover{
border-color:#D6C29C;
}

.active{
border:2px solid #D6C29C;
background:#1b1b1b;
}

/* INPUT FIX */
input,select,textarea{
width:100%;
padding:12px;
margin-top:8px;
background:#0f0f0f;
border:1px solid #333;
color:#fff;
border-radius:10px;
box-sizing:border-box;
}

.btn{
width:100%;
padding:14px;
background:#D6C29C;
border:none;
font-weight:700;
margin-top:10px;
cursor:pointer;
color:#111;
border-radius:10px;
}

/* TIME FIX */
#timeBox{
display:grid;
grid-template-columns:repeat(auto-fit,minmax(140px,1fr));
gap:10px;
}

.time-card{
background:#111;
border:1px solid #222;
border-radius:12px;
padding:12px;
text-align:center;
cursor:pointer;
display:flex;
flex-direction:column;
gap:4px;
}

.time-card:hover{
border-color:#D6C29C;
}

.time-card.active{
border:2px solid #D6C29C;
}

.small{
font-size:11px;
color:#aaa;
}

.dim{
opacity:.35;
pointer-events:none;
}

</style>
</head>

<body>

<div class="header">MIZPAH SPA BOOKING</div>

<div class="container">

<form method="POST">

<!-- CATEGORY -->
<div class="box">
<h3>1. CATEGORY</h3>
<div class="grid">
<div class="card category-item" data-cat="Massage">Massage</div>
<div class="card category-item" data-cat="Package">Package</div>
<div class="card category-item" data-cat="Promo">Promo</div>
</div>
</div>

<!-- SERVICE -->
<div class="box">
<h3>2. SERVICE</h3>
<div class="grid" id="serviceBox"></div>
</div>

<!-- DETAILS -->
<div class="box">
<h3>3. DETAILS</h3>
<div class="card"><div id="descBox">Select service</div></div>
</div>

<!-- DURATION (FIX LAYOUT OVERFLOW) -->
<div class="box">
<h3>4. DURATION</h3>
<div class="grid" id="durationBox"></div>
</div>

<!-- ADDONS -->
<div class="box">
<h3>5. ADD-ONS</h3>
<div class="grid" id="addonBox"></div>
<input type="hidden" name="addons" id="addons">
</div>

<!-- DATE -->
<div class="box">
<h3>6. DATE</h3>
<input type="date" name="booking_date" id="booking_date" required>
</div>

<!-- TIME (FIX SLOT DISPLAY) -->
<div class="box">
<h3>7. TIME SLOT</h3>
<div id="timeBox"></div>
<input type="hidden" name="booking_time" id="booking_time">
</div>

<!-- THERAPIST (FIX LAYOUT) -->
<div class="box">
<h3>8. THERAPIST</h3>
<div class="grid" id="therapistBox"></div>
<input type="hidden" name="therapist" id="therapist">
</div>

<!-- CUSTOMER -->
<div class="box">
<h3>9. CUSTOMER INFO</h3>

<input name="customer_name" placeholder="Full Name" required>
<input name="phone" placeholder="Phone" required>

<input type="number" name="pax" value="1" min="1" max="6">

<select name="payment_method">
<option>Cash</option>
<option>GCash</option>
</select>

<textarea name="notes" placeholder="Notes"></textarea>

<button class="btn" name="submit_booking">BOOK NOW</button>

</div>

<input type="hidden" name="service_id" id="service_id">
<input type="hidden" name="service" id="service">
<input type="hidden" name="duration" id="duration">
<input type="hidden" name="price" id="price">

</form>

</div>

<script>

const serviceBox = document.getElementById('serviceBox');
const addonBox = document.getElementById('addonBox');
const durationBox = document.getElementById('durationBox');
const therapistBox = document.getElementById('therapistBox');
const timeBox = document.getElementById('timeBox');

const booking_date = document.getElementById('booking_date');
const booking_time = document.getElementById('booking_time');

const service_id = document.getElementById('service_id');
const service = document.getElementById('service');
const duration = document.getElementById('duration');
const price = document.getElementById('price');
const addons = document.getElementById('addons');
const therapist = document.getElementById('therapist');
const descBox = document.getElementById('descBox');

/* CATEGORY + SERVICE */
document.addEventListener('click',function(e){

let cat = e.target.closest('.category-item');
if(cat){

document.querySelectorAll('.category-item').forEach(x=>x.classList.remove('active'));
cat.classList.add('active');

fetch('../get_services_by_category.php?cat='+cat.dataset.cat)
.then(r=>r.json())
.then(data=>{

serviceBox.innerHTML='';
data.forEach(s=>{
serviceBox.innerHTML += `
<div class="card service-item"
data-id="${s.id}"
data-name="${s.service_name}"
data-desc="${s.description}">
${s.service_name}
</div>`;
});

});

}

/* SERVICE */
let svc = e.target.closest('.service-item');
if(svc){

document.querySelectorAll('.service-item').forEach(x=>x.classList.remove('active'));
svc.classList.add('active');

service_id.value = svc.dataset.id;
service.value = svc.dataset.name;
descBox.innerText = svc.dataset.desc;

/* duration */
fetch('../get_duration.php?id='+svc.dataset.id)
.then(r=>r.json())
.then(data=>{

durationBox.innerHTML='';
data.forEach(d=>{
durationBox.innerHTML += `
<div class="card duration-item"
data-d="${d.duration}"
data-p="${d.price}">
${d.duration}<br>₱${d.price}
</div>`;
});

});

}

});

/* ADDONS */
fetch('../get_addons.php')
.then(r=>r.json())
.then(data=>{

addonBox.innerHTML='';
data.forEach(a=>{
addonBox.innerHTML += `
<div class="card addon-item" data-name="${a.service_name}">
<b>${a.service_name}</b>
<div class="small">${a.description}</div>
</div>`;
});

});

/* CLICK EVENTS */
document.addEventListener('click',function(e){

let add = e.target.closest('.addon-item');
if(add){
add.classList.toggle('active');

let arr=[];
document.querySelectorAll('.addon-item.active').forEach(x=>{
arr.push(x.dataset.name);
});

addons.value = arr.join(', ');
}

let dur = e.target.closest('.duration-item');
if(dur){
document.querySelectorAll('.duration-item').forEach(x=>x.classList.remove('active'));
dur.classList.add('active');

duration.value = dur.dataset.d;
price.value = dur.dataset.p;
}

let th = e.target.closest('.therapist-item');
if(th){
document.querySelectorAll('.therapist-item').forEach(x=>x.classList.remove('active'));
th.classList.add('active');

therapist.value = th.dataset.name;
}

});

/* TIME SLOT FIX + SLOT DISPLAY */
booking_date.addEventListener('change',function(){

let date = this.value;
if(!date) return;

timeBox.innerHTML='';
booking_time.value='';

let day = new Date(date).getDay();
let start = (day===0||day===6) ? 13 : 15;
let end = 27;

for(let h=start; h<end; h++){

let hour = h % 24;
let h12 = hour % 12; if(h12===0) h12=12;
let ampm = hour>=12?'PM':'AM';

let card=document.createElement('div');
card.className='time-card';

card.innerHTML=`
<div style="font-weight:600">${h12}:00 ${ampm}</div>
<div class="small">Loading slots...</div>
`;

card.dataset.time = hour+':00';

card.onclick=function(){

document.querySelectorAll('.time-card').forEach(x=>x.classList.remove('active'));
this.classList.add('active');

booking_time.value=this.dataset.time;

/* load therapist */
fetch('../get_available_therapists.php?date='+date+'&time='+this.dataset.time)
.then(r=>r.json())
.then(data=>{

therapistBox.innerHTML='';
data.forEach(n=>{
therapistBox.innerHTML += `
<div class="card therapist-item" data-name="${n}">
${n}
</div>`;
});

});

};

timeBox.appendChild(card);
}

});

</script>

</body>
</html>