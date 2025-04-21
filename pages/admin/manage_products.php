<?php
require_once '../../includes/logic/product_functions.php';

$products = getAllProducts();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['create'])) {
        $product = new Product(
            null,
            $_POST['name'],
            $_POST['description'],
            $_POST['price'],
            $_POST['stock']
        );
        createProduct($product);
    } elseif (isset($_POST['update'])) {
        $product = new Product(
            $_POST['id'],
            $_POST['name'],
            $_POST['description'],
            $_POST['price'],
            $_POST['stock']
        );
        updateProduct($product);
    } elseif (isset($_POST['delete'])) {
        deleteProduct($_POST['id']);
    }

    // Refresh page
    header("Location: manage_products.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Sản phẩm</title>
</head>
<body>
    <h1>Quản lý Sản phẩm</h1>

    <h2>Thêm sản phẩm</h2>
    <form method="POST">
        <input type="text" name="name" placeholder="Tên sản phẩm" required>
        <textarea name="description" placeholder="Mô tả sản phẩm" required></textarea>
        <input type="number" step="0.01" name="price" placeholder="Giá" required>
        <input type="number" name="stock" placeholder="Số lượng" required>
        <button type="submit" name="create">Thêm</button>
    </form>

    <h2>Danh sách sản phẩm</h2>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên</th>
                <th>Mô tả</th>
                <th>Giá</th>
                <th>Số lượng</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $product) : ?>
                <tr>
                    <td><?php echo $product['id']; ?></td>
                    <td><?php echo $product['name']; ?></td>
                    <td><?php echo $product['description']; ?></td>
                    <td><?php echo $product['price']; ?></td>
                    <td><?php echo $product['stock']; ?></td>
                    <td>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
                            <input type="text" name="name" value="<?php echo $product['name']; ?>" required>
                            <textarea name="description" required><?php echo $product['description']; ?></textarea>
                            <input type="number" step="0.01" name="price" value="<?php echo $product['price']; ?>" required>
                            <input type="number" name="stock" value="<?php echo $product['stock']; ?>" required>
                            <button type="submit" name="update">Cập nhật</button>
                        </form>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
                            <button type="submit" name="delete">Xóa</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>