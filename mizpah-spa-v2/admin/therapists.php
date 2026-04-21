<?php
session_start();
include '../includes/db.php';

if(!isset($_SESSION['user_id']) || $_SESSION['role']!='admin'){
header("Location: ../login.php");
exit;
}

/* =========================
   DELETE
========================= */
if(isset($_GET['delete'])){
$id = intval($_GET['delete']);
mysqli_query($conn,"DELETE FROM therapists WHERE id='$id'");
header("Location: therapists.php");
exit;
}

/* =========================
   ADD / UPDATE
========================= */
if(isset($_POST['save_therapist'])){

$id           = intval($_POST['id'] ?? 0);
$name         = mysqli_real_escape_string($conn,$_POST['name']);
$specialty    = mysqli_real_escape_string($conn,$_POST['specialty']);
$rating       = mysqli_real_escape_string($conn,$_POST['rating']);
$best_service = mysqli_real_escape_string($conn,$_POST['best_service']);
$bio          = mysqli_real_escape_string($conn,$_POST['bio']);
$schedule     = mysqli_real_escape_string($conn,$_POST['schedule']);
$status       = mysqli_real_escape_string($conn,$_POST['status']);

$imageName = "";

if(isset($_FILES['image']) && $_FILES['image']['name']!=""){
$imageName = time().'_'.basename($_FILES['image']['name']);
move_uploaded_file($_FILES['image']['tmp_name'],"../assets/images/therapists/".$imageName);
}

if($id > 0){

if($imageName!=""){
mysqli_query($conn,"UPDATE therapists SET
name='$name',
specialty='$specialty',
rating='$rating',
best_service='$best_service',
bio='$bio',
schedule='$schedule',
status='$status',
image='$imageName'
WHERE id='$id'");
}else{
mysqli_query($conn,"UPDATE therapists SET
name='$name',
specialty='$specialty',
rating='$rating',
best_service='$best_service',
bio='$bio',
schedule='$schedule',
status='$status'
WHERE id='$id'");
}

}else{

mysqli_query($conn,"INSERT INTO therapists
(name,image,specialty,rating,best_service,bio,schedule,status)
VALUES
('$name','$imageName','$specialty','$rating','$best_service','$bio','$schedule','$status')");

}

header("Location: therapists.php");
exit;
}

/* =========================
   EDIT DATA
========================= */
$edit = null;

if(isset($_GET['edit'])){
$id = intval($_GET['edit']);
$q = mysqli_query($conn,"SELECT * FROM therapists WHERE id='$id'");
$edit = mysqli_fetch_assoc($q);
}

/* =========================
   COUNTS
========================= */
$total = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) total FROM therapists"))['total'];
$active = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) total FROM therapists WHERE status='Active'"))['total'];
$inactive = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) total FROM therapists WHERE status='Inactive'"))['total'];

/* =========================
   LIST
========================= */
$list = mysqli_query($conn,"SELECT * FROM therapists ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Therapists Management</title>

<link rel="stylesheet" href="../assets/css/admin.css">

<style>

.main{
margin-left:260px;
padding:35px;
background:#0b0b0b;
color:#fff;
min-height:100vh;
}

h1{
color:#D6C29C;
margin-bottom:18px;
font-size:32px;
}

.stats{
display:grid;
grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
gap:15px;
margin-bottom:25px;
}

.box{
background:#161616;
padding:20px;
border-radius:14px;
border:1px solid rgba(214,194,156,.12);
}

.box h3{
font-size:13px;
color:#D6C29C;
margin-bottom:10px;
}

.box p{
font-size:28px;
font-weight:700;
}

.form-box{
background:#161616;
padding:22px;
border-radius:14px;
border:1px solid rgba(214,194,156,.12);
margin-bottom:25px;
}

.grid{
display:grid;
grid-template-columns:1fr 1fr;
gap:15px;
}

label{
font-size:13px;
color:#D6C29C;
margin-bottom:6px;
display:block;
}

input,textarea,select{
width:100%;
padding:11px;
border-radius:10px;
border:1px solid #2a2a2a;
background:#111;
color:#fff;
margin-bottom:12px;
}

textarea{
height:110px;
resize:none;
}

button{
padding:12px 18px;
background:#D6C29C;
color:#111;
border:none;
border-radius:10px;
font-weight:700;
cursor:pointer;
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
min-width:1100px;
}

th{
background:#1d1d1d;
padding:14px;
color:#D6C29C;
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

img.thumb{
width:58px;
height:58px;
object-fit:cover;
border-radius:10px;
border:1px solid rgba(214,194,156,.15);
}

.badge{
padding:5px 10px;
border-radius:20px;
font-size:12px;
font-weight:700;
display:inline-block;
}

.Active{
background:#17331f;
color:#78ffab;
}

.Inactive{
background:#381919;
color:#ff9999;
}

a.action{
text-decoration:none;
margin-right:10px;
color:#D6C29C;
font-size:13px;
}

@media(max-width:900px){
.main{margin-left:0;padding:20px;}
.grid{grid-template-columns:1fr;}
}

</style>
</head>

<body>

<?php include __DIR__.'/includes/sidebar.php'; ?>

<div class="main">

<h1>Therapists Management</h1>

<div class="stats">
<div class="box"><h3>Total Therapists</h3><p><?= $total ?></p></div>
<div class="box"><h3>Active</h3><p><?= $active ?></p></div>
<div class="box"><h3>Inactive</h3><p><?= $inactive ?></p></div>
</div>

<div class="form-box">

<form method="POST" enctype="multipart/form-data">

<input type="hidden" name="id" value="<?= $edit['id'] ?? '' ?>">

<div class="grid">

<div>
<label>Therapist Name</label>
<input type="text" name="name" required value="<?= $edit['name'] ?? '' ?>">
</div>

<div>
<label>Profile Photo</label>
<input type="file" name="image">
</div>

<div>
<label>Specialty</label>
<input type="text" name="specialty" value="<?= $edit['specialty'] ?? '' ?>">
</div>

<div>
<label>Rating (0-5)</label>
<input type="number" step="0.1" max="5" min="0" name="rating" value="<?= $edit['rating'] ?? '5' ?>">
</div>

<div>
<label>Best Service</label>
<input type="text" name="best_service" value="<?= $edit['best_service'] ?? '' ?>">
</div>

<div>
<label>Status</label>
<select name="status">
<option <?= (($edit['status'] ?? '')=='Active')?'selected':'' ?>>Active</option>
<option <?= (($edit['status'] ?? '')=='Inactive')?'selected':'' ?>>Inactive</option>
</select>
</div>

</div>

<label>Bio / Description</label>
<textarea name="bio"><?= $edit['bio'] ?? '' ?></textarea>

<label>Schedule</label>
<input type="text" name="schedule" value="<?= $edit['schedule'] ?? 'Mon-Sun 3PM-12MN' ?>">

<button type="submit" name="save_therapist">
<?= $edit ? 'Update Therapist' : 'Add Therapist' ?>
</button>

</form>

</div>

<div class="table-box">

<table>

<tr>
<th>Photo</th>
<th>Name</th>
<th>Specialty</th>
<th>Rating</th>
<th>Best Service</th>
<th>Schedule</th>
<th>Status</th>
<th>Action</th>
</tr>

<?php while($row=mysqli_fetch_assoc($list)){ ?>

<tr>

<td>
<img class="thumb" src="../assets/images/therapists/<?= $row['image'] ?: 'default.png' ?>">
</td>

<td><?= $row['name'] ?></td>

<td><?= $row['specialty'] ?></td>

<td>⭐ <?= $row['rating'] ?></td>

<td><?= $row['best_service'] ?></td>

<td><?= $row['schedule'] ?></td>

<td>
<span class="badge <?= $row['status'] ?>">
<?= $row['status'] ?>
</span>
</td>

<td>
<a class="action" href="?edit=<?= $row['id'] ?>">Edit</a>
<a class="action" href="?remove=<?= $row['id'] ?>" onclick="return confirm('Remove therapist?')">Remove</a>
</td>

</tr>

<?php } ?>

</table>

</div>

</div>

</body>
</html>