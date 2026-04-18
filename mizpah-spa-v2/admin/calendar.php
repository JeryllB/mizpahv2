<?php
session_start();
include '../includes/db.php';

if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
    exit;
}

/* DATA */
$calendarQuery = mysqli_query($conn,"
SELECT booking_date, COUNT(*) as total
FROM bookings
GROUP BY booking_date
");

$calendarData = [];

while($row = mysqli_fetch_assoc($calendarQuery)){
    $calendarData[$row['booking_date']] = $row['total'];
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Calendar</title>
<link rel="stylesheet" href="../assets/css/admin.css">

<style>
.calendar-table{
  width:100%;
  border-collapse:collapse;
  background:white;
}

.calendar-table th{
  background:#4B2E2A;
  color:white;
  padding:10px;
}

.calendar-table td{
  height:80px;
  text-align:center;
  border:1px solid #eee;
  cursor:pointer;
  vertical-align:top;
}

.booked{
  background:#C89B6A;
  color:white;
}

.badge{
  display:inline-block;
  margin-top:5px;
  font-size:11px;
  background:white;
  color:#4B2E2A;
  padding:2px 6px;
  border-radius:10px;
}

/* MODAL */
.modal{
  display:none;
  position:fixed;
  top:0;left:0;
  width:100%;
  height:100%;
  background:rgba(0,0,0,0.5);
}

.modal-content{
  background:white;
  width:400px;
  margin:10% auto;
  padding:20px;
  border-radius:10px;
}

.close{
  float:right;
  cursor:pointer;
}
</style>

</head>

<body>

<?php include __DIR__ . '/includes/sidebar.php'; ?>

<div class="main">

<h1>Booking Calendar</h1>

<table class="calendar-table">

<tr>
<th>Sun</th><th>Mon</th><th>Tue</th>
<th>Wed</th><th>Thu</th><th>Fri</th><th>Sat</th>
</tr>

<?php
$firstDay = date('w', strtotime(date('Y-m-01')));
$days = date('t');
$month = date('m');
$year = date('Y');

$day = 1;

echo "<tr>";

for($i=0;$i<$firstDay;$i++){
  echo "<td></td>";
}

for($i=$firstDay;$i<7;$i++){

$date = "$year-$month-".str_pad($day,2,"0",STR_PAD_LEFT);
$count = $calendarData[$date] ?? 0;

echo "<td class='".($count>0?"booked":"")."'
onclick=\"openModal('$date')\">

<div>$day</div>";

if($count>0){
  echo "<div class='badge'>$count</div>";
}

echo "</td>";

$day++;
}

echo "</tr>";

while($day <= $days){

echo "<tr>";

for($i=0;$i<7;$i++){

if($day > $days){
  echo "<td></td>";
} else {

$date = "$year-$month-".str_pad($day,2,"0",STR_PAD_LEFT);
$count = $calendarData[$date] ?? 0;

echo "<td class='".($count>0?"booked":"")."'
onclick=\"openModal('$date')\">

<div>$day</div>";

if($count>0){
  echo "<div class='badge'>$count</div>";
}

echo "</td>";

$day++;
}
}

echo "</tr>";
}
?>

</table>

</div>

<!-- MODAL -->
<div id="modal" class="modal">
  <div class="modal-content">

    <span class="close" onclick="closeModal()">X</span>

    <h3>Bookings</h3>
    <div id="modalData">Loading...</div>

  </div>
</div>

<script>
function openModal(date){
  document.getElementById("modal").style.display = "block";
  document.getElementById("modalData").innerHTML = "Loading...";

  fetch("get_bookings.php?date=" + date)
  .then(res => res.text())
  .then(data => {
    document.getElementById("modalData").innerHTML = data;
  });
}

function closeModal(){
  document.getElementById("modal").style.display = "none";
}
</script>

</body>
</html>