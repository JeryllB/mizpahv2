<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../includes/db.php';

/* =========================
   KPI DATA
========================= */

$bookingsQuery = mysqli_query($conn, "SELECT COUNT(*) as total FROM bookings");
$bookings = mysqli_fetch_assoc($bookingsQuery)['total'] ?? 0;

$pendingQuery = mysqli_query($conn, "SELECT COUNT(*) as total FROM bookings WHERE status='Pending'");
$pending = mysqli_fetch_assoc($pendingQuery)['total'] ?? 0;

$completedQuery = mysqli_query($conn, "SELECT COUNT(*) as total FROM bookings WHERE status='Completed'");
$completed = mysqli_fetch_assoc($completedQuery)['total'] ?? 0;

$revenueQuery = mysqli_query($conn, "SELECT SUM(price) as total FROM bookings");
$revenue = mysqli_fetch_assoc($revenueQuery)['total'] ?? 0;

/* =========================
   CALENDAR DATA
========================= */

$calendarQuery = mysqli_query($conn, "
  SELECT booking_date, COUNT(*) as total
  FROM bookings
  GROUP BY booking_date
");

$calendarData = [];

while($row = mysqli_fetch_assoc($calendarQuery)) {
    $calendarData[$row['booking_date']] = $row['total'];
}

/* =========================
   RECENT BOOKINGS
========================= */

$recent = mysqli_query($conn, "
    SELECT * FROM bookings 
    ORDER BY id DESC 
    LIMIT 5
");

/* =========================
   THERAPISTS
========================= */

$therapists = mysqli_query($conn, "SELECT * FROM therapists");
?>

<!DOCTYPE html>
<html>
<head>
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="../assets/css/admin.css">
</head>

<body>

<!-- SIDEBAR -->
<?php include __DIR__ . '/includes/sidebar.php'; ?>

<!-- MAIN -->
<div class="main">

  <h1>Admin Dashboard</h1>
  <p>Welcome back, Admin 👋</p>

  <!-- KPI CARDS -->
  <div class="cards">

    <div class="card">
      <h3>Bookings</h3>
      <p><?php echo $bookings; ?></p>
    </div>

    <div class="card">
      <h3>Pending</h3>
      <p><?php echo $pending; ?></p>
    </div>

    <div class="card">
      <h3>Completed</h3>
      <p><?php echo $completed; ?></p>
    </div>

    <div class="card">
      <h3>Revenue</h3>
      <p>₱<?php echo number_format($revenue ?? 0, 2); ?></p>
    </div>

  </div>

  <!-- CALENDAR -->
  <h2>Booking Calendar</h2>

  <div class="calendar-box">

    <p><strong>This Month:</strong> <?= date('F Y') ?></p>

    <div class="calendar-grid">

      <?php
      $daysInMonth = date('t');
      $month = date('m');
      $year = date('Y');

      for ($day = 1; $day <= $daysInMonth; $day++) {

          $date = $year . "-" . $month . "-" . str_pad($day, 2, "0", STR_PAD_LEFT);

          $count = $calendarData[$date] ?? 0;

          if ($count > 0) {
              echo "<div class='day booked'>$day</div>";
          } else {
              echo "<div class='day available'>$day</div>";
          }
      }
      ?>

    </div>
  </div>

  <!-- RECENT BOOKINGS -->
  <h2 style="margin-top:30px;">Recent Bookings</h2>

  <table class="table">

    <tr>
      <th>Customer</th>
      <th>Service</th>
      <th>Date</th>
      <th>Time</th>
      <th>Status</th>
    </tr>

    <?php if(mysqli_num_rows($recent) > 0): ?>
      <?php while($row = mysqli_fetch_assoc($recent)) { ?>
        <tr>
          <td><?php echo $row['customer_name']; ?></td>
          <td><?php echo $row['service']; ?></td>
          <td><?php echo $row['booking_date']; ?></td>
          <td><?php echo $row['booking_time']; ?></td>
          <td><?php echo $row['status']; ?></td>
        </tr>
      <?php } ?>
    <?php else: ?>
      <tr>
        <td colspan="5">No bookings yet</td>
      </tr>
    <?php endif; ?>

  </table>

  <!-- THERAPIST SECTION (FIXED OUTSIDE TABLE) -->
  <h2 style="margin-top:40px;">Therapist Overview</h2>

  <div class="cards">

    <?php while($t = mysqli_fetch_assoc($therapists)) { ?>

      <?php
        $tid = $t['id'];

        $countQuery = mysqli_query($conn, "
            SELECT COUNT(*) as total 
            FROM bookings 
            WHERE therapist_id = $tid
        ");

        $served = mysqli_fetch_assoc($countQuery)['total'] ?? 0;
      ?>

      <div class="card">
          <h3><?php echo $t['name']; ?></h3>
          <p><?php echo $t['specialization']; ?></p>
          <p><strong>Clients Served:</strong> <?php echo $served; ?></p>
          <p>Status: <?php echo $t['status']; ?></p>
      </div>

    <?php } ?>

  </div>

</div>

</body>
</html>