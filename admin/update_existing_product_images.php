<?php
require_once '../includes/config.php';
require_once '../app/Core/Database.php';

use App\Core\Database;

// Get database connection
$db = Database::getInstance()->getConnection();

echo "<h2>Update Existing Products with Images</h2>";

// Get products without images
$stmt = $db->query("SELECT id, name, image FROM products WHERE image IS NULL OR image = ''");
$productsWithoutImages = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Image mappings for existing products
$existingProductImageMappings = [
    'Nike Air Force 1' => 'nike-air-force-1.jpg',
    'Nike Air Zoom Pegasus' => 'nike-running-1.jpg',
    'Nike Court Vision Low' => 'nike-basketball-court.jpg',
    'Nike Free Run 5.0' => 'nike-running-2.jpg',
    'Nike Kyrie 8' => 'nike-basketball-1.jpg',
    'Nike LeBron 19' => 'nike-basketball-red.jpg',
    'Nike Metcon 7' => 'nike-training-1.jpg',
    'Nike SuperRep Go' => 'nike-training-gym.jpg'
];

if (count($productsWithoutImages) > 0) {
    echo "<h3>Products without images: " . count($productsWithoutImages) . "</h3>";
    
    if (isset($_POST['update_images'])) {
        $updatedCount = 0;
        
        foreach ($productsWithoutImages as $product) {
            if (isset($existingProductImageMappings[$product['name']])) {
                $imageName = $existingProductImageMappings[$product['name']];
                
                try {
                    $sql = "UPDATE products SET image = ? WHERE id = ?";
                    $stmt = $db->prepare($sql);
                    $stmt->execute([$imageName, $product['id']]);
                    
                    $updatedCount++;
                    echo "<p style='color: green;'>✓ Updated {$product['name']} with image: {$imageName}</p>";
                    
                } catch (Exception $e) {
                    echo "<p style='color: red;'>✗ Error updating {$product['name']}: " . $e->getMessage() . "</p>";
                }
            } else {
                echo "<p style='color: orange;'>⚠ No image mapping found for: {$product['name']}</p>";
            }
        }
        
        echo "<h3 style='color: green;'>Successfully updated {$updatedCount} products with images!</h3>";
    } else {
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr><th>Product Name</th><th>Suggested Image</th></tr>";
        
        foreach ($productsWithoutImages as $product) {
            $suggestedImage = $existingProductImageMappings[$product['name']] ?? 'No mapping found';
            echo "<tr>";
            echo "<td>{$product['name']}</td>";
            echo "<td>{$suggestedImage}</td>";
            echo "</tr>";
        }
        echo "</table>";
        
        echo "<br><form method='POST'>";
        echo "<button type='submit' name='update_images' style='background: #28a745; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;'>Update Existing Products with Images</button>";
        echo "</form>";
    }
} else {
    echo "<p style='color: green;'>All existing products already have images assigned.</p>";
}

echo "<br><p><a href='analyze_and_add_products.php'>← Back to Add New Products</a></p>";
echo "<p><a href='products.php'>View Products in Admin Panel</a></p>";
?>
