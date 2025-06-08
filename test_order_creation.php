<?php
require_once 'app/Core/Database.php';
require_once 'app/Models/Order.php';

$db = new Database();
$orderModel = new Order();

echo "=== Testing Order Creation ===\n";

// Test order data
$orderData = [
    'user_id' => 9,
    'total_amount' => 94.99,
    'status' => 'pending',
    'name' => 'Test User',
    'address' => '123 Test St',
    'city' => 'Test City, TS 12345, US',
    'phone' => '123-456-7890',
    'notes' => 'Payment method: credit_card'
];

try {
    $orderId = $orderModel->createOrder($orderData);
    echo "Order created successfully with ID: $orderId\n";
    
    // Test order item data
    $orderItemData = [
        'order_id' => $orderId,
        'product_id' => 1, // Use a known product ID
        'quantity' => 1,
        'price' => 94.99
    ];
    
    echo "=== Testing Order Item Creation ===\n";
    echo "Order item data: " . print_r($orderItemData, true) . "\n";
    
    $result = $orderModel->addOrderItem($orderItemData);
    if ($result) {
        echo "Order item created successfully\n";
    } else {
        echo "Failed to create order item\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
?>
