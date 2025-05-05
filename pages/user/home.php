<?php
session_start();
require_once __DIR__ . '/../../includes/logic/product_functions.php';
$featured_products = getFeaturedProducts(4);
require_once __DIR__ . '/../components/header.php';
?>
<html>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Trang chủ</title>
<link href="../../assets/css/home-styles.css" rel="stylesheet">

<body>
    <div class="container">
        <h2>Chào mừng, <?= htmlspecialchars($_SESSION['username'] ?? 'Quý khách') ?>!</h2>
        <h3>Sản phẩm nổi bật</h3>
        <div class="row">
            <?php foreach ($featured_products as $product): ?>
                <div class="col-md-3">
                    <div class="card">
                        <img src="<?= htmlspecialchars($product['image'] ?? '/assets/images/products/default.jpg') ?>" class="card-img-top" alt="<?= htmlspecialchars($product['name']) ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
                            <p class="card-text"><?= formatPrice($product['price']) ?></p>
                            <a href="../user/product_detail.php?id=<?= $product['id'] ?>" class="btn btn-primary">Xem chi tiết</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php require_once __DIR__ . '/../components/footer.php'; ?>
</body>
</html>