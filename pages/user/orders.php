<?php
// Kết nối cơ sở dữ liệu
require_once '../../config/database.php';

/**
 * Hàm xử lý thanh toán
 * @param array $orderData
 * @return array
 */
function handlePayment($orderData) {
    // Giả lập xử lý thanh toán (Ví dụ: tích hợp cổng thanh toán)
    $paymentStatus = true; // Giả sử thanh toán thành công

    if ($paymentStatus) {
        // Lưu thông tin đơn hàng vào cơ sở dữ liệu
        $orderSaved = saveOrderToDatabase($orderData);

        if ($orderSaved) {
            return [
                'status' => 'success',
                'message' => 'Thanh toán và lưu đơn hàng thành công!'
            ];
        } else {
            return [
                'status' => 'error',
                'message' => 'Lỗi khi lưu đơn hàng vào cơ sở dữ liệu.'
            ];
        }
    } else {
        return [
            'status' => 'error',
            'message' => 'Thanh toán thất bại. Vui lòng thử lại.'
        ];
    }
}

/**
 * Lưu thông tin đơn hàng vào cơ sở dữ liệu
 * @param array $orderData
 * @return bool
 */
function saveOrderToDatabase($orderData) {
    global $conn;

    $sql = "INSERT INTO orders (user_id, product_id, quantity, total_price, order_date) 
            VALUES (?, ?, ?, ?, NOW())";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "iiid",
        $orderData['user_id'],
        $orderData['product_id'],
        $orderData['quantity'],
        $orderData['total_price']
    );

    // Kiểm tra kết quả lưu
    return $stmt->execute();
}

?>
