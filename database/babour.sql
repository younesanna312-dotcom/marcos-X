CREATE DATABASE IF NOT EXISTS babour_shop CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE babour_shop;

CREATE TABLE users (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 username VARCHAR(32) NOT NULL UNIQUE,
 email VARCHAR(120) NOT NULL UNIQUE,
 password VARCHAR(255) NOT NULL,
 balance DECIMAL(12,2) NOT NULL DEFAULT 0,
 role ENUM('user','admin') NOT NULL DEFAULT 'user',
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE products (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 name VARCHAR(120) NOT NULL,
 category ENUM('vehicle','house','toy','biz','admin') NOT NULL,
 description TEXT,
 price DECIMAL(12,2) NOT NULL DEFAULT 0,
 image VARCHAR(500) DEFAULT '',
 active TINYINT(1) NOT NULL DEFAULT 1,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE orders (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 user_id INT UNSIGNED NOT NULL,
 total DECIMAL(12,2) NOT NULL,
 status VARCHAR(30) NOT NULL DEFAULT 'paid',
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE order_items (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 order_id BIGINT UNSIGNED NOT NULL,
 product_id INT UNSIGNED NOT NULL,
 price DECIMAL(12,2) NOT NULL,
 FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
 FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE RESTRICT
);

INSERT INTO products(name,category,description,price,image) VALUES
('Premium Vehicle Pack','vehicle','Exclusive vehicle product.',500,''),
('Luxury House','house','Premium house listing.',1000,''),
('Toy Bundle','toy','Collection of exclusive toys.',250,''),
('Business Package','biz','Premium business product.',1500,''),
('VIP Admin Package','admin','Website store admin product.',2000,'');
