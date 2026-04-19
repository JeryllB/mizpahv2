<?php
session_start();
include '../includes/db.php';

if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
    exit;
}

/* =========================
   KPI COUNTS
========================= */
$bookings = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) total FROM bookings
"))['total'] ?? 0;

$pending = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) total FROM bookings WHERE status='Pending'
"))['total'] ?? 0;

$confirmed = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) total FROM bookings WHERE status='Confirmed'
"))['total'] ?? 0;

$completed = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) total FROM bookings WHERE status='Completed'
"))['total'] ?? 0;

/* =========================
   ESTIMATED REVENUE
========================= */
$result = mysqli_query($conn,"SELECT service,pax FROM bookings");
$revenue = 0;

while($row = mysqli_fetch_assoc($result)){

    $service = strtolower($row['service']);
    $pax = (int)($row['pax'] ?? 1);
    if($pax <= 0) $pax = 1;

    $price = 600;

    if(str_contains($service,'swedish') && str_contains($service,'1.5')) $price = 850;
    elseif(str_contains($service,'swedish') && str_contains($service,'2')) $price = 1150;

    elseif(str_contains($service,'signature') && str_contains($service,'1.5')) $price = 1100;
    elseif(str_contains($service,'signature') && str_contains($service,'2')) $price = 1450;
    elseif(str_contains($service,'signature')) $price = 750;

    elseif(str_contains($service,'thai') && str_contains($service,'1.5')) $price = 950;
    elseif(str_contains($service,'thai') && str_contains($service,'2')) $price = 1250;
    elseif(str_contains($service,'thai')) $price = 650;

    elseif(str_contains($service,'shiatsu') && str_contains($service,'1.5')) $price = 950;
    elseif(str_contains($service,'shiatsu') && str_contains($service,'2')) $price = 1250;
    elseif(str_contains($service,'shiatsu')) $price = 650;

    elseif(str_contains($service,'lymphatic') && str_contains($service,'1.5')) $price = 1250;
    elseif(str_contains($service,'lymphatic') && str_contains($service,'2')) $price = 1650;
    elseif(str_contains($service,'lymphatic')) $price = 850;

    elseif(str_contains($service,'prenatal') && str_contains($service,'1.5')) $price = 1250;
    elseif(str_contains($service,'prenatal') && str_contains($service,'2')) $price = 1650;
    elseif(str_contains($service,'prenatal')) $price = 850;

    elseif(str_contains($service,'bronze')) $price = 1600;
    elseif(str_contains($service,'silver')) $price = 1800;
    elseif(str_contains($service,'gold')) $price = 2000;

    $revenue += ($price * $pax);
}

/* =========================
   CHART DATA LAST 7 DAYS
========================= */
$graph = mysqli_query($conn,"
SELECT DATE(booking_date) as date, COUNT(*) total
FROM bookings
GROUP BY DATE(booking_date)
ORDER BY date DESC
LIMIT 7
");

$labels = [];
$data = [];

while($row = mysqli_fetch_assoc($graph)){
    $labels[] = date("M d", strtotime($row['date']));
    $data[] = $row['total'];
}

$labels = array_reverse($labels);
$data   = array_reverse($data);

/* TODAY BOOKINGS */
$today = date("Y-m-d");

$todayBookings = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) total FROM bookings
WHERE booking_date='$today'
"))['total'] ?? 0;
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Dashboard</title>

<link rel="stylesheet" href="../assets/css/admin.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
body{
background:#0b0b0b;
color:#fff;
}

/* FIX SIDEBAR OVERLAP */
.main{
margin-left:250px;
padding:35px;
min-height:100vh;
}

.page-top{
display:flex;
justify-content:space-between;
align-items:center;
gap:15px;
flex-wrap:wrap;
margin-bottom:25px;
}

.page-top h1{
margin:0;
font-size:34px;
color:#D6C29C;
}

.page-top span{
font-size:14px;
color:#aaa;
}

.cards{
display:grid;
grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
gap:16px;
margin-bottom:25px;
}

.card{
background:#161616;
padding:22px;
border-radius:16px;
border:1px solid rgba(214,194,156,.12);
}

.card h3{
font-size:14px;
color:#D6C29C;
margin-bottom:10px;
font-weight:600;
}

.card p{
font-size:30px;
font-weight:700;
margin:0;
}

.grid-2{
display:grid;
grid-template-columns:2fr 1fr;
gap:18px;
}

.box{
background:#161616;
padding:22px;
border-radius:16px;
border:1px solid rgba(214,194,156,.12);
}

.box h2{
font-size:18px;
margin-bottom:18px;
color:#D6C29C;
}

.quick-list{
display:flex;
flex-direction:column;
gap:12px;
}

.quick-item{
display:flex;
justify-content:space-between;
padding:12px 0;
border-bottom:1px solid rgba(255,255,255,.05);
font-size:14px;
}

.quick-item:last-child{
border:none;
}

canvas{
max-height:340px;
}

/* MOBILE */
@media(max-width:1000px){

.main{
margin-left:0;
padding:20px;
}

.grid-2{
grid-template-columns:1fr;
}

}
</style>

</head>

<body>

<?php include __DIR__ . '/includes/sidebar.php'; ?>

<div class="main">

<div class="page-top">
    <div>
        <h1>Dashboard</h1>
        <span>Welcome back, Admin</span>
    </div>

    <span><?= date("l, F d, Y") ?></span>
</div>

<!-- KPI -->
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
<h3>Today's Bookings</h3>
<p><?= $todayBookings ?></p>
</div>

<div class="card">
<h3>Estimated Revenue</h3>
<p>₱<?= number_format($revenue,0) ?></p>
</div>

</div>

<div class="grid-2">

<!-- CHART -->
<div class="box">
<h2>Bookings Overview</h2>
<canvas id="chart"></canvas>
</div>

<!-- SIDE SUMMARY -->
<div class="box">
<h2>Quick Summary</h2>

<div class="quick-list">

<div class="quick-item">
<span>Pending Requests</span>
<strong><?= $pending ?></strong>
</div>

<div class="quick-item">
<span>Confirmed Guests</span>
<strong><?= $confirmed ?></strong>
</div>

<div class="quick-item">
<span>Completed Sessions</span>
<strong><?= $completed ?></strong>
</div>

<div class="quick-item">
<span>Total Customers</span>
<strong><?= $bookings ?></strong>
</div>

<div class="quick-item">
<span>Revenue</span>
<strong>₱<?= number_format($revenue,0) ?></strong>
</div>

</div>

</div>

</div>

</div>

<script>
const ctx = document.getElementById('chart');

new Chart(ctx,{
type:'line',
data:{
labels: <?= json_encode($labels) ?>,
datasets:[{
label:'Bookings',
data: <?= json_encode($data) ?>,
borderColor:'#D6C29C',
backgroundColor:'rgba(214,194,156,.12)',
fill:true,
tension:.4,
pointRadius:4,
pointHoverRadius:6
}]
},
options:{
responsive:true,
plugins:{
legend:{
labels:{color:'#fff'}
}
},
scales:{
x:{
ticks:{color:'#aaa'},
grid:{color:'rgba(255,255,255,.04)'}
},
y:{
ticks:{color:'#aaa'},
grid:{color:'rgba(255,255,255,.04)'}
}
}
}
});
</script>

</body>
</html>