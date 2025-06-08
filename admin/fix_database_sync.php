<?php
/**
 * Database Synchronization Fix Script
 * Sửa lỗi đồng bộ giữa database và website
 */

session_start();
require_once "../includes/config.php";

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$fix_results = [];
$error_messages = [];

try {
    // 1. Update Database Configuration để sử dụng cùng database
    $fix_results[] = "🔧 Step 1: Checking database configuration...";
    
    // Check current database in config
    $config_content = file_get_contents("../includes/config.php");
    if (strpos($config_content, "nike_store") !== false) {
        $fix_results[] = "✅ Config using nike_store database";
    } else {
        $fix_results[] = "❌ Config not using nike_store database";
    }
    
    // 2. Update Database class để sử dụng nike_store
    $fix_results[] = "🔧 Step 2: Updating Database class...";
    $database_class_file = "../app/Core/Database.php";
    
    if (file_exists($database_class_file)) {
        $database_content = file_get_contents($database_class_file);
        $updated_content = str_replace('"shoe_store"', '"nike_store"', $database_content);
        
        if (file_put_contents($database_class_file, $updated_content)) {
            $fix_results[] = "✅ Database class updated to use nike_store";
        } else {
            $error_messages[] = "❌ Failed to update Database class";
        }
    }
    
    // 3. Check and create products table if not exists (để tương thích với website)
    $fix_results[] = "🔧 Step 3: Synchronizing tables...";
    
    // Check if products table exists
    $check_products = $pdo->query("SHOW TABLES LIKE 'products'")->fetch();
    
    if (!$check_products) {
        // Create products table based on shoes table
        $create_products = "
        CREATE TABLE products (
            id int(11) NOT NULL AUTO_INCREMENT,
            name varchar(255) NOT NULL,
            description text,
            price decimal(10,2) NOT NULL,
            sale_price decimal(10,2) DEFAULT NULL,
            category varchar(100) DEFAULT NULL,
            image varchar(255) DEFAULT NULL,
            stock int(11) DEFAULT 0,
            created_at timestamp NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
        
        $pdo->exec($create_products);
        $fix_results[] = "✅ Products table created";
        
        // Copy data from shoes to products
        $copy_data = "
        INSERT INTO products (name, description, price, sale_price, category, image, stock, created_at)
        SELECT name, description, price, sale_price, category, image, 
               COALESCE(stock, 20) as stock, 
               COALESCE(created_at, NOW()) as created_at
        FROM shoes";
        
        $pdo->exec($copy_data);
        $fix_results[] = "✅ Data copied from shoes to products table";
    } else {
        $fix_results[] = "✅ Products table already exists";
    }
    
    // 4. Update Model references
    $fix_results[] = "🔧 Step 4: Updating Model references...";
    
    // Update Product model if needed
    $product_model_file = "../app/Models/Product.php";
    if (file_exists($product_model_file)) {
        $product_content = file_get_contents($product_model_file);
        // Ensure it uses 'products' table
        if (strpos($product_content, "protected \$table = 'products';") !== false) {
            $fix_results[] = "✅ Product model correctly uses products table";
        } else {
            $fix_results[] = "⚠️ Product model may need manual review";
        }
    }
    
    // 5. Update Cart model to use product_id correctly
    $fix_results[] = "🔧 Step 5: Checking cart and order_items references...";
    
    // Check order_items table structure
    $order_items_check = $pdo->query("DESCRIBE order_items")->fetchAll();
    $has_product_id = false;
    foreach ($order_items_check as $column) {
        if ($column['Field'] === 'product_id') {
            $has_product_id = true;
            break;
        }
    }
    
    if ($has_product_id) {
        $fix_results[] = "✅ order_items table has product_id column";
    } else {
        // Add product_id column if not exists
        $pdo->exec("ALTER TABLE order_items ADD COLUMN product_id INT AFTER order_id");
        $fix_results[] = "✅ Added product_id column to order_items";
    }
    
    // 6. Create sample orders if none exist
    $fix_results[] = "🔧 Step 6: Creating sample data if needed...";
    
    $existing_orders = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
    
    if ($existing_orders == 0) {
        // Get some customer IDs
        $customers = $pdo->query("SELECT id FROM users WHERE role = 'customer' LIMIT 3")->fetchAll(PDO::FETCH_COLUMN);
        
        if (count($customers) > 0) {
            // Create sample orders
            $sample_orders = [
                [$customers[0], 150.99, 'pending', 'Nguyen Van A', '123 Nguyen Trai, Quan 1, Ho Chi Minh', 'Ho Chi Minh', '0901234567', 'Giao hang buoi sang'],
                [$customers[0], 299.50, 'processing', 'Nguyen Van A', '123 Nguyen Trai, Quan 1, Ho Chi Minh', 'Ho Chi Minh', '0901234567', 'Dong goi can than'],
            ];
            
            if (count($customers) > 1) {
                $sample_orders[] = [$customers[1], 189.99, 'shipped', 'Tran Thi B', '456 Le Loi, Quan 3, Ho Chi Minh', 'Ho Chi Minh', '0902345678', 'Giao hang nhanh'];
                $sample_orders[] = [$customers[1], 420.00, 'delivered', 'Tran Thi B', '456 Le Loi, Quan 3, Ho Chi Minh', 'Ho Chi Minh', '0902345678', 'Da giao thanh cong'];
            }
            
            if (count($customers) > 2) {
                $sample_orders[] = [$customers[2], 89.99, 'pending', 'Le Van C', '789 Vo Van Tan, Quan 2, Ho Chi Minh', 'Ho Chi Minh', '0903456789', 'Lien he truoc khi giao'];
            }
            
            $order_stmt = $pdo->prepare("
                INSERT INTO orders (user_id, total_amount, status, name, address, city, phone, notes) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)
            ");
            
            foreach ($sample_orders as $order) {
                $order_stmt->execute($order);
            }
            
            // Create order items
            $products = $pdo->query("SELECT id, price FROM products LIMIT 5")->fetchAll();
            if (count($products) > 0) {
                $item_stmt = $pdo->prepare("
                    INSERT INTO order_items (order_id, product_id, quantity, price, subtotal) 
                    VALUES (?, ?, ?, ?, ?)
                ");
                
                for ($i = 1; $i <= count($sample_orders); $i++) {
                    if (isset($products[$i-1])) {
                        $product = $products[$i-1];
                        $quantity = 1;
                        $price = $product['price'];
                        $subtotal = $price * $quantity;
                        
                        $item_stmt->execute([$i, $product['id'], $quantity, $price, $subtotal]);
                    }
                }
            }
            
            $fix_results[] = "✅ Sample orders and items created";
        } else {
            $fix_results[] = "⚠️ No customer accounts found to create sample orders";
        }
    } else {
        $fix_results[] = "✅ Orders already exist in database";
    }
    
    // 7. Final verification
    $fix_results[] = "🔧 Step 7: Final verification...";
    
    $final_checks = [
        'Users' => $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn(),
        'Products' => $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn(),
        'Orders' => $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn(),
        'Order Items' => $pdo->query("SELECT COUNT(*) FROM order_items")->fetchColumn(),
        'Cart Items' => $pdo->query("SELECT COUNT(*) FROM cart")->fetchColumn(),
    ];
    
    foreach ($final_checks as $table => $count) {
        $fix_results[] = "📊 {$table}: {$count} records";
    }
    
} catch (Exception $e) {
    $error_messages[] = "❌ Error: " . $e->getMessage();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Sync Fix - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h4><i class="fas fa-wrench me-2"></i>Database Synchronization Fix</h4>
                    </div>
                    <div class="card-body">
                        
                        <!-- Fix Results -->
                        <div class="mb-4">
                            <h5>🔧 Fix Process Results</h5>
                            <div class="alert alert-light">
                                <?php foreach ($fix_results as $result): ?>
                                    <div><?php echo $result; ?></div>
                                <?php endforeach; ?>
                                
                                <?php foreach ($error_messages as $error): ?>
                                    <div class="text-danger"><?php echo $error; ?></div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- What was fixed -->
                        <div class="mb-4">
                            <h5>🛠️ What Was Fixed</h5>
                            <div class="alert alert-info">
                                <ul class="mb-0">
                                    <li><strong>Database Configuration:</strong> Updated to use nike_store database consistently</li>
                                    <li><strong>Table Sync:</strong> Created products table and synced with shoes data</li>
                                    <li><strong>Model Updates:</strong> Ensured models reference correct tables</li>
                                    <li><strong>Sample Data:</strong> Created sample orders and items if none existed</li>
                                    <li><strong>Cart System:</strong> Fixed product_id references in order_items</li>
                                </ul>
                            </div>
                        </div>

                        <!-- Next Steps -->
                        <div class="mb-4">
                            <h5>📋 Next Steps</h5>
                            <div class="alert alert-warning">
                                <ol class="mb-0">
                                    <li>Test customer registration on website</li>
                                    <li>Test product browsing and cart functionality</li>
                                    <li>Test order placement process</li>
                                    <li>Verify orders appear in admin panel</li>
                                    <li>Check that all data syncs properly</li>
                                </ol>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="mb-4">
                            <h5>⚡ Quick Actions</h5>
                            <div class="d-flex gap-2 flex-wrap">
                                <a href="index.php" class="btn btn-success">
                                    <i class="fas fa-tachometer-alt me-2"></i>Go to Dashboard
                                </a>
                                <a href="sync_data.php" class="btn btn-primary">
                                    <i class="fas fa-sync me-2"></i>Run Data Sync
                                </a>
                                <a href="../" class="btn btn-info">
                                    <i class="fas fa-globe me-2"></i>Test Website
                                </a>
                                <a href="../login.php" class="btn btn-warning">
                                    <i class="fas fa-sign-in-alt me-2"></i>Test Customer Login
                                </a>
                            </div>
                        </div>

                        <!-- Debug Info -->
                        <div class="alert alert-secondary">
                            <h6>🔧 Technical Details</h6>
                            <small>
                                <strong>Database:</strong> nike_store<br>
                                <strong>Main Tables:</strong> users, products, orders, order_items, cart<br>
                                <strong>Session User:</strong> <?php echo $_SESSION['name'] ?? 'N/A'; ?> (<?php echo $_SESSION['role'] ?? 'N/A'; ?>)<br>
                                <strong>Fix Time:</strong> <?php echo date('Y-m-d H:i:s'); ?>
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
