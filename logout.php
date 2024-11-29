<?php
session_start();
session_unset(); // Xóa toàn bộ session
session_destroy(); // Hủy session
header("Location: webbh.php"); // Quay lại trang chính
exit;
?>
