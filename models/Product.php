<?php

namespace models;
use Database;

class Product
{
    private $conn;
    private $table_name = "products"; // Tên bảng sản phẩm

    public $id;
    public $name;
    public $description;
    public $price;
    public $category_id;
    public $created;

    // Hàm khởi tạo để kết nối cơ sở dữ liệu
    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Lấy tất cả các sản phẩm
    public function readAll()
    {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy một sản phẩm cụ thể bằng ID
    public function readOne($id)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Tạo sản phẩm mới
    public function create($data)
    {
        $query = "INSERT INTO " . $this->table_name . " SET name=:name, description=:description, price=:price, category_id=:category_id, created=:created";
        $stmt = $this->conn->prepare($query);

        // Bind các tham số
        $stmt->bindParam(":name", $data->name);
        $stmt->bindParam(":description", $data->description);
        $stmt->bindParam(":price", $data->price);
        $stmt->bindParam(":category_id", $data->category_id);
        $stmt->bindParam(":created", $data->created);

        // Thực thi câu lệnh và kiểm tra kết quả
        if ($stmt->execute()) {
            return array("message" => "Product created successfully");
        } else {
            return array("message" => "Product creation failed");
        }
    }

    // Cập nhật sản phẩm hiện có
    public function update($data)
    {
        $query = "UPDATE " . $this->table_name . " SET name=:name, description=:description, price=:price, category_id=:category_id WHERE id=:id";
        $stmt = $this->conn->prepare($query);

        // Bind các tham số
        $stmt->bindParam(":name", $data->name);
        $stmt->bindParam(":description", $data->description);
        $stmt->bindParam(":price", $data->price);
        $stmt->bindParam(":category_id", $data->category_id);
        $stmt->bindParam(":id", $data->id);

        // Thực thi câu lệnh và kiểm tra kết quả
        if ($stmt->execute()) {
            return array("message" => "Product updated successfully");
        } else {
            return array("message" => "Product update failed");
        }
    }

    // Xóa sản phẩm
    public function delete($id)
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);

        // Thực thi câu lệnh và kiểm tra kết quả
        if ($stmt->execute()) {
            return array("message" => "Product deleted successfully");
        } else {
            return array("message" => "Product deletion failed");
        }
    }
}

?>