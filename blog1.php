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
                            <h1 class="news-detail-title">Những mẫu bàn ăn đẹp, hiện đại và sang trọng cho gia đình bạn</h1>
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
                <p>Trang trí phòng ăn không những giúp cho gia đình bạn có những bữa cơm đoàn viên thân mật mà còn mang đến cảm giác thoải mái và ấm cúng cho gian bếp. Cùng Nội Thất Bắc Âu tham khảo top những mẫu bàn ăn đẹp, sang trọng để giúp bạn chọn lựa những mẫu bàn ăn phù hợp nhất cho phòng bếp của mình.</p>
                
                <p style="text-align: center;">
                    <img alt="" src="./anh/mau-ban-an-dep.jpg" />
                </p>
                
                <p style="text-align: center;">Bộ bàn ăn đẹp sang trọng cho phòng ăn gia đình bạn</p>
                
                
                <h2>Có những mẫu bàn ăn đẹp hiện đại nào?</h2>
                <p>Tham khảo hình ảnh top những mẫu bàn ăn hiện đại được thiết kế bằng nhiều chất liệu khác nhau, mang đến cho bạn một không gian phòng ăn ấm cúng nhưng không kém phần sang trọng.</p>
                <p style="text-align: center;">
                    <img alt="" src="./anh/mau-ban-an-dep-bac-au.png" />
                </p>
                <p style="text-align: center;">Mẫu bàn ăn đẹp, hiện đại, xu hướng Bắc Âu mới đang được nhiều người lựa chọn</p>
                <p style="text-align: center;">
                    <img alt="" src="./anh/mau-ban-an-dep-bac-au-hien-dai.png" />
                </p>
                <p style="text-align: center;">Sắp xếp bàn ăn hợp lý, phù hợp với không gian phòng ăn</p>
                <p style="text-align: center;">
                    <img alt="" src="./anh/mau-ban-an-dep-bac-au-hien-dai-moi.png" />
                </p>
                <p style="text-align: center;">Bàn ghế ăn gỗ, chân kim loại mang đến sự chắc chắn và an toàn</p>
                
                <p style="text-align: center;">
                    <img alt="" src="./anh/mau-ban-an-hien-dai.png" />
                </p>
                <p style="text-align: center;">Bộ bàn ăn sang trọng với tone màu đen chủ đạo</p>
                <p style="text-align: center;">
                    <img alt="" src="./anh/mau-ban-an-moi.png" />
                </p>
                <p style="text-align: center;">Thiết kế bộ bàn ăn phù hợp mọi không gian</p>
                <h3>Mẫu bàn ăn đẹp được làm từ chất liệu nào?</h3>
                <h3>Bàn đá nhân tạo</h3>
                <p>Với thiết kế đa dạng, dễ lau chùi, mẫu bàn ăn làm từ đá nhân tạo là một trong những loại sản phẩm được ưa chuộng. Khác với đá tự nhiên, bàn ăn đẹp làm từ đá nhân tạo có kiểu dáng và màu sắc đa dạng lại rất dễ vệ sinh. Đặc biệt, các sản phẩm bàn ăn đá nhân tạo làm từ đá thạch anh còn có khả năng kháng khuẩn cao.</p>
                <p style="text-align: center;">
                    <img alt="" src="./anh/mau-ban-an-bac-au-moi.png" />
                </p>
                <p style="text-align: center;">Bàn ăn đá nhân tạo kết hợp chân gỗ tạo điểm nhấn cho phòng ăn gia đình bạn</p>
                 <h3>Mẫu bàn ăn đẹp bằng gỗ tự nhiên </h3>
                 <p>Các loại bàn ăn gỗ tự nhiên hiện nay thường được làm bằng loại gỗ công nghiệp, thân thiện với môi trường. Với đa dạng mẫu mã, kiểu dáng, bàn ăn đẹp bằng gỗ tự nhiên độ bền cao, giá cả hợp lý, mang đến cho bạn không gian ấm cúng trong mỗi bữa ăn gia đình.</p>
                 <p style="text-align: center;">
                    <img alt="" src="./anh/mau-ban-an-go.png" />
                </p>
                <p style="text-align: center;">Mẫu bàn ăn đẹp bằng gỗ tự nhiên kèm 4 ghế sang trọng</p>
                <h3>Mẫu bàn ăn đẹp bằng kính</h3>
                <p>Bàn ăn đẹp làm từ chất liệu kính có thiết kế kết hợp giữa 2 yếu tố tinh tế và tối giản phù hợp với không gian nhà bếp phong cách hiện đại. Ngoài ra, bạn có thể kết hợp mặt kính với mặt bàn bằng chất liệu gỗ, inox hoặc đá,… Bên cạnh đó, các bàn ghế ăn bàn kính có ưu điểm là không thấm nước, dễ dàng vệ sinh và độ bền cao.</p>
                <h2>Mẫu bàn ăn đẹp, ấn tượng và phù hợp với mọi không gian</h2>
                <h3>Bàn ăn mở rộng VEDDE gỗ công nghiệp, màu sồi đậm | R80xD80/160xC76cm</h3>
                <ol>
                    <li><strong>Thiết Kế:</strong> Bàn ăn GADESKOV có thiết kế đơn giản nhưng tinh tế và phù hợp với không gian phòng bếp hiện đại. Mặt bàn hình chữ nhật có thể mở rộng R80xD80/160xC76cm diện tích rộng, phù hợp với những gia đình có từ 3 đến 6 thành viên. Phần chân bàn có bọc lớp cao su giúp hạn chế tình trạng trầy xước sàn nhà.</li>
                    <li><strong>Chất Liệu:</strong> Mẫu bàn ăn GADESKOV từ chất liệu gỗ công nghiệp veneer sồi và kim loại. Mặt bàn có thiết kế hình chữ nhật bằng gỗ sồi công nghiệp veneer, dễ vệ sinh và giúp tạo điểm nhấn cho không gian. Chân bàn được làm bằng kim loại phủ sơn đen bền bỉ, không bị rỉ hay bong tróc sơn trong quá trình sử dụng.</li>
                    <li><strong>Kích Thước:</strong> Mẫu bàn ghế ăn GADESKOV có kích thước vừa phải với chiều cao 76cm, chiều rộng 80cm, và chiều dài 80cm có thể kéo dài đến 160cm. Với kích thước này, bàn ăn GADESKOV là sản phẩm nội thất phù hợp với hầu hết các không gian bếp của những gia đình hiện đại.</li>
                  </ol>
                  <p style="text-align: center;">
                    <img alt="" src="./anh/3659477-ban-an-mo-rong-vedde-jysk-11.jpg" />
                </p>
                <p style="text-align: center;">Bộ bàn ăn GADESKOV - Lựa chọn tinh tế cho không gian phòng ăn</p>
                <h3>Bộ bàn ăn JEGIND gỗ sồi màu trắng</h3>
                <ol>
                    <li><strong>Thiết Kế:</strong> Bàn ăn JEGIND có hình chữ nhật và được thiết kế đơn giản nhưng không kém phần tinh tế, tô điểm thêm nét hiện đại cho không gian sống. Nhờ vào thiết kế thanh mảnh, tinh giản nên sẽ tiết kiệm được diện tích cho không gian phòng ăn.</li>
                    <li><strong>Chất Liệu:</strong> Mặt bàn được làm từ gỗ MDF sản xuất theo dây chuyền tiên tiến, hiện đại, bên ngoài phủ lớp sơn chống mối mọt, chống cong vênh và bền bỉ theo thời gian. Chân bàn bằng gỗ có kết cấu chắc chắn và đảm bảo độ bền khi sử dụng lâu dài. Ngoài ra, các phần góc cạnh bàn đều được thiết kế cong đảm bảo an toàn cho gia đình có trẻ nhỏ. </li>
                    <li><strong>Kích Thước:</strong> Bàn ăn JEGIND có kích thước chuẩn R80xD130xC75cm nên bạn có thể đặt được nhiều vật dụng. Với kích thước tiêu chuẩn, bàn ăn Jegind còn có thể sử dụng như 1 chiếc bàn học. Đặc điểm nổi bật là sản phẩm dễ dàng tháo lắp và di chuyển bởi kết cấu có thể tháo rời, gắn kết bằng hệ thống phụ kiện đi kèm.
                    </li>
                  </ol>
                  <p style="text-align: center;">
                    <img alt="" src="./anh/36700278614-bo-ban-an-jegind-jysk.jpg" />
                </p>
                <p style="text-align: center;">Trọn bộ bàn ăn đẹp JEGIND của thương hiệu Nội Thất Bắc Âu</p>
                <h3>Bộ bàn ăn TERSLEV gỗ công nghiệp xám, chân kim loại sơn tĩnh điện </h3>
                <ol>
                    <li><strong>Thiết Kế:</strong>  Bộ sản phẩm sở hữu vẻ đẹp tinh tế từ lối thiết kế tối giản, hiện đại trong tone màu trung tính nhã nhặn. Kiểu dáng vuông vức, đường nét tinh giản đặc trưng theo phong cách Bắc Âu góp phần tô điểm cho không gian.</li>
                    <li><strong>Chất Liệu:</strong> Mặt bàn được làm từ gỗ công nghiệp chất lượng cao nên rất an toàn cho sức khỏe người sử dụng. Bề mặt melamine dễ dàng vệ sinh và bảo quản
              <br>          Ngoài ra, kết cấu chân bàn ăn Terslev được làm từ kim loại sơn tĩnh điện nên rất chắc chắn và bền. Sản phẩm dễ dàng tháo lắp và di chuyển bởi kết cấu có thể tháo rời, gắn kết bằng hệ thống phụ kiện đi kèm. Bàn ăn TERSLEV sử dụng gỗ đạt chứng nhận FSC - sản phẩm được làm từ gỗ có nguồn gốc rõ ràng, không ảnh hưởng đến giá trị bảo vệ môi trường.  </li>
                    <li><strong>Kích Thước:</strong> Bàn ăn TERSLEV có kích thước chuẩn R80xD140xC75cm nên phù hợp với các căn hộ nhỏ hay gia đình ít người. Kích thước nhỏ giúp tối ưu hóa không gian sống mà vẫn đảm bảo công năng và trải nghiệm. </li>
                  </ol>
                  <p style="text-align: center;">
                    <img alt="" src="./anh/1s369043.jpg" />
                </p>
                <p style="text-align: center;">Với kích thước tiêu chuẩn, bàn ăn Terslev còn có thể sử dụng như 1 chiếc bàn học</p>
                <h3>Ghế bàn ăn HYGUM chân kim loại/PU, màu cognac/đen</h3>
                <ol>
                    <li><strong>Thiết Kế:</strong> Với thiết kế độc đáo và màu sắc nhã nhặn, ghế ăn HYGUM có thể bố trí hài hòa trong nhiều không gian khác nhau, tăng tính thẩm mỹ cho ngôi nhà của bạn</li>
                    <li><strong>Chất Liệu:</strong> Với lớp đệm ngồi bọc vải polyester êm ái và rất dễ vệ sinh. Chân ghế bằng kim loại sơn tĩnh điện nên dễ vệ sinh, khỏe và bền bỉ. Phần tựa lưng ghế bàn ăn HYGUM , kết hợp màu đen của chân ghế tạo nên 1 tổng thể hài hòa. Vừa là điểm nhấn cho không gian, vừa tạo cảm hứng mỗi khi gia đình quây quần bên nhau. </li>
                    <li><strong>Kích Thước:</strong> Ghế sở hữu kích thước ghế tiêu chuẩn R54xS59xC86cm, có thể sử dụng làm ghế làm việc, ghế phụ phòng khách.</li>
                  </ol>
                  <p style="text-align: center;">
                    <img alt="" src="./anh/ghe-an.jpg" />
                </p>
                <p style="text-align: center;"> Mẫu ghế ăn HYGUM với thiết kế tinh tế và sang trọng sẽ mang đến cho không gian phòng ăn của bạn thêm hiện đại</p>
                <h2>Bạn đã biết mẹo lựa chọn mẫu bàn ăn phù hợp với không gian?</h2>
                <h3>Lựa chọn mẫu bàn ăn có kích thước phù hợp</h3>
                <p>Để bàn băn không chiếm quá nhiều diện tích, bạn cần lựa chọn những mẫu bộ bàn ăn có kích thước vừa phải, phù hợp với không gian phòng ăn của mình. Dưới đây sẽ là một số gợi ý dành cho bạn:</p>
                <ol>
                    <li><strong> Không gian, diện tích phòng ăn rộng: </strong> Cân nhắc lựa chọn kiểu bàn ăn tròn kết hợp với bộ ghế setup xung quanh. Hoặc bạn có thể chọn kiểu bàn ăn hình chữ nhật  6-8 ghế.</li>
                    <li><strong> Không gian phòng ăn hạn chế: </strong> Bạn nên chọn những mẫu bàn ăn đẹp có kích thước vừa phải, hoạ tiết đơn giản hơn để có thể kết hợp với các loại ghế nhỏ, kích thước vừa phải.</li>
                </ol>
                <p style="text-align: center;">
                    <img alt="" src="./anh/bo-ban-an-bac-au.png" />
                </p>
                <p style="text-align: center;"> Mẫu bộ bàn ăn Bắc Âu hiện đại, trẻ trung, ấn tượng</p>
                <h3>Bộ bàn ghế ăn có sự tương đồng với nhau</h3>
                <p>Việc tạo nên một không gian ấm cúng đóng một vai trò quan trọng trong mỗi bữa ăn. Chính vì vậy, giữa bàn và ghế trong phòng ăn cần có sự đồng bộ với nhau. Bạn nên lựa chọn những mẫu bàn ăn và ghế cùng tone màu, cùng kiểu thiết kế. Bạn có thể tham khảo thêm các bộ bàn ghế đẹp trên Nội Thất Bắc Âu để lựa chọn cho mình nhé!</p>
                <h3>Cân nhắc lựa chọn màu sắc của bộ bàn ghế ăn</h3>
                <p>Để các chi tiết nội thất trong phòng ăn được đồng bộ với nhau, bạn cần cân nhắc lựa chọn mẫu bàn ăn có màu sắc tương đồng với các chi tiết còn lại.</p>
                <ol>
                    <li><strong> Không gian phòng ăn có tone màu nhẹ: </strong> Chọn mẫu bàn ăn màu nâu gỗ, ít hoạ tiết.</li>
                    <li><strong> Không gian phòng ăn hiện đại, tone màu sáng: </strong>  Lựa chọn bộ bàn ăn đẹp có màu gỗ sáng, đường vân nổi bật.</li>
                </ol>
                <p style="text-align: center;">
                    <img alt="" src="./anh/bo-ban-an-bac-au-toi-an-1.png" />
                </p>
                <p style="text-align: center;">Lựa chọn mẫu bàn ăn có màu sắc tương đồng với các chi tiết còn lại</p>
                <h3>Cân nhắc phong cách của nhà ăn</h3>
                <p>Tuỳ thuộc vào kết cấu, thiết kế không gian phòng ăn mà bạn sẽ đưa ra lựa chọn mẫu bàn ăn phù hợp.</p>
                <ol>
                    <li><strong> Phòng ăn có phong cách hiện đại: </strong> Lựa chọn mẫu bàn ăn được làm từ gỗ công nghiệp với nhiều đường nét chấm phá tinh xảo, đơn giản và tinh tế.</li>
                    <li><strong> Phòng ăn với phong cách cổ điển: </strong>  Nên lựa chọn mẫu bàn ăn có thiết kế nhiều hoạ tiết nổi bật, gỗ tự nhiên để nhấn mạnh vẻ đẳng cấp của ngôi nhà</li>
                </ol>
                <h3>Chi phí mua bộ bàn ghế ăn</h3>
                <p>Chi phí cũng là một trong những yêu tố quan trọng bạn cần quan tâm khi tham khảo các mẫu bàn ăn đẹp. Với những bộ bàn ăn mang thương hiệu tên tuổi như Nội Thất Bắc Âu thì thông thường sẽ mang lại sự tin tưởng cho khách hàng. Và đương nhiên, mức giá sẽ phù hợp với chất lượng và vẻ thẩm mỹ mà những sản phẩm này mang lại.</p>
                <h3>Trải nghiệm trực tiếp và đưa ra quyết định mua</h3>
                <p>Đến với các showroom nội thất, bạn sẽ được trải nghiệm trực tiếp các sản phẩm mình đang cần mua, từ đó sẽ giúp bạn dễ dàng đưa ra quyết định hơn. Đồng thời, việc đến trực tiếp các showroom sẽ giúp bạn có cái nhìn tổng quan hơn về thiết kế, màu sắc, giá cả cũng như nhận được tư vấn đến từ đội ngũ bán hàng để giúp bạn có lựa chọn phù hợp nhất dành cho mình.</p>
                <p style="text-align: center;">
                    <img alt="" src="./anh/ban-an-toi-gian-2.jpg" />
                </p>
                <p style="text-align: center;">Đến cửa hàng để lựa chọn mẫu bàn ghế đẹp phù hợp</p>
                <h2>Mua bàn ăn bền đẹp và chất lượng tại Nội Thất Bắc Âu </h2>
                <p style="text-align: center;">
                    <img alt="" src="./anh/ban-an-toi-gian-dep-3.jpg" />
                </p>
                <p style="text-align: center;">Bàn ăn đẹp tại Nội Thất Bắc Âu luôn được khách hàng tin dùng</p>
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