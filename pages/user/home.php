<?php
session_start();
require_once __DIR__ . '/../../includes/logic/product_functions.php';
$featured_products = getFeaturedProducts(4);
require_once __DIR__ . '/../components/header.php';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang Chủ</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            min-height: 100vh; /* Đảm bảo body chiếm toàn bộ chiều cao viewport */
        }
        .home-container {
            flex: 1; /* Chiếm không gian còn lại để đẩy footer xuống */
            min-height: 80vh; /* Đảm bảo khoảng cách tối thiểu trước footer */
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }
        .home-container h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }
        .home-container h3 {
            text-align: center;
            margin: 30px 0 20px;
            color: #333;
        }
        .row {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }
        .col-md-3 {
            flex: 0 0 23%; /* Tương đương 4 cột trên màn hình lớn */
            max-width: 23%;
            box-sizing: border-box;
        }
        .card {
            background-color: #f4f4f4;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .card-img-top {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        .card-body {
            padding: 15px;
            text-align: center;
        }
        .card-title {
            font-size: 1.2em;
            margin: 0 0 10px;
            color: #333;
        }
        .card-text {
            color: #666;
            margin: 0 0 15px;
        }
        .btn-primary {
            background-color: #007bff;
            color: white;
            padding: 8px 16px;
            text-decoration: none;
            border-radius: 4px;
            display: inline-block;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        footer {
            background-color: #f8f8f8;
            padding: 10px;
            text-align: center;
            width: 100%;
        }
        @media (max-width: 768px) {
            .col-md-3 {
                flex: 0 0 48%;
                max-width: 48%;
            }
        }
        @media (max-width: 576px) {
            .col-md-3 {
                flex: 0 0 100%;
                max-width: 100%;
            }
        }
    </style>
</head>
<body>
<div class="home-container">
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
                        <a href="../user/product_detail.php?id=<?= $product['id'] ?>" class="btn-primary">Xem chi tiết</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php
require_once __DIR__ . '/../components/footer.php';
?>
</body>
</html>