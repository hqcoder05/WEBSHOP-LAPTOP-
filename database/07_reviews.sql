USE webshop_laptop;

CREATE TABLE IF NOT EXISTS reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT, -- Người đánh giá
    product_id INT, -- Sản phẩm được đánh giá
    rating TINYINT NOT NULL CHECK (rating BETWEEN 1 AND 5), -- Số sao (1 - 5)
    comment TEXT, -- Nội dung đánh giá
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Thời gian đánh giá
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);
