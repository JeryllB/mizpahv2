<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

/* ================= SESSION ROLE ================= */
$session_role = strtolower($_SESSION['role'] ?? 'guest');
if (!in_array($session_role, ['admin','customer'])) {
    $session_role = 'guest';
}

/* ================= AJAX ASSIGN ================= */
if (isset($_POST['ajax_assign'])) {

    $booking_id = (int)$_POST['booking_id'];
    $therapist_id = (int)$_POST['therapist_id'];

    $assigned_by = ($session_role == 'guest') ? 'guest' : 'customer';

    if ($therapist_id == 0) {
        mysqli_query($conn,"
            DELETE FROM booking_therapists
            WHERE booking_id='$booking_id'
        ");
        exit;
    }

    $b = mysqli_fetch_assoc(mysqli_query($conn,"
        SELECT pax FROM bookings WHERE id='$booking_id'
    "));
    $pax = (int)$b['pax'];

    $countQ = mysqli_query($conn,"
        SELECT COUNT(*) as total
        FROM booking_therapists
        WHERE booking_id='$booking_id'
    ");
    $current = (int)mysqli_fetch_assoc($countQ)['total'];

    $check = mysqli_query($conn,"
        SELECT id FROM booking_therapists
        WHERE booking_id='$booking_id'
        AND therapist_id='$therapist_id'
    ");

    if (mysqli_num_rows($check) > 0) {
        mysqli_query($conn,"
            DELETE FROM booking_therapists
            WHERE booking_id='$booking_id'
            AND therapist_id='$therapist_id'
        ");
        exit;
    }

    if ($current >= $pax) {
        echo "LIMIT";
        exit;
    }

    mysqli_query($conn,"
        INSERT INTO booking_therapists (booking_id, therapist_id, assigned_by)
        VALUES ('$booking_id','$therapist_id','$assigned_by')
    ");

    exit;
}

/* ================= STATUS ================= */
if (isset($_POST['update_status'])) {

    $id = (int)$_POST['id'];
    $status = $_POST['status'];

    mysqli_query($conn,"
        UPDATE bookings 
        SET status='$status' 
        WHERE id='$id'
    ");

    header("Location: bookings.php");
    exit;
}

/* ================= DATA ================= */
$bookings = mysqli_query($conn,"
    SELECT * FROM bookings
    ORDER BY booking_date DESC, booking_time DESC
");

$therapists = mysqli_query($conn,"
    SELECT * FROM therapists WHERE status='Active'
");
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Bookings</title>

<link rel="stylesheet" href="../assets/css/admin.css">

<style>
body{
    margin:0;
    font-family:Poppins, sans-serif;
    background:#0b0b0b;
    color:#fff;
}

.main{
    margin-left:250px;
    padding:25px;
}

table{
    width:100%;
    border-collapse:separate;
    border-spacing:0 10px;
}

tr{ background:#161616; }

td,th{ padding:12px; }

th{ color:#D6C29C; }

/* ROLE */
.role-customer{
    color:#4cc9f0;
    font-weight:bold;
    padding:4px 8px;
    border-radius:6px;
    background:rgba(76,201,240,0.15);
}

.role-guest{
    color:#ff9f43;
    font-weight:bold;
    padding:4px 8px;
    border-radius:6px;
    background:rgba(255,159,67,0.15);
}

/* REMOVE ANY LABEL LOOK */
select{
    width:100%;
    padding:6px;
    background:#0b0b0b;
    color:#fff;
    border:1px solid #333;
}

.badge{
    padding:3px 8px;
    border-radius:20px;
    font-size:11px;
}

.ok{background:#1f3;color:#000;}
.none{background:#444;}

small{color:#aaa;}
</style>
</head>

<body>

<?php include 'includes/sidebar.php'; ?>

<div class="main">

<h2>Bookings</h2>

<table>

<tr>
<th>Customer</th>
<th>Service</th>
<th>Schedule</th>
<th>Therapists</th>
<th>Pax</th>
<th>Status</th>
</tr>

<?php while($row=mysqli_fetch_assoc($bookings)): ?>

<tr>

<td>
<b><?= $row['customer_name'] ?></b><br>
<?php
echo !empty($row['user_id'])
    ? "<span class='role-customer'>customer</span>"
    : "<span class='role-guest'>guest</span>";
?>
</td>

<td>
<?= $row['service'] ?><br>
<small><?= $row['duration'] ?></small>
</td>

<td>
<?= date("M d, Y", strtotime($row['booking_date'])) ?><br>
<?= date("g:i A", strtotime($row['booking_time'])) ?>
</td>

<td>

<?php
$bt = mysqli_query($conn,"
SELECT t.name
FROM booking_therapists bt
JOIN therapists t ON t.id = bt.therapist_id
WHERE bt.booking_id=".$row['id']
);

if(mysqli_num_rows($bt)>0){

echo "<div class='badge ok'>Assigned</div><br>";

while($t=mysqli_fetch_assoc($bt)){
    echo "• {$t['name']}<br>"; // 🔥 NO LABEL ANYMORE
}

}else{
echo "<div class='badge none'>No Therapist</div>";
}
?>

<br>

<!-- THERAPIST CHOICE CLEAN -->
<select onchange="assignTherapist(this,<?= $row['id'] ?>)">
<option value="0">Select Therapist</option>

<?php
mysqli_data_seek($therapists,0);
while($t=mysqli_fetch_assoc($therapists)):
?>
<option value="<?= $t['id'] ?>"><?= $t['name'] ?></option>
<?php endwhile; ?>

</select>

</td>

<td><?= $row['pax'] ?></td>

<td>
<form method="POST">
<input type="hidden" name="id" value="<?= $row['id'] ?>">

<select name="status" onchange="this.form.submit()">
<option <?= $row['status']=='Pending'?'selected':'' ?>>Pending</option>
<option <?= $row['status']=='Confirmed'?'selected':'' ?>>Confirmed</option>
<option <?= $row['status']=='Completed'?'selected':'' ?>>Completed</option>
<option <?= $row['status']=='Cancelled'?'selected':'' ?>>Cancelled</option>
</select>

<input type="hidden" name="update_status" value="1">
</form>
</td>

</tr>

<?php endwhile; ?>

</table>

</div>

<script>
function assignTherapist(el,id){
fetch("bookings.php",{
method:"POST",
headers:{"Content-Type":"application/x-www-form-urlencoded"},
body:`ajax_assign=1&booking_id=${id}&therapist_id=${el.value}`
})
.then(r=>r.text())
.then(res=>{
if(res.trim()=="LIMIT"){
alert("Pax limit reached!");
}else{
location.reload();
}
});
}
</script>

</body>
</html>