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
                            <h1 class="news-detail-title">Bộ sưu tập Markskel: Cảm hứng tân cổ điển cho phong cách Bắc Âu</h1>
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
                <p>Bộ sưu tập nội thất Markskel của Nội Thất Bắc Âu mang đến làn gió mới cho phong cách nội thất Bắc Âu, khi kết hợp hài hòa giữa vẻ đẹp tân cổ điển cùng sự hiện đại và tinh tế đặc trưng của Scandinavian. Lấy cảm hứng từ các chi tiết lịch lãm, cổ điển nhưng không kém phần thanh thoát, BST Markskel không chỉ tạo nên không gian sống sang trọng mà còn đậm chất nghệ thuật. Mỗi sản phẩm trong bộ sưu tập đều mang một dấu ấn riêng, vừa giữ được vẻ đẹp vượt thời gian, vừa thổi hồn vào đó sự tươi mới, hiện đại của phong cách Bắc Âu.</p>
                <p style="text-align: center;">
                    <img alt="" src="./anh/top-image---in-the-spotlight-the-markskel-furniture-collection.jpg" />
                </p>
                <h2>BST Markskel - Vẻ đẹp hiện đại "ẩn mình" trong phong cách cổ điển</h2>
                <p>Không chỉ đơn giản là các sản phẩm nội thất làm từ gỗ, bộ sưu tập Markskel của Nội Thất Bắc Âu còn thổi vào không gian một hơi thở Scandinavia ấm cúng và hài hòa. "Markskel" trong tiếng Đan Mạch có nghĩa là "biên giới cánh đồng", thể hiện ranh giới giữa cái cũ và cái mới. Điều này hoàn toàn phù hợp với ý tưởng bộ sưu tập, khi nó gợi nhớ đến sự kết hợp đầy sáng tạo giữa yếu tố cổ điển và sự hiện đại trong phong cách Bắc Âu.</p>
                <h2>Điểm nhấn nổi bật của bộ sưu tập Markskel</h2>
                <p>BST Markskel không chỉ thu hút bởi vẻ đẹp tinh tế mà còn mang đến tính tiện dụng với các món nội thất như tủ quần áo, giường, bàn làm việc, kệ TV và nhiều sản phẩm khác. Những thiết kế này vừa giúp tối ưu hóa không gian lưu trữ, vừa mang lại sự ấm áp và lịch lãm cho ngôi nhà. Các chất liệu cao cấp như gỗ tự nhiên, màu sơn trắng, tay nắm đồng tạo nên sự kết hợp hoàn hảo giữa cổ điển và hiện đại, giúp không gian trở nên hài hòa, thanh lịch.</p>
                <h3>Tối ưu không gian phòng ăn</h3>
                <p>Một trong những điểm nổi bật của BST Markskel là các sản phẩm dành cho phòng bếp, bao gồm tủ búp phê, tủ trưng bày và bàn ăn với thiết kế tinh tế, đa dạng ngăn chứa. Sự đồng bộ này không chỉ giúp giữ cho phòng ăn luôn gọn gàng mà còn tạo điểm nhấn ấn tượng với những chi tiết thanh lịch.</p>
                <p style="text-align: center;">
                    <img alt="" src="./anh/tu-bep.jpg" />
                </p>
                <p style="text-align: center;">
                    <img alt="" src="./anh/tu-bep-dep.jpg" />
                </p>
                <h3>Làm đẹp không gian phòng khách</h3>
                <p>Nội thất Markskel không chỉ đẹp về thẩm mỹ mà còn đáp ứng tối đa nhu cầu sử dụng với các sản phẩm như bàn cà phê, kệ Tivi tích hợp ngăn chứa rộng rãi. Thiết kế tân cổ điển tinh tế giúp dễ dàng phối hợp với các món đồ khác, tạo nên một phòng khách gọn gàng và ấn tượng. Các sản phẩm trong BST này vừa mang lại vẻ đẹp thanh lịch, vừa đảm bảo sự tiện nghi cho không gian sống của bạn.</p>
            </p>
            <p style="text-align: center;">
                <img alt="" src="./anh/tu-cafe-markskel.jpg" />
            </p>
            <h3>Tạo không gian làm việc hiệu quả</h3>
            <p>Bàn làm việc trong BST Markskel là lựa chọn lý tưởng cho không gian làm việc tại nhà. Thiết kế tích hợp các ngăn lưu trữ giúp bạn sắp xếp tài liệu và vật dụng cá nhân một cách gọn gàng. Đồng thời, tủ sách được bố trí hợp lý để tạo nên một không gian làm việc khoa học và ấm cúng.</p>
            <p style="text-align: center;">
                <img alt="" src="./anh/ban-lam-viec-markskel.jpg" />
            </p>
            <h3>Không gian phòng ngủ hoàn hảo với Markskel</h3>
            <p>Đối với phòng ngủ, tủ quần áo Markskel là giải pháp lưu trữ lý tưởng, với thiết kế thông minh và nhiều ngăn chứa rộng rãi. Cùng hai kích cỡ phù hợp cho nhu cầu gia đình, riêng với tủ có kích thước lớn được trang bị gương tiện dụng. Bên cạnh đó, giường ngủ và tủ đầu giường trong bộ sưu tập cũng giúp hoàn thiện không gian nghỉ ngơi một cách đồng bộ và tinh tế.</p>
            <p style="text-align: center;">
                <img alt="" src="./anh/set-noi-that-phong-ngu-markskel.jpg" />
            </p>
           <h2>Đồng điệu không gian với trọn bộ sản phẩm BST Markskel từ Nội Thất Bắc Âu</h2>
           <p>Markskel không chỉ mang lại tính thẩm mỹ mà còn đáp ứng mọi nhu cầu lưu trữ và sắp xếp không gian sống. Với thiết kế đồng bộ và linh hoạt, BST này giúp bạn dễ dàng tạo nên một tổ ấm hiện đại và ấm cúng. Đặc biệt, các sản phẩm của Nội Thất Bắc Âu  đều đạt chứng nhận an toàn môi trường và sức khỏe: FSC®, vì vậy bạn có thể hoàn toàn yên tâm khi mua sắm tại Nội Thất Bắc Âu.</p>
           <p style="text-align: center;">
            <img alt="" src="./anh/bst-markskel.jpg" />
        </p>
        <p style="text-align: center;">Hãy khám phá thêm những mẹo trang trí hữu ích từ Nội Thất Bắc Âu và tìm cho mình những sản phẩm nội thất Bắc Âu phù hợp để làm đẹp không gian sống của bạn!</p>
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