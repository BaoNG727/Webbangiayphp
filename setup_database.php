<?php
/**
 * Database Setup Script
 * Script tự động tạo database và import dữ liệu
 */

echo "<!DOCTYPE html>
<html lang='vi'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Database Setup</title>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>
    <style>
        body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; }
        .card { border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
        .console { background: #000; color: #00ff00; font-family: monospace; padding: 15px; border-radius: 5px; }
    </style>
</head>
<body>
    <div class='container mt-5'>
        <div class='row justify-content-center'>
            <div class='col-md-8'>
                <div class='card'>
                    <div class='card-header bg-dark text-white text-center'>
                        <h3>🗄️ Database Setup - Nike Shoe Store</h3>
                    </div>
                    <div class='card-body'>";

// Database configuration
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'nike_shoe_store';

try {
    // Connect without database first
    $pdo = new PDO("mysql:host=$host;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<div class='alert alert-info'>
        <h5>🔄 Đang thiết lập database...</h5>
    </div>";
    
    echo "<div class='console'>";
    
    // Create database if not exists
    echo "▶ Tạo database '$dbname'...<br>";
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "✅ Database '$dbname' đã được tạo/kiểm tra<br><br>";
    
    // Switch to the database
    $pdo->exec("USE `$dbname`");
    
    // Create users table
    echo "▶ Tạo bảng 'users'...<br>";
    $sql_users = "
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
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    $pdo->exec($sql_users);
    echo "✅ Bảng 'users' đã được tạo<br>";
    
    // Create products table
    echo "▶ Tạo bảng 'products'...<br>";
    $sql_products = "
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
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    $pdo->exec($sql_products);
    echo "✅ Bảng 'products' đã được tạo<br>";
    
    // Create orders table
    echo "▶ Tạo bảng 'orders'...<br>";
    $sql_orders = "
    CREATE TABLE IF NOT EXISTS orders (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT,
        total_amount DECIMAL(10, 2) NOT NULL,
        status ENUM('pending', 'processing', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending',
        shipping_address TEXT,
        payment_method VARCHAR(50),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    $pdo->exec($sql_orders);
    echo "✅ Bảng 'orders' đã được tạo<br>";
    
    // Create order_items table
    echo "▶ Tạo bảng 'order_items'...<br>";
    $sql_order_items = "
    CREATE TABLE IF NOT EXISTS order_items (
        id INT AUTO_INCREMENT PRIMARY KEY,
        order_id INT,
        product_id INT,
        quantity INT NOT NULL,
        price DECIMAL(10, 2) NOT NULL,
        FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
        FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE SET NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    $pdo->exec($sql_order_items);
    echo "✅ Bảng 'order_items' đã được tạo<br>";
    
    // Create cart table
    echo "▶ Tạo bảng 'cart'...<br>";
    $sql_cart = "
    CREATE TABLE IF NOT EXISTS cart (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT,
        product_id INT,
        quantity INT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    $pdo->exec($sql_cart);
    echo "✅ Bảng 'cart' đã được tạo<br><br>";
    
    // Insert admin user if not exists
    echo "▶ Kiểm tra tài khoản admin...<br>";
    $check_admin = $pdo->prepare("SELECT id FROM users WHERE username = 'admin' OR email = 'admin@example.com'");
    $check_admin->execute();
    
    if ($check_admin->rowCount() == 0) {
        $admin_password = password_hash('admin123', PASSWORD_DEFAULT);
        $insert_admin = $pdo->prepare("
            INSERT INTO users (username, email, password, first_name, last_name, role) 
            VALUES ('admin', 'admin@example.com', ?, 'Admin', 'User', 'admin')
        ");
        $insert_admin->execute([$admin_password]);
        echo "✅ Tài khoản admin đã được tạo (admin/admin123)<br>";
    } else {
        echo "✅ Tài khoản admin đã tồn tại<br>";
    }
    
    // Insert sample products if empty
    echo "▶ Kiểm tra sản phẩm mẫu...<br>";
    $check_products = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
    
    if ($check_products == 0) {
        $sample_products = [
            ['Nike Air Max 270', 'The Nike Air Max 270 delivers visible cushioning under every step.', 150.00, 129.99, 'Running', 'nike-air-max-270.jpg', 25],
            ['Nike Air Force 1', 'The radiance lives on in the Nike Air Force 1, the basketball original.', 100.00, 89.99, 'Casual', 'nike-air-force-1.jpg', 40],
            ['Nike Revolution 6', 'Responsive cushioning and padded comfort wrap your foot.', 70.00, 59.99, 'Running', 'nike-revolution-6.jpg', 30],
            ['Nike Blazer Mid', 'In the 70s, Nike was the new shoe on the block.', 100.00, 89.99, 'Casual', 'nike-blazer-mid.jpg', 20]
        ];
        
        $insert_product = $pdo->prepare("
            INSERT INTO products (name, description, price, sale_price, category, image, stock) 
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        
        foreach ($sample_products as $product) {
            $insert_product->execute($product);
        }
        echo "✅ " . count($sample_products) . " sản phẩm mẫu đã được thêm<br>";
    } else {
        echo "✅ Database đã có $check_products sản phẩm<br>";
    }
    
    echo "</div>";
    
    echo "<div class='alert alert-success mt-3'>
        <h5>🎉 Thiết lập database hoàn tất!</h5>
        <hr>
        <h6>Thông tin đăng nhập Admin:</h6>
        <p><strong>Username:</strong> admin<br>
           <strong>Email:</strong> admin@example.com<br>
           <strong>Password:</strong> admin123</p>
        <hr>
        <div class='row'>
            <div class='col-md-6'>
                <a href='/Webgiay/admin/' class='btn btn-primary btn-sm w-100'>
                    🔐 Admin Panel
                </a>
            </div>
            <div class='col-md-6'>
                <a href='/Webgiay/' class='btn btn-success btn-sm w-100'>
                    🏠 Trang Chủ
                </a>
            </div>
        </div>
    </div>";
    
} catch (PDOException $e) {
    echo "<div class='alert alert-danger'>
        <h5>❌ Lỗi thiết lập database!</h5>
        <p><strong>Lỗi:</strong> " . $e->getMessage() . "</p>
        <hr>
        <h6>Hướng dẫn khắc phục:</h6>
        <ol>
            <li>Đảm bảo XAMPP đã khởi động</li>
            <li>Kiểm tra MySQL service đang chạy</li>
            <li>Thử truy cập phpMyAdmin: <a href='http://localhost/phpmyadmin' target='_blank'>http://localhost/phpmyadmin</a></li>
        </ol>
    </div>";
}

echo "
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>";
?>
