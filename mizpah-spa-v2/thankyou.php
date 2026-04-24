<?php
include 'includes/db.php';

$id = $_GET['id'] ?? 0;

$q = mysqli_query($conn,"SELECT * FROM bookings WHERE id='$id'");
$data = mysqli_fetch_assoc($q);
?>

<!DOCTYPE html>
<html>
<head>
<title>Booking Confirmed</title>

<style>
body{
background:#0b0b0b;
color:#fff;
font-family:Poppins;
text-align:center;
padding:50px;
}

.box{
background:#161616;
padding:25px;
border-radius:12px;
max-width:500px;
margin:auto;
border:1px solid #333;
}

h1{
color:#D6C29C;
}

span{
color:#D6C29C;
font-weight:600;
}
</style>
</head>

<body>

<div class="box">

<h1>Booking Confirmed</h1>

<p>Thank you for choosing Mizpah Wellness Spa</p>

<p><span>Name:</span> <?= $data['customer_name'] ?? 'Guest' ?></p>
<p><span>Service:</span> <?= $data['service'] ?? 'Not specified' ?></p>
<p><span>Date:</span> <?= $data['booking_date'] ?? 'Not set' ?></p>
<p><span>Time:</span> <?= $data['booking_time'] ?? 'Not set' ?></p>
<p><span>Total Price:</span> ₱<?= $data['price'] ?? 0 ?></p>
<p><span>Status:</span> <?= $data['status'] ?? 'Pending' ?></p>

<br><br>
<a href="index.php" style="color:#D6C29C;">Back to Home</a>

</div>

</body>
</html>