<?php
session_start();
require_once '../../includes/db/database.php';
global $conn;

// Kiểm tra quyền admin
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../user/login.php');
    exit();
}

// Xử lý cập nhật giá sản phẩm
$update_message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id']) && isset($_POST['price'])) {
    $product_id = $_POST['product_id'];
    $new_price = $_POST['price'];

    // Xác thực giá
    if (!is_numeric($new_price) || $new_price < 0) {
        $update_message = '<p class="error">Giá không hợp lệ. Vui lòng nhập số dương.</p>';
    } else {
        $stmt = $conn->prepare("UPDATE products SET price = ? WHERE id = ?");
        $stmt->bind_param('di', $new_price, $product_id);
        if ($stmt->execute()) {
            $update_message = '<p class="success"> Cập nhật giá thành công!</p>';
        } else {
            $update_message = '<p class="error">Lỗi khi cập nhật giá. Vui lòng thử lại.</p>';
        }
    }
}

// Xử lý thông báo từ delete_product.php
$delete_message = '';
if (isset($_GET['message'])) {
    $delete_message = '<p class="success">' . htmlspecialchars(urldecode($_GET['message'])) . '</p>';
} elseif (isset($_GET['error'])) {
    $delete_message = '<p class="error">' . htmlspecialchars(urldecode($_GET['error'])) . '</p>';
}

// Lấy danh sách sản phẩm từ CSDL
$result = $conn->query("SELECT p.*, c.name AS category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id");
$products = $result->fetch_all(MYSQLI_ASSOC);

// Xem chi tiết nếu có ID được chọn
$selectedId = $_GET['id'] ?? null;
$selectedProduct = null;
if ($selectedId !== null) {
    $stmt = $conn->prepare("SELECT p.*, c.name AS category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.id = ?");
    $stmt->bind_param('i', $selectedId);
    $stmt->execute();
    $product_result = $stmt->get_result();
    if ($product_result->num_rows > 0) {
        $selectedProduct = $product_result->fetch_assoc();
    }
}

include '../../pages/components/admin_header.php';
?>
    <title>ADMIN MANAGEMENT</title>
    <div class="admin-container">
        <h1>Quản lý sản phẩm</h1>

        <?php if ($update_message): ?>
            <?php echo $update_message; ?>
        <?php endif; ?>

        <?php if ($delete_message): ?>
            <?php echo $delete_message; ?>
        <?php endif; ?>

        <?php if ($selectedProduct): ?>
            <div class="product-detail">
                <h2><?= htmlspecialchars($selectedProduct['name']) ?></h2>
                <p><strong>Danh mục:</strong> <?= htmlspecialchars($selectedProduct['category_name'] ?: 'N/A') ?></p>
                <p><strong>Giá hiện tại:</strong> <?= number_format($selectedProduct['price'], 0, ',', '.') ?> VND</p>
                <p><strong>Mô tả:</strong> <?= htmlspecialchars($selectedProduct['description'] ?: 'Không có mô tả') ?></p>
                <p><strong>Kho:</strong> <?= $selectedProduct['stock'] ?> sản phẩm</p>
                <p><strong>Hình ảnh:</strong> <img src="../../assets/images/products/<?= htmlspecialchars($selectedProduct['image']) ?>" alt="<?= htmlspecialchars($selectedProduct['name']) ?>" width="200"></p>

                <h3>Sửa giá sản phẩm</h3>
                <form method="POST" action="">
                    <input type="hidden" name="product_id" value="<?= $selectedProduct['id'] ?>">
                    <label for="price">Giá mới (VND):</label>
                    <input type="number" id="price" name="price"

                           System: value="<?= $selectedProduct['price'] ?>" min="0" step="1000" required>
                    <button type="submit" class="btn btn-update">Cập nhật giá</button>
                </form>

                <a href="manage_products.php" class="btn btn-back">Quay lại</a>
            </div>
        <?php else: ?>
            <a href="add_product.php" class="btn btn-add">+ Thêm sản phẩm mới</a>
            <table>
                <thead>
                <tr>
                    <th ID</th>
                    <th>Tên sản phẩm</th>
                    <th>Danh mục</th>
                    <th>Giá</th>
                    <th>Hành động</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <td><?= $product['id'] ?></td>
                        <td><?= htmlspecialchars($product['name']) ?></td>
                        <td><?= htmlspecialchars($product['category_name'] ?: 'N/A') ?></td>
                        <td><?= number_format($product['price'], 0, ',', '.') ?> VND</td>
                        <td>
                            <a href="manage_products.php?id=<?= $product['id'] ?>" class="btn btn-view">Xem</a>
                            <a href="edit_product.php?id=<?= $product['id'] ?>" class="btn btn-edit">Sửa</a>
                            <a href="delete_product.php?id=<?= $product['id'] ?>" class="btn btn-delete" onclick="return confirm('Xóa sản phẩm này?')">Xóa</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

<?php
include '../../pages/components/admin_footer.php';
?>