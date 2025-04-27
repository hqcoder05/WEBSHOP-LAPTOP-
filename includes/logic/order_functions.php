<?php
require_once __DIR__ . '/../db/database.php';

function addOrder($user_id, $total_amount, $status) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO orders (user_id, total_amount, status, created_at) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("ids", $user_id, $total_amount, $status);
    $result = $stmt->execute();
    $insert_id = $result ? $conn->insert_id : false;
    $stmt->close();
    return $insert_id;
}

function getUserOrders($user_id) {
    global $conn;
    if (!$conn) {
        return [];
    }
    $stmt = $conn->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $orders = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $orders;
}

function getOrderDetails($order_id) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM order_details WHERE order_id = ?");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $details = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $details;
}

function updateOrderStatus($order_id, $status) {
    global $conn;
    $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE order_id = ?");
    $stmt->bind_param("si", $status, $order_id);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}
?>