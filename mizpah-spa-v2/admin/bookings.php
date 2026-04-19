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
$total = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) total FROM bookings
"))['total'];

$pending = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) total FROM bookings WHERE status='Pending'
"))['total'];

$confirmed = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) total FROM bookings WHERE status='Confirmed'
"))['total'];

$completed = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) total FROM bookings WHERE status='Completed'
"))['total'];

/* BOOKINGS */
$bookings = mysqli_query($conn,"
SELECT * FROM bookings
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
.topbar{
display:flex;
justify-content:space-between;
align-items:center;
gap:15px;
flex-wrap:wrap;
margin-bottom:20px;
}

.topbar h1{
margin:0;
}

.filters{
display:flex;
gap:10px;
flex-wrap:wrap;
}

.filters input,
.filters button{
padding:10px 12px;
border-radius:10px;
border:none;
font-size:14px;
}

.filters input{
background:#161616;
color:#fff;
border:1px solid rgba(214,194,156,.12);
}

.filters button{
background:#D6C29C;
color:#111;
font-weight:700;
cursor:pointer;
}

.stats{
display:grid;
grid-template-columns:repeat(auto-fit,minmax(180px,1fr));
gap:14px;
margin-bottom:20px;
}

.stat{
background:#161616;
border:1px solid rgba(214,194,156,.12);
padding:18px;
border-radius:14px;
}

.stat h3{
font-size:14px;
color:#D6C29C;
margin-bottom:8px;
}

.stat p{
font-size:28px;
font-weight:700;
}

.table-wrap{
background:#161616;
border:1px solid rgba(214,194,156,.12);
border-radius:14px;
overflow:auto;
}

table{
width:100%;
border-collapse:collapse;
min-width:1000px;
}

th{
background:#1d1d1d;
color:#D6C29C;
padding:14px;
font-size:14px;
text-align:left;
}

td{
padding:14px;
border-top:1px solid rgba(255,255,255,.05);
font-size:14px;
vertical-align:top;
}

tr:hover{
background:#121212;
}

.badge{
padding:6px 10px;
border-radius:30px;
font-size:12px;
font-weight:700;
display:inline-block;
}

.pending{background:#3a2f12;color:#ffd86b;}
.confirmed{background:#173527;color:#7dffaf;}
.completed{background:#1a2c4b;color:#8fc5ff;}
.cancelled{background:#3b1717;color:#ff9e9e;}

.actions{
display:flex;
gap:8px;
align-items:center;
}

.actions select{
padding:8px;
border-radius:8px;
border:none;
background:#0f0f0f;
color:#fff;
border:1px solid rgba(214,194,156,.1);
}

.actions button{
padding:8px 12px;
border:none;
border-radius:8px;
background:#D6C29C;
font-weight:700;
cursor:pointer;
color:#111;
}

.small{
font-size:12px;
color:#aaa;
margin-top:4px;
}

@media(max-width:900px){
.topbar{
flex-direction:column;
align-items:flex-start;
}
}
</style>
</head>

<body>

<?php include __DIR__ . '/includes/sidebar.php'; ?>

<div class="main">

<div class="topbar">
    <h1>Bookings Management</h1>

    <form class="filters" method="GET">
        <input type="text" name="search" placeholder="Search name / phone / service"
        value="<?= htmlspecialchars($search) ?>">

        <input type="date" name="date" value="<?= htmlspecialchars($date) ?>">

        <button type="submit">Filter</button>
    </form>
</div>

<!-- STATS -->
<div class="stats">

<div class="stat">
<h3>Total Bookings</h3>
<p><?= $total ?></p>
</div>

<div class="stat">
<h3>Pending</h3>
<p><?= $pending ?></p>
</div>

<div class="stat">
<h3>Confirmed</h3>
<p><?= $confirmed ?></p>
</div>

<div class="stat">
<h3>Completed</h3>
<p><?= $completed ?></p>
</div>

</div>

<!-- TABLE -->
<div class="table-wrap">

<table>

<tr>
<th>Customer</th>
<th>Service</th>
<th>Schedule</th>
<th>Pax</th>
<th>Status</th>
<th>Action</th>
</tr>

<?php while($row=mysqli_fetch_assoc($bookings)) { 

$statusClass = strtolower($row['status']);
?>

<tr>

<td>
<strong><?= htmlspecialchars($row['customer_name']) ?></strong>
<div class="small"><?= htmlspecialchars($row['phone']) ?></div>
</td>

<td>
<?= htmlspecialchars($row['service']) ?>
<div class="small"><?= htmlspecialchars($row['notes'] ?? '') ?></div>
</td>

<td>
<?= date("M d, Y", strtotime($row['booking_date'])) ?>
<div class="small"><?= htmlspecialchars($row['booking_time']) ?></div>
</td>

<td><?= $row['pax'] ?? 1 ?></td>

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

</div>

</body>
</html>