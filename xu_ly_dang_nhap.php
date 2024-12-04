<?php

session_start();  // Bắt đầu session

// Kết nối đến cơ sở dữ liệu
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Ql_bh"; // Tên cơ sở dữ liệu

$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Kết nối thất bại: ' . $conn->connect_error]));
}

// Lấy dữ liệu từ AJAX
$email = $_POST['email'];
$password = $_POST['password'];

// Sử dụng Prepared Statement để kiểm tra người dùng
$stmt = $conn->prepare("SELECT * FROM khachhang WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Lấy dữ liệu người dùng
    $user = $result->fetch_assoc();

    // Kiểm tra mật khẩu
    if (password_verify($password, $user['password'])) {
        // Đăng nhập thành công, lưu thông tin người dùng vào session
        $_SESSION['user_id'] = $user['id']; // Lưu id người dùng
        $_SESSION['fullname'] = $user['fullname']; // Lưu tên người dùng

        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Mật khẩu không đúng.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Email không tồn tại.']);
}

$stmt->close();
$conn->close();
?>
