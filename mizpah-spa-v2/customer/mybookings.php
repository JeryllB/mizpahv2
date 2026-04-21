<?php
session_start();
include '../includes/db.php';

if(!isset($_SESSION['user_id'])){
header("Location: ../login.php");
exit;
}

$user_id = $_SESSION['user_id'];

$data = mysqli_query($conn,"
SELECT b.*, t.name as therapist
FROM bookings b
LEFT JOIN therapists t ON b.therapist_id=t.id
WHERE b.user_id='$user_id'
ORDER BY b.id DESC
");
?>

<!DOCTYPE html>
<html>
<head>
<title>My Bookings</title>
<style>
body{background:#0b0b0b;color:#fff;font-family:Poppins}
.card{background:#161616;margin:15px;padding:15px;border-radius:14px;border:1px solid rgba(214,194,156,.15)}
.status{color:#D6C29C}
</style>
</head>

<body>

<h2 style="color:#D6C29C;text-align:center">My Bookings</h2>

<?php while($r=mysqli_fetch_assoc($data)){ ?>
<div class="card">
<b><?= $r['service'] ?></b><br>
<?= $r['booking_date'] ?> - <?= $r['booking_time'] ?><br>
Therapist: <?= $r['therapist'] ?? 'No Preference' ?><br>
Status: <span class="status"><?= $r['status'] ?></span>
</div>
<?php } ?>

</body>
</html>