<?php
require_once __DIR__ . '/../db/database.php';

function addOrder($user_id, $total_price, $status) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO orders (user_id, total_price, status, created_at) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("ids", $user_id, $total_price, $status);
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

function getOrderById($order_id) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM orders WHERE id = ?");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $order = $result->fetch_assoc();
    $stmt->close();
    return $order;
}

function getOrderDetails($order_id) {
    global $conn;
    $stmt = $conn->prepare("
        SELECT od.*, p.name as product_name 
        FROM order_details od 
        JOIN products p ON od.product_id = p.id 
        WHERE od.order_id = ?
    ");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $details = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $details;
}

function getOrderAddress($order_id) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM order_addresses WHERE order_id = ?");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $address = $result->fetch_assoc();
    $stmt->close();
    return $address;
}

function updateOrderStatus($order_id, $status) {
    global $conn;
    $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $order_id);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}

function cancelOrder($order_id, $user_id) {
    global $conn;
    $stmt = $conn->prepare("UPDATE orders SET status = 'cancelled' WHERE id = ? AND user_id = ? AND status = 'pending'");
    $stmt->bind_param("ii", $order_id, $user_id);
    $result = $stmt->execute();
    if ($result) {
        $details = getOrderDetails($order_id);
        foreach ($details as $detail) {
            $sql = "UPDATE products SET stock = stock + ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $detail['quantity'], $detail['product_id']);
            $stmt->execute();
        }
    }
    $stmt->close();
    return $result;
}
?>