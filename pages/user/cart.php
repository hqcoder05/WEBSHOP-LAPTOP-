<?php
session_start();

// Khởi tạo giỏ hàng rỗng nếu chưa có
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Xử lý cập nhật số lượng
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_cart'])) {
    foreach ($_POST['quantity'] as $product_id => $quantity) {
        $quantity = intval($quantity);
        if ($quantity > 0) {
            $_SESSION['cart'][$product_id]['quantity'] = $quantity;
        } else {
            unset($_SESSION['cart'][$product_id]);
        }
    }
    header('Location: cart.php');
    exit;
}

// Xử lý xóa sản phẩm
if (isset($_GET['remove'])) {
    $product_id = intval($_GET['remove']);
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
    header('Location: cart.php');
    exit;
}
?>
<?php include_once ("../components/header.php") ?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Giỏ hàng</title>
    <link rel="stylesheet" href="../../assets/css/user_carts.css">
    <script src="../../assets/js/jquery-3.7.1.js"></script>
    <script src="../../assets/js/cart.js"></script>
</head>
<body>
<div id="wrapper">
    <div class="hero">
        <div class="row">
            <div class="large-12 columns">
                <h2>Giỏ hàng của bạn</h2>
            </div>
        </div>
    </div>

    <div id="lowerContainer" class="row">
        <div id="content" class="large-12 columns">
            <?php if (empty($_SESSION['cart'])): ?>
                <div class="empty-cart">
                    <p>Giỏ hàng của bạn đang trống.</p>
                    <a href="products.php" class="continue-shopping">Tiếp tục mua sắm</a>
                </div>
            <?php else: ?>
                <form method="post" action="cart.php" id="cartForm">
                    <table class="cart-table">
                        <thead>
                        <tr>
                            <th><input type="checkbox" id="selectAll" class="select-all-checkbox"></th>
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
                        <?php foreach ($_SESSION['cart'] as $id => $item): ?>
                            <?php $subtotal = $item['price'] * $item['quantity']; $total += $subtotal; ?>
                            <tr class="product-row" data-id="<?= $id ?>">
                                <td><input type="checkbox" class="product-checkbox" name="selected_products[]" value="<?= $id ?>"></td>
                                <td><?= htmlspecialchars($item['name']) ?></td>
                                <td><img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="product-img"></td>
                                <td><?= number_format($item['price'], 0, ',', '.') ?> VNĐ</td>
                                <td><input type="number" name="quantity[<?= $id ?>]" value="<?= $item['quantity'] ?>" min="1" class="quantity-input"></td>
                                <td><?= number_format($subtotal, 0, ',', '.') ?> VNĐ</td>
                                <td><a href="?remove=<?= $id ?>" class="remove-btn">Xóa</a></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                        <tr>
                            <td colspan="5" class="text-right"><strong>Tổng cộng:</strong></td>
                            <td colspan="2"><strong><?= number_format($total, 0, ',', '.') ?> VNĐ</strong></td>
                        </tr>
                        </tfoot>
                    </table>
                    <div class="cart-actions">
                        <button type="submit" name="update_cart" class="update-btn">Cập nhật giỏ hàng</button>
                        <button type="button" id="checkoutSelected" class="checkout-btn disabled" disabled>Thanh toán đơn hàng</button>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include '../components/footer.php'; ?>
</body>
</html>
