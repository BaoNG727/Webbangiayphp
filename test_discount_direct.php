<?php
/**
 * Simple Discount API Test - Direct PHP Test
 */

session_start();
require_once 'includes/config.php';
require_once 'app/Models/DiscountCode.php';

// Simulate logged-in user for testing
$_SESSION['user_id'] = 1;

echo "<h1>Direct Discount API Test</h1>";

// Test cases
$testCases = [
    ['code' => 'WELCOME10', 'amount' => 600000],
    ['code' => 'NIKE2024', 'amount' => 1200000],
    ['code' => 'SAVE50K', 'amount' => 400000],
    ['code' => 'INVALID', 'amount' => 500000],
    ['code' => 'WELCOME10', 'amount' => 300000], // Below minimum
];

foreach ($testCases as $i => $test) {
    echo "<div style='margin: 20px 0; padding: 15px; border: 1px solid #ddd;'>";
    echo "<h3>Test " . ($i + 1) . ": Code '{$test['code']}' with " . number_format($test['amount']) . " VND</h3>";
    
    try {
        $discountModel = new DiscountCode();
        $result = $discountModel->validateCode($test['code'], $_SESSION['user_id'], $test['amount']);
        
        echo "<p><strong>Result:</strong></p>";
        echo "<pre>" . json_encode($result, JSON_PRETTY_PRINT) . "</pre>";
        
        if ($result['valid']) {
            echo "<p style='color: green;'>✓ <strong>VALID</strong> - Discount: " . number_format($result['discount_amount']) . " VND</p>";
            echo "<p>Final Amount: " . number_format($test['amount'] - $result['discount_amount']) . " VND</p>";
        } else {
            echo "<p style='color: red;'>✗ <strong>INVALID</strong> - " . $result['message'] . "</p>";
        }
        
    } catch (Exception $e) {
        echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
    }
    
    echo "</div>";
}

// Test API endpoint simulation
echo "<h2>API Endpoint Simulation</h2>";

foreach ($testCases as $i => $test) {
    echo "<div style='margin: 20px 0; padding: 15px; border: 1px solid #ccc; background-color: #f9f9f9;'>";
    echo "<h4>API Test " . ($i + 1) . ": {$test['code']}</h4>";
    
    // Simulate the API request data
    $requestData = [
        'discount_code' => $test['code'],
        'order_amount' => $test['amount']
    ];
    
    echo "<p><strong>Request Data:</strong></p>";
    echo "<pre>" . json_encode($requestData, JSON_PRETTY_PRINT) . "</pre>";
    
    try {
        $discountModel = new DiscountCode();
        $result = $discountModel->validateCode($test['code'], $_SESSION['user_id'], $test['amount']);
        
        // Format API response
        if ($result['valid']) {
            $apiResponse = [
                'success' => true,
                'message' => $result['message'],
                'discount_amount' => $result['discount_amount'],
                'discount_code' => $test['code']
            ];
        } else {
            $apiResponse = [
                'success' => false,
                'message' => $result['message']
            ];
        }
        
        echo "<p><strong>API Response:</strong></p>";
        echo "<pre>" . json_encode($apiResponse, JSON_PRETTY_PRINT) . "</pre>";
        
    } catch (Exception $e) {
        $apiResponse = [
            'success' => false,
            'message' => 'Internal server error'
        ];
        echo "<p><strong>API Error Response:</strong></p>";
        echo "<pre>" . json_encode($apiResponse, JSON_PRETTY_PRINT) . "</pre>";
    }
    
    echo "</div>";
}

echo "<p><a href='admin/discount-codes.php'>→ View Admin Discount Management</a></p>";
echo "<p><a href='test_discount_system.php'>→ Run Full System Test</a></p>";
?>
