<?php
require_once 'product_functions.php';

function getCartItems() {
    if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
        return [];
    }

    $items = [];
    foreach ($_SESSION['cart'] as $productId => $quantity) {
        $product = getProductById($productId);
        if ($product) {
            $product['quantity'] = $quantity;
            $product['subtotal'] = $product['price'] * $quantity;
            $items[] = $product;
        }
    }
    return $items;
}

function getCartTotal() {
    $items = getCartItems();
    $total = 0;
    foreach ($items as $item) {
        $total += $item['subtotal'];
    }
    return $total;
}

function validateCartQuantities() {
    foreach ($_SESSION['cart'] as $productId => $quantity) {
        $product = getProductById($productId);
        if (!$product || $product['stock'] < $quantity) {
            return false;
        }
    }
    return true;
}

function getCartCount() {
    if (!isset($_SESSION['cart'])) {
        return 0;
    }
    return array_sum($_SESSION['cart']);
}
?>