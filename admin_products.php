<?php
include 'db_connect.php'; 

$search_query = "";
if (isset($_GET['search'])) {
    $search_query = $_GET['search'];
}

$query = "SELECT * FROM products WHERE name LIKE '%$search_query%'";
$products = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Quản lý sản phẩm</title>
    <style>
        /* Cấu trúc cơ bản */
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
        }
        .menu a:hover {
            background-color: #002244;
            color: white;

        }

        /* Nội dung chính */
        .content {
            margin-left: 220px; /* Để tránh bị che khuất bởi menu */
            padding: 20px;
        }

        /* Tiêu đề chính */
        h1 {
            color: #003366;
            text-align: center;
            margin: 20px 0;
        }

        /* Form tìm kiếm sản phẩm */
        form {
            text-align: center;
            margin-bottom: 20px;
        }

        form input[type="text"] {
            padding: 8px;
            width: 250px;
            border: 1px solid #003366;
            border-radius: 5px;
        }

        form button {
            padding: 8px 15px;
            background-color: #003366;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        form button:hover {
            background-color: #002244;
        }

        /* Link thêm sản phẩm */
        a {
            text-decoration: none;
            color: #003366;
            font-weight: bold;
            display: inline-block;
            margin: 10px 20px;
        }

        a:hover {
            color: #002244;
        }

        /* Bảng danh sách sản phẩm */
        table {
            width: 80%;
            margin: 0 auto;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid #003366;
        }

        thead {
            background-color: #003366;
            color: #fff;
        }

        th, td {
            padding: 10px;
            text-align: center;
        }

        /* Hình ảnh sản phẩm */
        td img {
            border-radius: 5px;
        }

        /* Hành động sửa, xóa */
        td a {
            color: #003366;
            font-weight: bold;
        }

        td a:hover {
            color: #002244;
        }

        /* Các nút sửa, xóa */
        td a:link, td a:visited {
            text-decoration: none;
        }
    </style>
</head>
<body>

    <!-- Thanh menu -->
    <div class="menu">
        <h3>Quản lý nội thất</h3>
        <a href="admin.php">Trang chủ</a>
        <a href="admin_products.php" class="active">Quản lý sản phẩm</a>
        <a href="add_product.php">Thêm sản phẩm mới</a>
        <a href="admin_orders.php">Quản lý đơn hàng</a>
        <a href="khachhang.php">Quản lý khách hàng</a>
        <a href="stats.php">Thống kê doanh thu</a>
    </div>

    <!-- Nội dung chính -->
    <div class="content">
        <h1>Danh sách sản phẩm</h1>

        <!-- Tìm kiếm sản phẩm -->
        <form method="GET" action="admin_products.php">
            <input type="text" name="search" placeholder="Tìm kiếm sản phẩm..." value="<?php echo $search_query; ?>">
            <button type="submit">Tìm kiếm</button>
        </form>

        <!-- Thêm sản phẩm -->
        <a href="add_product.php">Thêm sản phẩm mới</a>

        <!-- Bảng danh sách sản phẩm -->
        <table>
            <thead>
                <tr>
                    <th>Tên sản phẩm</th>
                    <th>Mô tả</th>
                    <th>Giá</th>
                    <th>Danh mục</th>
                    <th>Hình ảnh</th>
                    <th>Giảm giá</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($product = mysqli_fetch_assoc($products)): ?>
                <tr>
                    <td><?php echo $product['name']; ?></td>
                    <td><?php echo $product['description']; ?></td>
                    <td><?php echo number_format($product['price'], 2); ?> VNĐ</td>
                    <td><?php echo $product['category']; ?></td>
                    <td><img src="ảnh/giường age/giuongage2.jpeg"  width="50"></td>
                    <td><?php echo $product['discount']; ?>%</td>
                    <td>
                        <a href="edit_product.php?id=<?php echo $product['id']; ?>">Sửa</a> | 
                        <a href="delete_product.php?delete_id=<?php echo $product['id']; ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')">Xóa</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

</body>
</html>
