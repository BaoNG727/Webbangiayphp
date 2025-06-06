<?php
/**
 * Email System Test Page
 * This page tests the email functionality for development purposes
 * Remove this file before production deployment
 */

require_once __DIR__ . '/app/Core/Email.php';

// Create a test email service
$emailService = new Email();

echo "<h1>Email System Test</h1>";
echo "<h2>Testing Email Templates</h2>";

// Test 1: Order Confirmation Email
echo "<h3>1. Order Confirmation Email Test</h3>";
$testOrder = [
    'id' => 123,
    'user_id' => 1,
    'total_amount' => 149.99,
    'status' => 'pending',
    'shipping_address' => 'John Doe, 123 Main St, New York, NY 10001, USA',
    'payment_method' => 'credit_card',
    'created_at' => date('Y-m-d H:i:s'),
    'items' => [
        [
            'product_id' => 1,
            'name' => 'Nike Air Max 270',
            'quantity' => 1,
            'price' => 149.99,
            'image' => 'nike-air-max-270.jpg'
        ]
    ]
];

try {
    $result = $emailService->sendOrderConfirmation($testOrder, 'customer@example.com');
    echo "<p style='color: green;'>✓ Order confirmation email sent successfully</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Error: " . $e->getMessage() . "</p>";
}

// Test 2: Contact Form Email
echo "<h3>2. Contact Form Email Test</h3>";
$testContactData = [
    'name' => 'John Smith',
    'email' => 'john@example.com',
    'subject' => 'Product Inquiry',
    'message' => 'I would like to know more about your Nike Air Max collection.',
    'date' => date('Y-m-d H:i:s')
];

try {
    $result = $emailService->sendContactFormNotification($testContactData);
    echo "<p style='color: green;'>✓ Contact form email sent successfully</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Error: " . $e->getMessage() . "</p>";
}

// Test 3: Order Status Update Email
echo "<h3>3. Order Status Update Email Test</h3>";
try {
    $result = $emailService->sendOrderStatusUpdate($testOrder, 'customer@example.com', 'pending', 'shipped');
    echo "<p style='color: green;'>✓ Order status update email sent successfully</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Error: " . $e->getMessage() . "</p>";
}

// Display email logs
echo "<h2>Email Logs (Development Mode)</h2>";
$logDir = __DIR__ . '/email_logs';
if (is_dir($logDir)) {
    $logFiles = glob($logDir . '/*.html');
    if (!empty($logFiles)) {
        echo "<p>Email logs are stored in: " . $logDir . "</p>";
        echo "<ul>";
        foreach ($logFiles as $logFile) {
            $filename = basename($logFile);
            echo "<li><a href='email_logs/" . $filename . "' target='_blank'>" . $filename . "</a></li>";
        }
        echo "</ul>";
    } else {
        echo "<p>No email logs found.</p>";
    }
} else {
    echo "<p>Email logs directory not found. Emails will be logged when sent.</p>";
}

echo "<hr>";
echo "<p><strong>Note:</strong> This is a test page for development purposes. Remove this file before production deployment.</p>";
echo "<p><a href='index-mvc.php'>← Back to Main Site</a></p>";
?>
