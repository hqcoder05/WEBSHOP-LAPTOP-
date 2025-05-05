<?php
require_once '../../includes/db/database.php'; // Kết nối cơ sở dữ liệu
global $conn;

if (isset($_GET['id'])) {
    $user_id = (int)$_GET['id'];

    // Kiểm tra và xóa người dùng
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param('i', $user_id);
    if ($stmt->execute()) {
        header('Location: manage_users.php');
        exit();
    } else {
        echo "Không thể xóa người dùng.";
    }
} else {
    echo "ID người dùng không hợp lệ.";
}
?>
