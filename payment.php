<?php
session_start();

if (!isset($_SESSION['cart']) || count($_SESSION['cart']) == 0) {
    header("Location: cart.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $note = $_POST['note'] ?? '';

    if (empty($name) || empty($phone) || empty($address)) {
        $error = "Vui lòng điền đầy đủ thông tin giao hàng!";
    } else {
        $_SESSION['shipping_info'] = [
            'name' => $name,
            'phone' => $phone,
            'address' => $address,
            'note' => $note
        ];

        header("Location: confirm_payment.php");
        exit();
    }
}

$cart = $_SESSION['cart'];
$total = 0;
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>THANH TOÁN</title>
    <link rel="stylesheet" href="./assets/thu.css">
    <style>
        
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: space-between;
            padding: 20px;
        }
        .container {
            width: 50%;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            background-color: #f9f9f9;
            box-sizing: border-box;
        }
        h2 {
            text-align: left;
            font-size: 18px;
            color: #333;
        }
        label {
            display: block;
            margin: 15px 0 5px;
        }
        input, select, textarea {
         
            padding: 10px;
            margin: 8px 0 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            width: 100%;
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        input[type="radio" i] {
             background-color: initial;
             cursor: default;
             appearance: auto;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        .payment-method {
           
            padding: 10px;
            margin: 8px 0 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
}


        textarea {
            resize: vertical;
            height: 100px;
        }



    /*  
        .section {
            margin-bottom: 20px;
            
        }
        */




        .payment-method, .shipping-method {
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 10px;
        }
        .payment-method img, .shipping-method img {
            width: 30px;
            vertical-align: middle;
            margin-right: 10px;
        }
        .order-summary {
            height: 75%;
            width: 49%;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            background-color: #f9f9f9;
            box-sizing: border-box;
        }
        .order-summary h2 {
            text-align: left;
            font-size: 18px;
            color: #333;
        }
        .order-summary .item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        .order-summary .item img {
            width: 50px;
            height: 50px;
            margin-right: 10px;
        }
        .order-summary .item-details {
            flex-grow: 1;
        }
        .order-summary .item-price {
            text-align: right;
        }
        .order-summary .total {
            font-weight: bold;
            font-size: 18px;
            text-align: right;
            margin-top: 20px;
        }

        #Information {
            width: 100%;
        }
   #province{
    width: 100%;
   }
   #district{
    width: 100%;
   }
    </style>
</head>
<body>

<div class="container">
    <h2>THÔNG TIN GIAO HÀNG</h2>
    <form id="paymentForm">
    <form action="/submit" method="post">
       
        <label for="name">Họ tên*</label>
        <input type="text" id="Information" name="name" required placeholder="Nhập họ tên">
        

        <label for="phone">Điện thoại*</label>
        <input type="tel" id="Information" name="phone" required placeholder="Nhập số điện thoại">

        <label for="email">Email*</label>
        <input type="email" id="Information" name="email" required placeholder="Nhập email">

        <label for="province">Tỉnh/Thành phố*</label>
        <select id="province" name="province" required>
            <option value="">Chọn Tỉnh/Thành phố</option>
        </select>

        <label for="district">Quận/Huyện*</label>
        <select id="district" name="district" required>
            <option value="">Chọn Quận/Huyện</option>
        </select>

        <label for="address">Địa chỉ*</label>
        <input type="text" id="Information" name="address" required placeholder="Số nhà, tên đường, phường , quận, thành phố ">

        <label for="notes">Ghi chú</label>
        <textarea id="Information" name="notes" placeholder="Nhập ghi chú (nếu có)"></textarea>
    
        <!-- Xuất hóa đơn 
        <div class="section">
            <label><input type="checkbox" id="invoice" name="invoice"> Xuất hóa đơn</label>
        </div>
    -->



        <!-- Phương thức vận chuyển -->
        <div class="section">
            <h2>PHƯƠNG THỨC VẬN CHUYỂN</h2>
            <div class="shipping-method">
                <label><input type="radio" name="shipping" value="Giao hàng tận nơi" checked> Giao hàng tận nơi</label>
                <img src="./hinhanh/ship-1.png" alt="">
            </div>
        </div>

        <!-- Phương thức thanh toán -->
        <div class="section">
            <h2>PHƯƠNG THỨC THANH TOÁN</h2>
            <div class="payment-method">
                <label><input type="radio" name="payment" value="COD" checked>
                    <img src="./hinhanh/tienmat.png" alt="COD">
                    Thanh toán tiền mặt khi nhận hàng (COD)
                </label>
            </div>
            <div class="payment-method">
                <label><input type="radio" name="payment" value="Alepay">
                    <img src="./hinhanh/online.png" alt="Alepay">
                    Thanh toán online qua cổng thanh toán VNPAY
                </label>
            </div>
        </div>
        <input type="submit" value="Gửi">
    </form>
</div>

<div class="order-summary">
    <h2>Tóm Tắt Đơn Hàng</h2>
    <div class="item">
        <img src="./hinhanh/th.jfif" alt="Bàn góc">
        <div class="item-details">
            <p>Sản Phẩm Nội Thất</p>
        </div>
        <div class="item-price">0 đ</div>
    </div>
    <div class="item">
        <div class="item-details">
            <p>Phí vận chuyển</p>
        </div>
        <div class="item-price"> 0 đ</div>
    </div>
    <div class="item">
        <div class="item-details">
            <p>Phí lắp đặt</p>
        </div>
        <div class="item-price">0 đ</div>
    </div>
    <div class="item">
        <div class="item-details">
            <p>Giảm giá</p>
        </div>
        <div class="item-price">0 đ</div>
    </div>
    <div class="total">
        Tổng cộng: 0 đ
    </div>
    
    
</div>
<script>
    // Add this to test redirection in the console
    document.getElementById('paymentForm').addEventListener('submit', function(event) {
        event.preventDefault();  // Prevent default form submission
        console.log("Redirecting to the success page...");  // Debugging message
        window.location.href = 'demothanhtoan.html';  // Redirect to the success page
    });
    // List of provinces and districts
const provinces = {
    "Hà Nội": ["Ba Đình", "Đống Đa", "Cầu Giấy", "Tây Hồ"],
    "TP Hồ Chí Minh": ["Quận 1", "Quận 3", "Quận 5", "Quận 7"],
    "Đà Nẵng": ["Hải Châu", "Thanh Khê", "Sơn Trà", "Ngũ Hành Sơn"],
    "Hải Phòng": ["Hồng Bàng", "Lê Chân", "Ngô Quyền", "Kiến An"]
};

// Populate province dropdown
const provinceSelect = document.getElementById('province');
const districtSelect = document.getElementById('district');

for (let province in provinces) {
    let option = document.createElement('option');
    option.value = province;
    option.textContent = province;
    provinceSelect.appendChild(option);
}

// Update districts when a province is selected
provinceSelect.addEventListener('change', function() {
    districtSelect.innerHTML = '<option value="">Chọn Quận/Huyện</option>'; // Reset district options
    const selectedProvince = provinceSelect.value;
    if (provinces[selectedProvince]) {
        provinces[selectedProvince].forEach(function(district) {
            let option = document.createElement('option');
            option.value = district;
            option.textContent = district;
            districtSelect.appendChild(option);
        });
    }
});

// Form submit redirection
document.getElementById('paymentForm').addEventListener('submit', function(event) {
    event.preventDefault();  // Prevent default form submission
    window.location.href = 'demothanhtoan.html';  // Redirect to the success page
});
</script>
</body>
</html>
