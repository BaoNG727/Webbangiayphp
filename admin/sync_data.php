<?php
/**
 * Data Sync Script cho Admin Panel
 * Script này sẽ kiểm tra và đồng bộ dữ liệu orders
 */

session_start();
require_once "../includes/config.php";

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$sync_results = [];
$error_messages = [];

try {
    // Test database connection
    $sync_results[] = "✅ Database connection successful";
    
    // Check orders table
    $orders_count = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
    $sync_results[] = "✅ Orders table accessible - Found {$orders_count} orders";
    
    // Check users table
    $users_count = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'customer'")->fetchColumn();
    $sync_results[] = "✅ Users table accessible - Found {$users_count} customers";
    
    // Check order_items table
    $items_count = $pdo->query("SELECT COUNT(*) FROM order_items")->fetchColumn();
    $sync_results[] = "✅ Order items table accessible - Found {$items_count} items";
    
    // Check shoes table
    $shoes_count = $pdo->query("SELECT COUNT(*) FROM shoes")->fetchColumn();
    $sync_results[] = "✅ Shoes table accessible - Found {$shoes_count} products";
    
    // Get recent orders with customer info
    $recent_orders = $pdo->query("
        SELECT o.*, u.name, u.email 
        FROM orders o 
        JOIN users u ON o.user_id = u.id 
        ORDER BY o.created_at DESC 
        LIMIT 5
    ")->fetchAll(PDO::FETCH_ASSOC);
    
    $sync_results[] = "✅ Recent orders query successful - Retrieved " . count($recent_orders) . " orders";
    
    // Get order status summary
    $status_summary = $pdo->query("
        SELECT status, COUNT(*) as count 
        FROM orders 
        GROUP BY status
    ")->fetchAll(PDO::FETCH_ASSOC);
    
    $sync_results[] = "✅ Order status summary retrieved";
    
} catch (Exception $e) {
    $error_messages[] = "❌ Database error: " . $e->getMessage();
}

// Function to create sample data if needed
function createSampleData($pdo) {
    try {
        // Check if we need sample data
        $existing_orders = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
        
        if ($existing_orders == 0) {
            // Create sample customers if they don't exist
            $pdo->exec("
                INSERT IGNORE INTO users (name, email, password, role) VALUES 
                ('Nguyen Van A', 'customer@nike.com', '$2y$10\$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer'),
                ('Tran Thi B', 'customer2@nike.com', '$2y$10\$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer'),
                ('Le Van C', 'customer3@nike.com', '$2y$10\$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer')
            ");
            
            // Get customer IDs
            $customers = $pdo->query("SELECT id FROM users WHERE role = 'customer' LIMIT 3")->fetchAll(PDO::FETCH_COLUMN);
            
            if (count($customers) >= 3) {
                // Create sample orders
                $pdo->exec("
                    INSERT INTO orders (user_id, total_amount, status, name, address, city, phone, notes) VALUES 
                    ({$customers[0]}, 150.99, 'pending', 'Nguyen Van A', '123 Nguyen Trai, Quan 1', 'Ho Chi Minh', '0901234567', 'Giao hang buoi sang'),
                    ({$customers[0]}, 299.50, 'processing', 'Nguyen Van A', '123 Nguyen Trai, Quan 1', 'Ho Chi Minh', '0901234567', 'Dong goi can than'),
                    ({$customers[1]}, 189.99, 'shipped', 'Tran Thi B', '456 Le Loi, Quan 3', 'Ho Chi Minh', '0902345678', 'Giao hang nhanh'),
                    ({$customers[1]}, 420.00, 'delivered', 'Tran Thi B', '456 Le Loi, Quan 3', 'Ho Chi Minh', '0902345678', 'Da giao thanh cong'),
                    ({$customers[2]}, 89.99, 'pending', 'Le Van C', '789 Vo Van Tan, Quan 2', 'Ho Chi Minh', '0903456789', 'Lien he truoc khi giao')
                ");
                
                // Create order items
                $shoes = $pdo->query("SELECT id FROM shoes LIMIT 5")->fetchAll(PDO::FETCH_COLUMN);
                if (count($shoes) >= 5) {
                    $pdo->exec("
                        INSERT INTO order_items (order_id, shoe_id, quantity, price, subtotal) VALUES 
                        (1, {$shoes[0]}, 1, 150.99, 150.99),
                        (2, {$shoes[1]}, 1, 299.50, 299.50),
                        (3, {$shoes[2]}, 1, 189.99, 189.99),
                        (4, {$shoes[3]}, 2, 210.00, 420.00),
                        (5, {$shoes[4]}, 1, 89.99, 89.99)
                    ");
                }
                
                return "✅ Sample data created successfully";
            }
        }
        
        return "ℹ️ Sample data already exists or could not be created";
        
    } catch (Exception $e) {
        return "❌ Error creating sample data: " . $e->getMessage();
    }
}

// Handle form submission
if (isset($_POST['create_sample'])) {
    $sample_result = createSampleData($pdo);
    $sync_results[] = $sample_result;
    
    // Refresh data
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Sync - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4><i class="fas fa-sync me-2"></i>Data Sync & Diagnostic</h4>
                    </div>
                    <div class="card-body">
                        
                        <!-- Sync Results -->
                        <div class="mb-4">
                            <h5>🔍 Database Connection Status</h5>
                            <div class="alert alert-light">
                                <?php foreach ($sync_results as $result): ?>
                                    <div><?php echo $result; ?></div>
                                <?php endforeach; ?>
                                
                                <?php foreach ($error_messages as $error): ?>
                                    <div class="text-danger"><?php echo $error; ?></div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- Order Status Summary -->
                        <?php if (isset($status_summary) && count($status_summary) > 0): ?>
                            <div class="mb-4">
                                <h5>📊 Order Status Summary</h5>
                                <div class="row">
                                    <?php foreach ($status_summary as $status): ?>
                                        <div class="col-md-2">
                                            <div class="card text-center">
                                                <div class="card-body">
                                                    <h5><?php echo ucfirst($status['status']); ?></h5>
                                                    <h3 class="text-primary"><?php echo $status['count']; ?></h3>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Recent Orders -->
                        <?php if (isset($recent_orders) && count($recent_orders) > 0): ?>
                            <div class="mb-4">
                                <h5>🛒 Recent Orders</h5>
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Order ID</th>
                                                <th>Customer</th>
                                                <th>Total</th>
                                                <th>Status</th>
                                                <th>Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($recent_orders as $order): ?>
                                                <tr>
                                                    <td>#<?php echo $order['id']; ?></td>
                                                    <td><?php echo htmlspecialchars($order['name']); ?></td>
                                                    <td>$<?php echo number_format($order['total_amount'], 2); ?></td>
                                                    <td>
                                                        <span class="badge bg-<?php 
                                                            echo $order['status'] == 'pending' ? 'warning' : 
                                                                ($order['status'] == 'delivered' ? 'success' : 'info'); 
                                                        ?>">
                                                            <?php echo ucfirst($order['status']); ?>
                                                        </span>
                                                    </td>
                                                    <td><?php echo date('M d, Y', strtotime($order['created_at'])); ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Actions -->
                        <div class="mb-4">
                            <h5>⚡ Quick Actions</h5>
                            <div class="d-flex gap-2">
                                <a href="index.php" class="btn btn-success">
                                    <i class="fas fa-tachometer-alt me-2"></i>Go to Dashboard
                                </a>
                                <a href="orders.php" class="btn btn-primary">
                                    <i class="fas fa-shopping-cart me-2"></i>Manage Orders
                                </a>
                                <form method="POST" class="d-inline">
                                    <button type="submit" name="create_sample" class="btn btn-warning">
                                        <i class="fas fa-database me-2"></i>Create Sample Data
                                    </button>
                                </form>
                                <button onclick="location.reload()" class="btn btn-secondary">
                                    <i class="fas fa-refresh me-2"></i>Refresh
                                </button>
                            </div>
                        </div>

                        <!-- Debug Info -->
                        <div class="alert alert-info">
                            <h6>🔧 Debug Information</h6>
                            <small>
                                <strong>Database:</strong> nike_store<br>
                                <strong>Session User:</strong> <?php echo $_SESSION['name'] ?? 'N/A'; ?> (<?php echo $_SESSION['role'] ?? 'N/A'; ?>)<br>
                                <strong>Current Time:</strong> <?php echo date('Y-m-d H:i:s'); ?><br>
                                <strong>PHP Version:</strong> <?php echo PHP_VERSION; ?>
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
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        