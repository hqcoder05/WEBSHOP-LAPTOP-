<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
require_once __DIR__ . '/../../includes/logic/cart_functions.php';
$user_id = $_SESSION['user_id'];
$cart_items = getCartItems($user_id);
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_cart'])) {
    foreach ($_POST['quantity'] as $product_id => $quantity) {
        $quantity = intval($quantity);
        if ($quantity > 0) {
            $_SESSION['cart'][$product_id] = $quantity;
        } else {
            unset($_SESSION['cart'][$product_id]);
        }
    }
    header('Location: /pages/user/cart.php');
    exit;
}
if (isset($_GET['remove'])) {
    $product_id = intval($_GET['remove']);
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
    header('Location: /pages/user/cart.php');
    exit;
}
require_once __DIR__ . '/../components/header.php';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Giỏ hàng</title>
    <link rel="stylesheet" href="/assets/css/user_carts.css">
    <script src="/assets/js/jquery-3.7.1.js"></script>
    <script src="/assets/js/cart.js"></script>
</head>
<body>
    <div id="wrapper">
        <div class="hero">
            <h2>Giỏ hàng của bạn</h2>
        </div>
        <div id="lowerContainer" class="row">
            <?php if (empty($cart_items)): ?>
                <div class="empty-cart">
                    <p>Giỏ hàng của bạn đang trống.</p>
                    <a href="../user/products.php" class="continue-shopping">Tiếp tục mua sắm</a>
                </div>
            <?php else: ?>
                <form method="post" action="/pages/user/cart.php" id="cartForm">
                    <table class="cart-table">
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="selectAll"></th>
                                <th>Sản phẩm</th>
                                <th>Hình ảnh</th>
                                <th>Giá</th>
                                <th>Số lượng</th>
                                <th>Thành tiền</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $total = 0; ?>
                            <?php foreach ($cart_items as $item): ?>
                                <?php $subtotal = $item['price'] * $item['quantity']; $total += $subtotal; ?>
                                <tr class="product-row" data-id="<?= $item['id'] ?>">
                                    <td><input type="checkbox" class="product-checkbox" name="selected_products[]" value="<?= $item['id'] ?>"></td>
                                    <td><?= htmlspecialchars($item['name']) ?></td>
                                    <td><img src="<?= htmlspecialchars($item['image'] ?? '/assets/images/products/default.jpg') ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="product-img"></td>
                                    <td><?= formatPrice($item['price']) ?></td>
                                    <td><input type="number" name="quantity[<?= $item['id'] ?>]" value="<?= $item['quantity'] ?>" min="1" class="quantity-input"></td>
                                    <td><?= formatPrice($subtotal) ?></td>
                                    <td><a href="/pages/user/cart.php?remove=<?= $item['id'] ?>" class="remove-btn">Xóa</a></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="5" class="text-right"><strong>Tổng cộng:</strong></td>
                                <td colspan="2"><strong><?= formatPrice($total) ?></strong></td>
                            </tr>
                        </tfoot>
                    </table>
                    <div class="cart-actions">
                        <button type="submit" name="update_cart" class="update-btn">Cập nhật giỏ hàng</button>
                        <a href="/pages/user/checkout.php" class="checkout-btn">Thanh toán</a>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </div>
<?php require_once __DIR__ . '/../components/footer.php'; ?>
</body>
</html>