<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    die(json_encode(['success' => false, 'message' => 'Phương thức không được hỗ trợ']));
}

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    die(json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập']));
}

if (!isset($_POST['product_id']) || !isset($_POST['quantity']) || !isset($_POST['csrf_token']) ||
    $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    http_response_code(400);
    die(json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ']));
}

$productId = (int)$_POST['product_id'];
$quantity = (int)$_POST['quantity'];

if ($productId <= 0 || $quantity <= 0) {
    http_response_code(400);
    die(json_encode(['success' => false, 'message' => 'Số lượng không hợp lệ']));
}

require_once __DIR__ . '/product_functions.php';

// Kiểm tra sản phẩm tồn tại và còn hàng
$product = getProductById($productId);
if (!$product) {
    http_response_code(404);
    die(json_encode(['success' => false, 'message' => 'Sản phẩm không tồn tại']));
}

if (!checkStock($productId, $quantity)) {
    http_response_code(400);
    die(json_encode(['success' => false, 'message' => 'Số lượng vượt quá tồn kho']));
}

// Cập nhật giỏ hàng
if (isset($_SESSION['cart'][$productId])) {
    $_SESSION['cart'][$productId] = $quantity;
    echo json_encode([
        'success' => true,
        'message' => 'Cập nhật giỏ hàng thành công',
        'totalItems' => array_sum($_SESSION['cart'])
    ]);
} else {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'Sản phẩm không có trong giỏ hàng']);
}
?>