<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    die('Unauthorized');
}

if (!isset($_GET['id']) || !isset($_GET['csrf']) || $_GET['csrf'] !== $_SESSION['csrf_token']) {
    http_response_code(400);
    die('Invalid request');
}

$productId = (int)$_GET['id'];

if (isset($_SESSION['cart'][$productId])) {
    unset($_SESSION['cart'][$productId]);

    // Set flash message
    $_SESSION['flash_message'] = [
        'type' => 'success',
        'message' => 'Đã xóa sản phẩm khỏi giỏ hàng'
    ];
}

header('Location: ../../pages/user/cart.php');
exit;
?>