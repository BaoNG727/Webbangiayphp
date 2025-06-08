<?php
require_once 'app/Core/Database.php';

try {
    $db = new Database();
    
    // Get all products and their images
    $products = $db->fetchAll("SELECT id, name, image FROM products ORDER BY id");
    
    echo "=== Product Images Status ===\n\n";
    echo "Total products: " . count($products) . "\n\n";
    
    $imageCount = [];
    $missingImages = [];
    
    foreach ($products as $product) {
        $imagePath = "uploads/" . $product['image'];
        
        if (isset($imageCount[$product['image']])) {
            $imageCount[$product['image']]++;
        } else {
            $imageCount[$product['image']] = 1;
        }
        
        if (!file_exists($imagePath)) {
            $missingImages[] = [
                'id' => $product['id'],
                'name' => $product['name'],
                'image' => $product['image']
            ];
        }
    }
    
    echo "=== Image Usage Count ===\n";
    foreach ($imageCount as $image => $count) {
        echo "$image: $count products\n";
    }
    
    echo "\n=== Missing Images ===\n";
    echo "Products with missing images: " . count($missingImages) . "\n\n";
    
    if (count($missingImages) > 0) {
        echo "First 10 products with missing images:\n";
        for ($i = 0; $i < min(10, count($missingImages)); $i++) {
            $product = $missingImages[$i];
            echo "ID: {$product['id']}, Name: {$product['name']}, Image: {$product['image']}\n";
        }
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>