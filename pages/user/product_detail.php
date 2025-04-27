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
    <link rel="stylesheet" href="/assets/css/style.css">
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
                <?php if ($product['stock'] > 0): ?>
                    <form action="/pages/user/cart.php" method="post">
                        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                        <input type="number" name="quantity" value="1" min="1" max="<?= $product['stock'] ?>">
                        <button type="submit" name="add_to_cart">Thêm vào giỏ hàng</button>
                    </form>
                <?php else: ?>
                    <button disabled>Hết hàng</button>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php require_once __DIR__ . '/../components/footer.php'; ?>
</body>
</html>