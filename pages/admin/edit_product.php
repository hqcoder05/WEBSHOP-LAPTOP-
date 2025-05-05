<?php
session_start();
require_once '../../includes/db/database.php'; // Kết nối cơ sở dữ liệu
global $conn;

// Kiểm tra quyền admin
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../user/login.php');
    exit();
}

// Kiểm tra ID sản phẩm
$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($product_id <= 0) {
    header('Location: manage_products.php?error=' . urlencode('Sản phẩm không hợp lệ'));
    exit();
}

// Lấy thông tin sản phẩm
$stmt = $conn->prepare("SELECT p.*, c.name AS category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.id = ?");
$stmt->bind_param('i', $product_id);
$stmt->execute();
$product_result = $stmt->get_result();

if ($product_result->num_rows === 0) {
    header('Location: manage_products.php?error=' . urlencode('Sản phẩm không tồn tại'));
    exit();
}
$product = $product_result->fetch_assoc();

// Lấy danh sách danh mục
$stmt = $conn->prepare("SELECT id, name FROM categories");
$stmt->execute();
$categories_result = $stmt->get_result();
$categories = $categories_result->fetch_all(MYSQLI_ASSOC);

// Hàm tạo đường dẫn hình ảnh từ cột image
function getImagePath($image) {
    // Nếu hình ảnh không tồn tại, trả về ảnh mặc định
    if (empty($image)) {
        return '/assets/images/products/default.jpg';
    }
    // Trả về đường dẫn hình ảnh lưu trong cơ sở dữ liệu
    return '/assets/images/products/' . htmlspecialchars($image);
}

// Xử lý khi gửi form
$update_message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $price = (float)($_POST['price'] ?? 0);
    $stock = (int)($_POST['stock'] ?? 0);
    $description = trim($_POST['description'] ?? '');
    $category_id = (int)($_POST['category_id'] ?? 0);
    $image = $_FILES['image'] ?? null;

    // Kiểm tra dữ liệu đầu vào
    if (empty($name)) {
        $update_message = '<p class="error">Vui lòng nhập tên sản phẩm.</p>';
    } elseif ($price <= 0) {
        $update_message = '<p class="error">Giá sản phẩm phải lớn hơn 0.</p>';
    } elseif ($stock < 0) {
        $update_message = '<p class="error">Số lượng tồn kho không hợp lệ.</p>';
    } elseif ($category_id <= 0) {
        $update_message = '<p class="error">Vui lòng chọn danh mục.</p>';
    } else {
        // Xử lý upload hình ảnh
        $image_name = $product['image']; // Giữ hình ảnh cũ nếu không upload
        if ($image && $image['error'] === UPLOAD_ERR_OK) {
            $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
            $max_size = 5 * 1024 * 1024; // 5MB
            if (!in_array($image['type'], $allowed_types)) {
                $update_message = '<p class="error">Chỉ chấp nhận file JPEG, PNG hoặc GIF.</p>';
            } elseif ($image['size'] > $max_size) {
                $update_message = '<p class="error">Kích thước file quá lớn (tối đa 5MB).</p>';
            } else {
                $image_name = time() . '_' . basename($image['name']);
                $image_path = __DIR__ . '/../../assets/images/products/' . $image_name;
                if (!move_uploaded_file($image['tmp_name'], $image_path)) {
                    $update_message = '<p class="error">Lỗi khi tải lên hình ảnh.</p>';
                }
            }
        }

        // Cập nhật sản phẩm
        if (empty($update_message)) {
            if ($image && $image['error'] === UPLOAD_ERR_OK && empty($update_message)) {
                $stmt = $conn->prepare("UPDATE products SET name = ?, price = ?, stock = ?, description = ?, category_id = ?, image = ? WHERE id = ?");
                $stmt->bind_param('sdisisi', $name, $price, $stock, $description, $category_id, $image_name, $product_id);
            } else {
                $stmt = $conn->prepare("UPDATE products SET name = ?, price = ?, stock = ?, description = ?, category_id = ? WHERE id = ?");
                $stmt->bind_param('sdisii', $name, $price, $stock, $description, $category_id, $product_id);
            }

            if ($stmt->execute()) {
                $update_message = '<p class="success">Cập nhật sản phẩm thành công!</p>';
                // Cập nhật lại thông tin sản phẩm để hiển thị
                $stmt = $conn->prepare("SELECT p.*, c.name AS category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.id = ?");
                $stmt->bind_param('i', $product_id);
                $stmt->execute();
                $product = $stmt->get_result()->fetch_assoc();
            } else {
                $update_message = '<p class="error">Lỗi khi cập nhật sản phẩm. Vui lòng thử lại.</p>';
            }
            $stmt->close();
        }
    }
}

include '../../pages/components/admin_header.php';
?>

<div class="admin-container">
    <h2>Chỉnh sửa sản phẩm #<?= $product['id'] ?></h2>

    <?php if ($update_message): ?>
        <?= $update_message ?>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" class="product-form">
        <div class="form-group">
            <label for="name">Tên sản phẩm:</label>
            <input type="text" id="name" name="name" class="form-control" value="<?= htmlspecialchars($product['name']) ?>" required>
        </div>

        <div class="form-group">
            <label for="price">Giá (VND):</label>
            <input type="number" id="price" name="price" class="form-control" value="<?= htmlspecialchars($product['price']) ?>" step="0.01" min="0" required>
        </div>

        <div class="form-group">
            <label for="stock">Số lượng tồn kho:</label>
            <input type="number" id="stock" name="stock" class="form-control" value="<?= htmlspecialchars($product['stock']) ?>" min="0" required>
        </div>

        <div class="form-group">
            <label for="description">Mô tả:</label>
            <textarea id="description" name="description" class="form-control" rows="5"><?= htmlspecialchars($product['description'] ?? '') ?></textarea>
        </div>

        <div class="form-group">
            <label for="category_id">Danh mục:</label>
            <select id="category_id" name="category_id" class="form-control" required>
                <option value="">Chọn danh mục</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= $category['id'] ?>" <?= $product['category_id'] == $category['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($category['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="image">Hình ảnh:</label>
            <input type="file" id="image" name="image" class="form-control" accept="image/jpeg,image/png,image/gif">
            <small>Chọn hình ảnh mới (JPEG, PNG, GIF, tối đa 5MB). Để trống để giữ hình ảnh hiện tại.</small>
        </div>

        <div class="form-group">
            <label>Hình ảnh hiện tại:</label><br>
            <img src="<?= getImagePath($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="product-image">
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Cập nhật sản phẩm</button>
            <a href="manage_products.php" class="btn btn-back">Quay lại danh sách sản phẩm</a>
        </div>
    </form>
</div>

<?php include '../../pages/components/admin_footer.php'; ?>
