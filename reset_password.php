
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
