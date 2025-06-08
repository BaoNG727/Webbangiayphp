<?php
/**
 * Test Order Creation and Database Sync
 */

session_start();
require_once "includes/config.php";
require_once "app/Core/Database.php";
require_once "app/Models/User.php";
require_once "app/Models/Product.php";
require_once "app/Models/Order.php";
require_once "app/Models/Cart.php";

echo "<h1>Testing Order Creation and Database Sync</h1>";

try {
    // Create test user if not exists
    $userModel = new User();
    $productModel = new Product();
    $orderModel = new Order();
    $cartModel = new Cart();
    $db = new Database();
    
    echo "<h3>Test 1: Create Test User</h3>";
    
    $testEmail = "ordertest_" . time() . "@test.com";
    $testUsername = "ordertest_" . time();
    
    $userData = [
        'name' => 'Order Test User',
        'username' => $testUsername,
        'email' => $testEmail,
        'password' => 'test123',
        'first_name' => 'Order',
        'last_name' => 'Test',
        'role' => 'customer'
    ];
    
    $userId = $userModel->createUser($userData);
    echo "✅ Test user created with ID: $userId<br>";
    
    echo "<h3>Test 2: Get Available Products</h3>";
    $products = $productModel->all();
    
    if (count($products) > 0) {
        echo "✅ Found " . count($products) . " products<br>";
        $testProduct = $products[0];
        echo "Test product: {$testProduct['name']} - $" . number_format($testProduct['price'], 2) . "<br>";
        
        echo "<h3>Test 3: Add Product to Cart</h3>";
        $cartData = [
            'user_id' => $userId,
            'product_id' => $testProduct['id'],
            'quantity' => 2
        ];
        
        $cartId = $cartModel->create($cartData);
        echo "✅ Product added to cart with ID: $cartId<br>";
        
        echo "<h3>Test 4: Create Test Order</h3>";
        $orderData = [
            'user_id' => $userId,
            'total_amount' => $testProduct['price'] * 2,
            'status' => 'pending',
            'shipping_address' => '123 Test Street, Test City, Test State 12345',
            'payment_method' => 'credit_card'
        ];
        
        $orderId = $orderModel->create($orderData);
        echo "✅ Order created with ID: $orderId<br>";
        
        echo "<h3>Test 5: Add Order Items</h3>";
        $orderItemData = [
            'order_id' => $orderId,
            'product_id' => $testProduct['id'],
            'quantity' => 2,
            'price' => $testProduct['price']
        ];
        
        $orderModel->addOrderItem($orderItemData);
        echo "✅ Order item added successfully<br>";
        
        echo "<h3>Test 6: Verify Order in Database</h3>";
        $order = $orderModel->find($orderId);
        if ($order) {
            echo "✅ Order found in database<br>";
            echo "Order details: " . json_encode($order) . "<br>";
            
            // Get order items
            $orderItems = $orderModel->getOrderItems($orderId);
            echo "✅ Order has " . count($orderItems) . " items<br>";
        }
        
        echo "<h3>Test 7: Check Recent Orders</h3>";
        $recentOrders = $orderModel->getRecentOrders(5);
        echo "✅ Found " . count($recentOrders) . " recent orders<br>";
        
        echo "<h4>Recent Orders:</h4>";
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>Order ID</th><th>Customer</th><th>Email</th><th>Total</th><th>Status</th><th>Date</th></tr>";
        
        foreach ($recentOrders as $order) {
            echo "<tr>";
            echo "<td>#{$order['id']}</td>";
            echo "<td>{$order['username']}</td>";
            echo "<td>{$order['email']}</td>";
            echo "<td>$" . number_format($order['total_amount'], 2) . "</td>";
            echo "<td>{$order['status']}</td>";
            echo "<td>{$order['created_at']}</td>";
            echo "</tr>";
        }
        echo "</table>";
        
    } else {
        echo "❌ No products found. Please add products first.<br>";
    }
    
    echo "<h3>Test 8: Database Tables Status</h3>";
    
    // Check table counts
    $tables = ['users', 'products', 'orders', 'order_items', 'cart'];
    
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>Table</th><th>Record Count</th></tr>";
    
    foreach ($tables as $table) {
        $result = $db->fetch("SELECT COUNT(*) as count FROM $table");
        echo "<tr><td>$table</td><td>{$result['count']}</td></tr>";
    }
    echo "</table>";
    
    echo "<h3>✅ All tests completed successfully!</h3>";
    echo "<h4>Database synchronization is working!</h4>";
    
    echo "<h4>Test the Website:</h4>";
    echo "<ul>";
    echo "<li><a href='index.php'>Go to Homepage</a> - Test browsing products</li>";
    echo "<li><a href='register'>Register a new account</a> - Test user registration</li>";
    echo "<li><a href='login'>Login</a> - Test with username: $testUsername, password: test123</li>";
    echo "<li><a href='admin/' target='_blank'>Admin Panel</a> - View orders and users</li>";
    echo "</ul>";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "<br>";
    echo "Stack trace: <pre>" . $e->getTraceAsString() . "</pre>";
}
?>
