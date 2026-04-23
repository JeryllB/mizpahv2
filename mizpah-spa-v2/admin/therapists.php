<?php
session_start();
include '../includes/db.php';

if(!isset($_SESSION['user_id']) || $_SESSION['role']!='admin'){
header("Location: ../login.php");
exit;
}

/* =========================
   ADD / UPDATE THERAPIST
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

if(isset($_FILES['image']) && $_FILES['image']['error']==0 && $_FILES['image']['name']!=""){
$imageName = time().'_'.$_FILES['image']['name'];
move_uploaded_file($_FILES['image']['tmp_name'],"../assets/images/therapists/".$imageName);
}

if($id > 0){

// UPDATE
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

// INSERT NEW
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
   LIST + EARNINGS + SESSIONS
========================= */
$list = mysqli_query($conn,"
SELECT t.*,
IFNULL(SUM(b.price * b.pax),0) as earnings,
COUNT(b.id) as sessions
FROM therapists t
LEFT JOIN bookings b 
ON t.id = b.therapist_id AND b.status='Completed'
GROUP BY t.id
ORDER BY t.id DESC
");

/* =========================
   COUNTS
========================= */
$total = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) total FROM therapists"))['total'];
$active = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) total FROM therapists WHERE status='Active'"))['total'];
$inactive = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) total FROM therapists WHERE status='Inactive'"))['total'];
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Therapists</title>
<link rel="stylesheet" href="../assets/css/admin.css">

<style>
body{
background:#0b0b0b;
color:#fff;
font-family:Poppins;
}

.main{
margin-left:250px;
padding:30px;
}

.card{
background:#161616;
padding:20px;
border-radius:12px;
margin-bottom:20px;
border:1px solid #222;
}

input,textarea,select{
width:100%;
padding:10px;
margin-top:5px;
margin-bottom:10px;
background:#0f0f0f;
border:1px solid #333;
color:#fff;
border-radius:6px;
}

button{
background:#D6C29C;
color:#111;
padding:10px 15px;
border:none;
border-radius:8px;
cursor:pointer;
font-weight:700;
}

table{
width:100%;
border-collapse:collapse;
}

th,td{
padding:12px;
border-bottom:1px solid #222;
text-align:left;
}

th{
color:#D6C29C;
}

.badge{
padding:5px 10px;
border-radius:20px;
font-size:12px;
}

.Active{background:#1f3b22;color:#7dffaf;}
.Inactive{background:#3b1f1f;color:#ff8a8a;}

a{
color:#D6C29C;
text-decoration:none;
font-weight:600;
}
</style>
</head>

<body>

<?php include __DIR__.'/includes/sidebar.php'; ?>

<div class="main">

<h2>Therapists Management</h2>

<!-- STATS -->
<div class="card">
Total: <?= $total ?> |
Active: <?= $active ?> |
Inactive: <?= $inactive ?>
</div>

<!-- ADD / EDIT FORM -->
<div class="card">

<form method="POST" enctype="multipart/form-data">

<input type="hidden" name="id" value="<?= $edit['id'] ?? '' ?>">

<label>Name</label>
<input type="text" name="name" required value="<?= $edit['name'] ?? '' ?>">

<label>Specialty</label>
<input type="text" name="specialty" value="<?= $edit['specialty'] ?? '' ?>">

<label>Rating</label>
<input type="number" step="0.1" max="5" min="0" name="rating" value="<?= $edit['rating'] ?? 5 ?>">

<label>Best Service</label>
<input type="text" name="best_service" value="<?= $edit['best_service'] ?? '' ?>">

<label>Schedule</label>
<input type="text" name="schedule" value="<?= $edit['schedule'] ?? 'Mon-Sun 3PM-12MN' ?>">

<label>Status</label>
<select name="status">
<option value="Active" <?= (($edit['status'] ?? '')=='Active')?'selected':'' ?>>Active</option>
<option value="Inactive" <?= (($edit['status'] ?? '')=='Inactive')?'selected':'' ?>>Inactive</option>
</select>

<label>Bio</label>
<textarea name="bio"><?= $edit['bio'] ?? '' ?></textarea>

<label>Image</label>
<input type="file" name="image">

<button type="submit" name="save_therapist">
<?= $edit ? 'Update Therapist' : 'Add Therapist' ?>
</button>

</form>

</div>

<!-- TABLE -->
<div class="card">

<table>

<tr>
<th>Name</th>
<th>Specialty</th>
<th>Rating</th>
<th>Sessions</th>
<th>Earnings</th>
<th>Status</th>
<th>Action</th>
</tr>

<?php while($row=mysqli_fetch_assoc($list)): ?>

<tr>

<td><?= $row['name'] ?></td>
<td><?= $row['specialty'] ?></td>
<td>⭐ <?= $row['rating'] ?></td>
<td><?= $row['sessions'] ?></td>
<td>₱<?= number_format($row['earnings'],2) ?></td>

<td>
<span class="badge <?= $row['status'] ?>">
<?= $row['status'] ?>
</span>
</td>

<td>
<a href="therapists.php?edit=<?= $row['id'] ?>">Edit</a>
</td>

</tr>

<?php endwhile; ?>

</table>

</div>

</div>

</body>
</html>