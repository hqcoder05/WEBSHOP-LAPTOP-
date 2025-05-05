<?php
session_start();
require_once '../../includes/db/database.php'; // Kết nối cơ sở dữ liệu
global $conn;

// Kiểm tra quyền admin
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../user/login.php');
    exit();
}

// Truy vấn lấy danh sách đơn hàng, liên kết với bảng users để lấy username
$stmt = $conn->prepare("
    SELECT o.id, o.user_id, o.total_price, o.status, o.created_at, u.username
    FROM orders o
    LEFT JOIN users u ON o.user_id = u.id
    ORDER BY o.created_at DESC
");
$stmt->execute();
$result = $stmt->get_result();
$orders = $result->fetch_all(MYSQLI_ASSOC);

// Định nghĩa trạng thái bằng tiếng Việt
$status_labels = [
    'pending' => 'Đang chờ xử lý',
    'processing' => 'Đang xử lý',
    'shipped' => 'Đã giao hàng',
    'delivered' => 'Đã nhận hàng',
    'cancelled' => 'Đã hủy'
];

include '../../pages/components/admin_header.php';
?>

    <div class="admin-container">
        <h1>Quản lý đơn hàng</h1>

        <?php if ($result->num_rows > 0): ?>
            <table>
                <thead>
                <tr>
                    <th>ID Đơn hàng</th>
                    <th>Khách hàng</th>
                    <th>Ngày đặt</th>
                    <th>Tổng tiền</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?= $order['id'] ?></td>
                        <td><?= htmlspecialchars($order['username'] ?: 'Khách không xác định') ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></td>
                        <td><?= number_format($order['total_price'], 0, ',', '.') ?> VND</td>
                        <td><?= $status_labels[$order['status']] ?? ucfirst($order['status']) ?></td>
                        <td>
                            <a href="order_detail.php?id=<?= $order['id'] ?>" class="btn btn-view">Xem chi tiết</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="error">Không có đơn hàng nào trong hệ thống.</p>
        <?php endif; ?>
    </div>

<?php
include '../../pages/components/admin_footer.php';
?>