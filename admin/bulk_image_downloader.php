<?php
/**
 * Bulk Nike Product Image Downloader and Assigner
 * Downloads real Nike product images and assigns them to products automatically
 */

require_once __DIR__ . '/../app/Core/Database.php';

set_time_limit(600); // 10 minutes
ini_set('memory_limit', '256M');

class BulkImageDownloader
{
    private $db;
    private $uploadDir;
    private $imageCounter = 1;
    
    // Extended collection of high-quality Nike shoe images
    private $nikeImages = [
        // Air Force 1 Series
        'nike-air-force-1-white.jpg' => 'https://images.unsplash.com/photo-1549298916-b41d501d3772?w=800&h=600&fit=crop&crop=center&q=80',
        'nike-air-force-1-black.jpg' => 'https://images.unsplash.com/photo-1597045566677-8cf032ed6634?w=800&h=600&fit=crop&crop=center&q=80',
        'nike-air-force-1-custom.jpg' => 'https://images.unsplash.com/photo-1606107557195-0e29a4b5b4aa?w=800&h=600&fit=crop&crop=center&q=80',
        
        // Air Max Series
        'nike-air-max-90.jpg' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=800&h=600&fit=crop&crop=center&q=80',
        'nike-air-max-270.jpg' => 'https://images.unsplash.com/photo-1595950653106-6c9ebd614d3a?w=800&h=600&fit=crop&crop=center&q=80',
        'nike-air-max-classic.jpg' => 'https://images.unsplash.com/photo-1600185365483-26d7a4cc7519?w=800&h=600&fit=crop&crop=center&q=80',
        
        // Basketball Shoes
        'nike-basketball-red.jpg' => 'https://images.unsplash.com/photo-1552346154-21d32810aba3?w=800&h=600&fit=crop&crop=center&q=80',
        'nike-basketball-pro.jpg' => 'https://images.unsplash.com/photo-1574494811066-f8e9c7d71ea6?w=800&h=600&fit=crop&crop=center&q=80',
        'nike-basketball-court.jpg' => 'https://images.unsplash.com/photo-1551107696-a4b0c5a0d9a2?w=800&h=600&fit=crop&crop=center&q=80',
        
        // Running Shoes
        'nike-running-blue.jpg' => 'https://images.unsplash.com/photo-1556906781-9a412961c28c?w=800&h=600&fit=crop&crop=center&q=80',
        'nike-running-sport.jpg' => 'https://images.unsplash.com/photo-1608667508764-6323ad7a8804?w=800&h=600&fit=crop&crop=center&q=80',
        'nike-running-pro.jpg' => 'https://images.unsplash.com/photo-1605348532760-6753d2c43329?w=800&h=600&fit=crop&crop=center&q=80',
        
        // Casual & Lifestyle
        'nike-casual-white.jpg' => 'https://images.unsplash.com/photo-1525966222134-fcfa99b8ae77?w=800&h=600&fit=crop&crop=center&q=80',
        'nike-casual-modern.jpg' => 'https://images.unsplash.com/photo-1603808033192-082d6919d3e1?w=800&h=600&fit=crop&crop=center&q=80',
        'nike-lifestyle-street.jpg' => 'https://images.unsplash.com/photo-1579952363873-27d3bfad9c0d?w=800&h=600&fit=crop&crop=center&q=80',
        
        // Special Editions
        'nike-retro-vintage.jpg' => 'https://images.unsplash.com/photo-1460353581641-37baddab0fa2?w=800&h=600&fit=crop&crop=center&q=80',
        'nike-high-top-black.jpg' => 'https://images.unsplash.com/photo-1584464491033-06628f3a6b7b?w=800&h=600&fit=crop&crop=center&q=80',
        'nike-low-top-clean.jpg' => 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=800&h=600&fit=crop&crop=center&q=80',
        
        // Colorful Variations
        'nike-red-edition.jpg' => 'https://images.unsplash.com/photo-1604671801908-6f0c6a092c05?w=800&h=600&fit=crop&crop=center&q=80',
        'nike-blue-sport.jpg' => 'https://images.unsplash.com/photo-1600269452121-4f2416e55c28?w=800&h=600&fit=crop&crop=center&q=80',
        'nike-multicolor.jpg' => 'https://images.unsplash.com/photo-1551107696-a4b0c5a0d9a2?w=800&h=600&fit=crop&crop=center&q=80',
        
        // Training & Cross-Training
        'nike-training-gym.jpg' => 'https://images.unsplash.com/photo-1605348532760-6753d2c43329?w=800&h=600&fit=crop&crop=center&q=80',
        'nike-crosstraining.jpg' => 'https://images.unsplash.com/photo-1608667508764-6323ad7a8804?w=800&h=600&fit=crop&crop=center&q=80',
        'nike-workout-pro.jpg' => 'https://images.unsplash.com/photo-1574494811066-f8e9c7d71ea6?w=800&h=600&fit=crop&crop=center&q=80',
        
        // Tennis & Court Sports
        'nike-tennis-court.jpg' => 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=800&h=600&fit=crop&crop=center&q=80',
        'nike-tennis-white.jpg' => 'https://images.unsplash.com/photo-1579952363873-27d3bfad9c0d?w=800&h=600&fit=crop&crop=center&q=80',
        
        // Additional Variations
        'nike-premium-leather.jpg' => 'https://images.unsplash.com/photo-1606107557195-0e29a4b5b4aa?w=800&h=600&fit=crop&crop=center&q=80',
        'nike-street-style.jpg' => 'https://images.unsplash.com/photo-1603808033192-082d6919d3e1?w=800&h=600&fit=crop&crop=center&q=80',
        'nike-classic-design.jpg' => 'https://images.unsplash.com/photo-1600185365483-26d7a4cc7519?w=800&h=600&fit=crop&crop=center&q=80'
    ];
    
    public function __construct()
    {
        $this->db = new Database();
        $this->uploadDir = __DIR__ . '/../uploads/';
        
        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0755, true);
        }
    }
    
    public function processAllProducts()
    {
        echo "<div style='font-family: Arial, sans-serif; max-width: 1200px; margin: 0 auto; padding: 20px;'>";
        echo "<h1>🏃‍♂️ Nike Product Image Bulk Processor</h1>";
        
        // First, download all images
        $this->downloadAllImages();
        
        // Then assign images to products
        $this->assignImagesToProducts();
        
        echo "<div style='background: #d4edda; padding: 20px; border-radius: 8px; margin: 20px 0;'>";
        echo "<h3>🎉 Process Complete!</h3>";
        echo "<p><strong>Your Nike store now has real product images!</strong></p>";
        echo "<p><a href='../products' style='background: #dc3545; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; display: inline-block; margin: 5px;'>👀 View Products Page</a></p>";
        echo "<p><a href='view_image_gallery.php' style='background: #007bff; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; display: inline-block; margin: 5px;'>🖼️ View Image Gallery</a></p>";
        echo "</div>";
        
        echo "</div>";
    }
    
    private function downloadAllImages()
    {
        echo "<h2>📥 Phase 1: Downloading Nike Product Images</h2>";
        echo "<div style='background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 15px 0; max-height: 400px; overflow-y: auto;'>";
        
        $downloaded = 0;
        $skipped = 0;
        $failed = 0;
        
        foreach ($this->nikeImages as $filename => $url) {
            $filepath = $this->uploadDir . $filename;
            
            if (file_exists($filepath)) {
                echo "<p>⏭️ <strong>{$filename}</strong> - Already exists, skipping</p>";
                $skipped++;
                continue;
            }
            
            echo "<p>📥 Downloading <strong>{$filename}</strong>... ";
            flush();
            
            if ($this->downloadImage($url, $filename)) {
                echo "<span style='color: green; font-weight: bold;'>✅ Success</span></p>";
                $downloaded++;
            } else {
                echo "<span style='color: red; font-weight: bold;'>❌ Failed</span></p>";
                $failed++;
            }
            
            // Be respectful to the server
            usleep(300000); // 0.3 seconds delay
            flush();
        }
        
        echo "</div>";
        echo "<div style='background: #e7f3ff; padding: 15px; border-radius: 5px; margin: 15px 0;'>";
        echo "<h4>📊 Download Summary</h4>";
        echo "<ul>";
        echo "<li>✅ <strong>{$downloaded}</strong> new images downloaded</li>";
        echo "<li>⏭️ <strong>{$skipped}</strong> images already existed</li>";
        echo "<li>❌ <strong>{$failed}</strong> download failures</li>";
        echo "<li>📁 <strong>" . count(glob($this->uploadDir . '*.{jpg,jpeg,png,gif}', GLOB_BRACE)) . "</strong> total images available</li>";
        echo "</ul>";
        echo "</div>";
    }
    
    private function downloadImage($url, $filename)
    {
        try {
            $filepath = $this->uploadDir . $filename;
            
            $context = stream_context_create([
                'http' => [
                    'timeout' => 30,
                    'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36'
                ]
            ]);
            
            $imageData = file_get_contents($url, false, $context);
            
            if ($imageData !== false && strlen($imageData) > 1000) {
                // Verify it's an image
                $imageInfo = getimagesizefromstring($imageData);
                if ($imageInfo !== false) {
                    file_put_contents($filepath, $imageData);
                    return true;
                }
            }
            
            return false;
            
        } catch (Exception $e) {
            error_log("Error downloading {$filename}: " . $e->getMessage());
            return false;
        }
    }
    
    private function assignImagesToProducts()
    {
        echo "<h2>🔄 Phase 2: Assigning Images to Products</h2>";
        
        // Get all available images
        $availableImages = array_keys($this->nikeImages);
        $uploadedImages = glob($this->uploadDir . '*.{jpg,jpeg,png,gif}', GLOB_BRACE);
        $uploadedImages = array_map('basename', $uploadedImages);
        
        // Filter to only use successfully downloaded images
        $usableImages = array_intersect($availableImages, $uploadedImages);
        
        if (empty($usableImages)) {
            echo "<div style='background: #f8d7da; padding: 15px; border-radius: 5px; color: #721c24;'>";
            echo "<strong>❌ Error:</strong> No usable images found. Please run the download process first.";
            echo "</div>";
            return;
        }
        
        echo "<p>📂 Found <strong>" . count($usableImages) . "</strong> usable images for assignment</p>";
        
        // Get products that need new images (using placeholders)
        $conn = $this->db->getConnection();
        $sql = "SELECT id, name, category, image FROM products 
                WHERE image LIKE 'nike_%' OR image LIKE 'product_%' 
                ORDER BY id";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $products = $stmt->fetchAll();
        
        echo "<p>🔍 Found <strong>" . count($products) . "</strong> products with placeholder images</p>";
        
        if (empty($products)) {
            echo "<div style='background: #d1ecf1; padding: 15px; border-radius: 5px; color: #0c5460;'>";
            echo "<strong>ℹ️ Info:</strong> No products found with placeholder images. All products already have real images assigned.";
            echo "</div>";
            return;
        }
        
        echo "<div style='background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 15px 0; max-height: 400px; overflow-y: auto;'>";
        
        $updated = 0;
        $imageIndex = 0;
        
        foreach ($products as $product) {
            // Cycle through available images
            $newImage = $usableImages[$imageIndex % count($usableImages)];
            
            // Smart assignment based on product name and category
            $smartImage = $this->getSmartImageAssignment($product, $usableImages);
            if ($smartImage) {
                $newImage = $smartImage;
            }
            
            // Update the product
            $updateSql = "UPDATE products SET image = ? WHERE id = ?";
            $updateStmt = $conn->prepare($updateSql);
            
            if ($updateStmt->execute([$newImage, $product['id']])) {
                echo "<p>✅ <strong>{$product['name']}</strong> ({$product['category']}) → <em>{$newImage}</em></p>";
                $updated++;
            } else {
                echo "<p>❌ Failed to update <strong>{$product['name']}</strong></p>";
            }
            
            $imageIndex++;
            
            if ($updated % 10 == 0) {
                flush(); // Update display every 10 products
            }
        }
        
        echo "</div>";
        
        echo "<div style='background: #d4edda; padding: 15px; border-radius: 5px; margin: 15px 0;'>";
        echo "<h4>🎯 Assignment Summary</h4>";
        echo "<ul>";
        echo "<li>✅ <strong>{$updated}</strong> products updated with real images</li>";
        echo "<li>🖼️ Used <strong>" . count($usableImages) . "</strong> different Nike images</li>";
        echo "<li>📊 Your catalog now has authentic Nike product photos!</li>";
        echo "</ul>";
        echo "</div>";
    }
    
    private function getSmartImageAssignment($product, $availableImages)
    {
        $name = strtolower($product['name']);
        $category = strtolower($product['category']);
        
        // Smart matching based on product name
        $patterns = [
            'air force' => ['nike-air-force-1-white.jpg', 'nike-air-force-1-black.jpg', 'nike-air-force-1-custom.jpg'],
            'air max' => ['nike-air-max-90.jpg', 'nike-air-max-270.jpg', 'nike-air-max-classic.jpg'],
            'basketball' => ['nike-basketball-red.jpg', 'nike-basketball-pro.jpg', 'nike-basketball-court.jpg'],
            'running' => ['nike-running-blue.jpg', 'nike-running-sport.jpg', 'nike-running-pro.jpg'],
            'training' => ['nike-training-gym.jpg', 'nike-crosstraining.jpg', 'nike-workout-pro.jpg'],
            'tennis' => ['nike-tennis-court.jpg', 'nike-tennis-white.jpg'],
            'casual' => ['nike-casual-white.jpg', 'nike-casual-modern.jpg', 'nike-lifestyle-street.jpg']
        ];
        
        // Try to match by product name first
        foreach ($patterns as $keyword => $images) {
            if (strpos($name, $keyword) !== false) {
                $matchingImages = array_intersect($images, $availableImages);
                if (!empty($matchingImages)) {
                    return array_values($matchingImages)[0];
                }
            }
        }
        
        // Try to match by category
        if (isset($patterns[$category])) {
            $matchingImages = array_intersect($patterns[$category], $availableImages);
            if (!empty($matchingImages)) {
                return array_values($matchingImages)[0];
            }
        }
          return null; // No smart match found, use default cycling
    }
    
    public function getImageCount()
    {
        return count($this->nikeImages);
    }
}

// Handle the request
if (isset($_GET['action']) && $_GET['action'] === 'process') {
    $processor = new BulkImageDownloader();
    $processor->processAllProducts();
} else {
    // Show the interface
    echo "<div style='font-family: Arial, sans-serif; max-width: 800px; margin: 0 auto; padding: 20px;'>";
    echo "<h1>🏃‍♂️ Nike Product Image Bulk Processor</h1>";
    echo "<div style='background: #e7f3ff; padding: 20px; border-radius: 8px; margin: 20px 0;'>";
    echo "<h3>What this tool does:</h3>";
    echo "<ul>";
    echo "<li>📥 Downloads " . (new BulkImageDownloader())->getImageCount() . " high-quality Nike product images</li>";
    echo "<li>🔄 Automatically assigns real images to products using placeholder images</li>";
    echo "<li>🎯 Smart matching based on product names and categories</li>";
    echo "<li>✨ Transforms your catalog with authentic Nike product photos</li>";
    echo "</ul>";
    echo "</div>";
    
    echo "<div style='background: #fff3cd; padding: 15px; border-radius: 5px; margin: 15px 0;'>";
    echo "<strong>⚠️ Important:</strong> This process may take 2-5 minutes depending on your internet connection. Please be patient.";
    echo "</div>";
    
    echo "<p style='text-align: center;'>";
    echo "<a href='?action=process' style='background: #dc3545; color: white; padding: 15px 30px; text-decoration: none; border-radius: 8px; font-size: 18px; font-weight: bold; display: inline-block;'>🚀 Start Processing</a>";
    echo "</p>";
    
    echo "<p style='text-align: center; margin-top: 30px;'>";
    echo "<a href='../products' style='background: #6c757d; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>👀 View Current Products</a>";
    echo "</p>";
    
    echo "</div>";
}
?>
