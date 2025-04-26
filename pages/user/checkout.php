<?php
include_once '../../includes/logic/order_functions.php';

// Kiểm tra phương thức POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    session_start();

    // Lấy thông tin từ session
    $userId = $_SESSION['user_id'] ?? null;
    $cartItems = $_SESSION['cart'] ?? [];
    $totalPrice = array_reduce($cartItems, function ($sum, $item) {
        return $sum + ($item['price'] * $item['quantity']);
    }, 0);

    if (!$userId || empty($cartItems)) {
        echo json_encode(['error' => 'Giỏ hàng trống hoặc chưa đăng nhập']);
        exit;
    }

    // Gọi hàm tạo đơn hàng
    $result = createOrder($userId, $cartItems, $totalPrice);

    // Xóa giỏ hàng sau khi thanh toán
    unset($_SESSION['cart']);

    // Trả về kết quả
    echo json_encode(['message' => $result]);
} else {
    echo json_encode(['error' => 'Phương thức không hợp lệ']);
}
?>
