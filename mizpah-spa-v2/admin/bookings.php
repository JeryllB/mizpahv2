<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

/* =========================
   THERAPIST CONFLICT CHECK
========================= */
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

/* =========================
   ASSIGN / REMOVE THERAPIST
========================= */
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

/* =========================
   STATUS UPDATE + ⭐ RATING SYSTEM ADDED
========================= */
if (isset($_POST['update_status'])) {

    $id = (int)$_POST['id'];
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    mysqli_query($conn,"
        UPDATE bookings 
        SET status='$status' 
        WHERE id='$id'
    ");

    /* =========================
       AUTO INSERT THERAPIST RATINGS
       WHEN COMPLETED
    ========================= */
    if ($status == 'Completed') {

        $q = mysqli_query($conn,"
            SELECT therapist_id 
            FROM booking_therapists
            WHERE booking_id='$id'
        ");

        while ($t = mysqli_fetch_assoc($q)) {

            $therapist_id = $t['therapist_id'];

            // default rating (5 stars auto)
            $rating = 5;

            mysqli_query($conn,"
                INSERT INTO therapist_ratings (booking_id, therapist_id, rating)
                VALUES ('$id', '$therapist_id', '$rating')
            ");
        }
    }

    exit;
}

/* =========================
   DATA
========================= */
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
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Bookings</title>

<link rel="stylesheet" href="../assets/css/admin.css">

<style>
body{
    margin:0;
    font-family:Poppins;
    background:#0b0b0b;
    color:#fff;
}

.main{
    margin-left:250px;
    padding:20px;
}

table{
    width:100%;
    border-collapse:collapse;
}

th,td{
    padding:12px;
    border-bottom:1px solid #222;
    vertical-align:top;
}

th{
    color:#D6C29C;
    text-align:left;
}

select{
    padding:6px;
    background:#161616;
    color:#fff;
    border:1px solid #333;
    border-radius:6px;
}

.badge{
    font-size:12px;
    padding:3px 6px;
    border-radius:6px;
    display:inline-block;
    margin-bottom:5px;
}

.ok{
    background:#1f3d2b;
    color:#7dffaf;
}

.none{
    background:#3d3a1f;
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
<option value="<?= $t['id'] ?>">
<?= $t['name'] ?>
</option>
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
        alert("❌ Therapist already booked on this schedule!");
    } else {
        location.reload();
    }
});
}
</script>

</body>
</html>