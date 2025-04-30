<?php
  include '../../pages/admin/admin_header.php'
?>

  <div class="admin-container">
    <h1>Bảng điều khiển Admin</h1>
      <div style="display: flex; gap: 20px; justify-content: space-between;">
      <div class="stat-box">
        <h3>15</h3>
        <p>Sản phẩm</p>
      </div>
      <div class="stat-box">
        <h3>4</h3>
        <p>Danh mục</p>
      </div>
      <div class="stat-box">
        <h3>12</h3>
        <p>Đơn hàng</p>
      </div>
    </div>
    <ul class="nav">
      <li><a href="../../pages/admin/manage_products.php"></i>Quản lý sản phẩm</a></li>
      <li><a href="../../pages/admin/manage_categories.php">Quản lý danh mục</a></li>
      <li><a href="../../pages/admin/manage_orders.php">Quản lý đơn hàng</a></li>
    </ul>
  </div>

<?php
  include '../../pages/admin/admin_footer.php'
?>