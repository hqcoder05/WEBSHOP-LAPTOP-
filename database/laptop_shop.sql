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

-- Gán role admin cho username: admin
UPDATE users SET role = 'admin' WHERE username = 'admin';

INSERT INTO categories (name, description) VALUES
    ('DELL', 'Laptop thương hiệu DELL với hiệu năng mạnh mẽ'),
    ('Lenovo', 'Laptop Lenovo với thiết kế tinh tế, phù hợp công việc'),
    ('HP', 'Laptop HP với cấu hình đa dạng và độ bền cao'),
    ('Macbook', 'Laptop Macbook của Apple, mỏng nhẹ, hiệu suất cao');
    
INSERT INTO products (category_id, name, description, price, stock, image) VALUES
((SELECT id FROM categories WHERE name = 'Lenovo'), 'Lenovo ThinkPad X1 Carbon', 'Ultrabook cao cấp, i7, 16GB RAM, 512GB SSD', 32000.00, 10, 'https://www.lenovo.com/medias/thinkpad-x1-carbon-gen10-hero.png'),
((SELECT id FROM categories WHERE name = 'Lenovo'), 'Lenovo IdeaPad 3', 'Laptop phổ thông, i3, 8GB RAM, 256GB SSD', 14000.00, 25, 'https://www.lenovo.com/medias/ideapad-3-15-hero.png'),
((SELECT id FROM categories WHERE name = 'Lenovo'), 'Lenovo Legion 5', 'Laptop gaming, Ryzen 7, 16GB RAM, RTX 3050', 28000.00, 12, 'https://www.lenovo.com/medias/legion-5-15-hero.png'),
((SELECT id FROM categories WHERE name = 'Lenovo'), 'Lenovo Yoga Slim 7', 'Laptop thời trang, i5, 8GB RAM, 512GB SSD', 23000.00, 18, 'https://www.lenovo.com/medias/yoga-slim-7-hero.png'),
((SELECT id FROM categories WHERE name = 'Lenovo'), 'Lenovo ThinkBook 14', 'Laptop doanh nhân, i5, 16GB RAM, 512GB SSD', 21000.00, 15, 'https://www.lenovo.com/medias/thinkbook-14-hero.png'),
((SELECT id FROM categories WHERE name = 'Lenovo'), 'Lenovo V14', 'Laptop học tập, Ryzen 3, 8GB RAM, 256GB SSD', 13000.00, 20, 'https://www.lenovo.com/medias/v14-ada-hero.png'),
((SELECT id FROM categories WHERE name = 'Lenovo'), 'Lenovo ThinkPad E15', 'Laptop doanh nghiệp, i5, 8GB RAM, 256GB SSD', 19000.00, 14, 'https://www.lenovo.com/medias/thinkpad-e15-hero.png'),
((SELECT id FROM categories WHERE name = 'Lenovo'), 'Lenovo Flex 5', 'Laptop cảm ứng, Ryzen 5, 16GB RAM, 512GB SSD', 24000.00, 10, 'https://www.lenovo.com/medias/flex-5-hero.png');

INSERT INTO products (category_id, name, description, price, stock, image) VALUES
((SELECT id FROM categories WHERE name = 'HP'), 'HP Pavilion 15', 'Laptop phổ thông, i5, 8GB RAM, 512GB SSD', 21000.00, 20, 'https://ssl-product-images.www8-hp.com/digmedialib/prodimg/lowres/c07715009.png'),
((SELECT id FROM categories WHERE name = 'HP'), 'HP Envy 13', 'Ultrabook sang trọng, i7, 16GB RAM, 512GB SSD', 28000.00, 12, 'https://ssl-product-images.www8-hp.com/digmedialib/prodimg/lowres/c07715010.png'),
((SELECT id FROM categories WHERE name = 'HP'), 'HP Omen 16', 'Laptop gaming, i7, 32GB RAM, RTX 3060', 37000.00, 8, 'https://ssl-product-images.www8-hp.com/digmedialib/prodimg/lowres/c07715011.png'),
((SELECT id FROM categories WHERE name = 'HP'), 'HP EliteBook 840', 'Laptop doanh nhân, i5, 16GB RAM, 512GB SSD', 26000.00, 15, 'https://ssl-product-images.www8-hp.com/digmedialib/prodimg/lowres/c07715012.png'),
((SELECT id FROM categories WHERE name = 'HP'), 'HP Spectre x360', 'Laptop cảm ứng 2-in-1, i7, 16GB RAM, 1TB SSD', 34000.00, 10, 'https://ssl-product-images.www8-hp.com/digmedialib/prodimg/lowres/c07715013.png'),
((SELECT id FROM categories WHERE name = 'HP'), 'HP 245 G8', 'Laptop học tập, Ryzen 5, 8GB RAM, 256GB SSD', 16000.00, 22, 'https://ssl-product-images.www8-hp.com/digmedialib/prodimg/lowres/c07715014.png'),
((SELECT id FROM categories WHERE name = 'HP'), 'HP ProBook 450', 'Laptop văn phòng, i5, 8GB RAM, 512GB SSD', 22000.00, 18, 'https://ssl-product-images.www8-hp.com/digmedialib/prodimg/lowres/c07715015.png'),
((SELECT id FROM categories WHERE name = 'HP'), 'HP ZBook Firefly 14', 'Laptop đồ họa, i7, 16GB RAM, 512GB SSD', 36000.00, 6, 'https://ssl-product-images.www8-hp.com/digmedialib/prodimg/lowres/c07715016.png');

INSERT INTO products (category_id, name, description, price, stock, image) VALUES
((SELECT id FROM categories WHERE name = 'Macbook'), 'MacBook Air M1', 'Macbook Air với chip M1, 8GB RAM, 256GB SSD', 28000.00, 15, 'https://store.storeimages.cdn-apple.com/4982/as-images.apple.com/is/macbook-air-m1-hero'),
((SELECT id FROM categories WHERE name = 'Macbook'), 'MacBook Pro M1 13"', 'Macbook Pro 13 inch M1, 8GB RAM, 512GB SSD', 36000.00, 10, 'https://store.storeimages.cdn-apple.com/4982/as-images.apple.com/is/macbook-pro-13-m1-hero'),
((SELECT id FROM categories WHERE name = 'Macbook'), 'MacBook Pro M2 14"', 'Macbook Pro 14 inch M2, 16GB RAM, 512GB SSD', 48000.00, 8, 'https://store.storeimages.cdn-apple.com/4982/as-images.apple.com/is/macbook-pro-14-hero'),
((SELECT id FROM categories WHERE name = 'Macbook'), 'MacBook Air M2', 'Macbook Air M2, 8GB RAM, 256GB SSD', 32000.00, 12, 'https://store.storeimages.cdn-apple.com/4982/as-images.apple.com/is/macbook-air-15-hero'),
((SELECT id FROM categories WHERE name = 'Macbook'), 'MacBook Pro 16" M2 Pro', 'Macbook Pro 16 inch M2 Pro, 32GB RAM, 1TB SSD', 68000.00, 5, 'https://store.storeimages.cdn-apple.com/4982/as-images.apple.com/is/macbook-pro-16-hero'),
((SELECT id FROM categories WHERE name = 'Macbook'), 'MacBook Air 2020 Intel', 'Macbook Air chip Intel, 8GB RAM, 256GB SSD', 24000.00, 20, 'https://store.storeimages.cdn-apple.com/4982/as-images.apple.com/is/macbook-air-2020-intel-hero'),
((SELECT id FROM categories WHERE name = 'Macbook'), 'MacBook Pro 2020 Intel', 'Macbook Pro 13 inch Intel, 16GB RAM, 512GB SSD', 30000.00, 10, 'https://store.storeimages.cdn-apple.com/4982/as-images.apple.com/is/macbook-pro-2020-intel'),
((SELECT id FROM categories WHERE name = 'Macbook'), 'MacBook 12"', 'Macbook 12 inch, 8GB RAM, 256GB SSD (2017)', 18000.00, 6, 'https://store.storeimages.cdn-apple.com/4982/as-images.apple.com/is/macbook-12-2017-hero');

INSERT INTO products (category_id, name, description, price, stock, image) VALUES
((SELECT id FROM categories WHERE name = 'Dell'), 'Dell XPS 13', 'Ultrabook cao cấp, i7, 16GB RAM, 512GB SSD', 35000.00, 10, 'https://i.dell.com/sites/csimages/Master_Imagery/all/xps-13-9300-laptop.jpg'),
((SELECT id FROM categories WHERE name = 'Dell'), 'Dell Inspiron 15', 'Laptop phổ thông, i5, 8GB RAM, 512GB SSD', 21000.00, 20, 'https://i.dell.com/sites/csimages/Master_Imagery/all/inspiron-15-5502-laptop.jpg'),
((SELECT id FROM categories WHERE name = 'Dell'), 'Dell G15 Gaming', 'Laptop gaming, i7, 16GB RAM, RTX 3050', 30000.00, 12, 'https://i.dell.com/sites/csimages/Video_Imagery/all/g15-gaming-laptop.jpg'),
((SELECT id FROM categories WHERE name = 'Dell'), 'Dell Latitude 3420', 'Laptop văn phòng, i5, 8GB RAM, 256GB SSD', 19000.00, 15, 'https://i.dell.com/sites/csimages/Video_Imagery/all/latitude-3420-laptop.jpg'),
((SELECT id FROM categories WHERE name = 'Dell'), 'Dell Vostro 3400', 'Laptop doanh nhân, i3, 8GB RAM, 256GB SSD', 17000.00, 18, 'https://i.dell.com/sites/csimages/Video_Imagery/all/vostro-3400.jpg'),
((SELECT id FROM categories WHERE name = 'Dell'), 'Dell Precision 5560', 'Laptop đồ họa, i9, 32GB RAM, 1TB SSD', 50000.00, 5, 'https://i.dell.com/sites/csimages/Video_Imagery/all/precision-5560.jpg'),
((SELECT id FROM categories WHERE name = 'Dell'), 'Dell XPS 15', 'Ultrabook màn hình lớn, i7, 16GB RAM, 1TB SSD', 42000.00, 8, 'https://i.dell.com/sites/csimages/Video_Imagery/all/xps-15-9500.jpg'),
((SELECT id FROM categories WHERE name = 'Dell'), 'Dell Alienware m15 R6', 'Laptop gaming cao cấp, i7, RTX 3070, 32GB RAM', 58000.00, 4, 'https://i.dell.com/sites/csimages/Video_Imagery/all/alienware-m15-r6.jpg');
