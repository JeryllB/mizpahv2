<?php
require_once __DIR__ . '/includes/db.php';

$services = mysqli_query($conn,"SELECT * FROM services WHERE category='Massage'");
$packages = mysqli_query($conn,"SELECT * FROM services WHERE category='Package'");
$addons = mysqli_query($conn,"SELECT * FROM services WHERE category='Add-on'");
?>

<!DOCTYPE html>
<html>
<head>
<title>Book Now</title>

<style>

body{
margin:0;
font-family:Poppins;
background:#0f0f0f;
color:#fff;
}

.container{
max-width:800px;
margin:50px auto;
padding:20px;
}

/* STEPS */
.step{display:none;}
.step.active{display:block;}

/* SIMPLE CARD */
.card{
background:#1e1e1e;
padding:18px;
margin-bottom:10px;
border-radius:10px;
cursor:pointer;
border:1px solid #2a2a2a;
}

.card:hover{
border-color:#D6C29C;
}

/* HEADER */
h2{
color:#D6C29C;
margin-bottom:15px;
}

/* SMALL TEXT */
p{
font-size:13px;
color:#ccc;
margin:5px 0 0;
}

/* BUTTON */
.btn{
width:100%;
padding:12px;
border:none;
border-radius:8px;
background:#D6C29C;
color:#111;
font-weight:bold;
margin-top:15px;
cursor:pointer;
}

.skip{
background:transparent;
color:#aaa;
margin-top:10px;
border:none;
width:100%;
}

</style>
</head>

<body>

<div class="container">

<!-- STEP 1 -->
<div class="step active">

<h2>Choose Service</h2>

<?php while($row=mysqli_fetch_assoc($services)){ ?>
<div class="card" onclick="nextStep()">
<?= $row['service_name'] ?>
</div>
<?php } ?>

<?php while($p=mysqli_fetch_assoc($packages)){ ?>
<div class="card" onclick="nextStep()">
<?= $p['service_name'] ?> (Package)
</div>
<?php } ?>

</div>

<!-- STEP 2 -->
<div class="step">

<h2>Choose Duration</h2>

<div class="card" onclick="nextStep()">1 Hour</div>
<div class="card" onclick="nextStep()">1.5 Hours</div>
<div class="card" onclick="nextStep()">2 Hours</div>

</div>

<!-- STEP 3 -->
<div class="step">

<h2>Add-ons (Optional)</h2>

<?php while($a=mysqli_fetch_assoc($addons)){ ?>
<div class="card"><?= $a['service_name'] ?></div>
<?php } ?>

<button class="skip" onclick="nextStep()">Skip</button>

</div>

<!-- STEP 4 -->
<div class="step">

<h2>Schedule</h2>

<div class="card" onclick="nextStep()">Today</div>
<div class="card" onclick="nextStep()">Tomorrow</div>

</div>

<!-- STEP 5 -->
<div class="step">

<h2>Confirm Booking</h2>

<p>Review your booking and confirm</p>

<button class="btn">Confirm</button>

</div>

</div>

<script>

let step = 0;
let steps = document.querySelectorAll(".step");

function show(i){
steps.forEach(s=>s.classList.remove("active"));
steps[i].classList.add("active");
}

function nextStep(){
if(step < steps.length - 1){
step++;
show(step);
}
}

</script>

</body>
</html>