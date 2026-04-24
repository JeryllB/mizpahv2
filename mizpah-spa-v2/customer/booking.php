<?php
session_start();
include '../includes/db.php';

if (!$conn) {
    die("Database connection failed");
}

/* ================= INSERT BOOKING ================= */
if (isset($_POST['submit_booking'])) {

    $user_id = $_SESSION['user_id'] ?? null;

    $customer_name = mysqli_real_escape_string($conn, $_POST['customer_name']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);

    $service_id = (int)$_POST['service_id'];
    $service = mysqli_real_escape_string($conn, $_POST['service']);

    $duration = mysqli_real_escape_string($conn, $_POST['duration']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);

    $booking_date = mysqli_real_escape_string($conn, $_POST['booking_date']);
    $booking_time = mysqli_real_escape_string($conn, $_POST['booking_time']);

    $pax = mysqli_real_escape_string($conn, $_POST['pax']);
    $notes = mysqli_real_escape_string($conn, $_POST['notes']);
    $payment_method = mysqli_real_escape_string($conn, $_POST['payment_method']);

    $therapist_id = (int)($_POST['therapist_id'] ?? 0);

    $addons = $_POST['addons'] ?? "";
    if (is_array($addons)) {
        $addons = implode(", ", $addons);
    }

    mysqli_query($conn,"
        INSERT INTO bookings (
            user_id, customer_name, phone,
            service, service_id,
            duration, price,
            booking_date, booking_time,
            pax, addons, notes,
            payment_method, therapist_id, status
        )
        VALUES (
            '$user_id','$customer_name','$phone',
            '$service','$service_id',
            '$duration','$price',
            '$booking_date','$booking_time',
            '$pax','$addons','$notes',
            '$payment_method','$therapist_id','Pending'
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

<link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">

<style>
body{
margin:0;
font-family:Poppins;
background:#0b0b0b;
color:#fff;
}

.wrapper{
max-width:900px;
margin:auto;
padding:20px;
}

h3{
color:#D6C29C;
text-align:center;
margin-top:25px;
}

.item{
background:#111;
border:1px solid #333;
padding:14px;
margin-top:10px;
border-radius:10px;
cursor:pointer;
}

.item:hover{border-color:#D6C29C;}

.item small{
display:block;
color:#aaa;
font-size:12px;
margin-top:4px;
}

input,select,textarea{
width:100%;
padding:10px;
margin-top:6px;
background:#111;
border:1px solid #333;
color:#fff;
border-radius:6px;
}

button{
width:100%;
padding:12px;
background:#D6C29C;
border:none;
font-weight:600;
margin-top:15px;
cursor:pointer;
}
</style>
</head>

<body>

<div class="wrapper">

<form method="POST">

<!-- hidden -->
<input type="hidden" name="service" id="service">
<input type="hidden" name="service_id" id="service_id">
<input type="hidden" name="duration" id="duration">
<input type="hidden" name="price" id="price">
<input type="hidden" name="addons" id="addons">
<input type="hidden" name="booking_time" id="booking_time">

<!-- ================= SERVICES (NO DUPLICATES FIX) ================= -->
<h3>Services</h3>

<?php
$services = mysqli_query($conn,"
SELECT service_name, MIN(id) as id
FROM services
WHERE category='Massage'
GROUP BY service_name
");

while($s=mysqli_fetch_assoc($services)):
?>
<div class="item"
onclick="loadDurations(<?= $s['id'] ?>,'<?= $s['service_name'] ?>')">

<b><?= $s['service_name'] ?></b>

</div>
<?php endwhile; ?>

<!-- ================= DURATIONS ================= -->
<h3>Choose Duration</h3>
<div id="durationBox"></div>

<!-- ================= PACKAGES ================= -->
<h3>Packages</h3>

<div class="item" onclick="setPackage('Bronze Package',1600)">
<b>Bronze Package - ₱1,600</b>
<small>Basic full spa experience</small>
</div>

<div class="item" onclick="setPackage('Silver Package',1800)">
<b>Silver Package - ₱1,800</b>
<small>Enhanced relaxation package</small>
</div>

<div class="item" onclick="setPackage('Gold Package',2000)">
<b>Gold Package - ₱2,000</b>
<small>Premium luxury spa experience</small>
</div>

<!-- ================= THERAPIST ================= -->
<h3>Therapist (Optional)</h3>

<select name="therapist_id">
<option value="0">No Preference</option>

<?php
$t = mysqli_query($conn,"SELECT * FROM therapists WHERE status='Active'");
while($r=mysqli_fetch_assoc($t)):
?>
<option value="<?= $r['id'] ?>">
<?= $r['name'] ?> - <?= $r['specialty'] ?>
</option>
<?php endwhile; ?>
</select>

<!-- ================= ADD ONS ================= -->
<h3>Add-ons</h3>

<?php
$a = mysqli_query($conn,"SELECT * FROM services WHERE category='Add-on'");
while($ad=mysqli_fetch_assoc($a)):
?>
<div class="item"
onclick="toggleAddon('<?= $ad['service_name'] ?>',<?= $ad['price'] ?>)">

<b><?= $ad['service_name'] ?></b>
<small><?= $ad['description'] ?></small>

</div>
<?php endwhile; ?>

<!-- ================= DETAILS ================= -->
<h3>Details</h3>

<input name="customer_name" placeholder="Name" required>
<input name="phone" placeholder="Phone" required>

<select name="pax">
<option>1</option><option>2</option><option>3</option>
<option>4</option><option>5</option><option>6</option>
</select>

<select name="payment_method">
<option>Cash</option>
<option>GCash</option>
</select>

<textarea name="notes" placeholder="Notes"></textarea>

<!-- ================= SCHEDULE ================= -->
<h3>Schedule</h3>

<input type="date" name="booking_date" required>

<div class="item" onclick="setTime('15:00')">3 PM</div>
<div class="item" onclick="setTime('16:00')">4 PM</div>
<div class="item" onclick="setTime('17:00')">5 PM</div>
<div class="item" onclick="setTime('18:00')">6 PM</div>

<button type="submit" name="submit_booking">CONFIRM BOOKING</button>

</form>

</div>

<script>

let addons = [];
let addonTotal = 0;
let basePrice = 0;

/* ================= LOAD DURATIONS ================= */
function loadDurations(service_id, name){

document.getElementById("service_id").value = service_id;
document.getElementById("service").value = name;

fetch("get_durations.php?service_id=" + service_id)
.then(res => res.json())
.then(data => {

let html = "";

data.forEach(d => {
html += `
<div class="item" onclick="selectDuration('${d.duration}',${d.price})">
<b>${d.duration}</b>
<small>₱${d.price}</small>
</div>`;
});

document.getElementById("durationBox").innerHTML = html;

});
}

/* ================= SELECT DURATION ================= */
function selectDuration(duration, price){
document.getElementById("duration").value = duration;
basePrice = price;
update();
}

/* ================= PACKAGE ================= */
function setPackage(name, price){
document.getElementById("duration").value = name;
basePrice = price;
update();
}

/* ================= ADDONS ================= */
function toggleAddon(name, price){

let i = addons.indexOf(name);

if(i === -1){
addons.push(name);
addonTotal += price;
}else{
addons.splice(i,1);
addonTotal -= price;
}

document.getElementById("addons").value = addons.join(", ");
update();
}

/* ================= TIME ================= */
function setTime(time){
document.getElementById("booking_time").value = time;
}

/* ================= PRICE UPDATE ================= */
function update(){
document.getElementById("price").value = basePrice + addonTotal;
}

</script>

</body>
</html>