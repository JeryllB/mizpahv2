<?php
session_start();
include '../includes/db.php';

if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
    exit;
}

/* UPDATE STATUS */
if(isset($_POST['update_status'])){
    $id = (int)$_POST['id'];
    $status = mysqli_real_escape_string($conn,$_POST['status']);

    mysqli_query($conn,"UPDATE bookings SET status='$status' WHERE id='$id'");
    header("Location: bookings.php");
    exit;
}

/* FILTERS */
$search = $_GET['search'] ?? '';
$date   = $_GET['date'] ?? '';

$where = " WHERE 1=1 ";

if($search != ''){
    $searchSafe = mysqli_real_escape_string($conn,$search);
    $where .= " AND (
        customer_name LIKE '%$searchSafe%' OR
        phone LIKE '%$searchSafe%' OR
        service LIKE '%$searchSafe%'
    )";
}

if($date != ''){
    $dateSafe = mysqli_real_escape_string($conn,$date);
    $where .= " AND booking_date='$dateSafe'";
}

/* COUNTS */
$total = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) total FROM bookings"))['total'];
$pending = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) total FROM bookings WHERE status='Pending'"))['total'];
$confirmed = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) total FROM bookings WHERE status='Confirmed'"))['total'];
$completed = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) total FROM bookings WHERE status='Completed'"))['total'];

/* BOOKINGS */
$bookings = mysqli_query($conn,"
SELECT 
    bookings.*,
    therapists.name AS therapist_name
FROM bookings
LEFT JOIN therapists 
    ON bookings.therapist_id = therapists.id
$where
ORDER BY booking_date DESC, booking_time DESC, id DESC
");
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Bookings</title>

<link rel="stylesheet" href="../assets/css/admin.css">

<style>
body{
font-family:Poppins;
background:#0b0b0b;
color:#fff;
margin:0;
}

/* override sidebar layout */
.main{
margin-left:250px;
padding:20px;
}

/* TOP */
.topbar{
display:flex;
justify-content:space-between;
flex-wrap:wrap;
gap:10px;
margin-bottom:20px;
}

.filters{
display:flex;
gap:10px;
flex-wrap:wrap;
}

input,button,select{
padding:10px;
border-radius:8px;
border:none;
font-size:14px;
}

input{
background:#161616;
color:#fff;
border:1px solid #333;
}

button{
background:#D6C29C;
font-weight:bold;
cursor:pointer;
color:#111;
}

/* STATS */
.stats{
display:grid;
grid-template-columns:repeat(auto-fit,minmax(160px,1fr));
gap:12px;
margin-bottom:20px;
}

.stat{
background:#161616;
padding:15px;
border-radius:10px;
border:1px solid #222;
}

.stat h3{
color:#D6C29C;
font-size:13px;
}

.stat p{
font-size:24px;
font-weight:bold;
}

/* TABLE FIX (NO BOX / NO SCROLL) */
table{
width:100%;
border-collapse:collapse;
table-layout:fixed; /* important */
}

/* ❌ override admin.css nga nag sisira */
th,td{
white-space:normal !important;
max-width:none !important;
overflow:visible !important;
text-overflow:unset !important;

padding:10px;
border-bottom:1px solid #222;
font-size:14px;
vertical-align:top;
}

th{
background:#1d1d1d;
color:#D6C29C;
text-align:left;
}

/* ===== SPA TABLE UPGRADE ===== */

table{
width:100%;
border-collapse:separate;
border-spacing:0 10px; /* space between rows */
table-layout:fixed;
}

tr{
background:#161616;
border-radius:12px;
overflow:hidden;
box-shadow:0 2px 10px rgba(0,0,0,0.3);
}

th{
background:#1d1d1d;
color:#D6C29C;
padding:14px;
text-align:left;
font-size:13px;
letter-spacing:0.5px;
}

td{
padding:14px;
border:none !important;
vertical-align:top;
font-size:14px;
}

/* row hover */
tr:hover{
transform:scale(1.01);
transition:.2s;
background:#1a1a1a;
}

/* customer highlight */
td strong{
color:#fff;
font-size:15px;
}

/* service look */
.service{
font-weight:600;
color:#D6C29C;
}

/* schedule style */
.schedule{
color:#ddd;
font-size:13px;
}

/* notes */
.note{
color:#aaa;
font-size:12px;
line-height:1.4;
}

/* action button cleaner */
.actions select{
padding:8px;
border-radius:6px;
background:#0f0f0f;
color:#fff;
border:1px solid #333;
}

.actions button{
padding:8px 10px;
border-radius:6px;
background:#D6C29C;
border:none;
font-weight:700;
cursor:pointer;
}

/* badge polish */
.badge{
padding:6px 10px;
border-radius:999px;
font-size:12px;
font-weight:600;
}

/* BADGE */
.badge{
padding:5px 10px;
border-radius:20px;
font-size:12px;
display:inline-block;
}

.pending{background:#3a2f12;color:#ffd86b;}
.confirmed{background:#173527;color:#7dffaf;}
.completed{background:#1a2c4b;color:#8fc5ff;}
.cancelled{background:#3b1717;color:#ff9e9e;}

.cash{background:#2d3b2f;color:#7dffaf;}
.gcash{background:#1e2a3b;color:#8fc5ff;}

/* ACTION */
.actions{
display:flex;
gap:8px;
flex-wrap:wrap;
}

.small{font-size:12px;color:#aaa;}
</style>
</head>

<body>

<?php include __DIR__ . '/includes/sidebar.php'; ?>

<div class="main">

<div class="topbar">
    <h2>Bookings Management</h2>

    <form class="filters" method="GET">
        <input type="text" name="search" placeholder="Search..."
        value="<?= htmlspecialchars($search) ?>">

        <input type="date" name="date" value="<?= htmlspecialchars($date) ?>">

        <button type="submit">Filter</button>
    </form>
</div>

<!-- STATS -->
<div class="stats">
    <div class="stat"><h3>Total</h3><p><?= $total ?></p></div>
    <div class="stat"><h3>Pending</h3><p><?= $pending ?></p></div>
    <div class="stat"><h3>Confirmed</h3><p><?= $confirmed ?></p></div>
    <div class="stat"><h3>Completed</h3><p><?= $completed ?></p></div>
</div>

<!-- TABLE (NO WRAPPER BOX) -->
<table>

<tr>
<th>Customer</th>
<th>Service</th>
<th>Schedule</th>
<th>Therapist</th>
<th>Notes</th>
<th>Pax</th>
<th>Payment</th>
<th>Status</th>
<th>Action</th>
</tr>

<?php while($row=mysqli_fetch_assoc($bookings)) {

$statusClass = strtolower($row['status']);
$pay = strtolower($row['payment_method'] ?? 'cash');
?>

<tr>

<td>
<strong><?= htmlspecialchars($row['customer_name']) ?></strong><br>
<span class="small"><?= htmlspecialchars($row['phone']) ?></span>
</td>

<td><?= htmlspecialchars($row['service']) ?></td>

<td>
<?= date("M d, Y", strtotime($row['booking_date'])) ?><br>
<?= date("h:i A", strtotime($row['booking_time'])) ?>
</td>

<td>
<?= $row['therapist_name'] ? htmlspecialchars($row['therapist_name']) : 'No Preference' ?>
</td>

<td>
<?= $row['notes'] ? nl2br(htmlspecialchars($row['notes'])) : '<span class="small">No notes</span>' ?>
</td>

<td><?= $row['pax'] ?? 1 ?></td>

<td>
<span class="badge <?= $pay ?>">
<?= htmlspecialchars($row['payment_method'] ?? 'Cash') ?>
</span>
</td>

<td>
<span class="badge <?= $statusClass ?>">
<?= htmlspecialchars($row['status']) ?>
</span>
</td>

<td>
<form method="POST" class="actions">
<input type="hidden" name="id" value="<?= $row['id'] ?>">

<select name="status">
<option <?= $row['status']=='Pending'?'selected':'' ?>>Pending</option>
<option <?= $row['status']=='Confirmed'?'selected':'' ?>>Confirmed</option>
<option <?= $row['status']=='Completed'?'selected':'' ?>>Completed</option>
<option <?= $row['status']=='Cancelled'?'selected':'' ?>>Cancelled</option>
</select>

<button name="update_status">Save</button>
</form>
</td>

</tr>

<?php } ?>

</table>

</div>

</body>
</html>