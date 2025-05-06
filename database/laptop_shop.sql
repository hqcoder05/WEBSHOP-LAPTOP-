CREATE DATABASE IF NOT EXISTS laptop_shop;
USE laptop_shop;

-- Bảng users
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

-- Bảng orders
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    total_price DECIMAL(10, 2) NOT NULL,
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
    full_name VARCHAR(255) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    address VARCHAR(255) NOT NULL,
    note TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
);

-- Bảng invoices
CREATE TABLE invoices (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    total_amount DECIMAL(10, 2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('unpaid', 'paid', 'cancelled') DEFAULT 'unpaid',
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
);

-- Gán role admin cho username: admin
UPDATE users SET role = 'admin' WHERE username = 'admin';

-- Dữ liệu mẫu cho bảng categories
INSERT INTO categories (name, description) VALUES
    ('DELL', 'Laptop thương hiệu DELL với hiệu năng mạnh mẽ'),
    ('Lenovo', 'Laptop Lenovo với thiết kế tinh tế, phù hợp công việc'),
    ('HP', 'Laptop HP với cấu hình đa dạng và độ bền cao'),
    ('Macbook', 'Laptop Macbook của Apple, mỏng nhẹ, hiệu suất cao');

-- Dữ liệu mẫu cho bảng products
CREATE DATABASE IF NOT EXISTS laptop_shop;
USE laptop_shop;

-- Bảng users
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

-- Bảng orders
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    total_price DECIMAL(10, 2) NOT NULL,
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
    full_name VARCHAR(255) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    address VARCHAR(255) NOT NULL,
    note TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
);

-- Bảng invoices
CREATE TABLE invoices (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    total_amount DECIMAL(10, 2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('unpaid', 'paid', 'cancelled') DEFAULT 'unpaid',
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
);

-- Gán role admin cho username: admin
UPDATE users SET role = 'admin' WHERE username = 'admin';

-- Dữ liệu mẫu cho bảng categories
INSERT INTO categories (name, description) VALUES
    ('DELL', 'Laptop thương hiệu DELL với hiệu năng mạnh mẽ'),
    ('Lenovo', 'Laptop Lenovo với thiết kế tinh tế, phù hợp công việc'),
    ('HP', 'Laptop HP với cấu hình đa dạng và độ bền cao'),
    ('Macbook', 'Laptop Macbook của Apple, mỏng nhẹ, hiệu suất cao');

-- Dữ liệu mẫu cho bảng products
INSERT INTO products (id, category_id, name, description, price, stock, image, created_at) VALUES
(1, 2, 'Lenovo ThinkPad X1 Carbon', 'Ultrabook cao cấp, i7, 16GB RAM, 512GB SSD', 50000000.00, 10, '../../assets/images/laptop/ThinkPad_X1_Carbon_Gen_11.png', '2025-04-26 07:57:00'),
(2, 2, 'Lenovo IdeaPad 3', 'Laptop phổ thông, i3, 8GB RAM, 256GB SSD', 50000000.00, 25, '../../assets/images/laptop/Lenovo_IdeaPad_3_15IAU7.jpg', '2025-04-26 07:57:00'),
(3, 2, 'Lenovo Legion 5', 'Laptop gaming, Ryzen 7, 16GB RAM, RTX 3050', 50000000.00, 12, '../../assets/images/laptop/legion-5-15-hero.png', '2025-04-26 07:57:00'),
(4, 2, 'Lenovo Yoga Slim 7', 'Laptop thời trang, i5, 8GB RAM, 512GB SSD', 50000000.00, 18, '../../assets/images/laptop/yoga-slim-7-hero.png', '2025-04-26 07:57:00'),
(5, 2, 'Lenovo ThinkBook 14', 'Laptop doanh nhân, i5, 16GB RAM, 512GB SSD', 50000000.00, 15, '../../assets/images/laptop/thinkbook-14-hero.png', '2025-04-26 07:57:00'),
(6, 2, 'Lenovo V14', 'Laptop học tập, Ryzen 3, 8GB RAM, 256GB SSD', 50000000.00, 20, '../../assets/images/laptop/V14.png', '2025-04-26 07:57:00'),
(7, 2, 'Lenovo ThinkPad E15', 'Laptop doanh nghiệp, i5, 8GB RAM, 256GB SSD', 50000000.00, 14, '../../assets/images/laptop/E15.png', '2025-04-26 07:57:00'),
(8, 2, 'Lenovo Flex 5', 'Laptop cảm ứng, Ryzen 5, 16GB RAM, 512GB SSD', 50000000.00, 10, '../../assets/images/laptop/flex-5-hero.png', '2025-04-26 07:57:00'),
(9, 3, 'HP Pavilion 15', 'Laptop phổ thông, i5, 8GB RAM, 512GB SSD', 50000000.00, 20, '../../assets/images/laptop/c07715009.png', '2025-04-26 07:57:39'),
(10, 3, 'HP Envy 13', 'Ultrabook sang trọng, i7, 16GB RAM, 512GB SSD', 50000000.00, 12, '../../assets/images/laptop/c07715010.png', '2025-04-26 07:57:39'),
(11, 3, 'HP Omen 16', 'Laptop gaming, i7, 32GB RAM, RTX 3060', 50000000.00, 8, '../../assets/images/laptop/c07715011.png', '2025-04-26 07:57:39'),
(12, 3, 'HP EliteBook 840', 'Laptop doanh nhân, i5, 16GB RAM, 512GB SSD', 50000000.00, 15, '../../assets/images/laptop/c07715012.png', '2025-04-26 07:57:39'),
(13, 3, 'HP 245 G8', 'Laptop học tập, Ryzen 5, 8GB RAM, 256GB SSD', 50000000.00, 22, '../../assets/images/laptop/v14-ada-hero.png', '2025-04-26 07:57:39'),
(14, 3, 'HP ProBook 450', 'Laptop văn phòng, i5, 8GB RAM, 512GB SSD', 50000000.00, 18, '../../assets/images/laptop/c07715014.png', '2025-04-26 07:57:39'),
(15, 3, 'HP ZBook Firefly 14', 'Laptop đồ họa, i7, 16GB RAM, 512GB SSD', 50000000.00, 6, '../../assets/images/laptop/c07715015.png', '2025-04-26 07:57:39'),
(16, 3, 'HP Spectre x360', 'Laptop cảm ứng 2-in-1, i7, 16GB RAM, 1TB SSD', 50000000.00, 10, '../../assets/images/laptop/c07715016.png', '2025-04-26 07:57:39'),
(17, 4, 'MacBook Air M1', 'Macbook Air với chip M1, 8GB RAM, 256GB SSD', 50000000.00, 15, '../../assets/images/laptop/Laptop_MacBook_Air_13_inch_M1.jpg', '2025-04-26 07:57:50'),
(18, 4, 'MacBook Pro M1 13"', 'Macbook Pro 13 inch M1, 8GB RAM, 512GB SSD', 50000000.00, 10, '../../assets/images/laptop/Laptop_Apple_MacBook_Pro_13_inch_M1.jpg', '2025-04-26 07:57:50'),
(19, 4, 'MacBook Pro M2 14"', 'Macbook Pro 14 inch M2, 16GB RAM, 512GB SSD', 50000000.00, 8, '../../assets/images/laptop/Laptop_MacBook_Pro_14_inch_M4.jpg', '2025-04-26 07:57:50'),
(20, 4, 'MacBook Air M2', 'Macbook Air M2, 8GB RAM, 256GB SSD', 50000000.00, 12, '../../assets/images/laptop/Laptop_MacBook_Air_13_inch_M2.jpg', '2025-04-26 07:57:50'),
(21, 4, 'MacBook Pro 16" M2 Pro', 'Macbook Pro 16 inch M2 Pro, 32GB RAM, 1TB SSD', 50000000.00, 5, '../../assets/images/laptop/Apple_M2_Pro_16.webp', '2025-04-26 07:57:50'),
(22, 4, 'MacBook Air 2020 Intel', 'Macbook Air chip Intel, 8GB RAM, 256GB SSD', 50000000.00, 20, '../../assets/images/laptop/macbook-air-gold-select-201810_4_7_1_1.webp', '2025-04-26 07:57:50'),
(23, 4, 'MacBook Pro 2020 Intel', 'Macbook Pro 13 inch Intel, 16GB RAM, 512GB SSD', 50000000.00, 10, '../../assets/images/laptop/macbook-pro-2020-intel.jpg', '2025-04-26 07:57:50'),
(24, 4, 'MacBook 12"', 'Macbook 12 inch, 8GB RAM, 256GB SSD (2017)', 50000000.00, 6, '../../assets/images/laptop/macbook-12-2017-hero.jpg', '2025-04-26 07:57:50'),
(25, 1, 'Dell XPS 13', 'Ultrabook cao cấp, i7, 16GB RAM, 512GB SSD', 50000000.00, 10, '../../assets/images/laptop/Dell_XPS_13.webp', '2025-04-26 07:58:15'),
(26, 1, 'Dell Inspiron 15', 'Laptop phổ thông, i5, 8GB RAM, 512GB SSD', 50000000.00, 20, '../../assets/images/laptop/Dell_Inspiron_15.webp', '2025-04-26 07:58:15'),
(27, 1, 'Dell G15 Gaming', 'Laptop gaming, i7, 16GB RAM, RTX 3050', 50000000.00, 12, '../../assets/images/laptop/Dell_G15.webp', '2025-04-26 07:58:15'),
(28, 1, 'Dell Latitude 3420', 'Laptop văn phòng, i5, 8GB RAM, 256GB SSD', 50000000.00, 15, '../../assets/images/laptop/Dell_Latitude_3420.jpeg', '2025-04-26 07:58:15'),
(29, 1, 'Dell Vostro 3400', 'Laptop doanh nhân, i3, 8GB RAM, 256GB SSD', 50000000.00, 18, '../../assets/images/laptop/vostro-3400.jpg', '2025-04-26 07:58:15'),
(30, 1, 'Dell Precision 5560', 'Laptop đồ họa, i9, 32GB RAM, 1TB SSD', 50000000.00, 5, '../../assets/images/laptop/precision5560.jpg', '2025-04-26 07:58:15'),
(31, 1, 'Dell XPS 15', 'Ultrabook màn hình lớn, i7, 16GB RAM, 1TB SSD', 50000000.00, 8, '../../assets/images/laptop/dell-xps-15-9530-1.jpg', '2025-04-26 07:58:15'),
(32, 1, 'Dell Alienware m15 R6', 'Laptop gaming cao cấp, i7, RTX 3070, 32GB RAM', 50000000.00, 4, '../../assets/images/laptop/alienware-m15-r6.jpg', '2025-04-26 07:58:15');


CREATE TABLE comments (
  id INT AUTO_INCREMENT PRIMARY KEY,
  product_id INT NOT NULL,
  user_name VARCHAR(255) NOT NULL,
  comment TEXT NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (product_id) REFERENCES products(id)
);

ALTER TABLE comments
ADD COLUMN user_id INT,
MODIFY user_name VARCHAR(255) NULL,
ADD FOREIGN KEY (user_id) REFERENCES users(id);

CREATE TABLE contacts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    subject VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE comments (
  id INT AUTO_INCREMENT PRIMARY KEY,
  product_id INT NOT NULL,
  user_name VARCHAR(255) NOT NULL,
  comment TEXT NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (product_id) REFERENCES products(id)
);

ALTER TABLE comments
ADD COLUMN user_id INT,
MODIFY user_name VARCHAR(255) NULL,
ADD FOREIGN KEY (user_id) REFERENCES users(id);

CREATE TABLE contacts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    subject VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
