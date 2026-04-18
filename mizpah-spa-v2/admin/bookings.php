<?php
session_start();
include '../includes/db.php';

if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
    exit;
}

if(isset($_POST['update_status'])){
    $id = $_POST['id'];
    $status = $_POST['status'];

    mysqli_query($conn,"UPDATE bookings SET status='$status' WHERE id=$id");

    header("Location: bookings.php");
    exit;
}

$bookings = mysqli_query($conn,"SELECT * FROM bookings ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
<title>Bookings</title>
<link rel="stylesheet" href="../assets/css/admin.css">
</head>

<body>

<?php include __DIR__ . '/includes/sidebar.php'; ?>

<div class="main">

<h1>Bookings</h1>

<table class="table">

<tr>
<th>Name</th>
<th>Service</th>
<th>Date</th>
<th>Status</th>
<th>Action</th>
</tr>

<?php while($row=mysqli_fetch_assoc($bookings)) { ?>

<tr>
<td><?= $row['customer_name'] ?></td>
<td><?= $row['service'] ?></td>
<td><?= $row['booking_date'] ?></td>

<td><?= $row['status'] ?></td>

<td>
<form method="POST">
<input type="hidden" name="id" value="<?= $row['id'] ?>">

<select name="status">
<option>Pending</option>
<option>Confirmed</option>
<option>Completed</option>
<option>Cancelled</option>
</select>

<button class="update-btn" name="update_status">Update</button>
</form>
</td>

</tr>

<?php } ?>

</table>

</div>

</body>
</html>