<?php
require_once '../includes/config.php';
require_once '../app/Core/Database.php';

use App\Core\Database;

// Get database connection
$db = Database::getInstance()->getConnection();

// Get existing products
$existingProducts = [];
$stmt = $db->query("SELECT name, image FROM products");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $existingProducts[] = strtolower($row['name']);
}

// Get categories
$categories = [];
$stmt = $db->query("SELECT id, name FROM categories");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $categories[strtolower($row['name'])] = $row['id'];
}

// Scan uploads directory
$uploadsDir = '../uploads/';
$images = glob($uploadsDir . '*.{jpg,jpeg,png,gif}', GLOB_BRACE);

// Define product mappings based on image names
$productMappings = [
    // Air Force models
    'nike-air-force-1-black.jpg' => [
        'name' => 'Nike Air Force 1 Black',
        'description' => 'Classic Nike Air Force 1 in sleek black colorway. A timeless basketball shoe with premium leather upper.',
        'price' => 110.00,
        'sale_price' => 95.99,
        'category' => 'casual',
        'stock' => 25
    ],
    'nike-air-force-1-white.jpg' => [
        'name' => 'Nike Air Force 1 White',
        'description' => 'The iconic Nike Air Force 1 in crisp white leather. A versatile classic that goes with everything.',
        'price' => 110.00,
        'sale_price' => 95.99,
        'category' => 'casual',
        'stock' => 30
    ],
    'nike-air-force-1-custom.jpg' => [
        'name' => 'Nike Air Force 1 Custom',
        'description' => 'Personalized Nike Air Force 1 with unique design elements. Stand out from the crowd.',
        'price' => 150.00,
        'sale_price' => 129.99,
        'category' => 'casual',
        'stock' => 15
    ],
    'nike-air-force-2.jpg' => [
        'name' => 'Nike Air Force 2',
        'description' => 'The evolution of the classic Air Force 1 with enhanced comfort and modern styling.',
        'price' => 120.00,
        'sale_price' => 105.99,
        'category' => 'casual',
        'stock' => 20
    ],
    
    // Air Max models
    'nike-air-max-270.jpg' => [
        'name' => 'Nike Air Max 270',
        'description' => 'Nike\'s biggest heel Air unit to date delivers unrivaled, all-day comfort.',
        'price' => 150.00,
        'sale_price' => 129.99,
        'category' => 'casual',
        'stock' => 22
    ],
    'nike-air-max-1.jpg' => [
        'name' => 'Nike Air Max 1',
        'description' => 'The original Air Max that started it all. Retro style with revolutionary Air cushioning.',
        'price' => 140.00,
        'sale_price' => 119.99,
        'category' => 'casual',
        'stock' => 18
    ],
    'nike-air-max-classic.jpg' => [
        'name' => 'Nike Air Max Classic',
        'description' => 'Classic Air Max styling with timeless appeal and comfortable Air cushioning.',
        'price' => 130.00,
        'sale_price' => 109.99,
        'category' => 'casual',
        'stock' => 25
    ],
    
    // Basketball shoes
    'nike-basketball-1.jpg' => [
        'name' => 'Nike Basketball Pro',
        'description' => 'High-performance basketball shoe designed for court domination with superior grip and support.',
        'price' => 180.00,
        'sale_price' => 159.99,
        'category' => 'basketball',
        'stock' => 20
    ],
    'nike-basketball-court.jpg' => [
        'name' => 'Nike Court Vision',
        'description' => 'Classic basketball aesthetics with modern performance features for the court.',
        'price' => 160.00,
        'sale_price' => 139.99,
        'category' => 'basketball',
        'stock' => 18
    ],
    'nike-basketball-red.jpg' => [
        'name' => 'Nike Basketball Red Edition',
        'description' => 'Bold red basketball shoe that makes a statement on and off the court.',
        'price' => 170.00,
        'sale_price' => 149.99,
        'category' => 'basketball',
        'stock' => 15
    ],
    
    // Running shoes
    'nike-running-1.jpg' => [
        'name' => 'Nike Running Pro',
        'description' => 'Professional running shoe with advanced cushioning and breathable mesh upper.',
        'price' => 140.00,
        'sale_price' => 119.99,
        'category' => 'running',
        'stock' => 30
    ],
    'nike-running-2.jpg' => [
        'name' => 'Nike Running Elite',
        'description' => 'Elite-level running shoe for serious athletes with responsive foam technology.',
        'price' => 160.00,
        'sale_price' => 139.99,
        'category' => 'running',
        'stock' => 25
    ],
    'nike-running-blue.jpg' => [
        'name' => 'Nike Running Blue',
        'description' => 'Stylish blue running shoe combining performance with street-ready aesthetics.',
        'price' => 130.00,
        'sale_price' => 109.99,
        'category' => 'running',
        'stock' => 28
    ],
    'nike-running-pro.jpg' => [
        'name' => 'Nike Running Pro Max',
        'description' => 'Maximum performance running shoe with cutting-edge technology for serious runners.',
        'price' => 180.00,
        'sale_price' => 159.99,
        'category' => 'running',
        'stock' => 20
    ],
    'nike-revolution-6.jpg' => [
        'name' => 'Nike Revolution 6',
        'description' => 'Comfortable and affordable running shoe perfect for everyday training and casual wear.',
        'price' => 65.00,
        'sale_price' => 54.99,
        'category' => 'running',
        'stock' => 40
    ],
    
    // Training shoes
    'nike-training-1.jpg' => [
        'name' => 'Nike Training Flex',
        'description' => 'Versatile training shoe designed for multiple workout activities with flexible sole.',
        'price' => 120.00,
        'sale_price' => 99.99,
        'category' => 'training',
        'stock' => 25
    ],
    'nike-training-gym.jpg' => [
        'name' => 'Nike Gym Trainer',
        'description' => 'Stable and supportive gym training shoe perfect for weightlifting and cross-training.',
        'price' => 110.00,
        'sale_price' => 89.99,
        'category' => 'training',
        'stock' => 22
    ],
    
    // Blazer models
    'nike-blazer-mid.jpg' => [
        'name' => 'Nike Blazer Mid',
        'description' => 'Classic mid-top silhouette with vintage basketball DNA and modern street style.',
        'price' => 100.00,
        'sale_price' => 84.99,
        'category' => 'casual',
        'stock' => 25
    ],
    
    // Casual/Lifestyle
    'nike-casual-1.jpg' => [
        'name' => 'Nike Casual Comfort',
        'description' => 'Comfortable casual shoe perfect for everyday wear with classic Nike styling.',
        'price' => 90.00,
        'sale_price' => 74.99,
        'category' => 'casual',
        'stock' => 30
    ],
    'nike-casual-2.jpg' => [
        'name' => 'Nike Casual Style',
        'description' => 'Stylish casual sneaker that combines comfort with contemporary design.',
        'price' => 95.00,
        'sale_price' => 79.99,
        'category' => 'casual',
        'stock' => 28
    ],
    'nike-casual-modern.jpg' => [
        'name' => 'Nike Modern Casual',
        'description' => 'Modern interpretation of classic Nike design with updated materials and styling.',
        'price' => 105.00,
        'sale_price' => 89.99,
        'category' => 'casual',
        'stock' => 22
    ],
    'nike-casual-white.jpg' => [
        'name' => 'Nike Casual White',
        'description' => 'Clean white casual sneaker that pairs well with any outfit for effortless style.',
        'price' => 85.00,
        'sale_price' => 69.99,
        'category' => 'casual',
        'stock' => 35
    ],
    'nike-lifestyle-1.jpg' => [
        'name' => 'Nike Lifestyle',
        'description' => 'Lifestyle sneaker designed for comfort and style in everyday situations.',
        'price' => 80.00,
        'sale_price' => 64.99,
        'category' => 'casual',
        'stock' => 32
    ],
    'nike-street-style.jpg' => [
        'name' => 'Nike Street Style',
        'description' => 'Urban-inspired sneaker with street-ready aesthetics and comfortable fit.',
        'price' => 110.00,
        'sale_price' => 94.99,
        'category' => 'casual',
        'stock' => 20
    ],
    
    // High-top models
    'nike-high-top-1.jpg' => [
        'name' => 'Nike High Top Classic',
        'description' => 'Classic high-top sneaker with ankle support and timeless basketball styling.',
        'price' => 125.00,
        'sale_price' => 105.99,
        'category' => 'casual',
        'stock' => 20
    ],
    'nike-high-top-black.jpg' => [
        'name' => 'Nike High Top Black',
        'description' => 'Sleek black high-top sneaker with premium materials and classic silhouette.',
        'price' => 130.00,
        'sale_price' => 109.99,
        'category' => 'casual',
        'stock' => 18
    ],
    
    // Low-top models
    'nike-low-top-1.jpg' => [
        'name' => 'Nike Low Top Original',
        'description' => 'Classic low-top design with comfortable fit and versatile styling.',
        'price' => 95.00,
        'sale_price' => 79.99,
        'category' => 'casual',
        'stock' => 30
    ],
    'nike-low-top-clean.jpg' => [
        'name' => 'Nike Low Top Clean',
        'description' => 'Minimalist low-top sneaker with clean lines and premium construction.',
        'price' => 100.00,
        'sale_price' => 84.99,
        'category' => 'casual',
        'stock' => 25
    ],
    
    // Tennis
    'nike-tennis-court.jpg' => [
        'name' => 'Nike Court Tennis',
        'description' => 'Professional tennis shoe designed for court performance with excellent grip and support.',
        'price' => 120.00,
        'sale_price' => 99.99,
        'category' => 'training',
        'stock' => 18
    ],
    
    // Special editions
    'nike-retro-1.jpg' => [
        'name' => 'Nike Retro Classic',
        'description' => 'Vintage-inspired design with retro colorways and classic Nike heritage.',
        'price' => 115.00,
        'sale_price' => 94.99,
        'category' => 'casual',
        'stock' => 22
    ],
    'nike-retro-vintage.jpg' => [
        'name' => 'Nike Vintage Collection',
        'description' => 'Authentic vintage styling with modern comfort and quality construction.',
        'price' => 125.00,
        'sale_price' => 104.99,
        'category' => 'casual',
        'stock' => 15
    ],
    'nike-premium-leather.jpg' => [
        'name' => 'Nike Premium Leather',
        'description' => 'Luxury sneaker crafted from premium leather with sophisticated design details.',
        'price' => 160.00,
        'sale_price' => 139.99,
        'category' => 'casual',
        'stock' => 12
    ],
    'nike-classic-design.jpg' => [
        'name' => 'Nike Classic Design',
        'description' => 'Timeless Nike design that never goes out of style with comfortable fit.',
        'price' => 105.00,
        'sale_price' => 89.99,
        'category' => 'casual',
        'stock' => 25
    ],
    
    // Color variants
    'nike-black-1.jpg' => [
        'name' => 'Nike All Black',
        'description' => 'Sleek all-black sneaker perfect for versatile styling and everyday wear.',
        'price' => 100.00,
        'sale_price' => 84.99,
        'category' => 'casual',
        'stock' => 28
    ],
    'nike-white-1.jpg' => [
        'name' => 'Nike Pure White',
        'description' => 'Clean white sneaker with minimalist design and comfortable fit.',
        'price' => 95.00,
        'sale_price' => 79.99,
        'category' => 'casual',
        'stock' => 32
    ],
    'nike-red-1.jpg' => [
        'name' => 'Nike Red Classic',
        'description' => 'Bold red sneaker that makes a statement with classic Nike design elements.',
        'price' => 105.00,
        'sale_price' => 89.99,
        'category' => 'casual',
        'stock' => 20
    ],
    'nike-red-edition.jpg' => [
        'name' => 'Nike Red Special Edition',
        'description' => 'Limited edition red colorway with special design details and premium materials.',
        'price' => 130.00,
        'sale_price' => 109.99,
        'category' => 'casual',
        'stock' => 15
    ],
    'nike-blue-1.jpg' => [
        'name' => 'Nike Blue Classic',
        'description' => 'Classic blue colorway with timeless appeal and comfortable construction.',
        'price' => 100.00,
        'sale_price' => 84.99,
        'category' => 'casual',
        'stock' => 25
    ],
    'nike-blue-sport.jpg' => [
        'name' => 'Nike Blue Sport',
        'description' => 'Sport-inspired blue sneaker with performance features and athletic styling.',
        'price' => 115.00,
        'sale_price' => 94.99,
        'category' => 'training',
        'stock' => 22
    ],
    'nike-colorful-1.jpg' => [
        'name' => 'Nike Colorful Edition',
        'description' => 'Vibrant multi-color design that stands out with bold patterns and unique styling.',
        'price' => 120.00,
        'sale_price' => 99.99,
        'category' => 'casual',
        'stock' => 18
    ],
    'nike-multicolor.jpg' => [
        'name' => 'Nike Multicolor Pro',
        'description' => 'Eye-catching multicolor design with premium materials and attention to detail.',
        'price' => 125.00,
        'sale_price' => 104.99,
        'category' => 'casual',
        'stock' => 16
    ]
];

// Check which products need to be added
$newProducts = [];
$updatedProducts = [];

foreach ($images as $imagePath) {
    $imageName = basename($imagePath);
    
    if (isset($productMappings[$imageName])) {
        $productData = $productMappings[$imageName];
        $productNameLower = strtolower($productData['name']);
        
        // Check if product already exists
        if (!in_array($productNameLower, $existingProducts)) {
            $newProducts[$imageName] = $productData;
        }
    }
}

echo "<h2>Nike Store Product Analysis</h2>";
echo "<h3>Found " . count($images) . " images in uploads folder</h3>";
echo "<h3>Current products in database: " . count($existingProducts) . "</h3>";
echo "<h3>New products to add: " . count($newProducts) . "</h3>";

if (count($newProducts) > 0) {
    echo "<h4>New Products to Add:</h4>";
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr><th>Image</th><th>Product Name</th><th>Price</th><th>Category</th><th>Stock</th></tr>";
    
    foreach ($newProducts as $imageName => $productData) {
        echo "<tr>";
        echo "<td>{$imageName}</td>";
        echo "<td>{$productData['name']}</td>";
        echo "<td>\${$productData['price']}</td>";
        echo "<td>{$productData['category']}</td>";
        echo "<td>{$productData['stock']}</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Add products to database
    if (isset($_POST['add_products'])) {
        $addedCount = 0;
        
        foreach ($newProducts as $imageName => $productData) {
            try {
                $categoryId = $categories[strtolower($productData['category'])] ?? 3; // Default to Casual
                
                $sql = "INSERT INTO products (name, brand, price, sale_price, description, image, category_id, category, stock, stock_quantity) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                
                $stmt = $db->prepare($sql);
                $stmt->execute([
                    $productData['name'],
                    'Nike',
                    $productData['price'],
                    $productData['sale_price'],
                    $productData['description'],
                    $imageName,
                    $categoryId,
                    ucfirst($productData['category']),
                    $productData['stock'],
                    $productData['stock']
                ]);
                
                $addedCount++;
                echo "<p style='color: green;'>✓ Added: {$productData['name']}</p>";
                
            } catch (Exception $e) {
                echo "<p style='color: red;'>✗ Error adding {$productData['name']}: " . $e->getMessage() . "</p>";
            }
        }
        
        echo "<h3 style='color: green;'>Successfully added {$addedCount} new products!</h3>";
        echo "<p><a href='products.php'>View all products in admin panel</a></p>";
    } else {
        echo "<br><form method='POST'>";
        echo "<button type='submit' name='add_products' style='background: #007cba; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;'>Add All New Products</button>";
        echo "</form>";
    }
} else {
    echo "<p>All images already have corresponding products in the database.</p>";
}

// Show existing products and their images
echo "<h4>Current Products in Database:</h4>";
$stmt = $db->query("SELECT name, image, price, stock FROM products ORDER BY name");
echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
echo "<tr><th>Product Name</th><th>Image</th><th>Price</th><th>Stock</th></tr>";

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "<tr>";
    echo "<td>{$row['name']}</td>";
    echo "<td>" . ($row['image'] ? $row['image'] : 'No image') . "</td>";
    echo "<td>\$" . number_format($row['price'], 2) . "</td>";
    echo "<td>{$row['stock']}</td>";
    echo "</tr>";
}
echo "</table>";
?>
