<?php
session_start();
include 'includes/db.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST['email'];
    $password = md5($_POST['password']);

    $query = "SELECT * FROM users WHERE email='$email' AND password='$password'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {

        $user = mysqli_fetch_assoc($result);

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['name'] = $user['name'];

        if ($user['role'] == 'admin') {
            header("Location: admin/dashboard.php");
        } else {
            header("Location: customer/dashboard.php");
        }
        exit();

    } else {
        $error = "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Login</title>

<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600&family=Poppins:wght@300;400;500&display=swap" rel="stylesheet">

<style>

/* RESET */
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

/* FULL CENTER FIX */
body{
    font-family:'Poppins', sans-serif;
    background:#0b0b0b;
    height:100vh;

    display:flex;
    justify-content:center;
    align-items:center;
}

/* BACKDROP */
.login-wrapper{
    position:fixed;
    inset:0;

    display:flex;
    justify-content:center;
    align-items:center;

    background:
    linear-gradient(135deg, rgba(0,0,0,0.75), rgba(20,20,20,0.9)),
    url('assets/images/spa-bg.jpg') center/cover no-repeat;
}

/* CARD */
.login-card{
    width:100%;
    max-width:380px;

    background:#161616;
    padding:40px 30px;
    border-radius:14px;

    border:1px solid rgba(214,194,156,.2);

    text-align:center;

    box-shadow:0 25px 60px rgba(0,0,0,0.7);
}

/* LOGO */
.login-logo{
    width:70px;
    margin-bottom:15px;
}

/* TITLE */
.login-card h2{
    font-family:'Playfair Display', serif;
    color:#D6C29C;
    font-size:26px;
}

/* SUBTEXT */
.login-card p{
    font-size:13px;
    color:#aaa;
    margin:10px 0 25px;
}

/* ERROR */
.error{
    background:rgba(255,0,0,0.1);
    color:#ff6b6b;
    padding:10px;
    border-radius:8px;
    margin-bottom:15px;
    font-size:13px;
}

/* INPUT */
input{
    width:100%;
    padding:12px;

    margin-bottom:12px;

    border-radius:8px;
    border:1px solid rgba(214,194,156,.2);

    background:#0b0b0b;
    color:#fff;

    outline:none;
}

input::placeholder{
    color:#777;
}

input:focus{
    border-color:#D6C29C;
    box-shadow:0 0 10px rgba(214,194,156,0.15);
}

/* BUTTON */
button{
    width:100%;
    padding:12px;

    border:none;
    border-radius:8px;

    background:#D6C29C;
    color:#111;

    font-weight:600;
    cursor:pointer;

    transition:.2s;
}

button:hover{
    transform:translateY(-2px);
}

/* FOOTER */
.footer-text{
    margin-top:15px;
    font-size:11px;
    color:#666;
}

</style>

</head>

<body>

<div class="login-wrapper">

    <div class="login-card">

        <img src="assets/images/logo.png" class="login-logo">

        <h2>Mizpah Spa</h2>
        <p>Luxury Healing • Calm Experience</p>

        <?php if($error != "") { ?>
            <div class="error"><?= $error ?></div>
        <?php } ?>

        <form method="POST">
            <input type="email" name="email" placeholder="Email Address" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>

        <div class="footer-text">© 2026 Mizpah Spa</div>

    </div>

</div>

</body>
</html>