<?php
/**
 * Debug Router - Nike Shoe Store
 * Use this to debug routing issues
 */

// Start session safely
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

echo "<h1>🔍 Router Debug Information</h1>";
echo "<hr>";

// Show current request information
echo "<h2>📍 Current Request Info</h2>";
echo "<p><strong>REQUEST_URI:</strong> " . $_SERVER['REQUEST_URI'] . "</p>";
echo "<p><strong>REQUEST_METHOD:</strong> " . $_SERVER['REQUEST_METHOD'] . "</p>";
echo "<p><strong>HTTP_HOST:</strong> " . $_SERVER['HTTP_HOST'] . "</p>";
echo "<p><strong>SCRIPT_NAME:</strong> " . $_SERVER['SCRIPT_NAME'] . "</p>";

// Parse URL like the router does
$request = $_SERVER['REQUEST_URI'];
$path = parse_url($request, PHP_URL_PATH);

echo "<h2>🛤️ Path Processing</h2>";
echo "<p><strong>Original REQUEST_URI:</strong> {$request}</p>";
echo "<p><strong>Parsed PATH:</strong> {$path}</p>";

// Remove the base path
$basePath = '/Webgiay';
if (strpos($path, $basePath) === 0) {
    $path = substr($path, strlen($basePath));
}

echo "<p><strong>After removing base path:</strong> {$path}</p>";
echo "<p><strong>Empty path (should be home):</strong> " . (empty($path) ? 'YES' : 'NO') . "</p>";

// Show what routes we expect to match
echo "<h2>🎯 Expected Route Matches</h2>";
$testUrls = [
    '/' => 'HomeController::index',
    '/home' => 'HomeController::index', 
    '/products' => 'ProductController::index',
    '/login' => 'AuthController::login',
    '/cart' => 'CartController::index'
];

foreach ($testUrls as $testPath => $expectedController) {
    $matches = ($testPath === $path);
    $status = $matches ? '✅' : '❌';
    echo "<p>{$status} <strong>{$testPath}</strong> → {$expectedController}</p>";
}

// Test if files exist
echo "<h2>📁 File Existence Check</h2>";
$coreFiles = [
    'app/Core/Router.php',
    'app/Controllers/HomeController.php',
    'app/Views/layouts/header.php',
    'app/Views/home/index.php'
];

foreach ($coreFiles as $file) {
    $exists = file_exists($file);
    $status = $exists ? '✅' : '❌';
    echo "<p>{$status} {$file}</p>";
}

// Try to load the router and test it
echo "<h2>🧪 Router Test</h2>";
try {
    require_once 'app/Core/Router.php';
    $router = new Router();
    
    // Add the home route
    $router->get('/', 'HomeController', 'index');
    
    echo "<p>✅ Router loaded successfully</p>";
    echo "<p>✅ Route added: / → HomeController::index</p>";
    
    // Test the current path
    echo "<p><strong>Testing current path:</strong> '{$path}'</p>";
    
} catch (Exception $e) {
    echo "<p>❌ Router error: " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<h2>💡 Troubleshooting Tips</h2>";
echo "<ol>";
echo "<li><strong>Check XAMPP:</strong> Make sure Apache and URL rewriting are enabled</li>";
echo "<li><strong>Check .htaccess:</strong> Verify the file exists and has correct content</li>";
echo "<li><strong>Try direct access:</strong> <a href='index-mvc.php'>index-mvc.php</a></li>";
echo "<li><strong>Check database:</strong> <a href='setup_database.php'>setup_database.php</a></li>";
echo "</ol>";

echo "<h2>🚀 Quick Links</h2>";
echo "<p><a href='simple_test.php' style='padding: 8px 12px; background: #28a745; color: white; text-decoration: none; border-radius: 4px;'>Simple Test</a></p>";
echo "<p><a href='index.php' style='padding: 8px 12px; background: #007bff; color: white; text-decoration: none; border-radius: 4px;'>Try Homepage</a></p>";
echo "<p><a href='index-mvc.php' style='padding: 8px 12px; background: #6c757d; color: white; text-decoration: none; border-radius: 4px;'>Direct MVC</a></p>";

?>

<style>
body { font-family: Arial, sans-serif; margin: 40px; }
h1, h2 { color: #333; }
p { margin: 8px 0; }
ol { margin: 10px 0; padding-left: 30px; }
a { color: #007bff; }
</style>
