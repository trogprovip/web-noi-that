<?php
session_start();

include 'db_connect.php';

// Kiểm tra nếu có product_id được gửi
if (isset($_POST['add'])) {
    $product_id = $_GET['product_id'];
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1; // Lấy số lượng từ form
    
    // Truy vấn sản phẩm từ CSDL
    $sql = "SELECT * FROM products WHERE id = ? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id); // Gắn tham số vào câu truy vấn
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Lấy dữ liệu sản phẩm
        $row = $result->fetch_assoc();

        // Tạo mảng sản phẩm mới
        $new_product = array(
            'id' => $row['id'],
            'name' => $row['name'],
            'image' => $row['image'],
            'price' => $row['price'],
            'quantity' => $quantity
        );

        // Kiểm tra xem giỏ hàng đã tồn tại hay chưa
        if (isset($_SESSION['cart'])) {
            $found = false;

            // Duyệt qua các sản phẩm trong giỏ hàng bằng tham chiếu
            foreach ($_SESSION['cart'] as &$item) {
                // Nếu sản phẩm đã tồn tại, tăng số lượng
                if ($item['id'] == $product_id) {
                    $item['quantity'] += $quantity; // Cộng thêm số lượng
                    $found = true;

  
            alert("Thêm giỏ hàng thành công");
        

                    break;
                }
            }
            unset($item); // Hủy tham chiếu để tránh lỗi

            // Nếu sản phẩm chưa có trong giỏ hàng, thêm sản phẩm mới
            if (!$found) {
                $_SESSION['cart'][] = $new_product;
            }
        } else {
            // Nếu giỏ hàng chưa tồn tại, khởi tạo giỏ hàng và thêm sản phẩm
            $_SESSION['cart'] = array($new_product);
        }
    }

    // Chuyển hướng đến trang giỏ hàng
 header('Location: product_detail.php?name=' . urlencode($row['name']) . '&added=1');


    exit();
}


