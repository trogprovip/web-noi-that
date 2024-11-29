<?php
// Bật hiển thị lỗi để dễ dàng kiểm tra lỗi trong quá trình phát triển
error_reporting(E_ALL);
ini_set('display_errors', 1);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Kiểm tra nếu người dùng gửi form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email']; // Nhận email từ form

    // Kết nối cơ sở dữ liệu
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "Ql_bh"; // Đổi thành tên cơ sở dữ liệu của bạn

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Kiểm tra kết nối
    if ($conn->connect_error) {
        die("Kết nối thất bại: " . $conn->connect_error);
    }

    // Kiểm tra email có tồn tại trong cơ sở dữ liệu không
    $sql = "SELECT * FROM khachhang WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Tạo mã xác nhận ngẫu nhiên
        $verification_code = bin2hex(random_bytes(6)); // 12 ký tự hex

        // Lưu mã xác nhận vào cơ sở dữ liệu
        $sql_update = "UPDATE khachhang SET verification_code = ? WHERE email = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("ss", $verification_code, $email);
        $stmt_update->execute();

        // Sử dụng PHPMailer để gửi email
        require 'vendor/autoload.php'; // Đảm bảo PHPMailer được cài đặt qua Composer

        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Máy chủ SMTP của Gmail
            $mail->SMTPAuth = true;         // Bật xác thực SMTP
            $mail->Username = 'duongtrong1306@gmail.com'; // Địa chỉ email Gmail của bạn
            $mail->Password = 'ymna hhbz wpfl kffk';   // Mật khẩu ứng dụng (App Password)
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Bảo mật TLS
            $mail->Port = 587;              // Cổng SMTP cho TLS
            

            // Người gửi và người nhận
            $mail->setFrom('youremail@gmail.com', 'Hệ Thống Đặt Lại Mật Khẩu'); // Email người gửi
            $mail->addAddress($email); // Email người nhận

            // Nội dung email
            $mail->isHTML(true); // Sử dụng định dạng HTML
            $mail->Subject = 'Mã xác nhận đặt lại mật khẩu';
            $mail->Body    = "<p>Xin chào,</p>
                              <p>Mã xác nhận của bạn để đặt lại mật khẩu là:</p>
                              <h2>$verification_code</h2>
                              <p>Vui lòng sử dụng mã này để đặt lại mật khẩu. Mã xác nhận có hiệu lực trong 15 phút.</p>";
            $mail->AltBody = "Mã xác nhận của bạn để đặt lại mật khẩu là: $verification_code";

            $mail->send();

            // Thông báo thành công và chuyển hướng đến trang nhập mã xác nhận
            echo "Mã xác nhận đã được gửi đến email của bạn.";
            header("Location: nhap_ma_xac_nhan.php?email=$email");
            exit;
        } catch (Exception $e) {
            echo "Không thể gửi email. Lỗi: {$mail->ErrorInfo}";
        }
    } else {
        echo "Email không tồn tại trong hệ thống.";
    }

    $stmt->close();
    $conn->close();
}
?>
