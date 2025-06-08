<?php
require_once '../app/Core/Database.php';

// Tạo kết nối database
$database = new Database();
$conn = $database->getConnection();

// Tạo hình ảnh placeholder cho các sản phẩm không có hình
function createPlaceholderImage($productName, $category) {
    // Tạo một hình ảnh placeholder đơn giản
    $width = 400;
    $height = 400;
    
    // Tạo image
    $image = imagecreate($width, $height);
    
    // Định nghĩa màu sắc dựa trên category
    $colors = [
        'Running' => ['bg' => [240, 248, 255], 'text' => [25, 25, 112]],
        'Basketball' => ['bg' => [255, 140, 0], 'text' => [255, 255, 255]],
        'Training' => ['bg' => [34, 139, 34], 'text' => [255, 255, 255]],
        'Tennis' => ['bg' => [255, 255, 224], 'text' => [0, 100, 0]],
        'Casual' => ['bg' => [220, 220, 220], 'text' => [64, 64, 64]]
    ];
    
    $colorSet = $colors[$category] ?? $colors['Casual'];
    
    // Tạo màu
    $bgColor = imagecolorallocate($image, $colorSet['bg'][0], $colorSet['bg'][1], $colorSet['bg'][2]);
    $textColor = imagecolorallocate($image, $colorSet['text'][0], $colorSet['text'][1], $colorSet['text'][2]);
    
    // Fill background
    imagefill($image, 0, 0, $bgColor);
    
    // Vẽ text
    $text = "NIKE";
    $font = 5; // Built-in font
    $textWidth = imagefontwidth($font) * strlen($text);
    $textHeight = imagefontheight($font);
    
    $x = ($width - $textWidth) / 2;
    $y = ($height - $textHeight) / 2 - 20;
    
    imagestring($image, $font, $x, $y, $text, $textColor);
    
    // Vẽ category
    $categoryWidth = imagefontwidth($font) * strlen($category);
    $categoryX = ($width - $categoryWidth) / 2;
    $categoryY = $y + 30;
    
    imagestring($image, $font, $categoryX, $categoryY, $category, $textColor);
    
    return $image;
}

// Lấy tất cả sản phẩm cần tạo hình ảnh
$sql = "SELECT id, name, category, image FROM products WHERE image = '' OR image IS NULL OR image LIKE 'nike_%'";
$stmt = $conn->prepare($sql);
$stmt->execute();
$products = $stmt->fetchAll();

$created = 0;
$uploadDir = '../uploads/';

// Tạo thư mục uploads nếu chưa có
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

foreach ($products as $product) {
    $imageName = 'product_' . $product['id'] . '.jpg';
    $imagePath = $uploadDir . $imageName;
    
    // Tạo placeholder image
    $image = createPlaceholderImage($product['name'], $product['category']);
    
    // Lưu hình ảnh
    if (imagejpeg($image, $imagePath, 85)) {
        // Cập nhật database
        $updateSql = "UPDATE products SET image = ? WHERE id = ?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->execute([$imageName, $product['id']]);
        
        $created++;
        echo "✓ Tạo hình cho: " . $product['name'] . " -> " . $imageName . "\n";
    }
    
    imagedestroy($image);
}

echo "\nĐã tạo {$created} hình ảnh placeholder cho sản phẩm.\n";

// Cập nhật thêm một số hình ảnh mẫu cho các sản phẩm phổ biến
$popularProducts = [
    'Nike Air Force 1' => 'nike-air-force-1.jpg',
    'Nike Air Max 270' => 'nike-air-max-270.jpg',
    'Nike Revolution' => 'nike-revolution-6.jpg',
    'Nike Blazer' => 'nike-blazer-mid.jpg'
];

foreach ($popularProducts as $productName => $imageName) {
    if (file_exists($uploadDir . $imageName)) {
        $updateSql = "UPDATE products SET image = ? WHERE name LIKE ?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->execute([$imageName, '%' . $productName . '%']);
        echo "✓ Cập nhật hình ảnh cho sản phẩm: {$productName}\n";
    }
}

echo "\nHoàn tất cập nhật hình ảnh!\n";
?>
