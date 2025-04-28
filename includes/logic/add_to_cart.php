<?php
session_start();
require_once 'product_functions.php';

// Kiểm tra user đã đăng nhập
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'Token không hợp lệ']);
        exit;
    }

    $productId = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;

    // Validate input
    if ($productId <= 0 || $quantity <= 0) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ']);
        exit;
    }

    // Kiểm tra sản phẩm tồn tại
    $product = getProductById($productId);
    if (!$product) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Sản phẩm không tồn tại']);
        exit;
    }

    // Kiểm tra tồn kho
    if (!checkStock($productId, $quantity)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Số lượng vượt quá tồn kho']);
        exit;
    }

    // Khởi tạo giỏ hàng nếu chưa có
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Thêm sản phẩm vào giỏ hàng
    if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId] += $quantity;
    } else {
        $_SESSION['cart'][$productId] = $quantity;
    }

    // Tính tổng số lượng sản phẩm trong giỏ
    $totalItems = array_sum($_SESSION['cart']);

    echo json_encode([
        'success' => true,
        'message' => 'Đã thêm vào giỏ hàng',
        'totalItems' => $totalItems,
        'productName' => htmlspecialchars($product['name'])
    ]);
}
?>