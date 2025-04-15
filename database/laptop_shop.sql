-- Tạo database
CREATE DATABASE IF NOT EXISTS laptop_shop;
USE laptop_shop;

-- Bảng users (đồng bộ với User.php)
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Bảng categories
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Bảng products
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    stock INT NOT NULL,
    image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);

-- Bảng carts
CREATE TABLE carts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Bảng cart_items
CREATE TABLE cart_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cart_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (cart_id) REFERENCES carts(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Bảng orders
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    total_amount DECIMAL(10, 2) NOT NULL,
    status ENUM('pending', 'processing', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Bảng order_details
CREATE TABLE order_details (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Bảng order_addresses
CREATE TABLE order_addresses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    address VARCHAR(255) NOT NULL,
    city VARCHAR(100) NOT NULL,
    postal_code VARCHAR(20),
    country VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
);

-- Bảng invoices
CREATE TABLE invoices (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    total_amount DECIMAL(10, 2) NOT NULL,
    issued_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('unpaid', 'paid', 'cancelled') DEFAULT 'unpaid',
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
);

-- Chèn dữ liệu mẫu
-- users (đồng bộ với User.php)
INSERT INTO users (username, password, email) VALUES
    ('admin', '$2y$10$8J9zX8yZ3tQwX7mZ5vN2.uK6lQz9jP8kL3rW4tY5uX6vZ7wY8x9A0', 'admin@laptopshop.com'),
    ('user1', '$2y$10$9K0yA9zX4uRxY8nZ6wO3.vL7mRz0kQ9lM4tX5uY6vZ8wA0xB1yC2D', 'user1@laptopshop.com');

-- categories
INSERT INTO categories (name, description) VALUES
    ('Laptop Gaming', 'Laptop hiệu năng cao cho chơi game'),
    ('Laptop Văn phòng', 'Laptop nhẹ, phù hợp công việc văn phòng');

-- products
INSERT INTO products (category_id, name, description, price, stock, image) VALUES
    (1, 'Laptop Gaming A', 'Laptop với GPU RTX 3060, RAM 16GB', 25000.00, 10, 'laptop_a.jpg'),
    (2, 'Laptop Văn phòng B', 'Laptop mỏng nhẹ, pin 10h', 15000.00, 20, 'laptop_b.jpg');

-- carts
INSERT INTO carts (user_id) VALUES
    (2);

-- cart_items
INSERT INTO cart_items (cart_id, product_id, quantity) VALUES
    (1, 1, 2);

-- orders
INSERT INTO orders (user_id, total_amount, status) VALUES
    (2, 50000.00, 'pending');

-- order_details
INSERT INTO order_details (order_id, product_id, quantity, price) VALUES
    (1, 1, 2, 25000.00);

-- order_addresses
INSERT INTO order_addresses (order_id, address, city, postal_code, country) VALUES
    (1, '123 Đường Láng', 'Hà Nội', '100000', 'Việt Nam');

-- invoices
INSERT INTO invoices (order_id, total_amount, status) VALUES
    (1, 50000.00, 'unpaid');

ALTER TABLE users
ADD role ENUM('user', 'admin') DEFAULT 'user';

-- Gán role admin cho username: admin
UPDATE users
SET role = 'admin'
WHERE username = 'admin';

-- Kiểm tra dữ liệu
SELECT * FROM users;