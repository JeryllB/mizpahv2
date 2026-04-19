<?php
$current = basename($_SERVER['PHP_SELF']);
?>

<div class="sidebar">

<div class="brand">
    <img src="../assets/images/logo.png" alt="Logo">
    <h2>MIZPAH ADMIN</h2>
    <span>Wellness Spa Panel</span>
</div>

<a href="dashboard.php" class="<?= $current=='dashboard.php' ? 'active':'' ?>">Dashboard</a>

<a href="bookings.php" class="<?= $current=='bookings.php' ? 'active':'' ?>">Bookings</a>

<a href="services.php" class="<?= $current=='services.php' ? 'active':'' ?>">Services</a>

<a href="users.php" class="<?= $current=='users.php' ? 'active':'' ?>">Users</a>

<a href="calendar.php" class="<?= $current=='calendar.php' ? 'active':'' ?>">Calendar</a>

<a href="reports.php" class="<?= $current=='reports.php' ? 'active':'' ?>">Reports</a>

<a href="logout.php" class="logout">Logout</a>

</div>