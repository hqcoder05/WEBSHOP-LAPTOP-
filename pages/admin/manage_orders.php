<?php
include '../../pages/components/admin_header.php';
require_once '../../includes/db/database.php'; // Kết nối cơ sở dữ liệu
global $conn;

// Truy vấn lấy danh sách đơn hàng
$result = $conn->query("SELECT * FROM orders");

if ($result->num_rows > 0):
    ?>
    <div class="admin-container">
        <h1>Quản lý đơn hàng</h1>
        <table>
            <thead>
            <tr>
                <th>ID Đơn hàng</th>
                <th>Khách hàng</th>
                <th>Ngày đặt</th>
                <th>Tổng tiền</th>
                <th>Trạng thái</th>
            </tr>
            </thead>
            <tbody>
            <?php while ($order = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $order['id']; ?></td>
                    <td><?php echo $order['customer_name']; ?></td>
                    <td><?php echo date('Y-m-d', strtotime($order['created_at'])); ?></td>
                    <td><?php echo number_format($order['total_amount'], 0, ',', '.') . ' VND'; ?></td>
                    <td><?php echo ucfirst($order['status']); ?></td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <p>Không có đơn hàng nào trong hệ thống.</p>
<?php endif; ?>

<?php
include '../../pages/components/admin_footer.php';
?>
