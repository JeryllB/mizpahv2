<?php
include 'includes/db.php';

$date = $_GET['date'] ?? '';
$time = $_GET['time'] ?? '';

if(!$date || !$time){
echo json_encode([]);
exit;
}

/* GET ALL THERAPISTS */
$result = mysqli_query($conn,"SELECT * FROM therapists");

$available = [];

while($t = mysqli_fetch_assoc($result)){

$id = $t['id'];
$name = $t['name'];

/* CHECK IF BOOKED */
$check = mysqli_query($conn,"
SELECT * FROM booking_therapists bt
JOIN bookings b ON b.id = bt.booking_id
WHERE bt.therapist_id='$id'
AND b.booking_date='$date'
AND b.booking_time='$time'
AND b.status!='Cancelled'
");

if(mysqli_num_rows($check) == 0){
$available[] = $name;
}

}

echo json_encode($available);