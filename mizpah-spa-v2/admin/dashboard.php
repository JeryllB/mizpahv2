<?php
session_start();
include '../includes/db.php';

if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
    exit;
}

/* =========================
   BASIC COUNTS ONLY
========================= */
$bookings = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) as total FROM bookings
"))['total'] ?? 0;

$pending = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) as total FROM bookings WHERE status='Pending'
"))['total'] ?? 0;

$confirmed = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) as total FROM bookings WHERE status='Confirmed'
"))['total'] ?? 0;

$completed = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) as total FROM bookings WHERE status='Completed'
"))['total'] ?? 0;

/* TODAY BOOKINGS */
$today = date("Y-m-d");

$todayBookings = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) as total FROM bookings
WHERE booking_date='$today'
"))['total'] ?? 0;

/* SIMPLE REVENUE ONLY (NO BREAKDOWN) */
$revenue = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT SUM(price * pax) as total
FROM bookings
WHERE status='Completed'
"))['total'] ?? 0;
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Dashboard</title>

<link rel="stylesheet" href="../assets/css/admin.css">

<style>
body{
background:#0b0b0b;
color:#fff;
font-family:Poppins;
}

.main{
margin-left:250px;
padding:35px;
min-height:100vh;
}

.page-top{
display:flex;
justify-content:space-between;
align-items:center;
margin-bottom:25px;
flex-wrap:wrap;
}

.page-top h1{
margin:0;
color:#D6C29C;
font-size:34px;
}

.page-top span{
color:#aaa;
font-size:14px;
}

.cards{
display:grid;
grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
gap:15px;
}

.card{
background:#161616;
padding:20px;
border-radius:14px;
border:1px solid #222;
}

.card h3{
color:#D6C29C;
font-size:13px;
margin:0;
}

.card p{
font-size:28px;
margin-top:10px;
font-weight:bold;
}

.quick{
margin-top:25px;
background:#161616;
padding:20px;
border-radius:14px;
border:1px solid #222;
}

.quick h2{
color:#D6C29C;
margin-bottom:15px;
}

.item{
display:flex;
justify-content:space-between;
padding:10px 0;
border-bottom:1px solid #222;
}

.item:last-child{
border:none;
}

.link{
display:inline-block;
margin-top:15px;
padding:10px 15px;
background:#D6C29C;
color:#111;
text-decoration:none;
border-radius:10px;
font-weight:bold;
}
</style>

</head>

<body>

<?php include 'includes/sidebar.php'; ?>

<div class="main">

<!-- HEADER -->
<div class="page-top">
    <div>
        <h1>Dashboard</h1>
        <span>Overview of Mizpah Wellness Spa</span>
    </div>

    <span><?= date("l, F d, Y") ?></span>
</div>

<!-- CARDS -->
<div class="cards">

<div class="card">
<h3>Total Bookings</h3>
<p><?= $bookings ?></p>
</div>

<div class="card">
<h3>Pending</h3>
<p><?= $pending ?></p>
</div>

<div class="card">
<h3>Confirmed</h3>
<p><?= $confirmed ?></p>
</div>

<div class="card">
<h3>Completed</h3>
<p><?= $completed ?></p>
</div>

<div class="card">
<h3>Today Bookings</h3>
<p><?= $todayBookings ?></p>
</div>

<div class="card">
<h3>Total Revenue</h3>
<p>₱<?= number_format($revenue,0) ?></p>
</div>

</div>

<!-- QUICK SUMMARY -->
<div class="quick">

<h2>Quick Summary</h2>

<div class="item">
<span>Total Bookings</span>
<strong><?= $bookings ?></strong>
</div>

<div class="item">
<span>Pending Requests</span>
<strong><?= $pending ?></strong>
</div>

<div class="item">
<span>Confirmed</span>
<strong><?= $confirmed ?></strong>
</div>

<div class="item">
<span>Completed Sessions</span>
<strong><?= $completed ?></strong>
</div>

<div class="item">
<span>Today's Bookings</span>
<strong><?= $todayBookings ?></strong>
</div>

<div class="item">
<span>Revenue</span>
<strong>₱<?= number_format($revenue,0) ?></strong>
</div>

<!-- LINK TO REPORTS -->
<a href="reports.php" class="link">View Sales Report →</a>

</div>

</div>

</body>
</html>