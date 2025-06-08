<?php
require_once 'app/Core/Database.php';
require_once 'app/Models/Cart.php';

$cartModel = new Cart();
$userId = 9; // Using the user ID from the logs

echo "=== Cart Contents for User ID: $userId ===\n";
$cartItems = $cartModel->getUserCart($userId);
echo "Cart items: " . print_r($cartItems, true) . "\n";

$cartTotal = $cartModel->getCartTotal($userId);
echo "Cart total: $cartTotal\n";

if (!empty($cartItems)) {
    echo "\n=== Simulating Order Item Data ===\n";
    foreach ($cartItems as $item) {
        $price = ($item['sale_price'] > 0) ? $item['sale_price'] : $item['price'];
        $orderItemData = [
            'order_id' => 999, // Dummy order ID
            'product_id' => $item['product_id'],
            'quantity' => $item['quantity'],
            'price' => $price
        ];
        echo "Order item data: " . print_r($orderItemData, true) . "\n";
    }
}
?>
