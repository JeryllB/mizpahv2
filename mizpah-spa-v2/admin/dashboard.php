<?php
session_start();
include '../includes/db.php';

if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
    exit;
}

/* KPI */
$bookings = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) total FROM bookings"))['total'] ?? 0;

$revenue = mysqli_fetch_assoc(mysqli_query($conn,"SELECT SUM(price) total FROM bookings"))['total'] ?? 0;

$pending = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) total FROM bookings WHERE status='Pending'"))['total'] ?? 0;

$completed = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) total FROM bookings WHERE status='Completed'"))['total'] ?? 0;

/* GRAPH DATA (LAST 7 DAYS) */
$graph = mysqli_query($conn,"
SELECT DATE(booking_date) as date, COUNT(*) as total
FROM bookings
GROUP BY DATE(booking_date)
ORDER BY date DESC
LIMIT 7
");

$labels = [];
$data = [];

while($row = mysqli_fetch_assoc($graph)){
    $labels[] = $row['date'];
    $data[] = $row['total'];
}

$labels = array_reverse($labels);
$data = array_reverse($data);
?>

<!DOCTYPE html>
<html>
<head>
<title>Dashboard</title>

<link rel="stylesheet" href="../assets/css/admin.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
.chart-box{
  background:white;
  padding:20px;
  border-radius:12px;
  margin-top:20px;
}
</style>

</head>

<body>

<?php include __DIR__ . '/includes/sidebar.php'; ?>

<div class="main">

<h1>Admin Dashboard</h1>

<!-- KPI -->
<div class="cards">

<div class="card"><h3>Bookings</h3><p><?= $bookings ?></p></div>
<div class="card"><h3>Pending</h3><p><?= $pending ?></p></div>
<div class="card"><h3>Completed</h3><p><?= $completed ?></p></div>
<div class="card"><h3>Revenue</h3><p>₱<?= number_format($revenue,2) ?></p></div>

</div>

<!-- GRAPH -->
<div class="chart-box">
<canvas id="chart"></canvas>
</div>

</div>

<script>
const ctx = document.getElementById('chart');

new Chart(ctx, {
    type: 'line',
    data: {
        labels: <?= json_encode($labels) ?>,
        datasets: [{
            label: 'Bookings (Last 7 Days)',
            data: <?= json_encode($data) ?>,
            borderColor: '#C89B6A',
            backgroundColor: 'rgba(200,155,106,0.2)',
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                display: true
            }
        }
    }
});
</script>

</body>
</html>