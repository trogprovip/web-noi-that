<?php

session_start();

// if (!isset($_SESSION['logged_in']) || $_SESSION['role'] != 'admin') {
//     header("Location: login.php");
//     exit();
// }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Trang quản lý bán hàng</title>
    <style>
        /* Cấu trúc và style của trang admin giống như đã thiết kế */
       /* Cấu trúc cơ bản */
       body {
            font-family: Arial, sans-serif;
            background-color: #f0f8ff;
            color: #333;
            margin: 0;
            padding: 0;
        }

        /* Thanh menu */
        .menu {
            width: 200px;
            background-color: #003366; /* Màu xanh đậm */
            padding: 15px;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
            position: fixed;
            height: 100%;
            top: 0;
            left: 0;
            overflow-y: auto;
        }
        .menu h3 {
            color: white;
            text-align: center;
            margin-bottom: 20px;
        }
        .menu a {
            color: white;
            text-decoration: none;
            display: block;
            padding: 10px;
            margin: 5px 0;
            border-radius: 4px;
            transition: background-color 0.3s;
            font-weight: bold; /* Làm chữ in đậm */

        }
        .menu a:hover {
            background-color: #002244;
        }

        .content {
            margin-left: 220px;
            padding: 20px;
            text-align: center;
        }
        h1 {
            color: #003366;
            font-size: 32px;
            font-weight: bold;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="menu">
    <h3>Quản lý nội thất</h3>
    <a href="admin.php">Trang chủ</a>
    <a href="admin_products.php">Quản lý sản phẩm</a>
    <a href="add_product.php">Thêm sản phẩm mới</a>
    <a href="admin_orders.php">Quản lý đơn hàng</a>
    <a href="khachhang.php">Quản lý khách hàng</a>
    <a href="stats.php">Thống kê doanh thu</a>
    <a href="webbh.php">Quay lại Web</a>
</div>

<div class="content">
    <h1>Chào mừng bạn đến với trang quản lý bán hàng</h1>
</div>

</body>
</html>
