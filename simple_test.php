<?php
/**
 * Simple Test Page - Nike Shoe Store
 * Basic test without complex dependencies
 */

// Start session safely
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

echo "<h1>🚀 Nike Shoe Store - Simple Test</h1>";
echo "<hr>";

// Test basic PHP functionality
echo "<h2>✅ PHP is working!</h2>";
echo "<p>PHP Version: " . phpversion() . "</p>";
echo "<p>Current time: " . date('Y-m-d H:i:s') . "</p>";

// Test session
$_SESSION['test'] = 'Session working!';
echo "<h2>✅ Sessions are working!</h2>";
echo "<p>Session test: " . $_SESSION['test'] . "</p>";

// Test file includes
echo "<h2>📁 Testing file structure:</h2>";
$coreFiles = [
    'app/Core/Database.php',
    'app/Core/Router.php', 
    'app/Core/Controller.php',
    'app/Controllers/HomeController.php'
];

foreach ($coreFiles as $file) {
    if (file_exists($file)) {
        echo "<p>✅ {$file}</p>";
    } else {
        echo "<p>❌ {$file}</p>";
    }
}

// Test database connection
echo "<h2>💾 Testing database:</h2>";
try {
    require_once 'app/Core/Database.php';
    $db = new Database();
    echo "<p>✅ Database connection successful!</p>";
    
    // Test a simple query
    $result = $db->fetch("SELECT COUNT(*) as count FROM products");
    echo "<p>✅ Products in database: " . $result['count'] . "</p>";
    
} catch (Exception $e) {
    echo "<p>❌ Database error: " . $e->getMessage() . "</p>";
    echo "<p>💡 Make sure to import database.sql into your MySQL</p>";
}

echo "<hr>";
echo "<h2>🎯 Next Steps:</h2>";
echo "<p><a href='index.php' style='padding: 10px 15px; background: #007bff; color: white; text-decoration: none; border-radius: 4px;'>🏠 Go to Main Website</a></p>";
echo "<p><a href='diagnostic.php' style='padding: 10px 15px; background: #28a745; color: white; text-decoration: none; border-radius: 4px;'>🔍 Full Diagnostic</a></p>";
?>
