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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport content="width=device-width, initial-scale=1.0>
    <link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"
      integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w=="
      crossorigin="anonymous" referrerpolicy="no-referrer" />
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
            z-index: 1002; /* Đảm bảo header nằm trên các phần tử khác */

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
            visibility: hidden; /* Ẩn hoàn toàn */
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
            width: 180px;
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
        #loginForm {
            display: none;
        }
        .loginnn{
            color: #0d425d; 
            margin-right: 20px;
            font-size: 18px;
            font-weight: bold; 
            cursor: pointer;
            
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
                <li><a href="index.html">Trang Chủ</a></li>
                <li><a href="#">Phòng</a>
                    <ul class="sub-menu">
                        <li><a href="#">Phòng khách</a></li>
                        <li><a href="#">Phòng ngủ</a></li>
                        <li><a href="#">Phòng ăn</a></li>
                        <li><a href="#">Phòng làm việc</a></li>
                    </ul>
                </li>
                <li><a href="khuyenmai.html">Khuyến mại</a></li>
                <li><a href="blog.html">Blog</a></li>
            </ul>
        </div>
        <div class="other">
            <input type="text" placeholder="Tìm kiếm..."> 
            <div class="loginnn"  id="loginLink">
              <img src="Font Awesome/user-solid.svg" style="width: 15px; height: auto;" class="icon">
              Đăng nhập
            </div>
            <a href="giohang.html">
                <img src="Font Awesome/cart-shopping-solid.svg" style="width: 20px; height: auto;" class="icon" >
                <span id="cart-count">0</span>
            </a>
        </div>
        <script>
           document.getElementById("loginLink").onclick = function () {
        const overlay = document.querySelector('.overlay');
        const centeredForm = document.querySelector('.centered-form');
        overlay.style.display = "block";
        centeredForm.style.display = "block";
        centeredForm.classList.add("signinForm"); // Bắt đầu với biểu mẫu đăng nhập
    };

    document.querySelector(".close-btn").onclick = function () {
        document.querySelector('.overlay').style.display = "none";
        document.querySelector('.centered-form').style.display = "none";
    };

    document.querySelector(".create").onclick = function () {
        document.querySelector(".container").classList.remove("signinForm"); // Chuyển sang biểu mẫu đăng ký
    };

    document.querySelector(".login").onclick = function () {
        document.querySelector(".container").classList.add("signinForm"); // Chuyển lại biểu mẫu đăng nhập
    };

    // Ẩn biểu mẫu khi nhấp ra ngoài nó.(on overlay)
    document.querySelector('.overlay').onclick = function () {
        document.querySelector('.overlay').style.display = "none";
        document.querySelector('.centered-form').style.display = "none";
    };
     

        </script>
    </header>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap');
  
        * {
          margin: 0;
          padding: 0;
          box-sizing: border-box;
          font-family: 'Poppins', sans-serif;
        }
  
        body {
          display: flex;
          justify-content: center;
          align-items: center;
          min-height: 100vh;
          background: #fff;
        }
  
        .container {
          padding: 40px;
          border-radius: 20px;
          border: 8px solid #e0e0e0;
          box-shadow: -5px -5px 15px rgba(255, 255, 255, 0.1),
            5px 5px 15px rgba(0, 0, 0, 0.35),
            inset -5px -5px 15px rgba(255, 255, 255, 0.1),
            inset 5px 5px 15px rgba(0, 0, 0, 0.35);
          background-color: #e0e0e0;
        }
  
        .close-btn {
          position: absolute;
          top: 5px;
          right: 15px;
          color: #fff;
          cursor: pointer;
          font-size: 2em;
        }
  
        .container .form {
          display: flex;
          justify-content: center;
          align-items: center;
          flex-direction: column;
          gap: 25px;
        }
  
        .container .form.signin,
        .container.signinForm .form.signup {
          display: none;
        }
  
        .container.signinForm .form.signin {
          display: flex;
        }
  
        .container .form h2 {
          color: #6495ed;
          font-weight: 500;
          letter-spacing: 0.1em;
        }
  
        .container .form .inputBox {
          position: relative;
          width: 300px;
        }
  
        .container .form .inputBox input {
          padding: 12px 10px 12px 48px;
          border: none;
          width: 100%;
          background: #e0e0e0;
          border: 1px solid #e0e0e0;
          color: #6495ed;
          font-weight: 300;
          border-radius: 25px;
          font-size: 1em;
          box-shadow: -5px -5px 15px rgba(255, 255, 255, 0.1),
            5px 5px 15px rgba(0, 0, 0, 0.35);
          transition: 0.5s;
          outline: none;
        }
  
        .container .form .inputBox span {
          position: absolute;
          left: 0;
          padding: 12px 10px 12px 48px;
          pointer-events: none;
          font-size: 1em;
          font-weight: 300;
          transition: 0.5s;
          letter-spacing: 0.05em;
          color: #6495ed;
        }
  
        .container .form .inputBox input:valid~span,
        .container .form .inputBox input:focus~span {
          color: #6495ed;
          border: 1px solid #e0e0e0;
          background: #e0e0e0;
          transform: translateX(25px) translateY(-7px);
          font-size: 0.6em;
          padding: 0 8px;
          border-radius: 10px;
          letter-spacing: 0.1em;
        }
  
        .container .form .inputBox input:valid,
        .container .form .inputBox input:focus {
          border: 1px solid #e0e0e0;
        }
  
        .container .form .inputBox i {
          position: absolute;
          top: 15px;
          left: 16px;
          width: 25px;
          padding: 2px 0;
          padding-right: 8px;
          color: #6495ed;
          border-right: 1px solid #6495ed;
        }
  
        .container .form .inputBox input[type="submit"] {
          background: #e0e0e0;
          color: #6495ed;
          padding: 10px 0;
          font-weight: 500;
          cursor: pointer;
          box-shadow: -5px -5px 15px rgba(255, 255, 255, 0.1),
            5px 5px 15px rgba(0, 0, 0, 0.35),
            inset -5px -5px 15px rgba(255, 255, 255, 0.1),
            inset 5px 5px 15px rgba(0, 0, 0, 0.35);
        }
  
        .container .form p {
          color: #6495ed;
          font-size: 0.75em;
          font-weight: 300;
        }
  
        .container .form p a {
          font-weight: 500;
          color: #6495ed;
        }

        .remember-me {
       display: flex;
       align-items: center;
       margin-top: 10px; /* Điều chỉnh khoảng cách từ trường mật khẩu */
       padding-left: 10px; /* Khoảng cách giữa checkbox và cạnh trái */
       margin-left: -190px;
       }

        .remember-me label {
        color: #6495ed;
        font-size: 0.9em;
        margin-left: 3px;
       }


        a{
          font-size: 12px;
        
        }

.overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5); 
    z-index: 1001; 
    display: none;
}
.centered-form {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 500px; 
    background-color: #fff;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    z-index: 2000; 
    border-radius: 20px;
    display: none;
    
}

      </style>
    </head>
    <body style="background-color: #fff">
        
        <div class="overlay"></div>
        <div class="centered-form">
      <div class="container signinForm">
        <span class="close-btn">&times;</span>
  
        <div class="form signin">
          <h2>Đăng nhập</h2>
          <div class="inputBox">
            <input type="text" required="required">
            <i class="fa-regular fa-user"></i>
            <span>Tài Khoản</span>
          </div>
          <div class="inputBox">
            <input type="password" required="required">
            <i class="fa-solid fa-lock"></i>
            <span>Mật khẩu</span>
          </div>
          <div class="remember-me">
            <input type="checkbox" id="rememberMe">
            <label for="rememberMe">Lưu mật khẩu</label>
        </div>
          <div class="inputBox">
            <input type="submit" value="Đăng nhập">
          </div>
          <p>Not Registered? <a href="#" class="create">Tạo tài khoản</a></p>
        </div>

        <div class="form signup">
          <h2>Đăng kí</h2>
          <div class="inputBox">
            <input type="text" required="required">
            <i class="fa-regular fa-user"></i>
            <span>Tài khoản</span>
          </div>
          <div class="inputBox">
            <input type="text" required="required">
            <i class="fa-regular fa-envelope"></i>
            <span>Email</span>
          </div>
          <div class="inputBox">
            <input type="password" required="required">
            <i class="fa-solid fa-lock"></i>
            <span>Mật khẩu</span>
          </div>
          <div class="inputBox">
            <input type="password" required="required">
            <i class="fa-solid fa-lock"></i>
            <span>Nhập lại mật khẩu</span>
          </div>
          <div class="inputBox">
            <input type="submit" value="Tạo tài khoản">
          </div>
          <p>Already a member? <a href="#" class="login">Đăng nhập</a></p>
        </div>
      </div>
    </div>
      <script>
        let login = document.querySelector(".login");
        let create = document.querySelector(".create");
        let container = document.querySelector(".container");
        let closeBtn = document.querySelector(".close-btn");
  
        login.onclick = function() {
          container.classList.add("signinForm");
        };
        create.onclick = function() {
          container.classList.remove("signinForm");
        };
        
        closeBtn.onclick = function() {
          container.style.display = "none";
        };
// Show the overlay and login form when "Đăng nhập" is clicked
document.getElementById("loginLink").onclick = function () {
    const overlay = document.querySelector('.overlay');
    const centeredForm = document.querySelector('.centered-form');
    overlay.style.display = "block"; // Show overlay
    centeredForm.style.display = "block"; // Show form
};

// Hide the overlay and form when "X" button is clicked
document.querySelector(".close-btn").onclick = function () {
    const overlay = document.querySelector('.overlay');
    const centeredForm = document.querySelector('.centered-form');
    overlay.style.display = "none"; // Hide overlay
    centeredForm.style.display = "none"; // Hide form
};

// Optional: Close the overlay when clicking outside the form
document.querySelector('.overlay').onclick = function (event) {
    if (event.target === event.currentTarget) { // Ensure click is on overlay
        const overlay = document.querySelector('.overlay');
        const centeredForm = document.querySelector('.centered-form');
        overlay.style.display = "none"; // Hide overlay
        centeredForm.style.display = "none"; // Hide form
    }
};

      </script>
    </body>
    
</body>



















<!-- Phần Banner -->

<style>
    

    .carousel {
        position: relative;
        overflow: hidden;
        width: 80%; 
        height: 300px; /* Chiều cao của banner */
        margin: 0 auto; /* Căn giữa phần tử cha */
        margin-top: 0px; /* Tạo khoảng cách với thanh menu */

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
            height: 500px; /* Tăng chiều cao banner thêm nữa cho màn hình rất lớn */
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
    
    <title>Banner Tự Trượt</title>
<div class="carousel">
    <div class="carousel-images">
        <img src="ảnh/khuyenmai/khach.jpg" alt="Image 1">
        <img src="ảnh/khuyenmai/ngu.jpg" alt="Image 2">
        <img src="ảnh/khuyenmai/an.jpg" alt="Image 3">
        <img src="ảnh/khuyenmai/lamvc.jpg" alt="Image 4">
    </div>

</div>
<script src="script.js"></script> <!-- Liên kết tới tệp JavaScript -->










<!-- Sản Phẩm-->
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
            justify-content: space-around;
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

<body>
    <div class="product-section">
        <h1> HOT DEAL </h1>
        <div class="product-grid">
            <!-- Product 1  -->
            <div class="product-item">
                <div class="discount-label">-20%</div>
                <a href="bannanghatrang.html">
                    <img src=".ảnh/Bàn nâng hạ trắng/bannanghatrang1.jpeg" alt="Bàn làm việc điều chỉnh độ cao | SVANEKE trắng">
                </a>
                <p>Mã: 207</p>
                <p><strong>Bàn nâng hạ | SVANEKE trắng</strong></p>
                <p class="old-price"><del>12.900.000 đ</del></p>
                <p class="new-price">10.320.000 đ</p>
                
            </div>
    
            <!-- Product 2 -->
            <div class="product-item">
                <div class="discount-label">-50%</div>
                <a href="bannanghatrang.html">
                    <img src="ảnh/sofa alle xam/allexam1.jpeg" >
                </a>
                <p>Mã: 207</p>
                <p><strong>Sofa | SVANEKE trắng</strong></p>
                <p class="old-price"><del>24.000.000 đ</del></p>
                <p class="new-price">12.000.000 đ</p>
                
            </div>
    
            <!-- Product 3  -->
            <div class="product-item">
                <div class="discount-label">-30%</div>
                <a href="bannanghatrang.html">
                    <img src="ảnh/giường age/giuongage1.jpeg" >
                </a>
                <p>Mã: 207</p>
                <p><strong> Giường | SVANEKE trắng</strong></p>
                <p class="old-price"><del>9.900.000 đ</del></p>
                <p class="new-price">6.930.000 đ</p>
                
            </div>
    
    
            <!-- Product 4  -->
            <div class="product-item">
                <div class="discount-label">-50%</div>
                <a href="bannanghatrang.html">
                    <img src="ảnh/bộ jegin trắng/bojegind1.jpeg" >
                </a>
                <p>Mã: 207</p>
                <p><strong>Bộ bàn ghế ăn | SVANEKE trắng</strong></p>
                <p class="old-price"><del>12.900.000 đ</del></p>
                <p class="new-price">10.320.000 đ</p>
                
            </div>
    
            <!-- Product 5  -->
            <div class="product-item">
                <div class="discount-label">-20%</div>
                <a href="bannanghatrang.html">
                    <img src="ảnh/tủ qa tqabir/tqabir1.jpeg" alt="">
                </a>
                <p>Mã: 207</p>
                <p><strong>Tủ quần áo | SVANEKE trắng</strong></p>
                <p class="old-price"><del>12.900.000 đ</del></p>
                <p class="new-price">10.320.000 đ</p>
                
            </div>

            <!-- Product 6  -->
            <div class="product-item">
                <div class="discount-label">-20%</div>
                <a href="bannanghatrang.html">
                    <img src="ảnh/Bàn nâng hạ trắng/bannanghatrang1.jpeg" alt="Bàn làm việc điều chỉnh độ cao | SVANEKE trắng">
                </a>
                <p>Mã: 207</p>
                <p><strong>Bàn làm việc điều chỉnh độ cao | SVANEKE trắng</strong></p>
                <p class="old-price"><del>12.900.000 đ</del></p>
                <p class="new-price">10.320.000 đ</p>
                
            </div>
            
        </div>
    </div>


    <div class="product-section">
    <h1> SẢN PHẨM MỚI </h1>
    <div class="product-grid">
        <!-- Product 1  -->
        <div class="product-item">
            <div class="discount-label">-20%</div>
            <a href="bannanghatrang.html">
                <img src="ảnh/bannanghatrang1.jpeg" alt="Bàn làm việc điều chỉnh độ cao | SVANEKE trắng">
            </a>
            <p>Mã: 207</p>
            <p><strong>Bàn làm việc điều chỉnh độ cao | SVANEKE trắng</strong></p>
            <p class="old-price"><del>12.900.000 đ</del></p>
            <p class="new-price">10.320.000 đ</p>
            
        </div>

        <!-- Product 2 -->
        <div class="product-item">
            <div class="discount-label">-20%</div>
            <a href="bannanghatrang.html">
                <img src="ảnh/bannanghatrang1.jpeg" alt="Bàn làm việc điều chỉnh độ cao | SVANEKE trắng">
            </a>
            <p>Mã: 207</p>
            <p><strong>Bàn làm việc điều chỉnh độ cao | SVANEKE trắng</strong></p>
            <p class="old-price"><del>12.900.000 đ</del></p>
            <p class="new-price">10.320.000 đ</p>
            
        </div>

        <!-- Product 3  -->
        <div class="product-item">
            <div class="discount-label">-20%</div>
            <a href="bannanghatrang.html">
                <img src="ảnh/bannanghatrang1.jpeg" alt="Bàn làm việc điều chỉnh độ cao | SVANEKE trắng">
            </a>
            <p>Mã: 207</p>
            <p><strong>Bàn làm việc điều chỉnh độ cao | SVANEKE trắng</strong></p>
            <p class="old-price"><del>12.900.000 đ</del></p>
            <p class="new-price">10.320.000 đ</p>
            
        </div>


        <!-- Product 4  -->
        <div class="product-item">
            <div class="discount-label">-20%</div>
            <a href="bannanghatrang.html">
                <img src="ảnh/bannanghatrang1.jpeg" alt="Bàn làm việc điều chỉnh độ cao | SVANEKE trắng">
            </a>
            <p>Mã: 207</p>
            <p><strong>Bàn làm việc điều chỉnh độ cao | SVANEKE trắng</strong></p>
            <p class="old-price"><del>12.900.000 đ</del></p>
            <p class="new-price">10.320.000 đ</p>
            
        </div>

        <!-- Product 5  -->
        <div class="product-item">
            <div class="discount-label">-20%</div>
            <a href="bannanghatrang.html">
                <img src="ảnh/bannanghatrang1.jpeg" alt="Bàn làm việc điều chỉnh độ cao | SVANEKE trắng">
            </a>
            <p>Mã: 207</p>
            <p><strong>Bàn làm việc điều chỉnh độ cao | SVANEKE trắng</strong></p>
            <p class="old-price"><del>12.900.000 đ</del></p>
            <p class="new-price">10.320.000 đ</p>
            
        </div>

        <!-- Product 6  -->
        <div class="product-item">
            <div class="discount-label">-20%</div>
            <a href="bannanghatrang.html">
                <img src="ảnh/bannanghatrang1.jpeg" alt="Bàn làm việc điều chỉnh độ cao | SVANEKE trắng">
            </a>
            <p>Mã: 207</p>
            <p><strong>Bàn làm việc điều chỉnh độ cao | SVANEKE trắng</strong></p>
            <p class="old-price"><del>12.900.000 đ</del></p>
            <p class="new-price">10.320.000 đ</p>
        </div>
        
    </div>
</div>
</body>
</html>










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
                <img src="ảnh/allexam4.jpeg" alt="Nội thất ">
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
    