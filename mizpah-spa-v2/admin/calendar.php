<?php
session_start();
include '../includes/db.php';

if(!isset($_SESSION['user_id'])){
header("Location: ../login.php");
exit;
}

/* ================= MONTH NAV ================= */
$month = $_GET['month'] ?? date('m');
$year  = $_GET['year'] ?? date('Y');

$month = (int)$month;
$year  = (int)$year;

$firstDay = date('w', strtotime("$year-$month-01"));
$days     = cal_days_in_month(CAL_GREGORIAN, $month, $year);

/* ================= BOOKINGS ================= */
$get = mysqli_query($conn,"
SELECT customer_name, booking_date, booking_time, service
FROM bookings
");

$bookings = [];

while($row = mysqli_fetch_assoc($get)){
$date = $row['booking_date'];

if(!isset($bookings[$date])){
$bookings[$date] = [];
}

$bookings[$date][] = $row;
}

/* ================= STATUS ================= */
function getStatus($count){
if($count >= 8) return 'full';
if($count > 0) return 'partial';
return 'available';
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Calendar</title>
<link rel="stylesheet" href="../assets/css/admin.css">

<style>

/* IMPORTANT FIX FOR SIDEBAR */
.main{
margin-left:250px;
padding:30px;
background:#0b0b0b;
min-height:100vh;
color:#fff;
}

/* HEADER */
h1{
color:#D6C29C;
margin-bottom:20px;
}

/* NAV MONTH */
.nav{
display:flex;
justify-content:space-between;
align-items:center;
margin-bottom:15px;
}

.nav a{
color:#D6C29C;
text-decoration:none;
font-weight:700;
}

/* CALENDAR */
.calendar{
background:#161616;
padding:20px;
border-radius:14px;
border:1px solid rgba(214,194,156,.12);
}

table{
width:100%;
border-spacing:8px;
}

th{
color:#D6C29C;
font-size:13px;
padding:8px;
}

td{
background:#1a1a1a;
height:95px;
border-radius:10px;
padding:8px;
vertical-align:top;
cursor:pointer;
position:relative;
transition:.2s;
}

td:hover{
transform:scale(1.03);
border:1px solid #D6C29C;
}

.day{
font-size:13px;
color:#fff;
opacity:.8;
}

/* DOT INDICATOR */
.dot{
width:8px;
height:8px;
border-radius:50%;
position:absolute;
top:10px;
right:10px;
}

.available .dot{background:#2ecc71;}
.partial .dot{background:#f1c40f;}
.full .dot{background:#e74c3c;}

/* STATUS BORDER */
.available{border:1px solid rgba(46,204,113,.3);}
.partial{border:1px solid rgba(241,196,15,.4);}
.full{border:1px solid rgba(231,76,60,.4);opacity:.6;}

/* MODAL */
.modal{
display:none;
position:fixed;
inset:0;
background:rgba(0,0,0,.7);
z-index:2000;
}

.modal-content{
background:#161616;
margin:8% auto;
padding:20px;
width:400px;
border-radius:12px;
border:1px solid rgba(214,194,156,.2);
max-height:70vh;
overflow:auto;
}

.close{
float:right;
cursor:pointer;
font-size:20px;
}

.item{
padding:10px;
border-bottom:1px solid rgba(255,255,255,.05);
font-size:13px;
}

.item strong{
color:#D6C29C;
}

</style>
</head>

<body>

<?php include __DIR__.'/includes/sidebar.php'; ?>

<div class="main">

<h1>Booking Calendar</h1>

<div class="nav">
<a href="?month=<?= $month-1 ?>&year=<?= $year ?>">← Prev</a>

<h3><?= date("F Y", strtotime("$year-$month-01")) ?></h3>

<a href="?month=<?= $month+1 ?>&year=<?= $year ?>">Next →</a>
</div>

<div class="calendar">

<table>

<tr>
<th>Sun</th><th>Mon</th><th>Tue</th><th>Wed</th>
<th>Thu</th><th>Fri</th><th>Sat</th>
</tr>

<tr>

<?php
for($i=0;$i<$firstDay;$i++){
echo "<td></td>";
}

$count = $firstDay;

for($d=1;$d<=$days;$d++){

$date = "$year-".str_pad($month,2,'0',STR_PAD_LEFT)."-".str_pad($d,2,'0',STR_PAD_LEFT);

$dayBookings = $bookings[$date] ?? [];
$status = getStatus(count($dayBookings));

echo "
<td class='$status' onclick=\"openModal('$date')\">
<div class='day'>$d</div>
<span class='dot'></span>
</td>
";

$count++;

if($count % 7 == 0 && $d != $days){
echo "</tr><tr>";
}
}
?>

</tr>

</table>

</div>

</div>

<!-- MODAL -->
<div class="modal" id="modal">

<div class="modal-content">

<span class="close" onclick="closeModal()">&times;</span>

<h3 id="date"></h3>
<div id="list"></div>

</div>

</div>

<script>

let bookings = <?= json_encode($bookings) ?>;

function openModal(date){

document.getElementById('modal').style.display='block';
document.getElementById('date').innerText=date;

let data = bookings[date] ?? [];

let html = "";

if(data.length === 0){
html = "<p>No bookings</p>";
}else{
data.forEach(b=>{
html += `
<div class="item">
<strong>${b.customer_name}</strong><br>
${b.service}<br>
${b.booking_time}
</div>
`;
});
}

document.getElementById('list').innerHTML = html;
}

function closeModal(){
document.getElementById('modal').style.display='none';
}

window.onclick = function(e){
if(e.target == document.getElementById('modal')){
closeModal();
}
}

</script>

</body>
</html>