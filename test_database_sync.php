<?php
/**
 * Test Database Synchronization
 * Kiểm tra việc đồng bộ dữ liệu giữa website và database
 */

require_once "includes/config.php";
require_once "app/Core/Database.php";

echo "<h2>🔍 Testing Database Synchronization</h2>\n";

try {
    // Test 1: Kiểm tra kết nối database
    echo "<h3>Test 1: Database Connection</h3>\n";
    $db = new Database();
    echo "✅ Database connection successful (using nike_store)<br>\n";
    
    // Test 2: Kiểm tra các bảng cần thiết
    echo "<h3>Test 2: Required Tables Check</h3>\n";
    $required_tables = ['users', 'products', 'cart', 'orders', 'order_items'];
    
    foreach ($required_tables as $table) {
        $result = $pdo->query("SHOW TABLES LIKE '$table'")->fetch();
        if ($result) {
            $count = $pdo->query("SELECT COUNT(*) FROM $table")->fetchColumn();
            echo "✅ Table '$table' exists with $count records<br>\n";
        } else {
            echo "❌ Table '$table' missing<br>\n";
        }
    }
    
    // Test 3: Kiểm tra sản phẩm có sẵn
    echo "<h3>Test 3: Available Products</h3>\n";
    $products = $pdo->query("SELECT id, name, price, stock FROM products WHERE stock > 0 LIMIT 5")->fetchAll();
    
    if (count($products) > 0) {
        echo "✅ Found " . count($products) . " products with stock:<br>\n";
        foreach ($products as $product) {
            echo "- {$product['name']} (Price: {$product['price']}, Stock: {$product['stock']})<br>\n";
        }
    } else {
        echo "❌ No products with stock available<br>\n";
    }
    
    // Test 4: Tạo user test
    echo "<h3>Test 4: Test User Registration</h3>\n";
    $test_email = "test_sync_" . time() . "@test.com";
    $test_username = "test_sync_" . time();
    
    $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
    $result = $stmt->execute([$test_username, $test_email, password_hash('test123', PASSWORD_DEFAULT), 'customer']);
    
    if ($result) {
        $user_id = $pdo->lastInsertId();
        echo "✅ Test user created successfully (ID: $user_id)<br>\n";
        
        // Test 5: Tạo order test
        echo "<h3>Test 5: Test Order Creation</h3>\n";
        
        // Tạo order
        $stmt = $pdo->prepare("INSERT INTO orders (user_id, total_amount, status, shipping_address, payment_method) VALUES (?, ?, ?, ?, ?)");
        $order_result = $stmt->execute([$user_id, 150.00, 'pending', 'Test Address 123', 'cash']);
        
        if ($order_result) {
            $order_id = $pdo->lastInsertId();
            echo "✅ Test order created successfully (ID: $order_id)<br>\n";
            
            // Thêm order items
            if (count($products) > 0) {
                $product = $products[0];
                $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price, subtotal) VALUES (?, ?, ?, ?, ?)");
                $item_result = $stmt->execute([$order_id, $product['id'], 1, $product['price'], $product['price']]);
                
                if ($item_result) {
                    echo "✅ Test order item added successfully<br>\n";
                } else {
                    echo "❌ Failed to add order item<br>\n";
                }
            }
        } else {
            echo "❌ Failed to create test order<br>\n";
        }
        
        // Test 6: Kiểm tra dữ liệu trong admin
        echo "<h3>Test 6: Admin Panel Data Check</h3>\n";
        $order_count = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
        $customer_count = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'customer'")->fetchColumn();
        $product_count = $pdo->query("SELECT COUNT(*) FROM products WHERE stock > 0")->fetchColumn();
        
        echo "✅ Total orders in database: $order_count<br>\n";
        echo "✅ Total customers in database: $customer_count<br>\n";
        echo "✅ Total products with stock: $product_count<br>\n";
        
    } else {
        echo "❌ Failed to create test user<br>\n";
    }
    
    echo "<h3>✅ Database Synchronization Test Complete!</h3>\n";
    echo "<p><strong>Next Steps:</strong></p>\n";
    echo "<ul>\n";
    echo "<li>Visit <a href='index.php'>website homepage</a> to test user registration</li>\n";
    echo "<li>Try creating an account and placing an order</li>\n";
    echo "<li>Check <a href='admin/'>admin panel</a> to see if data appears</li>\n";
    echo "</ul>\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "<br>\n";
}
?>
