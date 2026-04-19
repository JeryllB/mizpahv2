<?php
include 'includes/db.php';

$date = $_POST['date'];
$time = $_POST['time'];

$q = mysqli_query($conn,"
SELECT COUNT(*) as total
FROM bookings
WHERE booking_date='$date'
AND booking_time='$time'
");

$row = mysqli_fetch_assoc($q);

echo json_encode([
"count" => $row['total'],
"available" => $row['total'] < 6
]);
?>