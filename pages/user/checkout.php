<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
require_once __DIR__ . '/../../includes/logic/product_functions.php';
$cart = $_SESSION['cart'] ?? [];
$total = 0;
$cart_items = [];
foreach ($cart as $id => $qty) {
    $product = getProductById($id);
    if ($product && $product['stock'] >= $qty) {
        $cart_items[] = [
            'product' => $product,
            'quantity' => $qty,
            'subtotal' => $product['price'] * $qty
        ];
        $total += $product['price'] * $qty;
    }
}
require_once __DIR__ . '/../components/header.php';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thanh toán</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <div class="container">
        <h2>Thanh toán</h2>
        <?php if (empty($cart_items)): ?>
            <p>Giỏ hàng của bạn đang trống.</p>
            <a href="/pages/user/products.php">Tiếp tục mua sắm</a>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Sản phẩm</th>
                        <th>Số lượng</th>
                        <th>Giá</th>
                        <th>Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cart_items as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['product']['name']) ?></td>
                            <td><?= $item['quantity'] ?></td>
                            <td><?= formatPrice($item['product']['price']) ?></td>
                            <td><?= formatPrice($item['subtotal']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <p><strong>Tổng tiền:</strong> <?= formatPrice($total) ?></p>
            <form action="/pages/user/checkout_process.php" method="post">
                <button type="submit">Xác nhận đặt hàng</button>
            </form>
        <?php endif; ?>
    </div>
<?php require_once __DIR__ . '/../components/footer.php'; ?>
</body>
</html>