<?php
session_start();
include '../includes/db.php';

if(!isset($_SESSION['user_id']) || $_SESSION['role']!='admin'){
header("Location: ../login.php");
exit;
}

/* =====================================
   DEFAULT COMMISSION
===================================== */
define('DEFAULT_COMMISSION', 0.60); // 60%

/* =====================================
   ADD COLUMN IF NOT EXISTS (optional)
   therapist_commission column
===================================== */
// Run once manually in phpMyAdmin if wala pa:
// ALTER TABLE therapists ADD commission DECIMAL(5,2) DEFAULT 0.60;

/* =====================================
   SAVE THERAPIST
===================================== */
if(isset($_POST['save_therapist'])){

$id           = intval($_POST['id'] ?? 0);
$name         = mysqli_real_escape_string($conn,$_POST['name']);
$specialty    = mysqli_real_escape_string($conn,$_POST['specialty']);
$best_service = mysqli_real_escape_string($conn,$_POST['best_service']);
$bio          = mysqli_real_escape_string($conn,$_POST['bio']);
$schedule     = mysqli_real_escape_string($conn,$_POST['schedule']);
$status       = mysqli_real_escape_string($conn,$_POST['status']);
$commission   = floatval($_POST['commission']) / 100;

if($commission <= 0){
$commission = DEFAULT_COMMISSION;
}

if($id > 0){

mysqli_query($conn,"
UPDATE therapists SET
name='$name',
specialty='$specialty',
best_service='$best_service',
bio='$bio',
schedule='$schedule',
status='$status',
commission='$commission'
WHERE id='$id'
");

}else{

mysqli_query($conn,"
INSERT INTO therapists
(name,specialty,best_service,bio,schedule,status,commission)
VALUES
('$name','$specialty','$best_service','$bio','$schedule','$status','$commission')
");

}

header("Location: therapists.php");
exit;
}

/* =====================================
   EDIT
===================================== */
$edit = null;

if(isset($_GET['edit'])){
$id = intval($_GET['edit']);
$q = mysqli_query($conn,"SELECT * FROM therapists WHERE id='$id'");
$edit = mysqli_fetch_assoc($q);
}

/* =====================================
   LIST
===================================== */
$list = mysqli_query($conn,"
SELECT 
t.*,

/* completed sessions */
(
SELECT COUNT(DISTINCT bt.booking_id)
FROM booking_therapists bt
JOIN bookings b ON b.id = bt.booking_id
WHERE bt.therapist_id=t.id
AND b.status='Completed'
) as sessions,

/* earnings with commission */
(
SELECT IFNULL(SUM(

(
(b.price * b.pax)

/* divide sa assigned therapists */
/
(
SELECT COUNT(*)
FROM booking_therapists x
WHERE x.booking_id = b.id
)

)

/* multiply therapist commission */
*
IFNULL(t.commission,0.60)

),0)

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
$total = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) total FROM therapists
"))['total'];

$active = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) total FROM therapists WHERE status='Active'
"))['total'];

$inactive = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) total FROM therapists WHERE status='Inactive'
"))['total'];
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
font-family:Poppins,sans-serif;
}

.main{
margin-left:250px;
padding:30px;
}

.card{
background:rgba(255,255,255,.04);
backdrop-filter:blur(10px);
border:1px solid rgba(255,255,255,.08);
padding:20px;
border-radius:14px;
margin-bottom:20px;
}

h2{
color:#D6C29C;
margin-bottom:15px;
}

input,textarea,select{
width:100%;
padding:10px;
margin-top:5px;
margin-bottom:12px;
background:#111;
border:1px solid #333;
color:#fff;
border-radius:8px;
}

button{
background:#D6C29C;
color:#111;
padding:11px 16px;
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
border-bottom:1px solid rgba(255,255,255,.07);
text-align:left;
}

th{
color:#D6C29C;
font-size:14px;
}

tr:hover{
background:rgba(255,255,255,.03);
}

.badge{
padding:5px 10px;
border-radius:30px;
font-size:12px;
font-weight:700;
}

.Active{
background:#173524;
color:#7dffaf;
}

.Inactive{
background:#3a1717;
color:#ff9e9e;
}

a{
color:#D6C29C;
text-decoration:none;
font-weight:600;
}

.small{
font-size:12px;
color:#aaa;
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

<label>Commission (%)</label>
<input type="number" step="1" min="1" max="100"
name="commission"
value="<?= isset($edit['commission']) ? ($edit['commission']*100) : 60 ?>">

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
<th>Commission</th>
<th>Earnings</th>
<th>Status</th>
<th>Action</th>
</tr>

<?php while($row=mysqli_fetch_assoc($list)): ?>

<tr>

<td>
<?= $row['name'] ?><br>
<span class="small"><?= $row['best_service'] ?></span>
</td>

<td><?= $row['specialty'] ?></td>

<td>⭐ <?= number_format($row['rating'],1) ?></td>

<td><?= $row['sessions'] ?></td>

<td><?= number_format(($row['commission'] ?? 0.60)*100,0) ?>%</td>

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