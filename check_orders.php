<?php
require_once 'app/Core/Database.php';

$db = new Database();

echo "=== Recent Orders ===\n";
$orders = $db->fetchAll("SELECT * FROM orders ORDER BY created_at DESC LIMIT 5");
foreach ($orders as $order) {
    echo "Order ID: " . $order['id'] . ", User ID: " . $order['user_id'] . ", Total: $" . $order['total_amount'] . ", Status: " . $order['status'] . ", Created: " . $order['created_at'] . "\n";
}

echo "\n=== Recent Order Items ===\n";
$orderItems = $db->fetchAll("SELECT * FROM order_items ORDER BY id DESC LIMIT 5");
foreach ($orderItems as $item) {
    echo "Order Item ID: " . $item['id'] . ", Order ID: " . $item['order_id'] . ", Product ID: " . $item['product_id'] . ", Quantity: " . $item['quantity'] . ", Price: $" . $item['price'] . "\n";
}
?>
