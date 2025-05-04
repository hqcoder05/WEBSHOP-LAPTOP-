<?php
session_start();
require_once __DIR__ . '/../../includes/db/database.php';
require_once __DIR__ . '/../../includes/logic/product_functions.php';
require_once __DIR__ . '/../../includes/logic/category_functions.php';

// Tạo CSRF token
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Ghi log truy cập
error_log("Truy cập products.php: " . date('Y-m-d H:i:s'));

// Lấy danh sách danh mục và sản phẩm
$categories = getAllCategories();
$products = getAllProducts();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sản phẩm</title>
    <link rel="stylesheet" href="/assets/css/user_products.css">
</head>
<body>
    <?php include __DIR__ . '/../components/header.php'; ?>

    <main>
        <h1>Danh sách sản phẩm</h1>

        <?php if (isset($_SESSION['error'])): ?>
            <p class="error"><?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></p>
        <?php endif; ?>
        <?php if (isset($_SESSION['success'])): ?>
            <p class="success"><?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?></p>
        <?php endif; ?>

        <div class="products">
            <?php foreach ($products as $product): ?>
                <div class="product">
                    <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" onerror="this.src='/assets/images/products/default.jpg'">
                    <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                    <p><?php echo htmlspecialchars($product['description']); ?></p>
                    <p>Giá: <?php echo number_format($product['price'], 2); ?> VNĐ</p>
                    <p>Kho: <?php echo $product['stock']; ?></p>
                    <form action="/pages/user/checkout.php" method="POST">
                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                        <input type="number" name="quantity" value="1" min="1" max="<?php echo $product['stock']; ?>" required>
                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                        <button type="submit">Đặt hàng ngay</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    </main>

    <?php include __DIR__ . '/../components/footer.php'; ?>
    <script src="/assets/js/user_products.js"></script>
</body>
</html>