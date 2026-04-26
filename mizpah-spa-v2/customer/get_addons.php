<?php
include '../includes/db.php';

$res = mysqli_query($conn,"
SELECT id, service_name, description 
FROM services 
WHERE category='Add-ons'
");

$data = [];

while($row = mysqli_fetch_assoc($res)){
$data[] = $row;
}

header('Content-Type: application/json');
echo json_encode($data);