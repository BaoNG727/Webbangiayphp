<?php
/**
 * Simple Nike Images Downloader using Unsplash
 * Downloads free Nike-style shoe images for the product catalog
 */

require_once __DIR__ . '/../app/Core/Database.php';

set_time_limit(300); // 5 minutes

class SimpleImageDownloader
{
    private $db;
    private $uploadDir;
    
    // Collection of high-quality Nike-style shoe images from Unsplash
    private $shoeImages = [
        'nike-air-max-1.jpg' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=800&h=600&fit=crop&crop=center',
        'nike-air-force-2.jpg' => 'https://images.unsplash.com/photo-1549298916-b41d501d3772?w=800&h=600&fit=crop&crop=center',
        'nike-running-1.jpg' => 'https://images.unsplash.com/photo-1595950653106-6c9ebd614d3a?w=800&h=600&fit=crop&crop=center',
        'nike-basketball-1.jpg' => 'https://images.unsplash.com/photo-1552346154-21d32810aba3?w=800&h=600&fit=crop&crop=center',
        'nike-casual-1.jpg' => 'https://images.unsplash.com/photo-1606107557195-0e29a4b5b4aa?w=800&h=600&fit=crop&crop=center',
        'nike-white-1.jpg' => 'https://images.unsplash.com/photo-1600185365483-26d7a4cc7519?w=800&h=600&fit=crop&crop=center',
        'nike-black-1.jpg' => 'https://images.unsplash.com/photo-1597045566677-8cf032ed6634?w=800&h=600&fit=crop&crop=center',
        'nike-colorful-1.jpg' => 'https://images.unsplash.com/photo-1551107696-a4b0c5a0d9a2?w=800&h=600&fit=crop&crop=center',
        'nike-sport-1.jpg' => 'https://images.unsplash.com/photo-1608667508764-6323ad7a8804?w=800&h=600&fit=crop&crop=center',
        'nike-retro-1.jpg' => 'https://images.unsplash.com/photo-1460353581641-37baddab0fa2?w=800&h=600&fit=crop&crop=center',
        'nike-high-top-1.jpg' => 'https://images.unsplash.com/photo-1584464491033-06628f3a6b7b?w=800&h=600&fit=crop&crop=center',
        'nike-low-top-1.jpg' => 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=800&h=600&fit=crop&crop=center',
        'nike-red-1.jpg' => 'https://images.unsplash.com/photo-1604671801908-6f0c6a092c05?w=800&h=600&fit=crop&crop=center',
        'nike-blue-1.jpg' => 'https://images.unsplash.com/photo-1600269452121-4f2416e55c28?w=800&h=600&fit=crop&crop=center',
        'nike-training-1.jpg' => 'https://images.unsplash.com/photo-1605348532760-6753d2c43329?w=800&h=600&fit=crop&crop=center',
        'nike-running-2.jpg' => 'https://images.unsplash.com/photo-1556906781-9a412961c28c?w=800&h=600&fit=crop&crop=center',
        'nike-casual-2.jpg' => 'https://images.unsplash.com/photo-1525966222134-fcfa99b8ae77?w=800&h=600&fit=crop&crop=center',
        'nike-basketball-2.jpg' => 'https://images.unsplash.com/photo-1574494811066-f8e9c7d71ea6?w=800&h=600&fit=crop&crop=center',
        'nike-white-2.jpg' => 'https://images.unsplash.com/photo-1579952363873-27d3bfad9c0d?w=800&h=600&fit=crop&crop=center',
        'nike-lifestyle-1.jpg' => 'https://images.unsplash.com/photo-1603808033192-082d6919d3e1?w=800&h=600&fit=crop&crop=center'
    ];
    
    public function __construct()
    {
        $this->db = new Database();
        $this->uploadDir = __DIR__ . '/../uploads/';
        
        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0755, true);
        }
    }
    
    public function downloadAllImages()
    {
        echo "<h2>🏃‍♂️ Nike Images Downloader</h2>\n";
        echo "<p>Downloading " . count($this->shoeImages) . " Nike-style product images...</p>\n";
        echo "<div style='max-height: 400px; overflow-y: auto; border: 1px solid #ddd; padding: 15px; background: #f9f9f9;'>\n";
        
        $downloaded = 0;
        $failed = 0;
        
        foreach ($this->shoeImages as $filename => $url) {
            echo "<p>📥 Downloading: <strong>{$filename}</strong>... ";
            flush();
            
            if ($this->downloadImage($url, $filename)) {
                echo "<span style='color: green;'>✅ Success</span></p>\n";
                $downloaded++;
            } else {
                echo "<span style='color: red;'>❌ Failed</span></p>\n";
                $failed++;
            }
            
            // Small delay to be respectful
            usleep(200000); // 0.2 seconds
            flush();
        }
        
        echo "</div>\n";
        echo "<h3>📊 Download Summary</h3>\n";
        echo "<ul>";
        echo "<li>✅ Successfully downloaded: <strong>{$downloaded}</strong> images</li>";
        echo "<li>❌ Failed downloads: <strong>{$failed}</strong> images</li>";
        echo "<li>📁 Total images in uploads folder: <strong>" . count(glob($this->uploadDir . '*.{jpg,jpeg,png,gif}', GLOB_BRACE)) . "</strong></li>";
        echo "</ul>";
        
        if ($downloaded > 0) {
            echo "<div style='background: #d4edda; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
            echo "<h4>🎉 Great! Now you can assign these images to products:</h4>";
            echo "<ol>";
            echo "<li><a href='update_product_images.php' style='color: #007bff;'>🔄 Auto-assign images to products</a></li>";
            echo "<li><a href='../products' style='color: #007bff;'>👀 View products page</a></li>";
            echo "</ol>";
            echo "</div>";
        }
        
        return $downloaded;
    }
    
    private function downloadImage($url, $filename)
    {
        try {
            $filepath = $this->uploadDir . $filename;
            
            // Skip if file already exists
            if (file_exists($filepath)) {
                return true;
            }
            
            $context = stream_context_create([
                'http' => [
                    'timeout' => 30,
                    'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
                ]
            ]);
            
            $imageData = file_get_contents($url, false, $context);
            
            if ($imageData !== false) {
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
    
    public function listAvailableImages()
    {
        $files = glob($this->uploadDir . '*.{jpg,jpeg,png,gif}', GLOB_BRACE);
        $images = array_map('basename', $files);
        
        echo "<h3>📂 Available Images in Uploads Folder</h3>";
        echo "<div style='display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 15px; margin: 20px 0;'>";
        
        foreach ($images as $image) {
            $imagePath = "/Webgiay/uploads/" . $image;
            echo "<div style='border: 1px solid #ddd; border-radius: 8px; padding: 10px; text-align: center; background: white;'>";
            echo "<img src='{$imagePath}' style='width: 100%; height: 120px; object-fit: cover; border-radius: 5px; margin-bottom: 10px;' alt='{$image}'>";
            echo "<small style='display: block; word-break: break-all;'>{$image}</small>";
            echo "</div>";
        }
        
        echo "</div>";
        echo "<p><strong>Total: " . count($images) . " images</strong></p>";
    }
}

// Handle requests
if (isset($_GET['action'])) {
    $downloader = new SimpleImageDownloader();
    
    switch ($_GET['action']) {
        case 'download':
            $downloader->downloadAllImages();
            echo "<p style='margin-top: 20px;'><a href='?action=list' class='btn'>🖼️ View Downloaded Images</a></p>";
            break;
            
        case 'list':
            $downloader->listAvailableImages();
            echo "<p><a href='update_product_images.php' class='btn'>🔄 Assign Images to Products</a></p>";
            break;
            
        default:
            echo "<h2>🏃‍♂️ Nike Image Downloader</h2>";
            echo "<p>This tool will download high-quality Nike-style shoe images for your product catalog.</p>";
            echo "<p><a href='?action=download' class='btn'>📥 Download Images</a></p>";
            echo "<p><a href='?action=list' class='btn'>🖼️ View Current Images</a></p>";
    }
} else {
    echo "<h2>🏃‍♂️ Nike Image Downloader</h2>";
    echo "<p>This tool will download high-quality Nike-style shoe images for your product catalog.</p>";
    echo "<p><a href='?action=download' class='btn'>📥 Download Images</a></p>";
    echo "<p><a href='?action=list' class='btn'>🖼️ View Current Images</a></p>";
}
?>

<style>
body { 
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; 
    margin: 20px; 
    background: #f8f9fa;
    line-height: 1.6;
}
.btn { 
    display: inline-block; 
    padding: 12px 24px; 
    background: #007bff; 
    color: white; 
    text-decoration: none; 
    border-radius: 8px; 
    margin: 5px;
    font-weight: 500;
    transition: all 0.3s;
}
.btn:hover { 
    background: #0056b3; 
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0,123,255,0.3);
}
h2, h3 { color: #333; }
p { color: #666; }
</style>
