<?php
session_start();
require_once '../../includes/db/database.php';
global $conn;

// Kiểm tra quyền admin
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../user/login.php');
    exit();
}

// Kiểm tra ID sản phẩm
$product_id = $_GET['id'] ?? null;
if (!$product_id) {
    header('Location: manage_products.php');
    exit();
}

// Xóa sản phẩm
$stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
$stmt->bind_param('i', $product_id);
if ($stmt->execute()) {
    // Kiểm tra nếu sản phẩm đã bị xóa
    if ($stmt->affected_rows > 0) {
        header('Location: manage_products.php?message=' . urlencode('Xóa sản phẩm thành công!'));
    } else {
        header('Location: manage_products.php?error=' . urlencode('Sản phẩm không tồn tại.'));
    }
} else {
    header('Location: manage_products.php?error=' . urlencode('Lỗi khi xóa sản phẩm. Vui lòng thử lại.'));
}

exit();
?>