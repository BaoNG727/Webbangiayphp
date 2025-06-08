-- Create Database
CREATE DATABASE IF NOT EXISTS shoe_store;
USE shoe_store;

-- Create Products Table
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    sale_price DECIMAL(10, 2),
    category VARCHAR(100),
    image VARCHAR(255),
    stock INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Create Users Table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    first_name VARCHAR(100),
    last_name VARCHAR(100),
    address TEXT,
    phone VARCHAR(20),
    role ENUM('admin', 'customer') DEFAULT 'customer',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create Orders Table
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    total_amount DECIMAL(10, 2) NOT NULL,
    status ENUM('pending', 'processing', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending',
    shipping_address TEXT,
    payment_method VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Create Order Items Table
CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT,
    product_id INT,
    quantity INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE SET NULL
);

-- Create Cart Table
CREATE TABLE IF NOT EXISTS cart (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    product_id INT,
    quantity INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Insert Sample Admin User (password: admin123)
INSERT IGNORE INTO users (username, email, password, first_name, last_name, role) 
VALUES ('admin', 'admin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin', 'User', 'admin');

-- Insert Sample Products
INSERT INTO products (name, description, price, sale_price, category, image, stock) VALUES
('Nike Air Max 270', 'The Nike Air Max 270 delivers visible cushioning under every step. Highlighted by the first-ever Max Air unit created specifically for Nike Sportswear, it features the greatest heel volume displacement for maximum impact protection.', 150.00, 129.99, 'Running', 'nike-air-max-270.jpg', 25),
('Nike Air Force 1', 'The radiance lives on in the Nike Air Force 1, the basketball original that puts a fresh spin on what you know best: crisp leather, bold colors and the perfect amount of flash to make you shine.', 100.00, 89.99, 'Casual', 'nike-air-force-1.jpg', 40),
('Nike Revolution 6', 'Responsive cushioning and padded comfort wrap your foot for a supported run, while the lightweight, breathable design is made with at least 20% recycled material by weight.', 70.00, 59.99, 'Running', 'nike-revolution-6.jpg', 30),
('Nike Blazer Mid', 'In the \'70s, Nike was the new shoe on the block. So new, in fact, we were still testing prototypes on elite runners. Of course, the design improved over the years, but the name stuck.', 100.00, 89.99, 'Casual', 'nike-blazer-mid.jpg', 20);
