<?php
include 'includes/db.php';

$query = mysqli_query($conn, "
    SELECT * FROM ratings 
    WHERE status='approved'
    ORDER BY id DESC
");

while($row = mysqli_fetch_assoc($query)){
?>

<div class="rating-chat">

    <div class="chat-top">
        <div class="avatar">
            <?= strtoupper(substr(htmlspecialchars($row['name']),0,1)) ?>
        </div>

        <div>
            <h4><?= htmlspecialchars($row['name']) ?></h4>
            <small><?= $row['created_at'] ?></small>
        </div>
    </div>

    <div class="stars">
        <?php
        for($i=1;$i<=5;$i++){
            echo ($i <= $row['rating']) ? "★" : "☆";
        }
        ?>
    </div>

    <p><?= htmlspecialchars($row['message']) ?></p>

</div>

<?php } ?>