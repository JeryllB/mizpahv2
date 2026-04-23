<?php
session_start();

$data = $_SESSION['last_booking'] ?? [];

$name = $data['name'] ?? 'Guest';
$service = $data['service'] ?? 'Not specified';
$date = $data['date'] ?? '';
$time = $data['time'] ?? '';
$price = $data['price'] ?? 0;

$formattedDate = $date ? date("F d, Y", strtotime($date)) : 'Not set';
$formattedTime = $time ? date("g:i A", strtotime($time)) : 'Not set';
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Booking Confirmed</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&family=Playfair+Display:wght@600&display=swap" rel="stylesheet">

<style>
body{
margin:0;
background:#0b0b0b;
color:#fff;
font-family:Poppins;
}

.container{
min-height:100vh;
display:flex;
align-items:center;
justify-content:center;
flex-direction:column;
text-align:center;
padding:20px;
}

h1{
font-family:'Playfair Display';
color:#D6C29C;
font-size:34px;
margin-bottom:10px;
}

.subtitle{
color:#aaa;
font-size:14px;
margin-bottom:20px;
}

.card{
background:#161616;
border:1px solid #333;
border-radius:12px;
padding:20px;
max-width:420px;
width:100%;
text-align:left;
box-shadow:0 10px 30px rgba(0,0,0,0.4);
}

.row{
margin:10px 0;
font-size:14px;
color:#ccc;
}

.row b{
color:#fff;
}

.status{
color:#D6C29C;
font-weight:600;
margin-top:10px;
}

.btn{
margin-top:20px;
background:#D6C29C;
color:#000;
padding:12px 18px;
border:none;
border-radius:8px;
text-decoration:none;
display:inline-block;
font-weight:600;
}

.note{
margin-top:12px;
font-size:12px;
color:#777;
}

.countdown{
margin-top:10px;
font-size:12px;
color:#aaa;
}
</style>
</head>

<body>

<div class="container">

<h1>Booking Confirmed</h1>
<div class="subtitle">Thank you for choosing Mizpah Wellness Spa</div>

<div class="card">

<div class="row"><b>Name:</b> <?= htmlspecialchars($name) ?></div>
<div class="row"><b>Service:</b> <?= htmlspecialchars($service) ?></div>
<div class="row"><b>Date:</b> <?= htmlspecialchars($formattedDate) ?></div>
<div class="row"><b>Time:</b> <?= htmlspecialchars($formattedTime) ?></div>
<div class="row"><b>Total Price:</b> ₱<?= number_format($price) ?></div>

<div class="status">Status: Pending Confirmation</div>

</div>

<a class="btn" href="index.php">Back to Home</a>

<div class="note">We will contact you shortly for confirmation.</div>

<div class="countdown">
Redirecting in <span id="sec">5</span> seconds...
</div>

</div>

<script>
let sec = 5;

let timer = setInterval(()=>{
sec--;
document.getElementById("sec").innerText = sec;

if(sec <= 0){
clearInterval(timer);
window.location = "index.php";
}
},1000);
</script>

</body>
</html>