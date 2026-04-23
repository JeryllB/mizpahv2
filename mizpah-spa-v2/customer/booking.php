<?php
session_start();

/* FIXED PATH */
include '../includes/db.php';

if (!$conn) {
    die("Database connection failed");
}

/* =========================
   INSERT BOOKING
========================= */
if (isset($_POST['submit_booking'])) {

    $user_id = $_SESSION['user_id'] ?? null;

    $name = mysqli_real_escape_string($conn, $_POST['customer_name']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $service = mysqli_real_escape_string($conn, $_POST['service']);
    $duration = mysqli_real_escape_string($conn, $_POST['duration']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $date = mysqli_real_escape_string($conn, $_POST['booking_date']);
    $time = mysqli_real_escape_string($conn, $_POST['booking_time']);
    $pax = mysqli_real_escape_string($conn, $_POST['pax']);
    $notes = mysqli_real_escape_string($conn, $_POST['notes']);
    $payment_method = mysqli_real_escape_string($conn, $_POST['payment_method']);
    $therapist_id = (int)($_POST['therapist_id'] ?? 0);

    $addons = $_POST['addons'] ?? "";
    if (is_array($addons)) {
        $addons = implode(", ", $addons);
    }

    mysqli_query($conn, "
        INSERT INTO bookings (
            user_id, customer_name, phone, service, duration, price,
            booking_date, booking_time, pax, addons,
            payment_method, notes, therapist_id, status
        ) VALUES (
            '$user_id','$name','$phone','$service','$duration','$price',
            '$date','$time','$pax','$addons',
            '$payment_method','$notes','$therapist_id','Pending'
        )
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

/* LIST */
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

.item:hover{border-color:#D6C29C;}
.item.active{border:2px solid #D6C29C;}

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
flex-direction:column;
gap:10px;
margin-top:10px;
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

/* INPUT */
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

/* SUMMARY */
.summary{
background:#111;
border:1px solid #333;
padding:15px;
border-radius:10px;
margin-top:20px;
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

<!-- SERVICES -->
<section>
<h3>Services</h3>

<div class="list">

<div class="item" onclick="selectService(this,'Swedish Massage','A gentle relaxation massage',600)">
<b>Swedish Massage</b>
<small>A gentle, soothing massage using light pressure to relieve stress.</small>
</div>

<div class="item" onclick="selectService(this,'MIZPAH Signature','Premium blend therapy',750)">
<b>MIZPAH Signature</b>
<small>Swedish + Shiatsu + deep tissue + tool therapy blend.</small>
</div>

<div class="item" onclick="selectService(this,'Thai Massage','Stretch therapy',650)">
<b>Thai Massage</b>
<small>Stretching + pressure therapy for flexibility.</small>
</div>

<div class="item" onclick="selectService(this,'Shiatsu Massage','Energy flow',650)">
<b>Shiatsu Massage</b>
<small>Pressure-based energy balancing therapy.</small>
</div>

<div class="item" onclick="selectService(this,'Lymphatic Massage','Detox',850)">
<b>Lymphatic Massage</b>
<small>Detox and fluid drainage therapy.</small>
</div>

<div class="item" onclick="selectService(this,'Prenatal Massage','For moms',850)">
<b>Prenatal / Postpartum</b>
<small>Safe therapy for pregnancy recovery.</small>
</div>

</div>
</section>

<!-- THERAPIST -->
<section>
<h3>Therapist (Optional)</h3>

<select name="therapist_id">
<option value="0">No Preference</option>

<?php
$t = mysqli_query($conn,"SELECT * FROM therapists WHERE status='Active'");
while($row=mysqli_fetch_assoc($t)):
?>
<option value="<?= $row['id'] ?>">
<?= $row['name'] ?>
</option>
<?php endwhile; ?>

</select>
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
<h3 onclick="toggleAddons()">Add-ons ▼</h3>

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

</div>

<script>

let data = {
service:"",
duration:"",
price:0,
addons:[],
time:""
};

function selectService(el,name,desc,price){
document.querySelectorAll(".item").forEach(i=>i.classList.remove("active"));
el.classList.add("active");

data.service=name;
data.price=price;

document.getElementById("service").value=name;
document.getElementById("price").value=price;
update();
}

function setDuration(el,dur,extra){
document.querySelectorAll(".duration").forEach(d=>d.classList.remove("active"));
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
}

function toggleAddons(){
document.getElementById("addonBox").style.display="flex";
}

function update(){
console.log(data);
}

</script>

</body>
</html>