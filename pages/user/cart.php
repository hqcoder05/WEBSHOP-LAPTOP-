<?php
include_once '../../includes/logic/cart_functions.php';

// Xử lý các yêu cầu từ người dùng
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    session_start();
    $action = $_POST['action'] ?? null;

    switch ($action) {
        case 'add':
            $productId = $_POST['product_id'] ?? null;
            $quantity = $_POST['quantity'] ?? 1;
            echo json_encode(['message' => addToCart($productId, $quantity)]);
            break;

        case 'update':
            $productId = $_POST['product_id'] ?? null;
            $quantity = $_POST['quantity'] ?? 0;
            echo json_encode(['message' => updateCart($productId, $quantity)]);
            break;

        case 'remove':
            $productId = $_POST['product_id'] ?? null;
            echo json_encode(['message' => removeFromCart($productId)]);
            break;

        case 'save':
            $userId = $_SESSION['user_id'] ?? null;
            echo json_encode(['message' => saveCartToDatabase($userId)]);
            break;

        default:
            echo json_encode(['error' => 'Hành động không hợp lệ.']);
    }
} else {
    echo json_encode(['error' => 'Chỉ hỗ trợ phương thức POST.']);
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giỏ hàng</title>
    <link rel="stylesheet" href="../../assets/css/user_carts.css">
    <script src="../../assets/js/jquery-3.7.1.js"></script>
</head>
<body>
<div class="container">
    <h2>Giỏ hàng</h2>
    <?php if (empty($cart_items)): ?>
        <div class="empty-cart">
            <p>Giỏ hàng của bạn đang trống.</p>
            <a href="../user/products.php" class="continue-shopping">Tiếp tục mua sắm</a>
        </div>
    <?php else: ?>
        <table class="cart-table">
            <thead>
            <tr>
                <th>Hình ảnh</th>
                <th>Tên sản phẩm</th>
                <th>Giá</th>
                <th>Số lượng</th>
                <th>Tổng</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($cart_items as $item): ?>
                <tr>
                    <td><img src="<?= htmlspecialchars($item['image'] ?? '/Laptop_Shop - Copy/assets/images/products/default.jpg') ?>" alt="<?= htmlspecialchars($item['name']) ?>" style="width: 50px;"></td>
                    <td><?= htmlspecialchars($item['name']) ?></td>
                    <td><?= formatPrice($item['price']) ?></td>
                    <td>
                        <input type="number" min="1" value="<?= $item['quantity'] ?>" class="cart-quantity-input" data-product-id="<?= $item['id'] ?>">
                    </td>
                    <td><?= formatPrice($item['price'] * $item['quantity']) ?></td>
                    <td><a href="../../includes/logic/remove_from_cart.php?id=<?= $item['id'] ?>" class="btn btn-danger btn-sm">Xóa</a></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <div class="cart-actions">
            <a href="../user/products.php" class="btn btn-outline-primary">Tiếp tục mua sắm</a>
            <a href="../user/checkout.php" class="btn btn-primary">Thanh toán</a>
        </div>
    <?php endif; ?>
</div>

<script>
    $(document).ready(function() {
        $('.cart-quantity-input').on('change', function() {
            var productId = $(this).data('product-id');
            var quantity = parseInt($(this).val());
            if (isNaN(quantity) || quantity < 1) {
                alert('Vui lòng nhập số lượng hợp lệ');
                $(this).val(1);
                return;
            }
            $.ajax({
                url: '../../includes/logic/update_cart.php',
                type: 'POST',
                data: { product_id: productId, quantity: quantity },
                dataType: 'json',
                success: function(data) {
                    alert(data.message);
                    if (data.success) {
                        location.reload();
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', {
                        status: status,
                        error: error,
                        statusCode: xhr.status,
                        responseText: xhr.responseText
                    });
                    alert('Đã có lỗi xảy ra khi cập nhật giỏ hàng');
                }
            });
        });
    });
</script>

<?php require_once __DIR__ . '/../components/footer.php'; ?>
</body>
</html>
