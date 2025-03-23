USE webshop_laptop;

CREATE TABLE IF NOT EXISTS order_details (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT, -- Liên kết với bảng orders
    product_id INT, -- Liên kết với bảng products
    quantity INT NOT NULL CHECK (quantity > 0), -- Số lượng sản phẩm
    price DECIMAL(10,2) NOT NULL, -- Giá sản phẩm tại thời điểm mua
    subtotal DECIMAL(10,2) GENERATED ALWAYS AS (quantity * price) STORED, -- Tổng tiền sản phẩm (auto tính)
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);
