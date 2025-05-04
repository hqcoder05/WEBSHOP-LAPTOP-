<?php
session_start();
require_once __DIR__ . '/../../includes/db/database.php';
require_once __DIR__ . '/../../includes/logic/product_functions.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = 'Vui lòng đăng nhập để đặt hàng.';
    header('Location: /pages/user/login.php?redirect=/pages/user/checkout.php');
    exit;
}

// Kiểm tra dữ liệu từ products.php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && 
    isset($_POST['product_id'], $_POST['quantity'], $_POST['csrf_token']) &&
    $_POST['csrf_token'] === $_SESSION['csrf_token']) {
    $product_id = (int)$_POST['product_id'];
    $quantity = (int)$_POST['quantity'];
    
    // Kiểm tra sản phẩm
    $product = getProductById($product_id);
    if (!$product) {
        $_SESSION['error'] = 'Sản phẩm không tồn tại.';
        header('Location: /pages/user/products.php');
        exit;
    }
    
    // Kiểm tra tồn kho
    if ($quantity < 1 || $quantity > $product['stock']) {
        $_SESSION['error'] = 'Số lượng không hợp lệ hoặc sản phẩm đã hết hàng.';
        header('Location: /pages/user/products.php');
        exit;
    }
    
    // Lưu thông tin đơn hàng tạm vào session
    $_SESSION['checkout'] = [
        'product_id' => $product_id,
        'quantity' => $quantity,
        'total_price' => $product['price'] * $quantity
    ];
    
    // Ghi log để debug
    error_log("Checkout khởi tạo: Product ID=$product_id, Quantity=$quantity, Total Price={$_SESSION['checkout']['total_price']}");
} elseif (!isset($_SESSION['checkout'])) {
    $_SESSION['error'] = 'Không tìm thấy thông tin đơn hàng.';
    header('Location: /pages/user/products.php');
    exit;
}

// Lấy thông tin sản phẩm để hiển thị
$product = getProductById($_SESSION['checkout']['product_id']);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh toán</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <?php include __DIR__ . '/../components/header.php'; ?>

    <main>
        <h1>Thanh toán</h1>

        <?php if (isset($_SESSION['error'])): ?>
            <p class="error"><?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></p>
        <?php endif; ?>

        <div class="checkout-summary">
            <h2>Tóm tắt đơn hàng</h2>
            <p>Sản phẩm: <?php echo htmlspecialchars($product['name']); ?></p>
            <p>Số lượng: <?php echo $_SESSION['checkout']['quantity']; ?></p>
            <p>Tổng giá: <?php echo number_format($_SESSION['checkout']['total_price'], 2); ?> VNĐ</p>
        </div>

        <form action="/pages/user/checkout_process.php" method="POST">
            <h2>Thông tin giao hàng</h2>
            <div>
                <label for="full_name">Họ tên:</label>
                <input type="text" id="full_name" name="full_name" required>
            </div>
            <div>
                <label for="phone">Số điện thoại:</label>
                <input type="tel" id="phone" name="phone" pattern="[0-9]{10,11}" required>
            </div>
            <div>
                <label for="address">Địa chỉ:</label>
                <input type="text" id="address" name="address" required>
            </div>
            <div>
                <label for="note">Ghi chú (tùy chọn):</label>
                <textarea id="note" name="note"></textarea>
            </div>
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <button type="submit">Xác nhận đặt hàng</button>
        </form>
    </main>

    <?php include __DIR__ . '/../components/footer.php'; ?>
</body>
</html>