<?php
require_once '../includes/config.php';

echo "<!DOCTYPE html>";
echo "<html lang='vi'>";
echo "<head>";
echo "<meta charset='UTF-8'>";
echo "<meta name='viewport' content='width=device-width, initial-scale=1.0'>";
echo "<title>Nike Shoe Images Gallery</title>";
echo "<style>";
echo "body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }";
echo ".gallery { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px; margin: 20px 0; }";
echo ".image-card { background: white; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); padding: 15px; text-align: center; }";
echo ".image-card img { width: 100%; height: 200px; object-fit: cover; border-radius: 5px; margin-bottom: 10px; }";
echo ".image-name { font-weight: bold; color: #333; margin-bottom: 5px; }";
echo ".image-file { color: #666; font-size: 12px; }";
echo ".stats { background: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; }";
echo ".nav-buttons { margin: 20px 0; }";
echo ".nav-buttons a { display: inline-block; background: #007cba; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin-right: 10px; }";
echo ".nav-buttons a:hover { background: #005a87; }";
echo "</style>";
echo "</head>";
echo "<body>";

echo "<h1>🏃‍♂️ Nike Shoe Images Gallery</h1>";

// Navigation buttons
echo "<div class='nav-buttons'>";
echo "<a href='analyze_and_add_products.php'>📊 Analyze & Add Products</a>";
echo "<a href='update_existing_product_images.php'>🖼️ Update Existing Images</a>";
echo "<a href='products.php'>🛍️ Admin Products</a>";
echo "</div>";

// Get all images
$uploadsDir = '../uploads/';
$images = glob($uploadsDir . '*.{jpg,jpeg,png,gif}', GLOB_BRACE);
sort($images);

// Stats
echo "<div class='stats'>";
echo "<h3>📈 Gallery Statistics</h3>";
echo "<p><strong>Total Images:</strong> " . count($images) . "</p>";
echo "<p><strong>Upload Directory:</strong> " . realpath($uploadsDir) . "</p>";
echo "</div>";

// Image gallery
echo "<div class='gallery'>";

foreach ($images as $imagePath) {
    $imageName = basename($imagePath);
    $imageNameClean = str_replace(['-', '.jpg', '.jpeg', '.png', '.gif'], [' ', '', '', '', ''], $imageName);
    $imageNameClean = ucwords($imageNameClean);
    
    echo "<div class='image-card'>";
    
    // Check if image exists and display it
    if (file_exists($imagePath)) {
        $imageUrl = str_replace('../', '', $imagePath);
        echo "<img src='../{$imageUrl}' alt='{$imageName}' onerror=\"this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KICA8cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZGRkIi8+CiAgPHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtZmFtaWx5PSJBcmlhbCIgZm9udC1zaXplPSIxNCIgZmlsbD0iIzk5OSIgdGV4dC1hbmNob3I9Im1pZGRsZSIgZHk9Ii4zZW0iPkltYWdlIG5vdCBmb3VuZDwvdGV4dD4KICA8L3N2Zz4K'\">";
    } else {
        echo "<div style='width: 100%; height: 200px; background: #ddd; display: flex; align-items: center; justify-content: center; color: #999; border-radius: 5px;'>Image not found</div>";
    }
    
    echo "<div class='image-name'>{$imageNameClean}</div>";
    echo "<div class='image-file'>{$imageName}</div>";
    echo "</div>";
}

echo "</div>";

echo "</body>";
echo "</html>";
?>
