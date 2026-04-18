<?php
session_start();
include __DIR__ . '/../includes/db.php';

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

<style>
.action-btn{
  padding:6px 10px;
  border-radius:6px;
  font-size:12px;
  color:white;
  text-decoration:none;
  margin-right:5px;
  display:inline-block;
}

.edit-btn{
  background:#A67C52;
}

.status{
  padding:4px 8px;
  border-radius:6px;
  font-size:12px;
}

.active{
  background:#5cb85c;
  color:white;
}

.inactive{
  background:#b23b3b;
  color:white;
}
</style>

</head>

<body>

<?php include __DIR__ . '/includes/sidebar.php'; ?>

<div class="main">

<h1>Services Management</h1>
<p>Add or edit spa services</p>

<table class="table">

<tr>
<th>ID</th>
<th>Service Name</th>
<th>Price</th>
<th>Duration</th>
<th>Category</th>
<th>Status</th>
<th>Action</th>
</tr>

<?php while($row = mysqli_fetch_assoc($services)) { ?>

<tr>
<td><?= $row['id'] ?></td>
<td><?= $row['service_name'] ?></td>
<td>₱<?= number_format($row['price'],2) ?></td>
<td><?= $row['duration'] ?></td>
<td><?= $row['category'] ?></td>

<td>
<span class="status active">Active</span>
</td>

<td>

<a class="action-btn edit-btn"
href="edit_service.php?id=<?= $row['id'] ?>">
Edit
</a>

</td>

</tr>

<?php } ?>

</table>

</div>

</body>
</html>