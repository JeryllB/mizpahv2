<?php
include '../includes/db.php';

if(isset($_POST['submit'])){

$name = $_POST['customer_name'];
$service = $_POST['service'];
$date = $_POST['booking_date'];
$time = $_POST['booking_time'];

/* get service price */
$getPrice = mysqli_query($conn,"
SELECT price FROM services
WHERE service_name='$service'
LIMIT 1
");

$priceRow = mysqli_fetch_assoc($getPrice);
$price = $priceRow['price'] ?? 0;

/* save booking */
mysqli_query($conn,"
INSERT INTO bookings
(customer_name,service,booking_date,booking_time,status,price,payment_status)
VALUES
('$name','$service','$date','$time','Pending','$price','Unpaid')
");

$msg = "Booking Submitted Successfully!";
}

/* services list */
$services = mysqli_query($conn,"
SELECT DISTINCT service_name FROM services
ORDER BY service_name ASC
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Book Appointment</title>
<link rel="stylesheet" href="../assets/css/admin.css">
</head>

<body>

<div class="main" style="margin-left:0; width:100%; max-width:700px; margin:auto;">

<h1>Mizpah Wellness Spa</h1>
<p>Book your next appointment</p>

<?php if(isset($msg)){ ?>
<p style="color:green; font-weight:bold;"><?= $msg ?></p>
<?php } ?>

<form method="POST">

<div class="card">

<p>Full Name</p>
<input type="text" name="customer_name" required style="width:100%; padding:10px;">

<br><br>

<p>Select Service</p>
<select name="service" required style="width:100%; padding:10px;">

<option value="">Choose Service</option>

<?php while($row=mysqli_fetch_assoc($services)){ ?>

<option value="<?= $row['service_name'] ?>">
<?= $row['service_name'] ?>
</option>

<?php } ?>

</select>

<br><br>

<p>Date</p>
<input type="date" name="booking_date" required style="width:100%; padding:10px;">

<br><br>

<p>Time</p>
<input type="time" name="booking_time" required style="width:100%; padding:10px;">

<br><br>

<button class="btn" type="submit" name="submit">
Book Now
</button>

</div>

</form>

</div>

</body>
</html>