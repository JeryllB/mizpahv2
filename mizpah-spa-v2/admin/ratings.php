<?php
session_start();
include '../includes/db.php';

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin'){
    header("Location: ../login.php");
    exit;
}

// DELETE REVIEW
if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM ratings WHERE id='$id'");
    header("Location: ratings.php");
    exit;
}

// FETCH REVIEWS
$query = mysqli_query($conn, "SELECT * FROM ratings ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin Ratings</title>
<link rel="stylesheet" href="../assets/css/admin.css">
</head>

<body>

<?php include __DIR__ . '/includes/sidebar.php'; ?>

<div class="main">

<h2>Customer Ratings</h2>

<table border="1" cellpadding="10" cellspacing="0" style="width:100%; margin-top:20px; color:#fff;">
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
            <a href="?delete=<?= $row['id'] ?>" 
               onclick="return confirm('Delete this review?')" 
               style="color:red;">
               Delete
            </a>
        </td>
    </tr>
    <?php } ?>

</table>

</div>

</body>
</html>