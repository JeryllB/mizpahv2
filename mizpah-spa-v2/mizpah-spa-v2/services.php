<?php
include 'includes/db.php';

$services = mysqli_query($conn,"
SELECT * FROM services
ORDER BY 
CASE category
    WHEN 'Massage' THEN 1
    WHEN 'Package' THEN 2
    WHEN 'Promo' THEN 3
    WHEN 'Add-ons' THEN 4
    ELSE 5
END,
service_name
");
?>

<!DOCTYPE html>
<html>
<head>

<!-- FONTS -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&family=Playfair+Display:wght@500;600;700&display=swap" rel="stylesheet">

<style>
body{
margin:0;
background:#0b0b0b;
color:#fff;
font-family:Poppins;
}

/* HEADER */
.header{
position:sticky;
top:0;
z-index:100;
background:rgba(10,10,10,0.92);
backdrop-filter: blur(10px);
border-bottom:1px solid rgba(214,194,156,0.15);
padding:12px 18px;
display:flex;
align-items:center;
justify-content:space-between;
}

/* LOGO */
.logo{
display:flex;
align-items:center;
gap:10px;
}

.logo img{
width:42px;
height:42px;
object-fit:contain;
border-radius:6px;
}

.logo span{
font-family:'Playfair Display', serif;
font-size:16px;
color:#D6C29C;
letter-spacing:1px;
}

/* BACK BUTTON */
.back-btn{
text-decoration:none;
color:#0b0b0b;
background:#D6C29C;
padding:8px 14px;
border-radius:8px;
font-size:12px;
font-weight:600;
transition:0.3s;
}

.back-btn:hover{
opacity:0.8;
}

/* TITLE */
.page-title{
text-align:center;
color:#D6C29C;
margin:25px 0 10px;
font-size:24px;
font-family:'Playfair Display', serif;
}

/* CATEGORY */
.category-title{
max-width:800px;
margin:40px auto 15px;
padding:10px 15px;
font-size:16px;
font-weight:600;
color:#D6C29C;
border-left:4px solid #D6C29C;
background:rgba(214,194,156,0.05);
border-radius:6px;
}

/* CARD */
.card{
max-width:800px;
margin:12px auto;
padding:20px;
background:rgba(255,255,255,0.03);
border:1px solid rgba(214,194,156,0.12);
border-radius:14px;
transition:0.3s ease;
}

.card:hover{
transform:translateY(-3px);
border-color:rgba(214,194,156,0.35);
box-shadow:0 10px 25px rgba(0,0,0,0.4);
}

/* SERVICE TITLE */
.title{
font-size:18px;
font-weight:600;
color:#D6C29C;
margin-bottom:6px;
font-family:'Playfair Display', serif;
}

/* DESCRIPTION */
.desc{
font-size:13px;
color:#b5b5b5;
line-height:1.6;
margin-bottom:12px;
}

/* PRICE */
.price-box{
border-top:1px solid rgba(255,255,255,0.08);
padding-top:10px;
}

.price{
font-size:13px;
color:#e6d3a3;
margin:4px 0;
font-weight:500;
}

.no-data{
font-size:12px;
color:#777;
}
</style>

</head>

<body>

<!-- HEADER -->
<div class="header">

    <div class="logo">
        <img src="assets/images/logo.png" alt="Mizpah Wellness Spa Logo">
        <span>Mizpah Wellness Spa</span>
    </div>

    <a href="index.php" class="back-btn">← Back</a>

</div>

<div class="page-title">Our Services</div>

<?php
$currentCategory = null;

while($s = mysqli_fetch_assoc($services)):

if($currentCategory != $s['category']):
    $currentCategory = $s['category'];
?>

<div class="category-title">
    <?= htmlspecialchars($currentCategory) ?>
</div>

<?php endif; ?>

<div class="card">

    <div class="title">
        <?= htmlspecialchars($s['service_name']) ?>
    </div>

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
                • <?= htmlspecialchars($d['duration']) ?> — ₱<?= number_format($d['price'],2) ?>
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