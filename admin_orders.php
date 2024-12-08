<?php
session_start();

// Kết nối tới cơ sở dữ liệu
$mysqli = new mysqli("localhost", "root", "", "Ql_bh");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Kiểm tra nếu admin đã đăng nhập chưa
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: DNhap.php");
    exit();
}

// Cập nhật trạng thái đơn hàng
if (isset($_GET['id']) && isset($_GET['status'])) {
    $order_id = $_GET['id'];
    $status = $_GET['status'];

    if (!in_array($status, ['confirmed', 'canceled'])) {
        die("Trạng thái không hợp lệ");
    }

    $stmt = $mysqli->prepare("UPDATE orders SET order_status = ? WHERE order_id = ?");
    $stmt->bind_param("si", $status, $order_id);
    $stmt->execute();
    $stmt->close();
}

// Lấy danh sách đơn hàng
$result = $mysqli->query("SELECT * FROM orders ORDER BY created_at DESC");
?>


<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý Đơn Hàng</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f8ff;
            color: #333;
            margin: 0;
            padding: 0;
        }
        
        .menu {
            width: 200px;
            background-color: #003366;
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
            font-weight: bold;
        }
        .menu a:hover {
            background-color: #002244;
        }
        
        .content {
            margin-left: 220px;
            padding: 20px;
        }
        
        h2 {
            color: #003366;
            font-size: 28px;
            font-weight: bold;
            text-align: center;
            margin-top: 20px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ccc;
        }
        th, td {
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #003366;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        a.button {
            color: white;
            text-decoration: none;
            padding: 5px 10px;
            background-color: #003366;
            border-radius: 4px;
            margin-right: 5px;
        }
        a.button:hover {
            background-color: #002244;
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
    <h2>Quản lý Đơn Hàng</h2>
    <table>
        <tr><th>ID Đơn Hàng</th><th>Tên Khách Hàng</th><th>Tổng Tiền</th><th>Trạng Thái</th><th>Thao Tác</th><th>Thông tin sản phẩm</th><th>Thông tin khách hàng</th></tr>
        <?php while ($row = $result->fetch_assoc()) : ?>
            <tr>
                <td><?php echo $row['order_id']; ?></td>
                <td><?php echo htmlspecialchars($row['customer_name']); ?></td>
                <td><?php echo number_format($row['total_amount'], 0, ',', '.') . " đ"; ?></td>
                <td><?php echo $status_translation[$row['order_status']] ?? ucfirst($row['order_status']); ?></td>
                <td>
                    <?php if ($row['order_status'] == 'pending') : ?>
                        <a href="admin_orders.php?id=<?php echo $row['order_id']; ?>&status=confirmed" class="button">Xác nhận</a>
                        <a href="admin_orders.php?id=<?php echo $row['order_id']; ?>&status=canceled" class="button">Hủy</a>
                    <?php endif; ?>
                </td>
                <td>
    <a href="product_detail.php?id=<?php echo $row['order_id']; ?>" class="button">Xem Chi Tiết</a>
</td>
<td>
    <a href="order_details.php?id=<?php echo $row['order_id']; ?>" class="button">Xem Chi Tiết</a>
</td>

            </tr>
        <?php endwhile; ?>
    </table>
</div>

<?php $mysqli->close(); ?>
</body>
</html>

