<?php
session_start();
include '../includes/db.php';

if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
    exit;
}

/* TOTAL BOOKINGS */
$totalBookings = mysqli_fetch_assoc(
mysqli_query($conn,"SELECT COUNT(*) as total FROM bookings")
)['total'];

/* TOTAL USERS */
$totalUsers = mysqli_fetch_assoc(
mysqli_query($conn,"SELECT COUNT(*) as total FROM users WHERE role='customer'")
)['total'];

/* TEMP REVENUE (estimate muna habang wala payments table) */
$totalRevenue = mysqli_fetch_assoc(
mysqli_query($conn,"SELECT COUNT(*) * 750 as total FROM bookings WHERE status='Completed'")
)['total'];

/* MONTHLY GRAPH */
$monthly = mysqli_query($conn,"
SELECT MONTH(booking_date) as month, COUNT(*) as total
FROM bookings
GROUP BY MONTH(booking_date)
ORDER BY MONTH(booking_date)
");

$labels = [];
$data = [];

while($row=mysqli_fetch_assoc($monthly)){
    $labels[] = date("M", mktime(0,0,0,$row['month'],1));
    $data[] = $row['total'];
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Reports</title>
<link rel="stylesheet" href="../assets/css/admin.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<?php include __DIR__.'/includes/sidebar.php'; ?>

<div class="main">

<h1>Reports Dashboard</h1>
<p>Analytics overview</p>

<div class="cards">

<div class="card">
<h3>Total Bookings</h3>
<p><?= $totalBookings ?></p>
</div>

<div class="card">
<h3>Total Customers</h3>
<p><?= $totalUsers ?></p>
</div>

<div class="card">
<h3>Estimated Revenue</h3>
<p>₱<?= number_format($totalRevenue,2) ?></p>
</div>

</div>

<div class="report-box">
<h2>Monthly Bookings</h2>
<canvas id="myChart"></canvas>
</div>

</div>

<script>
const ctx = document.getElementById('myChart');

new Chart(ctx,{
type:'bar',
data:{
labels: <?= json_encode($labels) ?>,
datasets:[{
label:'Bookings',
data: <?= json_encode($data) ?>,
backgroundColor:'#D6C29C',
borderRadius:8
}]
},
options:{
responsive:true,
plugins:{
legend:{
labels:{ color:'#F8F5F0' }
}
},
scales:{
x:{
ticks:{ color:'#F8F5F0' },
grid:{ color:'#2A2A2A' }
},
y:{
ticks:{ color:'#F8F5F0' },
grid:{ color:'#2A2A2A' },
beginAtZero:true
}
}
}
});
</script>

</body>
</html>