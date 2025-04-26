<?php

// Kết nối tới cơ sở dữ liệu (sử dụng lại hàm từ product_functions.php)
include_once 'product_functions.php';

// Tạo đơn hàng mới
function createOrder($userId, $cartItems, $totalPrice) {
    $conn = getDatabaseConnection();
    $conn->beginTransaction();
    try {
        // Lưu thông tin đơn hàng
        $sql = "INSERT INTO orders (user_id, total_price, created_at) VALUES (:user_id, :total_price, NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            'user_id' => $userId,
            'total_price' => $totalPrice
        ]);
        $orderId = $conn->lastInsertId();

        // Lưu chi tiết đơn hàng
        $sql = "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (:order_id, :product_id, :quantity, :price)";
        $stmt = $conn->prepare($sql);
        foreach ($cartItems as $item) {
            $stmt->execute([
                'order_id' => $orderId,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price']
            ]);
        }

        $conn->commit();
        return "Đơn hàng đã được tạo thành công với ID: $orderId";
    } catch (Exception $e) {
        $conn->rollBack();
        return "Lỗi khi tạo đơn hàng: " . $e->getMessage();
    }
}
?>
