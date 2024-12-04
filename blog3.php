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
        <main>
            <div class="container">
                <div class="news-detail-section">
                    <div class="row">
                        <div class="col-lg-9">
                            <h1 class="news-detail-title">Khám phá BST nội thất LIMFJORDEN - Vẻ đẹp thư thái đến từ phong cách Bắc Âu</h1>
                            <div class="news-detail-subtitle">
                                <div class="account">Tác giả: <span>trongprovip</span></div>
                                <div class="text">-</div>
                                <div class="date">Ngày đăng: 13/06/2004</div>
                            </div>
                            <style>
                                .news-detail-title {
                                    font-family: Arial, sans-serif;
                                    font-size: 32px;
                                    color: #2c3e50;
                                    margin: 80px 0 20px; /* Khoảng cách phía trên tăng lên để tránh che khuất */
                                    line-height: 1.2;
                                    text-align: left;
                                    font-weight: bold;
                                }
            
                                .news-detail-subtitle {
                                    margin-bottom: 20px;
                                }
            
                                .account {
                                    font-family: Arial, sans-serif;
                                    font-size: 14px;
                                    color: #333;
                                    margin-bottom: 4px;
                                }
            
                                .account span {
                                    font-weight: bold;
                                }
            
                                .text {
                                    font-size: 14px;
                                    color: #777;
                                    margin: 0 4px;
                                }
            
                                .date {
                                    font-size: 14px;
                                    color: #555;
                                    margin-top: 4px;
                                }
                            </style>
                        </div>
                    </div>
                </div>
            </div>
    
            <div class="article-content">
                <h4>Bộ sưu tập nội thất LIMFJORDEN không chỉ cuốn hút bởi vẻ tinh giản, đường nét gọn gàng, không cầu kỳ, mà còn ghi điểm bởi chất liệu tự nhiên và an toàn. Bảng màu trung tính và đa dạng như xám, trắng và be của LIMFJORDEN sẽ giúp mang đến cảm giác thanh lịch và hiện đại cho tổ ấm của bạn.</h4>
                <p style="text-align: center;">
                    <img alt="" src="./anh/top-in-the-spotlight-limfjorden.jpg" />
                </p>
                <p style="text-align: center;">Cùng khám phá những nét đặc trưng của phong cách Bắc Âu thông qua bộ sưu tập LIMFJORDEN đến từ Nội Thất Bắc Âu nhé!</p>
                <h2>BST nội thất LIMFJORDEN - Nét tinh hoa của phong cách Bắc Âu</h2>
                <p>Bộ sưu tập nội thất LIMFJORDEN từ Nội Thất Bắc Âu được lấy cảm hứng từ vẻ đẹp thanh bình và tinh tế của vịnh hẹp LIMFJORDEN tại Đan Mạch. Với chất liệu tự nhiên và được chế tác một cách tỉ mỉ và an toàn. Mang đến sự thanh lịch và nhẹ nhàng cho không gian sống của bạn.</p>
                <p style="text-align: center;">
                    <img alt="" src="./anh/bst-limfjorden1.jpg" />
                </p>
                <p style="text-align: center;">Cái tên LIMFJORDEN mang nhiều ý nghĩa thú vị, trong đó chính là vẻ đẹp và văn hóa của vùng Scandinavia.</p>
                <p>LIMFJORDEN- là tên của một vịnh nổi tiếng nằm giữa bán đảo Jutland của Đan Mạch. Nơi đây nổi tiếng với vẻ đẹp tự nhiên hoang sơ, những hòn đảo nhỏ duyên dáng và làn nước trong xanh như ngọc. Tên gọi LIMFJORDEN gợi lên sự tinh tế, thanh bình và gần gũi với thiên nhiên.  Đặc trưng của phong cách Bắc Âu.
                    Ngoài ra, “LIM” trong tiếng Đan Mạch có nghĩa là "keo", tượng trưng cho sự gắn kết chặt chẽ, bền vững. Điều này cũng nhấn mạnh về chất lượng và độ bền của các sản phẩm trong bộ sưu tập nội thất LIMFJORDEN. Và với "FJORD"- từ bắt nguồn từ tiếng Na Uy cổ, có nghĩa là "vịnh hẹp" hoặc "eo biển". Những vịnh hẹp này thể hiện nét đặc trưng của vùng Scandinavia, một vẻ đẹp hùng vĩ và bí ẩn.  <br>  <br>  <br>  Có thể nói, cái tên LIMFJORDEN không chỉ đơn thuần là một địa danh mà còn gợi sự kết hợp tinh tế giữa thiên nhiên, chất liệu bền vững và cá tính riêng biệt. Hoàn toàn phù hợp với tinh thần và giá trị của phong cách Bắc Âu, đây cũng là ý nghĩa mà Nội Thất Bắc Âu mong muốn mang đến cho khách hàng của mình.</p>
                    <p style="text-align: center;">
                        <img alt="" src="./anh/bst-limfjorden2.jpg" />
                    </p>
                    <p style="text-align: center;">Cảm nhận sự tinh tế và vẻ đẹp mượt mà của dòng sản phẩm LIMFJORDEN, chắc chắn sẽ mang lại một cảm giác bình yên và thư thái như một tách trà ấm áp trong một ngày se lạnh.</p>
                    <h2>Điểm nhấn đặc sắc của bộ sưu tập nội thất LIMFJORDEN của Nội Thất Bắc Âu</h2>
                    <p>Có thể nói, điểm nhấn của các sản phẩm LIMFJORDEN chính là đường nét đẹp mắt, cùng thiết kế tối giản phù hợp với mọi ngôi nhà. Vẻ ngoài vượt thời gian của sản phẩm dễ dàng hòa quyện vào nội thất phòng khách, phòng ngủ hoặc phòng làm việc của bạn, phục vụ đồng thời cả mục đích chức năng và thẩm mỹ.</p>
                    <p style="text-align: center;">
                        <img alt="" src="./anh/bst-limfjorden4.jpg" />
                    </p>
                    <p style="text-align: center;">Trong đó, bộ sản phẩm LIMFJORDEN sẽ bao gồm: Tủ có ngăn kéo với nhiều kích cỡ và màu sắc khác nhau, tủ đầu giường, tủ quần áo và bàn làm việc… với 3 tông màu chủ đạo là: màu trắng, màu gỗ và be.</p>
                    <h3>Tủ quần áo LIMFJORDEN</h3>
                    <p>Tủ quần áo LIMFJORDEN là sản phẩm mới được làm từ chất liệu gỗ công nghiệp và cung cấp các lựa chọn lưu trữ tốt nhất dành cho phòng ngủ của bạn. Một chiếc tủ quần áo rộng rãi sẽ là giải pháp lý tưởng cho các cặp đôi hoặc những người yêu thích thời trang.</p>
                    <p style="text-align: center;">
                        <img alt="" src="./anh/bst-limfjorden7.jpg" />
                    </p>
                    <p style="text-align: center;">
                        <img alt="" src="./anh/bst-limfjorden6.jpg" />
                    </p>
                    <p style="text-align: center;">Tủ quần áo LIMFJORDEN có 2 kích cỡ gồm: Tủ 3 ngăn R180xS58xC200cm và tủ 2 ngăn R120xS58xC200cm</p>
                    <h3>Tủ đầu giường LIMFJORDEN</h3>
                    <p>Tủ đầu giường đóng vai trò như một điểm nhấn tuyệt vời trong nội thất phòng ngủ. Với thiết kế nhỏ gọn và xinh xắn, tủ đầu giường giúp đồ đạc của bạn được sắp xếp một cách gọn gàng, ngăn nắp mà còn góp phần làm cho không gian chung trở nên tiện nghi và hiện đại hơn.</p>
                    <p style="text-align: center;">
                        <img alt="" src="./anh/bst-limfjorden8.jpg" />
                    </p>
                    <p style="text-align: center;">Tủ đầu giường LIMFJORDEN với thiết kế tối giản nhưng đa dụng, là sự lựa chọn lý tưởng cho phòng ngủ của bạn.</p>
                    <h3>Giường ngủ LIMFJORDEN</h3>
                    <p style="text-align: center;">
                        <img alt="" src="./anh/bst-limfjorden12.jpg" />
                    </p>
                    <p style="text-align: center;">Ghi điểm với thiết kế tinh tế và đa năng, giường ngủ LIMFJORDEN được tăng cường thêm 2 ngăn kéo nằm bên hông.</p>
                    <p style="font-size: 20px;">Với 2 ngăn kéo bên hông của giường LIMFJORDEN, nhờ vậy bạn có thêm không gian lưu trữ vô cùng tiện lợi để cất giữ những bộ chăn- ga-drap, tài liệu hoặc những đồ vậy ít dùng tới.</p>
                    <h2>Bàn làm việc LIMFJORDEN</h2>
                    <p>Ngoài cung cấp các giải pháp lưu trữ rộng rãi và đầy phong cách, bộ sưu tập LIMFJORDEN còn có thêm những sản phục vụ cho từng không gian. Trong đó bàn làm việc LIMFJORDEN với thiết kế bốn ngăn kéo, cùng kích thước rộng rãi bao gồm: chiều rộng 60cm, chiều dài 120cm và chiều cao 76cm. Đây được xem là kích thước lý tưởng để các thành viên trong gia đình bạn có thể sử dụng thoải mái.</p>
                    <p style="text-align: center;">
                        <img alt="" src="./anh/bst-limfjorden11.jpg" />
                    </p>
                    <p style="text-align: center;">Bàn làm việc LIMFJORDEN có 3 màu sắc để bạn lựa chọn. Cả ba phiên bản đều tạo nên sự sang trọng đầy phong cách, phù hợp với mọi kiểu trang trí nội thất.</p>
</div>      
<style>
    .article-content {
        font-family: Arial, sans-serif;
        line-height: 1.6;
        margin: 20px;
        padding: 15px; /* Thêm khoảng cách bên trong khung nội dung */
        border: 1px solid #ddd; /* Viền cho khung nội dung */
        border-radius: 5px; /* Bo góc khung */
        background-color: #f9f9f9; /* Màu nền cho khung nội dung */
    }

    .article-content p {
        font-size: 16px;
        color: #333;
        margin-bottom: 12px;
        text-align: justify; /* Căn đều cho đoạn văn */
    }

    .article-content h2,
    .article-content h3 {
        color: #2c3e50;
        margin-top: 20px;
    }

    .article-content h2 {
        font-size: 26px; /* Kích thước chữ cho tiêu đề h2 */
        margin-bottom: 10px; /* Khoảng cách dưới tiêu đề h2 */
    }

    .article-content h3 {
        font-size: 24px; /* Kích thước chữ cho tiêu đề h3 */
        margin-bottom: 8px; /* Khoảng cách dưới tiêu đề h3 */
    }

    .article-content img {
        max-width: 100%;
        height: auto;
        margin: 10px 0;
        transition: transform 0.2s; /* Hiệu ứng chuyển động khi hover */
    }

    .article-content img:hover {
        transform: scale(1.05); /* Phóng to hình ảnh khi hover */
    }

    .article-content ol {
        margin-left: 20px; /* Khoảng cách bên trái cho danh sách có thứ tự */
    }

    .article-content ol li {
        font-size: 16px;
        color: #333;
        margin-bottom: 8px;
        position: relative; /* Để thêm hiệu ứng cho các mục */
        padding-left: 20px; /* Thêm khoảng cách bên trái cho các mục */
    }

    .article-content ol li::marker {
        color: #e74c3c; /* Màu cho số thứ tự của danh sách */
        font-weight: bold; /* Làm nổi bật số thứ tự */
    }
</style>

    </body>
    </html>