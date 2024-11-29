<?php
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

// Nhận dữ liệu từ form đăng ký
$fullname = $_POST['fullname'];
$email = $_POST['email'];
$password = $_POST['password'];

// Kiểm tra xem email đã tồn tại chưa
$sql_check = "SELECT * FROM khachhang WHERE email = ?";
$stmt = $conn->prepare($sql_check);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'Email đã tồn tại. Vui lòng sử dụng email khác.']);
} else {
    // Kiểm tra mật khẩu hợp lệ
    if (strlen($password) < 6) {
        echo json_encode(['success' => false, 'message' => 'Mật khẩu phải có ít nhất 6 ký tự.']);
    } else {
        // Mã hóa mật khẩu
        $password_hashed = password_hash($password, PASSWORD_DEFAULT);

        // Chèn dữ liệu vào bảng khachhang
        $sql = "INSERT INTO khachhang (fullname, email, password) VALUES (?, ?, ?)";
        $stmt_insert = $conn->prepare($sql);
        $stmt_insert->bind_param("sss", $fullname, $email, $password_hashed);

        if ($stmt_insert->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Đăng ký thất bại.']);
        }
    }
}

$stmt->close();
$conn->close();
?>
