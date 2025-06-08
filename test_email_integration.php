<?php
/**
 * Email Integration Test for Discount Codes
 * Tests that emails are properly generated with discount information
 */

require_once 'app/Core/Email.php';

echo "<!DOCTYPE html>";
echo "<html><head><title>Email Integration Test</title>";
echo "<link href='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css' rel='stylesheet'>";
echo "</head><body class='container py-4'>";

echo "<h1>Email Integration Test - Discount Codes</h1>";

// Test order data with discount
$sampleOrders = [
    [
        'id' => 12345,
        'user_email' => 'customer@example.com',
        'name' => 'John Doe',
        'address' => '123 Main Street',
        'city' => 'Ho Chi Minh City',
        'phone' => '0123456789',
        'created_at' => date('Y-m-d H:i:s'),
        'total_amount' => 900000,
        'subtotal_amount' => 1000000,
        'discount_code' => 'WELCOME10',
        'discount_amount' => 100000,
        'status' => 'pending',
        'items' => [
            [
                'name' => 'Nike Air Force 1 White',
                'quantity' => 2,
                'price' => 300000,
                'size' => '42'
            ],
            [
                'name' => 'Nike Air Max 90',
                'quantity' => 1,
                'price' => 400000,
                'size' => '43'
            ]
        ]
    ],
    [
        'id' => 12346,
        'user_email' => 'customer2@example.com',
        'name' => 'Jane Smith',
        'address' => '456 Oak Avenue',
        'city' => 'Hanoi',
        'phone' => '0987654321',
        'created_at' => date('Y-m-d H:i:s'),
        'total_amount' => 850000,
        'subtotal_amount' => 900000,
        'discount_code' => 'SAVE50K',
        'discount_amount' => 50000,
        'status' => 'pending',
        'items' => [
            [
                'name' => 'Nike Revolution 6',
                'quantity' => 3,
                'price' => 300000,
                'size' => '38'
            ]
        ]
    ],
    [
        'id' => 12347,
        'user_email' => 'customer3@example.com',
        'name' => 'Mike Johnson',
        'address' => '789 Pine Street',
        'city' => 'Da Nang',
        'phone' => '0555123456',
        'created_at' => date('Y-m-d H:i:s'),
        'total_amount' => 500000,
        'subtotal_amount' => 500000,
        'discount_code' => null,
        'discount_amount' => 0,
        'status' => 'pending',
        'items' => [
            [
                'name' => 'Nike Blazer Mid',
                'quantity' => 1,
                'price' => 500000,
                'size' => '41'
            ]
        ]
    ]
];

echo "<div class='row'>";

foreach ($sampleOrders as $i => $order) {
    echo "<div class='col-12 mb-4'>";
    echo "<div class='card'>";
    echo "<div class='card-header'>";
    echo "<h5>Test " . ($i + 1) . ": Order #" . str_pad($order['id'], 6, '0', STR_PAD_LEFT);
    if ($order['discount_code']) {
        echo " <span class='badge bg-success'>With Discount: {$order['discount_code']}</span>";
    } else {
        echo " <span class='badge bg-secondary'>No Discount</span>";
    }
    echo "</h5>";
    echo "</div>";
    
    echo "<div class='card-body'>";
    
    // Order Summary
    echo "<div class='row mb-3'>";
    echo "<div class='col-md-6'>";
    echo "<h6>Order Details:</h6>";
    echo "<p><strong>Customer:</strong> {$order['name']}<br>";
    echo "<strong>Email:</strong> {$order['user_email']}<br>";
    echo "<strong>Address:</strong> {$order['address']}, {$order['city']}<br>";
    echo "<strong>Phone:</strong> {$order['phone']}</p>";
    echo "</div>";
    
    echo "<div class='col-md-6'>";
    echo "<h6>Financial Summary:</h6>";
    if ($order['discount_code']) {
        echo "<p><strong>Subtotal:</strong> " . number_format($order['subtotal_amount']) . " VND<br>";
        echo "<strong>Discount ({$order['discount_code']}):</strong> -" . number_format($order['discount_amount']) . " VND<br>";
    }
    echo "<strong>Total:</strong> " . number_format($order['total_amount']) . " VND</p>";
    echo "</div>";
    echo "</div>";
    
    // Generate Email
    try {
        $emailService = new Email();
        
        // Use reflection to access the private method for testing
        $reflection = new ReflectionClass($emailService);
        $method = $reflection->getMethod('renderOrderConfirmationTemplate');
        $method->setAccessible(true);
        
        $emailContent = $method->invoke($emailService, $order);
        
        echo "<h6>Generated Email Content:</h6>";
        echo "<div class='border p-3' style='background-color: #f8f9fa; max-height: 400px; overflow-y: auto;'>";
        echo $emailContent;
        echo "</div>";
        
        // Test sending email (will be logged)
        $emailService->sendOrderConfirmation($order, $order['user_email']);
        echo "<p class='text-success mt-2'><i class='fas fa-check'></i> Email generated and logged successfully</p>";
        
    } catch (Exception $e) {
        echo "<p class='text-danger'><i class='fas fa-times'></i> Error generating email: " . $e->getMessage() . "</p>";
    }
    
    echo "</div>";
    echo "</div>";
    echo "</div>";
}

echo "</div>";

// Check email logs
echo "<div class='card mt-4'>";
echo "<div class='card-header'>";
echo "<h5>Email Logs</h5>";
echo "</div>";
echo "<div class='card-body'>";

$logFile = 'app/logs/emails.log';
if (file_exists($logFile)) {
    $logContent = file_get_contents($logFile);
    $logLines = array_reverse(explode("\n", trim($logContent)));
    
    echo "<h6>Recent Email Activity:</h6>";
    echo "<div style='background-color: #f8f9fa; padding: 10px; border-radius: 5px; max-height: 300px; overflow-y: auto;'>";
    echo "<pre style='margin: 0; font-size: 12px;'>";
    
    // Show last 20 lines
    $recentLines = array_slice($logLines, 0, 20);
    foreach ($recentLines as $line) {
        if (!empty(trim($line))) {
            echo htmlspecialchars($line) . "\n";
        }
    }
    
    echo "</pre>";
    echo "</div>";
    
    echo "<p class='mt-2'><small class='text-muted'>Note: In production environment, emails would be sent via SMTP. Currently logging for testing purposes.</small></p>";
} else {
    echo "<p class='text-muted'>No email log file found. Emails will be logged when sent.</p>";
}

echo "</div>";
echo "</div>";

echo "<div class='mt-4 text-center'>";
echo "<a href='test_complete_system.php' class='btn btn-primary'>← Back to Complete System Test</a> ";
echo "<a href='admin/discount-codes.php' class='btn btn-success'>View Discount Management →</a>";
echo "</div>";

echo "</body></html>";
?>
