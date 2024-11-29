<?php
// Kết nối cơ sở dữ liệu
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Ql_bh";

$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Lấy email và mã xác nhận từ form
$email = $_POST['email'];
$verification_code = $_POST['verification_code'];

// Kiểm tra mã xác nhận
$sql = "SELECT * FROM khachhang WHERE email = ? AND verification_code = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $email, $verification_code);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Nếu mã xác nhận đúng, yêu cầu người dùng nhập mật khẩu mới
    echo "Mã xác nhận hợp lệ. Bạn có thể thay đổi mật khẩu của mình!";
    // Chuyển hướng đến trang thay đổi mật khẩu (reset_password.php) 
    // Sau khi xác nhận mã, bạn có thể yêu cầu người dùng nhập mật khẩu mới và xác nhận lại mật khẩu.
    header("Location: reset_password.php?email=$email");
    exit;
} else {
    echo "Mã xác nhận không đúng hoặc đã hết hạn.";
}

$conn->close();
?>
