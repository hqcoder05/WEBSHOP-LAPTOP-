<?php
session_start();
require_once __DIR__ . '/../../includes/db/database.php';
require_once __DIR__ . '/../../includes/logic/product_functions.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = 'Vui lòng đăng nhập để đặt hàng.';
    header('Location: /pages/user/login.php?redirect=/pages/user/checkout.php');
    exit;
}

// Kiểm tra yêu cầu POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['full_name'], $_POST['phone'], $_POST['address'], $_POST['csrf_token'])) {
    $_SESSION['error'] = 'Dữ liệu không hợp lệ.';
    header('Location: /pages/user/checkout.php');
    exit;
}

// Kiểm tra CSRF token
if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    $_SESSION['error'] = 'Yêu cầu không hợp lệ.';
    header('Location: /pages/user/checkout.php');
    exit;
}

// Kiểm tra dữ liệu checkout trong session
if (!isset($_SESSION['checkout']) || !isset($_SESSION['checkout']['product_id'], $_SESSION['checkout']['quantity'], $_SESSION['checkout']['total_price'])) {
    $_SESSION['error'] = 'Không tìm thấy thông tin đơn hàng.';
    header('Location: /pages/user/products.php');
    exit;
}

// Lấy dữ liệu
$user_id = $_SESSION['user_id'];
$product_id = (int)$_SESSION['checkout']['product_id'];
$quantity = (int)$_SESSION['checkout']['quantity'];
$total_price = (float)$_SESSION['checkout']['total_price'];
$full_name = trim($_POST['full_name']);
$phone = trim($_POST['phone']);
$address = trim($_POST['address']);
$note = trim($_POST['note'] ?? '');

// Kiểm tra sản phẩm
$product = getProductById($product_id);
if (!$product) {
    $_SESSION['error'] = 'Sản phẩm không tồn tại.';
    header('Location: /pages/user/products.php');
    exit;
}

// Kiểm tra số lượng tồn kho
if ($quantity < 1 || $quantity > $product['stock']) {
    $_SESSION['error'] = 'Số lượng không hợp lệ hoặc sản phẩm đã hết hàng.';
    header('Location: /pages/user/checkout.php');
    exit;
}

// Bắt đầu giao dịch
$conn->begin_transaction();

try {
    // Kiểm tra tồn kho lần cuối
    if (!checkStock($product_id, $quantity)) {
        $conn->rollback();
        $_SESSION['error'] = 'Sản phẩm đã hết hàng hoặc số lượng không đủ.';
        header('Location: /pages/user/checkout.php');
        exit;
    }

    // Thêm đơn hàng
    $sql = "INSERT INTO orders (user_id, total_price, status, created_at) VALUES (?, ?, 'pending', NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("id", $user_id, $total_price);
    $stmt->execute();
    $order_id = $conn->insert_id;
    $stmt->close();

    // Thêm chi tiết đơn hàng
    $sql = "INSERT INTO order_details (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiid", $order_id, $product_id, $quantity, $product['price']);
    $stmt->execute();
    $stmt->close();

    // Thêm địa chỉ giao hàng
    $sql = "INSERT INTO order_addresses (order_id, full_name, phone, address, note) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issss", $order_id, $full_name, $phone, $address, $note);
    $stmt->execute();
    $stmt->close();

    // Thêm hóa đơn
    $sql = "INSERT INTO invoices (order_id, total_amount, created_at) VALUES (?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("id", $order_id, $total_price);
    $stmt->execute();
    $stmt->close();

    // Cập nhật tồn kho
    $sql = "UPDATE products SET stock = stock - ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $quantity, $product_id);
    $stmt->execute();
    $stmt->close();

    // Xác nhận giao dịch
    $conn->commit();

    // Xóa dữ liệu tạm
    unset($_SESSION['checkout']);
    $_SESSION['success'] = 'Đặt hàng thành công! Cảm ơn bạn đã mua sắm.';

    // Tạo CSRF token mới
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

    // Chuyển hướng
    header('Location: /pages/user/orders.php');
    exit;
} catch (Exception $e) {
    // Hoàn tác giao dịch
    $conn->rollback();
    error_log("Lỗi đặt hàng (Order ID: $order_id): " . $e->getMessage());
    $_SESSION['error'] = 'Đã xảy ra lỗi khi đặt hàng. Vui lòng thử lại.';
    header('Location: /pages/user/checkout.php');
    exit;
}
?>