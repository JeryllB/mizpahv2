<?php
session_start();
include '../includes/db.php';

$therapists = mysqli_query($conn,"
SELECT * FROM therapists
ORDER BY id DESC
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Therapists</title>
<link rel="stylesheet" href="../assets/css/admin.css">
</head>

<body>

<?php include __DIR__ . '/includes/sidebar.php'; ?>

<div class="main">

<h1>Therapist Management</h1>
<p>Monitor therapist performance and availability</p>

<div class="cards">

<?php while($row = mysqli_fetch_assoc($therapists)) { ?>

<?php
$id = $row['id'];

$count = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) total
FROM bookings
WHERE therapist_id='$id'
"))['total'] ?? 0;
?>

<div class="card">

<h3><?= $row['name'] ?></h3>

<p style="font-size:14px; font-weight:normal;">
<?= $row['specialization'] ?>
</p>

<p>Clients Served: <?= $count ?></p>

<p style="font-size:14px; font-weight:normal;">
Status: <?= $row['status'] ?>
</p>

</div>

<?php } ?>

</div>

</div>

</body>
</html>