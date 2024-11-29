<?php
// Bắt đầu session để lưu trữ giỏ hàng
session_start();

// Kết nối đến cơ sở dữ liệu
include 'db_connect.php';

// Khởi tạo biến số lượng giỏ hàng
$cart_count = 0;
if (isset($_SESSION['cart'])) {
    // Tính tổng số lượng sản phẩm trong giỏ hàng
    foreach ($_SESSION['cart'] as $item) {
        $cart_count += $item['quantity'];
    }
}

// Kiểm tra nếu có sản phẩm được thêm vào giỏ hàng
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['product_id'])) {
    $product_name = $_POST['product_id'];

    // Truy vấn thông tin sản phẩm từ cơ sở dữ liệu
    $sql = "SELECT * FROM products WHERE LOWER(name) = LOWER(?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $product_name);
    $stmt->execute();
    $result = $stmt->get_result();

    // Kiểm tra nếu sản phẩm tồn tại trong cơ sở dữ liệu
    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();

        // Kiểm tra nếu sản phẩm đã có trong giỏ hàng
        if (isset($_SESSION['cart'][$product_name])) {
            $_SESSION['cart'][$product_name]['quantity'] += 1; // Tăng số lượng nếu sản phẩm đã có trong giỏ
        } else {
            // Nếu sản phẩm chưa có, thêm vào giỏ
            $_SESSION['cart'][$product_name] = [
                'name' => $product_name,
                'quantity' => 1,
                'price' => $product['price'],
                'discount' => $product['discount']
            ];
        }
    } else {
        echo "Sản phẩm không tồn tại!";
    }
}

// Kiểm tra nếu người dùng chỉnh sửa số lượng sản phẩm
if (isset($_POST['update_quantity'])) {
    $product_name = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    if ($quantity > 0) {
        $_SESSION['cart'][$product_name]['quantity'] = $quantity;
    } else {
        unset($_SESSION['cart'][$product_name]); // Xóa sản phẩm nếu số lượng là 0
    }
}

// Kiểm tra nếu người dùng xóa sản phẩm
if (isset($_POST['remove_product'])) {
    $product_name = $_POST['product_id'];
    unset($_SESSION['cart'][$product_name]);
}

// Hiển thị giỏ hàng
$cart = $_SESSION['cart'] ?? [];
$total = 0;
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
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }
        .cart-container {
            width: 80%;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
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
        .back-btn {
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: transparent; /* Không có nền */
            color: #000; /* Màu đen cho dấu X */
            border: none;
            padding: 10px 15px;
            border-radius: 50%;
            font-size: 18px;
            cursor: pointer;
        }
        .back-btn:hover {
            background-color: #218838;
        }
        .update-btn, .remove-btn {
            background-color: #ff6f61;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
        }
        .update-btn:hover, .remove-btn:hover {
            background-color: #ff4f3d;
        }
        .checkout-btn {
            width: 100%;
            background-color: #007bff;
            color: white;
            padding: 15px;
            border: none;
            font-size: 18px;
            cursor: pointer;
            margin-top: 20px;
        }
        .checkout-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<button class="back-btn" onclick="window.history.back();">X</button>

<div class="cart-container">
    <h2>Giỏ hàng của bạn</h2>
    <?php if (count($cart) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Tên sản phẩm</th>
                    <th>Số lượng</th>
                    <th>Giá</th>
                    <th>Tổng</th>
                    <th>Thao tác</th>
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
                        <td>
                            <form method="POST">
                                <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1" style="width: 50px;">
                                <input type="hidden" name="product_id" value="<?php echo $product_name; ?>">
                                <button type="submit" name="update_quantity" class="update-btn">Cập nhật</button>
                            </form>
                        </td>
                        <td><?php echo number_format($item['price'] * (1 - $item['discount'] / 100), 0, ',', '.') . ' đ'; ?></td>
                        <td><?php echo number_format($subtotal, 0, ',', '.') . ' đ'; ?></td>
                        <td>
                            <form method="POST">
                                <input type="hidden" name="product_id" value="<?php echo $product_name; ?>">
                                <button type="submit" name="remove_product" class="remove-btn">Xóa</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="total-price">
            <strong>Tổng cộng: <?php echo number_format($total, 0, ',', '.') . ' đ'; ?></strong>
        </div>
        <form method="POST">
            <button type="submit" class="checkout-btn"formaction="payment.php">Thanh toán</button>
        </form>
    <?php else: ?>
        <p>Giỏ hàng của bạn hiện tại không có sản phẩm nào.</p>
    <?php endif; ?>
</div>

</body>
</html>
