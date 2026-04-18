<?php
session_start();
include '../includes/db.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

/* SECURITY CHECK */
if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
    exit;
}

/* FILTER */
$from = $_GET['from'] ?? '';
$to = $_GET['to'] ?? '';

$where = "";

if(!empty($from) && !empty($to)){
    $where = "WHERE booking_date BETWEEN '$from' AND '$to'";
}

/* =========================
   REVENUE DATA
========================= */

$daily = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT SUM(price) total 
FROM bookings 
WHERE DATE(booking_date) = CURDATE()
"))['total'] ?? 0;

$monthly = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT SUM(price) total 
FROM bookings 
WHERE MONTH(booking_date) = MONTH(CURDATE())
"))['total'] ?? 0;

$totalRevenue = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT SUM(price) total 
FROM bookings
"))['total'] ?? 0;

/* =========================
   STATUS COUNT
========================= */

$status = mysqli_query($conn,"
SELECT status, COUNT(*) as total
FROM bookings
GROUP BY status
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
   MONTHLY BOOKINGS
========================= */

$monthlyBookings = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) total
FROM bookings
WHERE MONTH(booking_date) = MONTH(CURDATE())
"))['total'] ?? 0;
?>

<!DOCTYPE html>
<html>
<head>
<title>Reports</title>

<link rel="stylesheet" href="../assets/css/admin.css">

<style>
:root {
  --dark: #4B2E2A;
  --brown: #C89B6A;
  --beige: #f5f2ee;
}

body {
  background: var(--beige);
}

/* CARDS */
.cards {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
  gap: 15px;
  margin-bottom: 20px;
}

.card {
  background: white;
  padding: 20px;
  border-radius: 12px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.06);
}

/* TABLE */
.table {
  width: 100%;
  background: white;
  border-collapse: collapse;
  border-radius: 10px;
  overflow: hidden;
  margin-top: 10px;
}

.table th {
  background: var(--dark);
  color: white;
  padding: 12px;
}

.table td {
  padding: 12px;
  border-bottom: 1px solid #eee;
}

/* BUTTONS */
button {
  padding: 8px 12px;
  background: #C89B6A;
  border: none;
  color: white;
  border-radius: 6px;
  cursor: pointer;
}

input {
  padding: 6px;
  margin-right: 5px;
}

/* PRINT */
@media print {
  button, form {
    display: none;
  }
}
</style>

</head>

<body>

<?php include __DIR__ . '/includes/sidebar.php'; ?>

<div class="main">

<h1>Reports & Analytics</h1>
<p>Business performance overview</p>

<!-- FILTER + PRINT -->
<form method="GET" style="margin-bottom:10px;">
  <input type="date" name="from" value="<?= $from ?>">
  <input type="date" name="to" value="<?= $to ?>">
  <button type="submit">Filter</button>
</form>

<button onclick="window.print()">Print Report</button>

<!-- KPI CARDS -->
<div class="cards">

  <div class="card">
    <h3>Daily Revenue</h3>
    <p>₱<?= number_format($daily,2) ?></p>
  </div>

  <div class="card">
    <h3>Monthly Revenue</h3>
    <p>₱<?= number_format($monthly,2) ?></p>
  </div>

  <div class="card">
    <h3>Total Revenue</h3>
    <p>₱<?= number_format($totalRevenue,2) ?></p>
  </div>

  <div class="card">
    <h3>Monthly Bookings</h3>
    <p><?= $monthlyBookings ?></p>
  </div>

</div>

<!-- STATUS -->
<h2>Booking Status</h2>

<table class="table">

<tr>
<th>Status</th>
<th>Total</th>
</tr>

<?php while($row = mysqli_fetch_assoc($status)) { ?>

<tr>
<td><?= $row['status'] ?></td>
<td><?= $row['total'] ?></td>
</tr>

<?php } ?>

</table>

<!-- TOP SERVICES -->
<h2 style="margin-top:30px;">Top Services</h2>

<table class="table">

<tr>
<th>Service</th>
<th>Bookings</th>
</tr>

<?php while($row = mysqli_fetch_assoc($services)) { ?>

<tr>
<td><?= $row['service'] ?></td>
<td><?= $row['total'] ?></td>
</tr>

<?php } ?>

</table>

</div>

</body>
</html>