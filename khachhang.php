<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Ql_bh";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

$sql = "SELECT id, fullname, email, password FROM khachhang";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Khách Hàng</title>
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
    font-weight: bold; /* Làm chữ in đậm */

}

.menu a:hover {
    background-color: #002244;
    color: white;
}

/* Phần nội dung chính */
.content {
    margin-left: 220px;
    padding: 20px;
}

/* Tiêu đề chính căn giữa */
h2 {
    text-align: center;
    font-size: 24px;
    color: #003366;
    margin-bottom: 20px;
}

/* Bảng danh sách khách hàng */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

table, th, td {
    border: 1px solid #ddd; /* Màu sắc đường viền */
}

th {
    background-color: #003366;
    color: white;
    padding: 12px;
    text-align: left;
}

td {
    background-color: #f9f9f9;
    padding: 10px;
    text-align: left;
    transition: background-color 0.3s ease;
}

/* Hiệu ứng hover cho các hàng trong bảng */
tr:hover td {
    background-color: #f1f1f1;
}

/* Định dạng cột mật khẩu */
td:nth-child(4) {
    font-weight: bold;
    color: #0077cc;
}

/* Đảm bảo bảng có khoảng cách hợp lý */
table {
    margin-top: 20px;
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
        <a href="webbh.php">Quay lại Web</a>
    </div>

    <!-- Phần nội dung chính -->
    <div class="content">
        <h2>Quản Lý Khách Hàng</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Họ Tên</th>
                <th>Email</th>
                <th>Mật Khẩu</th> <!-- Thêm cột Mật khẩu -->
            </tr>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["id"] . "</td>";
                    echo "<td>" . $row["fullname"] . "</td>";
                    echo "<td>" . $row["email"] . "</td>";
                    echo "<td>" . $row["password"] . "</td>"; // Hiển thị mật khẩu
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4'>Không có khách hàng nào</td></tr>";
            }

            $conn->close();
            ?>
        </table>
    </div>

</body>
</html>
