<?php
// Test view loading directly
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>DIRECT VIEW TEST</h1>";

// Test if we can include the contact view directly
$data = [
    'title' => 'Test Contact',
    'description' => 'Test description'
];

echo "Testing direct view inclusion...<br>";

try {
    extract($data);
    $viewFile = __DIR__ . "/app/Views/pages/contact.php";
    echo "View file path: " . $viewFile . "<br>";
    echo "File exists: " . (file_exists($viewFile) ? "YES" : "NO") . "<br>";
    echo "File size: " . filesize($viewFile) . " bytes<br>";
    
    if (file_exists($viewFile)) {
        echo "<hr><h2>Including view file...</h2>";
        ob_start();
        include $viewFile;
        $output = ob_get_contents();
        ob_end_clean();
        
        echo "Output captured. Length: " . strlen($output) . " characters<br>";
        
        if (strlen($output) > 0) {
            echo "<hr><h3>VIEW OUTPUT:</h3>";
            echo $output;
        } else {
            echo "<strong style='color: red;'>VIEW PRODUCED NO OUTPUT!</strong>";
        }
    }
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage();
}
?>
