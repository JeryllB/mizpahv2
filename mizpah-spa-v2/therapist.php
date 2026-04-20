<?php
session_start();
require_once 'includes/db.php';

/* CHECK DB */
if (!isset($conn)) {
die("Database connection failed.");
}

/* IF NO ID = SHOW LIST OF THERAPISTS */
if (!isset($_GET['id']) || $_GET['id'] == '') {

$list = mysqli_query($conn,"
SELECT * FROM therapists
WHERE status='Active'
ORDER BY rating DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Our Therapists | Mizpah Spa</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">

<style>

*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:Poppins,sans-serif;
}

body{
background:#0b0b0b;
color:#fff;
padding:40px 20px;
}

h1{
text-align:center;
font-family:'Playfair Display',serif;
color:#D6C29C;
font-size:40px;
margin-bottom:35px;
}

.grid{
display:grid;
grid-template-columns:repeat(auto-fit,minmax(280px,1fr));
gap:22px;
max-width:1300px;
margin:auto;
}

.card{
background:#161616;
border-radius:18px;
padding:18px;
text-decoration:none;
color:#fff;
border:1px solid rgba(214,194,156,.14);
transition:.25s;
}

.card:hover{
transform:translateY(-5px);
border-color:#D6C29C;
}

.card img{
width:100%;
height:270px;
object-fit:cover;
border-radius:14px;
margin-bottom:15px;
}

.card h3{
font-size:22px;
color:#D6C29C;
margin-bottom:8px;
}

.small{
font-size:14px;
color:#ccc;
margin-bottom:8px;
}

.rate{
color:#ffd86b;
font-weight:700;
margin-bottom:8px;
}

.btn{
display:inline-block;
margin-top:10px;
padding:10px 14px;
background:#D6C29C;
color:#111;
border-radius:10px;
font-weight:700;
font-size:14px;
}

.top{
text-align:center;
margin-bottom:30px;
}

.back{
display:inline-block;
padding:12px 18px;
background:#161616;
border:1px solid rgba(214,194,156,.18);
color:#fff;
text-decoration:none;
border-radius:10px;
}

</style>
</head>
<body>

<div class="top">
<a href="index.php" class="back">← Back Home</a>
</div>

<h1>Our Professional Therapists</h1>

<div class="grid">

<?php while($t=mysqli_fetch_assoc($list)){ ?>

<a href="therapist.php?id=<?= $t['id'] ?>" class="card">

<img src="assets/images/therapists/<?= $t['image'] ?: 'default.png' ?>">

<h3><?= htmlspecialchars($t['name']) ?></h3>

<div class="small"><?= htmlspecialchars($t['specialty']) ?></div>

<div class="rate">⭐ <?= number_format($t['rating'],1) ?>/5</div>

<div class="small">Best: <?= htmlspecialchars($t['best_service']) ?></div>

<span class="btn">View Profile</span>

</a>

<?php } ?>

</div>

</body>
</html>

<?php
exit;
}

/* =========================
   IF HAS ID = PROFILE PAGE
========================= */

$id = intval($_GET['id']);

$q = mysqli_query($conn,"SELECT * FROM therapists WHERE id='$id'");

if(mysqli_num_rows($q)==0){
die("
<div style='background:#0b0b0b;color:#fff;text-align:center;padding:80px;font-family:Poppins'>
<h2 style='color:#D6C29C'>Therapist Not Found</h2>
<a href='therapist.php' style='color:#D6C29C;text-decoration:none;'>← View All Therapists</a>
</div>
");
}

$t = mysqli_fetch_assoc($q);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title><?= $t['name'] ?> | Mizpah Spa</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">

<style>

*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:Poppins,sans-serif;
}

body{
background:#0b0b0b;
color:#fff;
min-height:100vh;
display:flex;
justify-content:center;
align-items:center;
padding:25px;
}

.card{
width:100%;
max-width:560px;
background:#161616;
padding:28px;
border-radius:18px;
border:1px solid rgba(214,194,156,.18);
text-align:center;
}

img{
width:100%;
height:340px;
object-fit:cover;
border-radius:14px;
margin-bottom:18px;
}

h1{
font-family:'Playfair Display',serif;
font-size:34px;
color:#D6C29C;
margin-bottom:10px;
}

.tag{
display:inline-block;
padding:7px 14px;
background:#111;
border-radius:30px;
margin-bottom:15px;
font-size:13px;
color:#D6C29C;
}

p{
font-size:14px;
line-height:1.8;
margin-bottom:10px;
color:#ddd;
}

.rate{
font-size:18px;
font-weight:700;
color:#ffd86b;
}

.btns{
display:flex;
gap:12px;
margin-top:20px;
flex-wrap:wrap;
}

.btn{
flex:1;
padding:13px;
border-radius:10px;
text-decoration:none;
font-weight:700;
font-size:14px;
}

.book{
background:#D6C29C;
color:#111;
}

.back{
background:#111;
color:#fff;
border:1px solid rgba(214,194,156,.18);
}

</style>
</head>
<body>

<div class="card">

<img src="assets/images/therapists/<?= $t['image'] ?: 'default.png' ?>">

<h1><?= htmlspecialchars($t['name']) ?></h1>

<div class="tag"><?= htmlspecialchars($t['specialty']) ?></div>

<p class="rate">⭐ <?= number_format($t['rating'],1) ?>/5</p>

<p><strong>Best Service:</strong> <?= htmlspecialchars($t['best_service']) ?></p>

<p><?= nl2br(htmlspecialchars($t['bio'])) ?></p>

<p><strong>Schedule:</strong> <?= htmlspecialchars($t['schedule']) ?></p>

<div class="btns">
<a href="booking-guest.php?therapist=<?= $t['id'] ?>" class="btn book">Book This Therapist</a>
<a href="therapist.php" class="btn back">← All Therapists</a>
</div>

</div>

</body>
</html>