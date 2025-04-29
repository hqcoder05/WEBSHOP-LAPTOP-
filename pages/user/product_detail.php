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
<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f9f9f9;
        margin: 0;
        padding: 20px;
    }
    .container {
    max-width: 1200px;
    margin: 40px auto 20px auto; /* Thêm khoảng cách phía trên */
    background-color: #fff;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    border-radius: 12px;
    overflow: hidden;
    padding: 40px;
    display: flex;
    flex-wrap: wrap;
    gap: 30px;
    }
    .row {
        display: flex;
        flex-wrap: wrap;
        width: 100%;
    }
    .col-md-6 {
        flex: 1 1 50%;
        padding: 10px;
    }
    img.img-fluid {
        max-width: 100%;
        height: auto;
        border-radius: 10px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    }
    h2 {
        font-size: 32px;
        color: #007bff;;
        margin-bottom: 10px;
    }
    p {
        font-size: 18px;
        margin: 10px 0;
    }
    strong {
        font-size: 20px;
    }
    input[type="number"] {
        width: 70px;
        padding: 6px 10px;
        font-size: 16px;
        margin-right: 10px;
        border: 2px solid #ccc;
        border-radius: 6px;
        box-shadow: 2px 2px 6px rgba(0, 0, 0, 0.1);
        outline: none;
        transition: all 0.3s ease;
        text-align: center;
        }
    input[type="number"]:focus {
        border-color: #007bff;
        box-shadow: 0 0 8px rgba(0, 123, 255, 0.4);
        background-color: #f0f8ff;
    }
    button {
        padding: 10px 20px;
        background-color: #007bff;
        color: #fff;
        font-size: 16px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }
    button:hover {
        background-color: #0056b3;
    }
    button:disabled {
        background-color: #999;
        cursor: not-allowed;
    }
    @media (max-width: 768px) {
        .col-md-6 {
            flex: 1 1 100%;
        }
    }
</style>
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