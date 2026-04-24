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
$best_service = mysqli_real_escape_string($conn,$_POST['best_service']);
$bio          = mysqli_real_escape_string($conn,$_POST['bio']);
$schedule     = mysqli_real_escape_string($conn,$_POST['schedule']);
$status       = mysqli_real_escape_string($conn,$_POST['status']);

if($id > 0){

mysqli_query($conn,"UPDATE therapists SET
name='$name',
specialty='$specialty',
best_service='$best_service',
bio='$bio',
schedule='$schedule',
status='$status'
WHERE id='$id'");

}else{

mysqli_query($conn,"INSERT INTO therapists
(name,specialty,best_service,bio,schedule,status)
VALUES
('$name','$specialty','$best_service','$bio','$schedule','$status')");

}

header("Location: therapists.php");
exit;
}

/* =========================
   EDIT
========================= */
$edit = null;

if(isset($_GET['edit'])){
$id = intval($_GET['edit']);
$q = mysqli_query($conn,"SELECT * FROM therapists WHERE id='$id'");
$edit = mysqli_fetch_assoc($q);
}

/* =========================
   LIST + EARNINGS + RATINGS
========================= */
$list = mysqli_query($conn,"
SELECT 
t.*,

/* sessions */
(
SELECT COUNT(DISTINCT bt.booking_id)
FROM booking_therapists bt
JOIN bookings b ON b.id = bt.booking_id
WHERE bt.therapist_id=t.id
AND b.status='Completed'
) as sessions,

/* earnings */
(
SELECT IFNULL(SUM(500),0)
FROM booking_therapists bt
JOIN bookings b ON b.id = bt.booking_id
WHERE bt.therapist_id=t.id
AND b.status='Completed'
) as earnings,

/* ratings */
(
SELECT IFNULL(AVG(rating),0)
FROM therapist_ratings tr
WHERE tr.therapist_id=t.id
) as rating

FROM therapists t
ORDER BY t.id DESC
");

/* COUNTS */
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

<div class="card">
Total: <?= $total ?> |
Active: <?= $active ?> |
Inactive: <?= $inactive ?>
</div>

<!-- FORM -->
<div class="card">

<form method="POST">

<input type="hidden" name="id" value="<?= $edit['id'] ?? '' ?>">

<label>Name</label>
<input type="text" name="name" required value="<?= $edit['name'] ?? '' ?>">

<label>Specialty</label>
<input type="text" name="specialty" value="<?= $edit['specialty'] ?? '' ?>">

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
<td>⭐ <?= number_format($row['rating'],1) ?></td>
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