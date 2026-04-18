<?php
session_start();
include '../includes/db.php';

$services = mysqli_query($conn,"
SELECT * FROM services
ORDER BY id DESC
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Services</title>
<link rel="stylesheet" href="../assets/css/admin.css">
</head>

<body>

<?php include __DIR__ . '/includes/sidebar.php'; ?>

<div class="main">

<h1>Services Management</h1>

<table class="table">

<tr>
<th>ID</th>
<th>Name</th>
<th>Price</th>
<th>Duration</th>
<th>Category</th>
<th>Description</th>
</tr>

<?php while($row = mysqli_fetch_assoc($services)) { ?>

<tr>
<td><?= $row['id'] ?></td>
<td><?= $row['service_name'] ?></td>
<td>₱<?= number_format($row['price'],2) ?></td>
<td><?= $row['duration'] ?></td>
<td><?= $row['category'] ?></td>
<td><?= $row['description'] ?></td>
</tr>

<?php } ?>

</table>

</div>

</body>
</html>