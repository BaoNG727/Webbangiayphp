<?php
// Test URL parsing like the router does
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>URL ROUTING TEST</h1>";

// Simulate what the router does
$url = $_SERVER['REQUEST_URI'] ?? '/contact';
echo "Full URL: " . $_SERVER['REQUEST_URI'] . "<br>";

$url = str_replace('/Webgiay', '', $url);
echo "After removing /Webgiay: " . $url . "<br>";

$path = parse_url($url, PHP_URL_PATH);
echo "Parsed path: " . $path . "<br>";

$method = $_SERVER['REQUEST_METHOD'];
echo "Method: " . $method . "<br>";

echo "<hr>";

// Test the exact conditions from router.php
if ($path === '/contact') {
    echo "✅ Path matches '/contact' - should trigger contact route<br>";
    
    // Test loading the controller
    try {
        require_once 'app/Core/Config.php';
        require_once 'app/Core/Database.php';
        require_once 'app/Core/Controller.php';
        require_once 'app/Core/Model.php';
        require_once 'app/Core/Security.php';
        require_once 'app/Core/Email.php';
        require_once 'app/Controllers/PageController.php';
        
        echo "All required files loaded<br>";
        
        $controller = new PageController();
        echo "PageController instantiated<br>";
        
        echo "About to call contact() method...<br>";
        $controller->contact();
        echo "contact() method completed<br>";
        
    } catch (Exception $e) {
        echo "ERROR: " . $e->getMessage() . "<br>";
        echo "Stack trace: <pre>" . $e->getTraceAsString() . "</pre>";
    }
} else {
    echo "❌ Path does not match '/contact'. Current path: '$path'<br>";
}
?>
