<?php
session_start();
require_once '../../includes/db/database.php';
global $conn;

// Kiểm tra quyền admin
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../user/login.php');
    exit();
}

// Lấy danh sách danh mục
$result = $conn->query("SELECT id, name FROM categories");
$categories = $result->fetch_all(MYSQLI_ASSOC);

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $category_id = $_POST['category_id'] ?? '';
    $price = $_POST['price'] ?? '';
    $description = $_POST['description'] ?? '';
    $stock = $_POST['stock'] ?? '';
    $image = $_FILES['image']['name'] ?? '';

    // Xác thực dữ liệu
    $errors = [];
    if (empty($name)) $errors[] = 'Tên sản phẩm không được để trống.';
    if (!is_numeric($category_id) || $category_id <= 0) $errors[] = 'Vui lòng chọn danh mục hợp lệ.';
    if (!is_numeric($price) || $price < 0) $errors[] = 'Giá phải là số dương.';
    if (!is_numeric($stock) || $stock < 0) $errors[] = 'Số lượng tồn kho phải là số không âm.';
    if (empty($image)) $errors[] = 'Vui lòng chọn hình ảnh sản phẩm.';

    // Xử lý upload hình ảnh
    if (empty($errors)) {
        $target_dir = "../../assets/images/products/";
        $target_file = $target_dir . basename($image);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($imageFileType, $allowed_types)) {
            $errors[] = 'Chỉ hỗ trợ định dạng hình ảnh JPG, JPEG, PNG, GIF.';
        } elseif ($_FILES['image']['size'] > 5000000) {
            $errors[] = 'Hình ảnh không được vượt quá 5MB.';
        }
    }

    // Lưu sản phẩm nếu không có lỗi
    if (empty($errors)) {
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $stmt = $conn->prepare("INSERT INTO products (category_id, name, description, price, stock, image) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param('issdis', $category_id, $name, $description, $price, $stock, $image);
            if ($stmt->execute()) {
                $message = '<p class="success">Thêm sản phẩm thành công!</p>';
            } else {
                $message = '<p class="error">Lỗi khi thêm sản phẩm. Vui lòng thử lại.</p>';
            }
        } else {
            $message = '<p class="error">Lỗi khi tải lên hình ảnh. Vui lòng thử lại.</p>';
        }
    } else {
        $message = '<p class="error">' . implode('<br>', $errors) . '</p>';
    }
}

include '../../pages/components/admin_header.php';
?>

    <div class="admin-container">
        <h1>Thêm sản phẩm mới</h1>

        <?php if ($message): ?>
            <?php echo $message; ?>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" class="product-form">
            <label for="name">Tên sản phẩm:</label>
            <input type="text" id="name" name="name" value="<?= isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '' ?>" required>

            <label for="category_id">Danh mục:</label>
            <select id="category_id" name="category_id" required>
                <option value="">Chọn danh mục</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= $category['id'] ?>" <?= (isset($_POST['category_id']) && $_POST['category_id'] == $category['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($category['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="price">Giá (VND):</label>
            <input type="number" id="price" name="price" value="<?= isset($_POST['price']) ? htmlspecialchars($_POST['price']) : '' ?>" min="0" step="1000" required>

            <label for="description">Mô tả:</label>
            <textarea id="description" name="description"><?= isset($_POST['description']) ? htmlspecialchars($_POST['description']) : '' ?></textarea>

            <label for="stock">Số lượng tồn kho:</label>
            <input type="number" id="stock" name="stock" value="<?= isset($_POST['stock']) ? htmlspecialchars($_POST['stock']) : '' ?>" min="0" required>

            <label for="image">Hình ảnh:</label>
            <input type="file" id="'\image'" name="image" accept="image/*" required>

            <button type="submit" class="btn btn-add">Thêm sản phẩm</button>
        </form>

        <a href="manage_products.php" class="btn btn-back">Quay lại</a>
    </div>

<?php
include '../../pages/components/admin_footer.php';
?>