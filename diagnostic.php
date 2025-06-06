<?php
/**
 * Diagnostic Page - Nike Shoe Store
 * Use this to test if PHP and the MVC system are working
 */

echo "<h1>Nike Shoe Store - System Diagnostic</h1>";
echo "<hr>";

// Test 1: PHP Version
echo "<h2>1. PHP Version</h2>";
echo "<p>PHP Version: " . phpversion() . "</p>";

// Test 2: File Structure
echo "<h2>2. File Structure Check</h2>";
$files = [
    'index-mvc.php' => 'MVC Entry Point',
    'app/Core/Database.php' => 'Database Core',
    'app/Core/Router.php' => 'Router Core',
    'app/Controllers/HomeController.php' => 'Home Controller',
    '.htaccess' => 'URL Rewriting'
];

foreach ($files as $file => $description) {
    $exists = file_exists(__DIR__ . '/' . $file);
    $status = $exists ? '✅' : '❌';
    echo "<p>{$status} {$description}: {$file}</p>";
}

// Test 3: Database Connection
echo "<h2>3. Database Connection Test</h2>";
try {
    require_once __DIR__ . '/app/Core/Database.php';
    $db = new Database();
    echo "<p>✅ Database connection successful</p>";
} catch (Exception $e) {
    echo "<p>❌ Database connection failed: " . $e->getMessage() . "</p>";
}

// Test 4: Session Test
echo "<h2>4. Session Test</h2>";
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$_SESSION['test'] = 'working';
if (isset($_SESSION['test'])) {
    echo "<p>✅ Sessions are working</p>";
} else {
    echo "<p>❌ Sessions not working</p>";
}

// Test 5: MVC System Test
echo "<h2>5. MVC System Test</h2>";
try {
    require_once __DIR__ . '/app/Core/Router.php';
    $router = new Router();
    echo "<p>✅ Router class loaded successfully</p>";
    
    require_once __DIR__ . '/app/Controllers/HomeController.php';
    echo "<p>✅ HomeController loaded successfully</p>";
    
} catch (Exception $e) {
    echo "<p>❌ MVC System error: " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<h2>Test Results</h2>";
echo "<p><strong>If all tests show ✅, the system should be working.</strong></p>";
echo "<p><a href='index-mvc.php'>🚀 Try MVC Entry Point</a></p>";
echo "<p><a href='index.php'>🏠 Try Main Entry Point</a></p>";

// Test 6: URL and Path Information
echo "<h2>6. URL and Path Information</h2>";
echo "<p>Current URL: " . ($_SERVER['HTTPS'] ?? false ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . "</p>";
echo "<p>Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "</p>";
echo "<p>Script Path: " . __DIR__ . "</p>";
echo "<p>Request URI: " . $_SERVER['REQUEST_URI'] . "</p>";

?>

<style>
body { font-family: Arial, sans-serif; margin: 40px; }
h1 { color: #333; }
h2 { color: #666; }
p { margin: 5px 0; }
a { color: #007bff; text-decoration: none; padding: 10px 15px; background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 4px; display: inline-block; margin: 5px; }
a:hover { background: #e9ecef; }
</style>
