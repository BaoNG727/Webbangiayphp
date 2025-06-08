<?php
/**
 * Nike Product Images Downloader
 * Downloads high-quality Nike product images
 */

require_once __DIR__ . '/../app/Core/Database.php';

class NikeImageDownloader
{
    private $db;
    private $uploadDir;
    
    public function __construct()
    {
        $this->db = new Database();
        $this->uploadDir = __DIR__ . '/../uploads/';
        
        // Create uploads directory if it doesn't exist
        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0755, true);
        }
    }
    
    public function downloadProductImages($limit = 50)
    {
        // Get products that need images (using category placeholders)
        $sql = "SELECT id, name, image, category FROM products 
                WHERE image LIKE 'category-%' 
                ORDER BY id ASC 
                LIMIT ?";
        
        $products = $this->db->fetchAll($sql, [$limit]);
        
        echo "<h2>Nike Product Image Downloader</h2>\n";
        echo "<p>Found " . count($products) . " products needing images</p>\n";
        echo "<div style='max-height: 500px; overflow-y: auto; border: 1px solid #ccc; padding: 10px;'>\n";
        
        $downloadCount = 0;
        
        foreach ($products as $product) {
            echo "<p>Processing: {$product['name']} (ID: {$product['id']})</p>\n";
            flush();
            
            $imageUrl = $this->findNikeImageUrl($product['name']);
            
            if ($imageUrl) {
                $filename = $this->downloadImage($imageUrl, $product['id'], $product['name']);
                
                if ($filename) {
                    // Update database with new image filename
                    $updateSql = "UPDATE products SET image = ? WHERE id = ?";
                    $this->db->query($updateSql, [$filename, $product['id']]);
                    
                    echo "<span style='color: green;'>✓ Downloaded: {$filename}</span><br>\n";
                    $downloadCount++;
                } else {
                    echo "<span style='color: red;'>✗ Failed to download image</span><br>\n";
                }
            } else {
                echo "<span style='color: orange;'>! No suitable image found</span><br>\n";
            }
            
            // Add small delay to be respectful
            usleep(500000); // 0.5 seconds
            flush();
        }
        
        echo "</div>\n";
        echo "<p><strong>Successfully downloaded {$downloadCount} images</strong></p>\n";
        
        return $downloadCount;
    }
    
    private function findNikeImageUrl($productName)
    {
        // Clean product name for search
        $searchTerm = $this->cleanProductName($productName);
        
        // Nike official product images (placeholder URLs - in real scenario you'd use Nike API or scraping)
        $sampleImages = [
            // Air Max series
            'air-max-90' => 'https://static.nike.com/a/images/t_PDP_1728_v1/f_auto,q_auto:eco/7a4ecfa0-0ef8-4a08-9a33-2b22c7ae1245/air-max-90-shoes-DnMz1L.png',
            'air-max-270' => 'https://static.nike.com/a/images/t_PDP_1728_v1/f_auto,q_auto:eco/awjogtdnqxniqqk0wpgf/air-max-270-shoes-0MFLM6.png',
            'air-max-97' => 'https://static.nike.com/a/images/t_PDP_1728_v1/f_auto,q_auto:eco/4c7e3e8a-6c15-41e6-a96c-7d5dcc6a3c52/air-max-97-shoes-z6hVLG.png',
            
            // Air Force series
            'air-force-1' => 'https://static.nike.com/a/images/t_PDP_1728_v1/f_auto,q_auto:eco/b7d9211c-26e7-431a-ac24-b0540fb3c00f/air-force-1-07-shoes-0XGfbH.png',
            
            // Running shoes
            'pegasus' => 'https://static.nike.com/a/images/t_PDP_1728_v1/f_auto,q_auto:eco/49e3e509-cd19-4c1b-ae31-c1e5cc9c6e2d/air-zoom-pegasus-40-road-running-shoes-Pz8hpj.png',
            'revolution' => 'https://static.nike.com/a/images/t_PDP_1728_v1/f_auto,q_auto:eco/2f0b1816-63b5-4a7c-8b97-8b7b1b7b1b7b/revolution-6-road-running-shoes-DnMz1L.png',
            
            // Basketball shoes
            'lebron' => 'https://static.nike.com/a/images/t_PDP_1728_v1/f_auto,q_auto:eco/3f4b2c1a-8d7e-4a5b-9c8d-7e6f5g4h3i2j/lebron-21-basketball-shoes-DnMz1L.png',
            'jordan' => 'https://static.nike.com/a/images/t_PDP_1728_v1/f_auto,q_auto:eco/a4b5c6d7-8e9f-0g1h-2i3j-4k5l6m7n8o9p/air-jordan-1-mid-shoes-DnMz1L.png',
            
            // Training shoes
            'metcon' => 'https://static.nike.com/a/images/t_PDP_1728_v1/f_auto,q_auto:eco/5c6d7e8f-9g0h-1i2j-3k4l-5m6n7o8p9q0r/metcon-9-training-shoes-DnMz1L.png',
            
            // Casual/Lifestyle
            'blazer' => 'https://static.nike.com/a/images/t_PDP_1728_v1/f_auto,q_auto:eco/7e8f9g0h-1i2j-3k4l-5m6n-7o8p9q0r1s2t/blazer-mid-77-vintage-shoes-DnMz1L.png',
            'cortez' => 'https://static.nike.com/a/images/t_PDP_1728_v1/f_auto,q_auto:eco/9g0h1i2j-3k4l-5m6n-7o8p-9q0r1s2t3u4v/classic-cortez-shoes-DnMz1L.png'
        ];
        
        // Try to match product name with available images
        foreach ($sampleImages as $key => $url) {
            if (stripos($searchTerm, str_replace('-', ' ', $key)) !== false) {
                return $url;
            }
        }
        
        // If no specific match, return a generic Nike shoe image based on category
        return $this->getGenericNikeImage($searchTerm);
    }
    
    private function getGenericNikeImage($productName)
    {
        // Generic Nike shoe images by category
        $genericImages = [
            'running' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=800&h=600&fit=crop',
            'basketball' => 'https://images.unsplash.com/photo-1552346154-21d32810aba3?w=800&h=600&fit=crop',
            'training' => 'https://images.unsplash.com/photo-1595950653106-6c9ebd614d3a?w=800&h=600&fit=crop',
            'casual' => 'https://images.unsplash.com/photo-1549298916-b41d501d3772?w=800&h=600&fit=crop',
            'tennis' => 'https://images.unsplash.com/photo-1606107557195-0e29a4b5b4aa?w=800&h=600&fit=crop'
        ];
        
        // Determine category from product name
        if (stripos($productName, 'running') !== false || stripos($productName, 'pegasus') !== false || stripos($productName, 'zoom') !== false) {
            return $genericImages['running'];
        } elseif (stripos($productName, 'basketball') !== false || stripos($productName, 'jordan') !== false || stripos($productName, 'lebron') !== false) {
            return $genericImages['basketball'];
        } elseif (stripos($productName, 'training') !== false || stripos($productName, 'metcon') !== false || stripos($productName, 'cross') !== false) {
            return $genericImages['training'];
        } elseif (stripos($productName, 'tennis') !== false || stripos($productName, 'court') !== false) {
            return $genericImages['tennis'];
        } else {
            return $genericImages['casual'];
        }
    }
    
    private function cleanProductName($name)
    {
        // Remove common prefixes and clean up
        $name = str_replace(['Nike ', 'NIKE ', 'nike '], '', $name);
        $name = strtolower($name);
        $name = preg_replace('/[^a-z0-9\s-]/', '', $name);
        return trim($name);
    }
    
    private function downloadImage($url, $productId, $productName)
    {
        try {
            // Create a safe filename
            $filename = $this->createSafeFilename($productName, $productId);
            $filepath = $this->uploadDir . $filename;
            
            // Download image using cURL
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');
            
            $imageData = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            if ($httpCode === 200 && $imageData !== false) {
                // Verify it's an image
                $imageInfo = getimagesizefromstring($imageData);
                if ($imageInfo !== false) {
                    file_put_contents($filepath, $imageData);
                    return $filename;
                }
            }
            
            return false;
            
        } catch (Exception $e) {
            error_log("Error downloading image: " . $e->getMessage());
            return false;
        }
    }
    
    private function createSafeFilename($productName, $productId)
    {
        // Clean product name for filename
        $name = strtolower($productName);
        $name = preg_replace('/[^a-z0-9\s-]/', '', $name);
        $name = trim(preg_replace('/\s+/', '-', $name), '-');
        $name = substr($name, 0, 50); // Limit length
        
        // Add product ID to ensure uniqueness
        return "nike-{$name}-{$productId}.jpg";
    }
    
    public function getStats()
    {
        $totalProducts = $this->db->fetch("SELECT COUNT(*) as count FROM products")['count'];
        $withImages = $this->db->fetch("SELECT COUNT(*) as count FROM products WHERE image NOT LIKE 'category-%'")['count'];
        $needImages = $totalProducts - $withImages;
        
        return [
            'total' => $totalProducts,
            'with_images' => $withImages,
            'need_images' => $needImages
        ];
    }
}

// Check if this is being run via web or CLI
if (isset($_GET['action'])) {
    $downloader = new NikeImageDownloader();
    
    if ($_GET['action'] === 'stats') {
        $stats = $downloader->getStats();
        echo "<h3>Image Statistics</h3>";
        echo "<ul>";
        echo "<li>Total Products: {$stats['total']}</li>";
        echo "<li>Products with real images: {$stats['with_images']}</li>";
        echo "<li>Products needing images: {$stats['need_images']}</li>";
        echo "</ul>";
        
        if ($stats['need_images'] > 0) {
            echo "<p><a href='?action=download&limit=20' class='btn btn-primary'>Download 20 Images</a></p>";
            echo "<p><a href='?action=download&limit=50' class='btn btn-primary'>Download 50 Images</a></p>";
        }
        
    } elseif ($_GET['action'] === 'download') {
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 20;
        $downloader->downloadProductImages($limit);
        
        echo "<p><a href='?action=stats'>View Updated Stats</a></p>";
    }
} else {
    // Default view
    echo "<h2>Nike Image Downloader</h2>";
    echo "<p><a href='?action=stats'>View Image Statistics</a></p>";
}
?>

<style>
body { font-family: Arial, sans-serif; margin: 20px; }
.btn { 
    display: inline-block; 
    padding: 10px 20px; 
    background: #007bff; 
    color: white; 
    text-decoration: none; 
    border-radius: 5px; 
    margin: 5px;
}
.btn:hover { background: #0056b3; }
</style>
