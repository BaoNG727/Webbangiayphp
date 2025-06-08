<?php
// Simulate the checkout process exactly as it happens in the browser
session_start();

// Set up session to simulate logged-in user
$_SESSION['user_id'] = 9;
$_SESSION['username'] = 'testuser';
$_SESSION['role'] = 'customer';

// Simulate POST data from checkout form
$_POST = [
    'first_name' => 'Test',
    'last_name' => 'User',
    'email' => 'test@example.com',
    'phone' => '123-456-7890',
    'address' => '123 Test Street',
    'city' => 'Test City',
    'state' => 'TS',
    'zip' => '12345',
    'country' => 'US',
    'payment_method' => 'credit_card'
];

// Set REQUEST_METHOD
$_SERVER['REQUEST_METHOD'] = 'POST';

echo "=== Simulating Checkout Process ===\n";
echo "Session User ID: " . $_SESSION['user_id'] . "\n";
echo "POST Data: " . print_r($_POST, true) . "\n";

// Include the checkout controller and process the order
require_once 'app/Controllers/CheckoutController.php';

try {
    $controller = new CheckoutController();
    echo "=== Calling CheckoutController::process() ===\n";
    $controller->process();
    echo "Checkout process completed successfully!\n";
} catch (Exception $e) {
    echo "Error during checkout: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
?>
