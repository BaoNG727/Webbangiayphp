<?php
/**
 * Test Contact Page Fix
 * Verifies that the contact page renders correctly without duplication
 */

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Testing Contact Page Fix</h1>";

try {
    // Start session
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Load required files
    require_once __DIR__ . '/app/Core/Database.php';
    require_once __DIR__ . '/app/Core/Controller.php';
    require_once __DIR__ . '/app/Controllers/PageController.php';

    echo "<h2>✅ Files loaded successfully</h2>";
    
    // Test PageController syntax
    $reflection = new ReflectionClass('PageController');
    $contactMethod = $reflection->getMethod('contact');
    echo "<h2>✅ PageController::contact method exists</h2>";
    
    // Test direct contact page view
    echo "<h2>Testing Contact Page Template:</h2>";
    echo "<div style='border: 2px solid #007bff; padding: 20px; margin: 20px 0; border-radius: 8px; background: #f8f9fa;'>";
    echo "<h3>Direct Template Include (should show single contact form):</h3>";
    
    // Mock data for testing
    $title = 'Contact Us - Nike Shoe Store';
    $data = [
        'title' => $title,
        'description' => 'Test contact page'
    ];
    extract($data);
    
    // Count includes
    echo "<div style='background: white; padding: 10px; margin-bottom: 10px; border-radius: 4px;'>";
    echo "<strong>Testing single include of contact template:</strong><br>";
    
    // Include contact page template once
    include __DIR__ . '/app/Views/pages/contact.php';
    
    echo "</div>";
    echo "</div>";
    
    echo "<h2>✅ Test completed - Check above for any duplication</h2>";
    echo "<p><strong>Expected:</strong> One contact form, one Quick Contact section, one Store Location section, one FAQ section</p>";
    echo "<p><strong>If you see duplicates:</strong> The issue is in the view rendering system, not the template file itself</p>";
    
} catch (Exception $e) {
    echo "<h2>❌ Error: " . $e->getMessage() . "</h2>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
?>

<style>
body {
    font-family: Arial, sans-serif;
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
    line-height: 1.6;
}

h1, h2 {
    color: #333;
    border-bottom: 2px solid #007bff;
    padding-bottom: 10px;
}

.form-container {
    background: white;
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 20px;
    margin: 20px 0;
}
</style>
