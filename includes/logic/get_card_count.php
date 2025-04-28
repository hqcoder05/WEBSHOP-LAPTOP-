<?php
session_start();

if (!isset($_SESSION['cart'])) {
    echo json_encode(['count' => 0]);
    exit;
}

echo json_encode(['count' => array_sum($_SESSION['cart'])]);
?>