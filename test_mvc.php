<?php
// Test if MVC system is working
echo "Starting MVC test...\n";

// Include necessary files (same as router.php)
require_once __DIR__ . '/app/Core/Database.php';
require_once __DIR__ . '/app/Core/Model.php';
require_once __DIR__ . '/app/Core/Controller.php';
require_once __DIR__ . '/app/Core/Security.php';
require_once __DIR__ . '/app/Core/Email.php';
require_once __DIR__ . '/app/Controllers/PageController.php';

try {
    echo "Creating PageController...\n";
    $controller = new PageController();
    echo "PageController created successfully!\n";
    
    echo "Testing contact method...\n";
    ob_start();
    $controller->contact();
    $output = ob_get_contents();
    ob_end_clean();
    
    echo "Contact method executed. Output length: " . strlen($output) . " characters\n";
    if (strlen($output) > 0) {
        echo "First 200 characters of output:\n";
        echo substr($output, 0, 200) . "...\n";
    } else {
        echo "NO OUTPUT GENERATED!\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
?>
