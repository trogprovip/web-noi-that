<?php
// Kiểm tra nếu có email gửi tới
if (isset($_GET['email'])) {
    $email = $_GET['email'];
} else {
    echo "Có lỗi xảy ra.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nhập Mã Xác Nhận</title>
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
        .verification-container {
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
        .verification-container h2 {
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
    <div class="verification-container">
        <a href="javascript:history.back()" class="close-btn">X</a>
        <h2>Nhập Mã Xác Nhận</h2>
        <form action="xu_ly_xac_nhan.php" method="POST">
            <div class="form-group">
                <label for="verification_code">Mã Xác Nhận</label>
                <input type="text" id="verification_code" name="verification_code" required>
            </div>
            <input type="hidden" name="email" value="<?= $email ?>"> <!-- Gửi email cùng mã -->
            <button type="submit" class="btn-submit">Xác Nhận</button>
        </form>
    </div>
</body>
</html>
