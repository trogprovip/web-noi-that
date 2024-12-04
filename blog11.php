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
                            <h1 class="news-detail-title">Giải pháp: Thiết kế phòng khách kết hợp phòng ngủ tinh tế</h1>
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
                <p class="p">Thiết kế phòng ngủ kết hợp phòng khách không phải là ý tưởng xa lạ. Tuy nhiên, đây lại là giải pháp không gian tuyệt vời dành cho những căn hộ chung cư, nhà nhỏ. Thiết kế tích hợp “2in1” mang lại tiện ích, thẩm mỹ cùng sự đa dạng phong cách.</p>
                <p style="text-align: center;">
                    <img alt="" src="https://jysk.vn/Data/Sites/1/media/blog-news/t7-2023/phong-khach-va-phong-ngu-ket-hop/ph%C3%B2ng-kh%C3%A1ch-ph%C3%B2ng-ng%E1%BB%A7-k%E1%BA%BFt-h%E1%BB%A3p.png"/></p>
                    <p class="p">Một số người cho rằng phòng ngủ là nơi riêng tư và quan trọng nhất của một ngôi nhà. Thậm chí, theo quan niệm Á Đông phòng ngủ còn được xem là “kho cất giữ tài lộc trong gia đình”. Đó cũng là lý giải vì sao thiết kế phòng ngủ thường được nhiều gia chủ yêu cầu cao về hình thức. Sự hợp lý trong thẩm mỹ lẫn phong thủy.</p>
                <p style="text-align: center;">
                    <img alt="" src="https://jysk.vn/Data/Sites/1/media/blog-news/t7-2023/phong-khach-va-phong-ngu-ket-hop/ket-hop-phong-khach-v%C3%A0-phong-ngu2.jpg"/></p>
                    <p style="text-align: center;">Với những căn hộ nhỏ từ 35m², việc thiết kế phòng ngủ kết hợp phòng khách được xem là giải pháp hợp lý.</p>
                <p style="text-align: center;">
                     <img alt="" src="https://jysk.vn/Data/Sites/1/media/blog-news/t7-2023/phong-khach-va-phong-ngu-ket-hop/ket-hop-phong-khach-v%C3%A0-phong-ngu1.jpg"/></p>
                    <p style="text-align: center;">Ứng dụng phong cách Scandinavian vào thiết kế đáp ứng 3 yếu tố: tiện nghi, đơn giản, đẹp mắt.</p>
                <h2>Lợi ích khi thiết kế phòng ngủ kết hợp phòng khách</h2>
                <p style="text-align: center;">
                    <img alt="" src="https://jysk.vn/Data/Sites/1/media/blog-news/t7-2023/phong-khach-va-phong-ngu-ket-hop/ket-hop-phong-khach-v%C3%A0-phong-ngu3.jpg"/></p>
                    <p style="text-align: center;">Không chỉ đơn thuần là xu hướng mà việc thiết kế phòng ngủ kết hợp phòng khách ngày càng nhiều người lựa chọn hơn.</p>
                <p style="text-align: center;">
                    <img alt="" src="https://jysk.vn/Data/Sites/1/media/blog-news/t7-2023/phong-khach-va-phong-ngu-ket-hop/ket-hop-phong-khach-v%C3%A0-phong-ngu4.jpg"/></p>
                    <p style="text-align: center;">Đặc biệt với những không gian nhỏ hẹp hoặc không có gian phòng phân tách.</p>
                    <p class="p"><strong>- Mang đến sự đồng nhất:</strong>giúp ngôi nhà trở nên hài hòa và dịu mắt hơn.</p>
                    <p class="p"><strong>- Khai thác tối đa không gian:</strong>Không gian sử dụng thoải mái, tiện ích nhân đôi. Vừa có nơi tiếp khách ấn tượng và đẹp mắt, vừa có khu vực riêng tư để nghỉ ngơi.</p>
                    <p class="p"><strong>- Gia tăng sự kết nối:</strong> thuận tiện cho sinh hoạt thông qua thiết kế thông dụng tại các căn hộ studio, căn hộ chung cư nhỏ cho 1-2 người sử dụng.</p>
                    <p class="p"><strong>- Tăng nét độc đáo và hiện đại:</strong>Kiểu không gian kết hợp đang là xu hướng của thiết kế nội thất hiện đại. Qua đó thể hiện cá tính khi thiết kế không gian riêng.</p>
                <h2>Giải pháp thiết kế phòng ngủ kết hợp phòng khách quen thuộc</h2>
                <h3>Thiết kế phòng khách liền phòng ngủ bằng gác lửng:</h3>
                <p style="text-align: center;">
                    <img alt="" src="https://jysk.vn/Data/Sites/1/media/blog-news/t7-2023/phong-khach-va-phong-ngu-ket-hop/ket-hop-phong-khach-v%C3%A0-phong-ngu5.jpg"/></p>
                    <p style="text-align: center;">Đầu tiên, hãy tận dụng không gian chiều cao của căn hộ diện tích nhỏ.</p>
                    <p class="p">Thiết kế gác lửng phù hợp sẽ đem đến diện mạo mới cho không gian. Ngoài đó, bạn lại có thêm tiện ích sử dụng để trang trí những góc yêu thích. Lưu ý khi lựa chọn nội thất để thiết kế phòng ngủ kết hợp phòng khách</p>
                <h3>Chọn kích thước vật dụng phù hợp với diện tích phòng:</h3>
                <p class="p">Với thiết kế “2in1” thì điều yêu cầu đầu tiên đó là tiết chế vật dụng. Điều này giúp giảm áp lực cho không gian, giảm sự gò bó và cảm giác tù túng. Việc cân đối đồ nội thất theo diện tích và kích thước là vô cùng quan trọng. Do đó, để dễ dàng sở hữu được không gian như ý thì bạn nên ưu tiên những sản phẩm có vẻ ngoài tối giản; nhỏ gọn và tránh chi tiết cầu kỳ vướng mắc</p>
                <h3>Chọn màu sắc nội thất phù hợp khi kết hợp phòng ngủ và phòng khách:</h3>
                <p class="p">Đối với thiết kế phòng khách và phòng ngủ liền nhau, nên lựa chọn tone màu sơn cùng đồ nội thất tươi sáng. Điều này giúp mở rộng không gian hiệu quả. Bạn có thể tham khảo những màu sắc như: trắng, xanh nhạt, vàng kem… Đây được xem là những màu mang đến cảm giác trang nhã; dễ ứng dụng và quan trọng là mang đến sự thoải mái.</p>
                <p style="text-align: center;">
                    <img alt="" src="https://jysk.vn/Data/Sites/1/media/blog-news/t7-2023/phong-khach-va-phong-ngu-ket-hop/ket-hop-phong-khach-v%C3%A0-phong-ngu6.jpg"/></p>
                    <p style="text-align: center;">   Nếu muốn nhấn vào sự mộc mạc, ấm cúng thì màu gỗ tự nhiên là một gợi ý tuyệt vời.</p>
                <h3>Thiết kế phòng khách liền phòng ngủ bằng vách ngăn:</h3>
                <p style="text-align: center;">
                    <img alt="" src="https://jysk.vn/Data/Sites/1/media/blog-news/t7-2023/phong-khach-va-phong-ngu-ket-hop/ket-hop-phong-khach-v%C3%A0-phong-ngu7.jpg"/></p>
                    <p style="text-align: center;">Giải pháp này phù hợp với những căn hộ vừa và nhỏ, nhưng vẫn không khiến không gian ngột ngạt.</p>
                <p class="p"> Thông qua việc sử dụng vách ngăn giúp tận dụng tối đa diện tích nhà. Tùy chất liệu và dòng sản phẩm vách ngăn bằng cửa hay bằng kính. Điều mang đến lợi ích trong việc tách riêng không gian giữa nơi tiếp khách và phòng ngủ. Đảm bảo sự riêng tư và thẩm mỹ đẹp mắt cho căn phòng.</p>
                <h3>Chất liệu vách ngăn dùng kết hợp phòng ngủ và phòng khách</h3>
                <p class="p">Sử dụng vách ngăn để phân chia hai khu vực phòng khách và phòng ngủ được nhiều người dùng. Vách ngăn không chỉ giúp tạo sự riêng tư mà còn mang đến tính ứng dụng đa dạng cho ngôi nhà.</p>
                <p style="text-align: center;">
                    <img alt="" src="https://jysk.vn/Data/Sites/1/media/blog-news/t7-2023/phong-khach-va-phong-ngu-ket-hop/vach-ngan-phong-ngu-64.png"/></p>
                    <p style="text-align: center;">Đồng thời nó cũng giúp che bớt đi ánh sáng chói mắt chiếu vào giường ngủ.</p>
               <p class="p">Tuy nhiên trên thị trường có rất nhiều loại sản phẩm vách ngăn khác nhau. Có thể tham khảo vách ngăn gỗ, kệ, rèm kéo, bình phong bằng gỗ, thạch cao hoặc nhựa để phân chia không gian. MMột ý tưởng hay hơn đó là sử dụng nội thất để tạo nên vách ngăn trong phòng. Sử dụng vật dụng như tủ, kệ tivi, kệ sách, sofa,… cũng là lựa chọn hay ho dùng để phân chia diện tích hiệu quả. Bạn hãy sắp xếp chúng tự nhiên, không gượng ép. Hoặc có thể điều chỉnh linh hoạt, thay đổi cách bài trí theo sở thích để đổi mới cho không gian.</p>
               <p style="text-align: center;">
                    <img alt="" src="https://jysk.vn/Data/Sites/1/media/blog-news/t7-2023/phong-khach-va-phong-ngu-ket-hop/ket-hop-phong-khach-v%C3%A0-phong-ngu8.jpg"/></p>
                    <p style="text-align: center;">Gợi ý dùng Sofa giường để chia tách không gian tiếp khách và giường ngủ.</p>
                <p style="text-align: center;">
                    <img alt="" src="https://jysk.vn/Data/Sites/1/media/blog-news/t7-2023/phong-khach-va-phong-ngu-ket-hop/ket-hop-phong-khach-v%C3%A0-phong-ngu10.jpg"/></p>
                    <p style="text-align: center;">Kệ tivi có tác dụng phân chia không gian phòng hiệu quả.</p>
                <h3>Ưu tiên nội thất đa năng khi kết hợp phòng ngủ và phòng khách</h3>
                <p class="p">Nội thất đa năng được xem là lựa chọn hoàn hảo cho những ai muốn tìm giải pháp kết hợp phòng khách và phòng ngủ. Một món đồ kiêm nhiều chức năng vừa tiết kiệm diện tích nhưng vẫn đảm bảo sự tiện nghi khi sử dụng.</p>
                <p style="text-align: center;">
                    <img alt="" src="https://jysk.vn/Data/Sites/1/media/blog-news/t7-2023/phong-khach-va-phong-ngu-ket-hop/ket-hop-phong-khach-v%C3%A0-phong-ngu11.jpg"/></p>
                    <p style="text-align: center;">Một số sản phẩm đa năng như: giường ngủ tích hợp ngăn kéo, hộc chứa đồ.</p>
                <p style="text-align: center;">
                    <img alt="" src="https://jysk.vn/Data/Sites/1/media/blog-news/t7-2023/phong-khach-va-phong-ngu-ket-hop/ket-hop-phong-khach-v%C3%A0-phong-ngu9.jpg"/></p>
                    <p style="text-align: center;">Hay sofa giường vừa làm ghế ngồi, vừa là giường nghỉ ngơi êm ái.</p>
            <p class="p">Ghế sofa bình thường dùng để ngồi thư giãn, làm nơi tiếp khách. Tuy nhiên sofa giường khi cần có thể mở ra thành một chiếc giường đơn tiện lợi. Điểm cộng khác là kích thước ghế nhỏ gọn giúp bạn có thể đặt tại bất kỳ vị trí nào. Tận dụng sản phẩm đa năng là cách giúp tận dụng không gian và tiết kiệm chi phí hiệu quả.</p>
            <p class="p">Nếu bạn đang tìm cho mình những sản phẩm nội thất dùng để kết hợp phòng khách và phòng ngủ thì Nội Thất Bắc ÂU là một sự lựa chọn lý tưởng cho bạn. JYSK – chuỗi bán lẻ quốc tế từ Đan Mạch chuyên cung cấp các giải pháp trang trí và nội thất phong cách Scandinavian. Đến với Nội Thất Bắc Âu, bạn sẽ dễ dàng tìm thấy các sản phẩm đa dạng thuộc 5 ngành hàng là Nội thất – Trang trí – Gia dụng – Chăn ga gối – Đệm, đèn thả trần, đèn phòng ngủ, lồng đèn, kệ sách, bàn làm việc văn phòng, bàn ghế ăn, sản phẩm ban công sân vườn… có chất lượng đảm bảo cùng mức giá hợp lý. Bên cạnh đó, dịch vụ chăm sóc khách hàng tận tâm, chu đáo đi kèm với chính sách thanh toán tiện lợi sẽ mang đến cho bạn những trải nghiệm mua sắm tiện lợi và an tâm tuyệt đối. </p>
    
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
