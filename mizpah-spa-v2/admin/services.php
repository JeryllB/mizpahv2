<?php
session_start();
include '../includes/db.php';

if(!isset($_SESSION['user_id'])){
header("Location: ../login.php");
exit;
}

/* DATA */
$services = mysqli_query($conn,"
SELECT * FROM services ORDER BY id DESC
");

/* COUNTS */
$total = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) total FROM services
"))['total'] ?? 0;

$massage = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) total FROM services WHERE category='Massage'
"))['total'] ?? 0;

$package = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) total FROM services WHERE category='Package'
"))['total'] ?? 0;

$addon = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) total FROM services WHERE category='Add-on'
"))['total'] ?? 0;
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Services</title>
<link rel="stylesheet" href="../assets/css/admin.css">

<style>

.main{
margin-left:250px;
padding:35px;
background:#0b0b0b;
color:#fff;
min-height:100vh;
}

.header{
display:flex;
justify-content:space-between;
align-items:center;
flex-wrap:wrap;
margin-bottom:20px;
}

.header h1{
color:#D6C29C;
font-size:32px;
}

.stats{
display:grid;
grid-template-columns:repeat(auto-fit,minmax(200px,1fr));
gap:15px;
margin-bottom:20px;
}

.stat{
background:#161616;
padding:18px;
border-radius:14px;
border:1px solid rgba(214,194,156,.12);
}

.stat h3{
font-size:13px;
color:#D6C29C;
margin-bottom:8px;
}

.stat p{
font-size:26px;
font-weight:700;
}

.table-box{
background:#161616;
border-radius:14px;
overflow:auto;
border:1px solid rgba(214,194,156,.12);
}

table{
width:100%;
border-collapse:collapse;
min-width:900px;
}

th{
background:#1d1d1d;
color:#D6C29C;
padding:14px;
text-align:left;
font-size:14px;
}

td{
padding:14px;
border-top:1px solid rgba(255,255,255,.05);
font-size:14px;
vertical-align:top;
}

tr:hover{
background:#111;
}

.badge{
padding:5px 10px;
border-radius:20px;
font-size:12px;
font-weight:700;
display:inline-block;
}

.Massage{background:#1a2c4b;color:#8fc5ff;}
.Package{background:#2d2a14;color:#ffd86b;}
["Add-on"]{background:#2a1a1a;color:#ffb3b3;}

.price{
color:#D6C29C;
font-weight:700;
}

</style>
</head>

<body>

<?php include __DIR__ . '/includes/sidebar.php'; ?>

<div class="main">

<div class="header">
<h1>Services Management</h1>
</div>

<!-- STATS -->
<div class="stats">

<div class="stat">
<h3>Total Services</h3>
<p><?= $total ?></p>
</div>

<div class="stat">
<h3>Massage</h3>
<p><?= $massage ?></p>
</div>

<div class="stat">
<h3>Packages</h3>
<p><?= $package ?></p>
</div>

<div class="stat">
<h3>Add-ons</h3>
<p><?= $addon ?></p>
</div>

</div>

<!-- TABLE -->
<div class="table-box">

<table>

<tr>
<th>ID</th>
<th>Service Name</th>
<th>Price</th>
<th>Duration</th>
<th>Category</th>
<th>Description</th>
</tr>

<?php while($row = mysqli_fetch_assoc($services)) { ?>

<tr>

<td><?= $row['id'] ?></td>

<td>
<strong><?= htmlspecialchars($row['service_name']) ?></strong>
</td>

<td class="price">
₱<?= number_format($row['price'],2) ?>
</td>

<td><?= $row['duration'] ?></td>

<td>
<span class="badge <?= $row['category'] ?>">
<?= $row['category'] ?>
</span>
</td>

<td>
<?= htmlspecialchars($row['description']) ?>
</td>

</tr>

<?php } ?>

</table>

</div>

</div>

</body>
</html>