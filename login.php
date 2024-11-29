<?php
session_start();

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Kiểm tra thông tin đăng nhập
    if ($email == "info@gmail.com" && $password == "bannoithat") {
        $_SESSION['logged_in'] = true; // Lưu trạng thái đăng nhập vào session
        header("Location: admin.php"); // Chuyển hướng đến trang admin
        exit();
    } else {
        $error = "Email hoặc mật khẩu không đúng!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Đăng nhập</title>
    <style>
        /* Style cơ bản cho trang đăng nhập */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #e3f2fd; 
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            color: #333;
            text-align: center;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            height: 100%;
        }

        .login-form {
            background-color: #ffffff;
            padding: 40px 50px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 51, 102, 0.1);
            width: 100%;
            max-width: 380px;
            text-align: center;
            transition: transform 0.3s ease;
        }

        .login-form:hover {
            transform: translateY(-5px);
        }

        .login-form h2 {
            color: #003366; 
            font-size: 30px;
            margin-bottom: 20px;
            font-weight: bold;
            letter-spacing: 1px;
        }

        .login-form input[type="email"],
        .login-form input[type="password"],
        .login-form button {
            width: 100%;
            padding: 15px;
            margin: 12px 0;
            border: 2px solid #003366; 
            border-radius: 8px;
            font-size: 18px;
            color: #003366; /
        }

        .login-form input[type="email"],
        .login-form input[type="password"] {
            background-color: #f4f8f9;
            box-sizing: border-box;
        }

        .login-form input[type="email"]:focus,
        .login-form input[type="password"]:focus {
            outline: none;
            border-color: #004d80; 
            box-shadow: 0 0 5px rgba(0, 77, 128, 0.6);
        }

        .login-form button {
            background-color: #003366; 
            color: white;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .login-form button:hover {
            background-color: #004d80; 
        }

        .error {
            color: #d32f2f;
            margin-top: 15px;
            font-size: 16px;
            font-weight: bold;
        }

        /* Responsive layout */
        @media (max-width: 400px) {
            .login-form {
                width: 90%;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="login-form">
        <h2>Đăng nhập</h2>
        <form action="login.php" method="POST">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Mật khẩu" required>
            <button type="submit" name="login">Đăng nhập</button>
            <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
        </form>
    </div>
</div>

</body>
</html>
