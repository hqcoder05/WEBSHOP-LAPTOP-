<?php
session_start();
require_once '../../includes/db/database.php'; // Database connection
global $conn;

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Check if order_id is provided in URL
if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];

    // Fetch order information
    $stmt = $conn->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ?");
    $stmt->bind_param('ii', $order_id, $user_id);
    $stmt->execute();
    $order_result = $stmt->get_result();

    if ($order_result->num_rows > 0) {
        $order = $order_result->fetch_assoc();

        // Fetch shipping information from order_addresses
        $stmt = $conn->prepare("SELECT full_name, phone, address, note FROM order_addresses WHERE order_id = ?");
        $stmt->bind_param('i', $order_id);
        $stmt->execute();
        $address_result = $stmt->get_result();
        $address = $address_result->num_rows > 0 ? $address_result->fetch_assoc() : [
            'full_name' => 'N/A',
            'phone' => 'N/A',
            'address' => 'N/A',
            'note' => 'N/A'
        ];

        // Fetch order details
        $stmt = $conn->prepare("SELECT order_details.*, products.name AS product_name, products.image AS product_image 
                                FROM order_details 
                                JOIN products ON order_details.product_id = products.id 
                                WHERE order_id = ?");
        $stmt->bind_param('i', $order_id);
        $stmt->execute();
        $details_result = $stmt->get_result();
    } else {
        echo "Đơn hàng không tồn tại hoặc bạn không có quyền truy cập.";
        exit();
    }
} else {
    echo "Không tìm thấy thông tin đơn hàng.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chi tiết đơn hàng #<?php echo $order['id']; ?></title>
    <link rel="stylesheet" href="../../assets/css/order_detail.css">
</head>
<body>
<div class="container mt-4 mb-5">
    <h2>Hóa đơn chi tiết đơn hàng #<?php echo $order['id']; ?></h2>

    <table class="table">
        <tr>
            <th>Ngày đặt</th>
            <td><?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></td>
        </tr>
        <tr>
            <th>Tổng giá trị</th>
            <td><?php echo number_format($order['total_price'], 0, ',', '.'); ?> VND</td>
        </tr>
        <tr>
            <th>Trạng thái</th>
            <td><?php echo ucfirst($order['status']); ?></td>
        </tr>
    </table>

    <h3>Thông tin giao hàng</h3>
    <table class="table">
        <tr>
            <th>Họ và tên</th>
            <td><?php echo htmlspecialchars($address['full_name']); ?></td>
        </tr>
        <tr>
            <th>Số điện thoại</th>
            <td><?php echo htmlspecialchars($address['phone']); ?></td>
        </tr>
        <tr>
            <th>Địa chỉ giao hàng</th>
            <td><?php echo htmlspecialchars($address['address']); ?></td>
        </tr>
        <tr>
            <th>Ghi chú</th>
            <td><?php echo htmlspecialchars($address['note']); ?></td>
        </tr>
    </table>

    <h3>Chi tiết sản phẩm</h3>
    <table class="table">
        <thead>
        <tr>
            <th>Hình ảnh</th>
            <th>Tên sản phẩm</th>
            <th>Số lượng</th>
            <th>Giá</th>
            <th>Tổng</th>
        </tr>
        </thead>
        <tbody>
        <?php while ($detail = $details_result->fetch_assoc()): ?>
            <tr>
                <td><img src="../../assets/images/products/<?php echo htmlspecialchars($detail['product_image']); ?>" width="100" alt="<?php echo htmlspecialchars($detail['product_name']); ?>"></td>
                <td><?php echo htmlspecialchars($detail['product_name']); ?></td>
                <td><?php echo $detail['quantity']; ?></td>
                <td><?php echo number_format($detail['price'], 0, ',', '.'); ?> VND</td>
                <td><?php echo number_format($detail['price'] * $detail['quantity'], 0, ',', '.'); ?> VND</td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>

    <div class="text-right">
        <strong>Tổng cộng: <?php echo number_format($order['total_price'], 0, ',', '.'); ?> VND</strong>
    </div>
</div>
</body>
</html>