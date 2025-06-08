<?php
/**
 * Image Gallery Viewer
 * Shows all downloaded images and their assignment status
 */

require_once __DIR__ . '/../app/Core/Database.php';

$db = new Database();
$conn = $db->getConnection();
$uploadDir = __DIR__ . '/../uploads/';

// Get image statistics
$sql = "SELECT 
    COUNT(*) as total,
    SUM(CASE WHEN image LIKE 'nike_%' OR image LIKE 'product_%' THEN 1 ELSE 0 END) as placeholder,
    SUM(CASE WHEN image NOT LIKE 'nike_%' AND image NOT LIKE 'product_%' AND image IS NOT NULL AND image != '' THEN 1 ELSE 0 END) as real_image
    FROM products";
$stmt = $conn->prepare($sql);
$stmt->execute();
$stats = $stmt->fetch();

// Get available images
$uploadedImages = glob($uploadDir . '*.{jpg,jpeg,png,gif}', GLOB_BRACE);
$uploadedImages = array_map('basename', $uploadedImages);

// Get sample products
$sampleSql = "SELECT id, name, category, image FROM products ORDER BY id LIMIT 20";
$sampleStmt = $conn->prepare($sampleSql);
$sampleStmt->execute();
$sampleProducts = $sampleStmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nike Store - Image Gallery</title>
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
        }
        .header {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin: 20px 0;
        }
        .stat-card {
            background: linear-gradient(135deg, #dc3545, #c82333);
            color: white;
            padding: 25px;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3);
        }
        .stat-number {
            font-size: 2.5em;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .stat-label {
            font-size: 1.1em;
            opacity: 0.9;
        }
        .image-gallery {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            margin: 30px 0;
        }
        .image-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .image-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        .image-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        .image-info {
            padding: 15px;
        }
        .image-name {
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: #dc3545;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
            margin: 5px;
        }
        .btn:hover {
            background: #c82333;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(220, 53, 69, 0.4);
        }
        .btn-secondary {
            background: #6c757d;
        }
        .btn-secondary:hover {
            background: #5a6268;
        }
        .product-samples {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin: 30px 0;
        }
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        .product-card {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #dc3545;
        }
        .product-image {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
            float: right;
            margin-left: 15px;
        }
        .placeholder-badge {
            background: #ffc107;
            color: #212529;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.8em;
            font-weight: bold;
        }
        .real-badge {
            background: #28a745;
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.8em;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🏃‍♂️ Nike Store - Image Gallery & Status</h1>
            <p>Manage and view your product images</p>
            
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number"><?= $stats['total'] ?></div>
                    <div class="stat-label">Total Products</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?= count($uploadedImages) ?></div>
                    <div class="stat-label">Available Images</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?= $stats['placeholder'] ?></div>
                    <div class="stat-label">Need Real Images</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?= $stats['real_image'] ?></div>
                    <div class="stat-label">Real Images</div>
                </div>
            </div>
            
            <div style="text-align: center; margin-top: 30px;">
                <?php if ($stats['placeholder'] > 0): ?>
                    <a href="bulk_image_downloader.php" class="btn">🚀 Fix Product Images</a>
                <?php endif; ?>
                <a href="../products" class="btn btn-secondary">👀 View Store</a>
                <a href="simple_image_downloader.php" class="btn btn-secondary">📥 Download More Images</a>
            </div>
        </div>

        <?php if (!empty($uploadedImages)): ?>
        <div class="header">
            <h2>🖼️ Available Images (<?= count($uploadedImages) ?>)</h2>
            <div class="image-gallery">
                <?php foreach ($uploadedImages as $image): ?>
                    <div class="image-card">
                        <img src="../uploads/<?= htmlspecialchars($image) ?>" alt="<?= htmlspecialchars($image) ?>">
                        <div class="image-info">
                            <div class="image-name"><?= htmlspecialchars($image) ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <div class="product-samples">
            <h2>📦 Sample Products (First 20)</h2>
            <div class="product-grid">
                <?php foreach ($sampleProducts as $product): ?>
                    <div class="product-card">
                        <?php if ($product['image']): ?>
                            <img src="../uploads/<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="product-image">
                        <?php endif; ?>
                        
                        <h4><?= htmlspecialchars($product['name']) ?></h4>
                        <p><strong>Category:</strong> <?= htmlspecialchars($product['category']) ?></p>
                        <p><strong>Image:</strong> <?= htmlspecialchars($product['image'] ?: 'No image') ?>
                            <?php if (strpos($product['image'], 'nike_') === 0 || strpos($product['image'], 'product_') === 0): ?>
                                <span class="placeholder-badge">PLACEHOLDER</span>
                            <?php elseif ($product['image']): ?>
                                <span class="real-badge">REAL</span>
                            <?php endif; ?>
                        </p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div style="text-align: center; margin: 40px 0;">
            <a href="../admin" class="btn btn-secondary">🔙 Back to Admin</a>
        </div>
    </div>
</body>
</html>
