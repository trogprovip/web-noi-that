<?php
session_start();

// Kiểm tra nếu có thông tin giao hàng trong session
if (!isset($_SESSION['shipping_info'])) {
    header("Location: cart.php"); // Chuyển hướng về giỏ hàng nếu chưa có thông tin giao hàng
    exit();
}

// Lấy thông tin giao hàng từ session
$shipping_info = $_SESSION['shipping_info'];

?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh toán thành công</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f9fafc;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            color: #333;
        }

        .container {
            background-color: #fff;
            border-radius: 15px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
            width: 90%;
            max-width: 500px;
            text-align: center;
            padding: 40px;
        }

        .close-btn {
            position: absolute;
            top: 20px;
            right: 20px;
            background-color: transparent;
            color: #003366;
            border: none;
            font-size: 30px;
            cursor: pointer;
        }

        .close-btn:hover {
            color: #0056b3;
        }

        .message {
            font-size: 2em;
            color: #5bc0de;
            margin-bottom: 30px;
            font-weight: bold;
        }

        .info {
            font-size: 1.2em;
            color: #333;
            margin-bottom: 20px;
            line-height: 1.6;
        }

        .btn-home {
            background-color: #003366;
            color: white;
            padding: 12px 25px;
            font-size: 1.1em;
            border: none;
            cursor: pointer;
            border-radius: 8px;
            text-decoration: none;
            transition: background-color 0.3s, transform 0.3s;
        }

        .btn-home:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }

        .btn-home:active {
            transform: scale(0.98);
        }
    </style>
</head>
<body>

<!-- Nút X để quay lại trang chủ -->
<button class="close-btn" onclick="window.location.href='webbh.php';">X</button>

<div class="container">
    <div class="message">Thanh toán thành công!</div>
    <div class="info">
        Cảm ơn bạn đã mua sắm tại cửa hàng chúng tôi!<br><br>
        Đơn hàng của bạn sẽ được giao đến địa chỉ:<br>
        <strong><?php echo htmlspecialchars($shipping_info['address']); ?></strong>
    </div>
    <a href="webbh.php" class="btn-home">Quay lại trang chủ</a>
</div>

</body>
</html>




<?php
session_start();

// Kết nối với cơ sở dữ liệu
$mysqli = new mysqli("localhost", "root", "", "Ql_bh");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Chuẩn bị câu lệnh SQL để thêm đơn hàng vào cơ sở dữ liệu
$stmt = $mysqli->prepare("INSERT INTO orders (customer_name, customer_phone, customer_address, note, total_amount) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("ssssd", $shipping_info['name'], $shipping_info['phone'], $shipping_info['address'], $shipping_info['note'], $total);

if ($stmt->execute()) {
    // Lưu thành công, có thể chuyển hướng tới trang thanh toán thành công
    $_SESSION['order_id'] = $stmt->insert_id;  // Lưu ID đơn hàng vào session
    header("Location: payment_success.php");
    exit();
} else {
    // Xử lý lỗi
    echo "Lỗi khi lưu đơn hàng: " . $stmt->error;
}

$stmt->close();
$mysqli->close();
?>

