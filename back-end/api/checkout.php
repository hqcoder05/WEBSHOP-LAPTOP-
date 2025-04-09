<?php
require_once '../../config/database.php';
require_once '../../models/Order.php';
require_once '../../models/OrderDetail.php';

// Kiểm tra phương thức HTTP
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['message' => 'Chỉ hỗ trợ phương thức POST']);
    exit();
}

// Lấy dữ liệu JSON từ yêu cầu
$data = json_decode(file_get_contents('php://input'), true);

// Kiểm tra dữ liệu đầu vào
if (
    !isset($data['user_id']) || 
    !isset($data['products']) || 
    !is_array($data['products']) || 
    count($data['products']) === 0
) {
    http_response_code(400); // Bad Request
    echo json_encode(['message' => 'Dữ liệu không hợp lệ']);
    exit();
}

try {
    // Kết nối database
    $db = new Database();
    $conn = $db->connect();

    // Tạo đơn hàng
    $order = new Order($conn);
    $order->user_id = $data['user_id'];
    $order->total_price = 0; // Sẽ được tính lại bên dưới
    $order->status = 'pending';

    if (!$order->create()) {
        throw new Exception('Không thể tạo đơn hàng');
    }

    $order_id = $conn->lastInsertId();

    // Thêm chi tiết đơn hàng và tính tổng tiền
    $total_price = 0;
    $orderDetail = new OrderDetail($conn);
    foreach ($data['products'] as $product) {
        if (!isset($product['id']) || !isset($product['quantity']) || $product['quantity'] <= 0) {
            throw new Exception('Dữ liệu sản phẩm không hợp lệ');
        }

        $orderDetail->order_id = $order_id;
        $orderDetail->product_id = $product['id'];
        $orderDetail->quantity = $product['quantity'];
        $orderDetail->price = $product['price']; // Giá tại thời điểm đặt hàng
        $total_price += $product['quantity'] * $product['price'];

        if (!$orderDetail->create()) {
            throw new Exception('Không thể thêm chi tiết đơn hàng');
        }
    }

    // Cập nhật tổng tiền cho đơn hàng
    $order->id = $order_id;
    $order->total_price = $total_price;

    if (!$order->updateTotalPrice()) {
        throw new Exception('Không thể cập nhật tổng tiền đơn hàng');
    }

    http_response_code(201); // Created
    echo json_encode(['message' => 'Đơn hàng đã được tạo thành công', 'order_id' => $order_id]);
} catch (Exception $e) {
    http_response_code(500); // Internal Server Error
    echo json_encode(['message' => $e->getMessage()]);
}