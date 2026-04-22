<?php
session_start();

$data = $_SESSION['last_booking'] ?? null;

$name = $data['name'] ?? 'Guest';
$service = $data['service'] ?? 'Service';
$date = $data['date'] ?? '';
$time = $data['time'] ?? '';
$price = $data['price'] ?? '';
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Booking Success</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&family=Playfair+Display:wght@600&display=swap" rel="stylesheet">

<style>
body{
margin:0;
background:#0b0b0b;
color:#fff;
font-family:Poppins;
}

.container{
height:100vh;
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
font-size:32px;
}

.box{
margin-top:20px;
background:#161616;
padding:20px;
border-radius:12px;
border:1px solid #333;
max-width:400px;
width:100%;
text-align:left;
}

.box p{
margin:8px 0;
font-size:14px;
color:#ccc;
}

.btn{
margin-top:20px;
background:#D6C29C;
color:#000;
padding:12px 20px;
border:none;
border-radius:8px;
text-decoration:none;
display:inline-block;
font-weight:600;
}

.small{
margin-top:10px;
font-size:12px;
color:#777;
}
</style>
</head>

<body>

<div class="container">

<h1>Booking Confirmed </h1>

<div class="box">
<p><b>Name:</b> <?= htmlspecialchars($name) ?></p>
<p><b>Service:</b> <?= htmlspecialchars($service) ?></p>
<p><b>Date:</b> <?= htmlspecialchars($date) ?></p>
<p><b>Time:</b> <?= htmlspecialchars($time) ?></p>
<p><b>Price:</b> ₱<?= htmlspecialchars($price) ?></p>
<p style="color:#D6C29C;">Status: Pending</p>
</div>

<a class="btn" href="index.php">Back to Home</a>

<div class="small">We will contact you shortly for confirmation.</div>

</div>

<script>
setTimeout(()=>{
window.location='index.php';
}, 5000);
</script>

</body>
</html>