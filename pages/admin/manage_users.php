<?php
include '../../pages/components/admin_header.php';
require_once '../../includes/db/database.php'; // Kết nối cơ sở dữ liệu
global $conn;

// Lấy danh sách người dùng
$sql = "SELECT id, username, email, role, created_at FROM users";
$result = $conn->query($sql);
?>

<title>Quản lý Người Dùng</title>

<div class="admin-container">
    <h1>Quản lý Người Dùng</h1>

    <table class="table">
        <thead>
        <tr>
            <th>ID</th>
            <th>Tên người dùng</th>
            <th>Email</th>
            <th>Quyền</th>
            <th>Ngày tạo</th>
            <th>Hành động</th>
        </tr>
        </thead>
        <tbody>
        <?php while ($user = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $user['id'] ?></td>
                <td><?= htmlspecialchars($user['username']) ?></td>
                <td><?= htmlspecialchars($user['email']) ?></td>
                <td><?= htmlspecialchars($user['role']) ?></td>
                <td><?= date('d-m-Y', strtotime($user['created_at'])) ?></td>
                <td>
                    <a href="edit_user.php?id=<?= $user['id'] ?>" class="btn btn-edit">Chỉnh sửa</a>
                    <a href="delete_user.php?id=<?= $user['id'] ?>" class="btn btn-delete" onclick="return confirm('Bạn chắc chắn muốn xóa người dùng này?')">Xóa</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php
include '../../pages/components/admin_footer.php';
?>
