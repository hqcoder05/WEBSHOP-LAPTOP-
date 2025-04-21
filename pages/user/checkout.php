<?php
// Import logic xử lý đơn hàng
require_once '../../includes/logic/order.php';

// Kiểm tra phương thức yêu cầu
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy dữ liệu từ client (giả lập từ form checkout)
    $orderData = [
        'user_id' => $_POST['user_id'],
        'product_id' => $_POST['product_id'],
        'quantity' => $_POST['quantity'],
        'total_price' => $_POST['total_price']
    ];

    // Gọi hàm xử lý thanh toán
    $response = handlePayment($orderData);

    // Phản hồi về client
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

?>
