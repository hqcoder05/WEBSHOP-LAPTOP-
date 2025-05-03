<?php

// Kết nối tới cơ sở dữ liệu
function getDatabaseConnection() {
    $host = 'localhost';
    $dbname = 'webshop';
    $username = 'root';
    $password = '';
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        die("Kết nối thất bại: " . $e->getMessage());
    }
}

// Thêm sản phẩm vào giỏ hàng
function addToCart($productId, $quantity) {
    session_start();
    $cart = $_SESSION['cart'] ?? [];

    // Kiểm tra nếu sản phẩm đã tồn tại trong giỏ hàng
    if (isset($cart[$productId])) {
        $cart[$productId]['quantity'] += $quantity;
    } else {
        $conn = getDatabaseConnection();
        $sql = "SELECT * FROM products WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->execute(['id' => $productId]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($product) {
            $cart[$productId] = [
                'product_id' => $productId,
                'name' => $product['name'],
                'price' => $product['price'],
                'quantity' => $quantity
            ];
        } else {
            return "Sản phẩm không tồn tại.";
        }
    }

    // Lưu giỏ hàng vào session
    $_SESSION['cart'] = $cart;
    return "Sản phẩm đã được thêm vào giỏ hàng.";
}

// Cập nhật giỏ hàng
function updateCart($productId, $quantity) {
    session_start();
    $cart = $_SESSION['cart'] ?? [];

    if (isset($cart[$productId])) {
        if ($quantity > 0) {
            $cart[$productId]['quantity'] = $quantity;
        } else {
            unset($cart[$productId]); // Xóa sản phẩm nếu số lượng <= 0
        }
        $_SESSION['cart'] = $cart;
        return "Giỏ hàng đã được cập nhật.";
    } else {
        return "Sản phẩm không tồn tại trong giỏ hàng.";
    }
}

// Xóa sản phẩm khỏi giỏ hàng
function removeFromCart($productId) {
    session_start();
    $cart = $_SESSION['cart'] ?? [];

    if (isset($cart[$productId])) {
        unset($cart[$productId]);
        $_SESSION['cart'] = $cart;
        return "Sản phẩm đã được xóa khỏi giỏ hàng.";
    } else {
        return "Sản phẩm không tồn tại trong giỏ hàng.";
    }
}

// Lưu giỏ hàng vào cơ sở dữ liệu
function saveCartToDatabase($userId) {
    session_start();
    $cart = $_SESSION['cart'] ?? [];

    if (empty($cart)) {
        return "Giỏ hàng trống.";
    }

    $conn = getDatabaseConnection();
    foreach ($cart as $item) {
        $sql = "INSERT INTO cart (user_id, product_id, quantity, price) 
                VALUES (:user_id, :product_id, :quantity, :price)
                ON DUPLICATE KEY UPDATE quantity = :quantity, price = :price";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            'user_id' => $userId,
            'product_id' => $item['product_id'],
            'quantity' => $item['quantity'],
            'price' => $item['price']
        ]);
    }
    return "Giỏ hàng đã được lưu vào cơ sở dữ liệu.";
}
?>
