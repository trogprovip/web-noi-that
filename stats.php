<?php


// Kết nối tới cơ sở dữ liệu
$mysqli = new mysqli("localhost", "root", "", "Ql_bh");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}


//dt
$total_revenue_query = $mysqli->query("SELECT SUM(total_amount) AS total_revenue FROM orders WHERE order_status = 'confirmed'");
$total_revenue = $total_revenue_query->fetch_assoc()['total_revenue'] ?? 0;



$confirmed_orders_query = $mysqli->query("SELECT COUNT(*) AS confirmed_orders FROM orders WHERE order_status = 'confirmed'");
$confirmed_orders = $confirmed_orders_query->fetch_assoc()['confirmed_orders'] ?? 0;



$canceled_orders_query = $mysqli->query("SELECT COUNT(*) AS canceled_orders FROM orders WHERE order_status = 'canceled'");
$canceled_orders = $canceled_orders_query->fetch_assoc()['canceled_orders'] ?? 0;


$total_orders_query = $mysqli->query("SELECT COUNT(*) AS total_orders FROM orders");
$total_orders = $total_orders_query->fetch_assoc()['total_orders'] ?? 0;

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thống kê doanh thu</title>
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
            text-align: center;
        }
        
        h1 {
            color: #003366;
            font-size: 32px;
            font-weight: bold;
            margin-top: 20px;
        }

        .stats {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 30px;
        }
        
        .stat-box {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            padding: 20px;
            width: 200px;
            text-align: center;
            border-radius: 8px;
            box-shadow: 2px 2px 8px rgba(0, 0, 0, 0.1);
        }

        .stat-box h3 {
            color: #003366;
            font-size: 24px;
            margin-bottom: 10px;
        }

        .stat-box p {
            font-size: 20px;
            font-weight: bold;
            margin: 0;
            color: #444;
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
</div>

<div class="content">
    <h1>Thống kê Doanh Thu</h1>
    
    <div class="stats">
        <div class="stat-box">
            <h3>Tổng Doanh Thu</h3>
            <p><?php echo number_format($total_revenue, 0, ',', '.') . " đ"; ?></p>
        </div>
        <div class="stat-box">
            <h3>Đơn Hàng Xác Nhận</h3>
            <p><?php echo $confirmed_orders; ?></p>
        </div>
        <div class="stat-box">
            <h3>Đơn Hàng Đã Hủy</h3>
            <p><?php echo $canceled_orders; ?></p>
        </div>
        <div class="stat-box">
            <h3>Tổng Số Đơn Hàng</h3>
            <p><?php echo $total_orders; ?></p>
        </div>
    </div>
</div>

</body>
</html>
