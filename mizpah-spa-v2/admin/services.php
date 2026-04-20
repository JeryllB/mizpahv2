<?php
session_start();
include '../includes/db.php';

if(!isset($_SESSION['user_id'])){
header("Location: ../login.php");
exit;
}

/* ================= UPDATE SERVICE ================= */
if(isset($_POST['update_service'])){

$id          = $_POST['id'];
$name        = mysqli_real_escape_string($conn,$_POST['service_name']);
$desc        = mysqli_real_escape_string($conn,$_POST['description']);
$price       = mysqli_real_escape_string($conn,$_POST['price']);
$duration    = mysqli_real_escape_string($conn,$_POST['duration']);
$category    = mysqli_real_escape_string($conn,$_POST['category']);

mysqli_query($conn,"
UPDATE services SET
service_name='$name',
description='$desc',
price='$price',
duration='$duration',
category='$category'
WHERE id='$id'
");

echo "<script>alert('Service Updated!');window.location='services.php';</script>";
exit;
}

/* ================= DATA ================= */
$services = mysqli_query($conn,"SELECT * FROM services ORDER BY id DESC");

$total = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) total FROM services"))['total'] ?? 0;

$massage = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) total FROM services WHERE category='Massage'"))['total'] ?? 0;

$package = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) total FROM services WHERE category='Package'"))['total'] ?? 0;

$addon = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) total FROM services WHERE category='Add-on'"))['total'] ?? 0;
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
margin-left:260px;
padding:30px;
background:#0b0b0b;
min-height:100vh;
color:#fff;
}

/* HEADER */
.header h1{
color:#D6C29C;
margin-bottom:20px;
}

/* STATS */
.stats{
display:grid;
grid-template-columns:repeat(auto-fit,minmax(180px,1fr));
gap:15px;
margin-bottom:20px;
}

.stat{
background:#161616;
padding:18px;
border-radius:12px;
border:1px solid rgba(214,194,156,.12);
}

.stat h3{
color:#D6C29C;
font-size:13px;
}

.stat p{
font-size:24px;
font-weight:700;
}

/* TABLE */
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
}

td{
padding:14px;
border-top:1px solid rgba(255,255,255,.05);
font-size:14px;
}

tr:hover{background:#111;}

.price{
color:#D6C29C;
font-weight:700;
}

/* BADGE */
.badge{
padding:4px 10px;
border-radius:20px;
font-size:12px;
font-weight:700;
}

.Massage{background:#1a2c4b;color:#8fc5ff;}
.Package{background:#2d2a14;color:#ffd86b;}
.Add-on{background:#2a1a1a;color:#ffb3b3;}
.Promo{background:#1f2a1f;color:#9effa5;}

/* MODAL */
.modal{
display:none;
position:fixed;
inset:0;
background:rgba(0,0,0,.7);
}

.modal-content{
background:#161616;
width:420px;
margin:8% auto;
padding:25px;
border-radius:14px;
border:1px solid rgba(214,194,156,.2);
}

label{
display:block;
margin:8px 0 4px;
font-size:12px;
color:#D6C29C;
}

input,textarea,select{
width:100%;
padding:10px;
margin-bottom:10px;
background:#0b0b0b;
border:1px solid #333;
color:#fff;
border-radius:8px;
}

button{
width:100%;
padding:12px;
background:#D6C29C;
border:none;
border-radius:10px;
font-weight:700;
cursor:pointer;
}

button:hover{opacity:.9;}

.close{
float:right;
cursor:pointer;
color:#fff;
}

</style>
</head>

<body>

<?php include __DIR__.'/includes/sidebar.php'; ?>

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
<th>Name</th>
<th>Price</th>
<th>Duration</th>
<th>Category</th>
<th>Description</th>
<th>Action</th>
</tr>

<?php while($row = mysqli_fetch_assoc($services)) { ?>

<tr>

<td><?= $row['id'] ?></td>

<td><b><?= $row['service_name'] ?></b></td>

<td class="price">₱<?= number_format($row['price'],2) ?></td>

<td><?= $row['duration'] ?></td>

<td><span class="badge <?= $row['category'] ?>"><?= $row['category'] ?></span></td>

<td><?= $row['description'] ?></td>

<td>
<button onclick="openEdit(
'<?= $row['id'] ?>',
'<?= htmlspecialchars($row['service_name']) ?>',
'<?= htmlspecialchars($row['description']) ?>',
'<?= $row['price'] ?>',
'<?= $row['duration'] ?>',
'<?= $row['category'] ?>'
)">Edit</button>
</td>

</tr>

<?php } ?>

</table>

</div>

</div>

<!-- EDIT MODAL -->
<div class="modal" id="editModal">
<div class="modal-content">

<span class="close" onclick="document.getElementById('editModal').style.display='none'">X</span>

<h3 style="color:#D6C29C;">Edit Service</h3>

<form method="POST">

<input type="hidden" name="id" id="eid">

<label>Service Name</label>
<input type="text" name="service_name" id="ename">

<label>Description</label>
<textarea name="description" id="edesc"></textarea>

<label>Price</label>
<input type="number" name="price" id="eprice">

<label>Duration</label>
<input type="text" name="duration" id="eduration">

<label>Category</label>
<select name="category" id="ecategory">
<option>Massage</option>
<option>Package</option>
<option>Add-on</option>
<option>Promo</option>
</select>

<button name="update_service">Update</button>

</form>

</div>
</div>

<script>

function openEdit(id,name,desc,price,duration,category){

document.getElementById('editModal').style.display='block';

document.getElementById('eid').value=id;
document.getElementById('ename').value=name;
document.getElementById('edesc').value=desc;
document.getElementById('eprice').value=price;
document.getElementById('eduration').value=duration;
document.getElementById('ecategory').value=category;

}

</script>

</body>
</html>