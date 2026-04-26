<?php
session_start();
include __DIR__ . '/includes/db.php';

if(isset($_POST['submit_booking'])){

$name       = $_POST['customer_name'] ?? '';
$phone      = $_POST['phone'] ?? '';

$service_id = $_POST['service_id'] ?? '';
$service    = $_POST['service'] ?? '';
$duration   = $_POST['duration'] ?? '';
$price      = $_POST['price'] ?? 0;

$date       = $_POST['booking_date'] ?? '';
$time       = $_POST['booking_time'] ?? '';
$pax        = (int)($_POST['pax'] ?? 1);
$payment    = $_POST['payment_method'] ?? 'Cash';
$notes      = $_POST['notes'] ?? '';

$therapist  = $_POST['therapist'] ?? '';
$addons     = $_POST['addons'] ?? '';

if($pax < 1){ $pax = 1; }
if($pax > 6){ die("Max 6 pax only"); }

mysqli_query($conn,"INSERT INTO bookings
(service_id,service,duration,price,customer_name,phone,booking_date,booking_time,pax,payment_method,notes,addons,therapist_id,status)
VALUES
('$service_id','$service','$duration','$price','$name','$phone','$date','$time','$pax','$payment','$notes','$addons','$therapist','Pending')
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
<title>Mizpah Spa Booking</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

<style>

*{
margin:0;
padding:0;
box-sizing:border-box;
}

body{
background:#0b0b0b;
color:#fff;
font-family:Poppins,sans-serif;
}

.header{
padding:18px;
text-align:center;
font-weight:600;
font-size:20px;
color:#D6C29C;
border-bottom:1px solid #222;
letter-spacing:1px;
}

.container{
max-width:1050px;
margin:auto;
padding:24px;
display:flex;
flex-direction:column;
gap:18px;
}

.box{
background:#141414;
border:1px solid #222;
border-radius:16px;
padding:20px;
}

h3{
font-size:12px;
letter-spacing:1px;
color:#D6C29C;
margin-bottom:14px;
}

.grid{
display:grid;
grid-template-columns:repeat(auto-fit,minmax(160px,1fr));
gap:14px;
}

.card{
background:#111;
border:1px solid #222;
border-radius:14px;
padding:14px;
cursor:pointer;
transition:.2s;
text-align:center;
min-height:82px;
display:flex;
flex-direction:column;
justify-content:center;
font-size:12px;
}

.card:hover{
transform:translateY(-2px);
border-color:#D6C29C;
}

.active{
border:2px solid #D6C29C !important;
background:#1b1b1b;
}

input,select,textarea{
width:100%;
background:#0f0f0f;
border:1px solid #333;
color:#fff;
border-radius:10px;
padding:12px;
margin-top:8px;
font-family:Poppins;
}

textarea{
resize:vertical;
min-height:90px;
}

.btn{
width:100%;
padding:14px;
border:none;
border-radius:12px;
background:#D6C29C;
color:#111;
font-weight:700;
cursor:pointer;
margin-top:10px;
}

#timeBox{
display:grid;
grid-template-columns:repeat(auto-fit,minmax(150px,1fr));
gap:14px;
}

.time-card{
background:#111;
border:1px solid #222;
border-radius:14px;
padding:16px;
cursor:pointer;
text-align:center;
transition:.2s;
}

.time-card:hover{
transform:translateY(-2px);
border-color:#D6C29C;
}

.time-card.active{
border:2px solid #D6C29C;
background:#1b1b1b;
}

.dim{
opacity:.35;
pointer-events:none;
}

.small{
font-size:11px;
color:#aaa;
margin-top:6px;
line-height:1.4;
}

</style>
</head>
<body>

<div class="header">MIZPAH SPA BOOKING</div>

<div class="container">

<form method="POST">

<div class="box">
<h3>1. CATEGORY</h3>
<div class="grid">
<div class="card category-item" data-cat="Massage">Massage</div>
<div class="card category-item" data-cat="Package">Package</div>
<div class="card category-item" data-cat="Promo">Promo</div>
</div>
</div>

<div class="box">
<h3>2. SERVICE</h3>
<div class="grid" id="serviceBox"></div>
</div>

<div class="box">
<h3>3. DETAILS</h3>
<div class="card" style="cursor:default">
<div id="descBox">Select service</div>
</div>
</div>

<div class="box">
<h3>4. DURATION</h3>
<div class="grid" id="durationBox"></div>
</div>

<div class="box">
<h3>5. ADD-ONS</h3>
<div class="grid" id="addonBox"></div>
<input type="hidden" name="addons" id="addons">
</div>

<div class="box">
<h3>6. DATE</h3>
<input type="date" name="booking_date" id="booking_date" required>
</div>

<div class="box">
<h3>7. TIME</h3>
<div id="timeBox"></div>
</div>

<div class="box">
<h3>8. THERAPIST</h3>
<div class="grid" id="therapistBox"></div>
<input type="hidden" name="therapist" id="therapist">
</div>

<div class="box">
<h3>9. CUSTOMER INFO</h3>

<input type="text" name="customer_name" placeholder="Full Name" required>
<input type="text" name="phone" placeholder="Phone Number" required>

<input type="number" name="pax" min="1" max="6" value="1">

<select name="payment_method">
<option>Cash</option>
<option>GCash</option>
</select>

<textarea name="notes" placeholder="Notes"></textarea>

<button type="submit" name="submit_booking" class="btn">BOOK NOW</button>

</div>

<input type="hidden" name="service_id" id="service_id">
<input type="hidden" name="service" id="service">
<input type="hidden" name="duration" id="duration">
<input type="hidden" name="price" id="price">
<input type="hidden" name="booking_time" id="booking_time">

</form>

</div>

<script>

/* CATEGORY + SERVICE + DURATION */
document.addEventListener('click',function(e){

let cat = e.target.closest('.category-item');

if(cat){

document.querySelectorAll('.category-item').forEach(x=>x.classList.remove('active'));
cat.classList.add('active');

fetch('get_services_by_category.php?cat='+encodeURIComponent(cat.dataset.cat))
.then(r=>r.json())
.then(data=>{

serviceBox.innerHTML='';
durationBox.innerHTML='';
descBox.innerText='Select service';

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
service.value    = svc.dataset.name;
descBox.innerText= svc.dataset.desc;

fetch('get_duration.php?id='+svc.dataset.id)
.then(r=>r.json())
.then(data=>{

durationBox.innerHTML='';

data.forEach(d=>{

durationBox.innerHTML += `
<div class="card duration-item"
data-d="${d.duration}"
data-p="${d.price}">
${d.duration}<br>
₱${d.price}
</div>`;

});

});

}

/* DURATION */
let dur = e.target.closest('.duration-item');

if(dur){

document.querySelectorAll('.duration-item').forEach(x=>x.classList.remove('active'));
dur.classList.add('active');

duration.value = dur.dataset.d;
price.value    = dur.dataset.p;

}

/* ADDONS */
let add = e.target.closest('.addon-item');

if(add){

add.classList.toggle('active');

let arr=[];

document.querySelectorAll('.addon-item.active').forEach(x=>{
arr.push(x.dataset.name);
});

addons.value = arr.join(', ');

}

/* THERAPIST */
let th = e.target.closest('.therapist-item');

if(th){

th.classList.toggle('active');

let arr=[];

document.querySelectorAll('.therapist-item.active').forEach(x=>{
arr.push(x.dataset.name);
});

therapist.value = arr.join(', ');

}

});

/* LOAD ADDONS */
fetch('get_addons.php')
.then(r=>r.json())
.then(data=>{

addonBox.innerHTML='';

data.forEach(a=>{

addonBox.innerHTML += `
<div class="card addon-item" data-name="${a.service_name}">
<strong>${a.service_name}</strong>
<div class="small">${a.description}</div>
</div>`;

});

});

/* DATE CHANGE -> TIME */
booking_date.addEventListener('change',loadTimes);

/* FULL FIXED TIME ORDER */
function loadTimes(){

let date = booking_date.value;
if(!date) return;

timeBox.innerHTML='';
therapistBox.innerHTML='';
booking_time.value='';
therapist.value='';

let day = new Date(date).getDay();

/* Sat-Sun = 1PM | Mon-Fri = 3PM */
let start = (day===0 || day===6) ? 13 : 15;
let end   = 27; /* until 3AM */

let requests=[];

/* preserve true order index */
for(let h=start; h<end; h++){

let realHour = h;
let sendHour = h % 24;

requests.push(
fetch('check_slot.php?date='+date+'&time='+sendHour+':00')
.then(r=>r.json())
.then(data=>({
realHour: realHour,
sendHour: sendHour,
data: data
}))
);

}

Promise.all(requests).then(results=>{

/* IMPORTANT: sort by realHour not sendHour */
results.sort((a,b)=>a.realHour-b.realHour);

results.forEach(item=>{

let hour24 = item.sendHour;
let data   = item.data;

let h12 = hour24 % 12;
if(h12===0) h12=12;

let ampm = hour24 >= 12 ? 'PM' : 'AM';

let card = document.createElement('div');
card.className='time-card';

card.innerHTML = `
<div style="font-size:14px;font-weight:600">
${h12}:00 ${ampm}
</div>
<div class="small">
${data.available ? data.remaining+' slot left' : 'FULL'}
</div>
`;

if(!data.available){
card.classList.add('dim');
}

card.dataset.time = hour24 + ':00';

card.onclick=function(){

document.querySelectorAll('.time-card').forEach(x=>x.classList.remove('active'));
this.classList.add('active');

booking_time.value = this.dataset.time;

loadTherapists();

};

timeBox.appendChild(card);

});

});

}

/* LOAD THERAPIST */
function loadTherapists(){

let date = booking_date.value;
let time = booking_time.value;

if(!date || !time) return;

fetch('get_available_therapists.php?date='+date+'&time='+time)
.then(r=>r.json())
.then(data=>{

therapistBox.innerHTML='';
therapist.value='';

data.forEach(name=>{

therapistBox.innerHTML += `
<div class="card therapist-item" data-name="${name}">
${name}
</div>`;

});

});

}

</script>

</body>
</html>