<?php
// Kết nối đến cơ sở dữ liệu
include 'db_connect.php';

// Kiểm tra nếu tham số 'name' tồn tại trong URL
if (isset($_GET['name'])) {
    // Lấy tên sản phẩm từ URL và xử lý ký tự đặc biệt
    $name = urldecode($_GET['name']);

    // Truy vấn sản phẩm với tên không phân biệt hoa thường
    $sql = "SELECT * FROM products WHERE LOWER(name) = LOWER(?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $name);
    $stmt->execute();
    $result = $stmt->get_result();

    // Kiểm tra nếu tìm thấy sản phẩm
    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
    } else {
        echo "<p>Không tìm thấy sản phẩm với tên: " . htmlspecialchars($name) . "</p>";
        exit;
    }

    // Đóng kết nối
    $stmt->close();
    $conn->close();
} else {
    echo "<p>Tham số 'name' không được cung cấp trong URL.</p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết sản phẩm</title>
    <style>
        /* Căn chỉnh toàn bộ trang */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
            margin-top: 80px; 
        }

        /* Canh giữa phần chi tiết sản phẩm */
        .product-detail {
            display: flex;
            justify-content: center; /* Căn giữa phần chi tiết sản phẩm */
            gap: 40px;
            width: 70%; /* Chiếm 70% chiều rộng của trang để làm lớn hơn */
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin: 0 auto;
        }

        /* Ảnh sản phẩm */
        .product-image img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
        }

        /* Thông tin sản phẩm */
        .product-info {
            max-width: 50%;
            text-align: left;
        }

        /* Nút "Thêm vào giỏ hàng" với màu xanh nước biển */
        .add-to-cart {
            padding: 15px 30px;
            background-color: #1e90ff;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 18px;
            transition: background-color 0.3s;
            margin-top: 20px;
        }

        .add-to-cart:hover {
            background-color: #4682b4;
        }

        /* Nút quay lại trang trước */
        .back-button {
            font-size: 30px;
            color: #000000;
            text-decoration: none;
            position: absolute;
            top: 20px;
            right: 20px;
            z-index: 10;
        }

        .back-button:hover {
            color: #333333;
        }

        /* Gạch ngang cho giá trước khi giảm */
        .original-price {
            text-decoration: line-through;
            color: #888888;
            font-size: 22px;
            display: block;
            margin-bottom: 10px;
        }

        /* Các kiểu font và kích thước chữ chung cho trang */
        h2 {
            font-size: 32px;
            margin-bottom: 20px;
        }

        p {
            font-size: 18px;
            line-height: 1.6;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

<!-- Nút quay lại trang trước -->
<a href="javascript:history.back()" class="back-button">&times;</a>

<div class="product-detail">
    <!-- Ảnh sản phẩm -->
    <div class="product-image">
        <img src="ảnh/giường age/giuongage2.jpeg" alt="<?php echo htmlspecialchars($product['name']); ?>">
    </div>

    <!-- Thông tin sản phẩm -->
    <div class="product-info">
        <h2><?php echo htmlspecialchars($product['name']); ?></h2>
        <p><?php echo htmlspecialchars($product['description']); ?></p>

        <!-- Giá gốc gạch ngang nếu có giảm giá -->
        <p>
            Giá: 
            <?php if ($product['discount'] > 0): ?>
                <span class="original-price"><?php echo number_format($product['price'], 0, ',', '.') . ' đ'; ?></span>
            <?php endif; ?>
            <?php echo number_format($product['price'] * (1 - $product['discount'] / 100), 0, ',', '.') . ' đ'; ?>
        </p>

        <p>Danh mục: <?php echo htmlspecialchars($product['category']); ?></p>
        
        <!-- Thêm vào giỏ hàng -->
        <form action="cart.php" method="POST">
            <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['name']); ?>">
            <button type="submit" class="add-to-cart">Thêm vào giỏ hàng</button>
        </form>
    </div>
</div>

</body>
</html>
