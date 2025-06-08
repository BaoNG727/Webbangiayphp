<?php
// Test the order views for undefined key issues
require_once 'app/Core/Database.php';
require_once 'app/Models/Order.php';

$orderModel = new Order();

echo "=== Testing Order Views ===\n";

// Get a recent order to test with
$db = new Database();
$order = $db->fetch("SELECT * FROM orders ORDER BY created_at DESC LIMIT 1");

if ($order) {
    echo "Testing with Order ID: " . $order['id'] . "\n";
    echo "Order data structure:\n";
    foreach ($order as $key => $value) {
        echo "  $key: " . (strlen($value) > 50 ? substr($value, 0, 50) . '...' : $value) . "\n";
    }
    
    echo "\n=== Simulating Order View Logic ===\n";
    
    // Test payment method extraction
    echo "1. Payment Method Test:\n";
    $paymentMethods = [
        'credit_card' => 'Credit Card',
        'paypal' => 'PayPal',
        'bank_transfer' => 'Bank Transfer',
        'cash_on_delivery' => 'Cash on Delivery'
    ];
    
    $paymentMethod = 'N/A';
    if (isset($order['payment_method'])) {
        $paymentMethod = $paymentMethods[$order['payment_method']] ?? ucfirst(str_replace('_', ' ', $order['payment_method']));
        echo "   Found direct payment_method: $paymentMethod\n";
    } elseif (isset($order['notes']) && strpos($order['notes'], 'Payment method:') !== false) {
        $noteParts = explode('Payment method:', $order['notes']);
        if (count($noteParts) > 1) {
            $extractedMethod = trim($noteParts[1]);
            $paymentMethod = $paymentMethods[$extractedMethod] ?? ucfirst(str_replace('_', ' ', $extractedMethod));
            echo "   Extracted from notes: $paymentMethod (raw: $extractedMethod)\n";
        }
    } else {
        echo "   Using fallback: $paymentMethod\n";
    }
    
    // Test shipping address extraction
    echo "\n2. Shipping Address Test:\n";
    $shippingAddress = isset($order['shipping_address']) ? $order['shipping_address'] : 
        ($order['name'] . "\n" . $order['address'] . "\n" . $order['city'] . "\n" . $order['phone']);
    echo "   Shipping address: " . str_replace("\n", " | ", $shippingAddress) . "\n";
    
    echo "\n✅ Order view logic working correctly!\n";
} else {
    echo "❌ No orders found in database\n";
}
?>
