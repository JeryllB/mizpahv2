<?php
session_start();
include '../includes/db.php';

if(!isset($_SESSION['user_id'])){
header("Location: ../login.php");
exit;
}

$id = $_SESSION['user_id'];

if(isset($_POST['update'])){
$name = $_POST['name'];
$email = $_POST['email'];

mysqli_query($conn,"UPDATE users SET name='$name', email='$email' WHERE id='$id'");
$_SESSION['name']=$name;

echo "<script>alert('Updated');</script>";
}

$user = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM users WHERE id='$id'"));
?>

<!DOCTYPE html>
<html>
<head>
<title>Profile</title>
<style>
body{background:#0b0b0b;color:#fff;font-family:Poppins}
.card{max-width:500px;margin:40px auto;background:#161616;padding:20px;border-radius:16px}
input{width:100%;padding:12px;margin-top:10px;background:#0d0d0d;color:#fff;border:1px solid #333}
button{width:100%;padding:12px;margin-top:15px;background:#D6C29C;border:none;font-weight:700}
</style>
</head>

<body>

<div class="card">
<h2 style="color:#D6C29C">Profile</h2>

<form method="POST">
<input name="name" value="<?= $user['name'] ?>">
<input name="email" value="<?= $user['email'] ?>">
<button>Update</button>
</form>

</div>

</body>
</html>