<?php
session_start();
require_once __DIR__ . '/../../includes/db/database.php';
require_once __DIR__ . '/../../includes/logic/order_functions.php';
require_once __DIR__ . '/../../includes/logic/product_functions.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    header('Location: /pages/user/login.php?redirect=/pages/user/orders.php');
    exit;
}

$order_id = (int)$_GET['id'];
$user_id = $_SESSION['user_id'];
$order = getOrderById($order_id);

if (!$order || $order['user_id'] != $user_id) {
    $_SESSION['error'] = 'Đơn hàng không tồn tại hoặc bạn không có quyền xem.';
    header('Location: /pages/user/orders.php');
    exit;
}

$details = getOrderDetails($order_id);
$address = getOrderAddress($order_id);
$page_title = "Chi tiết đơn hàng #$order_id";
require_once __DIR__ . '/../components/header.php';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page_title) ?></title>
    <link href="../../assets/css/user_products.css" rel="stylesheet"/>
</head>
<body>
<div class="container mt-4 mb-5">
    <h2>Chi tiết đơn hàng #<?= $order['id'] ?></h2>
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <?= htmlspecialchars($_SESSION['success']); ?>
            <?php unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-error">
            <?= htmlspecialchars($_SESSION['error']); ?>
            <?php unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Thông tin đơn hàng</h5>
        </div>
        <div class="card-body">
            <p><strong>Trạng thái:</strong> <?= htmlspecialchars($order['status']) ?></p>
            <p><strong>Ngày đặt:</strong> <?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></p>
            <p><strong>Tổng tiền:</strong> <?= formatPrice($order['total_price']) ?></p>
        </div>
    </div>
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Sản phẩm</h5>
        </div>
        <div class="card-body">
            <table class="order-table">
                <thead>
                <tr>
                    <th>Sản phẩm</th>
                    <th>Số lượng</th>
                    <th>Đơn giá</th>
                    <th>Thành tiền</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($details as $detail): ?>
                    <tr>
                        <td><?= htmlspecialchars($detail['product_name']) ?></td>
                        <td><?= $detail['quantity'] ?></td>
                        <td><?= formatPrice($detail['price']) ?></td>
                        <td><?= formatPrice($detail['price'] * $detail['quantity']) ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Thông tin giao hàng</h5>
        </div>
        <div class="card-body">
            <p><strong>Họ và tên:</strong> <?= htmlspecialchars($address['full_name']) ?></p>
            <p><strong>Số điện thoại:</strong> <?= htmlspecialchars($address['phone']) ?></p>
            <p><strong>Địa chỉ:</strong> <?= htmlspecialchars($address['address']) ?></p>
            <p><strong>Ghi chú:</strong> <?= htmlspecialchars($address['note'] ?? 'Không có') ?></p>
        </div>
    </div>
    <?php if ($order['status'] === 'pending'): ?>
        <form action="/includes/logic/cancel_order.php" method="POST">
            <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
            <button type="submit" class="button button-danger">Hủy đơn hàng</button>
        </form>
    <?php endif; ?>
</div>
<?php require_once __DIR__ . '/../components/footer.php'; ?>
</body>
</html>