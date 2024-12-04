<?php
session_start();

// Hủy session để đăng xuất
session_unset();
session_destroy();

// Quay lại trang trước đó
if (isset($_SERVER['HTTP_REFERER'])) {
    header("Location: " . $_SERVER['HTTP_REFERER']);
} else {
    // Nếu không có thông tin trang trước, quay về trang chủ như là lựa chọn dự phòng
    header("Location: webbh.php");
}
exit();
?>
