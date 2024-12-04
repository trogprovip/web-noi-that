<?php
session_start();

// Kiểm tra xem có tồn tại thông tin thanh toán trong session không
if (!isset($_SESSION['shipping_info'])) {
    header("Location: payment.php"); // Nếu không có thông tin thanh toán, chuyển hướng về trang thanh toán
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
    <title>Cảm ơn bạn!</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 20px;
            text-align: center;
        }

        .container {
            background-color: #fff;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: 0 auto;
        }

        h1 {
            color: #4CAF50;
            margin-bottom: 20px;
        }

        p {
            font-size: 18px;
            line-height: 1.6;
            margin-bottom: 20px;
        }

        .order-info {
            text-align: left;
            font-size: 18px;
            margin-bottom: 30px;
        }

        .button {
            background-color: #4CAF50;
            color: #fff;
            padding: 15px 30px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            font-size: 16px;
            cursor: pointer;
        }

        .button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Cảm ơn bạn đã đặt hàng!</h1>
    <p>Đơn hàng của bạn đã được xử lý thành công. Dưới đây là thông tin giao hàng của bạn:</p>

    <div class="order-info">
        <p><strong>Họ tên:</strong> <?php echo htmlspecialchars($shipping_info['name']); ?></p>
        <p><strong>Số điện thoại:</strong> <?php echo htmlspecialchars($shipping_info['phone']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($shipping_info['email']); ?></p>
        <p><strong>Địa chỉ:</strong> <?php echo htmlspecialchars($shipping_info['address']); ?></p>
        <p><strong>Tỉnh/Thành phố:</strong> <?php echo htmlspecialchars($shipping_info['province']); ?></p>
        <p><strong>Quận/Huyện:</strong> <?php echo htmlspecialchars($shipping_info['district']); ?></p>
    </div>

    <p>Cảm ơn bạn đã mua sắm tại cửa hàng của chúng tôi! Chúng tôi sẽ xử lý đơn hàng của bạn và gửi thông tin chi tiết qua email.</p>

    <a href="webbh.php" class="button">Quay lại trang chủ</a>
</div>

</body>
</html>

<?php
// Xóa thông tin giỏ hàng và giao hàng khỏi session sau khi xử lý xong
unset($_SESSION['cart']);
unset($_SESSION['shipping_info']);
?>
