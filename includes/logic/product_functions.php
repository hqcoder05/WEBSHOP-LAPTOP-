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

// Tạo sản phẩm mới
function createProduct($name, $price, $description) {
    $conn = getDatabaseConnection();
    $sql = "INSERT INTO products (name, price, description) VALUES (:name, :price, :description)";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['name' => $name, 'price' => $price, 'description' => $description]);
    return "Sản phẩm mới đã được thêm.";
}

// Đọc thông tin sản phẩm
function readProduct($id) {
    $conn = getDatabaseConnection();
    $sql = "SELECT * FROM products WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['id' => $id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Cập nhật sản phẩm
function updateProduct($id, $name, $price, $description) {
    $conn = getDatabaseConnection();
    $sql = "UPDATE products SET name = :name, price = :price, description = :description WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['id' => $id, 'name' => $name, 'price' => $price, 'description' => $description]);
    return "Sản phẩm đã được cập nhật.";
}

// Xóa sản phẩm
function deleteProduct($id) {
    $conn = getDatabaseConnection();
    $sql = "DELETE FROM products WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['id' => $id]);
    return "Sản phẩm đã được xóa.";
}
?>
