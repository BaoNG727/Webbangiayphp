<?php
/**
 * Minimal Router Test
 */

echo "<h1>Minimal Router Test</h1>";

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    // Load only essential classes
    require_once __DIR__ . '/app/Core/Router.php';
    
    echo "✅ Router loaded<br>";
    
    // Create router
    $router = new Router();
    echo "✅ Router created<br>";
    
    // Add a simple route
    $router->get('/', 'TestController', 'index');
    echo "✅ Route added<br>";
    
    // Create a simple test controller
    class TestController {
        public function index() {
            echo "<h2>✅ SUCCESS! Controller method executed!</h2>";
            echo "<p>The routing system is working correctly.</p>";
        }
    }
    
    echo "✅ Test controller created<br>";
    
    // Test dispatch
    echo "<h2>Testing dispatch for '/'...</h2>";
    $router->dispatch('/');
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "<br>";
    echo "Stack trace: <pre>" . $e->getTraceAsString() . "</pre>";
} catch (Error $e) {
    echo "❌ Fatal Error: " . $e->getMessage() . "<br>";
    echo "Stack trace: <pre>" . $e->getTraceAsString() . "</pre>";
}
?>
