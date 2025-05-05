<?php
session_start();
require_once '../../includes/db/database.php'; // Kết nối cơ sở dữ liệu
global $conn;

// Kiểm tra người dùng đã đăng nhập chưa
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
require_once __DIR__ . '/../components/header.php';

// Lấy danh sách đơn hàng của người dùng
$stmt = $conn->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
$stmt->bind_param('i', $user_id);
$stmt->execute();
$orders_result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đơn hàng của tôi</title>
    <link rel="stylesheet" href="../../assets/css/user_orders.css">
</head>
<body>
<div class="container mt-4 mb-5">
    <h2>Đơn hàng của bạn</h2>

    <?php if ($orders_result->num_rows > 0): ?>
        <table class="table table-bordered mt-3">
            <thead>
            <tr>
                <th>Mã đơn</th>
                <th>Ngày đặt</th>
                <th>Tổng tiền</th>
                <th>Trạng thái</th>
                <th>Chi tiết</th>
            </tr>
            </thead>
            <tbody>
            <?php while ($order = $orders_result->fetch_assoc()): ?>
                <tr>
                    <td>#<?php echo $order['id']; ?></td>
                    <td><?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></td>
                    <td><?php echo number_format($order['total_price'], 0, ',', '.'); ?> VND</td>
                    <td><?php echo ucfirst($order['status']); ?></td>
                    <td><a href="order_detail.php?order_id=<?php echo $order['id']; ?>">Xem chi tiết</a></td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Bạn chưa có đơn hàng nào.</p>
    <?php endif; ?>
</div>
</body>
</html>

<?php require_once __DIR__ . '/../components/footer.php'; ?>
