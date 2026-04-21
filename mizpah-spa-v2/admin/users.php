<?php
session_start();
include '../includes/db.php';

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin'){
    header("Location: ../login.php");
    exit;
}

$query = mysqli_query($conn, "
    SELECT 
        u.id,
        u.name,
        u.email,
        u.role,
        u.created_at,
        COUNT(b.id) AS total_bookings,
        MAX(b.booking_date) AS last_booking
    FROM users u
    LEFT JOIN bookings b ON u.id = b.user_id
    GROUP BY u.id
    ORDER BY u.id DESC
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Users</title>
<link rel="stylesheet" href="../assets/css/admin.css">
</head>

<body>

<?php include __DIR__ . '/includes/sidebar.php'; ?>

<div class="main">

<h2>System Users</h2>

<table border="1" cellpadding="10" cellspacing="0" style="width:100%; margin-top:20px; color:#fff;">
    <tr>
        <th>Name</th>
        <th>Email</th>
        <th>Role</th>
        <th>Total Bookings</th>
        <th>Last Booking</th>
        <th>Created</th>
    </tr>

    <?php while($row = mysqli_fetch_assoc($query)){ ?>
    <tr>
        <td><?= htmlspecialchars($row['name']) ?></td>
        <td><?= htmlspecialchars($row['email']) ?></td>
        <td><?= $row['role'] ?></td>
        <td><?= $row['total_bookings'] ?></td>
        <td><?= $row['last_booking'] ?? 'No booking' ?></td>
        <td><?= $row['created_at'] ?></td>
    </tr>
    <?php } ?>

</table>

</div>

</body>
</html>