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

// Lấy email từ form
$email = $_POST['email'];

// Kiểm tra xem email có tồn tại trong cơ sở dữ liệu không
$sql = "SELECT * FROM khachhang WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Tạo mã xác nhận ngẫu nhiên
    $verification_code = bin2hex(random_bytes(6));  // Tạo mã xác nhận ngẫu nhiên (12 ký tự hex)

    // Lưu mã xác nhận vào cơ sở dữ liệu
    $user = $result->fetch_assoc();
    $sql_update = "UPDATE khachhang SET verification_code = ? WHERE email = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("ss", $verification_code, $email);
    $stmt_update->execute();

    // Gửi email chứa mã xác nhận
    $subject = "Mã xác nhận đặt lại mật khẩu";
    $message = "Mã xác nhận của bạn để đặt lại mật khẩu là: $verification_code";
    $headers = "From: no-reply@yourdomain.com";

    if (mail($email, $subject, $message, $headers)) {
        echo "Mã xác nhận đã được gửi đến email của bạn.";
        // Chuyển hướng đến trang nhập mã xác nhận
        header("Location: nhap_ma_xac_nhan.php?email=$email");
        exit;
    } else {
        echo "Có lỗi xảy ra khi gửi email.";
    }
} else {
    echo "Email không tồn tại.";
}

$conn->close();
?>
