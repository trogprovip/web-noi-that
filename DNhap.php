
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f2f2f2;
            font-family: Arial, sans-serif;
        }
        .login-container {
            width: 300px;
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
            font-size: 15px;
            color: #333;
            text-decoration: none;
            font-weight: bold;
            cursor: pointer;
        }
        .login-container h2 {
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
        .error-message {
            color: red;
            font-size: 14px;
            margin-top: 5px;
            margin-bottom: 4px;
            display: none; /* Ẩn lỗi ban đầu */
        }
        .register-link {
            text-align: center;
            margin-top: 30px;
            font-size: 14px;
        }
        .register-link a {
            color: #4682B4;
            text-decoration: none;
            font-weight: bold;
        }
        .register-link a:hover {
            text-decoration: underline;
        }
        .forgot-password-link {
            text-align: center;
            font-size: 14px;
            margin-top: 10px;
        }
        .forgot-password-link a {
            color: #4682B4;
            text-decoration: none;
            font-weight: bold;
            margin-left: 190px;
        }
        .forgot-password-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <a href="javascript:void(0);" class="close-btn" onclick="window.history.back();">X</a>
        <h2>Đăng Nhập</h2>
        <form id="loginForm" method="POST">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Mật khẩu</label>
                <input type="password" id="password" name="password" required>
            </div>
            <!-- Thông báo lỗi -->
            <div id="error-message" class="error-message"></div>
            <div class="forgot-password-link">
                <a href="quen_mat_khau.php">Quên mật khẩu?</a>
            </div>
            <button type="submit" class="btn-submit">Đăng Nhập</button>
        </form>
        <div class="register-link">
            Chưa có tài khoản? <a href="Đki.php">Đăng ký</a>
        </div>
    </div>

    <script>
        // Xử lý gửi form qua AJAX
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            e.preventDefault(); // Ngăn form gửi thông thường

            // Lấy giá trị email và mật khẩu
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;

            // Gửi yêu cầu AJAX
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'xu_ly_dang_nhap.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                if (xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        // Chuyển hướng nếu đăng nhập thành công
                        window.location.href = 'Webbh.php';
                    } else {
                        // Hiển thị lỗi
                        const errorMessage = document.getElementById('error-message');
                        errorMessage.textContent = response.message;
                        errorMessage.style.display = 'block';
                    }
                }
            };
            xhr.send(`email=${encodeURIComponent(email)}&password=${encodeURIComponent(password)}`);
        });
    </script>
</body>
</html>
