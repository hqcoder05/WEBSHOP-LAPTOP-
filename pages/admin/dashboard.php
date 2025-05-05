<?php
include '../../pages/components/admin_header.php';
require_once '../../includes/db/database.php'; // Kết nối cơ sở dữ liệu
global $conn;

// Lấy thông tin thống kê từ cơ sở dữ liệu
$productCount = $conn->query("SELECT COUNT(*) AS count FROM products")->fetch_assoc()['count'];
$categoryCount = $conn->query("SELECT COUNT(*) AS count FROM categories")->fetch_assoc()['count'];
$orderCount = $conn->query("SELECT COUNT(*) AS count FROM orders")->fetch_assoc()['count'];
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
    </div>
    <ul class="nav">
        <li><a href="../../pages/admin/manage_products.php">Quản lý sản phẩm</a></li>
        <li><a href="../../pages/admin/manage_categories.php">Quản lý thương hiệu</a></li>
        <li><a href="../../pages/admin/manage_orders.php">Quản lý đơn hàng</a></li>
    </ul>
</div>

<?php
include '../../pages/components/admin_footer.php';
?>
