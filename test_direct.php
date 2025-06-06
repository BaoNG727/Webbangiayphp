<?php
/**
 * Direct Component Test - Bypasses Router
 */

// Test without routing
require_once __DIR__ . '/app/Core/Database.php';
require_once __DIR__ . '/app/Core/Model.php';
require_once __DIR__ . '/app/Models/Product.php';

try {
    echo "<h1>Direct Component Test</h1>";
    
    // Test database connection
    $db = new Database();
    echo "✅ Database connected<br>";
    
    // Test Product model
    $productModel = new Product();
    echo "✅ Product model created<br>";
    
    // Test getting products
    $products = $productModel->getFeatured(2);
    echo "✅ Featured products: " . count($products) . " found<br>";
    
    if (!empty($products)) {
        echo "<h2>Sample Products:</h2>";
        foreach ($products as $product) {
            echo "<p>• {$product['name']} - \${$product['price']}</p>";
        }
    }
    
    // Test view rendering
    $title = "Direct Test - Nike Shoe Store";
    $data = [
        'title' => $title,
        'featured_products' => $products,
        'sale_products' => $products
    ];
    
    echo "<h2>View Rendering Test:</h2>";
    echo "<div style='border: 1px solid #ccc; padding: 10px; margin: 10px 0;'>";
    
    // Extract data for view
    extract($data);
    
    // Include header
    include __DIR__ . '/app/Views/layouts/header.php';
    
    // Include home view
    include __DIR__ . '/app/Views/home/index.php';
    
    // Include footer
    include __DIR__ . '/app/Views/layouts/footer.php';
    
    echo "</div>";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "<br>";
    echo "Stack trace: " . $e->getTraceAsString();
}
?>
