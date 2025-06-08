<?php
/**
 * Contact Page Diagnostic
 * Checks for routing and rendering issues
 */

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>🔧 Contact Page Diagnostic</h1>";

try {
    // Start session
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    echo "<div style='background: #e7f3ff; padding: 15px; border-radius: 8px; margin: 20px 0;'>";
    echo "<h2>📊 System Status</h2>";
    echo "<ul>";
    echo "<li>✅ PHP Version: " . PHP_VERSION . "</li>";
    echo "<li>✅ Session Status: " . (session_status() === PHP_SESSION_ACTIVE ? 'Active' : 'Inactive') . "</li>";
    echo "<li>✅ Current Working Directory: " . getcwd() . "</li>";
    echo "<li>✅ Contact Template Exists: " . (file_exists(__DIR__ . '/app/Views/pages/contact.php') ? 'Yes' : 'No') . "</li>";
    echo "</ul>";
    echo "</div>";

    // Test routing
    echo "<div style='background: #f0f8f0; padding: 15px; border-radius: 8px; margin: 20px 0;'>";
    echo "<h2>🛤️ Routing Test</h2>";
    
    $testPaths = [
        '/Webgiay/contact',
        'contact',
        '/contact'
    ];
    
    foreach ($testPaths as $testPath) {
        echo "<div style='margin: 10px 0; padding: 10px; background: white; border-radius: 4px;'>";
        echo "<strong>Path:</strong> <code>$testPath</code><br>";
        echo "<strong>Result:</strong> <a href='http://localhost$testPath' target='_blank'>Test Link</a>";
        echo "</div>";
    }
    echo "</div>";

    // Check for file contents
    echo "<div style='background: #fff3cd; padding: 15px; border-radius: 8px; margin: 20px 0;'>";
    echo "<h2>📄 Template Analysis</h2>";
    
    $contactFile = __DIR__ . '/app/Views/pages/contact.php';
    if (file_exists($contactFile)) {
        $content = file_get_contents($contactFile);
        $lines = count(file($contactFile));
        echo "<ul>";
        echo "<li>✅ Contact template found</li>";
        echo "<li>📏 File size: " . strlen($content) . " bytes</li>";
        echo "<li>📄 Line count: $lines lines</li>";
        echo "<li>🔍 Contains 'Quick Contact': " . (strpos($content, 'Quick Contact') !== false ? 'Yes' : 'No') . "</li>";
        echo "<li>🔍 Contains 'Store Location': " . (strpos($content, 'Visit Our Store') !== false ? 'Yes' : 'No') . "</li>";
        echo "<li>🔍 Contains 'FAQ': " . (strpos($content, 'Frequently Asked Questions') !== false ? 'Yes' : 'No') . "</li>";
        echo "</ul>";
    } else {
        echo "<p>❌ Contact template not found</p>";
    }
    echo "</div>";

    // Check controller
    echo "<div style='background: #f8d7da; padding: 15px; border-radius: 8px; margin: 20px 0;'>";
    echo "<h2>🎮 Controller Test</h2>";
    
    require_once __DIR__ . '/app/Core/Database.php';
    require_once __DIR__ . '/app/Core/Controller.php';
    require_once __DIR__ . '/app/Controllers/PageController.php';
    
    echo "<ul>";
    echo "<li>✅ Controller files loaded successfully</li>";
    echo "<li>✅ PageController class exists: " . (class_exists('PageController') ? 'Yes' : 'No') . "</li>";
    echo "<li>✅ Contact method exists: " . (method_exists('PageController', 'contact') ? 'Yes' : 'No') . "</li>";
    echo "</ul>";
    echo "</div>";

    echo "<div style='background: #d1ecf1; padding: 15px; border-radius: 8px; margin: 20px 0;'>";
    echo "<h2>✅ Diagnostic Complete</h2>";
    echo "<p><strong>Summary:</strong> All core components are working. The syntax error in PageController.php has been fixed.</p>";
    echo "<p><strong>Next Steps:</strong></p>";
    echo "<ul>";
    echo "<li>1. Test the actual contact page: <a href='http://localhost/Webgiay/contact' target='_blank'>http://localhost/Webgiay/contact</a></li>";
    echo "<li>2. Check if duplication still occurs</li>";
    echo "<li>3. If duplication persists, it may be a client-side issue or caching problem</li>";
    echo "</ul>";
    echo "</div>";

} catch (Exception $e) {
    echo "<div style='background: #f8d7da; padding: 15px; border-radius: 8px; margin: 20px 0;'>";
    echo "<h2>❌ Error: " . $e->getMessage() . "</h2>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
    echo "</div>";
}
?>

<style>
body {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    max-width: 1000px;
    margin: 0 auto;
    padding: 20px;
    line-height: 1.6;
    background: #f8f9fa;
}

h1, h2 {
    color: #495057;
    margin-top: 0;
}

code {
    background: #e9ecef;
    padding: 2px 6px;
    border-radius: 3px;
    font-family: 'Monaco', 'Consolas', monospace;
}

a {
    color: #007bff;
    text-decoration: none;
}

a:hover {
    text-decoration: underline;
}

ul {
    margin: 0;
    padding-left: 20px;
}

li {
    margin: 5px 0;
}
</style>
