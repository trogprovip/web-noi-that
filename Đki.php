<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Ký Tài Khoản</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f2f2f2;
            font-family: Arial, sans-serif;
        }
        .register-container {
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
        .register-container h2 {
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
        .error-message {
            margin-bottom: 4px;
            color: red;
            font-size: 14px;
            margin-top: 10px;
            display: none; /* Ẩn lỗi ban đầu */
        }
    </style>
</head>
<body>
    <div class="register-container">
        <a href="javascript:history.back()" class="close-btn">X</a>
        <h2>Đăng Ký Tài Khoản</h2>
        <form id="registerForm">
            <div class="form-group">
                <label for="fullname">Họ tên</label>
                <input type="text" id="fullname" name="fullname" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Mật khẩu</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Xác nhận lại mật khẩu</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            <!-- Thông báo lỗi -->
            <div id="error-message" class="error-message"></div>
            <button type="submit" class="btn-submit">Đăng Ký</button>
        </form>
        <div class="login-link">
            Đã có tài khoản? <a href="DNhap.php">Đăng nhập</a>
        </div>
    </div>

    <script>
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            e.preventDefault(); // Ngăn form gửi thông thường

            const fullname = document.getElementById('fullname').value;
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const confirm_password = document.getElementById('confirm_password').value;

            // Kiểm tra mật khẩu khớp không
            if (password !== confirm_password) {
                document.getElementById('error-message').textContent = 'Mật khẩu không khớp!';
                document.getElementById('error-message').style.display = 'block';
                return;
            }

            // Gửi dữ liệu đăng ký qua AJAX
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'xu_ly_dang_ky.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                if (xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        window.location.href = 'DNhap.php'; // Chuyển hướng đến trang đăng nhập
                    } else {
                        document.getElementById('error-message').textContent = response.message;
                        document.getElementById('error-message').style.display = 'block';
                    }
                }
            };
            xhr.send(`fullname=${encodeURIComponent(fullname)}&email=${encodeURIComponent(email)}&password=${encodeURIComponent(password)}`);
        });
    </script>
</body>
</html>
