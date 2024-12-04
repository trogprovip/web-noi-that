<?php
// Kết nối đến cơ sở dữ liệu
include 'db_connect.php';
// Lấy sản phẩm từ cơ sở dữ liệu
$query = "SELECT * FROM products WHERE status = 'active'";
$products = mysqli_query($conn, $query);

// Kiểm tra kết nối
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
session_start(); // Bắt đầu session

// Kiểm tra xem người dùng đã đăng nhập chưa
if (isset($_SESSION['user_id'])) {
    $fullname = $_SESSION['fullname']; // Lấy tên người dùng từ session
} else {
    $fullname = null; // Nếu chưa đăng nhập
}
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

// Xử lý thêm sản phẩm vào giỏ hàng


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Lấy thông tin sản phẩm từ form
    $product_id = $_POST['product_id'];

    // Khởi tạo giỏ hàng nếu chưa có
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Kiểm tra nếu sản phẩm đã tồn tại trong giỏ hàng
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] += 1; // Tăng số lượng nếu đã tồn tại
    } else {
        // Nếu chưa có trong giỏ hàng, thêm sản phẩm vào giỏ hàng
        $_SESSION['cart'][$product_id] = [
            'id' => $product['id'], // Lưu thêm ID sản phẩm
            'name' => $product['name'],
            'price' => $product['price'],
            'discount' => $product['discount'],
            'image' => $product['image'], // Lưu đường dẫn ảnh
            'quantity' => 1 // Khởi tạo số lượng là 1
        ];
    }
    // Chuyển hướng đến trang giỏ hàng
    header("Location: cart.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết sản phẩm</title>
    <style>
        /* Cài đặt chung */
        * { 
            margin: 0; 
            padding: 0; 
            box-sizing: border-box; 
            font-family: Arial, sans-serif; 
        }
        
        /* Định dạng Header */
        header {
            background-color: #e0e0e0; /* Màu nền xám nhạt */
            color: #0d425d; /* Màu chữ */
            display: flex; /* Dùng flex để căn đều các mục trong header */
            justify-content: space-between; /* Căn đều hai bên trái và phải */
            padding: 10px 30px; /* Khoảng đệm trong */
            height: 65px; /* Chiều cao của header */
            align-items: center; /* Căn giữa các phần tử theo chiều dọc */
            position: fixed; /* Cố định header trên cùng màn hình */
            left: 0;
            top: 0;
            right: 0;
            z-index: 1000; /* Đảm bảo header nằm trên các phần tử khác */
        }

        /* Logo */
        .logo img { 
            max-width: 150px;
            margin-top: 10px;
        }

        /* Định dạng Menu */
        .menu ul { 
            display: flex;
            list-style: none; 

        }
        .menu ul li { 
            padding: 0 10px; /* Khoảng đệm ngang giữa các mục menu chính */
            margin-right: 50px; 
            position: relative; 
        }
        .menu a {
            color: #0d425d; 
            text-decoration: none; 
            font-size: 18px; 
            font-weight: bold; 
        }
        .sub-menu {
            display: none;
            opacity: 0; /* Ban đầu ẩn menu con */
            position: absolute;
            top: 80%;
            left: 0;
            background: #f9f9f9;
            padding: 30px 0; /* Khoảng đệm dọc */
            border: 1px solid #1e1d1d; /* Viền xám nhạt */
            z-index: 1000; /* Đảm bảo menu con nằm trên các phần tử khác */
            min-width: 180px; /* Chiều rộng tối thiểu cho menu con */
        }
        /* Định dạng cho các mục con */
        .sub-menu li {
         padding: 30px; /* Khoảng đệm cho các mục con */
         margin-bottom: 15px; /* Khoảng cách giữa các mục */
         border-bottom: 1px solid #ddd; /* Đường viền dưới giữa các mục con */
         }

        .menu ul li:hover .sub-menu { 
            display: block; 
        }
        .sub-menu a {
            color: #292b2c; 
            font-size: 16px; 
            font-weight: bold; 
        }
        .sub-menu a:hover { color: #0078d7; }
        /* Hiển thị menu con khi di chuột vào mục cha */
        .menu > ul > li:hover .sub-menu {
         margin-top: 25px;
        display: block; /* Hiển thị menu con */
        opacity: 1; /* Làm mờ dần */
        visibility: visible; /* Cho phép hiển thị */
        }   


        /* Định dạng phần khác */
        .other {
            display: flex;
            align-items: center;
        }
        .other input {
            width: 280px;
            height: 30px;
            padding: 5px;
            margin-right: 30px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .other img {
            margin-left: 10px; 
        }
        .other a {
            color: #0d425d; 
            margin-left: 5px;
            margin-right: 20px;
            font-size: 18px;
            font-weight: bold; 
            text-decoration: none; /* Xóa gạch chân cho liên kết */

        }
    
        /* Định dạng cho giỏ hàng */
        #cart {
            position: relative; 
        }

        /* Định dạng cho số lượng giỏ hàng */
        #cart-count {
            position: absolute;
            top: 15px; 
            right: 25px; 
            color: rgb(46, 44, 44); 
            font-size: 12px; 
            font-weight: bold; 
            min-width: 20px; /* Đảm bảo chiều rộng tối thiểu cho số lượng */
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">
            <img src="anhlogo.jpg" alt="Logo Nội thất Bắc Âu" style="width: 120px;">
        </div>
        <div class="menu">
            <ul>
                <li><a href="webbh.php">Trang Chủ</a></li>
                <li><a href="blog.php">Blog</a></li>
            </ul>
        </div>
        <div class="other">
            <input type="text" placeholder="Tìm kiếm..."> 
            <?php if ($fullname): ?>
            <!-- Hiển thị khi người dùng đã đăng nhập -->
            <span>Xin chào, <strong><?= htmlspecialchars($fullname); ?></strong></span>
            <a href="logout.php" class="logout-btn">Đăng xuất</a>
        <?php else: ?>
            <!-- Hiển thị khi người dùng chưa đăng nhập -->
            <a href="DNhap.php?redirect=<?php echo urlencode($_SERVER['REQUEST_URI']); ?>">
         <img src="Font Awesome/user-solid.svg" style="width: 15px; height: auto;" class="icon">
          Đăng nhập
              </a>
        <?php endif; ?>
        <a href="cart.php" id="cart-link">
    <img src="Font Awesome/cart-shopping-solid.svg" style="width: 20px; height: auto;" class="icon">
    <span id="cart-count">
        <?php 
        // Hiển thị tổng số lượng sản phẩm trong giỏ hàng
        echo isset($_SESSION['cart']) ? array_sum(array_column($_SESSION['cart'], 'quantity')) : 0; 
        ?>
    </span>
</a>
        </div>
    </header>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
            margin-top: 80px;
        }

        .product-detail {
            display: flex;
            flex-direction: row;
            justify-content: center;
            align-items: flex-start;
            width: 95%;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin: 20px auto;
        }

        .product-image {
            flex: 1;
            margin-right: 30px;
        }

        .product-image img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
        }

        .product-info {
            flex: 1;
            text-align: left;
            margin-top: 20px;
        }

        .product-info h2 {
            font-size: 32px;
            margin-bottom: 20px;
        }

        .product-info p {
            font-size: 18px;
            line-height: 1.6;
            margin-bottom: 15px;
        }

        .original-price {
            text-decoration: line-through;
            color: #888888;
            font-size: 18px;
            display: block;
            margin-bottom: 10px;
        }

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

        /* Bảng thông tin */
        .product-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
        }

        .product-table th,
        .product-table td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }

        .product-table th {
            background-color: #f1f1f1;
        }

        .product-table td {
            background-color: #f9f9f9;
        }

        .product-table .heading {
            background-color: #f8f8f8;
            font-weight: bold;
        }
        .discount {
         font-size: 15px;
         color: #ff4d4d;
         margin-left: 15px;
         }
         .price {
         font-size: 30px; /* Thay đổi kích thước chữ của giá sản phẩm */
         font-weight: bold;
         margin-bottom: 15px;
}
    </style>
</head>
<body>

<!-- Nút quay lại -->
<a href="javascript:history.back()" class="back-button">&times;</a>

<div class="product-detail">
    <!-- Hình ảnh sản phẩm -->
    <div class="product-image">
    <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="Hình ảnh sản phẩm" width="500" height="500">
    </div>

    <!-- Mô tả sản phẩm -->
    <div class="product-info">
        <h2><?php echo htmlspecialchars($product['name']); ?></h2>
        <p><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>

        <!-- Giá sản phẩm -->
        <div class="price">
            <p>
                Giá: 
                <?php if ($product['discount'] > 0): ?>
                        <span class="discount"><?php echo '-' . $product['discount'] . '%'; ?></span>
                    <span class="original-price"><?php echo number_format($product['price'], 0, ',', '.') . ' đ'; ?></span>
                <?php endif; ?>
               <span class="price"> <?php echo number_format($product['price'] * (1 - $product['discount'] / 100), 0, ',', '.') . ' đ'; ?></span>
            </p>
        </div>

        <form action="update_cart.php?product_id=<?php echo $product['id'];?>" id="add-to-cart-form" method="POST">
    <button name="add" type="submit" class="add-to-cart">Thêm vào giỏ hàng</button>
</form>

    </div>
</div>

<!-- Bảng thông tin sản phẩm -->
<table class="product-table">
    <tr class="heading">
        <th>Thông tin</th>
        <th>Chi tiết</th>
    </tr>
    <tr>
        <td>Chất liệu chính</td>
        <td><?php echo htmlspecialchars($product['material']); ?></td>
    </tr>
    <tr>
        <td>Chiều dài sản phẩm (cm)</td>
        <td><?php echo htmlspecialchars($product['length']); ?></td>
    </tr>
    <tr>
        <td>Chiều rộng sản phẩm (cm)</td>
        <td><?php echo htmlspecialchars($product['width']); ?></td>
    </tr>
    <tr>
        <td>Hướng dẫn sử dụng</td>
        <td><?php echo nl2br(htmlspecialchars($product['usage_guide'])); ?></td>
    </tr>
</table>
    <script>
document.querySelector('.add-to-cart').addEventListener('click', function() {
    const productId = document.querySelector('input[name="product_id"]').value;

    // Gửi yêu cầu AJAX để thêm sản phẩm vào giỏ hàng
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'update_cart.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function() {
        if (xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            if (response.success) {
                // Cập nhật số lượng hiển thị trên icon giỏ hàng
                document.getElementById('cart-count').textContent = response.total_quantity;
                alert('Sản phẩm đã được thêm vào giỏ hàng!');
            } else {
                alert('Không thể thêm sản phẩm vào giỏ hàng!');
            }
        }
    };

    xhr.send('product_id=' + encodeURIComponent(productId));
});
</script>

</body>
</html>
