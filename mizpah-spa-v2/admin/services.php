<?php
session_start();

/* ================= DB (ROOT) ================= */
include __DIR__ . '/../includes/db.php';

if(!isset($conn)){
    die("DB connection failed");
}

if(!isset($_SESSION['user_id'])){
header("Location: ../login.php");
exit;
}

/* ================= ADD SERVICE ================= */
if(isset($_POST['add_service'])){

$name     = mysqli_real_escape_string($conn,$_POST['service_name']);
$desc     = mysqli_real_escape_string($conn,$_POST['description']);
$category = mysqli_real_escape_string($conn,$_POST['category']);

mysqli_query($conn,"
INSERT INTO services (service_name, description, category)
VALUES ('$name','$desc','$category')
");

$service_id = mysqli_insert_id($conn);

/* durations */
if(!empty($_POST['duration'])){
    foreach($_POST['duration'] as $i=>$dur){

        $dur   = mysqli_real_escape_string($conn,$dur);
        $price = mysqli_real_escape_string($conn,$_POST['price'][$i]);

        if($dur && $price){
            mysqli_query($conn,"
            INSERT INTO service_durations (service_id,duration,price)
            VALUES ('$service_id','$dur','$price')
            ");
        }
    }
}

echo "<script>alert('Service Added');window.location='services.php';</script>";
exit;
}

/* ================= UPDATE SERVICE ================= */
if(isset($_POST['update_service'])){

$id       = (int)$_POST['id'];
$name     = mysqli_real_escape_string($conn,$_POST['service_name']);
$desc     = mysqli_real_escape_string($conn,$_POST['description']);
$category = mysqli_real_escape_string($conn,$_POST['category']);

mysqli_query($conn,"
UPDATE services SET
service_name='$name',
description='$desc',
category='$category'
WHERE id='$id'
");

/* delete old durations then reinsert (para walang duplicate) */
mysqli_query($conn,"DELETE FROM service_durations WHERE service_id='$id'");

if(!empty($_POST['duration'])){
    foreach($_POST['duration'] as $i=>$dur){

        $dur   = mysqli_real_escape_string($conn,$dur);
        $price = mysqli_real_escape_string($conn,$_POST['price'][$i]);

        if($dur && $price){
            mysqli_query($conn,"
            INSERT INTO service_durations (service_id,duration,price)
            VALUES ('$id','$dur','$price')
            ");
        }
    }
}

echo "<script>alert('Updated');window.location='services.php';</script>";
exit;
}

/* ================= GET DATA ================= */
$services = mysqli_query($conn,"SELECT * FROM services ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Services</title>

<link rel="stylesheet" href="../assets/css/admin.css">

<style>
body{
background:#0b0b0b;
color:#fff;
font-family:Poppins;
}

.main{
margin-left:260px;
padding:30px;
}

table{
width:100%;
border-collapse:collapse;
}

th,td{
padding:12px;
border-bottom:1px solid #222;
}

th{color:#D6C29C;}

button{
padding:6px 10px;
border:none;
border-radius:6px;
cursor:pointer;
}

.addbtn{
background:#1f3d2b;
color:#7dffaf;
margin-bottom:15px;
}

.editbtn{
background:#D6C29C;
color:#111;
}

/* MODAL */
.modal{
display:none;
position:fixed;
inset:0;
background:rgba(0,0,0,.7);
}

.modal-content{
background:#161616;
width:500px;
margin:5% auto;
padding:20px;
border-radius:10px;
}

input,textarea,select{
width:100%;
padding:10px;
margin-bottom:10px;
background:#0b0b0b;
color:#fff;
border:1px solid #333;
}

.addrow{
display:flex;
gap:10px;
margin-bottom:8px;
}
.addrow input{flex:1;}
</style>
</head>

<body>

<!-- FIXED SIDEBAR PATH -->
<?php include __DIR__.'/includes/sidebar.php'; ?>

<div class="main">

<h2>Services Management</h2>

<button class="addbtn" onclick="document.getElementById('addModal').style.display='block'">
+ Add Service
</button>

<table>

<tr>
<th>Name</th>
<th>Category</th>
<th>Description</th>
<th>Durations</th>
<th>Action</th>
</tr>

<?php while($row=mysqli_fetch_assoc($services)): ?>

<?php
$dur = mysqli_query($conn,"SELECT * FROM service_durations WHERE service_id=".$row['id']);
?>

<tr>

<td><?= htmlspecialchars($row['service_name']) ?></td>
<td><?= $row['category'] ?></td>
<td><?= htmlspecialchars($row['description']) ?></td>

<td>
<?php while($d=mysqli_fetch_assoc($dur)): ?>
✔ <?= $d['duration'] ?> - ₱<?= $d['price'] ?><br>
<?php endwhile; ?>
</td>

<td>
<button class="editbtn" onclick="openEdit(
'<?= $row['id'] ?>',
'<?= htmlspecialchars($row['service_name']) ?>',
'<?= htmlspecialchars($row['description']) ?>',
'<?= $row['category'] ?>'
)">Edit</button>
</td>

</tr>

<?php endwhile; ?>

</table>

</div>

<!-- ADD MODAL -->
<div class="modal" id="addModal">
<div class="modal-content">

<h3>Add Service</h3>

<form method="POST">

<input name="service_name" placeholder="Service Name" required>
<textarea name="description" placeholder="Description"></textarea>

<h4>Durations & Price</h4>

<div id="wrap">

<div class="addrow">
<input name="duration[]" placeholder="e.g 1hr">
<input name="price[]" placeholder="Price">
</div>

</div>

<button type="button" onclick="addRow()">+ Add More</button>

<br><br>

<select name="category">
<option>Massage</option>
<option>Package</option>
<option>Add-on</option>
<option>Promo</option>
</select>

<br><br>

<button name="add_service">Save</button>

</form>

</div>
</div>

<!-- EDIT MODAL -->
<div class="modal" id="editModal">
<div class="modal-content">

<h3>Edit Service</h3>

<form method="POST">

<input type="hidden" name="id" id="eid">

<input name="service_name" id="ename">
<textarea name="description" id="edesc"></textarea>

<select name="category" id="ecat">
<option>Massage</option>
<option>Package</option>
<option>Add-on</option>
<option>Promo</option>
</select>

<h4>Durations & Price</h4>

<div class="addrow">
<input name="duration[]" placeholder="e.g 1hr">
<input name="price[]" placeholder="Price">
</div>

<div class="addrow">
<input name="duration[]" placeholder="e.g 1.5hr">
<input name="price[]" placeholder="Price">
</div>

<div class="addrow">
<input name="duration[]" placeholder="e.g 2hr">
<input name="price[]" placeholder="Price">
</div>

<br>

<button name="update_service">Update</button>

</form>

</div>
</div>

<script>
function addRow(){
let div=document.createElement('div');
div.className='addrow';
div.innerHTML=`
<input name="duration[]" placeholder="e.g 1hr">
<input name="price[]" placeholder="Price">
`;
document.getElementById('wrap').appendChild(div);
}

function openEdit(id,name,desc,cat){

eid.value=id;
ename.value=name;
edesc.value=desc;
ecat.value=cat;

document.getElementById('editModal').style.display='block';
}
</script>

</body>
</html>