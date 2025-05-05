<?php
session_start();
require_once __DIR__ . '/../../includes/db/database.php';
global $conn;

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Lấy dữ liệu sản phẩm từ URL
$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$quantity = isset($_GET['quantity']) ? (int)$_GET['quantity'] : 1;

if ($product_id <= 0 || $quantity <= 0) {
    die("Không có thông tin sản phẩm.");
}

// Lấy thông tin sản phẩm từ DB
$stmt = $conn->prepare("SELECT id, name, price, image FROM products WHERE id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if (!$product) {
    die("Sản phẩm không tồn tại.");
}

$total_price = $product['price'] * $quantity;

// Xử lý đặt hàng
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $note = $_POST['note'];

    // Ghi vào bảng orders
    $stmt = $conn->prepare("INSERT INTO orders (user_id, total_price) VALUES (?, ?)");
    $stmt->bind_param('id', $user_id, $total_price);
    $stmt->execute();
    $order_id = $conn->insert_id;

    // Ghi vào bảng order_details
    $stmt = $conn->prepare("INSERT INTO order_details (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
    $stmt->bind_param('iiid', $order_id, $product['id'], $quantity, $product['price']);
    $stmt->execute();

    // Ghi địa chỉ giao hàng
    $stmt = $conn->prepare("INSERT INTO order_addresses (order_id, full_name, phone, address, note) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param('issss', $order_id, $name, $phone, $address, $note);
    $stmt->execute();

    // Chuyển hướng
    header('Location: orders.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thanh toán</title>
    <link href="../../assets/css/user_checkout.css" rel="stylesheet"/>
</head>
<body>
<?php require_once __DIR__ . '/../components/header.php'; ?>
<div class="container mt-5">
    <h2 class="text-center mb-4">Xác nhận đặt hàng</h2>
    <div class="row mb-4">
        <div class="col-md-4">
            <img src="../../assets/images/<?= htmlspecialchars($product['image']) ?>" width="100%" />
        </div>
        <div class="col-md-8">
            <h4><?= htmlspecialchars($product['name']) ?></h4>
            <p>Giá: <?= number_format($product['price'], 0, ',', '.') ?> VND</p>
            <p>Số lượng: <?= $quantity ?></p>
            <p><strong>Tổng tiền: <?= number_format($total_price, 0, ',', '.') ?> VND</strong></p>
        </div>
    </div>

    <form method="POST" action="">
        <div class="form-group">
            <label for="name">Họ và tên</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="form-group">
            <label for="phone">Số điện thoại</label>
            <input type="text" class="form-control" id="phone" name="phone" required>
        </div>
        <div class="form-group">
            <label for="address">Địa chỉ giao hàng</label>
            <input type="text" class="form-control" id="address" name="address" required>
        </div>
        <div class="form-group">
            <label for="note">Ghi chú</label>
            <textarea class="form-control" id="note" name="note" rows="3"></textarea>
        </div>
        <button type="submit" class="btn btn-primary mt-3">Đặt hàng</button>
    </form>
</div>
</body>
</html>
<?php require_once __DIR__ . '/../components/footer.php'; ?>