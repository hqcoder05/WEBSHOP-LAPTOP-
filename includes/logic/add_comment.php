<?php
session_start();
require_once __DIR__ . '/../db/database.php'; // Kết nối CSDL
global $conn;

// Kiểm tra đăng nhập và dữ liệu đầu vào
if (!isset($_SESSION['user_id']) || !isset($_POST['comment']) || !isset($_POST['product_id'])) {
    header('Location: /pages/user/product_detail.php?error=' . urlencode('Dữ liệu không hợp lệ'));
    exit();
}

$user_id = $_SESSION['user_id'];
$product_id = (int) $_POST['product_id'];
$comment = trim($_POST['comment']);

// Kiểm tra dữ liệu trống
if ($product_id <= 0 || $comment === '') {
    header('Location: /pages/user/product_detail.php?id=' . $product_id . '&error=' . urlencode('Vui lòng nhập bình luận hợp lệ'));
    exit();
}

// Lấy tên người dùng từ bảng users
$stmt_user = $conn->prepare("SELECT username FROM users WHERE id = ?");
$stmt_user->bind_param("i", $user_id);
$stmt_user->execute();
$result_user = $stmt_user->get_result();
if ($result_user->num_rows === 0) {
    header('Location: /pages/user/product_detail.php?id=' . $product_id . '&error=' . urlencode('Người dùng không tồn tại'));
    exit();
}
$user = $result_user->fetch_assoc();
$user_name = $user['username'];

// Kiểm tra sản phẩm có tồn tại không
$stmt_check = $conn->prepare("SELECT id FROM products WHERE id = ?");
$stmt_check->bind_param("i", $product_id);
$stmt_check->execute();
$result_check = $stmt_check->get_result();
if ($result_check->num_rows === 0) {
    header('Location: /pages/user/product_detail.php?error=' . urlencode('Sản phẩm không tồn tại'));
    exit();
}

// Chèn bình luận
$stmt = $conn->prepare("INSERT INTO comments (product_id, user_id, user_name, comment, created_at) VALUES (?, ?, ?, ?, NOW())");
$stmt->bind_param("iiss", $product_id, $user_id, $user_name, $comment);
if ($stmt->execute()) {
    header('Location: ../../pages/user/product_detail.php?id=' . $product_id . '&message=' . urlencode('Thêm bình luận thành công!'));
} else {
    header('Location: ../../pages/user/product_detail.php?id=' . $product_id . '&error=' . urlencode('Lỗi khi thêm bình luận. Vui lòng thử lại.'));
}
exit();
?>