<?php
session_start();
include 'includes/db.php';

$therapists = mysqli_query($conn,"
    SELECT * FROM therapists WHERE status='Active'
");
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Therapists</title>

<!-- SAME FONTS AS LANDING -->
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

<style>
body{
    margin:0;
    font-family:Poppins, sans-serif;
    background:#0b0b0b;
    color:#fff;
}

/* HEADER (LIKE LANDING STYLE) */
.header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:18px 30px;
    background:#111;
    border-bottom:1px solid #222;
}

.header-left{
    display:flex;
    align-items:center;
    gap:12px;
}

.header-left img{
    width:40px;
    height:40px;
}

.header-title{
    font-family:'Playfair Display', serif;
    font-size:20px;
    color:#D6C29C;
    font-weight:600;
}

/* BACK BUTTON */
.back-btn{
    text-decoration:none;
    color:#fff;
    background:#222;
    padding:8px 14px;
    border-radius:8px;
    font-size:13px;
    transition:.2s;
}

.back-btn:hover{
    background:#D6C29C;
    color:#111;
}

/* GRID */
.grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(280px,1fr));
    gap:18px;
    padding:25px;
}

/* CARD */
.card{
    background:#161616;
    border:1px solid #222;
    border-radius:16px;
    padding:18px;
    display:flex;
    flex-direction:column;
    justify-content:space-between;
    min-height:180px;
    transition:.2s;
}

.card:hover{
    transform:translateY(-4px);
    border-color:#D6C29C;
}

.name{
    font-family:'Playfair Display', serif;
    font-size:18px;
    font-weight:700;
    color:#fff;
}

.info{
    margin-top:6px;
    font-size:13px;
    color:#aaa;
    line-height:1.4;
}

/* BUTTON */
button{
    background:#D6C29C;
    border:none;
    padding:9px 14px;
    border-radius:10px;
    cursor:pointer;
    font-weight:600;
    transition:.2s;
    font-family:Poppins;
}

button:hover{
    background:#c9b08c;
}

/* MODAL */
.modal{
    display:none;
    position:fixed;
    inset:0;
    background:rgba(0,0,0,0.75);
    justify-content:center;
    align-items:center;
}

.modal-box{
    background:#161616;
    padding:22px;
    width:380px;
    border-radius:14px;
    border:1px solid #333;
    font-family:Poppins;
}

.close{
    float:right;
    cursor:pointer;
    color:#D6C29C;
    font-weight:bold;
    font-size:18px;
}

p{
    margin:6px 0;
    color:#ddd;
    font-family:Poppins;
}
</style>

</head>

<body>

<!-- HEADER -->
<div class="header">

    <div class="header-left">
        <img src="assets/images/logo.png" alt="Logo">
        <div class="header-title">Therapists</div>
    </div>

    <a href="index.php" class="back-btn">← Back to Home</a>

</div>

<!-- GRID -->
<div class="grid">

<?php while($t=mysqli_fetch_assoc($therapists)): ?>

<div class="card">

    <div>
        <div class="name"><?= $t['name'] ?></div>

        <div class="info">
            <?= $t['specialty'] ?><br>
            <?= $t['best_service'] ?>
        </div>
    </div>

    <div style="margin-top:15px;">
        <button onclick="openModal(<?= $t['id'] ?>)">
            View Ratings
        </button>
    </div>

</div>

<?php endwhile; ?>

</div>

<!-- MODAL -->
<div class="modal" id="modal">
    <div class="modal-box">

        <span class="close" onclick="closeModal()">×</span>

        <h3 style="color:#D6C29C;margin-top:0;">Ratings</h3>

        <div id="content">Loading...</div>

    </div>
</div>

<script>
function openModal(id){
    document.getElementById("modal").style.display="flex";

    fetch("rating_popup.php?id="+id)
    .then(res=>res.text())
    .then(data=>{
        document.getElementById("content").innerHTML=data;
    });
}

function closeModal(){
    document.getElementById("modal").style.display="none";
}
</script>

</body>
</html>