<?php
include 'includes/db.php';

$services = mysqli_query($conn,"
SELECT * FROM services
ORDER BY service_name
");
?>

<!DOCTYPE html>
<html>
<head>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

<style>
body{
background:#0b0b0b;
color:#fff;
font-family:Poppins;
}

h2{
text-align:center;
color:#D6C29C;
margin:20px 0;
}

.card{
background:#161616;
padding:20px;
margin:15px auto;
border-radius:12px;
max-width:700px;
border:1px solid rgba(214,194,156,.15);
}

.title{
color:#D6C29C;
font-size:18px;
font-weight:600;
}

.desc{
color:#aaa;
font-size:13px;
margin-top:6px;
}

.price-box{
margin-top:10px;
padding-top:10px;
border-top:1px solid rgba(255,255,255,.08);
}

.price{
color:#D6C29C;
font-weight:700;
font-size:14px;
}

.no-data{
color:#777;
font-size:12px;
}
</style>

</head>

<body>

<h2>Our Services</h2>

<?php while($s = mysqli_fetch_assoc($services)): ?>

<div class="card">

<div class="title"><?= htmlspecialchars($s['service_name']) ?></div>

<div class="desc">
<?= htmlspecialchars($s['description']) ?>
</div>

<div class="price-box">

<?php
$dur = mysqli_query($conn,"
SELECT * FROM service_durations
WHERE service_id = ".(int)$s['id']
);

if(mysqli_num_rows($dur) > 0):
?>

<?php while($d = mysqli_fetch_assoc($dur)): ?>
<div class="price">
• <?= $d['duration'] ?> — ₱<?= number_format($d['price'],2) ?>
</div>
<?php endwhile; ?>

<?php else: ?>

<div class="no-data">No duration available</div>

<?php endif; ?>

</div>

</div>

<?php endwhile; ?>

</body>
</html>