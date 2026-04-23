<?php
session_start();
include '../includes/db.php';

if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
    exit;
}

/* =========================
   TOTAL SALES (REAL)
========================= */
$totalSales = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT SUM(price * pax) as total
FROM bookings
WHERE status='Completed'
"))['total'] ?? 0;

/* =========================
   PAYMENT BREAKDOWN
========================= */
$payment = mysqli_query($conn,"
SELECT payment_method, SUM(price * pax) as total
FROM bookings
WHERE status='Completed'
GROUP BY payment_method
");

/* =========================
   TOP SERVICES
========================= */
$services = mysqli_query($conn,"
SELECT service, COUNT(*) as total
FROM bookings
GROUP BY service
ORDER BY total DESC
LIMIT 5
");

/* =========================
   DAILY SALES (LAST 7 DAYS)
========================= */
$daily = mysqli_query($conn,"
SELECT booking_date, SUM(price * pax) as total
FROM bookings
WHERE status='Completed'
GROUP BY booking_date
ORDER BY booking_date DESC
LIMIT 7
");

$labels = [];
$data = [];

while($row=mysqli_fetch_assoc($daily)){
    $labels[] = date("M d", strtotime($row['booking_date']));
    $data[] = $row['total'];
}

$labels = array_reverse($labels);
$data = array_reverse($data);
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sales Report</title>

<link rel="stylesheet" href="../assets/css/admin.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
body{
    margin:0;
    font-family:Poppins;
    background:#0b0b0b;
    color:#fff;
}

.main{
    margin-left:250px;
    padding:25px;
}

h2{
    color:#D6C29C;
}

.card{
    background:#161616;
    padding:20px;
    margin-bottom:15px;
    border-radius:14px;
    border:1px solid #222;
}

.grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(250px,1fr));
    gap:15px;
}

.title{
    color:#D6C29C;
    font-size:14px;
    margin-bottom:8px;
}

.value{
    font-size:26px;
    font-weight:bold;
}

</style>
</head>

<body>

<?php include 'includes/sidebar.php'; ?>

<div class="main">

<h2>Sales Report</h2>

<!-- TOTAL SALES -->
<div class="card">
<div class="title">Total Revenue</div>
<div class="value">₱<?= number_format($totalSales,2) ?></div>
</div>

<div class="grid">

<!-- PAYMENT METHOD -->
<div class="card">
<div class="title">Payment Breakdown</div>

<?php while($p=mysqli_fetch_assoc($payment)): ?>
<p><?= $p['payment_method'] ?>: ₱<?= number_format($p['total'],2) ?></p>
<?php endwhile; ?>

</div>

<!-- TOP SERVICES -->
<div class="card">
<div class="title">Top Services</div>

<?php while($s=mysqli_fetch_assoc($services)): ?>
<p><?= $s['service'] ?> - <?= $s['total'] ?> bookings</p>
<?php endwhile; ?>

</div>

</div>

<!-- DAILY SALES CHART -->
<div class="card">
<div class="title">Daily Sales (Last 7 Records)</div>
<canvas id="salesChart"></canvas>
</div>

</div>

<script>
new Chart(document.getElementById('salesChart'),{
type:'line',
data:{
labels: <?= json_encode($labels) ?>,
datasets:[{
label:'Sales (₱)',
data: <?= json_encode($data) ?>,
borderColor:'#D6C29C',
backgroundColor:'rgba(214,194,156,0.15)',
fill:true,
tension:0.4,
pointRadius:4
}]
},
options:{
responsive:true,
plugins:{
legend:{ labels:{ color:'#fff' } }
},
scales:{
x:{ ticks:{ color:'#aaa' } },
y:{ ticks:{ color:'#aaa' }, beginAtZero:true }
}
}
});
</script>

</body>
</html>