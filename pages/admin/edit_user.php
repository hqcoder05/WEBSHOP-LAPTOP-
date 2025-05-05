<?php
include '../../pages/components/admin_header.php';
require_once '../../includes/db/database.php'; // Kết nối cơ sở dữ liệu
global $conn;

if (isset($_GET['id'])) {
    $user_id = (int)$_GET['id'];
    // Lấy thông tin người dùng
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $user_result = $stmt->get_result();

    if ($user_result->num_rows > 0) {
        $user = $user_result->fetch_assoc();
    } else {
        echo "Người dùng không tồn tại.";
        exit;
    }
} else {
    echo "ID người dùng không hợp lệ.";
    exit;
}

// Xử lý form cập nhật
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $role = $_POST['role'] ?? '';

    if (empty($username) || empty($email) || empty($role)) {
        $message = '<p class="error">Vui lòng điền đầy đủ thông tin.</p>';
    } else {
        // Cập nhật thông tin người dùng
        $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, role = ? WHERE id = ?");
        $stmt->bind_param('sssi', $username, $email, $role, $user_id);
        if ($stmt->execute()) {
            $message = '<p class="success">Cập nhật thông tin người dùng thành công!</p>';
        } else {
            $message = '<p class="error">Lỗi khi cập nhật thông tin người dùng.</p>';
        }
    }
}
?>

<title>Chỉnh sửa người dùng</title>

<div class="admin-container">
    <h1>Chỉnh sửa Người Dùng</h1>

    <?php if (isset($message)) echo $message; ?>

    <form method="POST" class="form">
        <div class="form-group">
            <label for="username">Tên người dùng:</label>
            <input type="text" id="username" name="username" class="form-control" value="<?= htmlspecialchars($user['username']) ?>" required>
        </div>

        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>
        </div>

        <div class="form-group">
            <label for="role">Quyền:</label>
            <select id="role" name="role" class="form-control" required>
                <option value="user" <?= $user['role'] === 'user' ? 'selected' : '' ?>>Người dùng</option>
                <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Quản trị viên</option>
            </select>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Cập nhật</button>
            <a href="manage_users.php" class="btn btn-back">Quay lại danh sách người dùng</a>
        </div>
    </form>
</div>

<?php
include '../../pages/components/admin_footer.php';
?>
