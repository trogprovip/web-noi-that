<?php
session_start();

// Kết nối cơ sở dữ liệu
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Ql_bh";

$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Kết nối thất bại: ' . $conn->connect_error]));
}

// Lấy dữ liệu từ AJAX hoặc form POST
$email = $_POST['email'];
$password = $_POST['password'];

// Kiểm tra thông tin đăng nhập
$stmt = $conn->prepare("SELECT * FROM khachhang WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();

    if (password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['fullname'] = $user['fullname'];
        $_SESSION['role'] = $user['role']; // role có thể là admin hoặc customer

        // Debug kiểm tra role trước khi gửi về client
        echo json_encode(['success' => true, 'role' => $user['role']]);
        exit;
    } else {
        echo json_encode(['success' => false, 'message' => 'Mật khẩu không đúng.']);
        exit;
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Email không tồn tại.']);
    exit;
}


$stmt->close();
$conn->close();
