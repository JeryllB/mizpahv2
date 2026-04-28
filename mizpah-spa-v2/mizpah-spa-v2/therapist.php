<?php
session_start();
include 'includes/db.php';

$therapists = mysqli_query($conn,"
SELECT t.*,
IFNULL(AVG(tr.rating),0) as avg_rating,
COUNT(tr.id) as total_reviews
FROM therapists t
LEFT JOIN therapist_ratings tr ON tr.therapist_id=t.id
WHERE t.status='Active'
GROUP BY t.id
ORDER BY t.name ASC
");
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Therapists</title>

<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

<style>
body{
margin:0;
background:#0b0b0b;
font-family:Poppins,sans-serif;
color:#fff;
}

.header{
display:flex;
justify-content:space-between;
align-items:center;
padding:18px 30px;
background:#111;
border-bottom:1px solid #222;
}

.left{
display:flex;
align-items:center;
gap:12px;
}

.logo{
width:42px;
height:42px;
}

.title{
font-family:Playfair Display;
font-size:24px;
color:#D6C29C;
}

.back{
color:#aaa;
text-decoration:none;
}
.back:hover{color:#D6C29C;}

.grid{
display:grid;
grid-template-columns:repeat(auto-fit,minmax(260px,1fr));
gap:18px;
padding:25px;
}

.card{
background:#161616;
border:1px solid #222;
border-radius:16px;
padding:18px;
transition:.2s;
}

.card:hover{
transform:translateY(-5px);
border-color:#D6C29C;
}

.name{
font-size:19px;
font-weight:600;
}

.spec{
font-size:13px;
color:#aaa;
margin:8px 0;
line-height:1.5;
min-height:55px;
}

.rating{
color:#D6C29C;
font-size:15px;
font-weight:600;
margin-top:10px;
}

.small{
font-size:12px;
color:#777;
}
</style>
</head>

<body>

<div class="header">

<div class="left">
<img src="assets/images/logo.png" class="logo">
<div class="title">Our Therapists</div>
</div>

<a href="index.php" class="back">← Back Home</a>

</div>

<div class="grid">

<?php while($t=mysqli_fetch_assoc($therapists)): ?>

<div class="card">

<div class="name"><?= $t['name'] ?></div>

<div class="spec">
<?= $t['specialty'] ?>
</div>

<div class="rating">
⭐ <?= number_format($t['avg_rating'],1) ?>/5
</div>

<div class="small">
<?= $t['total_reviews'] ?> review(s)
</div>

</div>

<?php endwhile; ?>

</div>

</body>
</html>