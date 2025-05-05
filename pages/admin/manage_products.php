<?php
include '../../pages/components/admin_header.php';
require_once '../../includes/db/database.php';
global $conn;

// Lấy danh sách sản phẩm từ CSDL
$result = $conn->query("SELECT * FROM products");
$products = $result->fetch_all(MYSQLI_ASSOC);

// Xem chi tiết nếu có ID được chọn
$selectedId = $_GET['id'] ?? null;
$selectedProduct = null;
if ($selectedId !== null) {
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param('i', $selectedId);
    $stmt->execute();
    $product_result = $stmt->get_result();
    if ($product_result->num_rows > 0) {
        $selectedProduct = $product_result->fetch_assoc();
    }
}
?>

    <div class="container">
        <h1>Quản lý sản phẩm</h1>

        <?php if ($selectedProduct): ?>
            <div class="product-detail">
                <h2><?= htmlspecialchars($selectedProduct['name']) ?></h2>
                <p><strong>Thương hiệu:</strong> <?= htmlspecialchars($selectedProduct['brand']) ?></p>
                <p><strong>Giá:</strong> <?= number_format($selectedProduct['price'], 0, ',', '.') ?> VND</p>
                <p><strong>Mô tả:</strong> <?= htmlspecialchars($selectedProduct['description']) ?></p>
                <a href="manage_products.php" class="btn">Quay lại</a>
            </div>
        <?php else: ?>
            <a href="add_product.php" class="btn btn-add">+ Thêm sản phẩm mới</a>
            <table>
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Tên sản phẩm</th>
                    <th>Thương hiệu</th>
                    <th>Giá</th>
                    <th>Hành động</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <td><?= $product['id'] ?></td>
                        <td><?= htmlspecialchars($product['name']) ?></td>
                        <td><?= htmlspecialchars($product['brand']) ?></td>
                        <td><?= number_format($product['price'], 0, ',', '.') ?> VND</td>
                        <td>
                            <a href="manage_products.php?id=<?= $product['id'] ?>" class="btn">Xem</a>
                            <a href="edit_product.php?id=<?= $product['id'] ?>" class="btn btn-edit">Sửa</a>
                            <a href="delete_product.php?id=<?= $product['id'] ?>" class="btn btn-delete" onclick="return confirm('Xóa sản phẩm này?')">Xóa</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

<?php
include '../../pages/components/admin_footer.php';
?>