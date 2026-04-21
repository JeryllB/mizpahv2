<?php
session_start();
include '../includes/db.php';

if(!isset($_SESSION['user_id'])){
header("Location: ../login.php");
exit;
}

$user_id = $_SESSION['user_id'];

$data = mysqli_query($conn,"
SELECT * FROM bookings
WHERE user_id='$user_id' AND status!='Pending'
ORDER BY id DESC
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Notifications</title>
<style>
body{background:#0b0b0b;color:#fff;font-family:Poppins}
.card{background:#161616;margin:15px;padding:15px;border-radius:14px;border:1px solid rgba(214,194,156,.15)}
</style>
</head>

<body>

<h2 style="color:#D6C29C;text-align:center">Notifications</h2>

<?php while($r=mysqli_fetch_assoc($data)){ ?>
<div class="card">
Your booking <b><?= $r['service'] ?></b><br>
Status: <b style="color:#D6C29C"><?= $r['status'] ?></b>
</div>
<?php } ?>

</body>
</html>