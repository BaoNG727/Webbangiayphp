<?php
// Force contact page test
echo "<!DOCTYPE html><html><head><title>Contact Test</title></head><body>";
echo "<h1>Testing Contact Page Loading</h1>";

// Set up environment
$_SERVER['REQUEST_URI'] = '/Webgiay/contact';
$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['HTTP_HOST'] = 'localhost';

// Disable session errors
ini_set('display_errors', 0);
error_reporting(0);

// Load necessary files
require_once __DIR__ . '/app/Core/Database.php';
require_once __DIR__ . '/app/Core/Model.php';
require_once __DIR__ . '/app/Core/Controller.php';
require_once __DIR__ . '/app/Core/Security.php';
require_once __DIR__ . '/app/Core/Email.php';
require_once __DIR__ . '/app/Controllers/PageController.php';

try {
    echo "<p>Loading PageController...</p>";
    $controller = new PageController();
    echo "<p>Executing contact method...</p>";
    $controller->contact();
    echo "<p>Contact method completed successfully!</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}

echo "</body></html>";
?>
