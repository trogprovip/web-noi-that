<?php
session_start();
// Kiểm tra xem người dùng đã đăng nhập chưa
if (!isset($_SESSION['user_id'])) {
    // Nếu chưa đăng nhập, chuyển hướng đến trang đăng nhập
    header("Location: DNhap.php");
    exit();
}
// Kiểm tra xem giỏ hàng có dữ liệu không
if (!isset($_SESSION['cart']) || count($_SESSION['cart']) == 0) {
    header("Location: cart.php");
    exit();
}

// Lấy thông tin giỏ hàng từ session
$cart = $_SESSION['cart'];
$total = 0; // Tổng tiền tất cả sản phẩm

// Tính tổng tiền giỏ hàng
foreach ($cart as $item) {
    $subtotal = $item['price'] * $item['quantity']; // Tổng tiền từng sản phẩm
    $total += $subtotal; // Cộng dồn vào tổng tiền
}

// Xử lý form thanh toán
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $province = $_POST['province'];
    $district = $_POST['district'];

    // Kiểm tra dữ liệu hợp lệ
    if (empty($name) || empty($phone) || empty($email) || empty($address) || empty($province) || empty($district)) {
        $error = "Vui lòng điền đầy đủ thông tin!";
    } else {
        // Lưu thông tin giao hàng vào session
        $_SESSION['shipping_info'] = [
            'name' => $name,
            'phone' => $phone,
            'email' => $email,
            'address' => $address,
            'province' => $province,
            'district' => $district
        ];

        // Xóa giỏ hàng sau khi thanh toán thành công
        unset($_SESSION['cart']);

        // Chuyển hướng đến trang cảm ơn
        header("Location: thank_you.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh Toán</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 20px;
        }

        .container {
            display: flex;
            justify-content: space-between; /* Đặt các phần tử theo chiều ngang */
            gap: 30px;
            max-width: 1200px;
            margin: 0 auto;
            background: #fff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }

        .left-col, .right-col {
            width: 48%; /* Chia đều cho hai cột */
        }

        h2 {
            color: #333;
            font-size: 24px;
            margin-bottom: 15px;
        }

        label {
            display: block;
            font-weight: bold;
            margin: 10px 0 5px;
        }

        input, select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .order-summary {
            margin-top: 30px;
            background: #f9f9f9;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .order-summary h3 {
            font-size: 20px;
            margin-bottom: 10px;
        }

        .order-summary .item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 10px;
            padding: 10px 0;
        }

        .order-summary .item img {
            width: 50px; /* Kích thước hình ảnh sản phẩm */
            height: 50px;
            object-fit: cover;
            margin-right: 15px;
        }

        .order-summary .total {
            font-weight: bold;
            font-size: 18px;
            text-align: right;
        }

        .error {
            color: red;
            font-size: 14px;
            margin-bottom: 10px;
        }

        .submit-btn {
            width: 100%;
            background-color: #4CAF50;
            color: #fff;
            font-size: 16px;
            border: none;
            cursor: pointer;
            padding: 10px 20px;
            border-radius: 4px;
        }

        .submit-btn:hover {
            background-color: #45a049;
        }

    </style>
</head>
<body>

<div class="container">
    <!-- Cột bên trái (Thông tin thanh toán) -->
    <div class="left-col">
        <h2>Thông Tin Thanh Toán</h2>

        <?php if (!empty($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>

        <form action="" method="POST">
            <label for="name">Họ tên*</label>
            <input type="text" id="name" name="name" required placeholder="Nhập họ tên">

            <label for="phone">Số điện thoại*</label>
            <input type="tel" id="phone" name="phone" required placeholder="Nhập số điện thoại">

            <label for="email">Email*</label>
            <input type="email" id="email" name="email" required placeholder="Nhập email">

            <label for="province">Tỉnh/Thành phố*</label>
            <select id="province" name="province" required>
                <option value="">Chọn Tỉnh/Thành phố</option>
            </select>

            <label for="district">Quận/Huyện*</label>
            <select id="district" name="district" required>
                <option value="">Chọn Quận/Huyện</option>
            </select>

            <label for="address">Địa chỉ*</label>
            <input type="text" id="address" name="address" required placeholder="Nhập địa chỉ cụ thể">

            <button type="submit" class="submit-btn">Hoàn tất thanh toán</button>
        </form>
    </div>

    <!-- Cột bên phải (Tóm tắt đơn hàng) -->
    <div class="right-col">
        <div class="order-summary">
            <h3>Tóm Tắt Đơn Hàng</h3>
            <?php foreach ($cart as $item): ?>
                <div class="item">
                    <!-- Hiển thị hình ảnh sản phẩm -->
                    <img src="<?php echo $item['image']; ?>" alt="Hình ảnh sản phẩm">
                    <span class="name-price"><?php echo htmlspecialchars($item['name']); ?> x <?php echo $item['quantity'] ; ?></span>
                    <span class="price"> :<?php echo number_format($item['price'] * $item['quantity'], 0, ',', '.') . 'đ'; ?></span>
                </div>
            <?php endforeach; ?>
            <div class="total">Tổng cộng: <?php echo number_format($total, 0, ',', '.') . ' đ'; ?></div>
        </div>
    </div>
</div>

<script>
    // JavaScript code for handling provinces and districts
    const locations = {
        "Hà Nội": ["Ba Đình", "Hoàn Kiếm", "Tây Hồ", "Long Biên", "Cầu Giấy", "Đống Đa", "Hai Bà Trưng", "Hoàng Mai", "Thanh Xuân", "Sóc Sơn", "Đông Anh", "Gia Lâm", "Nam Từ Liêm", "Thanh Trì", "Bắc Từ Liêm"],
        "Hải Phòng": ["Hồng Bàng", "Lê Chân", "Ngô Quyền", "Hải An", "Kiến An", "Đồ Sơn", "Dương Kinh", "Thuỷ Nguyên", "An Dương", "An Lão"],
        "Quảng Ninh": ["Hạ Long", "Móng Cái", "Cẩm Phả", "Uông Bí", "Vân Đồn", "Hoành Bồ", "Cô Tô", "Đông Triều", "Quảng Yên"],
        "Hải Dương": ["Hải Dương", "Chí Linh", "Nam Sách", "Kinh Môn", "Thanh Hà", "Bình Giang", "Cẩm Giàng", "Gia Lộc"],
        "Thái Bình": ["Thái Bình", "Quỳnh Phụ", "Hưng Hà", "Đông Hưng", "Thái Thụy", "Kiến Xương", "Tiền Hải"],
        "Nam Định": ["Nam Định", "Mỹ Lộc", "Vụ Bản", "Ý Yên", "Nghĩa Hưng", "Nam Trực", "Xuân Trường", "Trực Ninh"],
        "Hà Nam": ["Phủ Lý", "Duy Tiên", "Kim Bảng", "Thanh Liêm", "Bình Lục", "Lý Nhân"],
        "Bắc Ninh": ["Bắc Ninh", "Từ Sơn", "Yên Phong", "Quế Võ", "Tiên Du", "Thuận Thành", "Lương Tài", "Gia Bình"],
        "Vĩnh Phúc": ["Vĩnh Yên", "Phúc Yên", "Lập Thạch", "Tam Dương", "Tam Đảo", "Yên Lạc", "Vĩnh Tường", "Sông Lô"],
        "Phú Thọ": ["Việt Trì", "Phú Thọ", "Lâm Thao", "Thanh Ba", "Thanh Sơn", "Hạ Hoà", "Cẩm Khê", "Đoan Hùng"],
        "Thái Nguyên": ["Thái Nguyên", "Sông Công", "Phổ Yên", "Đại Từ", "Định Hoá", "Võ Nhai", "Phú Bình"],
        "Bắc Giang": ["Bắc Giang", "Lục Ngạn", "Lục Nam", "Sơn Động", "Yên Dũng", "Hiệp Hoà", "Việt Yên", "Tân Yên"],
        "Lạng Sơn": ["Lạng Sơn", "Tràng Định", "Bình Gia", "Văn Lãng", "Cao Lộc", "Lộc Bình", "Chi Lăng"],
        "Cao Bằng": ["Cao Bằng", "Bảo Lạc", "Hà Quảng", "Trùng Khánh", "Nguyên Bình", "Quảng Hoà", "Hoà An"],
        "Hà Giang": ["Hà Giang", "Đồng Văn", "Mèo Vạc", "Yên Minh", "Quản Bạ", "Vị Xuyên", "Bắc Quang"],
        "Tuyên Quang": ["Tuyên Quang", "Sơn Dương", "Hàm Yên", "Yên Sơn", "Chiêm Hóa", "Nà Hang"],
        "Lào Cai": ["Lào Cai", "Sa Pa", "Bát Xát", "Bảo Thắng", "Bảo Yên", "Văn Bàn", "Mường Khương"],
        "Yên Bái": ["Yên Bái", "Nghĩa Lộ", "Trạm Tấu", "Mù Cang Chải", "Văn Chấn", "Yên Bình", "Lục Yên"],
        "Điện Biên": ["Điện Biên Phủ", "Mường Lay", "Mường Nhé", "Mường Chà", "Tủa Chùa", "Tuần Giáo"],
        "Sơn La": ["Sơn La", "Mộc Châu", "Mai Sơn", "Sông Mã", "Thuận Châu", "Yên Châu", "Bắc Yên"],
        "Hòa Bình": ["Hoà Bình", "Mai Châu", "Lương Sơn", "Đà Bắc", "Tân Lạc", "Yên Thủy"]
    };

    const provinceSelect = document.getElementById("province");
    const districtSelect = document.getElementById("district");

    // Populate provinces dropdown
    for (const province in locations) {
        const option = document.createElement("option");
        option.value = province;
        option.textContent = province;
        provinceSelect.appendChild(option);
    }

    provinceSelect.addEventListener("change", () => {
        const selectedProvince = provinceSelect.value;
        districtSelect.innerHTML = '<option value="">Chọn Quận/Huyện</option>';

        if (locations[selectedProvince]) {
            locations[selectedProvince].forEach((district) => {
                const option = document.createElement("option");
                option.value = district;
                option.textContent = district;
                districtSelect.appendChild(option);
            });
        }
    });
</script>

</body>
</html>
