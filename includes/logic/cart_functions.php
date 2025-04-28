<?php
require_once __DIR__ . '/../db/database.php';

function getCartItems($user_id) {
    global $conn;

    $sql = "SELECT p.*, ci.quantity FROM cart_items ci
            JOIN carts c ON ci.cart_id = c.id
            JOIN products p ON ci.product_id = p.id
            WHERE c.user_id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $items = [];
    while ($row = $result->fetch_assoc()) {
        $items[] = $row;
    }

    return $items;

    if (isset($_POST['add_to_cart'])) {
        $id = $_POST['product_id'];
        $qty = $_POST['quantity'];

        if (!isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id] = $qty;
        } else {
            $_SESSION['cart'][$id] += $qty;
        }
        header("Location: ../../pages/user/cart.php");
        exit;
    }
}
