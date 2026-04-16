<?php
include '../includes/auth.php';

if ($_SESSION['role'] != 'customer') {
    header("Location: ../login.php");
    exit();
}
?>

<h1>Customer Dashboard</h1>
<p>Welcome, <?php echo $_SESSION['name']; ?> 👋</p>

<ul>
    <li>My Bookings</li>
    <li>Book Service</li>
    <li>Notifications</li>
</ul>