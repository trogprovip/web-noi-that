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
        <main style=" margin-top: 83px; "></main>
            <div class="container">
                <div class="news-detail-section">
                    <div class="row">
                        <div class="col-lg-9">
                            <h1 class="news-detail-title">Sắp xếp phòng khách: cách sắp đặt nội thất để tối ưu hóa không gian</h1>
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
                <p class="p">Phòng khách không chỉ là nơi tiếp đón khách mà còn là trái tim của ngôi nhà của bạn. Theo đó, việc sắp xếp phòng khách một cách thông minh, mang đến lợi ích trong việc tận dụng không gian, đồng thời tạo nên một môi trường chất lượng, đẹp mắt và thoải mái. Hãy cùng chúng tôi “bỏ túi” một số mẹo sắp đặt nội thất giúp tối ưu hóa không gian hiệu quả.</p>
                <p style="text-align: center;">
                    <img alt="" src="https://jysk.vn/Data/Sites/1/media/blog-news/t9-2023/sap-xep-phong-kh%C3%A1ch/s%E1%BA%AFp-x%E1%BA%BFp-ph%C3%B2ng-kh%C3%A1ch.png" /></p>
                    <h2>Sắp xếp phòng khách quan trọng như thế nào?</h2>

                    <p class="p"> Không đơn thuần là không gian sinh hoạt chung của gia đình, phòng khách còn là bộ mặt của căn nhà, tạo nên ấn tượng đối với bất kỳ vị khách nào đến thăm nhà. Tuy nhiên, để có được một không gian vừa hiện đại, gọn gàng lại thẩm mỹ thì không phải là điều mà ai cũng biết.</p>
                    <p class="p"> Nhìn chung, không gian phòng khách thường có kiểu dáng dài và hẹp, nên gây khó khăn cho những gia đình có nhiều thành viên, hoặc yêu cầu tiện ích cao. Ngoài ra, việc sắp xếp phòng khách cũng đòi hỏi gia chủ sự tỉ mỉ trong khâu lựa chọn và gu thẩm mỹ tốt “một chút”, đảm bảo không gian phòng khách được bố trí thông minh và như ý.</p>
                    <p style="text-align: center;">
                    <img alt="" src="https://jysk.vn/Data/Sites/1/media/blog-news/t9-2023/sap-xep-phong-kh%C3%A1ch/sap-xep-phong-khach2.png"/></p>
                    <p style="text-align: center;">Cách tối ưu hóa không gian phòng khách hiệu quả chính là việc thêm nhiều khu vực lưu trữ “ẩn”</p>
                    <h2>Nguyên tắc cần nhớ trước khi sắp xếp phòng khách:</h2>
                    <p class="p">Để có được không gian phòng khách thoáng đãng, gọn gàng thì chỉ dựa vào việc sắp xếp thì chưa đủ để tạo nên sự hài hòa và tiện dụng để sinh hoạt. Để làm tốt này, trước tiên bạn cần lưu ý một vài nguyên tắc cơ bản sau để việc sắp xếp phòng khách trở nên hiệu quả hơn.</p> 
                        <h2>“5 Nên” lưu ý khi sắp xếp phòng khách</h2>
                        <p class="p">- Nên đo đạc diện tích phòng khách để có cái nhìn tổng quan, từ đó xây dựng ý tưởng chọn đồ nội thức phù hợp. Nhằm tạo sự kết nối và cần bằng cho không gian ngôi nhà, gia chủ từ đó cũng cảm thấy thoải mái hơn.</p>
                        <p class="p">- Nên hạn chế sử dụng đồ nội thất lớn đối với không gian nhỏ, điều này chỉ khiến ngôi nhà mang cảm giác bí bách và tù túng.</p>
                        <p class="p">- Nên lưu ý khu vực lối đi, hãy ưu tiên những món đồ nội thất có kích thước vừa phải để giúp cho việc sinh hoạt, dọn dẹp thêm tiện lợi. Hoặc tốt nhất nên để trống khu vực này.</p>
                        <p class="p">- Nên có cái nhìn tổng thể về phong cách thiết kế, đồ trang trí để từ đó lựa chọn đồ nội thất cùng tông màu và kiểu dáng, giúp tổng thể nội thất phòng khách thêm hài hoà hơn.</p>
                        <p class="p">- Nên hạn chế sử dụng những món đồ không phù hợp.</p>
                    <h2>“3 Không” trước khi sắp xếp phòng khách</h2>
                    <p class="p">- Không nên “tham” trong việc trang trí và sử dụng màu sắc rực rỡ, điều này chỉ khiến phòng khách ngột ngạt</p>
                    <p class="p">- Không nên sử dụng những vật trang trí sắc bén (dù món đồ có thiết kế độc đáo), bởi điều này có thể gây nguy hiểm đến gia đình có trẻ nhỏ.</p>
                    <p class="p">- Không nên mua sắm theo tính “nhất thời” vừa tốn tiền lại không sử dụng được, thay vì vậy bạn nên xem xét về nhu cầu sử dụng có cần thiết hay không.</p>
                    <h1 class="news-detail-title">Hướng dẫn cách sắp xếp phòng khách chi tiết dành cho bạn</h1>
                    <h2>Hướng dẫn lựa chọn nội thất “không kén” không gian</h2>
                    <h3>Ghế sofa:</h3>
                    <p style="text-align: center;">
                    <img alt="" src="https://jysk.vn/Data/Sites/1/media/blog-news/t9-2023/sap-xep-phong-kh%C3%A1ch/sap-xep-phong-khach3.png"/></p>
                    <p style="text-align:center;">Ghế sofa thường là món đồ quan trọng nhất trong phòng khách.</p>
                    <p class="p">Hãy lựa chọn một mẫu ghế có kích cỡ vừa vặn với không gian phòng khách của bạn và phù hợp với phong cách thiết kế. Trên thị trường có rất nhiều sản phẩm ghế sofa như: sofa giường, sofa góc, sofa 2-3 chỗ ngồi với nhiều chất liệu khác nhau, giá tiền cũng đa dạng.</p>
                    <h3>Ghế bành:</h3>
                    <p class="p">Với những không gian phòng khách nhỏ bạn có thể thay thế ghế Sofa bằng ghế bành (ghế thư giãn) để có nơi nghỉ ngơi, giải trí và tăng sự thoải mái cho phòng khách. Ngoài ra, giá của những chiếc ghế bành cũng không quá cao nên phù hợp với số đông người dùng, đặc biệt là những gia đình có người lớn tuổi.</p>
                    <p style="text-align: center;">
                    <img alt="" src="https://jysk.vn/Data/Sites/1/media/blog-news/t9-2023/sap-xep-phong-kh%C3%A1ch/sap-xep-phong-khach14.png"/></p>
                    <p style="text-align:center;">Thay thế ghế Sofa bằng ghế bành (ghế thư giãn) giúp tiết kiệm không gian.</p>
                    <h3>Bàn cafe:</h3>
                    <p style="text-align: center;">
                        <img alt="" src="https://jysk.vn/Data/Sites/1/media/blog-news/t9-2023/sap-xep-phong-kh%C3%A1ch/sap-xep-phong-khach5.png"/></p>
                        <p style="text-align:center;">Bàn cafe không chỉ có tính tiện dụng mà còn là một phần quan trọng trong thẩm mỹ của phòng khách.</p>
                        <p class="p">Chọn một bàn có kích thước và kiểu dáng phù hợp với không gian. Ngoài ra, bàn cafe còn được xem là một chiếc bàn đa năng khi không hề kén không gian sử dụng.</p>
                    <h3>Đồ trang trí:</h3>
                    <p style="text-align: center;">
                        <img alt="" src="https://jysk.vn/Data/Sites/1/media/blog-news/t9-2023/sap-xep-phong-kh%C3%A1ch/sap-xep-phong-khach6.png"/></p>
                        <p style="text-align:center;">Để làm đẹp không gian phòng khách thì khâu chọn đồ trang trí cũng cực kỳ quan trọng</p>
                        <p class="p">Một số vật dụng mà bạn có thể tham khảo để tân trang phòng khách như: tranh trang trí, gương, kệ sách để tạo điểm nhấn thẩm mỹ cho phòng khách. Đặc biệt đừng quên sử dụng cây xanh hoặc trang trí bằng cây nhân tạo để tăng thêm mảng xanh sinh động cho phòng khách nhé!</p>
                    <h2>Hướng dẫn sắp xếp phòng khách chi tiết</h2>
                    <p style="text-align: center;">
                        <img alt="" src="https://jysk.vn/Data/Sites/1/media/blog-news/t9-2023/sap-xep-phong-kh%C3%A1ch/paradis-(3).png"/></p>
                        <p style="text-align:center;">Sắp xếp phòng khách đòi hỏi sự cân nhắc tỉ mỉ để không gian trở nên hài hòa và thoải mái.</p>
                        <p class="p">Nội thất là yếu tố quan trọng của ngôi nhà, vì nó không chỉ giúp tạo ra một không gian sống tiện nghi và đẹp mắt, mà còn có thể tối ưu hóa không gian sống của bạn. Tuy nhiên, ngoài việc chọn nội thất phù hợp với diện tích thì cách sắp xếp phòng khách cũng quan trọng không kém.</p>
                    <h2>Ý tưởng tận dụng không gian có sẵn</h2>
                    <p class="p">Tận dụng không gian tường</p> 
                    <p style="text-align: center;">
                        <img alt="" src="https://jysk.vn/Data/Sites/1/media/blog-news/t9-2023/sap-xep-phong-kh%C3%A1ch/sap-xep-phong-khach7.png"/></p>
                        <p style="text-align:center;">Không gian tường là một cách tuyệt vời để tối đa hóa không gian trong nhà bạn.</p>
                    <p style="text-align: center;">
                        <img alt="" src="https://jysk.vn/Data/Sites/1/media/blog-news/t9-2023/sap-xep-phong-kh%C3%A1ch/sap-xep-phong-khach8.png"/></p>
                        <p style="text-align:center;">Sử dụng góc nhỏ để đặt một bộ ghế đọc và đèn để tạo nên một không gian thư giãn.</p>
                    <p style="text-align: center;">
                        <img alt="" src="https://jysk.vn/Data/Sites/1/media/blog-news/t9-2023/sap-xep-phong-kh%C3%A1ch/sap-xep-phong-khach9.png"/></p>
                        <p style="text-align:center;">Thật lãng phí nếu để trống những bức tường không sử dụng, khiến ngôi nhà trông buồn tẻ và nhàm chán.</p>
                    <p class="p">Tận dụng góc nhỏ hoặc góc chữ L</p>
                    <p class="p">- Tận dụng tầm nhìn:</p>
                    <p style="text-align: center;">
                        <img alt="" src="https://jysk.vn/Data/Sites/1/media/blog-news/t9-2023/sap-xep-phong-kh%C3%A1ch/sap-xep-phong-khach10.png"/></p>
                        <p style="text-align:center;">Nếu phòng của bạn có tầm nhìn đẹp ra ngoài, hãy đặt ghế sofa hoặc bàn cafe ở vị trí tận hưởng tầm nhìn này.</p>
                    <p class="p">Lựa chọn vị trí hợp lý cho ghế sofa, bàn trà và các vật trang trí khác để tạo sự hài hòa trong phòng. Điều này cũng sẽ tạo điểm nhấn thú vị trong không gian của bạn.</p>    <p style="text-align: center;">
                        <p class="p">- Tạo điểm nhấn và cân nhắc thẩm mỹ:</p>
                        <p style="text-align: center;">
                        <img alt="" src="https://jysk.vn/Data/Sites/1/media/blog-news/t9-2023/sap-xep-phong-kh%C3%A1ch/sap-xep-phong-khach11.png"/></p>
                        <p style="text-align:center;">Nên ưu tiên sử dụng đồ trang trí để tạo điểm nhấn màu sắc hoặc chất liệu.</p>
                        <p style="text-align: center;">
                            <img alt="" src="https://jysk.vn/Data/Sites/1/media/blog-news/t9-2023/sap-xep-phong-kh%C3%A1ch/sap-xep-phong-khach12.png"/></p>
                            <p style="text-align:center;">Lựa chọn màu sắc phù hợp với phong cách thiết kế phòng khách và tạo cảm giác ấm áp và thân thiện.</p>
                        <p class="p">- Lưu ý về an toàn và sự thoải mái</p>
                        <p class="p">Cuối cùng, đảm bảo rằng mọi chi tiết trong phòng khách của bạn an toàn và thoải mái để sử dụng. Điều này bao gồm việc bố trí nội thất sao cho không gian không gây cản trở và đảm bảo rằng mọi đồ đạc và vật trang trí được đặt ở vị trí an toàn. Nhớ rằng sắp xếp nội thất trong phòng khách là một quá trình sáng tạo và thường đòi hỏi sự kiên nhẫn. Tuy nhiên, kết quả sẽ là một không gian sống tinh tế và thoải mái mà bạn và gia đình sẽ thích thú. Nếu bạn đang tìm kiếm những món đồ nội thất chất lượng phong cách Bắc Âu hiện đại thì JYSK là một sự lựa chọn lý tưởng cho bạn. Nội Thất Bắc Âu chuỗi bán lẻ quốc tế từ Đan Mạch chuyên cung cấp các giải pháp trang trí và nội thất phong cách Scandinavian, giúp bạn sắp xếp phòng khách một cách đơn giản nhưng vẫn đảm bảo sự tinh tế. </p>

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




