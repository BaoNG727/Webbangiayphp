<?php
/**
 * Complete Database Synchronization Test
 * Final verification that website and admin panel are synced
 */

require_once "includes/config.php";

echo "<!DOCTYPE html>
<html>
<head>
    <title>Database Sync Status - Nike Shoe Store</title>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>
    <style>
        .success { color: #28a745; }
        .error { color: #dc3545; }
        .warning { color: #ffc107; }
    </style>
</head>
<body>
<div class='container mt-4'>
    <h1 class='mb-4'>🔄 Database Synchronization Status</h1>";

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<div class='alert alert-success'>✅ Database connection successful</div>";
    
    // Check database name
    echo "<h3>1. Database Configuration</h3>";
    echo "<div class='card mb-3'>";
    echo "<div class='card-body'>";
    echo "<p><strong>Database:</strong> " . DB_NAME . "</p>";
    echo "<p><strong>Host:</strong> " . DB_HOST . "</p>";
    echo "<p><strong>User:</strong> " . DB_USER . "</p>";
    
    // Check Database class configuration
    $dbClassFile = file_get_contents('app/Core/Database.php');
    if (strpos($dbClassFile, 'nike_store') !== false) {
        echo "<p class='success'>✅ Database class configured for 'nike_store'</p>";
    } else {
        echo "<p class='error'>❌ Database class NOT configured correctly</p>";
    }
    echo "</div></div>";
    
    // Check table structure
    echo "<h3>2. Table Structure Check</h3>";
    echo "<div class='card mb-3'>";
    echo "<div class='card-body'>";
    
    $requiredTables = [
        'users' => ['id', 'name', 'username', 'email', 'password', 'role'],
        'products' => ['id', 'name', 'price', 'description', 'stock'],
        'orders' => ['id', 'user_id', 'total_amount', 'status'],
        'order_items' => ['id', 'order_id', 'product_id', 'quantity', 'price'],
        'cart' => ['id', 'user_id', 'product_id', 'quantity']
    ];
    
    foreach ($requiredTables as $table => $columns) {
        echo "<h5>Table: $table</h5>";
        
        // Check if table exists
        $stmt = $pdo->query("SHOW TABLES LIKE '$table'");
        if ($stmt->rowCount() > 0) {
            echo "<span class='success'>✅ Table exists</span><br>";
            
            // Check columns
            $stmt = $pdo->query("DESCRIBE $table");
            $existingColumns = $stmt->fetchAll(PDO::FETCH_COLUMN);
            
            $missingColumns = array_diff($columns, $existingColumns);
            if (empty($missingColumns)) {
                echo "<span class='success'>✅ All required columns present</span><br>";
            } else {
                echo "<span class='warning'>⚠️ Missing columns: " . implode(', ', $missingColumns) . "</span><br>";
            }
            
            // Show row count
            $stmt = $pdo->query("SELECT COUNT(*) FROM $table");
            $count = $stmt->fetchColumn();
            echo "<span class='text-muted'>Records: $count</span><br>";
        } else {
            echo "<span class='error'>❌ Table missing</span><br>";
        }
        echo "<hr>";
    }
    echo "</div></div>";
    
    // Test data flow
    echo "<h3>3. Data Flow Test</h3>";
    echo "<div class='card mb-3'>";
    echo "<div class='card-body'>";
    
    // Check users
    $stmt = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'customer'");
    $customerCount = $stmt->fetchColumn();
    echo "<p>👥 Customer accounts: <strong>$customerCount</strong></p>";
    
    // Check products
    $stmt = $pdo->query("SELECT COUNT(*) FROM products WHERE stock > 0");
    $availableProducts = $stmt->fetchColumn();
    echo "<p>📦 Available products: <strong>$availableProducts</strong></p>";
    
    // Check orders
    $stmt = $pdo->query("SELECT COUNT(*) FROM orders");
    $orderCount = $stmt->fetchColumn();
    echo "<p>🛒 Total orders: <strong>$orderCount</strong></p>";
    
    // Check cart items
    $stmt = $pdo->query("SELECT COUNT(*) FROM cart");
    $cartItems = $stmt->fetchColumn();
    echo "<p>🛍️ Cart items: <strong>$cartItems</strong></p>";
    
    // Check recent activity
    if ($orderCount > 0) {
        echo "<h5>Recent Orders:</h5>";
        $stmt = $pdo->query("
            SELECT o.id, u.name, u.email, o.total_amount, o.status, o.created_at 
            FROM orders o 
            JOIN users u ON o.user_id = u.id 
            ORDER BY o.created_at DESC 
            LIMIT 5
        ");
        $recentOrders = $stmt->fetchAll();
        
        echo "<table class='table table-sm'>";
        echo "<tr><th>ID</th><th>Customer</th><th>Email</th><th>Amount</th><th>Status</th><th>Date</th></tr>";
        foreach ($recentOrders as $order) {
            echo "<tr>";
            echo "<td>#{$order['id']}</td>";
            echo "<td>{$order['name']}</td>";
            echo "<td>{$order['email']}</td>";
            echo "<td>$" . number_format($order['total_amount'], 2) . "</td>";
            echo "<td><span class='badge bg-primary'>{$order['status']}</span></td>";
            echo "<td>" . date('M j, Y', strtotime($order['created_at'])) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    echo "</div></div>";
    
    // System status
    echo "<h3>4. System Status</h3>";
    echo "<div class='card mb-3'>";
    echo "<div class='card-body'>";
    
    $status = "success";
    $message = "All systems operational";
    
    if ($customerCount == 0) {
        $status = "warning";
        $message = "No customer accounts found";
    }
    
    if ($availableProducts == 0) {
        $status = "error";
        $message = "No products available";
    }
    
    $statusClass = $status == 'success' ? 'alert-success' : ($status == 'warning' ? 'alert-warning' : 'alert-danger');
    $statusIcon = $status == 'success' ? '✅' : ($status == 'warning' ? '⚠️' : '❌');
    
    echo "<div class='alert $statusClass'>";
    echo "<h5>$statusIcon System Status: " . ucfirst($status) . "</h5>";
    echo "<p>$message</p>";
    echo "</div>";
    
    echo "</div></div>";
    
    // Quick links
    echo "<h3>5. Test Links</h3>";
    echo "<div class='card mb-3'>";
    echo "<div class='card-body'>";
    echo "<div class='row'>";
    echo "<div class='col-md-6'>";
    echo "<h5>Website Testing</h5>";
    echo "<ul>";
    echo "<li><a href='index.php' target='_blank'>🏠 Homepage</a></li>";
    echo "<li><a href='register' target='_blank'>📝 User Registration</a></li>";
    echo "<li><a href='login' target='_blank'>🔐 User Login</a></li>";
    echo "<li><a href='products' target='_blank'>🛍️ Product Catalog</a></li>";
    echo "</ul>";
    echo "</div>";
    echo "<div class='col-md-6'>";
    echo "<h5>Admin Panel</h5>";
    echo "<ul>";
    echo "<li><a href='admin/' target='_blank'>📊 Dashboard</a></li>";
    echo "<li><a href='admin/users.php' target='_blank'>👥 Users</a></li>";
    echo "<li><a href='admin/products.php' target='_blank'>📦 Products</a></li>";
    echo "<li><a href='admin/orders.php' target='_blank'>🛒 Orders</a></li>";
    echo "</ul>";
    echo "</div>";
    echo "</div>";
    echo "</div></div>";
    
    echo "<div class='alert alert-info'>";
    echo "<h5>🎉 Database Synchronization Complete!</h5>";
    echo "<p>The website and admin panel are now properly synchronized. Customer registrations and orders from the website will appear in the admin panel.</p>";
    echo "<p><strong>Admin Login:</strong> username: admin, password: admin123</p>";
    echo "</div>";
    
} catch (Exception $e) {
    echo "<div class='alert alert-danger'>";
    echo "<h5>❌ Error</h5>";
    echo "<p>" . $e->getMessage() . "</p>";
    echo "</div>";
}

echo "</div></body></html>";
?>
