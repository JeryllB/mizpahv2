<?php
session_start();
include '../includes/db.php';

if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
    exit;
}

/* ================= TOTAL ================= */
$totalSales = 0;

$res = mysqli_query($conn,"
SELECT SUM(price * pax) as total
FROM bookings
WHERE status='Completed'
");

if($res){
    $r = mysqli_fetch_assoc($res);
    $totalSales = $r['total'] ?? 0;
}

/* ================= PAYMENT ================= */
$payment = mysqli_query($conn,"
SELECT payment_method,
SUM(price * pax) as total
FROM bookings
WHERE status='Completed'
GROUP BY payment_method
");

/* ================= SERVICES ================= */
$services = mysqli_query($conn,"
SELECT service, COUNT(*) as total
FROM bookings
GROUP BY service
ORDER BY total DESC
LIMIT 5
");

/* ================= DAILY ================= */
$labels = [];
$data = [];

$daily = mysqli_query($conn,"
SELECT booking_date,
SUM(price * pax) as total
FROM bookings
WHERE status='Completed'
GROUP BY booking_date
ORDER BY booking_date ASC
");

while($row=mysqli_fetch_assoc($daily)){
    $labels[] = date("M d", strtotime($row['booking_date']));
    $data[] = $row['total'];
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Reports</title>

<link rel="stylesheet" href="../assets/css/admin.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
body{
    margin:0;
    font-family:Poppins;
    background:#0b0b0b;
    color:#fff;
}

/* IMPORTANT: DO NOT TOUCH SIDEBAR */
.main{
    margin-left:250px;
    padding:25px;
}

/* SIMPLE GLASS */
.card{
    background:#161616;
    border:1px solid #222;
    border-radius:14px;
    padding:20px;
    margin-bottom:15px;
}

.grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(250px,1fr));
    gap:15px;
}

h2{color:#D6C29C;}
.title{color:#D6C29C;font-size:14px;}
.value{font-size:28px;font-weight:bold;}

p{color:#ddd;font-size:14px;}

#chart{
    width:100%!important;
    height:300px!important;
}
</style>

</head>

<body>

<?php include 'includes/sidebar.php'; ?>

<div class="main">

<h2>Sales Report</h2>

<div class="card">
    <div class="title">Total Revenue</div>
    <div class="value">₱<?= number_format($totalSales,2) ?></div>
</div>

<div class="grid">

<div class="card">
<div class="title">Payment Breakdown</div>

<?php while($p=mysqli_fetch_assoc($payment)): ?>
<p><?= $p['payment_method'] ?> — ₱<?= number_format($p['total'],2) ?></p>
<?php endwhile; ?>

</div>

<div class="card">
<div class="title">Top Services</div>

<?php while($s=mysqli_fetch_assoc($services)): ?>
<p><?= $s['service'] ?> — <?= $s['total'] ?></p>
<?php endwhile; ?>

</div>

</div>

<div class="card">
<div class="title">Daily Sales</div>
<canvas id="chart"></canvas>
</div>

</div>

<script>
const labels = <?= json_encode($labels) ?>;
const data = <?= json_encode($data) ?>;

new Chart(document.getElementById("chart"),{
    type:'line',
    data:{
        labels:labels,
        datasets:[{
            label:'Sales',
            data:data,
            borderColor:'#D6C29C',
            backgroundColor:'rgba(214,194,156,0.15)',
            fill:true,
            tension:0.4
        }]
    },
    options:{
        plugins:{
            legend:{labels:{color:'#fff'}}
        },
        scales:{
            x:{ticks:{color:'#aaa'}},
            y:{ticks:{color:'#aaa'},beginAtZero:true}
        }
    }
});
</script>

</body>
</html>