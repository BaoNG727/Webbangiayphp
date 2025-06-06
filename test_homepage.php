<?php
/**
 * Minimal Working Example - Nike Shoe Store
 * This bypasses routing and directly loads the homepage
 */

// Start session safely
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

echo "<h1>🏠 Nike Shoe Store - Direct Homepage Test</h1>";
echo "<hr>";

// Test basic includes
echo "<h2>📋 Testing Core Components</h2>";

try {
    // Test database
    require_once 'app/Core/Database.php';
    $db = new Database();
    echo "<p>✅ Database connection successful</p>";
    
    // Test base controller
    require_once 'app/Core/Controller.php';
    echo "<p>✅ Controller class loaded</p>";
    
    // Test Product model
    require_once 'app/Core/Model.php';
    require_once 'app/Models/Product.php';
    $productModel = new Product();
    echo "<p>✅ Product model loaded</p>";
    
    // Get some test data
    $products = $productModel->getAll();
    echo "<p>✅ Products loaded: " . count($products) . " found</p>";
    
} catch (Exception $e) {
    echo "<p>❌ Error: " . $e->getMessage() . "</p>";
    echo "<p>💡 Check database setup: <a href='setup_database.php'>Setup Database</a></p>";
}

echo "<hr>";
echo "<h2>🎯 Loading Homepage Components</h2>";

// Set up data like HomeController would
$data = [
    'title' => 'Nike Shoe Store - Home',
    'featured_products' => [],
    'sale_products' => []
];

// Try to get real product data
try {
    if (isset($productModel)) {
        $data['featured_products'] = $productModel->getFeatured(4);
        $data['sale_products'] = $productModel->getSaleProducts(4);
        echo "<p>✅ Product data loaded successfully</p>";
    }
} catch (Exception $e) {
    echo "<p>⚠️ Using empty product data: " . $e->getMessage() . "</p>";
}

echo "<p>📦 Featured products: " . count($data['featured_products']) . "</p>";
echo "<p>🏷️ Sale products: " . count($data['sale_products']) . "</p>";

echo "<hr>";
echo "<h2>🖼️ Rendering Homepage</h2>";

// Include the homepage views directly
try {
    include 'app/Views/layouts/header.php';
    echo "<div style='background: #f8f9fa; padding: 20px; margin: 20px 0; border-radius: 8px;'>";
    echo "<h3>📄 Homepage Content:</h3>";
    include 'app/Views/home/index.php';
    echo "</div>";
    include 'app/Views/layouts/footer.php';
} catch (Exception $e) {
    echo "<p>❌ View error: " . $e->getMessage() . "</p>";
}

?>

<style>
body { font-family: Arial, sans-serif; margin: 20px; }
h1, h2, h3 { color: #333; }
p { margin: 8px 0; }
</style>
