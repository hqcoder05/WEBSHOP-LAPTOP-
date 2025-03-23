USE webshop_laptop;

CREATE TABLE IF NOT EXISTS payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT UNIQUE, -- Mỗi đơn hàng chỉ có 1 thanh toán
    payment_method ENUM('cash', 'credit_card', 'paypal', 'bank_transfer') NOT NULL, -- Phương thức thanh toán
    amount DECIMAL(10,2) NOT NULL, -- Số tiền thanh toán
    status ENUM('pending', 'completed', 'failed', 'refunded') DEFAULT 'pending', -- Trạng thái thanh toán
    paid_at TIMESTAMP NULL DEFAULT NULL, -- Thời gian thanh toán (nếu đã thanh toán)
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
);
