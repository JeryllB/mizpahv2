<?php
include 'includes/db.php';

header('Content-Type: application/json');

$date = $_POST['date'] ?? '';
$time = $_POST['time'] ?? '';

if(!$date || !$time){
echo json_encode([
"count" => 0,
"available" => false
]);
exit;
}

/* GET TOTAL PAX (REAL CAPACITY LOGIC) */
$q = mysqli_query($conn,"
SELECT COALESCE(SUM(pax),0) as total_pax
FROM bookings
WHERE booking_date='$date'
AND booking_time='$time'
AND status!='Cancelled'
");

$row = mysqli_fetch_assoc($q);

$total = (int)$row['total_pax'];
$available = $total < 6;

echo json_encode([
"count" => $total,
"available" => $available,
"remaining" => max(0, 6 - $total)
]);
?>