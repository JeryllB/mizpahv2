<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

/* ================= CONFLICT CHECK ================= */
function hasConflict($conn, $therapist_id, $date, $time) {

    $q = mysqli_query($conn,"
        SELECT bt.id
        FROM booking_therapists bt
        JOIN bookings b ON b.id = bt.booking_id
        WHERE bt.therapist_id='$therapist_id'
        AND b.booking_date='$date'
        AND b.booking_time='$time'
        AND b.status != 'Cancelled'
    ");

    return mysqli_num_rows($q) > 0;
}

/* ================= ASSIGN THERAPIST ================= */
if (isset($_POST['ajax_assign'])) {

    $booking_id = (int)$_POST['booking_id'];
    $therapist_id = (int)$_POST['therapist_id'];

    if ($therapist_id == 0) exit;

    $b = mysqli_fetch_assoc(mysqli_query($conn,"
        SELECT booking_date, booking_time
        FROM bookings
        WHERE id='$booking_id'
    "));

    $date = $b['booking_date'];
    $time = $b['booking_time'];

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

    } else {

        if (hasConflict($conn, $therapist_id, $date, $time)) {
            echo "CONFLICT";
            exit;
        }

        mysqli_query($conn,"
            INSERT INTO booking_therapists (booking_id, therapist_id)
            VALUES ('$booking_id','$therapist_id')
        ");
    }

    exit;
}

/* ================= STATUS UPDATE (FIXED) ================= */
if (isset($_POST['update_status'])) {

    $id = (int)$_POST['id'];
    $status = mysqli_real_escape_string($conn, $_POST['status']);

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

$therapists = mysqli_query($conn,"SELECT * FROM therapists WHERE status='Active'");
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Bookings</title>
<link rel="stylesheet" href="../assets/css/admin.css">

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    outline:none !important;
}

body{
    font-family:Poppins,sans-serif;
    color:#fff;
    overflow-x:hidden;
    background:#0b0b0b;
}

.main{
    margin-left:250px;
    padding:20px;
}

table{
    width:100%;
    border-collapse:separate !important;
    border-spacing:0 12px !important;
}

th{
    text-align:left;
    padding:14px;
    color:#D6C29C;
    background:transparent !important;
    border-bottom:1px solid rgba(255,255,255,0.08);
}

tr{
    background:rgba(255,255,255,0.04) !important;
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    transition:0.2s;
}

td{
    background:transparent !important;
    padding:14px;
    border:none !important;
    vertical-align:top;
}

tr:hover{
    background:rgba(214,194,156,0.08) !important;
    transform:scale(1.01);
}

select{
    width:100%;
    padding:8px;
    background:#111;
    border:1px solid rgba(255,255,255,0.1);
    color:#fff;
    border-radius:8px;
}

.badge{
    display:inline-block;
    padding:5px 10px;
    border-radius:999px;
    font-size:11px;
    margin-bottom:6px;
}

.ok{
    background:rgba(125,255,175,0.12);
    color:#7dffaf;
}

.none{
    background:rgba(255,224,138,0.12);
    color:#ffe08a;
}

.small{
    font-size:12px;
    color:#aaa;
}
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
<span class="small"><?= $row['phone'] ?></span>
</td>

<td>
<?= $row['service'] ?><br>
<span class="small"><?= $row['duration'] ?></span>
</td>

<td>
<b><?= date("M d, Y",strtotime($row['booking_date'])) ?></b><br>
<?= date("h:i A",strtotime($row['booking_time'])) ?>
</td>

<td>

<?php
$bt = mysqli_query($conn,"
SELECT t.name
FROM booking_therapists bt
JOIN therapists t ON t.id = bt.therapist_id
WHERE bt.booking_id=".$row['id']
);

if(mysqli_num_rows($bt) > 0){
    echo "<div class='badge ok'>Assigned</div><br>";
    while($t=mysqli_fetch_assoc($bt)){
        echo "• ".$t['name']."<br>";
    }
}else{
    echo "<div class='badge none'>No Pref</div>";
}
?>

<br>

<select onchange="assignTherapist(this, <?= $row['id'] ?>)">
<option value="0">No Pref</option>

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
function assignTherapist(el, id){

fetch("bookings.php", {
    method:"POST",
    headers:{"Content-Type":"application/x-www-form-urlencoded"},
    body:`ajax_assign=1&booking_id=${id}&therapist_id=${el.value}`
})
.then(res=>res.text())
.then(res=>{
    if(res.trim()=="CONFLICT"){
        alert("❌ Conflict schedule!");
    } else {
        location.reload();
    }
});
}
</script>

</body>
</html>