<?php
include 'db_connect.php';

// Lấy sản phẩm từ cơ sở dữ liệu
$query = "SELECT * FROM products WHERE status = 'active'";
$products = mysqli_query($conn, $query);

// Kiểm tra kết nối
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
} else {
    echo "Kết nối thành công!"; // Dòng này để kiểm tra kết nối
}
session_start(); // Bắt đầu session

// Kiểm tra xem người dùng đã đăng nhập chưa
if (isset($_SESSION['user_id'])) {
    $fullname = $_SESSION['fullname']; // Lấy tên người dùng từ session
} else {
    $fullname = null; // Nếu chưa đăng nhập
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Nội thất Bắc Âu</title>
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
            <a href="DNhap.php">
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

    <!-- Phần Banner -->
    <style>
    

    .carousel {
        position: relative;
        overflow: hidden;
        width: 80%; 
        height: 300px; /* Chiều cao của banner */
        margin: 0 auto; /* Căn giữa phần tử cha */
        margin-top: 80px; /* Tạo khoảng cách với thanh menu */

    }

    .carousel-images {
        display: flex;
        transition: transform 3s ease;
    }

    .carousel-images img {
        width: auto; /* Để chiều rộng tự động */
        height: 100%; /* Chiều cao của ảnh bằng chiều cao banner */
        max-width: 100%; /* Giới hạn chiều rộng không vượt quá 100% */
        object-fit: contain; /* Giữ tỷ lệ ảnh mà không bị cắt bớt */
        flex-shrink: 0; /* Đảm bảo ảnh không bị thu nhỏ */
        margin-bottom: 100px;
    }

    /* Animation để tự động chuyển đổi giữa các hình ảnh */
    @keyframes slide {
        0% { transform: translateX(0); }
        20% { transform: translateX(0); }
        25% { transform: translateX(-100%); }
        45% { transform: translateX(-100%); }
        50% { transform: translateX(-200%); }
        70% { transform: translateX(-200%); }
        75% { transform: translateX(-300%); }
        100% { transform: translateX(0); }
    }

    /* Đảm bảo ảnh hiển thị đẹp trên các màn hình rất lớn */
    @media (min-width: 1200px) {
        .carousel {
            height: 350px; /* Tăng chiều cao banner thêm nữa cho màn hình rất lớn */
        }
    }

    /* Thiết lập cho các màn hình nhỏ hơn */
    @media (max-width: 767px) {
        .carousel {
            width: 90%; /* Giảm chiều rộng xuống còn 90% trên màn hình nhỏ */
            height: 250px; /* Giảm chiều cao banner cho thiết bị di động */
        }

        .carousel-images img {
            height: auto; /* Đặt chiều cao tự động để ảnh không bị méo */
            width: 100%; /* Đảm bảo ảnh chiếm hết chiều rộng của banner */
        }
    }

    /* Đảm bảo căn giữa văn bản và hình ảnh trên các màn hình rất nhỏ */
    @media (max-width: 480px) {
        .carousel {
            height: 200px; /* Giảm thêm chiều cao banner */
        }
    }
</style>
<main>
    <div class="carousel">
        <div class="carousel-images">
            <img src="./ảnh/khuyenmai/khach.jpg" alt="Image 1">
            <img src="./ảnh/khuyenmai/ngu.jpg" alt="Image 2">
            <img src="./ảnh/khuyenmai/an.jpg" alt="Image 3">
            <img src="./ảnh/khuyenmai/lamvc.jpg" alt="Image 4">
        </div>
    </div>

    <script>
       let currentIndex = 0;
const images = document.querySelectorAll('.carousel-images img');
const totalImages = images.length;

function showNextImage() {
    currentIndex = (currentIndex + 1) % totalImages; // Chuyển đến ảnh tiếp theo
    updateCarousel();
}

function updateCarousel() {
    const offset = -currentIndex * 100; // Tính toán độ dịch chuyển
    document.querySelector('.carousel-images').style.transform = `translateX(${offset}%)`;
}

// Chỉ gọi hàm setInterval khi có ít nhất một ảnh
if (totalImages > 0) {
    setInterval(showNextImage, 8000); // Chuyển đổi mỗi 3 giây
}
    </script>

    <!-- Phần Sản phẩm -->
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f7f7f7;
        }

        .product-section {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        h1 {
            text-align: center;
            font-size: 28px;
            margin-bottom: 30px;
            color: #333;
        }

        .product-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: flex-start; /* Căn các mục về bên trái */
        }

        .product-item {
            background-color: #fff;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 15px;
            position: relative;
            text-align: center;
            width: calc(16.66% - 20px);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s, box-shadow 0.2s;
            height: 380px; /* Thiết lập chiều cao cố định */
            display: flex; /* Sử dụng flexbox */
            flex-direction: column; /* Đặt chiều dọc cho flexbox */

        }

        .product-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .product-item img {
            max-width: 100%;
            height: auto;
            border-radius: 5px;
            height: 200px; /* Thiết lập chiều cao cố định cho ảnh */
            object-fit: contain; /* Giữ nguyên tỷ lệ và thu nhỏ ảnh vừa với khung */

        }

        .product-item h3 {
            font-size: 15px;
            margin: 10px 0;
            color: #333;
        }

        .product-item p {
            font-size: 14px;
            color: #555;
            margin: 5px 0;
        }

        .old-price {
            color: #888;
            text-decoration: line-through;
        }

        .new-price {
            color: #0078d7;
            font-weight: bold;
            font-size: 16px;
        }

        .discount-label {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: #ff4d4d;
            color: white;
            padding: 3px 6px;
            border-radius: 50%;
            font-size: 12px;
            font-weight: bold;
        }

        @media (max-width: 1024px) {
            .product-item {
                width: calc(25% - 20px);
            }
        }

        @media (max-width: 768px) {
            .product-item {
                width: calc(33.333% - 20px);
            }
        }

        @media (max-width: 480px) {
            .product-item {
                width: calc(50% - 20px);
            }
        }

        @media (max-width: 320px) {
            .product-item {
                width: 100%;
            }
        }
    </style>
    <main>
        <div class="product-section">
            <h1>Sản Phẩm</h1>

            <div class="product-grid">
                <?php while ($product = mysqli_fetch_assoc($products)): ?>
                    <div class="product-item">
                        <?php if ($product['discount'] > 0): ?>
                            <div class="discount-label">-<?php echo $product['discount']; ?>%</div>
                        <?php endif; ?>
                        <a href="product_detail.php?name=<?php echo urlencode($product['name']); ?>">
                        <td>
                    <img src="<?php echo $product['image']; ?>" alt="Hình ảnh sản phẩm" width="200" height="200">
                    </td>
                        </a>
                        <p><strong><?php echo $product['name']; ?></strong></p>
                        <p class="old-price">
                            <?php echo number_format($product['price'], 0, ',', '.') . ' đ'; ?>
                        </p>
                        <p class="new-price">
                        <?php 
            if ($product['discount'] > 0) {
                $new_price = $product['price'] * (1 - $product['discount'] / 100);
                echo '<p class="new-price">' . number_format($new_price, 0, ',', '.') . ' đ</p>';
            }
            ?>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </main>
</body>
</html>

<?php $conn->close(); ?>


<!-- Phần nội dung blog -->
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f7f7f7;
        }

        .blog-container {
            padding: 30px;
            max-width: 1200px;
            margin: 0 auto;
        
        }

        h1 {
            text-align: center;
            font-size: 28px;
            margin-bottom: 30px;
            color: #333;
        }

        .blog-list {
            display: flex;
            justify-content: space-between;
            gap: 20px;
        }

        .blog-item {
            width: 400px; /* Chiều rộng cố định */
            height: 300px; /* Chiều cao cố định */
            flex: 1;
            width: calc(50% - 20px); /* 2 bài viết trên một hàng */
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
            background-color: #fff;
        }

        .blog-item img {
            width: 100%;
            height: 180px; /* Chiều cao cố định cho hình ảnh */
            object-fit: cover; /* Giúp hình ảnh được cắt gọn mà vẫn giữ tỷ lệ */
        }

        .blog-item-content {
            padding: 15px;
            text-align: left;
        }

        .blog-item-content h2 {
            font-size: 18px;
            color: #333;
            margin: 10px 0;
        }

        .blog-item-content p {
            font-size: 14px;
            color: #555;
            margin: 5px 0;
        }

        .blog-item-content a {
            font-size: 15px; /* Kích thước chữ nhỏ lại */
            color: #0078d7;
            text-decoration: none;
        }

        .blog-item-content a:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .blog-item {
                width: calc(50% - 10px); /* 2 bài viết trên một hàng cho màn hình nhỏ */
            }
        }

        @media (max-width: 480px) {
            .blog-item {
                width: 100%; /* 1 bài viết trên một hàng cho điện thoại di động */
            }
        }
    </style>
</head>
<body>
    <div class="blog-container">
        <h1>BLOG</h1>
        <div class="blog-list">
            <div class="blog-item">
                <img src="ảnh/sofa alle xam/allexam4 copy.jpeg" alt="Nội thất ">
                <div class="blog-item-content">
                    <h2>Nội thất đa năng: Giải pháp cho cuộc sống hiện đại</h2>
                    <p>Tìm hiểu về các sản phẩm nội thất đa năng giúp bạn tiết kiệm không gian và tăng tính tiện ích cho ngôi nhà của mình.</p>
                    <a href="#">Xem thêm</a> <!-- Chỉ để chữ Đọc thêm -->
                </div>
            </div>

            <div class="blog-item">
                <img src="ảnh/sofa alle xam/allexam4 copy.jpeg" alt="Nội thất ">
                <div class="blog-item-content">
                    <h2>Nội thất đa năng: Giải pháp cho cuộc sống hiện đại</h2>
                    <p>Tìm hiểu về các sản phẩm nội thất đa năng giúp bạn tiết kiệm không gian và tăng tính tiện ích cho ngôi nhà của mình.</p>
                    <a href="#">Xem thêm</a> <!-- Chỉ để chữ Đọc thêm -->
                </div>
            </div>
        </div>
    </div>
</body>
</html>

<!-- Phần Footer -->
<style>
    footer {
        background-color: #d3d3d3; /* Nền xám đậm hơn */
        color: #333;
        padding: 40px 0;
        font-family: Arial, sans-serif;
    }
    
    .footer-container {
        display: flex;
        justify-content: space-between;
        max-width: 1200px;
        margin: auto;
        padding: 0 20px;
        flex-wrap: wrap;
    }
    
    .footer-section {
        flex: 1;
        margin: 15px;
        text-align: justify;
    }
    
    .footer-section h3 {
        font-size: 18px;
        margin-bottom: 10px;
        color: #0d425d;
    }
    
    .footer-section a, .footer-section p {
        font-size: 14px;
        color: #555;
        text-decoration: none;
    }
    
    .footer-section a:hover {
        color: #0078d7;
    }
    
    /* Logo */
    .logo {
        margin-bottom: 15px;
    }
    
    /* Responsive Design */
    @media (max-width: 768px) {
        .footer-container {
            flex-direction: column;
        }
    }
    </style>
    
    <footer>
        <div class="footer-container">
            <!-- Phần Logo -->
            <div class="footer-section">
                <img src="anhlogo.jpg" alt="Logo" class="logo" width="150">
            </div>
    
            <!-- Phần Giới thiệu -->
            <div class="footer-section">
                <h3>Giới thiệu</h3>
                <p>Nội thất chất lượng cao phong cách Bắc Âu, mang lại tiện nghi cho không gian của bạn.</p>
                <p><a href="blog.html">Tìm hiểu thêm</a></p>
            </div>
    
            <!-- Phần Hỗ trợ khách hàng -->
            <div class="footer-section">
                <h3>Hỗ trợ khách hàng</h3>
                <p><a href="#">Chính sách mua hàng</a></p>
                <p><a href="#">Giao hàng & Lắp đặt</a></p>
                <p><a href="#">Chính sách bảo hành</a></p>
            </div>
    
            <!-- Phần Thông tin liên hệ -->
            <div class="footer-section">
                <h3>Thông tin liên hệ</h3>
                <p> <img src="Font Awesome/location-dot-solid.svg" style="width: 12px; height: auto;" class="icon" > Đ/c: Cầu Giấy, Hà Nội</p>
                <p> <img src="Font Awesome/phone-solid.svg" style="width: 12px; height: auto;" class="icon" > Sđt: +84 123 456 789</p>
                <p>  <img src="Font Awesome/envelope-solid.svg" style="width: 12px; height: auto;" class="icon"> Email: noithatbacau@gmail.com</p>
            </div>
        </div>
    </footer>
    











   






















