<?php
include 'db_connect.php'; // Kết nối cơ sở dữ liệu

if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $query = "SELECT image FROM products WHERE id = '$id'";
    $result = mysqli_query($conn, $query);
    $product = mysqli_fetch_assoc($result);

    // Xóa ảnh cũ nếu có
    if ($product['image'] != "") {
        unlink($product['image']);
    }

    // Xóa sản phẩm khỏi cơ sở dữ liệu
    $query = "DELETE FROM products WHERE id = '$id'";
    mysqli_query($conn, $query);
    header("Location: admin_products.php");
}
?>
