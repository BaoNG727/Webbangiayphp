<?php
/**
 * Working Nike Shoe Store Index
 * This version bypasses complex routing for now and goes directly to the homepage
 */

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    // Start session
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Load database and models
    require_once __DIR__ . '/app/Core/Database.php';
    require_once __DIR__ . '/app/Core/Model.php';
    require_once __DIR__ . '/app/Models/Product.php';

    // Create product model and get data
    $productModel = new Product();
    $featured_products = $productModel->getFeatured(4);
    $sale_products = $productModel->getSaleProducts(4);

    // Set up view data
    $title = 'Nike Shoe Store - Home';
    $data = [
        'title' => $title,
        'featured_products' => $featured_products,
        'sale_products' => $sale_products
    ];

    // Extract data for views
    extract($data);

    // Include views
    include __DIR__ . '/app/Views/layouts/header.php';
    include __DIR__ . '/app/Views/home/index.php';
    include __DIR__ . '/app/Views/layouts/footer.php';

} catch (Exception $e) {
    // Show error page
    $title = 'Error - Nike Shoe Store';
    $error_message = $e->getMessage();
    
    echo "<h1>Nike Shoe Store - Error</h1>";
    echo "<p>Sorry, there was an error loading the page:</p>";
    echo "<p><strong>" . htmlspecialchars($error_message) . "</strong></p>";
    echo "<p><a href='/Webgiay/setup_db.php'>Setup Database</a> | <a href='/Webgiay/debug_complete.php'>Debug System</a></p>";
}
?>
