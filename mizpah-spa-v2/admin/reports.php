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
WHERE status IN ('Completed','Confirmed')
AND price > 0
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
WHERE status IN ('Completed','Confirmed')
AND price > 0
GROUP BY payment_method
");

/* ================= SERVICES ================= */
$services = mysqli_query($conn,"
SELECT service, COUNT(*) as total
FROM bookings
WHERE service != ''
GROUP BY service
ORDER BY total DESC
LIMIT 5
");

/* ================= DAILY GRAPH ================= */
$labels = [];
$data = [];

$daily = mysqli_query($conn,"
SELECT booking_date,
SUM(price * pax) as total
FROM bookings
WHERE status IN ('Completed','Confirmed')
AND price > 0
GROUP BY booking_date
ORDER BY booking_date ASC
");

if($daily){
    while($row=mysqli_fetch_assoc($daily)){
        $labels[] = date("M d", strtotime($row['booking_date']));
        $data[] = (float)$row['total'];
    }
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

/* SIDEBAR SAFE */
.main{
    margin-left:250px;
    padding:25px;
}

/* GLASS BLACK THEME */
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

h2{
    color:#D6C29C;
    margin-bottom:15px;
}

.title{
    color:#D6C29C;
    font-size:14px;
    margin-bottom:8px;
}

.value{
    font-size:28px;
    font-weight:bold;
}

p{
    color:#ddd;
    font-size:14px;
    margin:8px 0;
}

#chart{
    width:100% !important;
    height:320px !important;
}
</style>

</head>
<body>

<?php include 'includes/sidebar.php'; ?>

<div class="main">

<h2>Sales Report</h2>

<!-- TOTAL -->
<div class="card">
    <div class="title">Total Revenue</div>
    <div class="value">₱<?= number_format($totalSales,2) ?></div>
</div>

<div class="grid">

<!-- PAYMENT -->
<div class="card">
<div class="title">Payment Breakdown</div>

<?php if($payment && mysqli_num_rows($payment)>0): ?>
    <?php while($p=mysqli_fetch_assoc($payment)): ?>
        <p><?= $p['payment_method'] ?> — ₱<?= number_format($p['total'],2) ?></p>
    <?php endwhile; ?>
<?php else: ?>
<p>No payment records</p>
<?php endif; ?>

</div>

<!-- SERVICES -->
<div class="card">
<div class="title">Top Services</div>

<?php if($services && mysqli_num_rows($services)>0): ?>
    <?php while($s=mysqli_fetch_assoc($services)): ?>
        <p><?= $s['service'] ?> — <?= $s['total'] ?> bookings</p>
    <?php endwhile; ?>
<?php else: ?>
<p>No service records</p>
<?php endif; ?>

</div>

</div>

<!-- GRAPH -->
<div class="card">
<div class="title">Daily Sales</div>
<canvas id="chart"></canvas>
</div>

</div>

<script>
const labels = <?= json_encode($labels) ?>;
const data = <?= json_encode($data) ?>;

const ctx = document.getElementById("chart");

if(labels.length > 0 && data.length > 0){

new Chart(ctx,{
    type:'line',
    data:{
        labels:labels,
        datasets:[{
            label:'Sales ₱',
            data:data,
            borderColor:'#D6C29C',
            backgroundColor:'rgba(214,194,156,0.12)',
            fill:true,
            tension:0.4,
            borderWidth:2,
            pointRadius:4,
            pointHoverRadius:6
        }]
    },
    options:{
        responsive:true,
        maintainAspectRatio:false,
        plugins:{
            legend:{
                labels:{color:'#fff'}
            }
        },
        scales:{
            x:{
                ticks:{color:'#aaa'},
                grid:{color:'rgba(255,255,255,0.05)'}
            },
            y:{
                beginAtZero:true,
                ticks:{color:'#aaa'},
                grid:{color:'rgba(255,255,255,0.05)'}
            }
        }
    }
});

}else{
    ctx.outerHTML = "<p style='color:#aaa;'>No sales data available for graph.</p>";
}
</script>

</body>
</html>