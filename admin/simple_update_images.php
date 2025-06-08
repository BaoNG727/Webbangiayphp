<?php
require_once '../app/Core/Database.php';

// Tạo kết nối database
$database = new Database();
$conn = $database->getConnection();

// Mapping category với hình ảnh mặc định
$categoryImages = [
    'Running' => 'category-running.jpg',
    'Basketball' => 'category-basketball.jpg', 
    'Training' => 'category-training.jpg',
    'Tennis' => 'category-casual.jpg',
    'Casual' => 'category-casual.jpg'
];

// Cập nhật hình ảnh cho các sản phẩm không có hình
$sql = "SELECT id, name, category, image FROM products WHERE image = '' OR image IS NULL OR image LIKE 'nike_%'";
$stmt = $conn->prepare($sql);
$stmt->execute();
$products = $stmt->fetchAll();

$updated = 0;

foreach ($products as $product) {
    $imageName = $categoryImages[$product['category']] ?? $categoryImages['Casual'];
    
    // Cập nhật database
    $updateSql = "UPDATE products SET image = ? WHERE id = ?";
    $updateStmt = $conn->prepare($updateSql);
    
    if ($updateStmt->execute([$imageName, $product['id']])) {
        $updated++;
        echo "✓ Cập nhật hình cho: " . $product['name'] . " -> " . $imageName . "\n";
    }
}

// Cập nhật các sản phẩm có tên phổ biến với hình ảnh cụ thể
$specificImages = [
    'Air Force' => 'nike-air-force-1.jpg',
    'Air Max 270' => 'nike-air-max-270.jpg', 
    'Revolution' => 'nike-revolution-6.jpg',
    'Blazer' => 'nike-blazer-mid.jpg'
];

foreach ($specificImages as $keyword => $imageName) {
    $updateSql = "UPDATE products SET image = ? WHERE name LIKE ? AND (image LIKE 'category-%' OR image = '')";
    $updateStmt = $conn->prepare($updateSql);
    $result = $updateStmt->execute([$imageName, '%' . $keyword . '%']);
    
    if ($updateStmt->rowCount() > 0) {
        echo "✓ Cập nhật hình ảnh đặc biệt cho sản phẩm chứa '{$keyword}': {$imageName}\n";
    }
}

echo "\nĐã cập nhật hình ảnh cho {$updated} sản phẩm.\n";
echo "Hoàn tất cập nhật hình ảnh!\n";
?>
