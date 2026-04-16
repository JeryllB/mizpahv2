<?php

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "mizpah_spa";
$port = 3307;

$conn = mysqli_connect($host, $user, $pass, $dbname, $port);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

?>