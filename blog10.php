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
        <main style=" margin-top: 83px; ">
            <div class="container">
                <div class="news-detail-section">
                    <div class="row">
                        <div class="col-lg-9">
                            <h1 class="news-detail-title">Lựa chọn nội thất phòng ăn đẹp mắt và cách ứng dụng theo từng diện tích</h1>
                            <div class="news-detail-subtitle">
                                <div class="account">Tác giả: <span>THTrueFalse</span></div>
                                <div class="text">-</div>
                                <div class="date">Ngày đăng:?????</div>
                            </div>
                            <style>
                                .news-detail-title {
                                    font-family: Arial, sans-serif;
                                    font-size: 32px;
                                    color: #2c3e50;
                                    margin: 20px 0 20px; /* Khoảng cách phía trên tăng lên để tránh che khuất */
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
                <p class="p">Để sở hữu một không gian sống trọn vẹn, nhiều người không ngại “chi mạnh” để nâng cấp không gian sống của mình. Đặc biệt là lựa chọn nội thất phòng ăn, phòng khách và phòng ngủ. Làm sao có thể đáp ứng đầy đủ các yếu tố như: đẹp mắt, hợp xu hướng, đầy đủ công năng và linh hoạt theo từng diện tích.</p>
                <p style="text-align: center;">
                    <img alt="" src="https://jysk.vn/Data/Sites/1/media/blog-news/t8-2023/mau-thiet-ke-phong-an/top-image---mix-and-match-your-dining-chairs.jpg"/></p>
                    <p class="p">Top mẫu thiết kế phòng ăn được chuyên gia đánh giá cao, từ xu hướng trang trí và đồ dùng nhà bếp phù hợp.</p>
                    <p class="p">Ngoài yếu tố đẹp mắt, những mẫu thiết kế phòng ăn đẹp không chỉ tạo nên không gian ấm cúng mà còn thể hiện gu thẩm mỹ của gia đình bạn. Dưới đây là những gợi ý hữu ích để lựa chọn và trang trí nội thất phòng ăn theo từng diện tích và phong cách.</p>
            <h2>5 mẫu nội thất phòng ăn đẹp được đánh giá cao</h2>
            <p class="p">Khi lựa chọn nội thất phòng ăn, nhiều người thường có thói quen tìm kiếm những mẫu đẹp mắt được các chuyên gia đánh giá cao. Tham khảo từ chất liệu, đường nét tinh tế và màu sắc hài hòa… là những điểm ưu tiên để lựa chọn sản phẩm cho phòng ăn. Đảm bảo rằng những sản phẩm, phụ kiện này sẽ phù hợp và linh hoạt với từng diện tích. Ngoài ra còn giúp thể hiện sự tinh tế trong thiết kế của gia chủ.</p>
            <h2>Giải pháp phòng ăn đẹp theo từng xu hướng</h2>
            <h3>Phòng ăn theo phong cách Bắc Âu:</h3>
            <p style="text-align: center;">
                    <img alt="" src="https://jysk.vn/Data/Sites/1/media/blog-news/t8-2023/mau-thiet-ke-phong-an/body-8---bistrup.png"/></p>
                    <p style="text-align: center;">Top mẫu thiết kế phòng ăn Bắc Âu được yêu thích.</p>
            <p class="p"> Với những ai yêu thích phong cách đơn giản, nhẹ nhàng, hơi “hướng nội” và muốn có góc riêng. Phong cách Bắc Âu chính là lựa chọn phù hợp. Lấy cảm hứng từ vùng đất lạnh giá nên phong cách này thường mang đến sự thoải mái và sự ấm áp cho không gian phòng ăn. Nếu biết cách ứng dụng phù hợp với các tông màu nhẹ nhàng như: trắng, xám và xanh để tạo nên vẻ đẹp tinh tế. Đồng thời, kết hợp thêm đèn trang trí và các chi tiết gỗ để góp phần không gian thêm ấn tượng.</p>
            <h3>Phòng ăn theo phong cách Hiện Đại:</h3>
            <p style="text-align: center;">
                <img alt="" src="https://jysk.vn/Data/Sites/1/media/blog-news/t8-2023/mau-thiet-ke-phong-an/362011786-ghe-ban-an-jonstrup-jysk-3.jpg"/></p>
                <p style="text-align: center;">Người trẻ thường lựa chọn phong cách phòng ăn hiện đại.</p>
            <p class="p">Đây chắc hẳn là lựa chọn của đại đa số người trẻ. Bởi phong cách này thường nhấn mạnh vào đường nét đơn giản, chất liệu hiện đại và tông màu tươi sáng. Ví dụ có thể chọn bộ bàn ghế phòng ăn với thiết kế đường cong tinh tế. Pha trộn với màu sắc táo bạo như: đỏ, vàng, hoặc xanh lam để tạo điểm nhấn thú vị.</p>
            <h3>Phòng ăn theo phong cách Đương Đại:</h3>
            <p style="text-align: center;">
                <img alt="" src="https://jysk.vn/Data/Sites/1/media/blog-news/t8-2023/mau-thiet-ke-phong-an/phong-cach-duong-dai.jpg"/></p>
                <p style="text-align: center;">Nếu muốn tạo dấu ấn riêng, hãy thử trang trí phòng ăn phong cách đương đại.</p>
            <p class="p">Nếu bạn là người có cá tính và luôn muốn tạo một cá tính riêng. Tại sao không thử phong cách kết hợp giữa truyền thống và hiện đại. Hãy thử lựa chọn những bộ bàn ghế có vẻ đơn giản nhưng vẫn mang lại sự ấm cúng. Sử dụng các tông màu trầm và các vật trang trí handmade để tạo nên sự gần gũi và độc đáo.</p>
<h2>Những điều “cần nhớ” khi thiết kế nội thất phòng ăn</h2>
<h3>Lưu ý khi bắt tay vào thiết kế phòng ăn:</h3>
<h4>Diện tích:</h4>
<p class="p">Dựa vào diện tích phòng, bạn có thể lựa chọn bàn ghế phù hợp để không làm cản trở di chuyển và tạo cảm giác thoải mái.Với những diện tích nhỏ</p>
<h4>Chi tiết trang trí:</h4>
<p class="p">Những chi tiết như đèn trang trí, thảm, và tranh treo tường có thể tạo nên điểm nhấn độc đáo cho phòng ăn của bạn.</p>
<p style="text-align: center;">
                <img alt="" src="https://jysk.vn/Data/Sites/1/media/blog-news/t8-2023/mau-thiet-ke-phong-an/phong-%C4%83n-%C4%91%E1%BA%B9p.jpg"/></p>
                <p style="text-align: center;">Liệu bạn có biết, phòng ăn cũng là nơi giúp tâm trạng trở nên thư thái.</p>
            <p class="p">Với những gợi ý trên, bạn có thể tự tin lựa chọn và trang trí nội thất phòng ăn đẹp mắt, thể hiện gu thẩm mỹ và tạo không gian ấm cúng cho gia đình. Hãy cùng khám phá và thể hiện phong cách của bạn thông qua nội thất phòng ăn!</p>
        <h3>Điều cần nhớ khi chọn nội thất cho phòng ăn:</h3>
        <p class="p">Như bạn thấy, lựa chọn nội thất phòng ăn không chỉ dừng lại ở việc chọn bàn ghế đẹp mắt. Mà còn liên quan đến cách tích hợp với phòng khách và bếp. Tạo nên không gian hài hòa và thuận tiện cho việc sử dụng hằng ngày. Ngay dưới đây, để tạo dấu ấn cho phòng bếp bạn hãy lưu lại những lưu ý sau đây:</p>
        <h3>Lưu ý chọn nội thất cho phòng ăn tích hợp với phòng khách và bếp:</h3>
        <p class="p">Đối với những ngôi nhà có diện tích giới hạn, hoặc những căn hộ studio. Việc lựa chọn nội thất là vô cùng quan trọng. Đặc biệt làm sao để đạt được yếu tố: tiện lợi, tối ưu hóa diện tích.</p>
<p style="text-align: center;">
        <img alt="" src="https://jysk.vn/Data/Sites/1/media/blog-news/t8-2023/mau-thiet-ke-phong-an/su-dung-ban-cafe-cho-phong-an.jpg"/></p>
        <p style="text-align: center;">Có thể sử dụng bàn café, bàn góc kết hợp với ghế sofa giường.</p>
<p style="text-align: center;">
        <img alt="" src="https://jysk.vn/Data/Sites/1/media/blog-news/t8-2023/mau-thiet-ke-phong-an/ban-an-tinh-te.png"/></p>
        <p style="text-align: center;">Hạn chế mua sắm nhiều, chỉ ưu tiên sử dụng sản phẩm có thể ứng dụng trong nhiều không gian.</p>
<p style="text-align: center;">
        <img alt="" src="https://jysk.vn/Data/Sites/1/media/blog-news/t8-2023/mau-thiet-ke-phong-an/m%E1%BA%ABu-b%C3%A0n-%C4%83n-%C4%91a-n%C4%83ng.png"/></p>
        <p style="text-align: center;">Bạn có thể dùng bàn ăn làm bàn làm việc. Sử dụng kết hợp đèn chùm vừa tạo sự ấm cúng, vừa tiết kiệm năng lượng, nhờ dùng đúng việc, đúng chỗ.</p>
 <h3>Lưu ý chọn nội thất cho phòng ăn có diện tích nhỏ:</h3>
 <p class="p">Diện tích nhỏ thường đòi hỏi sự tối ưu hóa về diện tích. Gợi ý chọn những sản phẩm nội thất nhỏ gọn, có tính năng đa dạng như ghế có thể xếp gọn.</p>
 <p style="text-align: center;">
    <img alt="" src="https://jysk.vn/Data/Sites/1/media/blog-news/t8-2023/mau-thiet-ke-phong-an/ban-ghe-mo-rong.png"/></p>
    <p style="text-align: center;">Bàn có thể mở rộng để tiết kiệm không gian mà vẫn đảm bảo sự tiện nghi.         
 <p style="text-align: center;">
        <img alt="" src="https://jysk.vn/Data/Sites/1/media/blog-news/t8-2023/mau-thiet-ke-phong-an/sofa-giuong-da-cong-nang.jpg"/></p>
        <p style="text-align: center;">Sofa giường vừa là nơi nghỉ ngơi, vừa là điểm tựa lưng êm ái mà không cần bạn phải di chuyển.</p>
 <p style="text-align: center;">
        <img alt="" src="https://jysk.vn/Data/Sites/1/media/blog-news/t8-2023/mau-thiet-ke-phong-an/sofa-giuong-paradis-xam-nhat-jysk-4.png"/></p>
        <p style="text-align: center;"> Sử dụng vách ngăn phòng khách bằng gỗ, màn hoặc kính. Vừa tăng tính thẩm mỹ, vừa tạo thêm góc riêng tư.</p>
    <h3>Lưu ý chọn nội thất cho phòng ăn có diện tích lớn:</h3>
    <p class="p">Với những ngôi nhà có diện tích lớn được xem là lợi thế để bạn thoải mái chọn sản phẩm dùng cho nhà bếp. Thậm chí có thể sử dụng những bộ bàn ghế ăn 6- 8 chỗ ngồi, dùng cho những gia đình nhiều thành viên.</p>
<p style="text-align: center;">
    <img alt="" src="https://jysk.vn/Data/Sites/1/media/blog-news/t8-2023/mau-thiet-ke-phong-an/thi%E1%BA%BFt-k%E1%BA%BF-ph%C3%B2ng-%C4%83n-l%E1%BB%9Bn.jpg"/></p>
    <p style="text-align: center;">Bạn có thể tham khảo sản phẩm nội thất có kích thước lớn. Kiểu dáng độc đáo sẽ làm cho không gian trở nên ấn tượng và đầy thú vị.</p>
<p style="text-align: center;">
        <img alt="" src="https://jysk.vn/Data/Sites/1/media/blog-news/t8-2023/mau-thiet-ke-phong-an/118357.jpg"/></p>
        <p style="text-align: center;">Tham khảo một số mẫu bàn ăn 6 -8 ghế có thiết kế tinh tế, dễ ứng dụng trong nhiều phong cách tại Nội Thất Bắc Âu.</p>
<p style="text-align: center;">
        <img alt="" src="https://jysk.vn/Data/Sites/1/media/blog-news/t8-2023/mau-thiet-ke-phong-an/phong-%C4%83n-%C4%91a-n%C4%83ng.jpg"/></p>
        <p style="text-align: center;"> Tham khảo thêm những sản phẩm nội thất đa dụng, có thể ứng dụng cho mọi diện tích phòng ăn.</p>
<p style="text-align: center;">
        <img alt="" src="https://jysk.vn/Data/Sites/1/media/blog-news/t8-2023/mau-thiet-ke-phong-an/ghe-banh-dep.jpg"/></p>
        <p style="text-align: center;">Ghế bành là lựa chọn thú vị để tạo góc nghỉ ngơi cho gia chủ. Vừa xem phim, vừa nghỉ ngơi sau khi ăn.</p>
        <p class="p">Nhớ rằng, tiêu chí lựa chọn nội thất phòng ăn không chỉ đơn giản là về thẩm mỹ. Mà còn liên quan đến tính ứng dụng, tiện nghi và tạo cảm giác thoải mái cho mọi người trong gia đình. Hy vọng với những gợi ý trên sẽ giúp bạn lựa chọn nội thất phòng ăn đẹp mắt và ứng dụng theo từng diện tích ngôi nhà.</p>
        <p class="p">Nếu bạn đang tìm cho mình những sản phẩm nội thất chất lượng mang phong cách Bắc Âu hiện đại để trang trí phòng ăn, Nội Thất Bắc Âu là một sự lựa chọn lý tưởng cho bạn. Nội Thất Bắc Âuchuỗi bán lẻ quốc tế từ Đan Mạch chuyên cung cấp các giải pháp trang trí và nội thất phong cách Scandinavian giúp bạn thiết kế ngôi nhà đơn giản nhưng tinh tế, hiện đại, thiết kế phòng khách đẹp.</p>

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