<?php
include '../../pages/components/admin_header.php';
require_once '../../includes/db/database.php'; // Kết nối cơ sở dữ liệu
global $conn;

// Lấy thông tin thống kê từ cơ sở dữ liệu
$productCount = $conn->query("SELECT COUNT(*) AS count FROM products")->fetch_assoc()['count'];
$categoryCount = $conn->query("SELECT COUNT(*) AS count FROM categories")->fetch_assoc()['count'];
$orderCount = $conn->query("SELECT COUNT(*) AS count FROM orders")->fetch_assoc()['count'];
$userCount = $conn->query("SELECT COUNT(*) AS count FROM users")->fetch_assoc()['count']; // Thêm phần thống kê người dùng
?>
<title>ADMIN MANAGEMENT</title>
<div class="admin-container">
    <h1>Bảng điều khiển Admin</h1>
    <div style="display: flex; gap: 20px; justify-content: space-between;">
        <div class="stat-box">
            <h3><?= $productCount ?></h3>
            <p>Sản phẩm</p>
        </div>
        <div class="stat-box">
            <h3><?= $categoryCount ?></h3>
            <p>Danh mục</p>
        </div>
        <div class="stat-box">
            <h3><?= $orderCount ?></h3>
            <p>Đơn hàng</p>
        </div>
        <div class="stat-box">
            <h3><?= $userCount ?></h3> <!-- Hiển thị số lượng người dùng -->
            <p>Người dùng</p>
        </div>
    </div>
    <ul class="nav">
        <li><a href="../../pages/admin/manage_products.php">Quản lý sản phẩm</a></li>
        <li><a href="../../pages/admin/manage_categories.php">Quản lý danh mục</a></li>
        <li><a href="../../pages/admin/manage_orders.php">Quản lý đơn hàng</a></li>
        <li><a href="../../pages/admin/manage_users.php">Quản lý người dùng</a></li>
    </ul>
</div>

<?php
include '../../pages/components/admin_footer.php';
?>
