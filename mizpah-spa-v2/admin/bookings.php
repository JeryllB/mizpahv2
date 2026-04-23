<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

/* =========================
   BED CHECK (6 LIMIT)
========================= */
function checkBeds($conn, $date, $time) {

    $q = mysqli_query($conn,"
        SELECT COUNT(*) as total 
        FROM bookings
        WHERE booking_date='$date'
        AND booking_time='$time'
        AND status != 'Cancelled'
    ");

    $r = mysqli_fetch_assoc($q);

    return $r['total'];
}

/* =========================
   BED STATUS DISPLAY
========================= */
function bedStatus($count) {

    if ($count >= 6) return "🔴 Full ($count/6)";
    if ($count >= 3) return "🟡 Busy ($count/6)";
    return "🟢 Available ($count/6)";
}

/* =========================
   SUGGEST AVAILABLE TIMES
========================= */
function suggestTimes($conn, $date) {

    $times = ["10:00 AM","12:00 PM","2:00 PM","4:00 PM","6:00 PM","8:00 PM"];

    $available = [];

    foreach ($times as $time) {

        $q = mysqli_query($conn,"
            SELECT COUNT(*) as total
            FROM bookings
            WHERE booking_date='$date'
            AND booking_time='$time'
            AND status != 'Cancelled'
        ");

        $r = mysqli_fetch_assoc($q);

        if ($r['total'] < 6) {
            $available[] = [
                "time" => $time,
                "left" => 6 - $r['total']
            ];
        }
    }

    return $available;
}

/* =========================
   STATUS UPDATE
========================= */
if (isset($_POST['update_status'])) {
    $id = (int)$_POST['id'];
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    mysqli_query($conn, "UPDATE bookings SET status='$status' WHERE id='$id'");
    header("Location: bookings.php");
    exit;
}

/* =========================
   ASSIGN THERAPIST + CHECKS
========================= */
if (isset($_POST['assign_therapist'])) {

    $id = (int)$_POST['id'];
    $therapist_id = (int)$_POST['therapist_id'];

    $b = mysqli_fetch_assoc(mysqli_query($conn,"
        SELECT booking_date, booking_time 
        FROM bookings 
        WHERE id=$id
    "));

    $date = $b['booking_date'];
    $time = $b['booking_time'];

    $count = checkBeds($conn, $date, $time);

    if ($count >= 6) {

        $_SESSION['error'] = "❌ Fully booked (6/6 beds) for this schedule.";

    } else {

        $conflict = mysqli_query($conn,"
            SELECT id FROM bookings
            WHERE therapist_id='$therapist_id'
            AND booking_date='$date'
            AND booking_time='$time'
            AND id != '$id'
        ");

        if (mysqli_num_rows($conflict) > 0) {

            $_SESSION['error'] = "❌ Therapist already booked on this time slot.";

        } else {

            mysqli_query($conn,"
                UPDATE bookings 
                SET therapist_id='$therapist_id'
                WHERE id='$id'
            ");

            $_SESSION['success'] = "✅ Assigned successfully!";
        }
    }

    header("Location: bookings.php");
    exit;
}

/* =========================
   FILTER
========================= */
$search = $_GET['search'] ?? '';
$date   = $_GET['date'] ?? '';

$where = " WHERE 1=1 ";

if ($search != '') {
    $s = mysqli_real_escape_string($conn, $search);
    $where .= " AND (
        customer_name LIKE '%$s%' OR
        phone LIKE '%$s%' OR
        service LIKE '%$s%' OR
        status LIKE '%$s%'
    )";
}

if ($date != '') {
    $d = mysqli_real_escape_string($conn, $date);
    $where .= " AND booking_date='$d'";
}

/* =========================
   DATA
========================= */
$bookings = mysqli_query($conn,"
SELECT bookings.*, therapists.name AS therapist_name
FROM bookings
LEFT JOIN therapists ON bookings.therapist_id = therapists.id
$where
ORDER BY booking_date DESC, booking_time DESC
");

$therapists = mysqli_query($conn,"SELECT * FROM therapists WHERE status='Active'");

/* =========================
   STATS
========================= */
$total = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) as c FROM bookings"))['c'];
$pending = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) as c FROM bookings WHERE status='Pending'"))['c'];
$confirmed = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) as c FROM bookings WHERE status='Confirmed'"))['c'];
$completed = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) as c FROM bookings WHERE status='Completed'"))['c'];
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
    font-size:14px;
}

.main{
    margin-left:250px;
    padding:20px;
}

.topbar{
    display:flex;
    gap:10px;
    flex-wrap:wrap;
    margin-bottom:10px;
}

input,select,button{
    padding:10px;
    border-radius:8px;
    border:none;
    font-size:13px;
}

input,select{
    background:#161616;
    color:#fff;
    border:1px solid #333;
}

button{
    background:#D6C29C;
    color:#111;
    font-weight:bold;
    cursor:pointer;
}

.msg{
    padding:10px;
    border-radius:8px;
    margin-bottom:10px;
}
.error{background:#3b1717;color:#ff9e9e;}
.success{background:#173527;color:#7dffaf;}

.stats{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(120px,1fr));
    gap:10px;
    margin-bottom:15px;
}

.stat{
    background:#161616;
    padding:12px;
    border-radius:10px;
    text-align:center;
}

.stat h3{color:#D6C29C;font-size:12px;margin:0;}
.stat p{font-size:20px;font-weight:bold;margin:5px 0 0;}

table{
    width:100%;
    border-collapse:collapse;
}

th,td{
    padding:12px;
    border-bottom:1px solid #222;
    vertical-align:top;
    font-size:14px;
}

th{
    color:#D6C29C;
    text-align:left;
}

.small{font-size:12px;color:#aaa;}

.suggest{
    margin:10px 0;
    color:#D6C29C;
}

.slot{
    display:inline-block;
    margin-right:8px;
    background:#161616;
    padding:5px 10px;
    border-radius:8px;
    font-size:12px;
}
</style>
</head>

<body>

<?php include __DIR__.'/includes/sidebar.php'; ?>

<div class="main">

<h2>Bookings</h2>

<?php if(isset($_SESSION['error'])): ?>
<div class="msg error"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
<?php endif; ?>

<?php if(isset($_SESSION['success'])): ?>
<div class="msg success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
<?php endif; ?>

<div class="topbar">

<form method="GET">
<input type="text" name="search" placeholder="Search..." value="<?= htmlspecialchars($search) ?>">
<input type="date" name="date" value="<?= htmlspecialchars($date) ?>">
<button>Filter</button>
</form>

</div>

<!-- SUGGEST TIME -->
<?php if($date != ''): ?>
<div class="suggest">
🔥 Available Slots:
<?php foreach(suggestTimes($conn,$date) as $s): ?>
<span class="slot"><?= $s['time'] ?> (<?= $s['left'] ?> left)</span>
<?php endforeach; ?>
</div>
<?php endif; ?>

<div class="stats">
<div class="stat"><h3>Total</h3><p><?= $total ?></p></div>
<div class="stat"><h3>Pending</h3><p><?= $pending ?></p></div>
<div class="stat"><h3>Confirmed</h3><p><?= $confirmed ?></p></div>
<div class="stat"><h3>Completed</h3><p><?= $completed ?></p></div>
</div>

<table>

<tr>
<th>Customer</th>
<th>Service</th>
<th>Schedule</th>
<th>Therapist</th>
<th>Add-ons</th>
<th>Pax</th>
<th>Status</th>
</tr>

<?php while($row=mysqli_fetch_assoc($bookings)): ?>

<?php $beds = checkBeds($conn,$row['booking_date'],$row['booking_time']); ?>

<tr>

<td>
<strong><?= $row['customer_name'] ?></strong><br>
<span class="small"><?= $row['user_id']?'User':'Guest' ?></span>
</td>

<td>
<?= $row['service'] ?><br>
<span class="small"><?= $row['duration'] ?></span>
</td>

<td>
<strong><?= date("M d, Y",strtotime($row['booking_date'])) ?></strong><br>
<?= date("h:i A",strtotime($row['booking_time'])) ?><br>
<span class="small"><?= bedStatus($beds) ?></span>
</td>

<td>
<form method="POST">
<input type="hidden" name="id" value="<?= $row['id'] ?>">
<input type="hidden" name="assign_therapist" value="1">

<select name="therapist_id" onchange="this.form.submit()">
<option value="0">No Pref</option>

<?php
mysqli_data_seek($therapists,0);
while($t=mysqli_fetch_assoc($therapists)):
?>
<option value="<?= $t['id'] ?>" <?= $row['therapist_id']==$t['id']?'selected':'' ?>>
<?= $t['name'] ?>
</option>
<?php endwhile; ?>

</select>
</form>
</td>

<td><?= $row['addons'] ?: 'None' ?></td>
<td><?= $row['pax'] ?: 1 ?></td>

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

</body>
</html>