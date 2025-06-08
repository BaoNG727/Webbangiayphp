<?php
// Debug the contact route specifically
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "=== CONTACT ROUTE DEBUG ===<br>";
echo "Starting debug...<br>";

// Include the same files as router.php
require_once 'app/Core/Config.php';
require_once 'app/Core/Database.php';
require_once 'app/Core/Controller.php';
require_once 'app/Core/Model.php';
require_once 'app/Core/Security.php';
require_once 'app/Core/Email.php';

echo "Core files loaded...<br>";

// Include all controllers
require_once 'app/Controllers/HomeController.php';
require_once 'app/Controllers/ProductController.php';
require_once 'app/Controllers/AuthController.php';
require_once 'app/Controllers/CartController.php';
require_once 'app/Controllers/CheckoutController.php';
require_once 'app/Controllers/OrderController.php';
require_once 'app/Controllers/PageController.php';

echo "Controllers loaded...<br>";

// Test PageController instantiation
try {
    echo "Creating PageController...<br>";
    $controller = new PageController();
    echo "PageController created successfully!<br>";
    
    echo "Calling contact() method...<br>";
    ob_start(); // Capture output
    $controller->contact();
    $output = ob_get_contents();
    ob_end_clean();
    
    echo "Contact method executed. Output length: " . strlen($output) . " characters<br>";
    echo "First 200 characters of output:<br>";
    echo "<pre>" . htmlspecialchars(substr($output, 0, 200)) . "</pre>";
    
    if (strlen($output) > 0) {
        echo "<hr><h3>FULL OUTPUT:</h3>";
        echo $output;
    } else {
        echo "<strong style='color: red;'>NO OUTPUT GENERATED!</strong>";
    }
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "<br>";
    echo "Stack trace:<br><pre>" . $e->getTraceAsString() . "</pre>";
}
?>
