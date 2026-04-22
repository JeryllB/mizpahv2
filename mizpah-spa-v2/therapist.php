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

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">

<link rel="stylesheet" href="assets/css/style.css">
</head>

<body class="therapist-page">

<!-- HEADER (SAFE) -->
<header class="site-header">

    <div class="logo">
        <img src="assets/images/logo.png" alt="logo">
        Mizpah Spa
    </div>

    <a href="index.php" class="btn-primary">Back</a>

</header>

<?php if (!isset($_GET['id'])) { ?>

<h1>Our Professional Therapists</h1>

<div class="grid">

<?php
$list = mysqli_query($conn,"SELECT * FROM therapists WHERE status='Active' ORDER BY rating DESC");

while($t = mysqli_fetch_assoc($list)) {
?>

<div class="card">

    <img src="assets/images/therapists/<?= $t['image'] ?>"
    onerror="this.src='assets/images/therapists/default.png'">

    <h3><?= htmlspecialchars($t['name']) ?></h3>

    <div class="small"><?= htmlspecialchars($t['specialty']) ?></div>

    <div class="rate">⭐ <?= number_format($t['rating'],1) ?>/5</div>

    <div class="small"><?= htmlspecialchars($t['best_service']) ?></div>

    <!-- BOOK BUTTON INSIDE CARD -->
    <a href="booking-guest.php?therapist=<?= $t['id'] ?>" class="btn">Book</a>

</div>

<?php } ?>

</div>

<?php exit; } ?>

<?php
$id = intval($_GET['id']);

$q = mysqli_query($conn,"SELECT * FROM therapists WHERE id='$id'");
$t = mysqli_fetch_assoc($q);
?>

<div class="profile-wrapper">

<div class="card">

<img src="assets/images/therapists/<?= $t['image'] ?: 'default.png' ?>">

<h1><?= htmlspecialchars($t['name']) ?></h1>

<div class="tag"><?= htmlspecialchars($t['specialty']) ?></div>

<p class="rate">⭐ <?= number_format($t['rating'],1) ?>/5</p>

<p><?= htmlspecialchars($t['best_service']) ?></p>

<p><?= nl2br(htmlspecialchars($t['bio'])) ?></p>

<p><?= htmlspecialchars($t['schedule']) ?></p>

<div class="btns">

<a href="booking-guest.php?therapist=<?= $t['id'] ?>" class="btn">Book</a>

<a href="therapist.php" class="btn">Back</a>

</div>

</div>

</div>

</body>
</html>