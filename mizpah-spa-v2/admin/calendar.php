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

/* TIME SLOTS */
$slots = [
"3:00 PM","4:00 PM","5:00 PM","6:00 PM","7:00 PM",
"8:00 PM","9:00 PM","10:00 PM","11:00 PM",
"12:00 AM","1:00 AM","2:00 AM"
];

/* GET BOOKINGS */
$bookings = [];

$get = mysqli_query($conn,"
SELECT booking_date, booking_time
FROM bookings
");

while($row=mysqli_fetch_assoc($get)){
$key = $row['booking_date']."|".$row['booking_time'];

if(!isset($bookings[$key])){
$bookings[$key]=0;
}

$bookings[$key]++;
}

/* DAY STATUS CALC */
function getDayStatus($date,$slots,$bookings){

$totalFull = 0;

foreach($slots as $slot){
$key = $date."|".$slot;

$count = $bookings[$key] ?? 0;

if($count >= 6){
$totalFull++;
}
}

if($totalFull == count($slots)) return "full";
if($totalFull > 0) return "partial";
return "available";
}

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Calendar</title>
<link rel="stylesheet" href="../assets/css/admin.css">

<style>

.main{
margin-left:250px;
padding:35px;
background:#0b0b0b;
color:#fff;
min-height:100vh;
}

h1{
color:#D6C29C;
margin-bottom:20px;
}

/* CALENDAR */
.calendar-box{
background:#161616;
padding:20px;
border-radius:16px;
border:1px solid rgba(214,194,156,.12);
}

table{
width:100%;
border-spacing:8px;
}

td{
background:#1a1a1a;
height:90px;
border-radius:10px;
padding:8px;
cursor:pointer;
position:relative;
transition:.2s;
text-align:left;
vertical-align:top;
}

td:hover{
transform:scale(1.03);
border:1px solid #D6C29C;
}

/* STATUS COLORS */
.available{border:1px solid rgba(0,255,120,.3);}
.partial{border:1px solid rgba(255,200,0,.4);}
.full{border:1px solid rgba(255,80,80,.5); opacity:.6;}

.num{
font-size:13px;
color:#fff;
opacity:.8;
}

.legend{
display:flex;
gap:15px;
margin-bottom:15px;
font-size:13px;
}

.dot{
width:10px;
height:10px;
border-radius:50%;
display:inline-block;
margin-right:5px;
}

.green{background:#2ecc71;}
.yellow{background:#f1c40f;}
.red{background:#e74c3c;}

/* MODAL */
.modal{
display:none;
position:fixed;
inset:0;
background:rgba(0,0,0,.7);
}

.modal-content{
background:#161616;
margin:8% auto;
padding:25px;
width:420px;
border-radius:14px;
border:1px solid rgba(214,194,156,.2);
}

.close{
float:right;
cursor:pointer;
}

.slot{
display:flex;
justify-content:space-between;
padding:6px 0;
border-bottom:1px solid rgba(255,255,255,.05);
font-size:13px;
}

.fullslot{color:#ff6b6b;}
.availslot{color:#7dffaf;}

</style>
</head>

<body>

<?php include __DIR__.'/includes/sidebar.php'; ?>

<div class="main">

<h1>Realtime Booking Calendar</h1>

<div class="legend">
<span><span class="dot green"></span>Available</span>
<span><span class="dot yellow"></span>Partial</span>
<span><span class="dot red"></span>Fully Booked</span>
</div>

<div class="calendar-box">

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

$status = getDayStatus($date,$slots,$bookings);

echo "
<td class='$status' onclick=\"openModal('$date')\">
<div class='num'>$d</div>
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

<span class="close" onclick="close()">&times;</span>

<h2 id="date"></h2>
<div id="slots"></div>

</div>

</div>

<script>

const slots = <?= json_encode($slots) ?>;
const bookings = <?= json_encode($bookings) ?>;

function openModal(date){

document.getElementById('modal').style.display='block';
document.getElementById('date').innerText=date;

let html="";

slots.forEach(s=>{

let key = date+"|"+s;
let count = bookings[key] ?? 0;

if(count >= 6){
html += `<div class='slot fullslot'>${s} - FULL (6/6)</div>`;
}else{
html += `<div class='slot availslot'>${s} - Available (${count}/6)</div>`;
}

});

document.getElementById('slots').innerHTML=html;

}

function close(){
document.getElementById('modal').style.display='none';
}

window.onclick=function(e){
if(e.target==document.getElementById('modal')){
close();
}
}

</script>

</body>
</html>