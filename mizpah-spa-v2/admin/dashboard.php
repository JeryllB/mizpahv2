<?php
session_start();
include '../includes/db.php';

if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
    exit;
}

/* ================= COUNTS ================= */
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

$today = date("Y-m-d");

$todayBookings = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) as total FROM bookings
WHERE booking_date='$today'
"))['total'] ?? 0;

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

/* ================= RESET ================= */
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    outline:none !important;
}

/* ================= BODY (BLACK CLEAN THEME) ================= */
body{
    font-family:Poppins,sans-serif;
    color:#fff;
    background:#0b0b0b;
    overflow-x:hidden;
}

/* ================= MAIN ================= */
.main{
    margin-left:250px;
    padding:35px;
    min-height:100vh;
}

/* ================= HEADER ================= */
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

/* ================= GLASS CARDS ================= */
.cards{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
    gap:15px;
}

.card{
    background:rgba(255,255,255,0.04);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border:1px solid rgba(255,255,255,0.08);
    padding:20px;
    border-radius:14px;
    transition:0.2s;
}

.card:hover{
    transform:translateY(-3px);
    background:rgba(214,194,156,0.06);
}

.card h3{
    color:#D6C29C;
    font-size:13px;
}

.card p{
    font-size:28px;
    margin-top:10px;
    font-weight:bold;
}

/* ================= QUICK PANEL ================= */
.quick{
    margin-top:25px;
    background:rgba(255,255,255,0.04);
    backdrop-filter: blur(12px);
    border:1px solid rgba(255,255,255,0.08);
    padding:20px;
    border-radius:14px;
}

.quick h2{
    color:#D6C29C;
    margin-bottom:15px;
}

.item{
    display:flex;
    justify-content:space-between;
    padding:10px 0;
    border-bottom:1px solid rgba(255,255,255,0.08);
}

.item:last-child{
    border:none;
}

/* ================= BUTTON ================= */
.link{
    display:inline-block;
    margin-top:15px;
    padding:10px 15px;
    background:#D6C29C;
    color:#111;
    text-decoration:none;
    border-radius:10px;
    font-weight:bold;
    transition:0.2s;
}

.link:hover{
    opacity:0.85;
}

/* ================= REMOVE BLUE ================= */
*{
    -webkit-tap-highlight-color: transparent;
}

a{
    color:inherit;
    text-decoration:none;
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

<a href="reports.php" class="link">View Sales Report →</a>

</div>

</div>

</body>
</html>