<?php
// Kết nối cơ sở dữ liệu
require_once '../../config/database.php';

// Xử lý cập nhật trạng thái đơn hàng
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $orderId = $_POST['order_id'];
    $newStatus = $_POST['status'];

    $sql = "UPDATE orders SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $newStatus, $orderId);

    if ($stmt->execute()) {
        $message = "Cập nhật trạng thái đơn hàng thành công!";
    } else {
        $message = "Có lỗi xảy ra khi cập nhật trạng thái đơn hàng.";
    }
}

// Lấy danh sách đơn hàng
$sql = "SELECT * FROM orders";
$result = $conn->query($sql);
$orders = $result->fetch_all(MYSQLI_ASSOC);

// Hàm xuất báo cáo
function generateReport($orders) {
    $filename = "order_report_" . date('Y-m-d') . ".csv";
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '"');

    $output = fopen("php://output", "w");
    fputcsv($output, ['Order ID', 'User ID', 'Product ID', 'Quantity', 'Total Price', 'Status', 'Order Date']);

    foreach ($orders as $order) {
        fputcsv($output, $order);
    }

    fclose($output);
    exit;
}

// Xuất báo cáo nếu được yêu cầu
if (isset($_GET['action']) && $_GET['action'] === 'export') {
    generateReport($orders);
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Đơn hàng</title>
</head>
<body>
    <h1>Quản lý Đơn hàng</h1>

    <?php if (isset($message)) : ?>
        <p><?php echo $message; ?></p>
    <?php endif; ?>

    <table border="1">
        <thead>
            <tr>
                <th>Mã đơn hàng</th>
                <th>Mã người dùng</th>
                <th>Mã sản phẩm</th>
                <th>Số lượng</th>
                <th>Tổng tiền</th>
                <th>Trạng thái</th>
                <th>Ngày đặt hàng</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orders as $order) : ?>
                <tr>
                    <td><?php echo $order['id']; ?></td>
                    <td><?php echo $order['user_id']; ?></td>
                    <td><?php echo $order['product_id']; ?></td>
                    <td><?php echo $order['quantity']; ?></td>
                    <td><?php echo $order['total_price']; ?></td>
                    <td><?php echo $order['status']; ?></td>
                    <td><?php echo $order['order_date']; ?></td>
                    <td>
                        <form method="POST">
                            <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                            <select name="status">
                                <option value="Pending" <?php echo $order['status'] === 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                <option value="Processing" <?php echo $order['status'] === 'Processing' ? 'selected' : ''; ?>>Processing</option>
                                <option value="Completed" <?php echo $order['status'] === 'Completed' ? 'selected' : ''; ?>>Completed</option>
                                <option value="Cancelled" <?php echo $order['status'] === 'Cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                            </select>
                            <button type="submit" name="update_status">Cập nhật</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <a href="?action=export">Xuất báo cáo</a>
</body>
</html>