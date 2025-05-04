<?php
session_start();
require_once __DIR__ . '/../../includes/db/database.php';
require_once __DIR__ . '/../../includes/logic/product_functions.php';
require_once __DIR__ . '/../../includes/logic/order_functions.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = 'Vui lòng đăng nhập để đặt hàng.';
    header('Location: /pages/user/login.php?redirect=/pages/user/checkout.php');
    exit;
}

// Kiểm tra phương thức POST và dữ liệu cần thiết
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || 
    !isset($_POST['full_name'], $_POST['phone'], $_POST['address'], $_POST['csrf_token'])) {
    $_SESSION['error'] = 'Dữ liệu không hợp lệ. Vui lòng kiểm tra lại.';
    header('Location: /pages/user/checkout.php');
    exit;
}

// Kiểm tra CSRF token
if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    $_SESSION['error'] = 'Yêu cầu không hợp lệ. Vui lòng thử lại.';
    header('Location: /pages/user/checkout.php');
    exit;
}

// Kiểm tra dữ liệu checkout trong session
if (!isset($_SESSION['checkout']) || 
    !isset($_SESSION['checkout']['product_id'], $_SESSION['checkout']['quantity'], $_SESSION['checkout']['total_price'])) {
    $_SESSION['error'] = 'Không tìm thấy thông tin đơn hàng.';
    header('Location: /pages/user/products.php');
    exit;
}

// Lấy và làm sạch dữ liệu
$user_id = (int)$_SESSION['user_id'];
$product_id = (int)$_SESSION['checkout']['product_id'];
$quantity = (int)$_SESSION['checkout']['quantity'];
$total_price = (float)$_SESSION['checkout']['total_price'];
$full_name = trim($_POST['full_name']);
$phone = trim($_POST['phone']);
$address = trim($_POST['address']);
$note = trim($_POST['note'] ?? '');

// Ghi log yêu cầu để debug
error_log("Xử lý đơn hàng: User ID=$user_id, Product ID=$product_id, Quantity=$quantity, Total Price=$total_price");

// Kiểm tra dữ liệu đầu vào
if (empty($full_name) || empty($phone) || empty($address)) {
    $_SESSION['error'] = 'Vui lòng nhập đầy đủ họ tên, số điện thoại và địa chỉ.';
    header('Location: /pages/user/checkout.php');
    exit;
}
if (!preg_match('/^[0-9]{10,11}$/', $phone)) {
    $_SESSION['error'] = 'Số điện thoại không hợp lệ. Vui lòng nhập 10 hoặc 11 số.';
    header('Location: /pages/user/checkout.php');
    exit;
}
if (strlen($full_name) > 255 || strlen($address) > 255) {
    $_SESSION['error'] = 'Họ tên hoặc địa chỉ quá dài. Vui lòng kiểm tra lại.';
    header('Location: /pages/user/checkout.php');
    exit;
}

// Kiểm tra sản phẩm
$product = getProductById($product_id);
if (!$product) {
    $_SESSION['error'] = 'Sản phẩm không tồn tại hoặc đã bị xóa.';
    header('Location: /pages/user/products.php');
    exit;
}

// Kiểm tra số lượng tồn kho
if ($quantity < 1 || !checkStock($product_id, $quantity)) {
    $_SESSION['error'] = 'Số lượng không hợp lệ hoặc sản phẩm đã hết hàng.';
    header('Location: /pages/user/checkout.php');
    exit;
}

// Kiểm tra tổng giá
$expected_total = $product['price'] * $quantity;
if (abs($total_price - $expected_total) > 0.01) {
    $_SESSION['error'] = 'Tổng giá không khớp. Vui lòng kiểm tra lại đơn hàng.';
    header('Location: /pages/user/checkout.php');
    exit;
}

// Bắt đầu giao dịch
$conn->begin_transaction();

try {
    // Thêm đơn hàng
    $order_id = addOrder($user_id, $total_price, 'pending');
    if (!$order_id) {
        throw new Exception('Không thể tạo đơn hàng.');
    }

    // Thêm chi tiết đơn hàng
    $sql = "INSERT INTO order_details (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiid", $order_id, $product_id, $quantity, $product['price']);
    if (!$stmt->execute()) {
        throw new Exception('Không thể thêm chi tiết đơn hàng.');
    }
    $stmt->close();

    // Thêm địa chỉ giao hàng
    $sql = "INSERT INTO order_addresses (order_id, full_name, phone, address, note) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issss", $order_id, $full_name, $phone, $address, $note);
    if (!$stmt->execute()) {
        throw new Exception('Không thể thêm địa chỉ giao hàng.');
    }
    $stmt->close();

    // Thêm hóa đơn
    $sql = "INSERT INTO invoices (order_id, total_amount, created_at) VALUES (?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("id", $order_id, $total_price);
    if (!$stmt->execute()) {
        throw new Exception('Không thể tạo hóa đơn.');
    }
    $stmt->close();

    // Giảm tồn kho
    if (!reduceStock($product_id, $quantity)) {
        throw new Exception('Không thể cập nhật tồn kho. Sản phẩm có thể đã hết hàng.');
    }

    // Ghi log hành động
    $sql = "INSERT INTO order_logs (order_id, action, created_at) VALUES (?, 'created', NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $stmt->close();

    // Xác nhận giao dịch
    $conn->commit();

    // Ghi log thành công
    error_log("Đặt hàng thành công: Order ID=$order_id");

    // Xóa dữ liệu tạm
    unset($_SESSION['checkout']);
    $_SESSION['success'] = 'Đặt hàng thành công! Mã đơn hàng: #' . $order_id . '. Cảm ơn bạn đã mua sắm.';

    // Tạo CSRF token mới
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

    // Chuyển hướng
    header('Location: /pages/user/orders.php');
    exit;
} catch (Exception $e) {
    // Hoàn tác giao dịch
    $conn->rollback();
    error_log("Lỗi đặt hàng (User ID: $user_id, Product ID: $product_id): " . $e->getMessage());
    $_SESSION['error'] = 'Đã xảy ra lỗi khi đặt hàng: ' . $e->getMessage() . '. Vui lòng thử lại.';
    header('Location: /pages/user/checkout.php');
    exit;
}
?>