<?php
session_start();

if (!isset($_SESSION['cart']) || count($_SESSION['cart']) == 0) {
    header("Location: cart.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $note = $_POST['note'] ?? '';

    if (empty($name) || empty($phone) || empty($address)) {
        $error = "Vui lòng điền đầy đủ thông tin giao hàng!";
    } else {
        $_SESSION['shipping_info'] = [
            'name' => $name,
            'phone' => $phone,
            'address' => $address,
            'note' => $note
        ];

        header("Location: confirm_payment.php");
        exit();
    }
}

$cart = $_SESSION['cart'];
$total = 0;
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông tin giao hàng</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f5;
            display: flex;
            justify-content: center;
            padding-top: 50px;
            color: #333;
            position: relative;
        }

        .container {
            display: flex;
            gap: 20px;
            width: 80%;
            max-width: 1200px;
        }

        .form-container, .invoice-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.1);
        }

        .form-container { flex: 1; }
        .invoice-container { width: 400px; }

        .close-btn {
            position: absolute;
            top: 20px;
            right: 20px;
            background-color: transparent;
            color: #003366; /* Màu xanh dương đậm */
            border: none;
            font-size: 30px;
            cursor: pointer;
        }

        .close-btn:hover {
            color: #0056b3; /* Màu xanh dương sáng hơn khi hover */
        }

        .confirm-btn {
            background-color: #003366; /* Màu xanh dương đậm */
            color: white;
            padding: 18px 25px;
            width: 100%;
            border: none;
            cursor: pointer;
            font-size: 18px;
            border-radius: 8px;
            transition: background-color 0.3s;
        }

        .confirm-btn:hover {
            background-color: #0056b3; /* Màu xanh dương sáng hơn khi hover */
        }

        h2 { font-size: 2em; margin-bottom: 20px; }
        h3 { font-size: 1.5em; margin-top: 30px; }

        .form-group label {
            font-size: 1.2em;
            margin-bottom: 8px;
            display: block;
        }

        .form-group input, .form-group textarea {
            width: 100%;
            padding: 12px;
            margin-top: 8px;
            border-radius: 5px;
            border: 1px solid #ddd;
            font-size: 1.1em;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 16px;
            text-align: left;
            border-bottom: 1px solid #ddd;
            font-size: 1.2em;
        }

        th { background-color: #f9f9f9; font-weight: bold; }

        .total-price {
            text-align: right;
            font-size: 1.5em;
            color: #d9534f;
            font-weight: bold;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<!-- Nút X để quay lại trang trước -->
<button class="close-btn" onclick="window.history.back();">X</button>

<div class="container">
    <!-- Form thông tin giao hàng -->
    <div class="form-container">
        <h2>Thông tin giao hàng</h2>
        <?php if (isset($error)): ?>
            <p style="color:red; font-weight:bold;"><?php echo $error; ?></p>
        <?php endif; ?>
        <form method="POST">
            <div class="form-group">
                <label for="name">Họ và tên:</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group" style="margin-top:15px;">
                <label for="phone">Số điện thoại:</label>
                <input type="text" id="phone" name="phone" required>
            </div>
            <div class="form-group" style="margin-top:15px;">
                <label for="address">Địa chỉ giao hàng:</label>
                <textarea id="address" name="address" rows="3" required></textarea>
            </div>
            <button type="submit" class="confirm-btn">Xác nhận thanh toán</button>
        </form>
    </div>

    <!-- Hóa đơn -->
    <div class="invoice-container">
        <h2>Hóa đơn của bạn</h2>
        <table>
            <thead>
                <tr>
                    <th>Sản phẩm</th>
                    <th>Số lượng</th>
                    <th>Giá</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cart as $product_name => $item): ?>
                    <?php
                    $subtotal = $item['price'] * (1 - $item['discount'] / 100) * $item['quantity'];
                    $total += $subtotal;
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($product_name); ?></td>
                        <td><?php echo $item['quantity']; ?></td>
                        <td><?php echo number_format($subtotal, 0, ',', '.') . ' đ'; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="total-price">
            Tổng cộng: <?php echo number_format($total, 0, ',', '.') . ' đ'; ?>
        </div>
    </div>
</div>

</body>
</html> 