<?php
include 'db_connect.php'; // Kết nối cơ sở dữ liệu

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT * FROM products WHERE id = '$id'";
    $result = mysqli_query($conn, $query);
    $product = mysqli_fetch_assoc($result);
}

if (isset($_POST['update_product'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category = $_POST['category'];
    $discount = $_POST['discount'];

    // Xử lý tải ảnh lên nếu có
    $image = $product['image']; // Giữ lại ảnh cũ nếu không tải ảnh mới lên
    if ($_FILES["image"]["name"] != "") {
        $target_dir = "uploads/"; // Thư mục để lưu ảnh
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
        $image = $target_file; // Cập nhật ảnh mới
    }

    $update_query = "UPDATE products SET name='$name', description='$description', price='$price', category='$category', image='$image', discount='$discount' WHERE id='$id'";
    mysqli_query($conn, $update_query);
    header("Location: admin_products.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cập nhật sản phẩm</title>
</head>
<style>/* Đặt nền và căn giữa form */
body {
    font-family: Arial, sans-serif;
    background-color: #e6f2ff;
    color: #333;
    display: flex;
    flex-direction: column;
    align-items: center;
    height: 100vh;
    margin: 0;
    padding-top: 20px;
}

/* Tiêu đề chính của trang */
h1 {
    color: #003366;
    font-size: 28px;
    font-weight: bold;
    margin-bottom: 20px;
    text-align: center;
}

/* Khung chứa form */
form {
    width: 400px;
    padding: 30px;
    background-color: #ffffff;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 51, 102, 0.3);
    text-align: center;
}

/* Các trường input */
form input[type="text"],
form input[type="number"],
form input[type="file"],
form textarea {
    width: 100%;
    padding: 12px;
    margin: 10px 0;
    border: 1px solid #003366;
    border-radius: 4px;
    box-sizing: border-box;
    font-size: 16px;
    color: #003366;
}

/* Nút Cập nhật sản phẩm */
form button {
    width: 100%;
    padding: 14px;
    background-color: #003366;
    color: #ffffff;
    border: none;
    border-radius: 4px;
    font-size: 18px;
    font-weight: bold;
    cursor: pointer;
    transition: background-color 0.3s ease;
    margin-top: 10px;
}

form button:hover {
    background-color: #004080;
}

/* Style cho textarea */
form textarea {
    height: 100px;
    resize: vertical;
}
</style>
<body>
    <h1>Cập nhật sản phẩm</h1>
    <form method="POST" action="edit_product.php?id=<?php echo $id; ?>" enctype="multipart/form-data">
        <input type="text" name="name" value="<?php echo $product['name']; ?>" required>
        <textarea name="description" required><?php echo $product['description']; ?></textarea>
        <input type="number" name="price" value="<?php echo $product['price']; ?>" required>
        <input type="text" name="category" value="<?php echo $product['category']; ?>" required>
        <input type="file" name="image">
        <input type="number" name="discount" value="<?php echo $product['discount']; ?>" required>
        <button type="submit" name="update_product">Cập nhật sản phẩm</button>
    </form>
</body>
</html>