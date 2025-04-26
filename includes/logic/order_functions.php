<?php

// Kết nối tới cơ sở dữ liệu (sử dụng lại hàm từ product_functions.php)
include_once 'product_functions.php';

// Tạo đơn hàng mới
function createOrder($userId, $productId, $quantity, $totalPrice) {
    $conn = getDatabaseConnection();
    $sql = "INSERT INTO orders (user_id, product_id, quantity, total_price) VALUES (:user_id, :product_id, :quantity, :total_price)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        'user_id' => $userId,
        'product_id' => $productId,
        'quantity' => $quantity,
        'total_price' => $totalPrice
    ]);
    return "Đơn hàng mới đã được tạo.";
}

// Cập nhật trạng thái đơn hàng
function updateOrderStatus($orderId, $status) {
    $conn = getDatabaseConnection();
    $sql = "UPDATE orders SET status = :status WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['id' => $orderId, 'status' => $status]);
    return "Trạng thái đơn hàng đã được cập nhật.";
}

// Lấy thông tin đơn hàng
function getOrderDetails($orderId) {
    $conn = getDatabaseConnection();
    $sql = "SELECT * FROM orders WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['id' => $orderId]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
?>
