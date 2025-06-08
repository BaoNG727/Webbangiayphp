<?php
// Simple test file to check checkout routing
session_start();

// Set up minimal test session (simulate logged in user)
$_SESSION['user_id'] = 1;
$_SESSION['username'] = 'testuser';
$_SESSION['role'] = 'customer';

echo "<h2>Checkout Test Page</h2>";
echo "<p>This is a simple test to check if form submission works.</p>";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "<h3>POST Data Received:</h3>";
    echo "<pre>" . print_r($_POST, true) . "</pre>";
    echo "<p>Form submission is working!</p>";
} else {
    echo '<form method="POST" action="/Webgiay/checkout/process">
        <input type="hidden" name="first_name" value="Test">
        <input type="hidden" name="last_name" value="User">
        <input type="hidden" name="email" value="test@example.com">
        <input type="hidden" name="phone" value="123-456-7890">
        <input type="hidden" name="address" value="123 Test St">
        <input type="hidden" name="city" value="Test City">
        <input type="hidden" name="state" value="TS">
        <input type="hidden" name="zip" value="12345">
        <input type="hidden" name="country" value="US">
        <input type="hidden" name="payment_method" value="credit_card">
        <button type="submit">Test Checkout Process</button>
    </form>';
}
?>
