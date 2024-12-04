<?php
session_start();

// Khởi tạo giỏ hàng nếu chưa có
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Xử lý các thao tác giỏ hàng
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Cập nhật số lượng sản phẩm
    if (isset($_POST['update_quantity'])) {
        $product_id = $_POST['product_id'];
        $quantity = intval($_POST['quantity']); // Chuyển đổi số lượng sang số nguyên
        if ($quantity > 0) {
            $_SESSION['cart'][$product_id]['quantity'] = $quantity;
        } else {
            unset($_SESSION['cart'][$product_id]); // Xóa sản phẩm nếu số lượng <= 0
        }
    }

    // Xóa sản phẩm khỏi giỏ hàng
    if (isset($_POST['remove_product'])) {
        $product_id = $_POST['product_id'];
        unset($_SESSION['cart'][$product_id]);
    }
}

// Tính tổng tiền giỏ hàng
$cart = $_SESSION['cart'];
$total = 0; // Biến lưu tổng cộng tiền giỏ hàng
foreach ($cart as $item) {
    // Tổng tiền của sản phẩm hiện tại (giá × số lượng)
    $subtotal = $item['price'] * $item['quantity'];
    // Cộng dồn vào tổng tiền giỏ hàng
    $total += $subtotal;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giỏ hàng</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }

        .cart-container {
            width: 95%;
            margin: 50px auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            font-weight: bold;
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f8f8f8;
        }

        .total-price {
            text-align: right;
            font-size: 20px;
            margin-top: 20px;
        }

        .checkout-btn {
            display: block;
            text-align: center;
            width: 100%;
            padding: 15px;
            background-color: #007bff;
            color: #ffffff;
            font-size: 18px;
            text-decoration: none;
            border-radius: 8px;
            margin-top: 20px;
            margin-left: -15px;
        }

        .checkout-btn:hover {
            background-color: #0056b3;
        }

        .cart-image {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
        }

        /* Căn chỉnh cột hình ảnh, tên sản phẩm */
        .product-details {
            display: flex;
            align-items: center;
        }

        .product-details img {
            margin-right: 15px;
        }
    </style>
</head>
<body>

<div class="cart-container">
    <h2>Giỏ hàng của bạn</h2>
    <?php if (count($cart) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Hình ảnh</th>
                    <th>Tên sản phẩm</th>
                    <th>Số lượng</th>
                    <th>Giá</th>
                    <th>Tổng</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cart as $product_id => $item): ?>
                    <?php 
                        // Tổng tiền cho sản phẩm
                        $subtotal = $item['price'] * $item['quantity']; 
                    ?>
                    <tr>
                        <td>
                        <img src="<?php echo $item['image']; ?>" alt="Hình ảnh sản phẩm" class="cart-image">
                        </td>
                        <td>
                            <div class="product-details">
                                <p><?php echo htmlspecialchars($item['name']); ?></p>
                            </div>
                        </td>
                        <td>
                            <form method="POST">
                                <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1" style="width: 50px;">
                                <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                                <button type="submit" name="update_quantity">Cập nhật</button>
                            </form>
                        </td>
                        <td><?php echo number_format($item['price'], 0, ',', '.') . ' đ'; ?></td>
                        <td><?php echo number_format($subtotal, 0, ',', '.') . ' đ'; ?></td>
                        <td>
                            <form method="POST">
                                <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                                <button type="submit" name="remove_product">Xóa</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Hiển thị tổng cộng -->
        <div class="total-price">
            <strong>Tổng cộng: <?php echo number_format($total, 0, ',', '.') . ' đ'; ?></strong>
        </div>

        <!-- Nút thanh toán -->
        <a href="payment.php" class="checkout-btn">Thanh toán</a>
    <?php else: ?>
        <p>Giỏ hàng của bạn hiện tại không có sản phẩm nào.</p>
    <?php endif; ?>
</div>

</body>
</html>
