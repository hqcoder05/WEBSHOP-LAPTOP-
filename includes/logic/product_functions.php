<?php

// Kết nối cơ sở dữ liệu
require_once '../../config/database.php';
require_once '../../models/Product.php';

/**
 * Lấy danh sách tất cả sản phẩm
 * @return array
 */
function getAllProducts() {
    global $conn;

    $sql = "SELECT * FROM products";
    $result = $conn->query($sql);

    return $result->fetch_all(MYSQLI_ASSOC);
}

/**
 * Thêm sản phẩm mới
 * @param Product $product
 * @return bool
 */
function createProduct($product) {
    global $conn;

    $sql = "INSERT INTO products (name, description, price, stock) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdi", $product->name, $product->description, $product->price, $product->stock);

    return $stmt->execute();
}

/**
 * Cập nhật sản phẩm
 * @param Product $product
 * @return bool
 */
function updateProduct($product) {
    global $conn;

    $sql = "UPDATE products SET name = ?, description = ?, price = ?, stock = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdii", $product->name, $product->description, $product->price, $product->stock, $product->id);

    return $stmt->execute();
}

/**
 * Xóa sản phẩm
 * @param int $id
 * @return bool
 */
function deleteProduct($id) {
    global $conn;

    $sql = "DELETE FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    return $stmt->execute();
}

?>
