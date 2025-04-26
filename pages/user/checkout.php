<?php
session_start();
include '../../includes/logic/product_functions.php';
$products = getAllProducts();
$total = 0;

foreach ($_SESSION['cart'] ?? [] as $id => $qty) {
    foreach ($products as $product) {
        if ($product['id'] == $id) {
            $total += $product['price'] * $qty;
        }
    }
}
?>
<h2>Thanh toán</h2>
<p>Tổng tiền: <?= number_format($total) ?> VND</p>
<form action="checkout_process.php" method="post">
    <button type="submit">Xác nhận đặt hàng</button>
</form>
