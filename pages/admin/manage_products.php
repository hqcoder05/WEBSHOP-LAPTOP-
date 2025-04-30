<?php
  include '../../pages/admin/admin_header.php'
?>

<?php
// Giả lập danh sách sản phẩm
$products = [
  [
    'id' => 1,
    'name' => 'Dell Inspiron 15',
    'brand' => 'Dell',
    'price' => '$800',
    'description' => 'Laptop hiệu năng cao, thiết kế chắc chắn.'
  ],
  [
    'id' => 2,
    'name' => 'MacBook Air M2',
    'brand' => 'Macbook',
    'price' => '$1200',
    'description' => 'Thiết kế mỏng nhẹ, chip M2 cực mạnh.'
  ],
  [
    'id' => 3,
    'name' => 'HP Pavilion 14',
    'brand' => 'HP',
    'price' => '$650',
    'description' => 'Hiệu suất tốt, phù hợp sinh viên và văn phòng.'
  ],
  [
    'id' => 4,
    'name' => 'Lenovo ThinkPad X1',
    'brand' => 'Lenovo',
    'price' => '$1100',
    'description' => 'Bền bỉ, pin trâu, bàn phím cực tốt cho dân văn phòng.'
  ]
];

// Xem chi tiết nếu có ID được chọn
$selectedId = $_GET['id'] ?? null;
$selectedProduct = null;
if ($selectedId !== null) {
  foreach ($products as $product) {
    if ($product['id'] == $selectedId) {
      $selectedProduct = $product;
      break;
    }
  }
}
?>

  <div class="container">
    <h1>Quản lý sản phẩm</h1>

    <?php if ($selectedProduct): ?>
      <div class="product-detail">
        <h2><?= $selectedProduct['name'] ?></h2>
        <p><strong>Thương hiệu:</strong> <?= $selectedProduct['brand'] ?></p>
        <p><strong>Giá:</strong> <?= $selectedProduct['price'] ?></p>
        <p><strong>Mô tả:</strong> <?= $selectedProduct['description'] ?></p>
        <a href="../../pages/admin/manage_products.php" class="btn">Quay lại</a>
      </div>
    <?php else: ?>
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Tên sản phẩm</th>
            <th>Thương hiệu</th>
            <th>Giá</th>
            <th>Hành động</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($products as $product): ?>
            <tr>
              <td><?= $product['id'] ?></td>
              <td><?= $product['name'] ?></td>
              <td><?= $product['brand'] ?></td>
              <td><?= $product['price'] ?></td>
              <td><a href="../..pages/admin/manage_products.php?id=<?= $product['id'] ?>" class="btn">Xem chi tiết</a></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </div>
<?php
  include '../../pages/admin/admin_footer.php'
?>