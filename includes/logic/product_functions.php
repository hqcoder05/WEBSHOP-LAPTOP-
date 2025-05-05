<?php
/**
 * File: product_functions.php
 * Chứa các hàm xử lý liên quan đến sản phẩm
 */
require_once '../../includes/db/database.php';

// Hàm lấy tất cả sản phẩm
function getAllProducts($limit = null, $offset = 0) {
    global $conn;

    $sql = "SELECT p.*, c.name as category_name 
            FROM products p 
            JOIN categories c ON p.category_id = c.id 
            ORDER BY p.created_at DESC";

    if ($limit !== null) {
        $sql .= " LIMIT ?, ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $offset, $limit);
    } else {
        $stmt = $conn->prepare($sql);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    $products = [];
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }

    $stmt->close();
    return $products;
}

// Hàm lấy sản phẩm theo ID
function getProductById($id) {
    global $conn;

    $sql = "SELECT p.*, c.name as category_name 
            FROM products p 
            JOIN categories c ON p.category_id = c.id 
            WHERE p.id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    $stmt->close();
    return $product;
}

// Hàm lấy sản phẩm theo danh mục
function getProductsByCategory($category_id, $limit = null, $offset = 0) {
    global $conn;

    $sql = "SELECT p.*, c.name as category_name 
            FROM products p 
            JOIN categories c ON p.category_id = c.id 
            WHERE p.category_id = ? 
            ORDER BY p.created_at DESC";

    if ($limit !== null) {
        $sql .= " LIMIT ?, ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iii", $category_id, $offset, $limit);
    } else {
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $category_id);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    $products = [];
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }

    $stmt->close();
    return $products;
}

// Hàm tìm kiếm sản phẩm
function searchProducts($keyword) {
    global $conn;

    $search = "%$keyword%";

    $sql = "SELECT p.*, c.name as category_name 
            FROM products p 
            JOIN categories c ON p.category_id = c.id 
            WHERE p.name LIKE ? OR p.description LIKE ? 
            ORDER BY p.created_at DESC";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $search, $search);
    $stmt->execute();

    $result = $stmt->get_result();

    $products = [];
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }

    $stmt->close();
    return $products;
}

// Hàm thêm sản phẩm mới (cho admin)
function addProduct($category_id, $name, $description, $price, $stock, $image) {
    global $conn;

    $sql = "INSERT INTO products (category_id, name, description, price, stock, image) 
            VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issdis", $category_id, $name, $description, $price, $stock, $image);

    $result = $stmt->execute();
    $insert_id = $result ? $conn->insert_id : false;

    $stmt->close();
    return $insert_id;
}

// Hàm cập nhật sản phẩm (cho admin)
function updateProduct($id, $category_id, $name, $description, $price, $stock, $image = null) {
    global $conn;

    if ($image !== null) {
        $sql = "UPDATE products 
                SET category_id = ?, name = ?, description = ?, price = ?, stock = ?, image = ? 
                WHERE id = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issdisi", $category_id, $name, $description, $price, $stock, $image, $id);
    } else {
        $sql = "UPDATE products 
                SET category_id = ?, name = ?, description = ?, price = ?, stock = ? 
                WHERE id = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issdii", $category_id, $name, $description, $price, $stock, $id);
    }

    $result = $stmt->execute();
    $stmt->close();

    return $result;
}

// Hàm xóa sản phẩm (cho admin)
function deleteProduct($id) {
    global $conn;

    $sql = "DELETE FROM products WHERE id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    $result = $stmt->execute();
    $stmt->close();

    return $result;
}

// Hàm lấy tất cả danh mục
function getAllCategories() {
    global $conn;

    $sql = "SELECT * FROM categories ORDER BY name ASC";

    $stmt = $conn->prepare($sql);
    $stmt->execute();

    $result = $stmt->get_result();

    $categories = [];
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }

    $stmt->close();
    return $categories;
}

// Hàm đếm số lượng sản phẩm theo điều kiện tìm kiếm
function countProducts($category_id = null, $keyword = null) {
    global $conn;

    $sql = "SELECT COUNT(*) as total FROM products p";
    $params = [];
    $types = "";

    if ($category_id !== null || $keyword !== null) {
        $sql .= " WHERE ";

        if ($category_id !== null) {
            $sql .= "p.category_id = ?";
            $params[] = $category_id;
            $types .= "i";
        }

        if ($keyword !== null) {
            if ($category_id !== null) {
                $sql .= " AND ";
            }
            $sql .= "(p.name LIKE ? OR p.description LIKE ?)";
            $search = "%$keyword%";
            $params[] = $search;
            $params[] = $search;
            $types .= "ss";
        }
    }

    $stmt = $conn->prepare($sql);

    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }

    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    $stmt->close();
    return $row['total'];
}

// Hàm kiểm tra tình trạng hàng
function checkStock($product_id, $quantity) {
    global $conn;

    $sql = "SELECT stock FROM products WHERE id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();

    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    $stmt->close();

    if (!$product) {
        return false;
    }

    return $product['stock'] >= $quantity;
}

// Hàm giảm số lượng khi đặt hàng
function reduceStock($product_id, $quantity) {
    global $conn;

    $sql = "UPDATE products SET stock = stock - ? WHERE id = ? AND stock >= ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iii", $quantity, $product_id, $quantity);

    $result = $stmt->execute();
    $affected = $stmt->affected_rows;

    $stmt->close();

    return $affected > 0;
}

// Hàm lấy sản phẩm nổi bật (ví dụ: sản phẩm mới nhất)
function getFeaturedProducts($limit = 8) {
    global $conn;

    $sql = "SELECT p.*, c.name as category_name 
            FROM products p 
            JOIN categories c ON p.category_id = c.id 
            ORDER BY p.created_at DESC 
            LIMIT ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $limit);
    $stmt->execute();

    $result = $stmt->get_result();

    $products = [];
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }

    $stmt->close();
    return $products;
}

// Hàm định dạng giá tiền
function formatPrice($price) {
    return number_format($price, 0, ',', '.') . ' ₫';
}
?>