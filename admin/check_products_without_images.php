<?php
/**
 * Kiểm tra và xóa sản phẩm không có ảnh
 * Check and delete products without images
 */

require_once __DIR__ . '/../app/Core/Database.php';

$db = new Database();
$conn = $db->getConnection();

// Kiểm tra tình trạng sản phẩm
function checkProductStatus($conn) {
    echo "<h2>📊 Kiểm tra tình trạng sản phẩm</h2>";
    
    // Tổng số sản phẩm
    $totalSql = "SELECT COUNT(*) as total FROM products";
    $totalStmt = $conn->prepare($totalSql);
    $totalStmt->execute();
    $total = $totalStmt->fetch()['total'];
    
    // Sản phẩm không có ảnh (NULL hoặc rỗng)
    $noImageSql = "SELECT COUNT(*) as count FROM products WHERE image IS NULL OR image = ''";
    $noImageStmt = $conn->prepare($noImageSql);
    $noImageStmt->execute();
    $noImage = $noImageStmt->fetch()['count'];
    
    // Sản phẩm có ảnh placeholder
    $placeholderSql = "SELECT COUNT(*) as count FROM products WHERE image LIKE 'nike_%' OR image LIKE 'product_%'";
    $placeholderStmt = $conn->prepare($placeholderSql);
    $placeholderStmt->execute();
    $placeholder = $placeholderStmt->fetch()['count'];
    
    // Sản phẩm có ảnh thật
    $realImageSql = "SELECT COUNT(*) as count FROM products WHERE image IS NOT NULL AND image != '' AND image NOT LIKE 'nike_%' AND image NOT LIKE 'product_%'";
    $realImageStmt = $conn->prepare($realImageSql);
    $realImageStmt->execute();
    $realImage = $realImageStmt->fetch()['count'];
    
    echo "<div style='display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin: 20px 0;'>";
    
    echo "<div style='background: #e7f3ff; padding: 20px; border-radius: 8px; text-align: center;'>";
    echo "<h3 style='margin: 0; font-size: 2em; color: #007bff;'>{$total}</h3>";
    echo "<p style='margin: 5px 0;'>Tổng sản phẩm</p>";
    echo "</div>";
    
    echo "<div style='background: #f8d7da; padding: 20px; border-radius: 8px; text-align: center;'>";
    echo "<h3 style='margin: 0; font-size: 2em; color: #dc3545;'>{$noImage}</h3>";
    echo "<p style='margin: 5px 0;'>Không có ảnh</p>";
    echo "</div>";
    
    echo "<div style='background: #fff3cd; padding: 20px; border-radius: 8px; text-align: center;'>";
    echo "<h3 style='margin: 0; font-size: 2em; color: #ffc107;'>{$placeholder}</h3>";
    echo "<p style='margin: 5px 0;'>Ảnh placeholder</p>";
    echo "</div>";
    
    echo "<div style='background: #d4edda; padding: 20px; border-radius: 8px; text-align: center;'>";
    echo "<h3 style='margin: 0; font-size: 2em; color: #28a745;'>{$realImage}</h3>";
    echo "<p style='margin: 5px 0;'>Ảnh thật</p>";
    echo "</div>";
    
    echo "</div>";
    
    return [
        'total' => $total,
        'noImage' => $noImage,
        'placeholder' => $placeholder,
        'realImage' => $realImage
    ];
}

// Hiển thị mẫu sản phẩm không có ảnh
function showSampleProductsWithoutImages($conn) {
    echo "<h3>🔍 Mẫu sản phẩm không có ảnh (10 sản phẩm đầu)</h3>";
    
    $sampleSql = "SELECT id, name, category, price, image FROM products 
                  WHERE image IS NULL OR image = '' 
                  LIMIT 10";
    $sampleStmt = $conn->prepare($sampleSql);
    $sampleStmt->execute();
    $samples = $sampleStmt->fetchAll();
    
    if (empty($samples)) {
        echo "<p style='color: green;'>✅ Không có sản phẩm nào thiếu ảnh!</p>";
        return;
    }
    
    echo "<div style='background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 15px 0;'>";
    echo "<table style='width: 100%; border-collapse: collapse;'>";
    echo "<tr style='background: #dc3545; color: white;'>";
    echo "<th style='padding: 10px; border: 1px solid #ddd;'>ID</th>";
    echo "<th style='padding: 10px; border: 1px solid #ddd;'>Tên sản phẩm</th>";
    echo "<th style='padding: 10px; border: 1px solid #ddd;'>Danh mục</th>";
    echo "<th style='padding: 10px; border: 1px solid #ddd;'>Giá</th>";
    echo "<th style='padding: 10px; border: 1px solid #ddd;'>Ảnh</th>";
    echo "</tr>";
    
    foreach ($samples as $product) {
        echo "<tr>";
        echo "<td style='padding: 8px; border: 1px solid #ddd;'>{$product['id']}</td>";
        echo "<td style='padding: 8px; border: 1px solid #ddd;'>" . htmlspecialchars($product['name']) . "</td>";
        echo "<td style='padding: 8px; border: 1px solid #ddd;'>" . htmlspecialchars($product['category']) . "</td>";
        echo "<td style='padding: 8px; border: 1px solid #ddd;'>" . number_format($product['price']) . " đ</td>";
        echo "<td style='padding: 8px; border: 1px solid #ddd; color: red;'>Không có</td>";
        echo "</tr>";
    }
    
    echo "</table>";
    echo "</div>";
}

// Xóa sản phẩm không có ảnh
function deleteProductsWithoutImages($conn) {
    echo "<h2>🗑️ Đang xóa sản phẩm không có ảnh...</h2>";
    
    // Đếm trước khi xóa
    $countSql = "SELECT COUNT(*) as count FROM products WHERE image IS NULL OR image = ''";
    $countStmt = $conn->prepare($countSql);
    $countStmt->execute();
    $countToDelete = $countStmt->fetch()['count'];
    
    if ($countToDelete == 0) {
        echo "<p style='color: green;'>✅ Không có sản phẩm nào cần xóa!</p>";
        return 0;
    }
    
    // Thực hiện xóa
    $deleteSql = "DELETE FROM products WHERE image IS NULL OR image = ''";
    $deleteStmt = $conn->prepare($deleteSql);
    
    if ($deleteStmt->execute()) {
        $deletedCount = $deleteStmt->rowCount();
        echo "<div style='background: #d4edda; padding: 15px; border-radius: 5px; margin: 15px 0;'>";
        echo "<h4>✅ Xóa thành công!</h4>";
        echo "<p>Đã xóa <strong>{$deletedCount}</strong> sản phẩm không có ảnh.</p>";
        echo "</div>";
        return $deletedCount;
    } else {
        echo "<div style='background: #f8d7da; padding: 15px; border-radius: 5px; margin: 15px 0;'>";
        echo "<h4>❌ Lỗi khi xóa!</h4>";
        echo "<p>Không thể xóa sản phẩm. Vui lòng kiểm tra lại.</p>";
        echo "</div>";
        return 0;
    }
}

?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý sản phẩm không có ảnh</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            margin: 0;
            padding: 20px;
            background: #f8f9fa;
            line-height: 1.6;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 500;
            margin: 5px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .btn-danger {
            background: #dc3545;
        }
        .btn-danger:hover {
            background: #c82333;
            transform: translateY(-2px);
        }
        .btn-secondary {
            background: #6c757d;
        }
        .btn-secondary:hover {
            background: #5a6268;
        }
        .btn-primary {
            background: #007bff;
        }
        .btn-primary:hover {
            background: #0056b3;
        }
        .warning-box {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .warning-box h4 {
            color: #856404;
            margin-top: 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🗑️ Quản lý sản phẩm không có ảnh</h1>
        
        <?php
        // Kiểm tra action
        if (isset($_GET['action']) && $_GET['action'] === 'delete') {
            if (isset($_POST['confirm']) && $_POST['confirm'] === 'yes') {
                // Xác nhận xóa
                $deleted = deleteProductsWithoutImages($conn);
                echo "<p><a href='?' class='btn btn-primary'>🔄 Kiểm tra lại</a></p>";
                echo "<p><a href='../products' class='btn btn-secondary'>👀 Xem sản phẩm</a></p>";
            } else {
                // Hiển thị form xác nhận
                $stats = checkProductStatus($conn);
                
                if ($stats['noImage'] > 0) {
                    echo "<div class='warning-box'>";
                    echo "<h4>⚠️ Cảnh báo quan trọng!</h4>";
                    echo "<p>Bạn sắp xóa <strong>{$stats['noImage']}</strong> sản phẩm không có ảnh.</p>";
                    echo "<p><strong>Thao tác này không thể hoàn tác!</strong></p>";
                    echo "</div>";
                    
                    showSampleProductsWithoutImages($conn);
                    
                    echo "<form method='POST' style='text-align: center; margin: 30px 0;'>";
                    echo "<p>Bạn có chắc chắn muốn xóa <strong>{$stats['noImage']}</strong> sản phẩm này không?</p>";
                    echo "<input type='hidden' name='confirm' value='yes'>";
                    echo "<button type='submit' class='btn btn-danger'>🗑️ Có, xóa {$stats['noImage']} sản phẩm</button>";
                    echo "<a href='?' class='btn btn-secondary'>❌ Hủy bỏ</a>";
                    echo "</form>";
                } else {
                    echo "<div style='background: #d4edda; padding: 20px; border-radius: 8px;'>";
                    echo "<h3>✅ Tuyệt vời!</h3>";
                    echo "<p>Không có sản phẩm nào thiếu ảnh cần xóa.</p>";
                    echo "<a href='?' class='btn btn-primary'>🔙 Quay lại</a>";
                    echo "</div>";
                }
            }
        } else {
            // Hiển thị trang chính
            $stats = checkProductStatus($conn);
            
            if ($stats['noImage'] > 0) {
                showSampleProductsWithoutImages($conn);
                
                echo "<div style='text-align: center; margin: 30px 0;'>";
                echo "<a href='?action=delete' class='btn btn-danger'>🗑️ Xóa {$stats['noImage']} sản phẩm không có ảnh</a>";
                echo "</div>";
            }
            
            echo "<div style='text-align: center; margin: 30px 0;'>";
            echo "<a href='bulk_image_downloader.php' class='btn btn-primary'>📥 Tải ảnh cho sản phẩm</a>";
            echo "<a href='view_image_gallery.php' class='btn btn-secondary'>🖼️ Xem thư viện ảnh</a>";
            echo "<a href='../products' class='btn btn-secondary'>👀 Xem sản phẩm</a>";
            echo "</div>";
        }
        ?>
        
        <div style="text-align: center; margin-top: 40px; padding-top: 20px; border-top: 1px solid #ddd;">
            <a href="../admin" class="btn btn-secondary">🔙 Quay lại Admin</a>
        </div>
    </div>
</body>
</html>
