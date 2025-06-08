<?php
session_start();
require_once "../includes/config.php";

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// Get counts for dashboard
$products_query = "SELECT COUNT(*) as count FROM shoes";
$products_result = mysqli_query($conn, $products_query);
$products_count = mysqli_fetch_assoc($products_result)['count'];

$orders_query = "SELECT COUNT(*) as count FROM orders";
$orders_result = mysqli_query($conn, $orders_query);
$orders_count = mysqli_fetch_assoc($orders_result)['count'];

$users_query = "SELECT COUNT(*) as count FROM users WHERE role = 'customer'";
$users_result = mysqli_query($conn, $users_query);
$users_count = mysqli_fetch_assoc($users_result)['count'];

$recent_orders_query = "SELECT o.*, u.name, u.email FROM orders o 
                         JOIN users u ON o.user_id = u.id 
                         ORDER BY o.created_at DESC LIMIT 5";
$recent_orders_result = mysqli_query($conn, $recent_orders_query);

// For low stock, we'll need to add a stock field or use a different approach for shoes
// Since shoes table doesn't have stock field, let's skip this for now
$low_stock_result = null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Nike Shoe Store</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-dark sidebar collapse">
                <div class="position-sticky pt-3">
                    <a href="index.php" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
                        <img src="../assets/images/nike-logo-white.png" alt="Nike Logo" height="30" class="me-2">
                        <span class="fs-4">Admin</span>
                    </a>
                    <hr class="text-white">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active text-white" href="index.php">
                                <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="products.php">
                                <i class="fas fa-shoe-prints me-2"></i> Products
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="orders.php">
                                <i class="fas fa-shopping-cart me-2"></i> Orders
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="users.php">
                                <i class="fas fa-users me-2"></i> Users
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="../index.php" target="_blank">
                                <i class="fas fa-external-link-alt me-2"></i> View Website
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="../logout.php">
                                <i class="fas fa-sign-out-alt me-2"></i> Logout
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
            
            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Dashboard</h1>
                    <div>
                        <span class="me-2"><?php echo date('F d, Y'); ?></span>
                        <span class="badge bg-primary"><?php echo $_SESSION['name'] ?? 'Admin'; ?></span>
                    </div>
                </div>
                
                <!-- Stats Cards -->
                <div class="row mb-4">
                    <div class="col-md-4 mb-3">
                        <div class="card text-white bg-primary">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="card-title">Products</h5>
                                        <h2 class="mb-0"><?php echo $products_count; ?></h2>
                                    </div>
                                    <i class="fas fa-shoe-prints fa-3x opacity-50"></i>
                                </div>
                                <a href="products.php" class="text-white">View all <i class="fas fa-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card text-white bg-success">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="card-title">Orders</h5>
                                        <h2 class="mb-0"><?php echo $orders_count; ?></h2>
                                    </div>
                                    <i class="fas fa-shopping-cart fa-3x opacity-50"></i>
                                </div>
                                <a href="orders.php" class="text-white">View all <i class="fas fa-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card text-white bg-info">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="card-title">Customers</h5>
                                        <h2 class="mb-0"><?php echo $users_count; ?></h2>
                                    </div>
                                    <i class="fas fa-users fa-3x opacity-50"></i>
                                </div>
                                <a href="users.php" class="text-white">View all <i class="fas fa-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <!-- Recent Orders -->
                    <div class="col-lg-8 mb-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Recent Orders</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Order ID</th>
                                                <th>Customer</th>
                                                <th>Total</th>
                                                <th>Status</th>
                                                <th>Date</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (mysqli_num_rows($recent_orders_result) > 0): ?>
                                                <?php while ($order = mysqli_fetch_assoc($recent_orders_result)): ?>
                                                    <tr>
                                                        <td>#<?php echo $order['id']; ?></td>
                                                        <td><?php echo $order['name']; ?></td>
                                                        <td>$<?php echo number_format($order['total_amount'], 2); ?></td>
                                                        <td>
                                                            <?php 
                                                            $status_class = '';
                                                            switch ($order['status']) {
                                                                case 'pending':
                                                                    $status_class = 'bg-warning';
                                                                    break;
                                                                case 'processing':
                                                                    $status_class = 'bg-info';
                                                                    break;
                                                                case 'shipped':
                                                                    $status_class = 'bg-primary';
                                                                    break;
                                                                case 'delivered':
                                                                    $status_class = 'bg-success';
                                                                    break;
                                                                case 'cancelled':
                                                                    $status_class = 'bg-danger';
                                                                    break;
                                                            }
                                                            ?>
                                                            <span class="badge <?php echo $status_class; ?>">
                                                                <?php echo ucfirst($order['status']); ?>
                                                            </span>
                                                        </td>
                                                        <td><?php echo date('M d, Y', strtotime($order['created_at'])); ?></td>
                                                        <td>
                                                            <a href="order-details.php?id=<?php echo $order['id']; ?>" class="btn btn-sm btn-info">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                <?php endwhile; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="6" class="text-center">No orders found</td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="card-footer">
                                <a href="orders.php" class="btn btn-sm btn-primary">View All Orders</a>
                            </div>
                        </div>
                    </div>
                      <!-- Low Stock Alert -->
                    <div class="col-lg-4 mb-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Product Management</h5>
                            </div>
                            <div class="card-body">
                                <div class="text-center">
                                    <i class="fas fa-shoe-prints fa-3x mb-3 text-muted"></i>
                                    <p class="text-muted">Manage your shoe inventory</p>
                                    <a href="products.php" class="btn btn-primary">View All Products</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/admin.js"></script>
</body>
</html>
