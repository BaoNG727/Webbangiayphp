<?php
/**
 * Quick API Test for Discount Validation
 */

// Test the discount validation API
echo "<h2>Testing Discount Validation API</h2>";

$testCodes = [
    ['code' => 'WELCOME10', 'total' => 600000],
    ['code' => 'NIKE2024', 'total' => 1200000],
    ['code' => 'INVALID', 'total' => 500000]
];

foreach ($testCodes as $test) {
    echo "<h3>Testing Code: {$test['code']} with Total: " . number_format($test['total']) . " VND</h3>";
    
    // Prepare POST data
    $postData = json_encode([
        'code' => $test['code'],
        'total' => $test['total'],
        'user_id' => 1
    ]);
    
    // Simulate API call
    $context = stream_context_create([
        'http' => [
            'method' => 'POST',
            'header' => "Content-Type: application/json\r\n",
            'content' => $postData
        ]
    ]);
    
    try {
        $response = file_get_contents('http://localhost:8080/api/validate-discount.php', false, $context);
        echo "<pre>Response: " . htmlspecialchars($response) . "</pre>";
    } catch (Exception $e) {
        echo "<p>Error: " . $e->getMessage() . "</p>";
    }
    
    echo "<hr>";
}

// Test using cURL if available
if (function_exists('curl_init')) {
    echo "<h2>Testing with cURL</h2>";
    
    $testCode = ['code' => 'WELCOME10', 'total' => 600000, 'user_id' => 1];
    
    $ch = curl_init('http://localhost:8080/api/validate-discount.php');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($testCode));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    echo "<p>HTTP Code: $httpCode</p>";
    echo "<pre>Response: " . htmlspecialchars($response) . "</pre>";
}
?>
