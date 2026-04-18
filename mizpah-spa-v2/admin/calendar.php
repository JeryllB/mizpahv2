<?php
session_start();
include '../includes/db.php';

if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
    exit;
}

$month = date('m');
$year = date('Y');

$days = cal_days_in_month(CAL_GREGORIAN,$month,$year);
$firstDay = date('w', strtotime("$year-$month-01"));

$bookings = [];

$get = mysqli_query($conn,"
SELECT booking_date, customer_name, service, status
FROM bookings
ORDER BY booking_date ASC
");

while($row=mysqli_fetch_assoc($get)){
    $bookings[$row['booking_date']][] = $row;
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Calendar</title>
<link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>

<?php include __DIR__.'/includes/sidebar.php'; ?>

<div class="main">

<h1>Booking Calendar</h1>

<div class="calendar-box">

<table class="calendar-table">

<tr>
<th>Sun</th>
<th>Mon</th>
<th>Tue</th>
<th>Wed</th>
<th>Thu</th>
<th>Fri</th>
<th>Sat</th>
</tr>

<tr>

<?php
for($i=0;$i<$firstDay;$i++){
echo "<td></td>";
}

$count = $firstDay;

for($d=1;$d<=$days;$d++){

$date = "$year-".str_pad($month,2,'0',STR_PAD_LEFT)."-".str_pad($d,2,'0',STR_PAD_LEFT);

$class="";
$badge="";
$details="No bookings.";

if(isset($bookings[$date])){

$total = count($bookings[$date]);

$class="booked";
$badge="<div class='badge'>$total Booking</div>";

$details="";

foreach($bookings[$date] as $b){
$details .= $b['customer_name']." - ".$b['service']." (".$b['status'].")\n";
}

}

echo "
<td class='$class' onclick=\"openModal('$date',`$details`)\" >
<div class='num'>$d</div>
$badge
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
<div class="modal" id="dayModal">

<div class="modal-content">

<span class="close" onclick="closeModal()">&times;</span>

<h2 id="modalDate"></h2>
<pre id="modalDetails" style="white-space:pre-wrap;"></pre>

</div>

</div>

<script>
function openModal(date, details){
document.getElementById("dayModal").style.display="block";
document.getElementById("modalDate").innerText=date;
document.getElementById("modalDetails").innerText=details;
}

function closeModal(){
document.getElementById("dayModal").style.display="none";
}

window.onclick=function(e){
if(e.target==document.getElementById("dayModal")){
closeModal();
}
}
</script>

</body>
</html>