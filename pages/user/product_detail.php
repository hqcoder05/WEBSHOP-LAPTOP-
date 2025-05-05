<?php
global $selectedProduct, $conn;
session_start();
require_once __DIR__ . '/../../includes/logic/product_functions.php';

$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$product = getProductById($product_id);

if (!$product) {
    header('Location: /pages/user/products.php');
    exit();
}

$comments = [];
if ($product_id > 0) {
    $stmt = $conn->prepare("SELECT user_name, comment, created_at FROM comments WHERE product_id = ? ORDER BY created_at DESC");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $comments = $result->fetch_all(MYSQLI_ASSOC);
}

require_once __DIR__ . '/../components/header.php';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($product['name']) ?></title>
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
    <div class="comments-section">
        <h3>Bình luận</h3>
        <?php if (count($comments) > 0): ?>
            <?php foreach ($comments as $cmt): ?>
                <div class="comment-box">
                    <p><strong><?= htmlspecialchars($cmt['user_name']) ?>:</strong></p>
                    <p><?= nl2br(htmlspecialchars($cmt['comment'])) ?></p>
                    <small><?= $cmt['created_at'] ?></small>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Chưa có bình luận nào.</p>
        <?php endif; ?>
        <?php if (isset($_SESSION['user_id'])): ?>
            <form method="post" action="../../includes/logic/add_comment.php" class="comment-form">
                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                <textarea name="comment" placeholder="Viết bình luận..." required></textarea>
                <button type="submit">Gửi</button>
            </form>
        <?php else: ?>
            <p>Bạn cần <a href="../../pages/user/login.php">đăng nhập</a> để bình luận.</p>
        <?php endif; ?>

    </div>

</div>

<?php require_once __DIR__ . '/../components/footer.php'; ?>
</body>
</html>