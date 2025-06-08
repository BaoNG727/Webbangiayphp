<?php
/**
 * Công cụ xóa sản phẩm không có ảnh - Phiên bản đơn giản
 */

require_once __DIR__ . '/../app/Core/Database.php';

$db = new Database();
$conn = $db->getConnection();

// Xử lý xóa
if (isset($_POST['delete_products'])) {
    $deleteSql = "DELETE FROM products WHERE image IS NULL OR image = ''";
    $deleteStmt = $conn->prepare($deleteSql);
    
    if ($deleteStmt->execute()) {
        $deleted = $deleteStmt->rowCount();
        $message = "✅ Đã xóa thành công {$deleted} sản phẩm không có ảnh!";
        $messageType = "success";
    } else {
        $message = "❌ Có lỗi xảy ra khi xóa sản phẩm!";
        $messageType = "error";
    }
}

// Lấy thống kê
$totalSql = "SELECT COUNT(*) as total FROM products";
$totalStmt = $conn->prepare($totalSql);
$totalStmt->execute();
$total = $totalStmt->fetch()['total'];

$noImageSql = "SELECT COUNT(*) as count FROM products WHERE image IS NULL OR image = ''";
$noImageStmt = $conn->prepare($noImageSql);
$noImageStmt->execute();
$noImageCount = $noImageStmt->fetch()['count'];

// Lấy danh sách sản phẩm không có ảnh
$listSql = "SELECT id, name, category, price, image FROM products WHERE image IS NULL OR image = '' LIMIT 20";
$listStmt = $conn->prepare($listSql);
$listStmt->execute();
$productsWithoutImages = $listStmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xóa Sản Phẩm Không Có Ảnh</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background: #f5f5f5;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .stats {
            display: flex;
            gap: 20px;
            margin: 20px 0;
        }
        .stat-box {
            flex: 1;
            padding: 20px;
            text-align: center;
            border-radius: 8px;
            color: white;
        }
        .total { background: #007bff; }
        .no-image { background: #dc3545; }
        .btn {
            display: inline-block;
            padding: 15px 25px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
            margin: 10px 5px;
        }
        .btn-danger {
            background: #dc3545;
            color: white;
        }
        .btn-danger:hover {
            background: #c82333;
        }
        .btn-secondary {
            background: #6c757d;
            color: white;
        }
        .btn-secondary:hover {
            background: #5a6268;
        }
        .alert {
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .alert-warning {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background: #f8f9fa;
            font-weight: bold;
        }
        .delete-form {
            background: #fff3cd;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border: 2px solid #ffeaa7;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🗑️ Xóa Sản Phẩm Không Có Ảnh</h1>
        
        <?php if (isset($message)): ?>
            <div class="alert alert-<?= $messageType ?>">
                <?= $message ?>
            </div>
        <?php endif; ?>
        
        <div class="stats">
            <div class="stat-box total">
                <h3><?= $total ?></h3>
                <p>Tổng sản phẩm</p>
            </div>
            <div class="stat-box no-image">
                <h3><?= $noImageCount ?></h3>
                <p>Không có ảnh</p>
            </div>
        </div>
        
        <?php if ($noImageCount > 0): ?>
            <div class="alert alert-warning">
                <h4>⚠️ Cảnh báo!</h4>
                <p>Có <strong><?= $noImageCount ?></strong> sản phẩm không có ảnh trong cơ sở dữ liệu.</p>
            </div>
            
            <h3>📋 Danh sách sản phẩm không có ảnh (<?= min(20, $noImageCount) ?> sản phẩm đầu):</h3>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên sản phẩm</th>
                        <th>Danh mục</th>
                        <th>Giá</th>
                        <th>Ảnh</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($productsWithoutImages as $product): ?>
                        <tr>
                            <td><?= $product['id'] ?></td>
                            <td><?= htmlspecialchars($product['name']) ?></td>
                            <td><?= htmlspecialchars($product['category']) ?></td>
                            <td><?= number_format($product['price']) ?> đ</td>
                            <td style="color: red;">Không có</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
            <div class="delete-form">
                <h4>🗑️ Xóa sản phẩm không có ảnh</h4>
                <p><strong>CẢNH BÁO:</strong> Thao tác này sẽ xóa vĩnh viễn tất cả <?= $noImageCount ?> sản phẩm không có ảnh!</p>
                <p>Bạn không thể hoàn tác sau khi xóa.</p>
                
                <form method="POST" onsubmit="return confirm('Bạn có CHẮC CHẮN muốn xóa <?= $noImageCount ?> sản phẩm không có ảnh? Thao tác này KHÔNG THỂ HOÀN TÁC!');">
                    <button type="submit" name="delete_products" class="btn btn-danger">
                        🗑️ XÓA NGAY <?= $noImageCount ?> SẢN PHẨM
                    </button>
                </form>
            </div>
            
        <?php else: ?>
            <div class="alert alert-success">
                <h4>✅ Tuyệt vời!</h4>
                <p>Tất cả sản phẩm đều có ảnh. Không có sản phẩm nào cần xóa!</p>
            </div>
        <?php endif; ?>
        
        <div style="text-align: center; margin-top: 30px;">
            <a href="../products" class="btn btn-secondary">👀 Xem sản phẩm</a>
            <a href="view_image_gallery.php" class="btn btn-secondary">🖼️ Thư viện ảnh</a>
            <a href="bulk_image_downloader.php" class="btn btn-secondary">📥 Tải ảnh</a>
            <a href="../admin" class="btn btn-secondary">🔙 Quay lại Admin</a>
        </div>
    </div>

    <script>
        // Thêm hiệu ứng cho nút xóa
        document.querySelectorAll('.btn-danger').forEach(btn => {
            btn.addEventListener('mouseover', function() {
                this.style.background = '#bd2130';
                this.style.transform = 'scale(1.05)';
            });
            btn.addEventListener('mouseout', function() {
                this.style.background = '#dc3545';
                this.style.transform = 'scale(1)';
            });
        });
    </script>
</body>
</html>
