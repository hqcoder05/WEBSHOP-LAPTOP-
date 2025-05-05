<?php
session_start();
require_once __DIR__ . '/../../includes/logic/product_functions.php';

$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$product = getProductById($product_id);

if (!$product) {
    header('Location: /pages/user/products.php');
    exit();
}

require_once __DIR__ . '/../components/header.php';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($product['name']) ?></title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="../../assets/css/product_detail.css">
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-md-6">
            <img src="<?= htmlspecialchars($product['image'] ?? '/assets/images/products/default.jpg') ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="img-fluid">
        </div>
        <div class="col-md-6">
            <h2><?= htmlspecialchars($product['name']) ?></h2>
            <p><strong>Danh mục:</strong> <?= htmlspecialchars($product['category_name']) ?></p>
            <p><strong>Giá:</strong> <?= formatPrice($product['price']) ?></p>
            <p><strong>Tình trạng:</strong> <?= $product['stock'] > 0 ? 'Còn hàng' : 'Hết hàng' ?></p>
            <p><?= htmlspecialchars($product['description'] ?? 'Không có mô tả') ?></p>

            <!-- Nút quay lại -->
            <p style="margin-top: 20px;">
                <a href="../../pages/user/products.php" style="text-decoration: none; color: #007bff;">← Quay lại danh sách sản phẩm</a>
            </p>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../components/footer.php'; ?>
</body>
</html>