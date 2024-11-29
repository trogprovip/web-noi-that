<?php
// Bật hiển thị lỗi (chỉ dùng khi phát triển)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Kết nối cơ sở dữ liệu
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Ql_bh";

$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối cơ sở dữ liệu
if ($conn->connect_error) {
    die("Kết nối cơ sở dữ liệu thất bại: " . $conn->connect_error);
}

// Xử lý form khi người dùng gửi yêu cầu
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email']; // Email của người dùng
    $password = $_POST['password']; // Mật khẩu mới
    $confirm_password = $_POST['confirm_password']; // Xác nhận mật khẩu

    // Kiểm tra mật khẩu khớp nhau
    if ($password !== $confirm_password) {
        echo "Mật khẩu và xác nhận mật khẩu không khớp. Vui lòng thử lại.";
        exit;
    }

    // Mã hóa mật khẩu mới
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Cập nhật mật khẩu mới vào cơ sở dữ liệu
    $sql = "UPDATE khachhang SET password = ?, verification_code = NULL WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $hashed_password, $email);

    if ($stmt->execute()) {
        echo "Đặt lại mật khẩu thành công! Đang chuyển hướng về trang đăng nhập...";
        // Chuyển hướng về trang đăng nhập sau 2 giây
        header("Refresh: 2; url=DNhap.php");
        exit;
    } else {
        echo "Có lỗi xảy ra trong quá trình đặt lại mật khẩu. Vui lòng thử lại.";
    }

    $stmt->close();
}

$conn->close();
?>
