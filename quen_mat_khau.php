<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quên Mật Khẩu</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f2f2f2;
            font-family: Arial, sans-serif;
        }
        .forgot-password-container {
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
        .forgot-password-container h2 {
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
        .login-link {
            text-align: center;
            margin-top: 30px;
            font-size: 14px;
        }
        .login-link a {
            color: #4682B4;
            text-decoration: none;
            font-weight: bold;
        }
        .login-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="forgot-password-container">
        <a href="javascript:history.back()" class="close-btn">X</a>
        <h2>Quên Mật Khẩu</h2>
 <!-- quên mật khẩu -->
<form action="xu_ly_quen_mat_khau.php" method="POST">
    <div class="form-group">
        <label for="email">Nhập Email của bạn</label>
        <input type="email" id="email" name="email" required>
    </div>
    <button type="submit" class="btn-submit">Gửi mã xác nhận</button>
</form>
        <div class="login-link">
            Quay lại trang <a href="DNhap.php">Đăng Nhập</a>
        </div>
    </div>
</body>
</html>
