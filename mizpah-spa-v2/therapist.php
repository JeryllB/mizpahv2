<?php
session_start();
require_once 'includes/db.php';

if (!isset($conn)) {
die("Database connection failed.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Our Therapists | Mizpah Spa</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="assets/css/style.css">

<style>
*{
margin:0;
padding:0;
box-sizing:border-box;
}

body{
background:#0b0b0b;
color:#fff;
font-family:Poppins;
}

/* HEADER FIXED DARK */
.site-header{
position:sticky;
top:0;
z-index:999;
background:#111;
border-bottom:1px solid rgba(214,194,156,.12);
padding:16px 30px;
display:flex;
justify-content:space-between;
align-items:center;
box-shadow:0 8px 20px rgba(0,0,0,.25);
}

.logo{
display:flex;
align-items:center;
gap:10px;
font-size:20px;
font-weight:700;
color:#D6C29C;
}

.logo img{
width:42px;
height:42px;
object-fit:contain;
}

.btn-primary,.btn{
background:#D6C29C;
color:#111;
text-decoration:none;
padding:10px 18px;
border-radius:10px;
font-weight:700;
display:inline-block;
transition:.2s;
}

.btn-primary:hover,.btn:hover{
transform:translateY(-2px);
}

.page-title{
text-align:center;
font-size:34px;
margin:35px 0 10px;
color:#D6C29C;
}

.sub{
text-align:center;
color:#aaa;
margin-bottom:30px;
}

.grid{
display:grid;
grid-template-columns:repeat(auto-fit,minmax(280px,1fr));
gap:20px;
padding:0 30px 40px;
}

.card{
background:#161616;
padding:22px;
border-radius:16px;
border:1px solid rgba(214,194,156,.10);
}

.card h3{
font-size:24px;
margin-bottom:8px;
}

.small{
color:#aaa;
font-size:14px;
margin-bottom:10px;
}

.rate{
font-size:18px;
color:#FFD76A;
font-weight:700;
margin:10px 0;
}

.bio{
font-size:14px;
line-height:1.6;
color:#ddd;
margin:12px 0;
min-height:70px;
}

.tag{
display:inline-block;
padding:6px 12px;
background:#222;
border-radius:20px;
font-size:12px;
color:#D6C29C;
margin-bottom:12px;
}

.row{
display:flex;
justify-content:space-between;
gap:10px;
font-size:14px;
padding:10px 0;
border-top:1px solid rgba(255,255,255,.05);
}

.btn-wrap{
margin-top:18px;
display:flex;
gap:10px;
flex-wrap:wrap;
}

.profile-wrapper{
max-width:760px;
margin:40px auto;
padding:0 20px;
}

.profile-card{
background:#161616;
padding:30px;
border-radius:18px;
border:1px solid rgba(214,194,156,.10);
}

.profile-card h1{
font-size:34px;
margin-bottom:8px;
color:#D6C29C;
}

.btns{
display:flex;
gap:12px;
flex-wrap:wrap;
margin-top:20px;
}

@media(max-width:768px){

.site-header{
padding:15px;
}

.grid{
padding:0 15px 30px;
}

.page-title{
font-size:28px;
}

.profile-card h1{
font-size:28px;
}

}
</style>
</head>

<body>

<header class="site-header">

<div class="logo">
<img src="assets/images/logo.png" alt="logo">
Mizpah Spa
</div>

<a href="index.php" class="btn-primary">Back</a>

</header>

<?php if (!isset($_GET['id'])) { ?>

<h1 class="page-title">Our Professional Therapists</h1>
<p class="sub">Experienced therapists ready to help you relax</p>

<div class="grid">

<?php
$list = mysqli_query($conn,"
SELECT t.*,
(
SELECT IFNULL(AVG(rating),0)
FROM therapist_ratings tr
WHERE tr.therapist_id=t.id
) as avg_rating
FROM therapists t
WHERE t.status='Active'
ORDER BY avg_rating DESC, t.name ASC
");

while($t = mysqli_fetch_assoc($list)) {
?>

<div class="card">

<h3><?= htmlspecialchars($t['name']) ?></h3>

<div class="small"><?= htmlspecialchars($t['specialty']) ?></div>

<div class="rate">
⭐ <?= number_format($t['avg_rating'],1) ?>/5
</div>

<div class="tag">
Best At: <?= htmlspecialchars($t['best_service']) ?>
</div>

<p class="bio">
<?= nl2br(htmlspecialchars($t['bio'])) ?>
</p>

<div class="row">
<span>Schedule</span>
<strong><?= htmlspecialchars($t['schedule']) ?></strong>
</div>

<div class="btn-wrap">
<a href="booking-guest.php?therapist=<?= $t['id'] ?>" class="btn">Book Now</a>
<a href="therapist.php?id=<?= $t['id'] ?>" class="btn">View</a>
</div>

</div>

<?php } ?>

</div>

<?php exit; } ?>

<?php
$id = intval($_GET['id']);

$q = mysqli_query($conn,"
SELECT t.*,
(
SELECT IFNULL(AVG(rating),0)
FROM therapist_ratings tr
WHERE tr.therapist_id=t.id
) as avg_rating
FROM therapists t
WHERE t.id='$id'
");

$t = mysqli_fetch_assoc($q);
?>

<div class="profile-wrapper">

<div class="profile-card">

<h1><?= htmlspecialchars($t['name']) ?></h1>

<div class="small"><?= htmlspecialchars($t['specialty']) ?></div>

<p class="rate">
⭐ <?= number_format($t['avg_rating'],1) ?>/5
</p>

<div class="tag">
Best At: <?= htmlspecialchars($t['best_service']) ?>
</div>

<p class="bio">
<?= nl2br(htmlspecialchars($t['bio'])) ?>
</p>

<div class="row">
<span>Schedule</span>
<strong><?= htmlspecialchars($t['schedule']) ?></strong>
</div>

<div class="btns">
<a href="booking-guest.php?therapist=<?= $t['id'] ?>" class="btn">Book Now</a>
<a href="therapist.php" class="btn">Back</a>
</div>

</div>

</div>

</body>
</html>