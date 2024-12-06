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
        <a href="cart.php">
            <img src="Font Awesome/cart-shopping-solid.svg" style="width: 20px; height: auto;" class="icon">
            <span id="cart-count"></span>
        </a>
        </div>
    </header>

    
</body>











        <style>
            body {
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 0;
                background-color: #f9f9f9; /* Màu nền nhẹ nhàng */
            }
        
            .blog-container {
                padding: 20px;
            }
        
            h1 {
                text-align: center; /* Căn giữa tiêu đề */
                margin-top: 60px; /* Tạo khoảng cách với thanh menu */
                margin-bottom: 20px; /* Tạo khoảng cách phía dưới thẻ h1 */
            }
        
            .blog-list {
                display: flex;
                justify-content: space-between; 
                gap: 20px; /* Khoảng cách giữa các bài viết */
                flex-wrap: wrap; /* Đảm bảo bố cục không bị vỡ trên các màn hình nhỏ */
            }
        
            .blog-item {
                flex: 1; 
                width: calc(40% - 20px); /* Chia đôi hàng với 2 bài viết */
                box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1); 
                border-radius: 8px; 
                overflow: hidden;
                background-color: #fff; /* Màu nền cho mỗi blog item */
            }

        
            .blog-item img {
                width: 100%;
                height: auto; 
            }
        
            .blog-item-content {
                padding: 15px;
                text-align: left;
            }
        
            @media (max-width: 768px) {
                .blog-item {
                    width: calc(50% - 20px); /* 2 mục sản phẩm trên một hàng cho màn hình nhỏ hơn */
                }
            }
        
            @media (max-width: 480px) {
                .blog-item {
                    width: 100%; /* 1 mục sản phẩm trên một hàng cho điện thoại di động */
                }
            }
        </style>
        
       











        
            <style>
                body {
                    font-family: Arial, sans-serif;
                    margin: 0;
                    padding: 0;
                    background-color: #f9f9f9; /* Màu nền nhẹ nhàng */
                }
        
                .blog-container {
                    padding: 20px;
                }
        
                
        
                .blog-list {
                    display: flex;
                    justify-content: space-between; 
                    gap: 20px; 
                }
        
                .blog-item {
                    flex: 1; 
                    width: calc(33.333% - 20px); 
                    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1); 
                    border-radius: 8px; 
                    overflow: hidden;
                    background-color: #fff; /* Màu nền cho mỗi blog item */
                }
        
                .blog-item img {
                    width: 100%;
                    height: auto; 
                }
        
                .blog-item-content {
                    padding: 15px;
                    text-align: left;
                }
        
                @media (max-width: 768px) {
                    .blog-item {
                        width: calc(50% - 20px); /* 2 mục sản phẩm trên một hàng cho màn hình nhỏ hơn */
                    }
                }
        
                @media (max-width: 480px) {
                    .blog-item {
                        width: 100%; /* 1 mục sản phẩm trên một hàng cho điện thoại di động */
                    }
                }
            </style>
        </head>
        <body>
            <div class="blog-container" style="padding-top: 80px;">
                
                <div class="blog-list">
                    <!-- Bài viết 1 -->
                    <div class="blog-item">
                        <img src="./ảnh/anh blog/anhnenblog2.png" alt="Nội thất đa năng">
                        <div class="blog-item-content">
                            <h2>Những mẫu bàn ăn đẹp, hiện đại và sang trọng cho gia đình bạn</h2>
                            <p>Giới thiệu hơn 50 mẫu bộ bàn ăn đẹp, hiện đại và sang trọng với nhiều chất liệu khác nhau mang đến cảm giác thoải mái và ấm cúng cho không gian gia đình bạn. </p>
                            <a href="blog1.php">Đọc thêm</a>
                        </div>
                    </div>
        
                    <!-- Bài viết 2 -->
                    <div class="blog-item">
                        <img src="./ảnh/anh blog/anhnenblog3.jpg" alt="Nội thất đa năng">
                        <div class="blog-item-content">
                            <h2>Khám phá BST nội thất LIMFJORDEN - Vẻ đẹp thư thái đến từ phong cách Bắc Âu</h2>
                            <p>Bộ sưu tập nội thất LIMFJORDEN mới được xem là hiện thân của phong cách Bắc Âu hiện đại. Vẻ đẹp đến từ chính sự tinh tế và tối giản trong thiết kế.</p>
                            <a href="blog3.php">Đọc thêm</a>
                        </div>
                    </div>
        
                    <!-- Bài viết 3 -->
                    <div class="blog-item">
                        <img src="./ảnh/anh blog/anhnenblog1.jpg" alt="Nội thất đa năng">
                        <div class="blog-item-content">
                            <h2>Bộ sưu tập Markskel: Cảm hứng tân cổ điển cho phong cách Bắc Âu</h2>
                            <p>Bộ sưu tập nội thất Markskel mang đến làn gió mới cho phong cách nội thất Bắc Âu, khi kết hợp hài hòa giữa vẻ đẹp tân cổ điển cùng sự hiện đại và tinh tế đặc trưng của Scandinavian</p>
                            <a href="blog2.php">Đọc thêm</a>
                        </div>
                    </div>
                </div>
            </div>
        </body>
        









        
        <style>
                body {
                    font-family: Arial, sans-serif;
                    margin: 0;
                    padding: 0;
                    background-color: #f9f9f9; /* Màu nền nhẹ nhàng */
                }
        
                .blog-container {
                    padding: 20px;
                }
        
                
        
                .blog-list {
                    display: flex;
                    justify-content: space-between; 
                    gap: 20px; 
                }
        
                .blog-item {
                    flex: 1; 
                    width: calc(33.333% - 20px); 
                    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1); 
                    border-radius: 8px; 
                    overflow: hidden;
                    background-color: #fff; /* Màu nền cho mỗi blog item */
                }
        
                .blog-item img {
                    width: 100%;
                    height: auto; 
                }
        
                .blog-item-content {
                    padding: 15px;
                    text-align: left;
                }
        
                @media (max-width: 768px) {
                    .blog-item {
                        width: calc(50% - 20px); /* 2 mục sản phẩm trên một hàng cho màn hình nhỏ hơn */
                    }
                }
        
                @media (max-width: 480px) {
                    .blog-item {
                        width: 100%; /* 1 mục sản phẩm trên một hàng cho điện thoại di động */
                    }
                }
            </style>
        </head>
        <body>
            <div class="blog-container" style="padding-top: 80px;">
                
                <div class="blog-list">
                    <!-- Bài viết 4 -->
                    <div class="blog-item">
                        <img src="./ảnh/anh blog/anhnenblog9.jpg" alt="Nội thất đa năng">
                        <div class="blog-item-content">
                            <h2>Sắp xếp phòng khách: cách sắp đặt nội thất để tối ưu hóa không gian</h2>
                            <p>Tầm quan trọng của việc sắp xếp nội thất trong phòng khách, để tạo không gian sống tiện nghi và thẩm mỹ.</p>
                            <a href="blog9.php">Đọc thêm</a>
                        </div>
                    </div>
        
                    <!-- Bài viết 5 -->
                    <div class="blog-item">
                        <img src="./ảnh/anh blog/anhnenblog10.jpg" alt="Nội thất đa năng">
                        <div class="blog-item-content">
                            <h2>Lựa chọn nội thất phòng ăn đẹp mắt và cách ứng dụng theo từng diện tích</h2>
                            <p>Nếu bạn đang tìm kiếm mẫu thiết kế hay các sản phẩm phòng ăn phù hợp với ngôi nha. Lại đa công năng sử dụng thì đừng bỏ qua những gợi ý sau đây.</p>
                            <a href="blog10.php">Đọc thêm</a>
                        </div>
                    </div>
        
                    <!-- Bài viết 6 -->
                    <div class="blog-item">
                        <img src="./ảnh/anh blog/anhnenblog11.jpg" alt="Nội thất đa năng">
                        <div class="blog-item-content">
                            <h2>Giải pháp: Thiết kế phòng khách kết hợp phòng ngủ tinh tế</h2>
                            <p>Các căn hộ mini, nhà ống, phòng cho thuê sinh viên có mức giá rẻ đều thiết kế phòng khách kết hợp phòng ngủ để mang đến sự tiện lợi.
                            </p>
                            <a href="blog11.php">Đọc thêm</a>
                        </div>
                    </div>
                </div>
            </div>
        </body>
        










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
    









