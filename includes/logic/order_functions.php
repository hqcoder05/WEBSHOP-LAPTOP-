<?php
include '../db/database.php';

// Thêm đơn hàng
function addOrder($user_id, $total_amount, $status) {
    global $conn;

    $stmt = $conn->prepare("INSERT INTO orders (user_id, total_amount, status, created_at) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("ids", $user_id, $total_amount, $status);

    if ($stmt->execute()) {
        return $conn->insert_id;
    } else {
        return false;
    }
}

// Lấy danh sách đơn hàng của người dùng
function getUserOrders($user_id) {
    global $conn;

    $stmt = $conn->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();

    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Lấy chi tiết đơn hàng
function getOrderDetails($order_id) {
    global $conn;

    $stmt = $conn->prepare("SELECT * FROM order_details WHERE order_id = ?");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();

    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Cập nhật trạng thái đơn hàng
function updateOrderStatus($order_id, $status) {
    global $conn;

    $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE order_id = ?");
    $stmt->bind_param("si", $status, $order_id);

    return $stmt->execute();
}
?>
