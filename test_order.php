<?php
// Test order creation with correct database columns
session_start();
require_once __DIR__ . '/app/Core/Database.php';
require_once __DIR__ . '/app/Core/Model.php';
require_once __DIR__ . '/app/Models/Order.php';

echo "<h2>Order Creation Test</h2>";

try {
    $orderModel = new Order();
    
    // Test order data with correct column names
    $orderData = [
        'user_id' => 9,
        'total_amount' => 99.99,
        'status' => 'pending',
        'name' => 'Test User',
        'address' => '123 Test Street',
        'city' => 'Test City, TS 12345, US',
        'phone' => '123-456-7890',
        'notes' => 'Payment method: test'
    ];
    
    echo "<h3>Attempting to create order with data:</h3>";
    echo "<pre>" . print_r($orderData, true) . "</pre>";
    
    $orderId = $orderModel->createOrder($orderData);
    
    if ($orderId) {
        echo "<p style='color: green;'>✅ Order created successfully! Order ID: " . $orderId . "</p>";
        
        // Test retrieving the order
        $order = $orderModel->find($orderId);
        echo "<h3>Retrieved order:</h3>";
        echo "<pre>" . print_r($order, true) . "</pre>";
    } else {
        echo "<p style='color: red;'>❌ Failed to create order</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Exception: " . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
?>
