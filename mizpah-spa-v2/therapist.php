<?php
session_start();
require_once 'includes/db.php';

if (!isset($conn)) {
die("Database connection failed.");
}

/* ================= LIST PAGE ================= */
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
padding:30px 15px;
}

.top{
text-align:center;
margin-bottom:20px;
}

.back{
display:inline-block;
padding:10px 16px;
background:#161616;
border:1px solid rgba(214,194,156,.2);
color:#fff;
text-decoration:none;
border-radius:10px;
}

h1{
text-align:center;
font-family:'Playfair Display',serif;
color:#D6C29C;
font-size:38px;
margin-bottom:25px;
}

/* GRID FIX */
.grid{
display:grid;
grid-template-columns:repeat(4, 1fr);
gap:12px;
max-width:1000px;
margin:auto;
}

.card{
background:#161616;
border-radius:12px;
padding:10px;
text-align:center;
border:1px solid rgba(214,194,156,.12);
transition:0.25s ease;
text-decoration:none;
color:#fff;
}

.card:hover{
transform:translateY(-4px);
border-color:#D6C29C;
}

.card img{
width:100%;
height:150px;
object-fit:cover;
border-radius:10px;
margin-bottom:8px;
}

.card h3{
font-size:14px;
color:#D6C29C;
font-family:'Playfair Display';
margin-bottom:3px;
}

.small{
font-size:11px;
color:#bbb;
margin-bottom:3px;
}

.rate{
font-size:12px;
color:#ffd86b;
font-weight:700;
margin-bottom:5px;
}

.btn{
display:inline-block;
margin-top:6px;
padding:6px 10px;
font-size:11px;
background:#D6C29C;
color:#111;
border-radius:6px;
font-weight:700;
text-decoration:none;
}

/* RESPONSIVE */
@media(max-width:900px){
.grid{grid-template-columns:repeat(3,1fr);}
}

@media(max-width:600px){
.grid{grid-template-columns:repeat(2,1fr);}
}

@media(max-width:400px){
.grid{grid-template-columns:1fr;}
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

<!-- FIXED IMAGE -->
<img src="assets/images/therapists/<?= htmlspecialchars($t['image']) ?>" 
onerror="this.src='assets/images/therapists/default.png'">

<h3><?= htmlspecialchars($t['name']) ?></h3>

<div class="small"><?= htmlspecialchars($t['specialty']) ?></div>

<div class="rate">⭐ <?= number_format($t['rating'],1) ?>/5</div>

<div class="small"><?= htmlspecialchars($t['best_service']) ?></div>

<span class="btn">View</span>

</a>

<?php } ?>

</div>

</body>
</html>

<?php exit; }

/* ================= PROFILE PAGE ================= */

$id = intval($_GET['id']);

$q = mysqli_query($conn,"SELECT * FROM therapists WHERE id='$id'");

if(mysqli_num_rows($q)==0){
die("Not Found");
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

body{
background:#0b0b0b;
color:#fff;
display:flex;
justify-content:center;
align-items:center;
padding:20px;
min-height:100vh;
font-family:Poppins;
}

.card{
background:#161616;
padding:25px;
border-radius:16px;
max-width:520px;
width:100%;
text-align:center;
border:1px solid rgba(214,194,156,.18);
}

img{
width:100%;
height:320px;
object-fit:cover;
border-radius:12px;
margin-bottom:15px;
}

h1{
font-family:'Playfair Display';
color:#D6C29C;
font-size:30px;
margin-bottom:10px;
}

.tag{
background:#111;
display:inline-block;
padding:6px 12px;
border-radius:20px;
font-size:12px;
color:#D6C29C;
margin-bottom:10px;
}

p{
font-size:13px;
color:#ddd;
margin-bottom:8px;
line-height:1.6;
}

.rate{
color:#ffd86b;
font-weight:700;
margin-bottom:10px;
}

.btns{
display:flex;
gap:10px;
margin-top:15px;
}

.btn{
flex:1;
padding:10px;
border-radius:10px;
text-decoration:none;
font-weight:700;
font-size:13px;
}

.book{
background:#D6C29C;
color:#111;
}

.back{
background:#111;
color:#fff;
border:1px solid rgba(214,194,156,.2);
}

</style>
</head>
<body>

<div class="card">

<!-- FIXED IMAGE -->
<img src="assets/images/therapists/<?= htmlspecialchars($t['image']) ?>" 
onerror="this.src='assets/images/therapists/default.png'">

<h1><?= htmlspecialchars($t['name']) ?></h1>

<div class="tag"><?= htmlspecialchars($t['specialty']) ?></div>

<p class="rate">⭐ <?= number_format($t['rating'],1) ?>/5</p>

<p><?= htmlspecialchars($t['best_service']) ?></p>

<p><?= nl2br(htmlspecialchars($t['bio'])) ?></p>

<p><?= htmlspecialchars($t['schedule']) ?></p>

<div class="btns">
<a href="booking-guest.php?therapist=<?= $t['id'] ?>" class="btn book">Book</a>
<a href="therapist.php" class="btn back">Back</a>
</div>

</div>

</body>
</html>