<?php
session_start();
include '../includes/db.php';

if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['name'];

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

    // FIXED: therapist_id (hindi therapist)
    $therapist_id = mysqli_real_escape_string($conn,$_POST['therapist'] ?? '');

    // addons FIX
    $addons = isset($_POST['addons']) ? mysqli_real_escape_string($conn,$_POST['addons']) : "";

    mysqli_query($conn,"INSERT INTO bookings
    (user_id,customer_name,phone,service,duration,price,booking_date,booking_time,pax,addons,therapist_id,notes,status)
    VALUES
    ('$user_id','$name','$phone','$service','$duration','$price','$date','$time','$pax','$addons','$therapist_id','$notes','Pending')
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
body{
margin:0;
font-family:Poppins;
background:#0b0b0b;
color:#fff;
}

header{
padding:15px 8%;
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

<!-- THERAPIST (FIXED - therapist_id) -->
<div class="section">
<div class="title">Preferred Therapist (Optional)</div>

<select name="therapist">
    <option value="">No Preference</option>

    <?php
    $query = mysqli_query($conn,"SELECT id, name FROM therapists ORDER BY name ASC");

    if($query && mysqli_num_rows($query) > 0){
        while($row = mysqli_fetch_assoc($query)){
    ?>
        <option value="<?= $row['id'] ?>">
            <?= htmlspecialchars($row['name']) ?>
        </option>
    <?php
        }
    } else {
        echo '<option disabled>No therapists available</option>';
    }
    ?>
</select>
</div>

<!-- SERVICES (UNCHANGED UI) -->
<div class="section">
<div class="title">Services</div>

<div class="grid">

<div class="card" onclick="selectService(this,'Swedish Massage','600','850','1150','Gentle soothing massage for relaxation')">
<b>Swedish Massage</b>
<div class="desc">Gentle, soothing massage for stress relief</div>
<div class="price">₱600 - ₱1150</div>
</div>

<div class="card" onclick="selectService(this,'Mizpah Signature','750','1100','1450','Premium blend therapy')">
<b>Mizpah Signature</b>
<div class="desc">Most requested premium massage blend</div>
<div class="price">₱750 - ₱1450</div>
</div>

<div class="card" onclick="selectService(this,'Thai Massage','650','950','1250','Stretch + pressure therapy')">
<b>Thai Massage</b>
<div class="desc">Stretching & deep pressure therapy</div>
<div class="price">₱650 - ₱1250</div>
</div>

<div class="card" onclick="selectService(this,'Shiatsu Massage','650','950','1250','Energy flow massage')">
<b>Shiatsu Massage</b>
<div class="desc">Finger pressure & energy balance</div>
<div class="price">₱650 - ₱1250</div>
</div>

<div class="card" onclick="selectService(this,'Lymphatic Massage','850','1250','1650','Detox & circulation')">
<b>Lymphatic Massage</b>
<div class="desc">Detox + body fluid drainage</div>
<div class="price">₱850 - ₱1650</div>
</div>

</div>
</div>

<!-- PACKAGES (UNCHANGED) -->
<div class="section">
<div class="title">Mizpah Packages (Fixed Duration)</div>

<div class="grid">

<div class="card" onclick="selectPackage(this,'Bronze Package','1 hr 45 mins','1600')">
<b>Bronze Package</b>
<div class="desc">
Swedish massage<br>
Body scrub<br>
Hot stone<br>
Milk mask<br>
Korean face mask<br>
Foot mask
</div>
<div class="price">₱1,600 • 1 hr 45 mins</div>
</div>

<div class="card" onclick="selectPackage(this,'Silver Package','1 hr 45 mins','1800')">
<b>Silver Package</b>
<div class="desc">
Mizpah Signature massage<br>
Body scrub + Hot stone<br>
Milk mask + Face mask + Foot mask
</div>
<div class="price">₱1,800 • 1 hr 45 mins</div>
</div>

<div class="card" onclick="selectPackage(this,'Gold Package','2 hrs','2000')">
<b>Gold Package</b>
<div class="desc">
Signature massage<br>
Body scrub + Hot stone<br>
Head or Foot massage + full masks
</div>
<div class="price">₱2,000 • 2 hrs</div>
</div>

</div>
</div>

<!-- ADDONS -->
<div class="section">
<div class="title">Add-ons</div>

<div class="grid">

<div class="card addon" onclick="toggleAddon(this,'Hot Stone +300')">
Hot Stone <div class="desc">Deep muscle relaxation</div><div class="price">₱300</div>
</div>

<div class="card addon" onclick="toggleAddon(this,'Foot Massage +350')">
Foot Massage <div class="desc">Reflexology therapy</div><div class="price">₱350</div>
</div>

<div class="card addon" onclick="toggleAddon(this,'Head & Shoulder +350')">
Head & Shoulder <div class="desc">Upper body relief</div><div class="price">₱350</div>
</div>

<div class="card addon" onclick="toggleAddon(this,'Body Scrub +750')">
Body Scrub <div class="desc">Skin renewal treatment</div><div class="price">₱750</div>
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

<label>Date</label>
<input type="date" name="booking_date">

<label>Time</label>
<select name="booking_time">
<option>1:00 PM</option><option>2:00 PM</option>
<option>3:00 PM</option><option>4:00 PM</option>
<option>5:00 PM</option><option>6:00 PM</option>
<option>7:00 PM</option><option>8:00 PM</option>
</select>

<label>Pax</label>
<select name="pax">
<option>1</option><option>2</option>
<option>3</option><option>4</option>
<option>5</option><option>6</option>
</select>

<label>Phone</label>
<input type="text" name="phone">

<label>Notes</label>
<textarea name="notes"></textarea>

<div class="summary" id="summary">Select service or package</div>

<button type="submit" name="submit_booking">CONFIRM BOOKING</button>

</div>

</form>

<script>
let selected={};

function selectService(el,name,d1,d2,d3,desc){
reset();
el.classList.add("active");

selected={name,duration:"1hr",price:d1};

document.getElementById("service").value=name;
document.getElementById("duration").value="1hr";
document.getElementById("price").value=d1;

update();
}

function selectPackage(el,name,duration,price){
reset();
el.classList.add("active");

selected={name,duration,price};

document.getElementById("service").value=name;
document.getElementById("duration").value=duration;
document.getElementById("price").value=price;

update();
}

function reset(){
document.querySelectorAll(".card").forEach(c=>c.classList.remove("active"));
}

function update(){
document.getElementById("summary").innerHTML=
"<b>Service:</b> "+selected.name+"<br>"+
"<b>Duration:</b> "+selected.duration+"<br>"+
"<b>Price:</b> ₱"+selected.price;
}

let addons=[];
function toggleAddon(el,name){
el.classList.toggle("active");

if(addons.includes(name)){
addons=addons.filter(a=>a!=name);
}else{
addons.push(name);
}

document.getElementById("addons").value=addons.join(",");
}
</script>

</body>
</html>