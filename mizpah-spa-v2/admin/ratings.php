<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

/* =========================
   HIDE REVIEW (SOFT DELETE)
========================= */
if (isset($_GET['hide'])) {
    $id = (int) $_GET['hide'];

    mysqli_query($conn, "
        UPDATE ratings 
        SET status='hidden' 
        WHERE id=$id
    ");

    header("Location: ratings.php");
    exit;
}

/* =========================
   FETCH REVIEWS
========================= */
$query = mysqli_query($conn, "
    SELECT * FROM ratings
    ORDER BY id DESC
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin Ratings</title>
<link rel="stylesheet" href="../assets/css/admin.css">

<style>
.hide-btn{
    color:red;
    font-weight:bold;
    font-size:18px;
    text-decoration:none;
    cursor:pointer;
    display:inline-block;
}

.hide-btn:hover{
    color:#ff6666;
}
</style>

</head>

<body>

<?php include __DIR__ . '/includes/sidebar.php'; ?>

<div class="main">

<h2>Customer Ratings</h2>

<table border="1" cellpadding="10" cellspacing="0"
style="width:100%; margin-top:20px; color:#fff;">

<tr>
    <th>Name</th>
    <th>Rating</th>
    <th>Message</th>
    <th>Date</th>
    <th>Action</th>
</tr>

<?php while($row = mysqli_fetch_assoc($query)){ ?>

<tr>
    <td><?= htmlspecialchars($row['name']) ?></td>
    <td><?= str_repeat("★", (int)$row['rating']) ?></td>
    <td><?= htmlspecialchars($row['message']) ?></td>
    <td><?= $row['created_at'] ?></td>

    <td>
    <?php if($row['status'] == 'shown'){ ?>

        <a href="ratings.php?hide=<?= $row['id'] ?>"
           onclick="return confirm('Hide this review?')"
           style="
                color:red;
                font-weight:bold;
                font-size:20px;
                text-decoration:none;
                display:inline-block;
                position:relative;
                z-index:9999;
           ">
           ✖
        </a>

    <?php } else { ?>

        <span style="color:gray;">Hidden</span>

    <?php } ?>
</td>

</tr>

<?php } ?>

</table>

</div>

</body>
</html>