<?php
session_start();
include '../includes/db.php';

/* =========================
   REVENUE
========================= */

// DAILY REVENUE
$dailyQuery = mysqli_query($conn, "
  SELECT SUM(price) as total 
  FROM bookings 
  WHERE DATE(booking_date) = CURDATE()
");
$daily = mysqli_fetch_assoc($dailyQuery)['total'] ?? 0;

// MONTHLY REVENUE
$monthlyQuery = mysqli_query($conn, "
  SELECT SUM(price) as total 
  FROM bookings 
  WHERE MONTH(booking_date) = MONTH(CURDATE())
");
$monthly = mysqli_fetch_assoc($monthlyQuery)['total'] ?? 0;

/* =========================
   BOOKING STATUS
========================= */

$statusQuery = mysqli_query($conn, "
  SELECT status, COUNT(*) as total
  FROM bookings
  GROUP BY status
");

/* =========================
   TOP SERVICES
========================= */

$serviceQuery = mysqli_query($conn, "
  SELECT service, COUNT(*) as total
  FROM bookings
  GROUP BY service
  ORDER BY total DESC
  LIMIT 5
");
?>

<!DOCTYPE html>
<html>
<head>
  <title>Reports - MIS Admin</title>
  <link rel="stylesheet" href="../assets/css/admin.css">
</head>

<body>

<?php include 'includes/sidebar.php'; ?>
</div>

<div class="main">

  <h1>Reports & Analytics</h1>
  <p>Business Overview Dashboard</p>

  <!-- KPI REPORT CARDS -->
  <div class="cards">

    <div class="card">
      <h3>Daily Revenue</h3>
      <p>₱<?php echo number_format($daily ?? 0, 2); ?></p>
    </div>

    <div class="card">
      <h3>Monthly Revenue</h3>
      <p>₱<?php echo number_format($monthly ?? 0, 2); ?></p>
    </div>

  </div>

  <!-- BOOKING STATUS -->
  <h2 style="margin-top:30px;">Booking Status</h2>

  <table class="table">
    <tr>
      <th>Status</th>
      <th>Total</th>
    </tr>

    <?php while($row = mysqli_fetch_assoc($statusQuery)) { ?>
      <tr>
        <td><?php echo $row['status']; ?></td>
        <td><?php echo $row['total']; ?></td>
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

    <?php while($row = mysqli_fetch_assoc($serviceQuery)) { ?>
      <tr>
        <td><?php echo $row['service']; ?></td>
        <td><?php echo $row['total']; ?></td>
      </tr>
    <?php } ?>

  </table>

</div>

</body>
</html>