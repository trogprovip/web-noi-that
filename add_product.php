<?php
include 'db_connect.php'; 
if (isset($_POST['add_product'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category = $_POST['category'];
    $discount = $_POST['discount'];

    $target_dir = "uploads/"; 
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);

    $query = "INSERT INTO products (name, description, price, category, image, discount) 
              VALUES ('$name', '$description', '$price', '$category', '$target_file', '$discount')";
    mysqli_query($conn, $query);
    header("Location: admin_products.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Thêm sản phẩm</title>
    <style>
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

        /* Nội dung chính */
        .content {
            margin-left: 220px; /* Để tránh bị che khuất bởi menu */
            padding: 20px;
        }

        /* Tiêu đề chính của trang */
        h1 {
            color: #003366;
            font-size: 32px;
            font-weight: bold;
            margin-bottom: 20px;
            text-align: center;
        }

        /* Khung chứa form */
        form {
            width: 60%; /* Chiều rộng form vừa phải */
            max-width: 600px; /* Giới hạn chiều rộng tối đa */
            padding: 35px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 51, 102, 0.3);
            margin: 0 auto; /* Đặt form vào giữa */
        }

        /* Các trường input */
        form input[type="text"],
        form input[type="number"],
        form input[type="file"],
        form textarea {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #003366;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 16px;
            color: #003366;
        }

        /* Placeholder */
        input::placeholder,
        textarea::placeholder {
            color: #7a7a7a;
        }

        /* Nút Thêm sản phẩm */
        form button {
            width: 100%;
            padding: 14px;
            background-color: #003366;
            color: #ffffff;
            border: none;
            border-radius: 4px;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-top: 10px;
        }

        form button:hover {
            background-color: #004080;
        }

        /* Style cho textarea */
        form textarea {
            height: 100px;
            resize: vertical;
        }

    </style>
</head>
<body>

    <!-- Thanh menu -->
    <div class="menu">
        <h3>Quản lý nội thất</h3>
        <a href="admin.php">Trang chủ</a>
        <a href="admin_products.php">Quản lý sản phẩm</a>
        <a href="add_product.php" class="active">Thêm sản phẩm mới</a>
        <a href="admin_orders.php">Quản lý đơn hàng</a>
        <a href="khachhang.php">Quản lý khách hàng</a>
        <a href="stats.php">Thống kê doanh thu</a>
    </div>

    <!-- Nội dung chính -->
    <div class="content">
        <h1>Thêm sản phẩm mới</h1>
        <form action="add_product.php" method="POST" enctype="multipart/form-data">
            <input type="text" name="name" placeholder="Tên sản phẩm" required>
            <textarea name="description" placeholder="Mô tả sản phẩm" required></textarea>
            <input type="number" name="price" placeholder="Giá" required>
            <input type="text" name="category" placeholder="Danh mục" required>
            <input type="file" name="image" required>
            <input type="number" name="discount" placeholder="Giảm giá" required>
            <button type="submit" name="add_product">Thêm sản phẩm</button>
        </form>
    </div>

</body>
</html>
