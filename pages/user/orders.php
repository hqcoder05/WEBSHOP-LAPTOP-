<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
require_once __DIR__ . '/../../includes/logic/order_functions.php';
$user_id = $_SESSION['user_id'];
$orders = getUserOrders($user_id);
require_once __DIR__ . '/../components/header.php';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đơn hàng của tôi</title>
    <link rel="stylesheet" href="/assets/css/orders.css">
</head>
<body>
    <div class="container">
        <h2>Danh sách đơn hàng của bạn</h2>
        <?php if (empty($orders)): ?>
            <p>Không có đơn hàng nào.</p>
        <?php else: ?>
            <table class="orders-table">
                <thead>
                    <tr>
                        <th>Mã đơn hàng</th>
                        <th>Ngày đặt</th>
                        <th>Tình trạng</th>
                        <th>Tổng tiền</th>
                        <th>Chi tiết</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td><?= htmlspecialchars($order['order_id']) ?></td>
                            <td><?= htmlspecialchars($order['created_at']) ?></td>
                            <td><?= htmlspecialchars($order['status']) ?></td>
                            <td><?= number_format($order['total_amount'], 0, ',', '.') ?> VND</td>
                            <td><a href="/pages/user/order_details.php?id=<?= $order['order_id'] ?>">Xem chi tiết</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
<?php require_once __DIR__ . '/../components/footer.php'; ?>
</body>
</html>