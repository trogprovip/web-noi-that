<?php
session_start();

// Kết nối cơ sở dữ liệu
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Ql_bh";

$conn = new mysqli($servername, $username, $password, $dbname);
$password = 'admin123'; // Mật khẩu gốc
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

echo 'Mật khẩu đã mã hóa: ' . $hashed_password;
