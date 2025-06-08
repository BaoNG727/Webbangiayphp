<?php
require_once '../app/Core/Database.php';

// Tạo kết nối database
$database = new Database();
$conn = $database->getConnection();

// Đếm tổng số sản phẩm
$countSql = "SELECT COUNT(*) as total FROM products";
$countStmt = $conn->prepare($countSql);
$countStmt->execute();
$totalProducts = $countStmt->fetch()['total'];

echo "Tổng số sản phẩm trong database: " . $totalProducts . "\n\n";

// Lấy một số sản phẩm mới nhất
$recentSql = "SELECT id, name, price, category, stock FROM products ORDER BY created_at DESC LIMIT 10";
$recentStmt = $conn->prepare($recentSql);
$recentStmt->execute();
$recentProducts = $recentStmt->fetchAll();

echo "10 sản phẩm mới nhất:\n";
echo "ID | Tên sản phẩm | Giá | Danh mục | Tồn kho\n";
echo str_repeat("-", 80) . "\n";

foreach ($recentProducts as $product) {
    echo sprintf("%3d | %-40s | $%6.2f | %-12s | %5d\n", 
        $product['id'], 
        substr($product['name'], 0, 40), 
        $product['price'], 
        $product['category'], 
        $product['stock']
    );
}

// Thống kê theo danh mục
echo "\n\nThống kê sản phẩm theo danh mục:\n";
$categorySql = "SELECT category, COUNT(*) as count FROM products GROUP BY category ORDER BY count DESC";
$categoryStmt = $conn->prepare($categorySql);
$categoryStmt->execute();
$categories = $categoryStmt->fetchAll();

foreach ($categories as $cat) {
    echo sprintf("%-15s: %3d sản phẩm\n", $cat['category'], $cat['count']);
}

echo "\nImport hoàn tất thành công!\n";
?>
