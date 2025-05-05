<?php
include '../../pages/components/admin_header.php';
require_once '../../includes/db/database.php';
global $conn;

// Xử lý thêm danh mục
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['category_name'])) {
    $name = trim($_POST['category_name']);
    if (!empty($name)) {
        $stmt = $conn->prepare("INSERT INTO categories (name) VALUES (?)");
        $stmt->bind_param("s", $name);
        $stmt->execute();
    }
}

// Xử lý xóa danh mục
if (isset($_GET['delete_id'])) {
    $id = (int)$_GET['delete_id'];
    $conn->query("DELETE FROM categories WHERE id = $id");
}

// Lấy danh sách danh mục
$categories = $conn->query("SELECT * FROM categories ORDER BY id DESC");
?>
<title>ADMIN MANAGEMENT</title>
<div class="admin-container">
    <h1>Quản lý danh mục</h1>

    <!-- Form thêm danh mục -->
    <form class="form-section" method="POST">
        <label for="category_name">Tên danh mục mới:</label>
        <input type="text" id="category_name" name="category_name" required>
        <button type="submit">Thêm</button>
    </form>

    <!-- Bảng danh sách danh mục -->
    <h2>Danh sách danh mục</h2>
    <table class="admin-table">
        <thead>
        <tr>
            <th>ID</th>
            <th>Tên danh mục</th>
            <th>Thao tác</th>
        </tr>
        </thead>
        <tbody>
        <?php while ($row = $categories->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td>
                    <a href="?delete_id=<?= $row['id'] ?>" onclick="return confirm('Bạn có chắc muốn xóa?')">Xóa</a>
                    <!-- Có thể thêm nút sửa ở đây -->
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include '../../pages/components/admin_footer.php'; ?>
