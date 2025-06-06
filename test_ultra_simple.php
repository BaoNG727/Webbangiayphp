<?php
/**
 * Ultra Simple Test - Nike Shoe Store
 */

echo "<h1>Ultra Simple Test</h1>";
echo "<p>Current URL: " . $_SERVER['REQUEST_URI'] . "</p>";
echo "<p>Script Name: " . $_SERVER['SCRIPT_NAME'] . "</p>";
echo "<p>Path Info: " . ($_SERVER['PATH_INFO'] ?? 'NOT SET') . "</p>";

// Parse URL
$request = $_SERVER['REQUEST_URI'];
$path = parse_url($request, PHP_URL_PATH);
echo "<p>Parsed Path: $path</p>";

// Remove base path
$basePath = '/Webgiay';
if (strpos($path, $basePath) === 0) {
    $path = substr($path, strlen($basePath));
}

if (empty($path) || $path === '') {
    $path = '/';
} elseif ($path[0] !== '/') {
    $path = '/' . $path;
}

echo "<p>Final Path: <strong>$path</strong></p>";

// Test simple routing
if ($path === '/') {
    echo "<h2>✅ HOME PAGE ROUTE MATCHED!</h2>";
    echo "<p>This means the routing logic is working correctly.</p>";
    
    // Test including the actual home page
    try {
        $title = "Nike Shoe Store - Home";
        
        echo "<h3>Testing Header Include:</h3>";
        if (file_exists(__DIR__ . '/app/Views/layouts/header.php')) {
            echo "✅ Header file exists<br>";
        } else {
            echo "❌ Header file missing<br>";
        }
        
        echo "<h3>Testing Home View Include:</h3>";
        if (file_exists(__DIR__ . '/app/Views/home/index.php')) {
            echo "✅ Home view file exists<br>";
        } else {
            echo "❌ Home view file missing<br>";
        }
        
        echo "<hr>";
        echo "<h3>Actual Website Preview:</h3>";
        echo "<div style='border: 2px solid #000; padding: 10px;'>";
        
        // Mock data for testing
        $featured_products = [
            ['id' => 1, 'name' => 'Nike Air Max', 'price' => 150.00, 'image' => 'nike-air-max-270.jpg'],
            ['id' => 2, 'name' => 'Nike Air Force 1', 'price' => 100.00, 'image' => 'nike-air-force-1.jpg']
        ];
        $sale_products = $featured_products;
        
        // Include views
        include __DIR__ . '/app/Views/layouts/header.php';
        include __DIR__ . '/app/Views/home/index.php';
        include __DIR__ . '/app/Views/layouts/footer.php';
        
        echo "</div>";
        
    } catch (Exception $e) {
        echo "❌ Error loading views: " . $e->getMessage();
    }
} else {
    echo "<h2>❌ Route '$path' not recognized</h2>";
}
?>
