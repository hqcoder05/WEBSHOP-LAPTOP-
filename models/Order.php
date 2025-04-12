<?php
class Order {
    private $conn;
    private $table = 'orders';

    public $id;
    public $user_id;
    public $total_price;
    public $status;
    public $created_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Tạo đơn hàng
    public function create() {
        $query = "INSERT INTO " . $this->table . " (user_id, total_price, status) VALUES (:user_id, :total_price, :status)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->bindParam(':total_price', $this->total_price);
        $stmt->bindParam(':status', $this->status);

        return $stmt->execute();
    }

    // Cập nhật tổng tiền đơn hàng
    public function updateTotalPrice() {
        $query = "UPDATE " . $this->table . " SET total_price = :total_price WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':total_price', $this->total_price);
        $stmt->bindParam(':id', $this->id);

        return $stmt->execute();
    }
}