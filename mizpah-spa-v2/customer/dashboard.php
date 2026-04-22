<?php
session_start();
include '../includes/db.php';

if(!isset($_SESSION['user_id'])){
header("Location: ../login.php");
exit;
}

$user_id = $_SESSION['user_id'];
$name = $_SESSION['name'];

$total = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) total FROM bookings WHERE user_id='$user_id'"))['total'] ?? 0;
$pending = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) total FROM bookings WHERE user_id='$user_id' AND status='Pending'"))['total'] ?? 0;
$approved = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) total FROM bookings WHERE user_id='$user_id' AND status='Approved'"))['total'] ?? 0;
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Dashboard</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:Poppins}
body{background:#0b0b0b;color:#fff}

header{
display:flex;justify-content:space-between;align-items:center;
padding:18px 8%;background:#111;border-bottom:1px solid rgba(214,194,156,.15)
}
.logo{color:#D6C29C;font-size:22px;font-weight:700}
nav a{color:#fff;text-decoration:none;margin-left:14px;font-size:13px;opacity:.85}
nav a:hover{color:#D6C29C}

.wrap{padding:35px 8%}

.hero{
background:linear-gradient(135deg,#161616,#0d0d0d);
padding:35px;border-radius:18px;
border:1px solid rgba(214,194,156,.15)
}
.hero h1{color:#D6C29C;font-size:34px}
.hero p{color:#aaa;margin-top:8px}

.grid{
display:grid;
grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
gap:15px;margin-top:25px
}

.card{
background:#161616;
padding:22px;border-radius:16px;
border:1px solid rgba(214,194,156,.15)
}

.card h3{font-size:13px;color:#aaa}
.card h2{font-size:34px;color:#D6C29C;margin-top:8px}

.btn{
display:block;text-align:center;
padding:14px;margin-top:20px;
background:#D6C29C;color:#111;
text-decoration:none;border-radius:12px;
font-weight:700
}
</style>
</head>

<body>

<header>
<div class="logo">Mizpah Wellness Spa</div>
<nav>
<a href="dashboard.php">Dashboard</a>
<a href="booking.php">Book</a>
<a href="mybookings.php">My Bookings</a>
<a href="notifications.php">Notifications</a>
<a href="profile.php">Profile</a>
<a href="logout.php">Logout</a>
</nav>
</header>

<div class="wrap">

<div class="hero">
<h1>Welcome, <?= $name ?></h1>
<p>Your relaxation journey starts here.</p>
</div>

<div class="grid">
<div class="card"><h3>Total Bookings</h3><h2><?= $total ?></h2></div>
<div class="card"><h3>Pending</h3><h2><?= $pending ?></h2></div>
<div class="card"><h3>Approved</h3><h2><?= $approved ?></h2></div>
</div>

<a class="btn" href="booking.php">Book Now</a>

</div>

</body>
</html>