<?php
session_start();
require_once __DIR__ . '/../../includes/db/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: /pages/user/login.php?redirect=/pages/user/orders.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT o.id, o.total_price, o.status, o.created_at 
        FROM orders o 
        WHERE o.user_id = ? 
        ORDER BY o.created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$orders = [];
while ($row = $result->fetch_assoc()) {
    $orders[] = $row;
}
$stmt->close();

$page_title = "Đơn hàng của bạn";
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
    <h2>Đơn hàng của bạn</h2>
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
    <?php if (empty($orders)): ?>
        <div class="alert alert-info">Bạn chưa có–

            System: đơn hàng nào.</div>
    <?php else: ?>
        <table class="order-table">
            <thead>
            <tr>
                <th>Mã đơn hàng</th>
                <th>Tổng tiền</th>
                <th>Trạng thái</th>
                <th>Ngày đặt</th>
                <th>Chi tiết</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($orders as $order): ?>
                <tr>
                    <td>#<?= $order['id']; ?></td>
                    <td><?= formatPrice($order['total_price']); ?></td>
                    <td><?= htmlspecialchars($order['status']); ?></td>
                    <td><?= date('d/m/Y H:i', strtotime($order['created_at'])); ?></td>
                    <td><a href="order_detail.php?id=<?= $order['id']; ?>" class="button button-primary">Xem chi tiết</a></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
<?php require_once __DIR__ . '/../components/footer.php'; ?>
</body>
</html>
?>