<?php

namespace models;
class OrderDetail
{
    private $conn;
    private $table = 'order_details';

    public $id;
    public $order_id;
    public $product_id;
    public $quantity;
    public $price;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Tạo chi tiết đơn hàng
    public function create()
    {
        $query = "INSERT INTO " . $this->table . " (order_id, product_id, quantity, price) VALUES (:order_id, :product_id, :quantity, :price)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':order_id', $this->order_id);
        $stmt->bindParam(':product_id', $this->product_id);
        $stmt->bindParam(':quantity', $this->quantity);
        $stmt->bindParam(':price', $this->price);

        return $stmt->execute();
    }
}