<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Ql_bh";

// Kết nối MySQL
$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
