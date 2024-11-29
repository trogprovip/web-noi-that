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

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt lại Mật khẩu</title>
    <style>
        /* Cấu hình CSS của form */
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f2f2f2;
            font-family: Arial, sans-serif;
        }
        .reset-password-container {
            width: 350px;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            position: relative;
        }
        .close-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 20px;
            color: #333;
            text-decoration: none;
            font-weight: bold;
        }
        .reset-password-container h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            font-weight: bold;
            color: #333;
            display: block;
            margin-bottom: 5px;
        }
        .form-group input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .btn-submit {
            width: 100%;
            padding: 10px;
            background-color: #4682B4;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
        }
        .btn-submit:hover {
            background-color: #5a9bd3;
        }
    </style>
</head>
<body>
    <div class="reset-password-container">
        <a href="javascript:history.back()" class="close-btn">X</a>
        <h2>Đặt lại Mật khẩu</h2>
        <form action="xu_ly_reset_password.php" method="POST">
            <div class="form-group">
                <label for="password">Mật khẩu mới</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Xác nhận lại mật khẩu</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            <input type="hidden" name="email" value="<?= $_GET['email']; ?>">
            <button type="submit" class="btn-submit">Đặt lại mật khẩu</button>
        </form>
    </div>
</body>
</html>
