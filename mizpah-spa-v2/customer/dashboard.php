<?php
session_start();
include '../includes/db.php';

if(!isset($_SESSION['user_id'])){
header("Location: ../login.php");
exit;
}

$user_id = $_SESSION['user_id'];
$name = $_SESSION['name'];

/* STATS */
$total = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) as total 
FROM bookings 
WHERE user_id='$user_id'
"))['total'] ?? 0;

$pending = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) as total 
FROM bookings 
WHERE user_id='$user_id' AND status='Pending'
"))['total'] ?? 0;

$approved = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) as total 
FROM bookings 
WHERE user_id='$user_id' AND status='Approved'
"))['total'] ?? 0;

/* LATEST BOOKINGS */
$bookings = mysqli_query($conn,"
SELECT * FROM bookings 
WHERE user_id='$user_id'
ORDER BY booking_date DESC, booking_time DESC
LIMIT 5
");

/* NOTIF */
$notif = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) as total 
FROM notifications 
WHERE user_id='$user_id' AND is_read=0
"))['total'] ?? 0;
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

body{
background:#0b0b0b;
color:#fff;
}

/* HEADER */
header{
display:flex;
justify-content:space-between;
align-items:center;
padding:14px 8%;
background:#111;
border-bottom:1px solid #222;
position:sticky;
top:0;
}

.logo img{
height:38px;
}

/* NAV */
nav{
display:flex;
gap:16px;
}

nav a{
color:#fff;
text-decoration:none;
font-size:14px;
opacity:.7;
position:relative;
}

nav a:hover{color:#D6C29C;opacity:1}

/* DOT */
.dot{
position:absolute;
top:-4px;
right:-6px;
width:8px;
height:8px;
background:red;
border-radius:50%;
}

/* WRAP */
.wrap{
padding:30px 8%;
}

/* HERO */
.hero{
background:#161616;
padding:25px;
border-radius:14px;
border:1px solid #222;
}

.hero h1{
color:#D6C29C;
}

/* GRID */
.grid{
display:grid;
grid-template-columns:repeat(auto-fit,minmax(200px,1fr));
gap:15px;
margin-top:20px;
}

.card{
background:#161616;
padding:18px;
border-radius:12px;
border:1px solid #222;
text-align:center;
}

.card h2{
color:#D6C29C;
margin-top:6px;
}

/* BOOKINGS */
.section-title{
margin-top:35px;
color:#D6C29C;
}

.booking{
background:#161616;
padding:15px;
border-radius:12px;
border:1px solid #222;
margin-top:10px;
}

.small{
font-size:12px;
color:#aaa;
}
</style>
</head>

<body>

<header>

<div class="logo">
<img src="../assets/images/logo.png">
</div>

<nav>
<a href="dashboard.php">Home</a>
<a href="booking.php">Book</a>
<a href="mybookings.php">My Bookings</a>
<a href="notifications.php">
Notifications
<?php if($notif>0): ?>
<span class="dot"></span>
<?php endif; ?>
</a>
<a href="profile.php">Profile</a>
<a href="logout.php">Logout</a>
</nav>

</header>

<div class="wrap">

<div class="hero">
<h1>Welcome, <?= htmlspecialchars($name) ?></h1>
<p>Your booking overview</p>
</div>

<div class="grid">
<div class="card">
<h3>Total</h3>
<h2><?= $total ?></h2>
</div>

<div class="card">
<h3>Pending</h3>
<h2><?= $pending ?></h2>
</div>

<div class="card">
<h3>Approved</h3>
<h2><?= $approved ?></h2>
</div>
</div>

<!-- CURRENT BOOKINGS ONLY -->
<div class="section-title">Your Recent Bookings</div>

<?php while($b = mysqli_fetch_assoc($bookings)): ?>

<div class="booking">

<b><?= htmlspecialchars($b['service']) ?></b><br>

<span class="small">
<?= $b['booking_date'] ?> | <?= $b['booking_time'] ?>
</span><br>

<span class="small">
Status: <?= $b['status'] ?>
</span>

</div>

<?php endwhile; ?>

</div>

</body>
</html>