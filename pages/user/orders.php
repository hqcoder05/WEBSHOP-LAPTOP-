<?php
session_start();
include '../../includes/logic/order_functions.php';
$user_id = $_SESSION['user_id']; // Giả sử người dùng đã đăng nhập và có user_id
$orders = getUserOrders($user_id); // Hàm lấy danh sách đơn hàng của người dùng

?>
<h2>Danh sách đơn hàng của bạn</h2>

<?php if (empty($orders)): ?>
    <p>Không có đơn hàng nào.</p>
<?php else: ?>
    <table>
        <thead>
        <tr>
            <th>Mã đơn hàng</th>
            <th>Ngày đặt</th>
            <th>Tình trạng</th>
            <th>Tổng tiền</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($orders as $order): ?>
            <tr>
                <td><?= $order['order_id'] ?></td>
                <td><?= $order['order_date'] ?></td>
                <td><?= $order['status'] ?></td>
                <td><?= number_format($order['total']) ?> VND</td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
