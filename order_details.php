<?php
session_start();

// Kết nối cơ sở dữ liệu
$mysqli = new mysqli("localhost", "root", "", "Ql_bh");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Kiểm tra nếu ID đơn hàng được truyền qua URL
if (isset($_GET['id'])) {
    $order_id = $_GET['id'];

    // Lấy thông tin đơn hàng
    $order_query = "SELECT * FROM orders WHERE order_id = '$order_id'";
    $order_result = $mysqli->query($order_query);
    $order = $order_result->fetch_assoc();

    // Lấy thông tin sản phẩm trong đơn hàng
    $product_query = "SELECT p.name, p.description, p.price, p.image, od.quantity 
                      FROM order_details od
                      JOIN products p ON od.product_id = p.id
                      WHERE od.order_id = '$order_id'";

    $product_result = $mysqli->query($product_query);
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chi Tiết Đơn Hàng</title>
    <style>
        /* Basic styles for the page */
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 20px;
        }
        h1 {
            text-align: center;
            color: #003366;
        }
        .order-info, .product-info {
            width: 80%;
            margin: 20px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .order-info h2, .product-info h2 {
            color: #003366;
        }
        .order-info p, .product-info p {
            font-size: 16px;
            line-height: 1.6;
        }
        .product-info table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .product-info th, .product-info td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ccc;
        }
        .product-info th {
            background-color: #003366;
            color: #fff;
        }
        .product-info td img {
            width: 100px;
            height: auto;
            border-radius: 5px;
        }
    </style>
</head>
<body>

    <h1>Chi Tiết Đơn Hàng #<?php echo $order['order_id']; ?></h1>

    <!-- Thông tin khách hàng -->
    <div class="order-info">
        <h2>Thông Tin Khách Hàng</h2>
        <p><strong>Tên Khách Hàng: </strong><?php echo $order['customer_name']; ?></p>
        <p><strong>Email: </strong><?php echo $order['customer_email']; ?></p>
        <p><strong>Số Điện Thoại: </strong><?php echo $order['customer_phone']; ?></p>
        <p><strong>Địa Chỉ: </strong><?php echo $order['customer_address']; ?></p>
        <p><strong>Trạng Thái Đơn Hàng: </strong><?php echo ucfirst($order['order_status']); ?></p>
    </div>

    <!-- Thông tin sản phẩm trong đơn hàng -->
    <div class="product-info">
        <h2>Danh Sách Sản Phẩm</h2>
        <table>
            <thead>
                <tr>
                    <th>Hình Ảnh</th>
                    <th>Tên Sản Phẩm</th>
                    <th>Mô Tả</th>
                    <th>Giá</th>
                    <th>Số Lượng</th>
                    <th>Tổng</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($product = $product_result->fetch_assoc()): ?>
                <tr>
                    <td><img src="<?php echo $product['image']; ?>" alt="Product Image"></td>
                    <td><?php echo $product['name']; ?></td>
                    <td><?php echo $product['description']; ?></td>
                    <td><?php echo number_format($product['price'], 0, ',', '.') . " đ"; ?></td>
                    <td><?php echo $product['quantity']; ?></td>
                    <td><?php echo number_format($product['price'] * $product['quantity'], 0, ',', '.') . " đ"; ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

</body>
</html>

<?php $mysqli->close(); ?>
