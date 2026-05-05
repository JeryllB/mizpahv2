<?php
include __DIR__ . '/includes/db.php';

header('Content-Type: application/json');

$date = $_GET['date'] ?? '';
$time = $_GET['time'] ?? '';

if (!$date || !$time) {
echo json_encode([]);
exit;
}

$date = mysqli_real_escape_string($conn, $date);
$time = mysqli_real_escape_string($conn, $time);

$sql = "
SELECT name
FROM therapists
WHERE status = 'Active'
AND name NOT IN (
SELECT therapist_id
FROM bookings
WHERE booking_date = '$date'
AND booking_time = '$time'
AND status != 'Cancelled'
)
";

$q = mysqli_query($conn, $sql);

$data = [];

if ($q) {
while ($row = mysqli_fetch_assoc($q)) {
$data[] = $row['name'];
}
}

echo json_encode($data);