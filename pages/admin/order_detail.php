<?php
session_start();
require_once '../../includes/db/database.php'; // Kết nối cơ sở dữ liệu
global $conn;

// Kiểm tra quyền admin
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../user/login.php');
    exit();
}

// Kiểm tra ID đơn hàng
$order_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($order_id <= 0) {
    header('Location: manage_orders.php?error=' . urlencode('Đơn hàng không hợp lệ'));
    exit();
}

// Xử lý cập nhật trạng thái
$update_message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['status'])) {
    $new_status = $_POST['status'];
    $valid_statuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];

    if (in_array($new_status, $valid_statuses)) {
        $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
        $stmt->bind_param('si', $new_status, $order_id);
        if ($stmt->execute()) {
            $update_message = '<p class="success">Cập nhật trạng thái đơn hàng thành công!</p>';
        } else {
            $update_message = '<p class="error">Lỗi khi cập nhật trạng thái. Vui lòng thử lại.</p>';
        }
        $stmt->close();
    } else {
        $update_message = '<p class="error">Trạng thái không hợp lệ.</p>';
    }
}

// Lấy thông tin đơn hàng
$stmt = $conn->prepare("
    SELECT o.id, o.user_id, o.total_price, o.status, o.created_at, u.username
    FROM orders o
    LEFT JOIN users u ON o.user_id = u.id
    WHERE o.id = ?
");
$stmt->bind_param('i', $order_id);
$stmt->execute();
$order_result = $stmt->get_result();
if ($order_result->num_rows === 0) {
    header('Location: manage_orders.php?error=' . urlencode('Đơn hàng không tồn tại'));
    exit();
}
$order = $order_result->fetch_assoc();

// Lấy chi tiết đơn hàng
$stmt = $conn->prepare("
    SELECT od.product_id, od.quantity, od.price, p.name, p.image
    FROM order_details od
    LEFT JOIN products p ON od.product_id = p.id
    WHERE od.order_id = ?
");
$stmt->bind_param('i', $order_id);
$stmt->execute();
$details_result = $stmt->get_result();
$order_details = $details_result->fetch_all(MYSQLI_ASSOC);

// Định nghĩa trạng thái bằng tiếng Việt
$status_labels = [
    'pending' => 'Đang chờ xử lý',
    'processing' => 'Đang xử lý',
    'shipped' => 'Đã giao hàng',
    'delivered' => 'Đã nhận hàng',
    'cancelled' => 'Đã hủy'
];

// Hàm tạo đường dẫn hình ảnh từ cột image
function getImagePath($image) {
    if (empty($image)) {
        return '/assets/images/products/default.jpg';
    }
    return '/assets/images/products/' . htmlspecialchars($image);
}

include '../../pages/components/admin_header.php';
?>

    <div class="admin-container">
        <h1>Chi tiết đơn hàng #<?= $order['id'] ?></h1>

        <?php if ($update_message): ?>
            <?= $update_message ?>
        <?php endif; ?>

        <div class="order-info">
            <p><strong>Khách hàng:</strong> <?= htmlspecialchars($order['username'] ?: 'Khách không xác định') ?></p>
            <p><strong>Ngày đặt:</strong> <?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></p>
            <p><strong>Tổng tiền:</strong> <?= number_format($order['total_price'], 0, ',', '.') ?> VND</p>
            <p><strong>Trạng thái:</strong> <?= $status_labels[$order['status']] ?? ucfirst($order['status']) ?></p>
            <form method="POST" class="status-form">
                <label for="status">Cập nhật trạng thái:</label>
                <select name="status" id="status">
                    <?php foreach ($status_labels as $value => $label): ?>
                        <option value="<?= $value ?>" <?= $order['status'] === $value ? 'selected' : '' ?>><?= $label ?></option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" class="btn btn-update">Cập nhật</button>
            </form>
        </div>

        <h2>Sản phẩm trong đơn hàng</h2>
        <?php if (count($order_details) > 0): ?>
            <table>
                <thead>
                <tr>
                    <th>Hình ảnh</th>
                    <th>Tên sản phẩm</th>
                    <th>Số lượng</th>
                    <th>Giá đơn vị</th>
                    <th>Thành tiền</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($order_details as $detail): ?>
                    <tr>
                        <td>
                            <img src="<?= getImagePath($detail['image']) ?>" alt="<?= htmlspecialchars($detail['name'] ?: 'Sản phẩm không xác định') ?>" width="100" class="product-image">
                        </td>
                        <td><?= htmlspecialchars($detail['name'] ?: 'Sản phẩm không xác định') ?></td>
                        <td><?= $detail['quantity'] ?></td>
                        <td><?= number_format($detail['price'], 0, ',', '.') ?> VND</td>
                        <td><?= number_format($detail['quantity'] * $detail['price'], 0, ',', '.') ?> VND</td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="error">Không có sản phẩm nào trong đơn hàng.</p>
        <?php endif; ?>

        <a href="manage_orders.php" class="btn btn-back">Quay lại danh sách đơn hàng</a>
    </div>

<?php
include '../../pages/components/admin_footer.php';
?>